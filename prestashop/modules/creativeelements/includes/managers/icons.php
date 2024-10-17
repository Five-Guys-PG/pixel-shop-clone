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

use CE\CoreXCommonXModulesXAjaxXModule as Ajax;
use CE\CoreXFilesXAssetsXSvgXSvgHandler as SvgHandler;

/**
 * Elementor icons manager.
 *
 * Elementor icons manager handler class
 *
 * @since 2.4.0
 */
class IconsManager
{
    // const NEEDS_UPDATE_OPTION = 'icon_manager_needs_update';

    /**
     * Tabs.
     *
     * Holds the list of all the tabs.
     *
     * @static
     *
     * @since 2.4.0
     *
     * @var array
     */
    private static $tabs;

    // private static function getNeedsUpgradeOption()

    /**
     * register styles
     *
     * Used to register all icon types stylesheets so they could be enqueued later by widgets
     */
    public function registerStyles()
    {
        $config = self::getIconManagerTabsConfig();

        $shared_styles = [];

        foreach ($config as $type => $icon_type) {
            if (!isset($icon_type['url'])) {
                continue;
            }
            $dependencies = [];
            if (!empty($icon_type['enqueue'])) {
                foreach ((array) $icon_type['enqueue'] as $font_css_url) {
                    if (!in_array($font_css_url, array_keys($shared_styles))) {
                        $style_handle = 'elementor-icons-shared-' . count($shared_styles);
                        wp_register_style(
                            $style_handle,
                            $font_css_url,
                            [],
                            $icon_type['ver']
                        );
                        $shared_styles[$font_css_url] = $style_handle;
                    }
                    $dependencies[] = $shared_styles[$font_css_url];
                }
            }
            wp_register_style(
                'elementor-icons-' . $icon_type['name'],
                $icon_type['url'],
                $dependencies,
                $icon_type['ver']
            );
        }
        wp_register_style(
            'font-awesome-4-shim',
            self::getFaAssetUrl('v4-shims'),
            [],
            '6.2.0'
        );
    }

    /**
     * Init Tabs
     *
     * Initiate Icon Manager Tabs.
     *
     * @static
     *
     * @since 2.4.0
     */
    private static function initTabs()
    {
        self::$tabs = apply_filters('elementor/icons_manager/native', [
            'fa-regular' => [
                'name' => 'fa-regular',
                'label' => __('Font Awesome - Regular'),
                'url' => self::getFaAssetUrl('regular'),
                'enqueue' => [],
                'prefix' => 'fa-',
                'displayPrefix' => 'far',
                'labelIcon' => 'fab fa-square-font-awesome-stroke',
                'ver' => '6.2.0',
                'fetchJson' => self::getFaAssetUrl('regular', 'js', false),
                'native' => true,
            ],
            'fa-solid' => [
                'name' => 'fa-solid',
                'label' => __('Font Awesome - Solid'),
                'url' => self::getFaAssetUrl('solid'),
                'enqueue' => [],
                'prefix' => 'fa-',
                'displayPrefix' => 'fas',
                'labelIcon' => 'fab fa-square-font-awesome',
                'ver' => '6.2.0',
                'fetchJson' => self::getFaAssetUrl('solid', 'js', false),
                'native' => true,
            ],
            'fa-brands' => [
                'name' => 'fa-brands',
                'label' => __('Font Awesome - Brands'),
                'url' => self::getFaAssetUrl('brands'),
                'enqueue' => [],
                'prefix' => 'fa-',
                'displayPrefix' => 'fab',
                'labelIcon' => 'far fa-font-awesome',
                'ver' => '6.4.2',
                'fetchJson' => self::getFaAssetUrl('brands', 'js', false),
                'native' => true,
            ],
        ]);
    }

    /**
     * Get Icon Manager Tabs
     *
     * @return array
     */
    public static function getIconManagerTabs()
    {
        if (!self::$tabs) {
            self::initTabs();

            $additional_tabs = apply_filters('elementor/icons_manager/additional_tabs', []);

            self::$tabs = array_merge(self::$tabs, $additional_tabs);
        }

        return self::$tabs;
    }

