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

class ModulesXCatalogXTagsXListingClearUrl extends DataTag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'listing-clear-url';
    }

    public function getTitle()
    {
        return __('Clear Filters URL');
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
        return Helper::getClearAllLink();
    }
}
