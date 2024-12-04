<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2024 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

use CE\CoreXDynamicTagsXDataTag as DataTag;
use CE\ModulesXCatalogXControlsXSelectCategory as SelectCategory;
use CE\ModulesXCatalogXControlsXSelectManufacturer as SelectManufacturer;
use CE\ModulesXCatalogXControlsXSelectSupplier as SelectSupplier;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXDynamicTagsXTagsXInternalURL extends DataTag
{
    public function getName()
    {
        return 'internal-url';
    }

    public function getGroup()
    {
        return Module::SITE_GROUP;
    }

    public function getCategories()
    {
        return [Module::URL_CATEGORY];
    }

    public function getTitle()
    {
        return __('Internal URL');
    }

    public function getPanelTemplateSettingKey()
    {
        return 'type';
    }

    protected function _registerControls()
    {
        $pages = [
            '' => __('Select...'),
            'index' => __('Home'),
            'listing' => [
                'label' => __('Listing'),
                'options' => [
                    'category' => __('Category'),
                    'manufacturer' => __('Brand'),
                    'supplier' => __('Supplier'),
                    'search' => __('Search'),
                    'prices-drop' => __('Prices Drop'),
                    'new-products' => __('New Products'),
                    'best-sales' => __('Best Sellers'),
                ],
            ],
            'product' => __('Product'),
            'cart' => __('Shopping Cart'),
            'contact' => __('Contact Page'),
            'cms' => __('CMS'),
            'usermenu' => [
                'label' => __('Usermenu'),
                'options' => [
                    'authentication' => __('Sign in'),
                    'my-account' => __('My account'),
                    'identity' => __('Personal info'),
                    'address' => __('New Address'),
                    'addresses' => __('Addresses'),
                    'history' => __('Order history'),
                    'order-slip' => __('Credit slip'),
                    'discount' => __('My vouchers'),
                    'logout' => __('Sign out'),
                ],
            ],
            'pagenotfound' => __('404 Page'),
        ];
        if (!\Configuration::get('PS_DISPLAY_BEST_SELLERS') || \Configuration::get('PS_CATALOG_MODE')) {
            unset($pages['listing']['options']['best-sales']);
        }
        if (!$display_suppliers = \Configuration::get('PS_DISPLAY_SUPPLIERS')) {
            unset($pages['listing']['options']['supplier']);
        }
        if (!$display_manufacturers = version_compare(_PS_VERSION_, '1.7.7', '<') ? $display_suppliers : \Configuration::get('PS_DISPLAY_MANUFACTURERS')) {
            unset($pages['listing']['options']['manufacturer']);
        }
        $this->addControl(
            'type',
            [
                'label' => __('Page'),
                'label_block' => true,
                'type' => ControlsManager::SELECT,
                'groups' => &$pages,
            ]
        );

        $this->addControl(
            'id_cms',
            [
                'label' => __('Search & Select'),
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'select2options' => [
                    'placeholder' => __('Type Here'),
                    'ajax' => [
                        'get' => 'Cms',
                    ],
                ],
                'condition' => [
                    'type' => 'cms',
                ],
            ]
        );

        $this->addControl(
            'id_product',
            [
                'label' => __('Search & Select'),
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'select2options' => [
                    'placeholder' => __('Type Product Name / Ref'),
                    'ajax' => [
                        'get' => 'Products',
                        'url' => Helper::getAjaxProductsListLink(),
                    ],
                ],
                'condition' => [
                    'type' => 'product',
                ],
            ]
        );

        $this->addControl(
            'id_category',
            [
                'label' => __('Search & Select'),
                'label_block' => true,
                'type' => SelectCategory::CONTROL_TYPE,
                'select2options' => [
                    'allowClear' => false,
                ],
                'default' => \Context::getContext()->shop->id_category,
                'condition' => [
                    'type' => 'category',
                ],
            ]
        );

        $display_manufacturers && $this->addControl(
            'id_manufacturer',
            [
                'label' => __('Search & Select'),
                'label_block' => true,
                'type' => SelectManufacturer::CONTROL_TYPE,
                'select2options' => [
                    'placeholder' => __('Select...'),
                ],
                'extend' => [
                    '0' => __('Listing'),
                ],
                'condition' => [
                    'type' => 'manufacturer',
                ],
            ]
        );

        $display_suppliers && $this->addControl(
            'id_supplier',
            [
                'label' => __('Search & Select'),
                'label_block' => true,
                'type' => SelectSupplier::CONTROL_TYPE,
                'select2options' => [
                    'placeholder' => __('Select...'),
                ],
                'extend' => [
                    '0' => __('Listing'),
                ],
                'condition' => [
                    'type' => 'supplier',
                ],
            ]
        );

        $this->addControl(
            'search',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Search') . '...',
                'condition' => [
                    'type' => 'search',
                ],
            ]
        );
    }

    public function getValue(array $options = [])
    {
        $settings = $this->getSettings();
        $type = $settings['type'];
        $method = "get{$type}Link";
        $context = \Context::getContext();

        if (method_exists('Link', $method) && !empty($settings["id_$type"])) {
            return $context->link->$method($settings["id_$type"]);
        }
        $id_lang = $context->language->id;

        if ('search' === $type && $settings['search']) {
            return $context->link->getPageLink($type, true, $id_lang, ['s' => $settings['search']]);
        }
        if ('logout' === $type) {
            return $context->link->getPageLink('index', true, $id_lang, 'mylogout');
        }

        return $context->link->getPageLink($type, true, $id_lang);
    }

    protected function getSmartyValue(array $options = [])
    {
        $settings = $this->getSettings();
        $type = $settings['type'];
        $method = "get{$type}Link";

        if (method_exists('Link', $method) && !empty($settings["id_$type"])) {
            return "{call_user_func([\$link, $method], {$settings["id_$type"]})}";
        }
        if ('search' === $type && $settings['search']) {
            return
                "{capture assign=ce_search}{$settings['search']}{/capture}" .
                '{call_user_func([$link, getPageLink], search, true, $language.id, array_combine([s], [$ce_search]))}';
        }
        if ('logout' === $type) {
            return '{call_user_func([$link, getPageLink], index, true, $language.id, mylogout)}';
        }

        return "{call_user_func([\$link, getPageLink], $type, true, \$language.id)}";
    }
}
