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

class ModulesXCatalogXTagsXCategoryUrl extends DataTag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'category-url';
    }

    public function getTitle()
    {
        return __('Category URL');
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
        $context = \Context::getContext();
        $vars = &$context->smarty->tpl_vars;
        $id_category = isset($vars['product']->value['id_category_default'])
            ? $vars['product']->value['id_category_default']
            : $vars['category']->value['id'];

        return $id_category && \Validate::isLoadedObject($category = new \Category($id_category, $context->language->id))
            ? $context->link->getCategoryLink($category)
            : '';
    }

    protected function getSmartyValue(array $options = [])
    {
        return '{if $product.id_category_default}{url entity=category id=$product.id_category_default}{/if}';
    }
}
