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

class ModulesXCatalogXControlsXSelectSupplier extends ControlSelect2
{
    const CONTROL_TYPE = 'select_supplier';

    private static $_suppliers;

    public function getType()
    {
        return self::CONTROL_TYPE;
    }

    public static function getSuppliers()
    {
        if (is_admin() && null === self::$_suppliers) {
            self::$_suppliers = [];
            $rows = \Db::getInstance()->executeS(
                'SELECT `id_supplier` AS `id`, `name` FROM ' . _DB_PREFIX_ . 'supplier WHERE `active` = 1 ORDER BY `name`'
            );
            if ($rows) {
                foreach ($rows as &$row) {
                    self::$_suppliers[$row['id']] = "#{$row['id']} {$row['name']}";
                }
            }
        }

        return self::$_suppliers ?: [];
    }

    protected function getDefaultSettings()
    {
        return [
            'options' => self::getSuppliers(),
            'multiple' => false,
            'select2options' => [],
            'extend' => [],
        ];
    }

    public function onImport($id_supplier, array $control_data)
    {
        return isset(self::$_suppliers[$id_supplier]) ? $id_supplier : $control_data['default'];
    }

    public function contentTemplate()
    {
        echo '<# $.extend( data.options, data.extend ) #>';

        parent::contentTemplate();
    }
}
