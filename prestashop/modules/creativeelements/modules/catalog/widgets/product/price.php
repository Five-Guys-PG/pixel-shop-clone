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

class ModulesXCatalogXWidgetsXProductXPrice extends WidgetBase
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-price';
    }

    public function getTitle()
    {
        return __('Product Price');
    }

    public function getIcon()
    {
        return 'eicon-product-price';
    }

    public function getCategories()
    {
        return ['product-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'price', 'product', 'sale', 'discount'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_product_price',
            [
                'label' => __('Product Price'),
            ]
        );

        $this->addControl(
            'layout',
            [
                'label' => __('Layout'),
                'type' => ControlsManager::SELECT,
                'default' => 'stacked',
                'options' => [
                    'inline' => __('Inline'),
                    'stacked' => __('Stacked'),
                ],
                'prefix_class' => 'ce-product-prices--layout-',
            ]
        );

        $this->addControl(
            'regular',
            [
                'label' => __('Regular Price'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'return_value' => 'show',
                'default' => 'show',
            ]
        );

        $this->addControl(
            'discount',
            [
                'label' => __('Discount'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'return_value' => 'show',
                'default' => 'show',
            ]
        );

        $this->addControl(
            'unit_price',
            [
                'label' => __('Unit Price'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'return_value' => 'show',
                'default' => 'show',
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-prices' => 'justify-content: {{VALUE}}',
                ],
                'prefix_class' => 'elementor%s-align-',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_product_price_style',
            [
                'label' => __('Product Price'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ce-product-prices',
            ]
        );

        $this->addControl(
            'price_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-prices' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'space_between',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    'body:not(.lang-rtl) {{WRAPPER}} .ce-product-prices > div' => 'margin: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0',
                    'body:not(.lang-rtl) {{WRAPPER}} .ce-product-prices' => 'margin: 0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}} 0',
                    'body.lang-rtl {{WRAPPER}} .ce-product-prices > div' => 'margin: 0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}}',
                    'body.lang-rtl {{WRAPPER}} .ce-product-prices' => 'margin: 0 0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->startControlsTabs('tabs_style_prices');

        $this->startControlsTab(
            'tab_regular',
            [
                'label' => __('Regular'),
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'regular_typography',
                'selector' => '{{WRAPPER}} .ce-product-price-regular',
            ]
        );

        $this->addControl(
            'regular_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-price-regular' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_sale',
            [
                'label' => __('Sale'),
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'sale_typography',
                'selector' => '{{WRAPPER}} .ce-has-discount',
            ]
        );

        $this->addControl(
            'sale_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-has-discount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_unit',
            [
                'label' => __('Unit'),
                'condition' => [
                    'unit_price!' => '',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'unit_typography',
                'selector' => '{{WRAPPER}} .ce-product-price-unit',
            ]
        );

        $this->addControl(
            'unit_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-price-unit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        if ($this->getName() === 'product-price') {
            $this->startControlsTabs('tabs_style_others');

            $this->startControlsTab(
                'tab_tax_excl',
                [
                    'label' => __('Tax excl.'),
                ]
            );

            $this->addGroupControl(
                GroupControlTypography::getType(),
                [
                    'name' => 'tax_excl_typography',
                    'selector' => '{{WRAPPER}} .ce-product-price-without-taxes',
                ]
            );

            $this->addControl(
                'tax_excl_color',
                [
                    'label' => __('Text Color'),
                    'type' => ControlsManager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ce-product-price-without-taxes' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->endControlsTab();

            $this->startControlsTab(
                'tab_pack',
                [
                    'label' => __('Pack'),
                ]
            );

            $this->addGroupControl(
                GroupControlTypography::getType(),
                [
                    'name' => 'pack_typography',
                    'selector' => '{{WRAPPER}} .ce-product-price-pack',
                ]
            );

            $this->addControl(
                'pack_color',
                [
                    'label' => __('Text Color'),
                    'type' => ControlsManager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ce-product-price-pack' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->endControlsTab();

            $this->startControlsTab(
                'tab_tax',
                [
                    'label' => __('Tax'),
                ]
            );

            $this->addGroupControl(
                GroupControlTypography::getType(),
                [
                    'name' => 'tax_typography',
                    'selector' => '{{WRAPPER}} .ce-tax-shipping-delivery-label',
                ]
            );

            $this->addControl(
                'tax_color',
                [
                    'label' => __('Text Color'),
                    'type' => ControlsManager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ce-tax-shipping-delivery-label' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->addControl(
                'heading_ecotax',
                [
                    'label' => __('Ecotax'),
                    'type' => ControlsManager::HEADING,
                ]
            );

            $this->addGroupControl(
                GroupControlTypography::getType(),
                [
                    'name' => 'ecotax_typography',
                    'selector' => '{{WRAPPER}} .ce-product-price-ecotax',
                ]
            );

            $this->addControl(
                'ecotax_color',
                [
                    'label' => __('Text Color'),
                    'type' => ControlsManager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ce-product-price-ecotax' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->endControlsTab();

            $this->endControlsTabs();
        }

        $this->endControlsSection();

        $this->startControlsSection(
            'section_product_price_discount_style',
            [
                'label' => __('Discount'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'discount!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'discount_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    'body:not(.lang-rtl) {{WRAPPER}} .ce-product-badge-sale' => 'margin-left: {{SIZE}}{{UNIT}}',
                    'body.lang-rtl {{WRAPPER}} .ce-product-badge-sale' => 'margin-right: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'discount_typography',
                'selector' => '{{WRAPPER}} .ce-product-badge-sale',
            ]
        );

        $this->addControl(
            'discount_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-sale' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'discount_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-sale' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'discount_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-sale' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'discount_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-sale' => 'border-style: solid; border-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'discount_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-sale' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'discount_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-sale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-overflow-hidden';
    }

    protected function render()
    {
        $context = \Context::getContext();
        $vars = &$context->smarty->tpl_vars;
        $product = $vars['product']->value;

        if (!$product['show_price']) {
            return;
        }
        $settings = $this->getSettingsForDisplay();
        $priceDisplay = $vars['priceDisplay']->value;
        $displayUnitPrice = $settings['unit_price'] && $vars['displayUnitPrice']->value;
        $displayPackPrice = $vars['displayPackPrice']->value;
        $noPackPrice = $vars['noPackPrice']->value;
        $configuration = &$vars['configuration']->value;
        $t = $context->getTranslator();
        ?>
        <div class="ce-product-prices">
        <?php if ($settings['regular'] && $product['has_discount']) { ?>
            <?php echo \Hook::exec('displayProductPriceBlock', ['product' => $product, 'type' => 'old_price']); ?>
            <div class="ce-product-price-regular"><?php echo $product['regular_price']; ?></div>
        <?php } ?>
            <div class="ce-product-price<?php $product['has_discount'] && print ' ce-has-discount'; ?>">
                <span><?php echo \Hook::exec('displayProductPriceBlock', ['product' => $product, 'type' => 'custom_price', 'hook_origin' => 'product_sheet']) ?: $product['price']; ?></span>
        <?php if ($settings['discount'] && $product['has_discount']) { ?>
            <?php if ('percentage' === $product['discount_type']) { ?>
                <span class="ce-product-badge ce-product-badge-sale ce-product-badge-sale-percentage">
                    <?php echo $t->trans('Save %percentage%', ['%percentage%' => $product['discount_percentage_absolute']], 'Shop.Theme.Catalog'); ?>
                </span>
            <?php } else { ?>
                <span class="ce-product-badge ce-product-badge-sale ce-product-badge-sale-amount">
                    <?php echo $t->trans('Save %amount%', ['%amount%' => $product['discount_to_display']], 'Shop.Theme.Catalog'); ?>
                </span>
            <?php } ?>
        <?php } ?>
            </div>
        <?php if ($displayUnitPrice) { ?>
            <div class="ce-product-price-unit">
                <?php echo $t->trans('(%unit_price%)', ['%unit_price%' => $product['unit_price_full']], 'Shop.Theme.Catalog'); ?>
            </div>
        <?php } ?>
        <?php if (2 == $priceDisplay) { ?>
            <div class="ce-product-price-without-taxes">
                <?php echo $t->trans('%price% tax excl.', ['%price%' => $product['price_tax_exc']], 'Shop.Theme.Catalog'); ?>
            </div>
        <?php } ?>
        <?php if ($displayPackPrice) { ?>
            <div class="ce-product-price-pack">
                <?php echo $t->trans('Instead of %price%', ['%price%' => $noPackPrice], 'Shop.Theme.Catalog'); ?>
            </div>
        <?php } ?>
        <?php if ($product['ecotax']['amount'] > 0) { ?>
            <div class="ce-product-price-ecotax">
                <?php echo $t->trans('Including %amount% for ecotax', ['%amount%' => $product['ecotax']['value']], 'Shop.Theme.Catalog'); ?>
            <?php if ($product['has_discount']) { ?>
                <?php echo $t->trans('(not impacted by the discount)', [], 'Shop.Theme.Catalog'); ?>
            <?php } ?>
            </div>
        <?php } ?>
            <?php echo \Hook::exec('displayProductPriceBlock', ['product' => $product, 'type' => 'weight', 'hook_origin' => 'product_sheet']); ?>
        <?php ob_start(); ?>
        <?php if (isset($configuration['taxes_enabled']) && !$configuration['taxes_enabled']) { ?>
            <?php echo $t->trans('No tax', [], 'Shop.Theme.Catalog'); ?>
        <?php } elseif ($configuration['display_taxes_label']) { ?>
            <?php echo $product['labels']['tax_long']; ?>
        <?php } ?>
            <?php echo \Hook::exec('displayProductPriceBlock', ['product' => $product, 'type' => 'price']); ?>
            <?php echo \Hook::exec('displayProductPriceBlock', ['product' => $product, 'type' => 'after_price']); ?>
        <?php if ($tax_shipping = trim(ob_get_clean())) { ?>
            <div class="ce-tax-shipping-delivery-label"><?php echo $tax_shipping; ?></div>
        <?php } ?>
        </div>
        <?php
    }

    public function renderPlainContent()
    {
    }
}
