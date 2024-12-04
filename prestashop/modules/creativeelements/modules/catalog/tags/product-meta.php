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

use CE\CoreXDynamicTagsXTag as Tag;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXCatalogXTagsXProductMeta extends Tag
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

    public function getGroup()
    {
        return Module::CATALOG_GROUP;
    }

    public function getCategories()
    {
        return [Module::TEXT_CATEGORY];
    }

    public function getPanelTemplateSettingKey()
    {
        return 'type';
    }

    protected function _registerControls()
    {
        $this->addControl(
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
    }

    public function render()
    {
        $vars = &$GLOBALS['smarty']->tpl_vars;
        $product = $vars['product']->value;
        $type = $this->getSettings('type');

        switch ($type) {
            case 'category':
                echo esc_html($product['category_name']);
                break;
            case 'manufacturer':
                empty($vars['product_manufacturer']->value->name) || print esc_html($vars['product_manufacturer']->value->name);
                break;
            case 'supplier':
                $product['id_supplier'] && print esc_html(\Supplier::getNameById($product['id_supplier']));
                break;
            case 'delivery':
                if (1 == $product['additional_delivery_times']) {
                    echo $product['delivery_information'];
                } elseif (2 == $product['additional_delivery_times']) {
                    if ($product['quantity'] > 0) {
                        echo $product['delivery_in_stock'];
                    } elseif ($product['add_to_cart_url']) {
                        echo $product['delivery_out_stock'];
                    }
                }
                break;
            case 'quantity':
                $product['show_quantities'] && print esc_html(max(0, $product['quantity']) . " {$product['quantity_label']}");
                break;
            case 'condition':
                empty($product[$type]) || print esc_html($product[$type]['label']);
                break;
            case 'availability_date':
                echo esc_html(\Tools::displayDate($product[$type]));
                break;
            case 'reference':
                echo esc_html($product['reference_to_display']);
                break;
            default:
                // isbn, ean13, upc, mpn
                $attributes = $product['attributes'];
                $attribute = reset($attributes);

                if (!empty($attribute[$type])) {
                    echo esc_html($attribute[$type]);
                } elseif (!empty($product[$type])) {
                    echo esc_html($product[$type]);
                }
        }
    }

    protected function renderSmarty()
    {
        $type = $this->getSettings('type');

        switch ($type) {
            case 'category':
                echo '{$product.category_name}';
                break;
            case 'manufacturer':
                echo '{if $product.id_manufacturer}Manufacturer::getNameById($product.id_manufacturer){/if}';
                break;
            case 'supplier':
                echo '{if $product.id_supplier}Supplier::getNameById($product.id_supplier){/if}';
                break;
            case 'delivery':
                echo '{if 1 == $product.additional_delivery_times}{$product.delivery_information}';
                echo '{elseif 2 == $product.additional_delivery_times}{if $product.quantity > 0}{$product.delivery_in_stock}{elseif $product.add_to_cart_url}{$product.delivery_out_stock}{/if}';
                echo '{/if}';
                break;
            case 'quantity':
                echo '{if !empty($product.show_quantities)}{max(0, $product.quantity)} {$product.quantity_label}{/if}';
                break;
            case 'condition':
                echo '{if !empty($product.condition)}{$product.condition.label}{/if}';
                break;
            case 'availability_date':
                echo '{Tools::displayDate($product.availability_date)}';
                break;
            case 'reference':
                echo '{if !empty($product.reference_to_display)}{$product.reference_to_display}{/if}';
                break;
            default:
                // isbn, ean13, upc, mpn
                echo "{if !empty(\$product.$type)}{\$product.$type}{/if}";
        }
    }
}
