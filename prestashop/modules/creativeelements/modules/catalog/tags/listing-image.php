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

class ModulesXCatalogXTagsXListingImage extends DataTag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'listing-image';
    }

    public function getTitle()
    {
        return __('Listing Image');
    }

    public function getGroup()
    {
        return Module::CATALOG_GROUP;
    }

    public function getCategories()
    {
        return [Module::IMAGE_CATEGORY];
    }

    protected function _registerControls()
    {
        $this->startControlsTabs('listing_tabs');

        $this->startControlsTab(
            'listing_tab_categoty',
            [
                'label' => __('Category'),
            ]
        );

        $this->addControl(
            'category_size',
            [
                'label' => __('Image Size'),
                'type' => ControlsManager::SELECT,
                'options' => GroupControlImageSize::getAllImageSizes('categories', true),
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'listing_tab_manuafacturer',
            [
                'label' => __('Brand'),
            ]
        );

        $this->addControl(
            'manufacturer_size',
            [
                'label' => __('Image Size'),
                'type' => ControlsManager::SELECT,
                'options' => GroupControlImageSize::getAllImageSizes('manufacturers', true),
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();
    }

    public function getValue(array $options = [])
    {
        $context = \Context::getContext();
        $vars = &$context->smarty->tpl_vars;
        $value = [
            'id' => '',
            'url' => '',
        ];
        if (!empty($vars['category']->value['image'])) {
            $category = &$vars['category']->value;
            $size = $this->getSettings('category_size');
            isset($category['image']['bySize'][$size])
                ? $value = ['id' => ''] + $category['image']['bySize'][$size]
                : $value['url'] = $context->link->getCatImageLink($category['link_rewrite'], $category['id'], $size);
            $value['alt'] = $category['name'];
        } elseif (!empty($vars['manufacturer']->value['id'])) {
            $manufacturer = &$vars['manufacturer']->value;
            $size = $this->getSettings('manufacturer_size');
            $value['url'] = $context->link->getManufacturerImageLink($manufacturer['id'], $size);
            $size && ($image_type = @array_column(\ImageType::getImagesTypes('manufacturers'), null, 'name')[$size]) && $value += [
                'width' => $image_type['width'],
                'height' => $image_type['height'],
            ];
            $value['alt'] = $manufacturer['name'];
        }

        return $value;
    }
}
