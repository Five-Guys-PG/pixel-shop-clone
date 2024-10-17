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

use CE\CoreXDynamicTagsXDataTag as DataTag;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXCatalogXTagsXCartRuleDateTime extends DataTag
{
    public function getName()
    {
        return 'cart-rule-date-time';
    }

    public function getTitle()
    {
        return __('Cart Rule') . ' ' . __('Date');
    }

    public function getGroup()
    {
        return Module::CATALOG_GROUP;
    }

    public function getCategories()
    {
        return [Module::DATE_TIME_CATEGORY];
    }

    public function getPanelTemplateSettingKey()
    {
        return 'date';
    }

    protected function getCartRuleOptions()
    {
        $opts = [];
        $id_lang = \Context::getContext()->language->id;
        $rows = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT a.`id_cart_rule`, b.`name` FROM ' . _DB_PREFIX_ . 'cart_rule a ' .
            \Shop::addSqlAssociation('cart_rule', 'a') . '
            LEFT JOIN ' . _DB_PREFIX_ . 'cart_rule_lang b ON b.`id_cart_rule` = a.`id_cart_rule` AND b.`id_lang` = ' . (int) $id_lang . '
            WHERE a.`active` = 1 AND a.`date_to` > NOW()
        ');
        if ($rows) {
            foreach ($rows as &$row) {
                $opts[$row['id_cart_rule']] = "#{$row['id_cart_rule']} {$row['name']}";
            }
        }

        return $opts;
    }

    protected function _registerControls()
    {
        $this->addControl(
            'id_cart_rule',
            [
                'label' => __('Cart Rule'),
                'type' => ControlsManager::SELECT2,
                'label_block' => true,
                'select2options' => [
                    'placeholder' => __('Select...'),
                ],
                'options' => is_admin() ? $this->getCartRuleOptions() : [],
            ]
        );

        $this->addControl(
            'date',
            [
                'label' => __('Date'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'from' => __('From'),
                    'to' => __('To'),
                ],
                'default' => 'to',
            ]
        );
    }

    public function getValue(array $options = [])
    {
        $settings = $this->getSettings();
        $cr = new \CartRule($settings['id_cart_rule'], \Context::getContext()->language->id);

        return $cr->{"date_{$settings['date']}"};
    }

    protected function getSmartyValue(array $options = [])
    {
        $settings = $this->getSettings();

        return "{\$cr = ce_new(CartRule, {$settings['id_cart_rule']}, \$language.id)}{\$cr->date_{$settings['date']}}";
    }
}
