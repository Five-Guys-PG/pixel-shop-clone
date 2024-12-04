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

class CERevision extends ObjectModel
{
    public $parent;
    public $id_employee;
    public $title;
    public $type;
    public $content;
    public $active;
    public $date_upd;

    public static $definition = [
        'table' => 'ce_revision',
        'primary' => 'id_ce_revision',
        'fields' => [
            'parent' => ['type' => self::TYPE_STRING, 'validate' => 'isIp2Long', 'required' => true],
            'id_employee' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'title' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255],
            'type' => ['type' => self::TYPE_STRING, 'validate' => 'isHookName', 'size' => 64],
            'content' => ['type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'],
            'active' => ['type' => self::TYPE_INT, 'validate' => 'isBool'],
            'date_upd' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
        ],
    ];

    public static function deleteAllByParent($parent)
    {
        $rows = Db::getInstance()->executeS(
            'SELECT `id_ce_revision` FROM ' . _DB_PREFIX_ . 'ce_revision WHERE `parent` LIKE "' . pSQL($parent) . '"'
        );
        if ($rows) {
            foreach ($rows as &$row) {
                (new CERevision($row['id_ce_revision']))->delete();
            }
        }
    }
}
