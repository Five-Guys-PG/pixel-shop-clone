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

class ModulesXCatalogXControlsXSelectManufacturer extends ControlSelect2
{
    const CONTROL_TYPE = 'select_manufacturer';

    private static $_manufacturers;

    public function getType()
    {
        return self::CONTROL_TYPE;
    }

    public static function getManufacturers()
    {
        if (is_admin() && null === self::$_manufacturers) {
            self::$_manufacturers = [];
            $rows = \Db::getInstance()->executeS(
                'SELECT `id_manufacturer` AS `id`, `name` FROM ' . _DB_PREFIX_ . 'manufacturer WHERE `active` = 1 ORDER BY `name`'
            );
            if ($rows) {
                foreach ($rows as &$row) {
                    self::$_manufacturers[$row['id']] = "#{$row['id']} {$row['name']}";
                }
            }
        }

        return self::$_manufacturers ?: [];
    }

    protected function getDefaultSettings()
    {
        return [
            'options' => self::getManufacturers(),
            'multiple' => false,
            'select2options' => [],
            'extend' => [],
        ];
    }

    public function onImport($id_manufacturer, array $control_data)
    {
        return isset(self::$_manufacturers[$id_manufacturer]) ? $id_manufacturer : $control_data['default'];
    }

    public function contentTemplate()
    {
        echo '<# $.extend( data.options, data.extend ) #>';

        parent::contentTemplate();
    }
}
