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

class ModulesXDynamicTagsXTagsXPageTitle extends Tag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'page-title';
    }

    public function getTitle()
    {
        return __('Page Title');
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
        $breadcrumb = $GLOBALS['smarty']->tpl_vars['breadcrumb']->value;

        echo esc_html($breadcrumb['links'][$breadcrumb['count'] - 1]['title']);
    }

    public function renderSmarty()
    {
        echo '{$breadcrumb.links[count($breadcrumb)-1].title}';
    }
}
