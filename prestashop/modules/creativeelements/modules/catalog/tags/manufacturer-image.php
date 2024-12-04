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
use CE\ModulesXCatalogXControlsXSelectManufacturer as SelectManufacturer;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXCatalogXTagsXManufacturerImage extends DataTag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'manufacturer-image';
    }

    public function getTitle()
    {
        return __('Brand Image');
    }

    public function getGroup()
    {
        return Module::CATALOG_GROUP;
    }

    public function getCategories()
    {
        return [Module::IMAGE_CATEGORY];
    }

    public function getPanelTemplateSettingKey()
    {
        return 'image_size';
    }

    protected function _registerControls()
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
                    '0' => __('Current'),
                ],
                'default' => 0,
            ]
        );

        $this->addControl(
            'image_size',
            [
                'label' => __('Image Size'),
                'type' => ControlsManager::SELECT,
                'options' => GroupControlImageSize::getAllImageSizes('manufacturers', true),
            ]
        );
    }

    public function getValue(array $options = [])
    {
        $context = \Context::getContext();
        $vars = &$context->smarty->tpl_vars;
        $value = [
            'id' => '',
            'url' => '',
        ];
        if (!$id_manufacturer = $this->getSettings('id_manufacturer')) {
            if (!empty($vars['manufacturer']->value['id'])) {
                $id_manufacturer = $vars['manufacturer']->value['id'];
                $value['alt'] = $vars['manufacturer']->value['name'];
            } elseif (!empty($vars['product']->value['id_manufacturer'])) {
                $id_manufacturer = $vars['product']->value['id_manufacturer'];
                $value['alt'] = $vars['product']->value['manufacturer_name'];
            }
        }
        if ($id_manufacturer) {
            $value['url'] = $context->link->getManufacturerImageLink($id_manufacturer, $size = $this->getSettings('image_size'));
            empty($value['alt']) && $value['alt'] = __('Brand');

            $size && ($image_type = @array_column(\ImageType::getImagesTypes('manufacturers'), null, 'name')[$size]) && $value += [
                'width' => $image_type['width'],
                'height' => $image_type['height'],
            ];
        }

        return $value;
    }

    protected function getSmartyValue(array $options = [])
    {
        $image_size = $this->getSettings('image_size') ?: 'null';

        return [
            'id' => '',
            // Tmp fix: Absolute URLs need to contain ://
            'url' => '{*://*}' .
                '{if $product.id_manufacturer}' .
                    "{call_user_func([\$link, getManufacturerImageLink], \$product.id_manufacturer, $image_size)}" .
                '{else}' .
                    'data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIGhlaWdodD0nMCcvPg' .
                '{/if}',
            'alt' => __('Brand'),
        ];
    }
}
