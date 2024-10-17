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

class ModulesXCatalogXWidgetsXProductXStock extends WidgetBase
{
    const REMOTE_RENDER = true;

    protected $context;

    protected $translator;

    public function getName()
    {
        return 'product-stock';
    }

    public function getTitle()
    {
        return __('Product Stock');
    }

    public function getIcon()
    {
        return 'eicon-product-stock';
    }

    public function getCategories()
    {
        return ['product-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'stock', 'quantity', 'availability', 'product'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_stock',
            [
                'label' => __('Product Stock'),
            ]
        );

        $this->addControl(
            'heading_icon',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Icon'),
            ]
        );

        $this->addControl(
            'selected_in_stock_icon',
            [
                'label' => $this->translator->trans('In stock', [], 'Shop.Theme.Catalog'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['svg'],
                'fa4compatibility' => 'in_stock_icon',
                'recommended' => [
                    'ce-icons' => [
                        'check',
                    ],
                    'fa-solid' => [
                        'square-check',
                        'circle-check',
                        'battery-full',
                        'battery-three-quarters',
                    ],
                    'fa-regular' => [
                        'square-check',
                        'circle-check',
                    ],
                ],
                'default' => [
                    'value' => 'ceicon-check',
                    'library' => 'ce-icons',
                ],
            ]
        );

        $this->addControl(
            'selected_backorder_icon',
            [
                'label' => __('Backorder'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['svg'],
                'recommended' => [
                    'ce-icons' => [
                        'check',
                    ],
                    'fa-solid' => [
                        'calendar',
                        'calendar-days',
                        'calendar-check',
                        'clock',
                        'clock-rotate-left',
                        'stopwatch',
                        'hourglass',
                        'hourglass-start',
                        'hourglass-half',
                    ],
                    'fa-regular' => [
                        'calendar',
                        'calendar-days',
                        'calendar-check',
                        'clock',
                        'clock-rotate-left',
                        'stopwatch',
                        'hourglass',
                        'hourglass-start',
                        'hourglass-half',
                    ],
                ],
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->addControl(
            'selected_low_stock_level_icon',
            [
                'label' => $this->translator->trans('Low stock level', [], 'Admin.Catalog.Feature'),
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'exclude_inline_options' => ['svg'],
                'fa4compatibility' => 'low_stock_level_icon',
                'recommended' => [
                    'fa-solid' => [
                        'exclamation',
                        'circle-exclamation',
                        'triangle-exclamation',
                        'fire',
                        'fire-flame-simple',
                        'fire-flame-curved',
                        'battery-quarter',
                    ],
                ],
                'default' => [
                    'value' => 'fas fa-exclamation',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->addControl(
            'selected_out_of_stock_icon',
            [
                'label' => $this->translator->trans('Out-of-Stock', [], 'Admin.Shopparameters.Feature'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['svg'],
                'fa4compatibility' => 'out_of_stock_icon',
                'recommended' => [
                    'ce-icons' => [
                        'close',
                    ],
                    'fa-solid' => [
                        'xmark',
                        'square-xmark',
                        'circle-xmark',
                        'ban',
                        'battery-empty',
                    ],
                    'fa-regular' => [
                        'circle-xmark',
                    ],
                ],
                'default' => [
                    'value' => 'fas fa-xmark',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->addControl(
            'heading_additional_options',
            [
                'label' => __('Additional Options'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        is_admin() && $this->addControl(
            'configure',
            [
                'label' => __('Global Settings'),
                'type' => ControlsManager::BUTTON,
                'text' => '<i class="eicon-external-link-square"></i>' . __('Configure'),
                'link' => [
                    'url' => $this->context->link->getAdminLink('AdminPPreferences') . '#configuration_fieldset_stock',
                    'is_external' => true,
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_stock_style',
            [
                'label' => __('Product Stock'),
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
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .ce-product-stock',
            ]
        );

        $this->addControl(
            'heading_text_color',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Text Color'),
            ]
        );

        $this->addControl(
            'in_stock_color',
            [
                'label' => $this->translator->trans('In stock', [], 'Shop.Theme.Catalog'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-stock--in-stock .ce-product-stock__availability' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'backorder_color',
            [
                'label' => __('Backorder'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-stock--backorder .ce-product-stock__availability' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'low_stock_level_color',
            [
                'label' => $this->translator->trans('Low stock level', [], 'Admin.Catalog.Feature'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-stock--low-stock-level .ce-product-stock__availability' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'out_of_stock_color',
            [
                'label' => $this->translator->trans('Out-of-Stock', [], 'Admin.Shopparameters.Feature'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-stock--out-of-stock .ce-product-stock__availability' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_icon_style',
            [
                'label' => __('Icon'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'icon_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    'body:not(.lang-rtl) {{WRAPPER}} .ce-product-stock__availability i' => 'margin-right: {{SIZE}}{{UNIT}}',
                    'body.lang-rtl {{WRAPPER}} .ce-product-stock__availability i' => 'margin-left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'icon_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 16,
                ],
                'range' => [
                    'px' => [
                        'min' => 6,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-stock__availability i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'heading_icon_color',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Color'),
            ]
        );

        $this->addControl(
            'in_stock_icon_color',
            [
                'label' => $this->translator->trans('In stock', [], 'Shop.Theme.Catalog'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-stock--in-stock .ce-product-stock__availability i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'backorder_icon_color',
            [
                'label' => __('Backorder'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-stock--backorder .ce-product-stock__availability i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'low_stock_level_icon_color',
            [
                'label' => $this->translator->trans('Low stock level', [], 'Admin.Catalog.Feature'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-stock--low-stock-level .ce-product-stock__availability i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'out_of_stock_icon_color',
            [
                'label' => $this->translator->trans('Out-of-Stock', [], 'Admin.Shopparameters.Feature'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-stock--out-of-stock .ce-product-stock__availability i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_min_quantity_style',
            [
                'label' => $this->translator->trans('Minimum quantity for sale', [], 'Admin.Catalog.Feature'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'min_quantity_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-stock__min-quantity' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'min_quantity_typography',
                'selector' => '{{WRAPPER}} .ce-product-stock__min-quantity',
            ]
        );

        $this->addControl(
            'min_quantity_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-stock__min-quantity' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $product = $GLOBALS['smarty']->tpl_vars['product']->value;
        $availability = 'available' === $product['availability'] ? 'in-stock' : (
            'last_remaining_items' === $product['availability'] ? 'low-stock-level' : 'out-of-stock'
        );
        $backorder = $product['allow_oosp'] && $product['quantity'] < $product['minimal_quantity'] ? ' ce-product-stock--backorder' : '';
        ?>
        <div class="ce-product-stock ce-product-stock--<?php echo esc_attr($availability . $backorder); ?>">
        <?php if ($product['show_availability'] && $product['availability_message']) {
            $availability_icon = ($backorder ? 'backorder' : str_replace('-', '_', $availability)) . '_icon';
            $icon = isset($settings[$availability_icon]) && !isset($settings['__fa4_migrated']["selected_$availability_icon"])
                ? $settings[$availability_icon]
                : $settings["selected_$availability_icon"]['value'];
            ?>
            <div class="ce-product-stock__availability">
            <?php if ($icon) { ?>
                <i class="<?php echo esc_attr($icon); ?>" aria-hidden="true"></i>
            <?php } ?>
                <span class="ce-product-stock__availability-label">
                    <?php echo esc_html($product['availability_message']); ?>
                </span>
            </div>
        <?php } ?>
        <?php if ($product['minimal_quantity'] > 1) { ?>
            <div class="ce-product-stock__min-quantity">
                <?php
                echo $this->translator->trans(
                    'The minimum purchase order quantity for the product is %quantity%.',
                    ['%quantity%' => $product['minimal_quantity']],
                    'Shop.Theme.Checkout'
                ); ?>
            </div>
        <?php } ?>
        </div>
        <?php
    }

    protected function renderSmarty()
    {
        $settings = $this->getSettingsForDisplay();
        $is_icon = isset($settings['in_stock_icon']) && !isset($settings['__fa4_migrated']['selected_in_stock_icon'])
            ? $settings['in_stock_icon']
            : $settings['selected_in_stock_icon']['value'];
        $oos_icon = isset($settings['out_of_stock_icon']) && !isset($settings['__fa4_migrated']['selected_out_of_stock_icon'])
            ? $settings['out_of_stock_icon']
            : $settings['selected_out_of_stock_icon']['value'];
        $lsl_icon = isset($settings['low_stock_level_icon']) && !isset($settings['__fa4_migrated']['selected_low_stock_level_icon'])
            ? $settings['low_stock_level_icon']
            : $settings['selected_low_stock_level_icon']['value'];
        ?>
        {$availability = _q_c_($product.allow_oosp && $product.quantity < $product.minimal_quantity, 'backorder', $product.availability)}
        {$stock = [
            'available' => ['class' => 'in-stock', 'icon' => <?php var_export($is_icon); ?>],
            'unavailable' => ['class' => 'out-of-stock', 'icon' => <?php var_export($oos_icon); ?>],
            'last_remaining_items' => ['class' => 'low-stock-level', 'icon' => <?php var_export($lsl_icon); ?>],
            'backorder' => ['class' => 'in-stock ce-product-stock--backorder', 'icon' => <?php var_export($settings['selected_backorder_icon']['value']); ?>]
        ]}
        <div class="ce-product-stock ce-product-stock--{$stock[$availability].class}">
        {if $product.show_availability && $product.availability_message}
            <div class="ce-product-stock__availability">
            {if $stock[$availability].icon}
                <i class="{$stock[$availability].icon}" aria-hidden="true"></i>
            {/if}
                <span class="ce-product-stock__availability-label">{$product.availability_message}</span>
            </div>
        {/if}
        {if $product.minimal_quantity > 1}
            <div class="ce-product-stock__min-quantity">
                {l s='The minimum purchase order quantity for the product is %quantity%.' sprintf=[
                    '%quantity%' => $product.minimal_quantity
                ] d='Shop.Theme.Checkout'}
            </div>
        {/if}
        </div>
        <?php
    }

    public function renderPlainContent()
    {
    }

    public function __construct($data = [], $args = [])
    {
        $this->context = \Context::getContext();
        $this->translator = $this->context->getTranslator();

        parent::__construct($data, $args);
    }
}
