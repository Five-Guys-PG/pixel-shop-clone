<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2024 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

use CE\CoreXBaseXApp as App;
use CE\CoreXBaseXDocument as Document;
use CE\CoreXFilesXCSSXGlobalCSS as GlobalCSS;
use CE\CoreXFilesXCSSXPost as PostCSS;
use CE\CoreXFilesXCSSXPostPreview as PostPreview;
use CE\CoreXResponsiveXFilesXFrontend as FrontendFile;
use CE\CoreXResponsiveXResponsive as Responsive;
use CE\CoreXSettingsXManager as SettingsManager;

/**
 * Elementor frontend.
 *
 * Elementor frontend handler class is responsible for initializing Elementor in
 * the frontend.
 *
 * @since 1.0.0
 */
class Frontend extends App
{
    /**
     * The priority of the content filter.
     */
    const THE_CONTENT_FILTER_PRIORITY = 9;

    /**
     * Post ID.
     *
     * Holds the ID of the current post.
     *
     * @var int Post ID
     */
    private $post_id;

    /**
     * Fonts to enqueue
     *
     * Holds the list of fonts that are being used in the current page.
     *
     * @since 1.9.4
     *
     * @var array Used fonts. Default is an empty array
     */
    public $fonts_to_enqueue = [];

    /**
     * Registered fonts.
     *
     * Holds the list of enqueued fonts in the current page.
     *
     * @since 1.0.0
     *
     * @var array Registered fonts. Default is an empty array
     */
    private $registered_fonts = [];

    /**
     * Icon Fonts to enqueue
     *
     * Holds the list of Icon fonts that are being used in the current page.
     *
     * @since 2.4.0
     *
     * @var array Used icon fonts. Default is an empty array
     */
    private $icon_fonts_to_enqueue = [];

    /**
     * Enqueue Icon Fonts
     *
     * Holds the list of Icon fonts already enqueued  in the current page.
     *
     * @since 2.4.0
     *
     * @var array enqueued icon fonts. Default is an empty array
     */
    private $enqueued_icon_fonts = [];

    /**
     * Whether the page is using Elementor.
     *
     * Used to determine whether the current page is using Elementor.
     *
     * @since 1.0.0
     *
     * @var bool Whether Elementor is being used. Default is false
     */
    private $_has_elementor_in_page = false;

    /**
     * Whether the excerpt is being called.
     *
     * Used to determine whether the call to `the_content()` came from `get_the_excerpt()`.
     *
     * @since 1.0.0
     *
     * @var bool Whether the excerpt is being used. Default is false
     */
    private $_is_excerpt = false;

    /**
     * Filters removed from the content.
     *
     * Hold the list of filters removed from `the_content()`. Used to hold the filters that
     * conflicted with Elementor while Elementor process the content.
     *
     * @since 1.0.0
     *
     * @var array Filters removed from the content. Default is an empty array
     */
    private $content_removed_filters = [];

    // private $admin_bar_edit_documents = [];

    /**
     * @var string[]
     */
    private $body_classes = [
        'elementor-default',
    ];

    /**
     * Front End constructor.
     *
     * Initializing Elementor front end. Make sure we are not in admin, not and
     * redirect from old URL structure of Elementor editor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        // We don't need this class in admin side, but in AJAX requests.
        if (is_admin() && !wp_doing_ajax()) {
            return;
        }

        add_action('template_redirect', [$this, 'init']);
        add_action('wp_register_scripts', [$this, 'registerScripts'], 5);
        add_action('wp_register_scripts', [$this, 'registerStyles'], 5);

        $this->addContentFilter();

        // Hack to avoid enqueue post CSS while it's a `the_excerpt` call.
        // add_filter('get_the_excerpt', [$this, 'startExcerptFlag'], 1);
        // add_filter('get_the_excerpt', [$this, 'endExcerptFlag'], 20);
    }

    /**
     * Get module name.
     *
     * Retrieve the module name.
     *
     * @since 2.3.0
     *
     * @return string Module name
     */
    public function getName()
    {
        return 'frontend';
    }

