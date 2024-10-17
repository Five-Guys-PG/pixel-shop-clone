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

class CEContent extends ObjectModel
{
    public $id_employee;
    public $id_product = 0;
    public $title;
    public $hook;
    public $content;
    public $position;
    public $active;
    public $date_add;
    public $date_upd;

    public static $definition = [
        'table' => 'ce_content',
        'primary' => 'id_ce_content',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => [
            'id_employee' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'hook' => ['type' => self::TYPE_STRING, 'validate' => 'isHookName', 'required' => true, 'size' => 64],
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

    public function __construct($id = null, $id_lang = null, $id_shop = null, $translator = null)
    {
        parent::__construct($id, $id_lang, $id_shop, $translator);

        // Insert missing ce_content_lang row if new language was added
        if (!$this->id && $id && $id_lang && $id_shop && Shop::getShop($id_shop) && Language::getLanguage($id_lang) && self::getHookById($id) !== false) {
            Db::getInstance()->insert('ce_content_lang', [
                'id_ce_content' => (int) $id,
                'id_lang' => (int) $id_lang,
                'id_shop' => (int) $id_shop,
                'title' => '',
                'content' => '',
            ]) && parent::__construct($id, $id_lang, $id_shop, $translator);
        }
    }

    public function add($auto_date = true, $null_values = false)
    {
        $ctx = Context::getContext();
        $this->id_employee = $ctx->employee->id;

        $res = parent::add($auto_date, $null_values);

        if ($res && $this->hook && !empty($ctx->controller->module)) {
            $ctx->controller->module->registerHook($this->hook, Shop::getContextListShopID());
        }

        return $res;
    }

    public function update($null_values = false)
    {
        if ('0000-00-00 00:00:00' === $this->date_add) {
            $this->date_add = date('Y-m-d H:i:s');
        }
        $before = new self($this->id);

        if ($res = parent::update($null_values)) {
            $module = Context::getContext()->controller->module;
            // handle hook changes
            if ($before->hook && !method_exists($module, 'hook' . $before->hook) && !self::hasHook($before->hook)) {
                $module->unregisterHook($before->hook, Shop::getContextListShopID());
            }
            $this->hook && $module->registerHook($this->hook, Shop::getContextListShopID());
        }

        return $res;
    }

    public function delete()
    {
        $res = parent::delete();

        if ($res && 'displayFooterProduct' !== $this->hook) {
            $module = Context::getContext()->controller->module;
            $shops = Shop::getContextListShopID();

            // unregister hook if needed
            if (!method_exists($module, 'hook' . $this->hook) && !self::hasHook($this->hook)) {
                $module->unregisterHook($this->hook, $shops);
            }
        }

        return $res;
    }

    public static function hasHook($hook, $active = false)
    {
        return Db::getInstance()->getValue(
            'SELECT 1 FROM ' . _DB_PREFIX_ . 'ce_content WHERE `hook` LIKE "' . pSQL($hook) . '"' . ($active ? ' AND `active` = 1' : '')
        );
    }

    public static function getHookById($id)
    {
        return Db::getInstance()->getValue(
            'SELECT `hook` FROM ' . _DB_PREFIX_ . 'ce_content WHERE `id_ce_content` = ' . (int) $id
        );
    }

    public static function getIdsByHook($hook, $id_lang, $id_shop, $id_product = 0, $preview = false)
    {
        $id_preview = isset($preview->id, $preview->id_type) && CE\UId::CONTENT === $preview->id_type ? $preview->id : 0;

        $query = new DbQuery();
        $query->select('a.`id_ce_content` AS id')->from('ce_content', 'a');
        $query->leftJoin('ce_content_lang', 'b', 'a.`id_ce_content` = b.`id_ce_content`');
        $query->leftJoin('ce_content_shop', 'c', 'c.`id_ce_content` = a.`id_ce_content` AND c.`id_shop` = b.`id_shop`');
        $query->where('b.`id_lang` = ' . (int) $id_lang)->where('c.`id_shop` = ' . (int) $id_shop)->where('a.`hook` LIKE "' . pSQL($hook) . '"');
        $query->where($id_preview ? 'a.`active` = 1 OR a.`id_ce_content` = ' . (int) $id_preview : 'a.`active` = 1');
        $id_product && $query->where('a.`id_product` = 0 OR a.`id_product` = ' . (int) $id_product);
        $query->orderBy('a.`id_product` DESC');

        return Db::getInstance()->executeS($query) ?: [];
    }

    public static function getFooterProductId($id_product)
    {
        return (int) Db::getInstance()->getValue(
            'SELECT `id_ce_content` FROM ' . _DB_PREFIX_ . 'ce_content WHERE `id_product` = ' . (int) $id_product . ' AND `hook` = "displayFooterProduct"'
        );
    }

    public static function getMaintenanceId()
    {
        return (int) Db::getInstance()->getValue(
            'SELECT `id_ce_content` FROM ' . _DB_PREFIX_ . 'ce_content WHERE `hook` LIKE "displayMaintenance" ORDER BY `active` DESC'
        );
    }
}

Shop::addTableAssociation(CEContent::$definition['table'], ['type' => 'shop']);
Shop::addTableAssociation(CEContent::$definition['table'] . '_lang', ['type' => 'fk_shop']);
