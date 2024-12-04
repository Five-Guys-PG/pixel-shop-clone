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

class ModulesXCatalogXWidgetsXProductXMeta extends WidgetBase
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-meta';
    }

    public function getTitle()
    {
        return __('Product Meta');
    }

    public function getIcon()
    {
        return 'eicon-product-info';
    }

    public function getCategories()
    {
        return ['product-elements'];
    }

    public function getKeywords()
    {
        return [
            'shop',
            'store',
            'product',
            'meta',
            'category',
            'brand',
            'manufacturer',
            'supplier',
            // 'tags',
            'delivery',
            'quantity',
            'availability',
            'condition',
            'reference',
            'sku',
            'isbn',
            'ean13',
            'upc',
            'mpn',
        ];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_product_meta',
            [
                'label' => __('Product Meta'),
            ]
        );

        $this->addControl(
            'layout',
            [
                'label' => __('Layout'),
                'label_block' => false,
                'type' => ControlsManager::SELECT,
                'options' => [
                    'inline' => __('Inline'),
                    'stacked' => __('Stacked'),
                    'table' => __('Table'),
                ],
                'default' => 'inline',
                'prefix_class' => 'ce-product-meta--layout-',
            ]
        );

        $repeater = new Repeater();

        $repeater->addControl(
            'type',
            [
                'label' => __('Field'),
                'type' => ControlsManager::SELECT,
                'groups' => [
                    'category' => __('Category'),
                    'manufacturer' => __('Brand'),
                    'supplier' => __('Supplier'),
                    // 'tags' => __('Tags'),
                    'delivery' => __('Delivery Time'),
                    'quantity' => __('Quantity'),
                    'availability_date' => __('Availability Date'),
                    'condition' => __('Condition'),
                    'references' => [
                        'label' => __('References'),
                        'options' => [
                            'reference' => __('SKU'),
                            'isbn' => __('ISBN'),
                            'ean13' => __('EAN-13'),
                            'upc' => __('UPC'),
                            'mpn' => __('MPN'),
                        ],
                    ],
                ],
                'default' => 'reference',
            ]
        );

        $repeater->addControl(
            'label',
            [
                'label' => __('Label'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Default'),
            ]
        );

        $this->addControl(
            'meta_list',
            [
                'type' => ControlsManager::REPEATER,
                'fields' => $repeater->getControls(),
                'default' => [
                    ['type' => 'reference'],
                    ['type' => 'category'],
                    ['type' => 'manufacturer'],
                ],
                'title_field' => '{{{ label.trim() || ' .
                    '(label = elementor.panel.currentView.currentPageView.model.get("settings").controls.' .
                    'meta_list.fields.type.groups)[type] || label.references.options[type] }}}',
            ]
        );

        $this->addControl(
            'show_colon',
            [
                'label' => __('Colon'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'selectors' => [
                    '{{WRAPPER}} .ce-product-meta__label:after' => 'content: ":"',
                ],
                'separator' => 'before',
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
                    '{{WRAPPER}} .ce-product-meta' => 'justify-content: {{VALUE}}',
                ],
                'prefix_class' => 'elementor%s-align-',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_product_meta_style',
            [
                'label' => __('Product Meta'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}}.ce-product-meta--layout-inline .ce-product-meta' => 'margin: 0 calc(-{{SIZE}}{{UNIT}} / 2)',
                    '{{WRAPPER}}.ce-product-meta--layout-inline .ce-product-meta__detail' => 'padding: 0 calc({{SIZE}}{{UNIT}} / 2)',
                    '{{WRAPPER}}:not(.ce-product-meta--layout-inline) .ce-product-meta__detail:not(:first-child)' => 'padding-top: calc({{SIZE}}{{UNIT}} / 2)',
                    '{{WRAPPER}}:not(.ce-product-meta--layout-inline) .ce-product-meta__detail:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}} / 2)',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'text_typography',
            ]
        );

        $this->addControl(
            'text_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->startControlsTabs('tabs_style_columns', ['separator' => 'before']);

        $this->startControlsTab(
            'tab_column_label',
            [
                'label' => __('Label'),
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .ce-product-meta__label',
            ]
        );

        $this->addControl(
            'label_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-meta__label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'label_width',
            [
                'label' => __('Min Width'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.ce-product-meta--layout-table .ce-product-meta__label' => 'min-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'layout' => 'table',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_column_link',
            [
                'label' => __('Link'),
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'link_typography',
                'selector' => '{{WRAPPER}} .ce-product-meta__value a',
            ]
        );

        $this->addControl(
            'link_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-meta__value a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'show_divider',
            [
                'label' => __('Divider'),
                'classes' => 'elementor-control-type-heading',
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'selectors' => [
                    '{{WRAPPER}} .ce-product-meta__detail:not(:last-child):after' => 'content: ""; border-color: #ddd',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'divider_style',
            [
                'label' => __('Style'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'solid' => __('Solid'),
                    'double' => __('Double'),
                    'dotted' => __('Dotted'),
                    'dashed' => __('Dashed'),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .ce-product-meta__detail:not(:last-child):after' => 'border-style: {{VALUE}}',
                ],
                'condition' => [
                    'show_divider!' => '',
                ],
            ]
        );

        $this->addControl(
            'divider_weight',
            [
                'label' => __('Weight'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    'body:not(.lang-rtl) {{WRAPPER}}.ce-product-meta--layout-inline .ce-product-meta__detail:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}; margin-right: calc(-{{SIZE}}{{UNIT}} / 2)',
                    'body.lang-rtl {{WRAPPER}}.ce-product-meta--layout-inline .ce-product-meta__detail:not(:last-child):after' => 'border-right-width: {{SIZE}}{{UNIT}}; margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
                    '{{WRAPPER}}:not(.ce-product-meta--layout-inline) .ce-product-meta__detail:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}; margin-bottom: calc(-{{SIZE}}{{UNIT}} / 2)',
                ],
                'condition' => [
                    'show_divider!' => '',
                ],
            ]
        );

        $this->addControl(
            'divider_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-meta__detail:not(:last-child):after' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_divider!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'divider_width',
            [
                'label' => __('Width'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}}.ce-product-meta--layout-inline .ce-product-meta__detail:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}:not(.ce-product-meta--layout-inline) .ce-product-meta__detail:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'show_divider!' => '',
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
        $t = $context->getTranslator();

        echo '<div class="ce-product-meta">';

        foreach ($this->getSettings('meta_list') as $meta) {
            $label = $meta['label'];
            $value = '';

            switch ($meta['type']) {
                case 'category':
                    if (!\Validate::isLoadedObject($category = new \Category($product['id_category_default'], $context->language->id))) {
                        continue 2;
                    }
                    $label = $label ?: rtrim($t->trans('Category: %category_name%', ['%category_name%' => ''], 'Shop.Theme.Catalog'), ': ');
                    $value = sprintf(
                        '<a href="%s">%s</a>',
                        esc_attr($context->link->getCategoryLink($category)),
                        esc_html($product['category_name'])
                    );
                    break;
                case 'manufacturer':
                    if (empty($vars['product_manufacturer']->value->name)) {
                        continue 2;
                    }
                    $label = $label ?: $t->trans('Brand', [], 'Shop.Theme.Catalog');
                    $value = sprintf(
                        '<a href="%s">%s</a>',
                        esc_attr($vars['product_brand_url']->value),
                        esc_html($vars['product_manufacturer']->value->name)
                    );
                    break;
                case 'supplier':
                    if (!$product['id_supplier']) {
                        continue 2;
                    }
                    $label = $label ?: $t->trans('Supplier', [], 'Shop.Theme.Catalog');
                    $supplier = new \Supplier($product['id_supplier'], $context->language->id);
                    $value = sprintf(
                        '<a href="%s">%s</a>',
                        esc_attr($context->link->getSupplierLink($supplier)),
                        esc_html($supplier->name)
                    );
                    break;
                case 'delivery':
                    $label = $label ?: $t->trans('Delivery Time', [], 'Admin.Catalog.Feature');

                    if (1 == $product['additional_delivery_times']) {
                        $value = $product['delivery_information'];
                    } elseif (2 == $product['additional_delivery_times']) {
                        if ($product['quantity'] > 0) {
                            $value = $product['delivery_in_stock'];
                        } elseif ($product['add_to_cart_url']) {
                            $value = $product['delivery_out_stock'];
                        }
                    }
                    if (!$value) {
                        continue 2;
                    }
                    break;
                case 'quantity':
                    isset($product['show_quantities']) || $product['show_quantities'] = !empty($product['quantity']) && $product['available_for_order'] && !\Configuration::isCatalogMode();
                    if (!$product['show_quantities']) {
                        continue 2;
                    }
                    $label = $label ?: $t->trans('In stock', [], 'Shop.Theme.Catalog');
                    $quantity = max(0, $product['quantity']);
                    isset($product['quantity_label']) || $product['quantity_label'] = $t->trans(1 === $quantity ? 'Item' : 'Items', [], 'Shop.Theme.Catalog');
                    $value = esc_html("$quantity {$product['quantity_label']}");
                    break;
                case 'availability_date':
                    if (!$product['availability_date'] || $product['quantity'] >= $product['minimal_quantity'] || date('Y-m-d') >= $product['availability_date']) {
                        continue 2;
                    }
                    $label = $label ?: rtrim($t->trans('Availability date:', [], 'Shop.Theme.Catalog'), ': ');
                    $value = esc_html(\Tools::displayDate($product['availability_date']));
                    break;
                case 'condition':
                    if (!$product['condition']) {
                        continue 2;
                    }
                    $label = $label ?: $t->trans('Condition', [], 'Shop.Theme.Catalog');
                    $value = esc_html($product['condition']['label']);
                    break;
                case 'reference':
                    if (!$product['reference_to_display']) {
                        continue 2;
                    }
                    $label = $label ?: $t->trans('Reference', [], 'Shop.Theme.Catalog');
                    $value = esc_html($product['reference_to_display']);
                    break;
                default:
                    // isbn, ean13, upc, mpn
                    $type = $meta['type'];
                    $attributes = $product['attributes'];
                    $attribute = reset($attributes);
                    $value = isset($attribute[$type]) ? $attribute[$type] : (
                        isset($product[$type]) ? $product[$type] : ''
                    );
                    if (!$value) {
                        continue 2;
                    }
                    $label = $label ?: \Tools::strtoupper($type);
                    $value = esc_html($value);
            }
            printf(
                '<span class="ce-product-meta__detail ce-product-meta__%s">' .
                '   <span class="ce-product-meta__label">%s</span>' .
                '   <span class="ce-product-meta__value">%s</span>' .
                '</span>',
                esc_attr($meta['type']),
                esc_html($label),
                $value
            );
        }
        echo '</div>';
    }

    protected function renderSmarty()
    {
        ?>
        <div class="ce-product-meta">
        <?php foreach ($this->getSettings('meta_list') as $meta) { ?>
            <?php if ('category' === $meta['type']) { ?>
                {if $product.id_category_default}
                    <span class="ce-product-meta__detail ce-product-meta__category">
                        <span class="ce-product-meta__label">
                            <?php echo $meta['label'] ?: "{Context::getContext()->getTranslator()->trans('Category: %category_name%', ['%category_name%' => ''], 'Shop.Theme.Catalog')|rtrim:': '}"; ?>
                        </span>
                        <a class="ce-product-meta__value" href="{url entity='category' id=$product.id_category_default}">{$product.category_name}</a>
                    </span>
                {/if}
            <?php } elseif ('manufacturer' === $meta['type']) { ?>
                {if $product.id_manufacturer}
                    <span class="ce-product-meta__detail ce-product-meta__manufacturer">
                        <span class="ce-product-meta__label"><?php echo $meta['label'] ?: "{l s='Brand' d='Shop.Theme.Catalog'}"; ?></span>
                        <a class="ce-product-meta__value" href="{Context::getContext()->link->getManufacturerLink($product.id_manufacturer)}">{Manufacturer::getNameById($product.id_manufacturer)}</a>
                    </span>
                {/if}
            <?php } elseif ('supplier' === $meta['type']) { ?>
                {if $product.id_supplier}
                    <span class="ce-product-meta__detail ce-product-meta__supplier">
                        <span class="ce-product-meta__label"><?php echo $meta['label'] ?: "{l s='Brand' d='Shop.Theme.Catalog'}"; ?></span>
                        <a class="ce-product-meta__value" href="{Context::getContext()->link->getSupplierLink($product.id_supplier)}">{Supplier::getNameById($product.id_supplier)}</a>
                    </span>
                {/if}
            <?php } elseif ('delivery' === $meta['type']) { ?>
                {$delivery = ''}
                {if 1 == $product.additional_delivery_times}
                    {$delivery = $product.delivery_information}
                {elseif 2 == $product.additional_delivery_times}
                    {if $product.quantity > 0}
                        {$delivery = $product.delivery_in_stock}
                    {elseif $product.add_to_cart_url}
                        {$delivery = $product.delivery_out_stock}
                    {/if}
                {/if}
                {if $delivery}
                    <span class="ce-product-meta__detail ce-product-meta__delivery">
                        <span class="ce-product-meta__label"><?php echo $meta['label'] ?: "{l s='Delivery Time' d='Admin.Catalog.Feature'}"; ?></span>
                        <span class="ce-product-meta__value">{$delivery}</span>
                    </span>
                {/if}
            <?php } elseif ('quantity' === $meta['type']) { ?>
                {if !empty($product.quantity) && $product.available_for_order && !$configuration.is_catalog}
                    <span class="ce-product-meta__detail ce-product-meta__quantity">
                        <span class="ce-product-meta__label"><?php echo $meta['label'] ?: "{l s='In stock' d='Shop.Theme.Catalog'}"; ?></span>
                        <span class="ce-product-meta__value">{$product.quantity} {l s='Items' d='Shop.Theme.Catalog'}</span>
                    </span>
                {/if}
            <?php } elseif ('availability_date' === $meta['type']) { ?>
                {if !empty($product.availability_date) && ($product.allow_oosp || 'unavailable' === $product.availability) && date('Y-m-d') < $product.availability_date}
                    <span class="ce-product-meta__detail ce-product-meta__availability_date">
                        <span class="ce-product-meta__label"><?php echo $meta['label'] ?: "{Context::getContext()->getTranslator()->trans('Availability date:', [], 'Shop.Theme.Catalog')|rtrim:': '}"; ?></span>
                        <span class="ce-product-meta__value">{Tools::displayDate($product.availability_date)}</span>
                    </span>
                {/if}
            <?php } elseif ('condition' === $meta['type']) { ?>
                {if !empty($product.condition)}
                    <span class="ce-product-meta__detail ce-product-meta__condition">
                        <span class="ce-product-meta__label"><?php echo $meta['label'] ?: "{l s='Condition' d='Shop.Theme.Catalog'}"; ?></span>
                        <span class="ce-product-meta__value">{$product.condition.label}</span>
                    </span>
                {/if}
            <?php } elseif ('reference' === $meta['type']) { ?>
                {if $product.reference}
                    <span class="ce-product-meta__detail ce-product-meta__reference">
                        <span class="ce-product-meta__label"><?php echo $meta['label'] ?: "{l s='Reference' d='Shop.Theme.Catalog'}"; ?></span>
                        <span class="ce-product-meta__value">{$product.reference}</span>
                    </span>
                {/if}
            <?php } else { ?>
                {$ref = <?php var_export($meta['type']); ?>}
                {if !empty($product[$ref])}
                    <span class="ce-product-meta__detail ce-product-meta__{$ref}">
                        <span class="ce-product-meta__label"><?php echo $meta['label'] ?: '{$ref|upper}'; ?></span>
                        <span class="ce-product-meta__value">{$product[$ref]}</span>
                    </span>
                {/if}
            <?php } ?>
        <?php } ?>
        </div>
        <?php
    }

    public function renderPlainContent()
    {
    }
}