    /**
     * Init.
     *
     * Initialize Elementor front end. Hooks the needed actions to run Elementor
     * in the front end, including script and style registration.
     *
     * Fired by `template_redirect` action.
     *
     * @since 1.0.0
     */
    public function init()
    {
        if (Plugin::$instance->editor->isEditMode()) {
            return;
        }

        // add_filter('body_class', [$this, 'bodyClass']);

        if (Plugin::$instance->preview->isPreviewMode()) {
            return;
        }

        // if (current_user_can('manage_options')) {
        //     Plugin::$instance->initCommon();
        // }

        $this->post_id = get_the_ID();

        // if (is_singular() && Plugin::$instance->db->isBuiltWithElementor($this->post_id)) {
        if ($this->_has_elementor_in_page) {
            add_action('wp_enqueue_scripts', [$this, 'enqueueStyles']);
        }

        // Priority 7 to allow google fonts in header template to load in <head> tag
        add_action('wp_head', [$this, 'printFontsLinks'], 7);
        add_action('wp_footer', [$this, 'wpFooter']);

        // Add Edit with the Elementor in Admin Bar.
        // add_action('admin_bar_menu', [$this, 'addMenuInAdminBar'], 200);

        // Detect Elementor documents via their css that printed before the Admin Bar.
        // add_action('elementor/css-file/post/enqueue', [$this, 'addDocumentToAdminBar']);
    }

    // public function addBodyClass($class)

    // public function bodyClass($classes = [])

    /**
     * Add content filter.
     *
     * Remove plain content and render the content generated by Elementor.
     *
     * @since 1.8.0
     */
    public function addContentFilter()
    {
        add_filter('the_content', [$this, 'applyBuilderInContent'], self::THE_CONTENT_FILTER_PRIORITY);
    }

    // public function removeContentFilter()

    /**
     * Registers scripts.
     *
     * Registers all the frontend scripts.
     *
     * Fired by `wp_enqueue_scripts` action.
     *
     * @since 1.2.1
     */
    public function registerScripts()
    {
        /*
         * Before frontend register scripts.
         *
         * Fires before Elementor frontend scripts are registered.
         *
         * @since 1.2.1
         */
        do_action('elementor/frontend/before_register_scripts');

        wp_register_script(
            'elementor-frontend-modules',
            $this->getJsAssetsUrl('frontend-modules'),
            [
                'jquery',
            ],
            _CE_VERSION_,
            true
        );

        wp_register_script(
            'flatpickr',
            $this->getJsAssetsUrl('flatpickr', 'views/lib/flatpickr/'),
            [
                'jquery',
            ],
            '4.1.4',
            true
        );
        /*
        wp_register_script(
            'imagesloaded',
            $this->getJsAssetsUrl('imagesloaded', 'views/lib/imagesloaded/'),
            [
                'jquery',
            ],
            '4.1.0',
            true
        );
        */
        wp_register_script(
            'jquery-numerator',
            $this->getJsAssetsUrl('jquery-numerator', 'views/lib/jquery-numerator/'),
            [
                'jquery',
            ],
            '0.2.1',
            true
        );

        \Configuration::get('elementor_load_swiper') && wp_register_script(
            'swiper',
            $this->getJsAssetsUrl('swiper', 'views/lib/swiper/'),
            [],
            '11.1.12',
            true
        );

        wp_register_script(
            'elementor-dialog',
            $this->getJsAssetsUrl('dialog', 'views/lib/dialog/'),
            [
                'jquery-ui-position',
            ],
            '4.7.6',
            true
        );

        wp_register_script(
            'elementor-gallery',
            $this->getJsAssetsUrl('e-gallery', 'views/lib/e-gallery/js/'),
            [
                'jquery',
            ],
            '1.1.3',
            true
        );
        /*
        wp_register_script(
            'share-link',
            $this->getJsAssetsUrl('share-link', 'views/lib/share-link/'),
            [
                'jquery',
            ],
            _CE_VERSION_,
            true
        );
        */
        wp_register_script(
            'smartmenus',
            $this->getJsAssetsUrl('jquery.smartmenus', 'views/lib/smartmenus/'),
            [
                'jquery',
            ],
            '1.2.1',
            true
        );

        \Configuration::get('elementor_load_sticky', null, null, null, 1) && wp_register_script(
            'elementor-sticky',
            $this->getJsAssetsUrl('jquery.sticky', 'views/lib/sticky/'),
            [
                'jquery',
            ],
            _CE_VERSION_,
            true
        );

        wp_register_script(
            'elementor-frontend',
            // Tmp fix for streched sections aren't resizing in editor with min.js
            $this->getJsAssetsUrl('frontend', null, isset($_REQUEST['preview_id'], ${'_GET'}['ver']) ? false : 'default'),
            [
                'elementor-frontend-modules',
                'elementor-dialog',
                'elementor-sticky',
                'swiper',
                // 'share-link',
            ],
            _CE_VERSION_,
            true
        );

        /*
         * After frontend register scripts.
         *
         * Fires after Elementor frontend scripts are registered.
         *
         * @since 1.2.1
         */
        do_action('elementor/frontend/after_register_scripts');
    }

