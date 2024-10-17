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

use CE\CoreXBaseXDocument as Document;
use CE\CoreXBaseXModule as BaseModule;
use CE\CoreXDocumentTypesXPost as PostDocument;
use CE\ModulesXLibraryXDocumentsXPage as PageDocument;
use CE\ModulesXThemeXDocumentsXThemePageDocument as ThemePageDocument;

/**
 * Elementor page templates module.
 *
 * Elementor page templates module handler class is responsible for registering
 * and managing Elementor page templates modules.
 *
 * @since 2.0.0
 */
class ModulesXPageTemplatesXModule extends BaseModule
{
    /**
     * Elementor Canvas template name.
     */
    const TEMPLATE_CANVAS = 'elementor_canvas';

    const TEMPLATE_HEADER_FOOTER = 'elementor_header_footer';

    // protected $print_callback;

    /**
     * Get module name.
     *
     * Retrieve the page templates module name.
     *
     * @since 2.0.0
     *
     * @return string Module name
     */
    public function getName()
    {
        return 'page-templates';
    }

    /**
     * Template include.
     *
     * Update the path for the Elementor Canvas template.
     *
     * Fired by `template_include` filter.
     *
     * @since 2.0.0
     *
     * @param string $template The path of the template to include
     *
     * @return string The path of the template to include
     */
    public function templateInclude($template)
    {
        if (is_singular()) {
            $document = Plugin::$instance->documents->getDocForFrontend(get_the_ID());

            if ($document) {
                $template_path = $this->getTemplatePath($document->getMeta('_wp_page_template'));

                if ($template_path) {
                    $template = $template_path;

                    // Plugin::$instance->inspector->addLog('Page Template', Plugin::$instance->inspector->parseTemplatePath($template), $document->getEditUrl());
                }
            }
        }

        return $template;
    }

    /**
     * Add Elementor templates.
     *
     * Adds Elementor templates to all the post types that support
     * Elementor.
     *
     * Fired by `init` action.
     *
     * @since 2.0.0
     */
    public function addTemplatesSupport()
    {
        $post_types = get_post_types_by_support('elementor');

        foreach ($post_types as $post_type) {
            add_filter("theme_{$post_type}_templates", [$this, 'addPageTemplates'], 10, 4);
        }
    }

    /**
     * Add page templates.
     *
     * Add the Elementor page templates to the theme templates.
     *
     * Fired by `theme_{$post_type}_templates` filter.
     *
     * @since 2.0.0
     * @static
     *
     * @param array $page_templates Array of page templates. Keys are filenames,
     *                              checks are translated names.
     * @param WPPost $post
     *
     * @return array Page templates
     */
    public function addPageTemplates($page_templates, $post)
    {
        if ($post) {
            $document = Plugin::$instance->documents->get($post->ID);

            if ($document && !$document::getProperty('support_wp_page_templates')) {
                return $page_templates;
            }
        }

        $page_templates += [
            'elementor' => [
                'label' => 'Creative Elements',
                'options' => [
                    self::TEMPLATE_CANVAS => __('Canvas'),
                    self::TEMPLATE_HEADER_FOOTER => __('Full Width'),
                ],
            ],
        ];

        return $page_templates;
    }

    // public function setPrintCallback($callback)

    // public function printCallback()

    // public function printContent()

    /**
     * Get page template path.
     *
     * Retrieve the path for any given page template.
     *
     * @since 2.0.0
     *
     * @param string $page_template The page template name
     *
     * @return string Page template path
     */
    public function getTemplatePath($page_template)
    {
        $template_path = '';

        if (self::TEMPLATE_CANVAS === $page_template) {
            $template_path = _CE_TEMPLATES_ . 'front/theme/layouts/layout-canvas.tpl';
        } elseif (self::TEMPLATE_HEADER_FOOTER === $page_template) {
            $template_path = _CE_TEMPLATES_ . 'front/theme/layouts/layout-header-footer.tpl';
        } elseif ($page_template && 'default' !== $page_template) {
            $template_path = "layouts/$page_template.tpl";
        }

        return $template_path;
    }

    /**
     * Register template control.
     *
     * Adds custom controls to any given document.
     *
     * Fired by `update_post_metadata` action.
     *
     * @since 2.0.0
     *
     * @param Document $document The document instance
     */
    public function actionRegisterTemplateControl($document)
    {
        if ($document instanceof PostDocument || $document instanceof PageDocument || $document instanceof ThemePageDocument) {
            $this->registerTemplateControl($document);
        }
    }

    /**
     * Register template control.
     *
     * Adds custom controls to any given document.
     *
     * @since 2.0.0
     *
     * @param Document $document The document instance
     * @param string $control_id Optional. The control ID. Default is `template`
     */
    public function registerTemplateControl($document, $control_id = 'template')
    {
        $main_post = $document->getMainPost();

        if (!Utils::isCptCustomTemplatesSupported($main_post)) {
            return;
        }

        // require_once ABSPATH . '/wp-admin/includes/template.php';

        if (!$groups = get_page_templates(null, $main_post->post_type)) {
            return;
        }

        $document->startInjection([
            'of' => 'post_status',
        ]);

        $document->addControl(
            $control_id,
            [
                'label' => __('Page Layout'),
                'type' => ControlsManager::SELECT,
                'groups' => &$groups,
                'default' => 'default',
            ]
        );

        $document->addControl(
            $control_id . '_default_description',
            [
                'type' => ControlsManager::RAW_HTML,
                'raw' => __('Default Page Template from your theme'),
                'separator' => 'none',
                'content_classes' => 'elementor-descriptor',
                'condition' => [
                    $control_id => 'default',
                ],
            ]
        );

        $document->addControl(
            $control_id . '_canvas_description',
            [
                'type' => ControlsManager::RAW_HTML,
                'raw' => __('No header, no footer, just Creative Elements'),
                'separator' => 'none',
                'content_classes' => 'elementor-descriptor',
                'condition' => [
                    $control_id => self::TEMPLATE_CANVAS,
                ],
            ]
        );

        $document->addControl(
            $control_id . '_header_footer_description',
            [
                'type' => ControlsManager::RAW_HTML,
                'raw' => __('This template includes the header, full-width content and footer'),
                'separator' => 'none',
                'content_classes' => 'elementor-descriptor',
                'condition' => [
                    $control_id => self::TEMPLATE_HEADER_FOOTER,
                ],
            ]
        );

        if ($selector = \Configuration::get('elementor_page_wrapper_selector')) {
            $document->addControl(
                'full_width',
                [
                    'label' => __('Clear Content Wrapper'),
                    'type' => ControlsManager::SWITCHER,
                    'description' => sprintf(__(
                        'Not working? You can set a different selector for the content wrapper ' .
                        'in the <a href="%s" target="_blank">Settings page</a>.'
                    ), Helper::getSettingsLink()),
                    'selectors' => [
                        $selector => 'min-width: 100%; margin: 0; padding: 0;',
                    ],
                    'condition' => [
                        'template!' => self::TEMPLATE_CANVAS,
                    ],
                ]
            );
        }

        $document->endInjection();
    }

    // public function filterUpdateMeta($check, $object_id, $meta_key)

    /**
     * Page templates module constructor.
     *
     * Initializing Elementor page templates module.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
        add_action('init', [$this, 'addTemplatesSupport']);

        add_filter('template_include', [$this, 'templateInclude'], 11);

        add_action('elementor/documents/register_controls', [$this, 'actionRegisterTemplateControl']);

        // add_filter('update_post_metadata', [$this, 'filterUpdateMeta'], 10, 3);
    }
}