    public static function enqueueShim()
    {
        static $enqueued;

        if ($enqueued) {
            return;
        }
        /*
        wp_enqueue_script(
            'font-awesome-4-shim',
            self::getFaAssetUrl('v4-shims', 'js'),
            [],
            _CE_VERSION_
        );
        // Make sure that the CSS in the 'all' file does not override FA Pro's CSS
        if (!wp_script_is('font-awesome-pro')) {
            wp_enqueue_style(
                'font-awesome-5-all',
                self::getFaAssetUrl('all'),
                [],
                _CE_VERSION_
            );
        }
        $frontend = Plugin::$instance->frontend;
        $frontend->maybeEnqueueIconFont('fa-regular');
        $frontend->maybeEnqueueIconFont('fa-solid');
        $frontend->maybeEnqueueIconFont('fa-brands');
        */
        wp_enqueue_style('font-awesome-4-shim');

        $enqueued = true;
    }

    private static function getFaAssetUrl($filename, $ext_type = 'css', $add_suffix = true)
    {
        $url = _CE_ASSETS_URL_ . 'lib/font-awesome/' . $ext_type . '/' . $filename;

        if (!_PS_MODE_DEV_ && $add_suffix) {
            $url .= '.min';
        }

        return $url . '.' . $ext_type;
    }

    public static function getIconManagerTabsConfig()
    {
        $tabs = [
            'all' => [
                'name' => 'all',
                'label' => __('All Icons'),
                'labelIcon' => 'eicon-filter',
                'native' => true,
            ],
        ];

        return array_values(array_merge($tabs, self::getIconManagerTabs()));
    }

    private static function renderSvgIcon($value)
    {
        if (empty($value['url'])) {
            return '';
        }

        return SvgHandler::getInlineSvg($value['url']);
    }

    private static function renderIconHtml($icon, $attributes = [], $tag = 'i')
    {
        $icon_types = self::getIconManagerTabs();
        if (isset($icon_types[$icon['library']]['render_callback']) && is_callable($icon_types[$icon['library']]['render_callback'])) {
            return $icon_types[$icon['library']]['render_callback']($icon, $attributes, $tag);
        }

        if (empty($attributes['class'])) {
            $attributes['class'] = $icon['value'];
        } else {
            if (is_array($attributes['class'])) {
                $attributes['class'][] = $icon['value'];
            } else {
                $attributes['class'] .= ' ' . $icon['value'];
            }
        }

        return '<' . $tag . ' ' . Utils::renderHtmlAttributes($attributes) . '></' . $tag . '>';
    }

    /**
     * Render Icon
     *
     * Used to render Icon for ControlsManager::ICONS
     *
     * @param array $icon Icon Type, Icon value
     * @param array $attributes Icon HTML Attributes
     * @param string $tag Icon HTML tag, defaults to <i>
     *
     * @return bool
     */
    public static function renderIcon($icon, $attributes = [], $tag = 'i')
    {
        if (empty($icon['library'])) {
            return false;
        }
        $output = '';
        // handler SVG Icon
        if ('svg' === $icon['library']) {
            $output = self::renderSvgIcon($icon['value']);
        } else {
            $output = self::renderIconHtml($icon, $attributes, $tag);
        }

        echo $output;

        return true;
    }

    public static function getBcIcon(array &$settings, $bc_name, $attributes = [], $tag = 'i')
    {
        if (isset($attributes['selected'])) {
            $control_name = $attributes['selected'];

            unset($attributes['selected']);
        } else {
            $control_name = "selected_$bc_name";
        }

        if (isset($settings[$bc_name]) && !isset($settings['__fa4_migrated'][$control_name])) {
            $icon = [
                'value' => $settings[$bc_name],
                'library' => 'v4-shims',
            ];
            empty($settings[$bc_name]) || self::enqueueShim();
        } elseif (isset($settings[$control_name])) {
            $icon = &$settings[$control_name];
        }

        if (empty($icon['value']) || empty($icon['library'])) {
            return;
        }

        return 'svg' === $icon['library']
            ? self::renderSvgIcon($icon['value'])
            : self::renderIconHtml($icon, $attributes, $tag);
    }

    public static function renderBcIcon(array &$settings, $bc_name, $attributes = [], $tag = 'i')
    {
        $control_name = isset($attributes['selected']) ? $attributes['selected'] : "selected_$bc_name";

        if (!isset($settings[$control_name])) {
            return false;
        }
        unset($attributes['selected']);

        if (!empty($settings[$bc_name]) && !isset($settings['__fa4_migrated'][$control_name])) {
            $settings[$control_name] = [
                'value' => $settings[$bc_name],
                'library' => 'v4-shims',
            ];
            self::enqueueShim();
        }

        return self::renderIcon($settings[$control_name], $attributes, $tag);
    }