    /**
     * Registers styles.
     *
     * Registers all the frontend styles.
     *
     * Fired by `wp_enqueue_scripts` action.
     *
     * @since 1.2.0
     */
    public function registerStyles()
    {
        /*
         * Before frontend register styles.
         *
         * Fires before Elementor frontend styles are registered.
         *
         * @since 1.2.0
         */
        do_action('elementor/frontend/before_register_styles');

        wp_register_style(
            'elementor-icons',
            $this->getCssAssetsUrl('elementor-icons', 'views/lib/eicons/css/'),
            [],
            '5.7.1'
        );

        wp_register_style(
            'ce-icons',
            $this->getCssAssetsUrl('ceicons', 'views/lib/ceicons/'),
            [],
            _CE_VERSION_
        );

        \Configuration::get('elementor_load_animate', null, null, null, 1) && wp_register_style(
            'elementor-animations',
            $this->getCssAssetsUrl('animations', 'views/lib/animations/'),
            [],
            _CE_VERSION_
        );

        \Configuration::get('elementor_load_swiper') && wp_register_style(
            'swiper',
            $this->getCssAssetsUrl('swiper', 'views/lib/swiper/css/'),
            [],
            '11.1.12'
        );

        wp_register_style(
            'flatpickr',
            $this->getCssAssetsUrl('flatpickr', 'views/lib/flatpickr/'),
            [],
            '4.1.4'
        );

        wp_register_style(
            'elementor-gallery',
            $this->getCssAssetsUrl('e-gallery', 'views/lib/e-gallery/css/'),
            [],
            '1.1.3'
        );

        $min_suffix = _PS_MODE_DEV_ ? '' : '.min';

        $direction_suffix = is_rtl() ? '-rtl' : '';

        $frontend_file_name = 'frontend' . $direction_suffix . $min_suffix . '.css';

        $has_custom_file = Responsive::hasCustomBreakpoints();

        if ($has_custom_file) {
            $id_shop = (int) \Context::getContext()->shop->id;

            $frontend_file = new FrontendFile("$id_shop-" . $frontend_file_name, Responsive::getStylesheetTemplatesPath() . $frontend_file_name);

            $time = $frontend_file->getMeta('time');

            if (!$time) {
                $frontend_file->update();
            }

            $frontend_file_url = $frontend_file->getUrl();
        } else {
            $frontend_file_url = _CE_ASSETS_URL_ . 'css/' . $frontend_file_name;
        }

        wp_register_style(
            'elementor-frontend',
            $frontend_file_url,
            [],
            $has_custom_file ? null : _CE_VERSION_
        );

        /*
         * After frontend register styles.
         *
         * Fires after Elementor frontend styles are registered.
         *
         * @since 1.2.0
         */
        do_action('elementor/frontend/after_register_styles');
    }

    /**
     * Enqueue scripts.
     *
     * Enqueue all the frontend scripts.
     *
     * @since 1.0.0
     */
    public function enqueueScripts()
    {
        /*
         * Before frontend enqueue scripts.
         *
         * Fires before Elementor frontend scripts are enqueued.
         *
         * @since 1.0.0
         */
        do_action('elementor/frontend/before_enqueue_scripts');

        $this->printConfig();

        wp_enqueue_script('elementor-frontend');

        /*
         * After frontend enqueue scripts.
         *
         * Fires after Elementor frontend scripts are enqueued.
         *
         * @since 1.0.0
         */
        do_action('elementor/frontend/after_enqueue_scripts');
    }

    /**
     * Enqueue styles.
     *
     * Enqueue all the frontend styles.
     *
     * Fired by `wp_enqueue_scripts` action.
     *
     * @since 1.0.0
     */
    public function enqueueStyles()
    {
        /*
         * Before frontend styles enqueued.
         *
         * Fires before Elementor frontend styles are enqueued.
         *
         * @since 1.0.0
         */
        do_action('elementor/frontend/before_enqueue_styles');

        wp_enqueue_style('elementor-animations');
        wp_enqueue_style('elementor-frontend');
        wp_enqueue_style('swiper');
        wp_enqueue_style('ce-icons');

        /*
         * After frontend styles enqueued.
         *
         * Fires after Elementor frontend styles are enqueued.
         *
         * @since 1.0.0
         */
        do_action('elementor/frontend/after_enqueue_styles');

        if (Plugin::$instance->preview->isPreviewMode()) {
            // Load only in preview mode
            wp_enqueue_style('elementor-icons');
        } else {
            $this->parseGlobalCssCode();

            do_action('elementor/frontend/after_enqueue_global');

            // $post_id = get_the_ID();
            // Check $post_id for virtual pages. check is singular because the $post_id is set to the first post on archive pages.
            // if ($post_id) {
            //     $css_file = PostCSS::create($post_id);
            //     $css_file->enqueue();
            // }
        }
    }

