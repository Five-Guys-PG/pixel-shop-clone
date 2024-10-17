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

use CE\ModulesXCatalogXControlsXSelectManufacturer as SelectManufacturer;
use CE\ModulesXCatalogXDocumentsXListingXPage as ListingPageDocument;
use CE\ModulesXCatalogXWidgetsXListingXDescription as ListingDescription;
use CE\ModulesXCatalogXWidgetsXListingXImage as ListingImage;
use CE\ModulesXCatalogXWidgetsXListingXPageTitle as PageTitle;
use CE\ModulesXCatalogXWidgetsXManufacturerXShortDescription as ShortDescription;

class ModulesXCatalogXDocumentsXListingXManufacturer extends ListingPageDocument
{
    public function getName()
    {
        return 'listing-manufacturer';
    }

    public static function getTitle()
    {
        return __('Brand Page');
    }

    protected static function getEditorPanelCategories()
    {
        return [
            'listing-elements' => [
                'title' => __('Brand'),
            ],
        ] + parent::getEditorPanelCategories();
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['category'] = 'brand';

        return $config;
    }

    public static function getTagClasses()
    {
        return array_merge(
            [
                'ManufacturerName',
                'ManufacturerUrl',
            ],
            parent::getTagClasses()
        );
    }

    protected function getPermalinkUrl(\Link $link, $id_lang, $id_shop, array $args, $relative = true)
    {
        $settings = $this->getData('settings');
        $id_manufacturer = !empty($settings['id_manufacturer'])
            ? $settings['id_manufacturer']
            : Helper::getLastUpdatedManufacturerId();

        return add_query_arg($args, $link->getManufacturerLink($id_manufacturer, null, $id_lang, $id_shop, $relative));
    }

    protected function registerPreviewSettings()
    {
        $this->addControl(
            'id_manufacturer',
            [
                'label' => __('Brand'),
                'type' => SelectManufacturer::CONTROL_TYPE,
                'label_block' => true,
                'select2options' => [
                    'allowClear' => false,
                ],
                'extend' => [
                    '0' => __('Default'),
                ],
                'default' => 0,
                'export' => false,
            ]
        );
    }

    public static function registerWidgets($widgets_manager)
    {
        parent::registerWidgets($widgets_manager);

        $widgets_manager->registerWidgetType(new PageTitle([], null, [
            'title' => __('Brand Name'),
            'icon' => 'eicon-product-title',
            'categories' => ['listing-elements' => 1],
            'keywords' => ['shop', 'store', 'heading', 'title', 'name', 'brand', 'manufacturer'],
            'dynamic_tag_name' => 'manufacturer-name',
        ]));
        $widgets_manager->registerWidgetType(new ListingImage([], null, [
            'title' => __('Brand Image'),
            'icon' => 'eicon-logo',
            'keywords' => ['shop', 'store', 'brand', 'manufacturer', 'image', 'picture', 'product'],
            'dynamic_tag_name' => 'manufacturer-image',
        ]));
        $widgets_manager->registerWidgetType(new ShortDescription());
        $widgets_manager->registerWidgetType(new ListingDescription([], null, [
            'title' => __('Brand Description'),
            'keywords' => ['shop', 'store', 'text', 'description', 'brand', 'manufacturer'],
        ]));
    }
}
