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

use CE\ModulesXCatalogXWidgetsXProductXBox as ProductBox;

class ModulesXCatalogXWidgetsXProductXMiniatureXBox extends ProductBox
{
    const REMOTE_RENDER = true;

    const SELECTOR = '{{WRAPPER}} .elementor-product-miniature';

    const SELECTOR_HOVER = '{{WRAPPER}} .elementor-product-miniature:hover';

    public function getName()
    {
        return 'product-miniature-box';
    }

    public function getTitle()
    {
        return __('Product Box');
    }

    public function getIcon()
    {
        return 'eicon-info-box';
    }

    public function getCategories()
    {
        return ['product-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'product', 'box'];
    }

    protected function getDefaultProductId()
    {
        return '';
    }

    public static function getSkinOptions()
    {
        return [];
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->updateControl(
            'skin',
            [
                'type' => ControlsManager::HIDDEN,
                'default' => 'custom',
            ]
        );

        $this->updateControl(
            'product_id',
            [
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Current Product'),
                ],
                'separator' => '',
            ]
        );

        $this->removeControl('description_length');

        $this->updateControl('title_color', ['scheme' => '']);
        $this->updateControl('category_color', ['scheme' => '']);
        $this->updateControl('description_color', ['scheme' => '']);

        $this->updateControl('title_typography_font_family', ['scheme' => '']);
        $this->updateControl('title_typography_font_weight', ['scheme' => '']);

        $this->updateControl('category_typography_font_family', ['scheme' => '']);
        $this->updateControl('category_typography_font_weight', ['scheme' => '']);

        $this->updateControl('description_typography_font_family', ['scheme' => '']);
        $this->updateControl('description_typography_font_weight', ['scheme' => '']);
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-product-box';
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $product = $GLOBALS['smarty']->tpl_vars['product']->value;

        echo $this->fetchMiniature($settings, $product);
    }