    /**
     * Elementor footer scripts and styles.
     *
     * Handle styles and scripts that are not printed in the header.
     *
     * Fired by `wp_footer` action.
     *
     * @since 1.0.11
     */
    public function wpFooter()
    {
        if (!$this->_has_elementor_in_page) {
            return;
        }

        $this->enqueueStyles();
        $this->enqueueScripts();

        // $this->printFontsLinks();
    }

    /**
     * Print fonts links.
     *
     * Enqueue all the frontend fonts by url.
     *
     * Fired by `wp_head` action.
     *
     * @since 1.9.4
     */
    public function printFontsLinks()
    {
        $google_fonts = [
            'google' => [],
            'early' => [],
        ];
        foreach ($this->fonts_to_enqueue as $font) {
            $font_type = Fonts::getFontType($font);

            switch ($font_type) {
                case Fonts::GOOGLE:
                    $google_fonts['google'][] = $font;
                    break;

                case Fonts::EARLYACCESS:
                    $google_fonts['early'][] = $font;
                    break;

                case false:
                    $this->maybeEnqueueIconFont($font);
                    break;

                default:
                    /*
                     * Print font links.
                     *
                     * Fires when Elementor frontend fonts are printed on the HEAD tag.
                     *
                     * The dynamic portion of the hook name, `$font_type`, refers to the font type.
                     *
                     * @since 2.0.0
                     *
                     * @param string $font Font name
                     */
                    do_action("elementor/fonts/print_font_links/{$font_type}", $font);
                    break;
            }
        }
        $this->fonts_to_enqueue = [];

        $this->enqueueGoogleFonts($google_fonts);
        $this->enqueueIconFonts();
    }

    public function maybeEnqueueIconFont($icon_font_type)
    {
        // if (!IconsManager::isMigrationAllowed()) {
        //     return;
        // }

        $icons_types = IconsManager::getIconManagerTabs();
        if (!isset($icons_types[$icon_font_type])) {
            return;
        }

        $icon_type = $icons_types[$icon_font_type];
        if (isset($icon_type['url'])) {
            $this->icon_fonts_to_enqueue[$icon_font_type] = [$icon_type['url']];
        }
    }

    private function enqueueIconFonts()
    {
        // if (empty($this->icon_fonts_to_enqueue) || !IconsManager::isMigrationAllowed()) {
        if (empty($this->icon_fonts_to_enqueue)) {
            return;
        }

        foreach ($this->icon_fonts_to_enqueue as $icon_type => $css_url) {
            wp_enqueue_style('elementor-icons-' . $icon_type);
            $this->enqueued_icon_fonts[] = $css_url;
        }

        // clear enqueued icons
        $this->icon_fonts_to_enqueue = [];
    }

