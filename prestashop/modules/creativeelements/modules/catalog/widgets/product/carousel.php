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

class ModulesXCatalogXWidgetsXProductXCarousel extends ProductBase
{
    use CarouselTrait;

    public function getName()
    {
        return 'product-carousel';
    }

    public function getTitle()
    {
        return __('Product Carousel');
    }

    public function getIcon()
    {
        return 'eicon-posts-carousel';
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'product', 'carousel', 'slider', 'featured', 'prices drop', 'new', 'best seller', 'related', 'recently viewed', 'brand'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_product_carousel',
            [
                'label' => __('Product Carousel'),
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

        $this->registerListingControls();

        $this->endControlsSection();

        $this->registerMiniatureSections();

        $this->registerHeadingStyleSection();

        $this->registerCarouselSection([
            'default_slides_count' => 4,
        ]);

        $this->startControlsSection(
            'section_style_product',
            [
                'label' => __('Product Box'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'product_spacing_custom',
            [
                'label' => __('Columns Gap'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .swiper:not(.swiper-initialized) .swiper-wrapper' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
                ],
                'frontend_available' => true,
                'render_type' => 'none',
            ]
        );

        $this->addResponsiveControl(
            'product_spacing_row',
            [
                'label' => __('Rows Gap'),
                'separator' => '',
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'margin-top: calc({{SIZE}}{{UNIT}} / 2); margin-bottom: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $product_selector = '{{WRAPPER}} .swiper-slide > *';
        $product_selector_hover = '{{WRAPPER}} .swiper-slide > :hover';

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
                'separator' => '',
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
                'separator' => '',
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
                'separator' => '',
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
                'separator' => '',
                'selector' => '{{WRAPPER}} .swiper-slide',
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
                'separator' => '',
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
                'separator' => '',
                'selector' => '{{WRAPPER}} .swiper-slide:hover',
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->registerMiniatureStyleSections();

        $this->registerNavigationStyleSection();
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
            $settings['limit'] ?: 8,
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

        $slides = [];

        if ('custom' === $settings['skin']) {
            // BC fix
            $settings['qv_icon_align'] = $this->getSettings('qv_icon_align');
            $settings['atc_icon_align'] = $this->getSettings('atc_icon_align');

            foreach ($products as $product) {
                $slides[] = '<div class="swiper-slide">' . $this->fetchMiniature($settings, $product) . '</div>';
            }
        } else {
            if (!$tpl = static::getSkinTemplate($settings['skin'])) {
                return;
            }
            foreach ($products as $product) {
                $slides[] = '<div class="swiper-slide">' . $this->context->smarty->fetch($tpl, null, null, ['product' => $product]) . '</div>';
            }
        }

        $this->renderCarousel($settings, $slides);
        $this->renderItemList($products);
    }
}
