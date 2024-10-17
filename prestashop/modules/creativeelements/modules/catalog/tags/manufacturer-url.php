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

use CE\CoreXDynamicTagsXDataTag as DataTag;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXCatalogXTagsXManufacturerUrl extends DataTag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'manufacturer-url';
    }

    public function getTitle()
    {
        return __('Brand URL');
    }

    public function getGroup()
    {
        return Module::CATALOG_GROUP;
    }

    public function getCategories()
    {
        return [Module::URL_CATEGORY];
    }

    public function getValue(array $options = [])
    {
        $vars = &$GLOBALS['smarty']->tpl_vars;

        return isset($vars['product_brand_url']) ? $vars['product_brand_url']->value : '';
    }

    protected function getSmartyValue(array $options = [])
    {
        return
            '{if $product.id_manufacturer}' .
                '{call_user_func([$link, getManufacturerLink], $product.id_manufacturer)}' .
            '{else}' .
                'javascript:;' .
            '{/if}';
    }
}
