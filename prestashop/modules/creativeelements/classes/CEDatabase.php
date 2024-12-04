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

class CEDatabase
{
    private static $hooks = [
        'displayBackOfficeHeader',
        'displayHeader',
        'displayOverrideTemplate',
        'displayFooterProduct',
        'overrideLayoutTemplate',
        'CETemplate',
        // Actions
        'actionClearCompileCache',
        'actionFrontControllerAfterInit',
        'actionFrontControllerInitAfter',
        'actionObjectCERevisionDeleteAfter',
        'actionObjectCETemplateDeleteAfter',
        'actionObjectCEThemeDeleteAfter',
        'actionObjectCEContentDeleteAfter',
        'actionObjectProductDeleteAfter',
        'actionObjectCategoryDeleteAfter',
        'actionObjectManufacturerDeleteAfter',
        'actionObjectSupplierDeleteAfter',
        'actionObjectCmsDeleteAfter',
        'actionObjectCmsCategoryDeleteAfter',
        'actionObjectYbc_blog_post_classDeleteAfter',
        'actionObjectXipPostsClassDeleteAfter',
        'actionObjectStBlogClassDeleteAfter',
        'actionObjectBlogPostsDeleteAfter',
        'actionObjectNewsClassDeleteAfter',
        'actionObjectTvcmsBlogPostsClassDeleteAfter',
        'actionProductAdd',
        'filterProductSearch',
    ];

    public static function initConfigs()
    {
        $defaults = [
            // General
            'elementor_frontend_edit' => 1,
            'elementor_max_revisions' => 10,
            'elementor_disable_color_schemes' => 1,
            'elementor_disable_typography_schemes' => 1,
            // Style
            'elementor_default_generic_fonts' => 'sans-serif',
            'elementor_page_title_selector' => 'header.page-header',
            'elementor_page_wrapper_selector' => '#content, #wrapper, #wrapper .container',
            'elementor_viewport_lg' => 1025,
            'elementor_viewport_md' => 768,
            'elementor_global_image_lightbox' => 1,
            // Advanced
            'elementor_exclude_modules' => json_encode([
                'administration',
                'analytics_stats',
                'billing_invoicing',
                'checkout',
                'dashboard',
                'export',
                'emailing',
                'i18n_localization',
                'migration_tools',
                'payments_gateways',
                'payment_security',
                'quick_bulk_update',
                'seo',
                'shipping_logistics',
                'market_place',
            ]),
            'elementor_load_animate' => 1,
            'elementor_load_fontawesome' => 1,
            'elementor_load_sticky' => 1,
            'elementor_load_swiper' => 1,
        ];
        foreach ($defaults as $key => $value) {
            Configuration::hasKey($key) || Configuration::updateValue($key, $value);
        }
        copy(_CE_PATH_ . 'views/lib/filemanager/include.php', _PS_IMG_DIR_ . 'cms/config.php');
        (int) _PS_VERSION_ < 8 && copy(_CE_PATH_ . 'views/lib/filemanager/.htaccess', _PS_IMG_DIR_ . 'cms/.htaccess');
    }

