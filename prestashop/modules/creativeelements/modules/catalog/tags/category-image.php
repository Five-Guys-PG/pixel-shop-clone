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

class ModulesXCatalogXTagsXCategoryImage extends DataTag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'category-image';
    }

    public function getTitle()
    {
        return __('Category Image');
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
            'image_size',
            [
                'label' => __('Image Size'),
                'type' => ControlsManager::SELECT,
                'options' => GroupControlImageSize::getAllImageSizes('categories', true),
            ]
        );
    }

    public function getValue(array $options = [])
    {
        $context = \Context::getContext();
        $vars = &$context->smarty->tpl_vars;
        $size = $this->getSettings('image_size');
        $value = [
            'id' => '',
            'url' => '',
        ];
        if ($context->controller instanceof \CategoryController && $vars['category']->value['image']) {
            // Category array
            $category = &$vars['category']->value;
            isset($category['image']['bySize'][$size])
                ? $value = ['id' => ''] + $category['image']['bySize'][$size]
                : $value['url'] = $context->link->getCatImageLink($category['link_rewrite'], $category['id'], $size);
            $value['alt'] = $category['name'];
        } elseif (!empty($vars['category']->value->id_image)) {
            // Category object
            $category = $vars['category']->value;
            $value['url'] = $context->link->getCatImageLink($category->link_rewrite, $category->id, $size);
            $value['alt'] = $category->name;

            $size && ($image_type = @array_column(\ImageType::getImagesTypes('categories'), null, 'name')[$size]) && $value += [
                'width' => $image_type['width'],
                'height' => $image_type['height'],
            ];
        }

        return $value;
    }
}
