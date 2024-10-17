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

use CE\ModulesXCatalogXTagsXProductAddToCart as ProductAddToCart;

class ModulesXCatalogXTagsXProductBuyNow extends ProductAddToCart
{
    const ACTION = 'buyNow';

    public function getName()
    {
        return 'product-buy-now';
    }

    public function getTitle()
    {
        return __('Buy Now');
    }
}
