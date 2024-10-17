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

function upgrade_module_2_10_1($module)
{
    Shop::isFeatureActive() && Shop::setContext(Shop::CONTEXT_ALL);

    // Register hooks
    $module->registerHook('displayOverrideTemplate');
    $module->registerHook('filterProductSearch', null, 1);

    // Alter DB tables character set
    $db = Db::getInstance();
    $res = $db->execute('ALTER TABLE ' . _DB_PREFIX_ . 'ce_meta CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci')
        && $db->execute('ALTER TABLE ' . _DB_PREFIX_ . 'ce_revision CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci')
        && $db->execute('ALTER TABLE ' . _DB_PREFIX_ . 'ce_template CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci')
        && $db->execute('ALTER TABLE ' . _DB_PREFIX_ . 'ce_content CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci')
        && $db->execute('ALTER TABLE ' . _DB_PREFIX_ . 'ce_content_shop CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci')
        && $db->execute('ALTER TABLE ' . _DB_PREFIX_ . 'ce_content_lang CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci')
        && $db->execute('ALTER TABLE ' . _DB_PREFIX_ . 'ce_theme CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci')
        && $db->execute('ALTER TABLE ' . _DB_PREFIX_ . 'ce_theme_shop CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci')
        && $db->execute('ALTER TABLE ' . _DB_PREFIX_ . 'ce_theme_lang CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci')
        && $db->execute('ALTER TABLE ' . _DB_PREFIX_ . 'ce_icon_set CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci')
        && $db->execute('ALTER TABLE ' . _DB_PREFIX_ . 'ce_font CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

    // Remove useless files
    array_map('unlink', array_filter([
        _CE_PATH_ . 'classes/assets/CEAssetManager-1.6.php',
        _CE_PATH_ . 'core/base/header-footer-base.php',
        _CE_PATH_ . 'core/base/theme-document.php',
        _CE_PATH_ . 'core/base/theme-page-document.php',
        _CE_PATH_ . 'core/document-types/content.php',
        _CE_PATH_ . 'core/document-types/footer.php',
        _CE_PATH_ . 'core/document-types/header.php',
        _CE_PATH_ . 'includes/editor.php',
        _CE_PATH_ . 'includes/base/widget-category-base.php',
        _CE_PATH_ . 'includes/base/widget-product-base.php',
        _CE_PATH_ . 'includes/managers/schemes.php',
        _CE_PATH_ . 'includes/widgets/ajax-search.php',
        _CE_PATH_ . 'includes/widgets/animated-headline.php',
        _CE_PATH_ . 'includes/widgets/breadcrumb.php',
        _CE_PATH_ . 'includes/widgets/call-to-action.php',
        _CE_PATH_ . 'includes/widgets/category-tree.php',
        _CE_PATH_ . 'includes/widgets/contact-form.php',
        _CE_PATH_ . 'includes/widgets/countdown.php',
        _CE_PATH_ . 'includes/widgets/currency-selector.php',
        _CE_PATH_ . 'includes/widgets/email-subscription.php',
        _CE_PATH_ . 'includes/widgets/facebook-button.php',
        _CE_PATH_ . 'includes/widgets/facebook-page.php',
        _CE_PATH_ . 'includes/widgets/flip-box.php',
        _CE_PATH_ . 'includes/widgets/image-hotspot.php',
        _CE_PATH_ . 'includes/widgets/image-slider.php',
        _CE_PATH_ . 'includes/widgets/language-selector.php',
        _CE_PATH_ . 'includes/widgets/layer-slider.php',
        _CE_PATH_ . 'includes/widgets/module.php',
        _CE_PATH_ . 'includes/widgets/nav-menu.php',
        _CE_PATH_ . 'includes/widgets/shopping-cart.php',
        _CE_PATH_ . 'includes/widgets/sign-in.php',
        _CE_PATH_ . 'includes/widgets/site-logo.php',
        _CE_PATH_ . 'includes/widgets/site-title.php',
        _CE_PATH_ . 'includes/widgets/testimonial-carousel.php',
        _CE_PATH_ . 'includes/widgets/trustedshops-reviews.php',
        _CE_PATH_ . 'modules/catalog/widgets/manufacturer-image.php',
        _CE_PATH_ . 'modules/dynamic-tags/tags/cart-rule-date-time.php',
        _CE_PATH_ . 'modules/dynamic-tags/tags/specific-price-rule-date-time.php',
        _CE_PATH_ . 'views/css/admin.css',
        _CE_PATH_ . 'views/css/media.css',
        _CE_PATH_ . 'views/js/admin.js',
        _CE_PATH_ . 'views/templates/front/preview-1.6.tpl',
        _CE_PATH_ . 'views/templates/front/theme/layouts/layout-product-block.tpl',
    ], 'file_exists'));

    foreach ([
        'includes/schemes',
        'includes/traits',
        'modules/creative',
        'modules/sticky',
        'views/lib/font-awesome/fonts',
        'views/lib/iris',
        'views/lib/jquery-easing',
        'views/lib/tinymce/plugins',
        'views/lib/tinymce/skins',
        'views/lib/wp-color-picker',
        'views/templates/front/theme-1.6',
    ] as $dir) {
        Tools::deleteDirectory(_CE_PATH_ . $dir);
    }
    array_map('unlink', glob(_CE_PATH_ . 'core/document-types/page-*.php', GLOB_NOSORT));
    array_map('unlink', glob(_CE_PATH_ . 'core/document-types/prod*.php', GLOB_NOSORT));
    array_map('unlink', glob(_CE_PATH_ . 'includes/widgets/product-*.php', GLOB_NOSORT));
    array_map('unlink', glob(_CE_PATH_ . 'modules/catalog/widgets/product-*.php', GLOB_NOSORT));

    return $res;
}
