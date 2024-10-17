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

// use CE\CoreXCommonXModulesXFinderXModule as Finder;
use CE\CoreXBaseXApp as BaseApp;
use CE\CoreXCommonXModulesXAjaxXModule as Ajax;
use CE\CoreXCommonXModulesXConnectXModule as Connect;

/**
 * App
 *
 * Elementor's common app that groups shared functionality, components and configuration
 *
 * @since 2.3.0
 */
class CoreXCommonXApp extends BaseApp
{
    private $templates = [];

    /**
     * App constructor.
     *
     * @since 2.3.0
     */
    public function __construct()
    {
        $this->addDefaultTemplates();

        add_action('elementor/editor/before_enqueue_scripts', [$this, 'registerScripts']);
        // add_action('admin_enqueue_scripts', [$this, 'registerScripts']);
        add_action('wp_enqueue_scripts', [$this, 'registerScripts']);

        add_action('elementor/editor/before_enqueue_styles', [$this, 'registerStyles']);
        // add_action('admin_enqueue_scripts', [$this, 'registerStyles']);
        add_action('wp_enqueue_scripts', [$this, 'registerStyles'], 9);

        add_action('elementor/editor/footer', [$this, 'printTemplates']);
        // add_action('admin_footer', [$this, 'printTemplates']);
        add_action('wp_footer', [$this, 'printTemplates']);
    }

    /**
     * Init components
     *
     * Initializing common components.
     *
     * @since 2.3.0
     */
    public function initComponents()
    {
        $this->addComponent('ajax', new Ajax());

        // if (current_user_can('manage_options')) {
        //     if (!is_customize_preview()) {
        //         $this->addComponent('finder', new Finder());
        //     }
        // }
        // $this->addComponent('connect', new Connect());
    }

    /**
     * Get name.
     *
     * Retrieve the app name.
     *
     * @since 2.3.0
     *
     * @return string Common app name
     */
    public function getName()
    {
        return 'common';
    }

    /**
     * Register scripts.
     *
     * Register common scripts.
     *
     * @since 2.3.0
     */
    public function registerScripts()
    {
        wp_register_script(
            'elementor-common-modules',
            $this->getJsAssetsUrl('common-modules'),
            [],
            _CE_VERSION_,
            true
        );

        wp_register_script(
            'backbone-marionette',
            $this->getJsAssetsUrl('backbone.marionette', 'views/lib/backbone/'),
            [
                'backbone',
            ],
            '2.4.5',
            true
        );

        wp_register_script(
            'backbone-radio',
            $this->getJsAssetsUrl('backbone.radio', 'views/lib/backbone/'),
            [
                'backbone',
            ],
            '1.0.4',
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
            'elementor-common',
            $this->getJsAssetsUrl('common'),
            [
                'jquery',
                'jquery-ui-draggable',
                'backbone-marionette',
                'backbone-radio',
                'elementor-common-modules',
                'elementor-dialog',
            ],
            _CE_VERSION_,
            true
        );

        $this->printConfig();

        // Used for external plugins.
        do_action('elementor/common/after_register_scripts', $this);

        wp_enqueue_script('elementor-common');
    }

    /**
     * Register styles.
     *
     * Register common styles.
     *
     * @since 2.3.0
     */
    public function registerStyles()
    {
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

        wp_enqueue_style(
            'elementor-common',
            $this->getCssAssetsUrl('common', null, 'default', true),
            [
                'elementor-icons',
            ],
            _CE_VERSION_
        );
    }

    /**
     * Add template.
     *
     * @since 2.3.0
     *
     * @param string $template can be either a link to template file or template
     *                         HTML content
     * @param string $type Optional. Whether to handle the template as path
     *                     or text. Default is `path`.
     */
    public function addTemplate($template, $type = 'path')
    {
        if ('path' === $type) {
            ob_start();

            include $template;

            $template = ob_get_clean();
        }

        $this->templates[] = $template;
    }

    /**
     * Print Templates
     *
     * Prints all registered templates.
     *
     * @since 2.3.0
     */
    public function printTemplates()
    {
        foreach ($this->templates as $template) {
            echo $template;
        }
    }

    /**
     * Get init settings.
     *
     * Define the default/initial settings of the common app.
     *
     * @since 2.3.0
     *
     * @return array
     */
    protected function getInitSettings()
    {
        return [
            'version' => _CE_VERSION_,
            'isRTL' => is_rtl(),
            'isDebug' => _PS_MODE_DEV_,
            'activeModules' => array_keys($this->getComponents()),
            'urls' => [
                'assets' => _MODULE_DIR_ . 'creativeelements/views/',
            ],
        ];
    }

    /**
     * Add default templates.
     *
     * Register common app default templates.
     *
     * @since 2.3.0
     */
    private function addDefaultTemplates()
    {
        $default_templates = [
            'includes/editor-templates/library-layout.php',
        ];

        foreach ($default_templates as $template) {
            $this->addTemplate(_CE_PATH_ . $template);
        }
    }
}