    /**
     * Print Google fonts.
     *
     * Enqueue all the frontend Google fonts.
     *
     * Fired by `wp_head` action.
     *
     * @since 1.0.0
     *
     * @param array $google_fonts Optional. Google fonts to print in the frontend
     *                            Default is an empty array.
     */
    private function enqueueGoogleFonts($google_fonts = [])
    {
        static $google_fonts_index = 0;

        $print_google_fonts = true;

        /*
         * Print frontend google fonts.
         *
         * Filters whether to enqueue Google fonts in the frontend.
         *
         * @since 1.0.0
         *
         * @param bool $print_google_fonts Whether to enqueue Google fonts. Default is true
         */
        $print_google_fonts = apply_filters('elementor/frontend/print_google_fonts', $print_google_fonts);

        if (!$print_google_fonts) {
            return;
        }

        // Print used fonts
        if (!empty($google_fonts['google'])) {
            ++$google_fonts_index;

            foreach ($google_fonts['google'] as &$font) {
                $font = str_replace(' ', '+', $font) . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
            }

            $fonts_url = sprintf('https://fonts.googleapis.com/css?family=%s&display=swap', implode(rawurlencode('|'), $google_fonts['google']));

            $subsets = [
                'ru' => 'cyrillic',
                'bg' => 'cyrillic',
                'he' => 'hebrew',
                'el' => 'greek',
                'vi' => 'vietnamese',
                'uk' => 'cyrillic',
                'cs' => 'latin-ext',
                'ro' => 'latin-ext',
                'pl' => 'latin-ext',
            ];
            $locale = get_locale();

            if (isset($subsets[$locale])) {
                $fonts_url .= '&subset=' . $subsets[$locale];
            }

            wp_enqueue_style('google-fonts-' . $google_fonts_index, $fonts_url);
        }

        if (!empty($google_fonts['early'])) {
            foreach ($google_fonts['early'] as $current_font) {
                ++$google_fonts_index;

                $font_url = sprintf('https://fonts.googleapis.com/earlyaccess/%s.css', strtolower(str_replace(' ', '', $current_font)));

                wp_enqueue_style('google-earlyaccess-' . $google_fonts_index, $font_url);
            }
        }
    }

    /**
     * Enqueue fonts.
     *
     * Enqueue all the frontend fonts.
     *
     * @since 1.2.0
     *
     * @param array $font Fonts to enqueue in the frontend
     */
    public function enqueueFont($font)
    {
        if (in_array($font, $this->registered_fonts)) {
            return;
        }

        $this->fonts_to_enqueue[] = $font;
        $this->registered_fonts[] = $font;
    }

    /**
     * Parse global CSS.
     *
     * Enqueue the global CSS file.
     *
     * @since 1.2.0
     */
    protected function parseGlobalCssCode()
    {
        $id_shop = (int) \Context::getContext()->shop->id;

        $scheme_css_file = GlobalCSS::create("$id_shop-global.css");
        $scheme_css_file->enqueue();
    }

    /**
     * Apply builder in content.
     *
     * Used to apply the Elementor page editor on the post content.
     *
     * @since 1.0.0
     *
     * @param string $content The post content
     *
     * @return string The post content
     */
    public function applyBuilderInContent($content)
    {
        // $this->restoreContentFilters();
        $post_id = UId::$_ID;

        if (\CreativeElements::getPreviewUId() == $post_id || $this->_is_excerpt) {
            return $content;
        }

        // Remove the filter itself in order to allow other `the_content` in the elements
        // $this->removeContentFilter();

        $builder_content = $this->getBuilderContent($post_id);

        if ($builder_content) {
            $content = $builder_content;
            // $this->removeContentFilters();
        }

        // Add the filter again for other `the_content` calls
        // $this->addContentFilter();

        return $content;
    }

    /**
     * Retrieve builder content.
     *
     * Used to render and return the post content with all the Elementor elements.
     *
     * Note that this method is an internal method, please use `getBuilderContentForDisplay()`.
     *
     * @since 1.0.0
     *
     * @param int $post_id The post ID
     * @param bool $with_css Optional. Whether to retrieve the content with CSS or not. Default is false
     *
     * @return string The post content
     */
    public function getBuilderContent($post_id, $with_css = false)
    {
        // if (post_password_required($post_id)) {
        //     return '';
        // }

        if (!Plugin::$instance->db->isBuiltWithElementor($post_id)) {
            return '';
        }

        $document = Plugin::$instance->documents->getDocForFrontend($post_id);

        // Change the current post, so widgets can use `documents->get_current`.
        Plugin::$instance->documents->switchToDocument($document);

        $data = $document->getElementsData();

        // $data = apply_filters('elementor/frontend/builder_content_data', $data, $post_id);

        if (!$data) {
            return '';
        }

        if (!$this->_is_excerpt) {
            if ($document->isAutosave()) {
                $css_file = PostPreview::create($document->getPost()->ID);
            } else {
                $css_file = PostCSS::create($post_id);
            }

            $css_file->enqueue();
        }

        ob_start();

        // Handle JS and Customizer requests, with CSS inline.
        if (is_customize_preview() || wp_doing_ajax()) {
            $with_css = true;
        }

        if (!empty($css_file) && $with_css) {
            $css_file->printCss();
        }

        $document->printElementsWithWrapper($data);

        $content = ob_get_clean();

        // $content = $this->processMoreTag($content);

        // $content = apply_filters('elementor/frontend/the_content', $content);

        if ($content) {
            $this->_has_elementor_in_page = true;
        }

        Plugin::$instance->documents->restoreDocument();

        return $content;
    }

