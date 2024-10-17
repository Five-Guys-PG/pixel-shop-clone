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

use CE\ModulesXCatalogXWidgetsXProductXBase as ProductBase;

class ModulesXCatalogXWidgetsXProductXBox extends ProductBase
{
    const SELECTOR = '{{WRAPPER}} .elementor-product-box > *';

    const SELECTOR_HOVER = '{{WRAPPER}} .elementor-product-box > :hover';

    public function getName()
    {
        return 'product-box';
    }

    public function getTitle()
    {
        return __('Product Box');
    }

    public function getIcon()
    {
        return 'eicon-info-box';
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'product', 'box'];
    }

    protected function getDefaultProductId()
    {
        return is_admin() ? (Helper::getLastUpdatedProductId($this->context->shop->id) ?: 1) : '';
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_product_box',
            [
                'label' => __('Product Box'),
            ]
        );

        $this->addControl(
            'skin',
            [
                'label' => __('Miniature'),
                'type' => ControlsManager::SELECT,
                'options' => static::getSkinOptions() + [
                    'custom' => __('Custom'),
                ],
                'default' => 'product',
                'save_default' => true,
            ]
        );

        $this->addControl(
            'product_id',
            [
                'label' => __('Product'),
                'type' => ControlsManager::SELECT2,
                'label_block' => true,
                'select2options' => [
                    'placeholder' => __('Loading') . '...',
                    'allowClear' => false,
                    'ajax' => [
                        'get' => 'Products',
                        'url' => Helper::getAjaxProductsListLink(),
                    ],
                ],
                'default' => $this->getDefaultProductId(),
                'save_default' => true,
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();

        $this->registerMiniatureSections();

        $this->startControlsSection(
            'section_style_product',
            [
                'label' => __('Product Box'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'align',
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
                    '' => [
                        'title' => __('Justified'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-product-box' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'product_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    static::SELECTOR => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->addControl(
            'product_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::DIMENSIONS,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    static::SELECTOR => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
                ],
            ]
        );

        $this->addControl(
            'product_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    static::SELECTOR => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->startControlsTabs('product_style_tabs');

        $this->startControlsTab(
            'product_style_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'product_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    static::SELECTOR => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'product_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-product-miniature' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'skin' => 'custom',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'product_box_shadow',
                'selector' => static::SELECTOR,
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'product_style_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'product_border_color_hover',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    static::SELECTOR_HOVER => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'product_bg_color_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-product-miniature:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'skin' => 'custom',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'product_box_shadow_hover',
                'selector' => static::SELECTOR_HOVER,
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->registerMiniatureStyleSections();
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        if (!$settings['product_id'] || empty($this->context->currency->id) || !$product = $this->getProduct($settings['product_id'])) {
            return;
        }

        if ('custom' === $settings['skin']) {
            // BC fix
            $settings['qv_icon_align'] = $this->getSettings('qv_icon_align');
            $settings['atc_icon_align'] = $this->getSettings('atc_icon_align');

            echo '<div class="elementor-product-box">' . $this->fetchMiniature($settings, $product) . '</div>';
        } else {
            if (!$tpl = static::getSkinTemplate($settings['skin'])) {
                return;
            }
            echo '<div class="elementor-product-box">' . $this->context->smarty->fetch($tpl, null, null, ['product' => $product]) . '</div>';
        }
    }
}
