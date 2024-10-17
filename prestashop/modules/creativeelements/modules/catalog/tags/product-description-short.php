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

class ModulesXCatalogXTagsXProductDescriptionShort extends Tag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-description-short';
    }

    public function getTitle()
    {
        return __('Product') . ' ' . __('Short Description');
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
        echo strip_tags($GLOBALS['smarty']->tpl_vars['product']->value['description_short']);
    }

    protected function renderSmarty()
    {
        echo '{$product.description_short|strip_tags:0}';
    }
}
