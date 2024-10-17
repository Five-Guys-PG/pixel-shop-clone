<?php
/**
 * Creative Elements - live PageBuilder
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

use CE\ModulesXCatalogXWidgetsXProductXImage as ProductImage;

class ModulesXCatalogXWidgetsXProductXMiniatureXImage extends ProductImage
{
    public function getName()
    {
        return 'product-miniature-image';
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->updateControl('link_to', [
            'type' => ControlsManager::HIDDEN,
            'default' => 'product',
        ]);
    }

    protected function shouldPrintEmpty()
    {
        return false;
    }

    protected function renderSmarty()
    {
        $settings = $this->getSettingsForDisplay();
        $index = $settings['image_index'] - 1;

        echo $index < 0
            ? '{if $product.cover}{$image = $product.cover}'
            : "{if isset(\$product.images[$index])}{\$image = \$product.images[$index]}";
        echo $settings['show_no_image']
            ? '{else}{$image = call_user_func("CE\Helper::getNoImage")}{/if}' : ''; ?>
        {$caption = <?php echo $settings['show_caption'] ? '$image.legend' : '""'; ?>}
        {$image_by_size = $image.bySize[<?php var_export($settings['image_size']); ?>]}
        {$ratio = round($image_by_size.width / $image_by_size.height, 3)}
        {$srcset = ["{$image_by_size.url} {$image_by_size.width}w"]}
        {foreach $image.bySize as $size => $img}
            {if <?php var_export($settings['image_size']); ?> !== $size && round($img.width / $img.height, 3) === $ratio}
                {$srcset[] = "{$img.url} {$img.width}w"}
            {/if}
        {/foreach}
        <div class="ce-product-image elementor-image">
        {if $caption}
            <figure class="ce-caption">
        {/if}
            <a href="{$product.url}">
                <img src="{$image_by_size.url}" srcset="{implode(', ', $srcset)}" alt="{$image.legend}" loading="lazy"
                <?php if ($settings['hover_animation']) { ?>
                    class="<?php echo esc_attr("elementor-animation-{$settings['hover_animation']}"); ?>"
                <?php } ?>
                    width="{$image_by_size.width}" height="{$image_by_size.height}"
                    sizes="(max-width: {$image_by_size.width}px) 100vw, {$image_by_size.width}px">
            </a>
        {if $caption}
            <figcaption class="widget-image-caption ce-caption-text">{$caption}</figcaption>
            </figure>
        {/if}
        </div>
        <?php
        $settings['show_no_image'] || print '{/if}';
    }
}
