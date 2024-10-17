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

class ModulesXCatalogXWidgetsXProductXBadges extends WidgetBase
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-badges';
    }

    public function getTitle()
    {
        return __('Product Badges');
    }

    public function getIcon()
    {
        return 'eicon-meta-data';
    }

    public function getCategories()
    {
        return ['product-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'product', 'badges', 'flags', 'discount', 'reduced', 'new', 'pack', 'out-of-stock', 'sold out', 'sale'];
    }

    protected function _registerControls()
    {
        $t = \Context::getContext()->getTranslator();

        $this->startControlsSection(
            'section_product_badges',
            [
                'label' => __('Product Badges'),
            ]
        );

        $this->addControl(
            'layout',
            [
                'label' => __('Layout'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'inline' => __('Inline'),
                    'stacked' => __('Stacked'),
                ],
                'default' => 'inline',
                'prefix_class' => 'ce-product-badges--',
            ]
        );

        $this->addControl(
            'badges',
            [
                'label' => __('Badges'),
                'type' => ControlsManager::SELECT2,
                'options' => [
                    'sale' => __('Sale'),
                    'new' => $t->trans('New', [], 'Shop.Theme.Catalog'),
                    'pack' => $t->trans('Pack', [], 'Shop.Theme.Catalog'),
                    'out' => __('Out-of-Stock'),
                    'online' => $t->trans('Online only', [], 'Shop.Theme.Catalog'),
                    'onsale' => $t->trans('On sale!', [], 'Shop.Theme.Catalog'),
                ],
                'default' => ['sale', 'new', 'pack', 'out', 'online'],
                'label_block' => true,
                'multiple' => true,
            ]
        );

        $this->addControl(
            'heading_label',
            [
                'label' => __('Label'),
                'type' => ControlsManager::HEADING,
                'condition' => [
                    'badges!' => [],
                ],
            ]
        );

        $this->addControl(
            'sale_text',
            [
                'label' => __('Sale'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Default'),
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'badges',
                            'operator' => 'contains',
                            'value' => 'sale',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'new_text',
            [
                'label' => $t->trans('New', [], 'Shop.Theme.Catalog'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Default'),
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'badges',
                            'operator' => 'contains',
                            'value' => 'new',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'pack_text',
            [
                'label' => $t->trans('Pack', [], 'Shop.Theme.Catalog'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Default'),
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'badges',
                            'operator' => 'contains',
                            'value' => 'pack',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'out_text',
            [
                'label' => __('Out-of-Stock'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Default'),
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'badges',
                            'operator' => 'contains',
                            'value' => 'out',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'online_text',
            [
                'label' => $t->trans('Online only', [], 'Shop.Theme.Catalog'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Default'),
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'badges',
                            'operator' => 'contains',
                            'value' => 'online',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'onsale_text',
            [
                'label' => $t->trans('On sale!', [], 'Shop.Theme.Catalog'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Default'),
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'badges',
                            'operator' => 'contains',
                            'value' => 'onsale',
                        ],
                    ],
                ],
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
                    '{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_product_badges',
            [
                'label' => __('Product Badges'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'space_between',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 5,
                ],
                'selectors' => [
                    'body:not(.lang-rtl) {{WRAPPER}} .ce-product-badge' => 'margin: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0',
                    'body:not(.lang-rtl) {{WRAPPER}} .ce-product-badges' => 'margin: 0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}} 0',
                    'body.lang-rtl {{WRAPPER}} .ce-product-badge' => 'margin: 0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}}',
                    'body.lang-rtl {{WRAPPER}} .ce-product-badges' => 'margin: 0 0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'min_width',
            [
                'label' => __('Min Width'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .ce-product-badge',
            ]
        );

        $this->startControlsTabs('tabs_style_badges');

        $this->startControlsTab(
            'tab_badge_sale',
            [
                'label' => __('Sale'),
            ]
        );

        $this->addControl(
            'sale_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-sale, {{WRAPPER}} .ce-product-badge-onsale' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'sale_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-sale, {{WRAPPER}} .ce-product-badge-onsale' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'sale_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-sale, {{WRAPPER}} .ce-product-badge-onsale' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_badge_new',
            [
                'label' => __('New'),
            ]
        );

        $this->addControl(
            'new_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-new' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'new_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-new' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'new_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-new' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_badge_pack',
            [
                'label' => __('Pack'),
            ]
        );

        $this->addControl(
            'pack_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-pack' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'pack_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-pack' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'pack_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-pack' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_badge_out',
            [
                'label' => __('Out-of-Stock'),
            ]
        );

        $this->addControl(
            'out_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-out' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'out_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-out' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'out_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-out' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_badge_online',
            [
                'label' => __('Online only'),
            ]
        );

        $this->addControl(
            'online_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-online' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'online_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-online' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'online_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge-online' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge' => 'border-style: solid; border-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .ce-product-badge',
            ]
        );

        $this->endControlsSection();
    }

    protected function shouldPrintEmpty()
    {
        return true;
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-overflow-hidden';
    }

    protected $flags = [
        'sale' => 'discount',
        'new' => 'new',
        'pack' => 'pack',
        'out' => 'out_of_stock',
        'online' => 'online-only',
        'onsale' => 'on-sale',
    ];

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $product = $GLOBALS['smarty']->tpl_vars['product']->value;
        $badges = [];

        foreach ($settings['badges'] as $badge) {
            $flag = $this->flags[$badge];

            if (!empty($product['flags'][$flag])) {
                $badges[$badge] = $settings["{$badge}_text"] ?: $product['flags'][$flag]['label'];
            }
        }
        if (!$badges) {
            return;
        } ?>
        <div class="ce-product-badges">
        <?php foreach ($badges as $badge => $label) { ?>
            <div class="ce-product-badge ce-product-badge-<?php echo $badge; ?>"><?php echo $label; ?></div>
        <?php } ?>
        </div>
        <?php
    }

    protected function renderSmarty()
    {
        $settings = $this->getSettingsForDisplay();

        if (!$settings['badges']) {
            return;
        } ?>
        <div class="ce-product-badges">
        <?php foreach ($settings['badges'] as $badge) { ?>
            {if !empty(<?php echo "\$product.flags['{$this->flags[$badge]}']"; ?>)}
                <div class="ce-product-badge ce-product-badge-<?php echo $badge; ?>">
                    <?php echo $settings["{$badge}_text"] ?: "{\$product.flags['{$this->flags[$badge]}'].label}"; ?>
                </div>
            {/if}
        <?php } ?>
        </div>
        <?php
    }

    public function renderPlainContent()
    {
    }
}
