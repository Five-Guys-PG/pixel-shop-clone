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

use CE\CoreXSettingsXManager as SettingsManager;

function upgrade_module_2_9_4($module)
{
    require_once _CE_PATH_ . 'classes/CEDatabase.php';

    Shop::isFeatureActive() && Shop::setContext(Shop::CONTEXT_ALL);

    // Generate Kit
    $kit_uid = CE\Plugin::instance()->kits_manager->getActiveId();
    $kit_settings = CE\get_post_meta($kit_uid, '_elementor_page_settings', true) ?: [];

    foreach (['container_width', 'space_between_widgets'] as $key) {
        if (${$key} = Configuration::get("elementor_$key")) {
            $kit_settings[$key] = [
                'size' => ${$key},
                'unit' => 'px',
            ];
        }
        Configuration::deleteByName("elementor_$key");
    }
    foreach (['lightbox_color', 'lightbox_ui_color', 'lightbox_ui_color_hover', 'lightbox_text_color'] as $key) {
        if (${$key} = Configuration::get("elementor_$key")) {
            $kit_settings[$key] = ${$key};
        }
        Configuration::deleteByName("elementor_$key");
    }
    if ($colors = CE\get_option('elementor_scheme_color-picker')) {
        $kit_settings['custom_colors'] = [];

        foreach ($colors as $i => $color) {
            $kit_settings['custom_colors'][] = [
                '_id' => CE\Utils::generateRandomString(),
                'title' => "Color #$i",
                'color' => $color,
            ];
        }
        Configuration::deleteByName('elementor_scheme_color-picker');
    }

    $page_settings_manager = SettingsManager::getSettingsManagers('page');
    $page_settings_manager->saveSettings($kit_settings, "$kit_uid");

    // Custom Fonts moved to global configuration
    if ($font_types = CE\get_post_meta(0, 'elementor_fonts_manager_font_types', true)) {
        Configuration::updateGlobalValue('elementor_fonts_manager_font_types', json_encode($font_types));
    }
    if ($fonts = CE\get_post_meta(0, 'elementor_fonts_manager_fonts', true)) {
        Configuration::updateGlobalValue('elementor_fonts_manager_fonts', json_encode($fonts));
    }
    CE\delete_post_meta(0, 'elementor_fonts_manager_font_types');
    CE\delete_post_meta(0, 'elementor_fonts_manager_fonts');

    // Add Custom Icons
    CEDatabase::updateTabs();
    CEDatabase::initConfigs();

    $result = Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ce_icon_set (
            `id_ce_icon_set` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` varchar(128) NOT NULL DEFAULT "",
            `config` longtext,
            PRIMARY KEY (`id_ce_icon_set`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
    ');

    return $result;
}
