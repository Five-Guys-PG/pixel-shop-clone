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
use CE\ModulesXCatalogXControlsXSelectCategory as SelectCategory;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXCatalogXTagsXCategoryImages extends DataTag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'category-images';
    }

    public function getTitle()
    {
        return __('Category Images');
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

    public static function getSubCategories($id_category, $caption, $description, \Context $context)
    {
        if ($id_category > 1) {
            $id = $id_category;
        } elseif ($context->controller instanceof \CategoryController) {
            if (!$id_category || $context->smarty->tpl_vars['subcategories']->value) {
                return $context->smarty->tpl_vars['subcategories']->value;
            }
            $id = $context->smarty->tpl_vars['category']->value['id_parent'];
        } elseif ($context->controller instanceof \ProductController) {
            $id = $context->controller->getProduct()->id_category_default;
        } else {
            $id = $context->shop->id_category;
        }

        if (array_intersect([$caption, $description], ['description', 'additional_description', 'meta_title', 'meta_description'])) {
            $category = new \Category();
            $category->id = $id;
            $subcategories = $category->getSubCategories($context->language->id);
        } else {
            $subcategories = \Category::getChildren($id, $context->language->id, true, $context->shop->id);
        }

        return $subcategories;
    }

    protected function _registerControls()
    {
        $this->addControl(
            'image_size',
            [
                'label' => __('Image Size'),
                'label_block' => true,
                'type' => ControlsManager::SELECT,
                'options' => GroupControlImageSize::getAllImageSizes('categories', true),
            ]
        );

        $this->addControl(
            'id_category',
            [
                'label' => __('Root Category'),
                'label_block' => true,
                'type' => SelectCategory::CONTROL_TYPE,
                'select2options' => [
                    'allowClear' => false,
                ],
                'extend' => [
                    '0' => __('Current Category') . ' / ' . __('Default'),
                    '1' => __('Current Category') . ' / ' . __('Parent Category'),
                ],
                'default' => 0,
            ]
        );

        $options = [
            '' => __('None'),
            'name' => __('Category Name'),
            'description' => __('Category Description'),
            'additional_description' => __('Additional Description'),
            'meta_title' => __('Meta Title'),
            'meta_description' => __('Meta Description'),
            'custom' => __('Custom'),
        ];
        if ((int) _PS_VERSION_ < 8) {
            unset($options['additional_description']);
        }
        $this->addControl(
            'caption',
            [
                'label' => __('Caption'),
                'type' => ControlsManager::SELECT,
                'options' => $options,
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
        $settings = $this->getSettings();
        $cats = self::getSubCategories(
            $settings['id_category'],
            $caption = $settings['caption'],
            $description = $settings['description'],
            $context = \Context::getContext()
        );
        $image_size = $settings['image_size'];
        $image_types = array_column(\ImageType::getImagesTypes('categories'), null, 'name');
        $image_sizes = isset($image_types[$image_size]) ? [
            'width' => $image_types[$image_size]['width'],
            'height' => $image_types[$image_size]['height'],
        ] : [];
        $image_sizes || $image_size = '';
        $items = [];

        foreach ($cats as &$cat) {
            isset($cat['id_image'])
                || $cat['id_image'] = \Tools::file_exists_cache(_PS_CAT_IMG_DIR_ . $cat['id_category'] . '.jpg') ? $cat['id_category'] : '';
            $items[] = [
                'image' => [
                    'id' => '',
                    'url' => $context->link->getCatImageLink($cat['link_rewrite'], (int) $cat['id_image'] ?: $context->language->iso_code, $image_size),
                    'alt' => $cat['name'],
                ] + $image_sizes,
                'link' => [
                    'url' => isset($cat['url']) ? $cat['url'] : $context->link->getCategoryLink($cat['id_category'], $cat['link_rewrite'], $context->language->id, null, $context->shop->id),
                ],
                'caption' => $caption ? (
                    'custom' === $caption ? $settings['caption_text'] : strip_tags($cat[$caption])
                ) : '',
                'description' => $description ? strip_tags(
                    'custom' === $description ? $settings['description_text'] : $cat[$description]
                ) : '',
            ];
        }

        return $items;
    }
}
