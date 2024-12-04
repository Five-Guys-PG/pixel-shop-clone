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

class ModulesXCatalogXTagsXManufacturerName extends Tag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'manufacturer-name';
    }

    public function getTitle()
    {
        return __('Brand Name');
    }

    public function getGroup()
    {
        return Module::CATALOG_GROUP;
    }

    public function getCategories()
    {
        return [Module::TEXT_CATEGORY];
    }

    public function render()
    {
        $vars = &$GLOBALS['smarty']->tpl_vars;

        if (!empty($vars['product']->value['id_manufacturer'])) {
            echo (int) _PS_VERSION_ < 8 ? (
                new \Manufacturer($vars['product']->value['id_manufacturer'], $vars['language']->value['id'])
            )->name : $vars['product']->value['manufacturer_name'];
        } elseif (isset($vars['manufacturer'])) {
            echo $vars['manufacturer']->value['name'];
        }
    }

    protected function renderSmarty()
    {
        echo (int) _PS_VERSION_ < 8 ? (
            '{if $product.id_manufacturer}' .
                '{$ce_brand = ce_new(Manufacturer, $product.id_manufacturer, $language.id)}' .
                '{$ce_brand->name}' .
            '{/if}'
        ) : '{$product.manufacturer_name}';
    }
}
