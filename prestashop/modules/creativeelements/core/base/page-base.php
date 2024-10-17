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

abstract class CoreXBaseXPageBase extends Document
{
    /**
     * @since 2.0.8
     * @static
     */
    public static function getProperties()
    {
        $properties = parent::getProperties();

        $properties['admin_tab_group'] = '';
        $properties['support_wp_page_templates'] = true;

        return $properties;
    }

    // protected static function getEditorPanelCategories()

    /**
     * @since 2.0.0
     */
    public function getCssWrapperSelector()
    {
        return 'body.elementor-page-' . $this->getMainId();
    }

    /**
     * @since 2.0.0
     */
    protected function _registerControls()
    {
        parent::_registerControls();

        self::registerHideTitleControl($this);

        self::registerPostFieldsControl($this);

        self::registerStyleControls($this);
    }

    /**
     * @since 2.0.0
     * @static
     *
     * @param Document $document
     */
    public static function registerHideTitleControl($document)
    {
        $page_title_selector = \Configuration::get('elementor_page_title_selector');

        if (!$page_title_selector) {
            $page_title_selector = 'header.page-header';
        }

        // $page_title_selector .= ', .elementor-page-title';

        $document->startInjection([
            'of' => 'post_title',
        ]);

        $document->addControl(
            'hide_title',
            [
                'label' => __('Hide Title'),
                'type' => ControlsManager::SWITCHER,
                'description' => sprintf(__(
                    'Not working? You can set a different selector for the title ' .
                    'in the <a href="%s" target="_blank">Settings page</a>.'
                ), Helper::getSettingsLink()),
                'selectors' => [
                    '{{WRAPPER}} ' . $page_title_selector => 'display: none',
                ],
            ]
        );

        $document->endInjection();
    }

    /**
     * @since 2.0.0
     * @static
     *
     * @param Document $document
     */
    public static function registerStyleControls($document)
    {
        $type = $document->getTemplateType();

        if ('product-quick-view' === $type || 'product-miniature' === $type) {
            return;
        }

        $document->startControlsSection(
            'section_page_style',
            [
                'label' => __('Body Style'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $document->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background',
                'fields_options' => [
                    'image' => [
                        // Currently isn't supported.
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                ],
            ]
        );

        $document->addResponsiveControl(
            'padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $document->endControlsSection();

        Plugin::$instance->controls_manager->addCustomCssControls($document);
    }

    /**
     * @since 2.0.0
     * @static
     *
     * @param Document $document
     */
    public static function registerPostFieldsControl($document)
    {
        $document->startInjection([
            'of' => 'post_status',
        ]);

        $uid = UId::parse($document->getMainId());

        // if (UId::PRODUCT === $uid->id_type) {
        //     $document->addControl(
        //         'post_excerpt',
        //         [
        //             'label' => __('Summary'),
        //             'type' => ControlsManager::WYSIWYG,
        //             'default' => $document->post->post_excerpt,
        //             'label_block' => true,
        //         ]
        //     );
        // }

        if (UId::CMS === $uid->id_type || UId::THEME === $uid->id_type && strpos($document->getTemplateType(), 'page') === 0) {
            $document->addControl(
                'post_featured_image',
                [
                    'label' => __('Featured Image'),
                    'type' => ControlsManager::MEDIA,
                    'default' => [
                        'url' => is_admin() ? $document->getMeta('_og_image') : '',
                    ],
                ]
            );
        }

        $document->endInjection();
    }

    /**
     * @since 2.0.0
     *
     * @param array $data
     *
     * @throws \Exception
     */
    public function __construct(array $data = [])
    {
        if (isset($data['settings'])) {
            $template = get_post_meta($data['post_id'], '_wp_page_template', true) ?: 'default';

            $data['settings']['template'] = $template;
        }

        parent::__construct($data);
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['category'] = '';
        $config['type'] = 'page';
        $config['default_route'] = 'templates/pages';

        return $config;
    }
}
