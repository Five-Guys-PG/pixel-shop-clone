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

class CEIconSet extends ObjectModel
{
    public $name;
    public $config;

    public static $definition = [
        'table' => 'ce_icon_set',
        'primary' => 'id_ce_icon_set',
        'fields' => [
            'name' => ['type' => self::TYPE_STRING, 'validate' => 'isImageTypeName', 'required' => true, 'size' => 128],
            'config' => ['type' => self::TYPE_STRING, 'validate' => 'isJson'],
        ],
    ];

    public static function nameExists($name, $exclude_id = 0)
    {
        return (bool) Db::getInstance()->getValue(
            'SELECT `id_ce_icon_set` FROM ' . _DB_PREFIX_ . 'ce_icon_set WHERE `name` = "' . pSQL($name) . '" AND `id_ce_icon_set <> ' . (int) $exclude_id
        );
    }

    public static function prefixExists($prefix)
    {
        if (!$config = self::getCustomIconsConfig()) {
            return false;
        }
        foreach ($config as $icon_set_name => $icon_config) {
            if ($prefix === $icon_config['prefix']) {
                return true;
            }
        }

        return false;
    }

    public static function getSupportedIconSets()
    {
        return [
            'fontastic' => 'CE\ModulesXFontsManagerXIconSetsXFontastic',
            'fontello' => 'CE\ModulesXFontsManagerXIconSetsXFontello',
            'icomoon' => 'CE\ModulesXFontsManagerXIconSetsXIcomoon',
        ];
    }

    public static function getCustomIconsConfig($regenerate = false)
    {
        $config = json_decode(Configuration::getGlobalValue('elementor_custom_icon_sets_config'), true);

        if (null === $config || $regenerate) {
            $config = [];
            $rows = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'ce_icon_set');

            if ($rows) {
                foreach ($rows as $icon_set) {
                    $set_config = json_decode($icon_set['config'], true);
                    $set_config['custom_icon_post_id'] = $icon_set['id_ce_icon_set'];
                    $set_config['label'] = $icon_set['name'];

                    if (isset($set_config['fetchJson'])) {
                        unset($set_config['icons']);
                    }
                    $config[$set_config['name']] = $set_config;
                }
            }
            Configuration::updateGlobalValue('elementor_custom_icon_sets_config', json_encode($config));
        }

        return $config;
    }

    public function add($auto_date = true, $null_values = false)
    {
        if ($result = parent::add($auto_date, $null_values)) {
            self::getCustomIconsConfig(true);
        }

        return $result;
    }

    public function update($null_values = false)
    {
        if ($result = parent::update($null_values)) {
            self::getCustomIconsConfig(true);
        }

        return $result;
    }

    public function delete()
    {
        $config = json_decode($this->config, true);

        if ($result = parent::delete()) {
            self::getCustomIconsConfig(true);

            if (!empty($config['name']) && preg_match('/^[-\w]+$/', $config['name'])) {
                Tools::deleteDirectory(_CE_PATH_ . 'views/lib/custom-icons/' . $config['name']);
            }
        }

        return $result;
    }
}