    // public function addDocumentToAdminBar($css_file)

    // public function addMenuInAdminBar(\WPAdminBar $wp_admin_bar)

    /**
     * Retrieve builder content for display.
     *
     * Used to render and return the post content with all the Elementor elements.
     *
     * @since 1.0.0
     *
     * @param int $post_id The post ID
     * @param bool $with_css Optional. Whether to retrieve the content with CSS
     *                       or not. Default is false.
     *
     * @return string The post content
     */
    public function getBuilderContentForDisplay($post_id, $with_css = false)
    {
        // if (!get_post($post_id)) {
        if (!($post = get_post($post_id)) || 'publish' !== $post->post_status) {
            return '';
        }

        $editor = Plugin::$instance->editor;

        // Avoid recursion
        // if (get_the_ID() === (int) $post_id) {
        if (get_the_ID() == $post_id) {
            $content = '';
            if ($editor->isEditMode()) {
                $content = '<div class="elementor-alert elementor-alert-danger">' . __(
                    'Invalid Data: The Template ID cannot be the same as the currently edited template. ' .
                    'Please choose a different one.'
                ) . '</div>';
            }

            return $content;
        }

        // Set edit mode as false, so don't render settings and etc. use the $is_edit_mode to indicate if we need the CSS inline
        $is_edit_mode = $editor->isEditMode();
        $editor->setEditMode(false);

        $with_css = $with_css ? true : $is_edit_mode;

        $content = $this->getBuilderContent($post_id, $with_css);

        // Restore edit mode state
        Plugin::$instance->editor->setEditMode($is_edit_mode);

        return $content;
    }

    // public function startExcerptFlag($excerpt)

    // public function endExcerptFlag($excerpt)

    // public function removeContentFilters();

    /**
     * Has Elementor In Page
     *
     * Determine whether the current page is using Elementor.
     *
     * @since 2.0.9
     *
     * @return bool|null
     */
    public function hasElementorInPage($value = null)
    {
        if (null === $value) {
            // getter
            return $this->_has_elementor_in_page;
        } else {
            // setter
            $this->_has_elementor_in_page = (bool) $value;
        }
    }

    public function createActionHash($action, array $settings = [])
    {
        $args = array_filter($settings);

        return "#ce-action=$action" . ($args ? '&' . http_build_query($args) : '');
    }

    /**
     * Get Init Settings
     *
     * Used to define the default/initial settings of the object. Inheriting classes may implement this method to define
     * their own default/initial settings.
     *
     * @since 2.3.0
     *
     * @return array
     */
    protected function getInitSettings()
    {
        $is_preview_mode = Plugin::$instance->preview->isPreviewMode(Plugin::$instance->preview->getPostId());

        $settings = [
            'environmentMode' => [
                'edit' => $is_preview_mode,
                'wpPreview' => is_preview(),
            ],
            'is_rtl' => is_rtl(),
            'breakpoints' => Responsive::getBreakpoints(),
            'version' => _CE_VERSION_,
            'urls' => [
                'assets' => _MODULE_DIR_ . 'creativeelements/views/',
            ],
            'productQuickView' => (int) \Configuration::get('CE_PRODUCT_QUICK_VIEW'),
        ];

        $settings['settings'] = SettingsManager::getSettingsFrontendConfig();

        if (is_singular()) {
            $post = get_post();

            $title = Utils::urlencodeHtmlEntities($post->post_title);

            $settings['post'] = [
                'id' => $post->ID,
                'title' => $title,
                'excerpt' => $post->post_excerpt,
                // 'featuredImage' => get_the_post_thumbnail_url(),
            ];
        } else {
            $settings['post'] = [
                'id' => 0,
                // 'title' => wp_get_document_title(),
                // 'excerpt' => get_the_archive_description(),
                'title' => '',
                'excerpt' => '',
            ];
        }

        $empty_object = (object) [];

        if ($is_preview_mode) {
            $settings['elements'] = [
                'data' => $empty_object,
                'editSettings' => $empty_object,
                'keys' => $empty_object,
            ];
        }

        // if (is_user_logged_in()) {
        //     $user = wp_get_current_user();

        //     if (!empty($user->roles)) {
        //         $settings['user'] = [
        //             'roles' => $user->roles,
        //         ];
        //     }
        // }

        return $settings;
    }

    // private function restoreContentFilters();

    // private function processMoreTag($content);
}
