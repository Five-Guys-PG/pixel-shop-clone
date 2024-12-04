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

use CE\CoreXDynamicTagsXTag as Tag;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXDynamicTagsXTagsXSiteTitle extends Tag
{
    public function getName()
    {
        return 'site-title';
    }

    public function getTitle()
    {
        return __('Shop Title');
    }

    public function getGroup()
    {
        return Module::SITE_GROUP;
    }

    public function getCategories()
    {
        return [Module::TEXT_CATEGORY];
    }

    public function render()
    {
        echo \Configuration::get('PS_SHOP_NAME');
    }
}