    protected function renderSmarty()
    {
        $flags = self::FLAGS;
        $settings = $this->getSettingsForDisplay();
        // BC fix
        $settings['qv_icon_align'] = $this->getSettings('qv_icon_align');
        $settings['atc_icon_align'] = $this->getSettings('atc_icon_align');

        $image_size = $settings['image_size'] ?: $this->imageSize;
        $image_size_tablet = $settings['image_size_tablet']
            && $settings['image_size_tablet'] !== $image_size ? $settings['image_size_tablet'] : '';
        $image_size_mobile = $settings['image_size_mobile']
            && $settings['image_size_mobile'] !== $image_size_tablet ? $settings['image_size_mobile'] : '';

        $this->setRenderAttribute('cover', [
            'src' => "{\$cover.bySize.$image_size.url}",
            'alt' => '{if !empty($cover.legend)}{$cover.legend}{else}{$product.name}{/if}',
            'width' => "{\$cover.bySize.$image_size.width}",
            'height' => "{\$cover.bySize.$image_size.height}",
        ]);
        $settings['show_second_image'] && $this->setRenderAttribute('image', [
            'src' => "{\$image.bySize.$image_size.url}",
            'alt' => '{$image.legend}',
            'width' => "{\$image.bySize.$image_size.width}",
            'height' => "{\$image.bySize.$image_size.height}",
        ]); ?>
        {if empty($urls.no_picture_image)}
            {$urls.no_picture_image = call_user_func('CE\Helper::getNoImage')}
        {/if}
        {$cover = _q_c_($product.cover, $product.cover, $urls.no_picture_image)}
        <div class="elementor-product-miniature">
            <a class="elementor-product-link" href="{$product.url}">
                <div class="elementor-image">
                    <picture class="elementor-cover-image">
                    <?php if ($image_size_mobile) { ?>
                        <source media="(max-width: 767px)" srcset="{$cover.bySize.<?php echo $image_size_mobile; ?>.url}">
                    <?php } ?>
                    <?php if ($image_size_tablet) { ?>
                        <source media="(max-width: 991px)" srcset="{$cover.bySize.<?php echo $image_size_tablet; ?>.url}">
                    <?php } ?>
                        <img <?php $this->printRenderAttributeString('cover'); ?> loading="lazy">
                    </picture>
            <?php if ($settings['show_second_image']) { ?>
                {$index = _q_c_(!empty($cover.position) && 1 < $cover.position, 0, 1)}
                {if !empty($product.images[$index])}
                    {$image = $product.images[$index]}
                    <picture class="elementor-second-image">
                    <?php if ($image_size_mobile) { ?>
                        <source media="(max-width: 767px)" srcset="{$image.bySize.<?php echo $image_size_mobile; ?>.url}">
                    <?php } ?>
                    <?php if ($image_size_tablet) { ?>
                        <source media="(max-width: 991px)" srcset="{$image.bySize.<?php echo $image_size_tablet; ?>.url}">
                    <?php } ?>
                        <img <?php $this->printRenderAttributeString('image'); ?> loading="lazy">
                    </picture>
                {/if}
            <?php } ?>
                <?php if ($settings['show_qv']) { ?>
                    <div class="elementor-button elementor-quick-view" data-link-action="quickview">
                        <span class="elementor-button-content-wrapper">
                        <?php if ($qv_icon = IconsManager::getBcIcon($settings, 'qv_icon')) { ?>
                            <?php $qv_icon_align = "elementor-align-icon-{$settings['qv_icon_align']}"; ?>
                            <span class="elementor-button-icon <?php echo esc_attr($qv_icon_align); ?>"><?php echo $qv_icon; ?></span>
                        <?php } ?>
                            <span class="elementor-button-text"><?php echo $settings['qv_text']; ?></span>
                        </span>
                    </div>
                <?php } ?>
                </div>
            <?php foreach (['left', 'right'] as $position) { ?>
                <div class="elementor-badges-<?php echo $position; ?>">
                <?php foreach ($settings['show_badges'] as $badge) { ?>
                    <?php if ($position === $settings["badge_{$badge}_position"]) { ?>
                        {if !empty(<?php echo "\$product.flags['{$flags[$badge]}']"; ?>)}
                            <div class="elementor-badge elementor-badge-<?php echo $badge; ?>">
                                <?php echo $settings["badge_{$badge}_text"] ?: "{\$product.flags['{$flags[$badge]}'].label}"; ?>
                            </div>
                        {/if}
                    <?php } ?>
                <?php } ?>
                </div>
            <?php } ?>
                <div class="elementor-content">
                <?php if ($settings['show_category']) { ?>
                    <h4 class="elementor-category">{$product.category_name}</h4>
                <?php } ?>
                    <<?php echo $settings['title_tag']; ?> class="elementor-title">{$product.name}</<?php echo $settings['title_tag']; ?>>
                <?php if ($settings['show_description']) { ?>
                    <div class="elementor-description">{$product.description_short|strip_tags:0}</div>
                <?php } ?>
                {if $product.show_price}
                    <div class="elementor-price-wrapper">
                <?php if ($settings['show_regular_price']) { ?>
                    {if $product.has_discount}
                        <span class="elementor-price-regular">{$product.regular_price}</span>
                    {/if}
                <?php } ?>
                        <span class="elementor-price">{$product.price}</span>
                    </div>
                {/if}
                </div>
            </a>
        <?php if ($settings['show_atc']) { ?>
            {if !$configuration.is_catalog && $product.available_for_order}
                {if $product.add_to_cart_url}
                    {$atc_url = $product.add_to_cart_url}
                {else}
                    {$atc_url = {url entity='cart' ssl=true params=[
                        'add' => 1,
                        'id_product' => $product.id_product|intval,
                        'ipa' => $product.id_product_attribute|intval,
                        'token' => Tools::getToken(false)
                    ]}}
                {/if}
                <form class="elementor-atc" action="{$atc_url}">
                    <input type="hidden" name="qty" value="{max(1, $product[_q_c_(
                        !empty($product.product_attribute_minimal_quantity),
                        'product_attribute_minimal_quantity',
                        'minimal_quantity'
                    )])}">
                    <button type="submit" class="elementor-button elementor-size-<?php echo $settings['atc_size']; ?>"
                        data-button-action="add-to-cart" {if isset($product.flags.out_of_stock)}disabled{/if}>
                        <span class="elementor-button-content-wrapper">
                        <?php if ($atc_icon = IconsManager::getBcIcon($settings, 'atc_icon')) { ?>
                            <span class="elementor-atc-icon elementor-align-icon-<?php echo $settings['atc_icon_align']; ?>">
                                <?php echo $atc_icon; ?>
                            </span>
                        <?php } ?>
                            <span class="elementor-button-text"><?php echo $settings['atc_text']; ?></span>
                        </span>
                    </button>
                </form>
            {/if}
        <?php } ?>
        </div>
        <?php
    }
}
