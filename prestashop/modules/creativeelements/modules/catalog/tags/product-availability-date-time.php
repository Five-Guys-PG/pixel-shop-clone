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

class ModulesXCatalogXTagsXProductAvailabilityDateTime extends DataTag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-availability-date-time';
    }

    public function getTitle()
    {
        return __('Product') . ' ' . __('Availability Date');
    }

    public function getGroup()
    {
        return Module::CATALOG_GROUP;
    }

    public function getCategories()
    {
        return [Module::DATE_TIME_CATEGORY];
    }

    public function getValue(array $options = [])
    {
        return $GLOBALS['smarty']->tpl_vars['product']->value['availability_date'];
    }

    protected function getSmartyValue(array $options = [])
    {
        return '{$product.availability_date}';
    }
}
