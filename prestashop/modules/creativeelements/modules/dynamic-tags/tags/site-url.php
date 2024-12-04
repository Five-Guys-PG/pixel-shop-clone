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
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXDynamicTagsXTagsXSiteUrl extends DataTag
{
    public function getName()
    {
        return 'site-url';
    }

    public function getTitle()
    {
        return __('Shop URL');
    }

    public function getGroup()
    {
        return Module::SITE_GROUP;
    }

    public function getCategories()
    {
        return [Module::URL_CATEGORY];
    }

    public function getValue(array $options = [])
    {
        $url = \Context::getContext()->link->getPageLink('index');

        \Configuration::get('PS_REWRITING_SETTINGS') && $url = substr($url, 0, strrpos($url, '/') + 1);

        return $url;
    }
}
