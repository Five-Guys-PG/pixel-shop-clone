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

class ModulesXCatalogXTagsXProductQuickView extends DataTag
{
    public function getName()
    {
        return 'product-quick-view';
    }

    public function getTitle()
    {
        return __('Quick View');
    }

    public function getGroup()
    {
        return Module::ACTION_GROUP;
    }

    public function getCategories()
    {
        return [Module::URL_CATEGORY];
    }

    public function _registerControls()
    {
        $this->addControl(
            'id_product',
            [
                'label' => __('Product'),
                'type' => ControlsManager::SELECT2,
                'label_block' => true,
                'select2options' => [
                    'placeholder' => __('Current Product'),
                    'ajax' => [
                        'get' => 'Products',
                        'url' => Helper::getAjaxProductsListLink(),
                    ],
                ],
            ]
        );
    }

    public function getValue(array $options = [])
    {
        return Plugin::$instance->frontend->createActionHash('quickview', $this->getSettings());
    }
}
