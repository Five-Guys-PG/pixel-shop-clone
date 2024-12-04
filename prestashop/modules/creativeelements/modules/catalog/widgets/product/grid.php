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

class ModulesXCatalogXWidgetsXProductXGrid extends ProductBase
{
    public function getName()
    {
        return 'product-grid';
    }

    public function getTitle()
    {
        return __('Product Grid');
    }

    public function getIcon()
    {
        return 'eicon-posts-grid';
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'product', 'grid', 'listing', 'featured', 'prices drop', 'new', 'best seller', 'related', 'recently viewed', 'brand'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_grid_settings',
            [
                'label' => __('Product Grid'),
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

        $this->registerListingControls('num_of_prods');

        $this->addResponsiveControl(
            'columns',
            [
                'label' => __('Columns'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'default' => 4,
                'tablet_default' => 3,
                'mobile_default' => 1,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
                ],
                'style_transfer' => true,
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();

        $this->registerMiniatureSections();

        $this->registerHeadingStyleSection();

        $this->startControlsSection(
            'section_style_product',
            [
                'label' => __('Product Box'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'product_column_gap',
            [
                'label' => __('Columns Gap'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'product_row_gap',
            [
                'label' => __('Rows Gap'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $product_selector = '{{WRAPPER}} .ce-product-grid > *';
        $product_selector_hover = '{{WRAPPER}} .ce-product-grid > :hover';

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
                    $product_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
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
                    $product_selector => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
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
                    $product_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    $product_selector => 'border-color: {{VALUE}};',
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
                'selector' => $product_selector,
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
                    $product_selector_hover => 'border-color: {{VALUE}};',
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
                'selector' => $product_selector_hover,
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->registerMiniatureStyleSections();
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-heading elementor-widget-product-box';
    }

    protected function render()
    {
        if (empty($this->context->currency->id)) {
            return;
        }
        $settings = $this->getSettingsForDisplay();
        $listing = $settings['listing'];

        if ($settings['randomize'] && in_array($listing, ['category', 'viewed', 'products', 'related'])) {
            $settings['order_by'] = 'rand';
        }
        $settings['related_id'] = $settings['related_product_id'];

        $products = $this->getProducts(
            $listing,
            $settings['order_by'],
            $settings['order_dir'],
            $settings['num_of_prods'] ?: 8,
            in_array($listing, ['category', 'related', 'manufacturer', 'supplier']) ? $settings["{$listing}_id"] : 0,
            $settings['products']
        );

        if (empty($products)) {
            return;
        }

        if ('' !== $settings['heading']) {
            $this->addRenderAttribute('heading', 'class', 'elementor-heading-title');
            $settings['heading_display'] && $this->addRenderAttribute('heading', 'class', 'ce-display-' . $settings['heading_display']);
            $this->addInlineEditingAttributes('heading');

            echo "<{$settings['heading_size']} {$this->getRenderAttributeString('heading')}>{$settings['heading']}</{$settings['heading_size']}>";
        }

        if ('custom' === $settings['skin']) {
            // BC fix
            $settings['qv_icon_align'] = $this->getSettings('qv_icon_align');
            $settings['atc_icon_align'] = $this->getSettings('atc_icon_align');

            echo '<div class="ce-product-grid">';
            foreach ($products as $product) {
                echo $this->fetchMiniature($settings, $product);
            }
            echo '</div>';
        } else {
            if (!$tpl = static::getSkinTemplate($settings['skin'])) {
                return;
            }
            echo '<div class="ce-product-grid">';
            foreach ($products as $product) {
                echo $this->context->smarty->fetch($tpl, null, null, ['product' => $product]);
            }
            echo '</div>';
        }

        $this->renderItemList($products);
    }
}
