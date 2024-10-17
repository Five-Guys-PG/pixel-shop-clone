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

function upgrade_module_1_4_10($module)
{
    Shop::isFeatureActive() && Shop::setContext(Shop::CONTEXT_ALL);

    Configuration::updateValue('elementor_max_revisions', 10);
    Configuration::updateValue('elementor_page_title_selector', 'header.page-header');
    Configuration::updateValue('elementor_page_wrapper_selector', '#content, #wrapper, #wrapper .container');

    $result = Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ce_revision (
            `id_ce_revision` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `parent` bigint(20) UNSIGNED NOT NULL,
            `id_employee` int(10) UNSIGNED NOT NULL,
            `title` varchar(255) NOT NULL,
            `type` varchar(64) NOT NULL DEFAULT "",
            `content` longtext NOT NULL,
            `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
            `date_upd` datetime NOT NULL,
            PRIMARY KEY (`id_ce_revision`),
            KEY `id` (`parent`),
            KEY `date_add` (`date_upd`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
    ');
    $result &= $module->registerHook('actionObjectCERevisionDeleteAfter');
    $result &= $module->registerHook('actionProductAdd');
    $result &= $module->registerHook('CETemplate');

    return $result;
}
