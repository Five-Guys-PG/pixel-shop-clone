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

class ModulesXCatalogXTagsXProductPrice extends Tag
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
                'options' => [
                    'currency' => __('Currency'),
                    'price' => __('Price'),
                    'price_amount' => __('Price Amount'),
                    'price_integer' => __('Price Integer'),
                    'price_decimals' => __('Price Decimals'),
                    'regular_price' => __('Regular'),
                    'regular_price_amount' => __('Regular Amount'),
                    'price_tax_exc' => __('Price (tax excl.)'),
                    'discount' => __('Discount'),
                    'unit_price' => __('Unit Price'),
                    'unity' => __('Unit'),
                    'unit_price_full' => __('Unit Price') . ' (' . _x('Full', 'Image Size Control') . ')',
                ],
                'default' => 'price',
            ]
        );
    }

    public function render()
    {
        $vars = &$GLOBALS['smarty']->tpl_vars;
        $product = $vars['product']->value;

        if (!$product['show_price']) {
            return;
        }
        $type = $this->getSettings('type');

        switch ($type) {
            case 'currency':
                echo $vars['currency']->value['sign'];
                break;
            case 'price':
            case 'price_amount':
            case 'unity':
            case 'unit_price':
            case 'unit_price_full':
                echo $product[$type];
                break;
            case 'price_integer':
                echo (int) $product['price_amount'];
                break;
            case 'price_decimals':
                echo str_pad(round(100 * ($product['price_amount'] - (int) $product['price_amount'])), 2, '0', 0);
                break;
            case 'regular_price':
            case 'regular_price_amount':
                $product['has_discount'] && print $product[$type];
                break;
            case 'price_tax_exc':
                echo \Tools::displayPrice($product[$type]);
                break;
            case 'discount':
                $product['has_discount'] && print $product[
                    'percentage' === $product['discount_type'] ? 'discount_percentage_absolute' : 'discount_to_display'
                ];
                break;
        }
    }

    protected function renderSmarty()
    {
        $type = $this->getSettings('type');

        echo '{if $product.show_price}';

        switch ($type) {
            case 'currency':
                echo '{$currency.sign}';
                break;
            case 'price':
            case 'price_amount':
            case 'unity':
            case 'unit_price':
            case 'unit_price_full':
                echo "{\$product.$type}";
                break;
            case 'price_integer':
                echo '{$product.price_amount|intval}';
                break;
            case 'price_decimals':
                echo '{str_pad(intval(100 * ($product.price_amount - $product.price_amount|intval)), 2, 0, 0)}';
                break;
            case 'regular_price':
            case 'regular_price_amount':
                echo "{if \$product.has_discount}{\$product.$type}{/if}";
                break;
            case 'price_tax_exc':
                echo '{Tools::displayPrice($product.price_tax_exc)}';
                break;
            case 'discount':
                echo '{if $product.has_discount}' .
                        '{if percentage === $product.discount_type}' .
                            '{$product.discount_percentage_absolute}' .
                        '{else}' .
                            '{$product.discount_to_display}' .
                        '{/if}' .
                    '{/if}';
                break;
        }
        echo '{/if}';
    }
}
