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

function upgrade_module_2_5_7($module)
{
    // Create table for Custom Fonts
    $result = Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ce_font (
            `id_ce_font` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `family` varchar(128) NOT NULL DEFAULT "",
            `files` text,
            PRIMARY KEY (`id_ce_font`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
    ');
    // Register delete action for ThemeVolty Blog post
    $result &= $module->registerHook('actionObjectTvcmsBlogPostsClassDeleteAfter');

    return $result;
}
