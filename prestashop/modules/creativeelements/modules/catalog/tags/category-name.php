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

class ModulesXCatalogXTagsXCategoryName extends Tag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'category-name';
    }

    public function getTitle()
    {
        return __('Category Name');
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

        echo isset($vars['product']->value['category_name'])
            ? $vars['product']->value['category_name']
            : $vars['category']->value['name']
        ;
    }

    protected function renderSmarty()
    {
        echo '{$product.category_name}';
    }
}
