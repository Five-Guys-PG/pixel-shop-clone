<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ModulesXCatalogXWidgetsXManufacturerXShortDescription extends WidgetBase
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'manufacturer-short-description';
    }

    public function getTitle()
    {
        return __('Short Description');
    }

    public function getIcon()
    {
        return 'eicon-product-description';
    }

    public function getCategories()
    {
        return ['listing-elements' => 6];
    }

    public function getKeywords()
    {
        return ['manufacturer', 'brand', 'description', 'short'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_style',
            [
                'label' => __('Style'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'text_align',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .elementor-widget-container',
            ]
        );

        $this->endControlsSection();
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-text-editor';
    }

    protected function render()
    {
        $manufacturer = &$GLOBALS['smarty']->tpl_vars['manufacturer']->value;

        echo $manufacturer['short_description'];
    }

    public function renderPlainContent()
    {
    }
}