    /**
     * Font Awesome 4 to font Awesome 5 Value Migration
     *
     * used to convert string value of Icon control to array value of Icons control
     * ex: 'fa fa-star' => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ]
     *
     * @param $value
     *
     * @return array
     */
    public static function fa4ToFa6ValueMigration($value)
    {
        static $migration_dictionary = false;

        if ('' === $value) {
            return [
                'value' => '',
                'library' => '',
            ];
        }
        if ('c' === $value[0]) {
            return [
                'value' => $value,
                'library' => 'ce-icons',
            ];
        }
        if (false === $migration_dictionary) {
            $migration_dictionary = json_decode(
                @call_user_func('file_get_contents', _CE_ASSETS_PATH_ . 'lib/font-awesome/migration/mapping.js'),
                true
            );
        }
        if (isset($migration_dictionary[$value])) {
            return $migration_dictionary[$value];
        }

        return [
            'value' => 'fas ' . str_replace('fa ', '', $value),
            'library' => 'fa-solid',
        ];
    }

    /**
     * on_import_migration
     *
     * @param array $element settings array
     * @param string $old_control old control id
     * @param string $new_control new control id
     * @param bool $remove_old boolean weather to remove old control or not
     *
     * @return array
     */
    public static function &onImportMigration(array &$element, $old_control = '', $new_control = '', $remove_old = true)
    {
        if (!isset($element['settings'][$old_control]) || isset($element['settings'][$new_control])) {
            return $element;
        }

        // Case when old value is saved as empty string
        $new_value = [
            'value' => '',
            'library' => '',
        ];

        // Case when old value needs migration
        // if (!empty($element['settings'][$old_control]) && !self::isMigrationAllowed()) {
        if (!empty($element['settings'][$old_control])) {
            $new_value = self::fa4ToFa6ValueMigration($element['settings'][$old_control]);
        }

        $element['settings'][$new_control] = $new_value;

        // remove old value
        if ($remove_old) {
            unset($element['settings'][$old_control]);
        }

        return $element;
    }

    /**
     * is_migration_allowed
     *
     * @return bool
     */
    public static function isMigrationAllowed()
    {
        return true;
    }

    // public function registerAdminSettings(Settings $settings)

    // public function registerAdminToolsSettings(Tools $settings)

    // public function ajaxUpgradeToFa5()

    // public function addUpdateNeededFlag($settings)

    public function enqueueFontawesomeCss()
    {
        // if ( ! self::is_migration_allowed() ) {
        //     wp_enqueue_style( 'font-awesome' );
        // } else {
        //     $current_filter = current_filter();
        //     $load_shim = get_option( 'elementor_load_fa4_shim', false );
        //     if ( 'elementor/editor/after_enqueue_styles' === $current_filter ) {
        //         self::enqueue_shim();
        //     } else if ( 'yes' === $load_shim ) {
        //         self::enqueue_shim();
        //     }
        // }
        \Configuration::get('elementor_load_fa4_shim') && self::enqueueShim();
    }

    // public function addAdminStrings($settings)

    /**
     * Icons Manager constructor
     */
    public function __construct()
    {
        /*
        if (is_admin()) {
            // @todo: remove once we deprecate fa4
            add_action('elementor/admin/after_create_settings/' . Settings::PAGE_ID, [$this, 'registerAdminSettings'], 100);
            add_action('elementor/admin/localize_settings', [$this, 'addAdminStrings']);
        }
        */
        add_action('elementor/editor/after_enqueue_styles', [$this, 'enqueueShim']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueueFontawesomeCss']);
        add_action('elementor/frontend/after_register_styles', [$this, 'registerStyles']);

        // if (!self::isMigrationAllowed()) {
        //     add_filter('elementor/editor/localize_settings', [$this, 'addUpdateNeededFlag']);
        //     // add_action('elementor/admin/after_create_settings/' . Tools::PAGE_ID, [$this, 'registerAdminToolsSettings'], 100);

        //     if (!empty(${'_POST'})) {
        //         add_action('wp_ajax_' . self::NEEDS_UPDATE_OPTION . '_upgrade', [$this, 'ajaxUpgradeToFa5']);
        //     }
        // }
    }
}
