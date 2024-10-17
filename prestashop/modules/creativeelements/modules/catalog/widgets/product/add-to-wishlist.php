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

class ModulesXCatalogXWidgetsXProductXAddToWishlist extends WidgetIcon
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-add-to-wishlist';
    }

    public function getTitle()
    {
        return __('Add to Wishlist');
    }

    public function getIcon()
    {
        return 'eicon-heart';
    }

    public function getCategories()
    {
        return ['product-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'product', 'wishlist', 'favorite'];
    }

    protected function isDynamicContent()
    {
        return true;
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        is_admin() && !\Module::isEnabled('blockwishlist') && $this->addControl(
            'notice',
            [
                'raw' => sprintf(__('%s module (%s) must be installed!'), __('Wishlist'), 'blockwishlist'),
                'type' => ControlsManager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
            ],
            [
                'position' => [
                    'at' => 'before',
                    'of' => 'selected_icon',
                ],
            ]
        );

        $this->updateControl('selected_icon', [
            'type' => ControlsManager::HIDDEN,
        ]);

        $this->updateControl('link', [
            'type' => ControlsManager::HIDDEN,
        ]);

        $this->updateControl('primary_color', [
            'scheme' => [],
        ]);
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-' . parent::getName();
    }

    protected function getProductsTagged()
    {
        static $tagged;

        if (null === $tagged) {
            $tagged = [];
            $context = \Context::getContext();

            if ($context->customer->isLogged()) {
                $js_def = &\Closure::bind(function &() {
                    return \Media::$js_def;
                }, null, 'Media')->__invoke();

                if (isset($js_def['productsAlreadyTagged'])) {
                    $tagged = $js_def['productsAlreadyTagged'];
                } elseif (file_exists(_PS_MODULE_DIR_ . 'blockwishlist/classes/WishList.php')) {
                    require_once _PS_MODULE_DIR_ . 'blockwishlist/classes/WishList.php';

                    $tagged = call_user_func(
                        'WishList::getAllProductByCustomer',
                        $context->customer->id,
                        $context->shop->id
                    ) ?: [];
                }
            }
        }

        return $tagged;
    }

    protected function render()
    {
        $context = \Context::getContext();
        $product = $context->smarty->tpl_vars['product']->value;
        $checked = array_filter($this->getProductsTagged(), function ($tagged) use ($product) {
            return $tagged['id_product'] == $product['id_product']
                && $tagged['id_product_attribute'] == $product['id_product_attribute'];
        });
        $this->setSettings('selected_icon', [
            'value' => $checked ? 'ceicon-heart' : 'ceicon-heart-o',
            'library' => 'ce-icons',
        ]);
        $this->setSettings('link', [
            'url' => $context->link->getModuleLink('blockwishlist', 'action'),
        ]);

        $this->addRenderAttribute('icon-wrapper', [
            'class' => 'ce-add-to-wishlist',
            'data-product-id' => $product['id_product'],
            'data-product-attribute-id' => $product['id_product_attribute'],
        ]);

        if ($checked) {
            $this->addRenderAttribute('icon-wrapper', 'class', 'elementor-active');
        }

        parent::render();
    }

    protected function renderSmarty()
    {
        $this->addRenderAttribute('icon-wrapper', [
            'class' => ['elementor-icon', '{$atw_class}'],
            'data-product-id' => '{$product.id}',
            'data-product-attribute-id' => '{$product.id_product_attribute}',
        ]);
        $this->addLinkAttributes('icon-wrapper', [
            'url' => '{$js_custom_vars.blockwishlistController}',
        ]);

        if ($hover_animation = $this->getSettings('hover_animation')) {
            $this->addRenderAttribute('icon-wrapper', 'class', "elementor-animation-$hover_animation");
        } ?>
        {$atw_class = 'ce-add-to-wishlist'}
        {$atw_icon = 'ceicon-heart-o'}
        {if !isset($js_custom_vars.productsAlreadyTagged)}
            {$js_custom_vars.productsAlreadyTagged = []}
            {$js_custom_vars.blockwishlistController = {url entity='module' name='blockwishlist' controller='action'}}
        {/if}
        {foreach $js_custom_vars.productsAlreadyTagged as $tagged}
            {if $tagged.id_product == $product.id && $tagged.id_product_attribute == $product.id_product_attribute}
                {$atw_icon = 'ceicon-heart'}
                {$atw_class = 'ce-add-to-wishlist elementor-active'}
                {break}
            {/if}
        {/foreach}
        <div class="elementor-icon-wrapper">
            <a <?php $this->printRenderAttributeString('icon-wrapper'); ?>>
                <i class="{$atw_icon}" aria-hidden="true"></i>
            </a>
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