    public static function createTables()
    {
        $db = Db::getInstance();

        return $db->execute('
            CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ce_meta (
                `id_ce_meta` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
                `name` varchar(255) DEFAULT NULL,
                `value` longtext,
                PRIMARY KEY (`id_ce_meta`),
                KEY `id` (`id`),
                KEY `name` (`name`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
        ') && $db->execute('
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
                KEY `parent` (`parent`),
                KEY `date_upd` (`date_upd`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
        ') && $db->execute('
            CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ce_template (
                `id_ce_template` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_employee` int(10) UNSIGNED NOT NULL,
                `title` varchar(128) NOT NULL DEFAULT "",
                `type` varchar(64) NOT NULL DEFAULT "",
                `content` longtext,
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_template`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
        ') && $db->execute('
            CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ce_content (
                `id_ce_content` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_employee` int(10) UNSIGNED NOT NULL,
                `id_product` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `hook` varchar(64) NOT NULL DEFAULT "",
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_content`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
        ') && $db->execute('
            CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ce_content_shop (
                `id_ce_content` int(10) UNSIGNED NOT NULL,
                `id_shop` int(10) UNSIGNED NOT NULL,
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_content`,`id_shop`),
                KEY `id_shop` (`id_shop`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
        ') && $db->execute('
            CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ce_content_lang (
                `id_ce_content` int(10) UNSIGNED NOT NULL,
                `id_lang` int(10) UNSIGNED NOT NULL,
                `id_shop` int(10) UNSIGNED NOT NULL DEFAULT 1,
                `title` varchar(128) NOT NULL DEFAULT "",
                `content` longtext,
                PRIMARY KEY (`id_ce_content`,`id_shop`,`id_lang`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
        ') && $db->execute('
            CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ce_theme (
                `id_ce_theme` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_employee` int(10) UNSIGNED NOT NULL,
                `type` varchar(64) NOT NULL DEFAULT "",
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_theme`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
        ') && $db->execute('
            CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ce_theme_shop (
                `id_ce_theme` int(10) UNSIGNED NOT NULL,
                `id_shop` int(10) UNSIGNED NOT NULL,
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_theme`,`id_shop`),
                KEY `id_shop` (`id_shop`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
        ') && $db->execute('
            CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ce_theme_lang (
                `id_ce_theme` int(10) UNSIGNED NOT NULL,
                `id_lang` int(10) UNSIGNED NOT NULL,
                `id_shop` int(10) UNSIGNED NOT NULL DEFAULT 1,
                `title` varchar(128) NOT NULL DEFAULT "",
                `content` text,
                PRIMARY KEY (`id_ce_theme`,`id_shop`,`id_lang`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
        ') && $db->execute('
            CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ce_font (
                `id_ce_font` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `family` varchar(128) NOT NULL DEFAULT "",
                `files` text,
                PRIMARY KEY (`id_ce_font`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
        ') && $db->execute('
            CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ce_icon_set (
                `id_ce_icon_set` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(128) NOT NULL DEFAULT "",
                `config` longtext,
                PRIMARY KEY (`id_ce_icon_set`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public static function updateTabs()
    {
        $id = (int) Tab::getIdFromClassName('IMPROVE');

        try {
            $pos = $id ? 1 : Tab::getInstanceFromClassName('AdminParentModules')->position;
            $parent = self::updateTab($id, $pos, 'AdminParentCEContent', true, ['en' => 'Creative Elements'], 'ce');

            self::updateTab($parent->id, 1, 'AdminCEThemes', true, [
                'en' => 'Theme Builder',
                'fr' => 'Constructeur de thème',
                'es' => 'Maquetador de temas',
                'it' => 'Generatore di temi',
                'de' => 'Theme Builder',
                'pl' => 'Kreator motywów',
            ]);
            self::updateTab($parent->id, 2, 'AdminCEContent', true, [
                'en' => 'Content Anywhere',
                'fr' => 'Contenu n’importe où',
                'es' => 'Contenido cualquier lugar',
                'it' => 'Contenuto Ovunque',
                'de' => 'Inhalt überall',
                'pl' => 'Treść w dowolnym miejscu',
            ]);
            self::updateTab($parent->id, 3, 'AdminCETemplates', true, [
                'en' => 'Saved Templates',
                'fr' => 'Modèles enregistrés',
                'es' => 'Plantillas guardadas',
                'it' => 'Template salvati',
                'de' => 'Gespeicherte Templates',
                'pl' => 'Zapisane szablony',
            ]);
            $custom_parent = self::updateTab($parent->id, 4, 'AdminParentCEFonts', true, [
                'en' => 'Fonts & Icons',
                'fr' => 'Polices & Icônes',
                'es' => 'Fuentes & Iconos',
                'it' => 'Font & Icone',
                'de' => 'Schriftarten & Icons',
                'pl' => 'Czcionki & Ikonki',
            ]);
            self::updateTab($custom_parent->id, 1, 'AdminCEFonts', true, [
                'en' => 'Custom Fonts',
                'fr' => 'Polices personnalisées',
                'es' => 'Fuentes personalizadas',
                'it' => 'Font Personalizzati',
                'de' => 'Eigene Schriftarten',
                'pl' => 'Własne czcionki',
            ]);
            self::updateTab($custom_parent->id, 2, 'AdminCEIconSets', true, [
                'en' => 'Custom Icons',
                'fr' => 'Icônes personnalisées',
                'es' => 'Iconos personalizadas',
                'it' => 'Icone personalizzate',
                'de' => 'Eigene Icons',
                'pl' => 'Własne ikonki',
            ]);
            self::updateTab($parent->id, 6, 'AdminCESettings', true, [
                'en' => 'Settings',
                'fr' => 'Réglages',
                'es' => 'Ajustes',
                'it' => 'Impostazioni',
                'de' => 'Einstellungen',
                'pl' => 'Ustawienia',
            ]);
            self::updateTab($parent->id, 7, 'AdminCEEditor', false, [
                'en' => 'Live Editor',
                'fr' => 'Éditeur en direct',
                'es' => 'Editor en vivo',
                'it' => 'Editor live',
                'de' => 'Live Editor',
                'pl' => 'Edytor na żywo',
            ]);
        } catch (Exception $ex) {
            return false;
        }

        return true;
    }

    protected static function updateTab($id_parent, $position, $class, $active, array $name, $icon = '')
    {
        $id = (int) Tab::getIdFromClassName($class);
        $tab = new Tab($id);
        $tab->id_parent = $id_parent;
        $tab->position = (int) $position;
        $tab->module = 'creativeelements';
        $tab->class_name = $class;
        $tab->active = $active;
        $tab->icon = $icon;
        $tab->name = [];

        foreach (Language::getLanguages(false) as $lang) {
            $code = $lang['locale'][0] . $lang['locale'][1];

            $tab->name[$lang['id_lang']] = isset($name[$code]) ? $name[$code] : $name['en'];
        }

        if (!$tab->save()) {
            throw new Exception('Can not save Tab: ' . $class);
        }

        if (!$id && $tab->position != $position) {
            $tab->position = (int) $position;
            $tab->update();
        }

        return $tab;
    }

    public static function getHooks($all = true)
    {
        $hooks = self::$hooks;

        if ($all) {
            $rows = Db::getInstance()->executeS(
                'SELECT DISTINCT `hook` FROM ' . _DB_PREFIX_ . 'ce_content'
            );
            if ($rows) {
                foreach ($rows as &$row) {
                    $hook = $row['hook'];

                    if ($hook && !in_array($hook, $hooks)) {
                        $hooks[] = $hook;
                    }
                }
            }
        }

        return $hooks;
    }
}
