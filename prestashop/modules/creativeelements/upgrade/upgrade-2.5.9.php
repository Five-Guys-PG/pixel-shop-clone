<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_5_9($module)
{
    Shop::isFeatureActive() && Shop::setContext(Shop::CONTEXT_ALL);

    if (isset($module->context->cookie->ceViewedProducts)) {
        $products = explode(',', $module->context->cookie->ceViewedProducts);
        $products = array_slice($products, 0, $module::VIEWED_PRODUCTS_LIMIT);

        $module->context->cookie->ceViewedProducts = implode(',', $products);
        $module->context->cookie->write();
    }

    return true;
}
