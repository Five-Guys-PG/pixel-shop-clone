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

class CETheme extends CETemplate
{
    public static $definition = [
        'table' => 'ce_theme',
        'primary' => 'id_ce_theme',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => [
            'id_employee' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'type' => ['type' => self::TYPE_STRING, 'validate' => 'isHookName', 'required' => true, 'size' => 64],
            // Shop fields
            'position' => ['type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isUnsignedInt'],
            'active' => ['type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isBool'],
            'date_add' => ['type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDate'],
            // Lang fields
            'title' => ['type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 128],
            'content' => ['type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'],
        ],
    ];

    public function delete()
    {
        $result = parent::delete();

        if ($result && 'product-miniature' === $this->type) {
            array_map('unlink', glob(_CE_TEMPLATES_ . "front/theme/catalog/_partials/miniatures/product-{$this->id}17????.tpl"));
        }

        return $result;
    }

    public static function getOptions($type, $id_lang, $id_shop)
    {
        return Db::getInstance()->executeS('
            SELECT t.`id_ce_theme` AS `value`, CONCAT("#", t.`id_ce_theme`, " ", tl.`title`) AS `name` FROM ' . _DB_PREFIX_ . 'ce_theme t
            INNER JOIN ' . _DB_PREFIX_ . 'ce_theme_shop ts ON t.`id_ce_theme` = ts.`id_ce_theme`
            INNER JOIN ' . _DB_PREFIX_ . 'ce_theme_lang tl ON t.`id_ce_theme` = tl.`id_ce_theme` AND ts.`id_shop` = tl.`id_shop`
            WHERE ts.`active` = 1 AND ts.`id_shop` = ' . (int) $id_shop . ' AND tl.`id_lang` = ' . (int) $id_lang . ' AND t.`type` = "' . pSQL($type) . '"
            ORDER BY tl.`title`
        ') ?: [];
    }
}

Shop::addTableAssociation(CETheme::$definition['table'], ['type' => 'shop']);
Shop::addTableAssociation(CETheme::$definition['table'] . '_lang', ['type' => 'fk_shop']);
