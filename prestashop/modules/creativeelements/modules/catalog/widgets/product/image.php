<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ModulesXCatalogXWidgetsXProductXImage extends WidgetImage
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-image';
    }

    public function getTitle()
    {
        return __('Product Image');
    }

    public function getIcon()
    {
        return 'eicon-image';
    }

    public function getCategories()
    {
        return ['product-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'image', 'picture', 'product', 'cover', 'lightbox'];
    }

    protected function isDynamicContent()
    {
        return true;
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->startInjection([
            'type' => 'section',
            'at' => 'start',
            'of' => 'section_image',
        ]);

        $index_options = range(0, 10);
        $index_options[0] = __('Cover');

        $this->addControl(
            'image_index',
            [
                'label' => __('Image'),
                'type' => ControlsManager::SELECT2,
                'select2options' => [
                    'tags' => true,
                    'allowClear' => false,
                ],
                'options' => $index_options,
                'default' => '0',
            ]
        );

        $image_size_options = GroupControlImageSize::getAllImageSizes('products');

        $this->addControl(
            'image_size',
            [
                'label' => __('Image Size'),
                'type' => ControlsManager::SELECT,
                'options' => &$image_size_options,
                'default' => key($image_size_options),
            ]
        );

        $this->addControl(
            'show_no_image',
            [
                'label' => __('No Image'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'default' => 'yes',
                'condition' => [
                    'image_index' => '0',
                ],
            ]
        );

        $this->endInjection();

        $this->startInjection([
            'of' => 'align',
        ]);

        $this->addControl(
            'show_caption',
            [
                'label' => __('Caption'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
            ]
        );

        $this->endInjection();

        $this->updateControl(
            'link_to',
            [
                'options' => [
                    'none' => __('None'),
                    'file' => __('Lightbox'),
                    'product' => __('Product'),
                    'custom' => __('Custom URL'),
                ],
                'default' => 'file',
            ]
        );

        $this->removeControl('image');
        $this->removeControl('caption');
        $this->removeControl('open_lightbox');

        $this->updateControl('text_color', ['scheme' => '']);

        $this->updateControl('caption_typography_font_family', ['scheme' => '']);
        $this->updateControl('caption_typography_font_weight', ['scheme' => '']);
    }

    public function onImport($widget)
    {
        $sizes = array_column(\ImageType::getImagesTypes('products'), 'name');

        if (isset($widget['settings']['image_size']) && !in_array($widget['settings']['image_size'], $sizes)) {
            $home = \ImageType::getFormattedName('home');

            $widget['settings']['image_size'] = in_array($home, $sizes) ? $home : reset($sizes);
        }

        return $widget;
    }

    protected function shouldPrintEmpty()
    {
        return true;
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-image';
    }

    protected function render()
    {
        $product = $GLOBALS['smarty']->tpl_vars['product']->value;
        $settings = $this->getSettingsForDisplay();
        $index = (int) $settings['image_index'] - 1;

        if ($index < 0 && $product['cover']) {
            $image = $product['cover'];
        } elseif (isset($product['images'][$index])) {
            $image = $product['images'][$index];
        } else {
            if (!$settings['show_no_image']) {
                return;
            }
            $image = Helper::getNoImage();
        }

        $caption = $image['legend'];
        $image_by_size = &$image['bySize'][$settings['image_size']];
        $ratio = round($image_by_size['width'] / $image_by_size['height'], 3);
        $srcset = ["{$image_by_size['url']} {$image_by_size['width']}w"];

        foreach ($image['bySize'] as $size => &$img) {
            if ($settings['image_size'] !== $size && round($img['width'] / $img['height'], 3) === $ratio) {
                $srcset[] = "{$img['url']} {$img['width']}w";
            }
        }
        $this->addRenderAttribute('image', [
            'width' => $image_by_size['width'],
            'height' => $image_by_size['height'],
            'src' => $image_by_size['url'],
            'alt' => $caption,
            'srcset' => implode(', ', $srcset),
            'sizes' => "(max-width: {$image_by_size['width']}px) 100vw, {$image_by_size['width']}px",
        ]);

        if ($settings['hover_animation']) {
            $this->addRenderAttribute('image', 'class', 'elementor-animation-' . $settings['hover_animation']);
        }

        $has_caption = $settings['show_caption'] && $caption;
        $has_link = 'none' !== $settings['link_to'];

        switch ($settings['link_to']) {
            case 'file':
                empty($image['id_image']) || $this->addRenderAttribute('link', [
                    'href' => Helper::getProductImageLink($image),
                    'data-elementor-lightbox-slideshow' => "p-{$product['id_product']}-{$product['id_product_attribute']}",
                ]);
                break;
            case 'product':
                $this->addRenderAttribute('link', [
                    'href' => $product['url'],
                ]);
                break;
            case 'custom':
                $this->addRenderAttribute('link', [
                    'href' => $settings['link']['url'],
                    'target' => $settings['link']['is_external'] ? '_blank' : '_self',
                    'rel' => $settings['link']['nofollow'] ? 'nofollow' : '',
                ]);
                break;
        } ?>
        <div class="ce-product-image elementor-image">
        <?php if ($has_caption) { ?>
            <figure class="ce-caption">
        <?php } ?>
        <?php if ($has_link) { ?>
            <a <?php $this->printRenderAttributeString('link'); ?>>
        <?php } ?>
            <img <?php $this->printRenderAttributeString('image'); ?>>
        <?php if ($has_link) { ?>
            </a>
        <?php } ?>
        <?php if ($has_caption) { ?>
            <figcaption class="widget-image-caption ce-caption-text"><?php echo $caption; ?></figcaption>
            </figure>
        <?php } ?>
        </div>
        <?php
    }

    public function renderPlainContent()
    {
    }

    protected function contentTemplate()
    {
    }
}
