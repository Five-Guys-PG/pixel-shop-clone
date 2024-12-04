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

class ModulesXCatalogXTagsXManufacturerImages extends DataTag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'manufacturer-images';
    }

    public function getTitle()
    {
        return __('Brand Images');
    }

    public function getGroup()
    {
        return Module::CATALOG_GROUP;
    }

    public function getCategories()
    {
        return [Module::GALLERY_CATEGORY];
    }

    public function getPanelTemplateSettingKey()
    {
        return 'image_size';
    }

    protected function _registerControls()
    {
        $this->addControl(
            'image_size',
            [
                'label' => __('Image Size'),
                'label_block' => true,
                'type' => ControlsManager::SELECT,
                'options' => GroupControlImageSize::getAllImageSizes('manufacturers', true),
            ]
        );

        $this->addControl(
            'caption',
            [
                'label' => __('Caption'),
                'type' => ControlsManager::SELECT,
                'options' => $options = [
                    '' => __('None'),
                    'name' => __('Brand Name'),
                    'short_description' => __('Short Description'),
                    'description' => __('Brand Description'),
                    'custom' => __('Custom'),
                ],
                'default' => 'name',
            ]
        );

        $this->addControl(
            'caption_text',
            [
                'show_label' => false,
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Enter your image caption'),
                'condition' => [
                    'caption' => 'custom',
                ],
            ]
        );

        $this->addControl(
            'description',
            [
                'label' => __('Description'),
                'type' => ControlsManager::SELECT,
                'options' => $options,
            ]
        );

        $this->addControl(
            'description_text',
            [
                'show_label' => false,
                'label_block' => true,
                'type' => ControlsManager::TEXTAREA,
                'rows' => 2,
                'placeholder' => __('Enter your description'),
                'condition' => [
                    'description' => 'custom',
                ],
            ]
        );
    }

    public function getValue(array $options = [])
    {
        $context = \Context::getContext();
        $brands = \Manufacturer::getManufacturers(false, $context->language->id);
        $settings = $this->getSettings();
        $image_size = $settings['image_size'];
        $image_types = array_column(\ImageType::getImagesTypes('manufacturers'), null, 'name');
        $image_sizes = isset($image_types[$image_size]) ? [
            'width' => $image_types[$image_size]['width'],
            'height' => $image_types[$image_size]['height'],
        ] : [];
        $image_sizes || $image_size = '';
        $caption = $settings['caption'];
        $description = $settings['description'];
        $items = [];

        foreach ($brands as &$brand) {
            $items[] = [
                'image' => [
                    'id' => '',
                    'url' => $context->link->getManufacturerImageLink($brand['id_manufacturer'], $image_size),
                    'alt' => $brand['name'],
                ] + $image_sizes,
                'link' => [
                    'url' => $context->link->getManufacturerLink(
                        $brand['id_manufacturer'],
                        $brand['link_rewrite'],
                        $context->language->id,
                        $context->shop->id
                    ),
                ],
                'caption' => $caption ? (
                    'custom' === $caption ? $settings['caption_text'] : strip_tags($brand[$caption])
                ) : '',
                'description' => $description ? strip_tags(
                    'custom' === $description ? $settings['description_text'] : $brand[$description]
                ) : '',
            ];
        }

        return $items;
    }
}
