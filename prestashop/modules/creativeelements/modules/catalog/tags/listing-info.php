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

class ModulesXCatalogXTagsXListingInfo extends Tag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'listing-info';
    }

    public function getTitle()
    {
        return __('Listing Info');
    }

    public function getGroup()
    {
        return Module::CATALOG_GROUP;
    }

    public function getCategories()
    {
        return [Module::TEXT_CATEGORY];
    }

    public function getPanelTemplateSettingKey()
    {
        return 'info';
    }

    protected function _registerControls()
    {
        $this->addControl(
            'info',
            [
                'label' => __('Info'),
                'type' => ControlsManager::SELECT,
                'groups' => [
                    'listing' => [
                        'label' => __('Listing'),
                        'options' => [
                            'label' => __('Label'),
                        ],
                    ],
                    'pagination' => [
                        'label' => __('Pagination'),
                        'options' => [
                            'total_items' => __('Total Items'),
                            'items_shown_from' => __('Items Shown from'),
                            'items_shown_to' => __('Items Shown to'),
                            'current_page' => __('Current Page'),
                            'pages_count' => __('Pages Count'),
                        ],
                    ],
                ],
                'default' => 'total_items',
            ]
        );
    }

    public function render()
    {
        $listing = &$GLOBALS['smarty']->tpl_vars['listing']->value;

        if ('label' === $info = $this->getSettings('info')) {
            return print $listing[$info];
        }
        $pagination = &$listing['pagination'];
        // Fix: total_items correction for pricePostFiltering
        'total_items' === $info && 1 === $pagination['pages_count'] && $pagination['total_items'] = count($listing['products']);

        if (isset($_SERVER['HTTP_X_CE_PAGINATION'])) {
            'items_shown_from' === $info && $pagination['items_shown_from'] = 1;
            'items_shown_to' === $info && $pagination['items_shown_to'] = ($pagination['current_page'] - 1) * \Configuration::get('PS_PRODUCTS_PER_PAGE') + count($listing['products']);
        }
        empty($pagination[$info]) || print $pagination[$info];
    }
}
