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

class ModulesXPremiumXWidgetsXModule extends WidgetBase
{
    const REMOTE_RENDER = true;

    private static $product_hooks = [
        'displayproductadditionalinfo',
        'displayproductactions',
        'displayfooterproduct',
        'displaycartextraproductactions',
    ];

    protected $context;

    public function getName()
    {
        return 'ps-widget-module';
    }

    public function getTitle()
    {
        return __('Module');
    }

    public function getIcon()
    {
        return 'eicon-puzzle-piece';
    }

    public function getCategories()
    {
        return ['premium'];
    }

    public function getKeywords()
    {
        return ['module', 'hook'];
    }

    public function getModuleConfig($module)
    {
        $iso = $this->context->language->iso_code;
        $path = _PS_MODULE_DIR_ . "$module/config_$iso.xml";

        if ('en' === $iso || !file_exists($path)) {
            $path = _PS_MODULE_DIR_ . "$module/config.xml";

            if (!file_exists($path)) {
                return null;
            }
        }

        libxml_use_internal_errors(true);
        $config = @simplexml_load_file($path);
        libxml_clear_errors();

        return !$config ? null : (object) [
            'name' => (string) $config->name,
            'displayName' => (string) $config->displayName,
            'author' => (string) $config->author,
            'tab' => (string) $config->tab,
        ];
    }

    protected function getModuleOptions()
    {
        $modules = [];
        $exclude_tabs = json_decode(\Configuration::get('elementor_exclude_modules')) ?: [];
        $rows = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            'SELECT m.`name` FROM ' . _DB_PREFIX_ . 'module m ' . \Shop::addSqlAssociation('module', 'm') .
            ' WHERE m.`active` = 1 AND m.`name` NOT IN ("creativeelements", "creativepopup", "layerslider", "messengerchat")'
        );
        if ($rows) {
            foreach ($rows as &$row) {
                $mod = $this->getModuleConfig($row['name']);

                if ($mod && !in_array($mod->tab, $exclude_tabs)) {
                    $modules[$mod->name] = $mod->displayName ?: $mod->name;
                }
            }
        }

        return $modules;
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_module',
            [
                'label' => __('Module'),
            ]
        );

        $this->addControl(
            'module',
            [
                'show_label' => false,
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'select2options' => [
                    'placeholder' => __('Select...'),
                ],
                'options' => $this->context->controller instanceof \AdminCEEditorController ? $this->getModuleOptions() : [],
            ]
        );

        $this->addControl(
            'hook',
            [
                'label' => __('Hook'),
                'type' => ControlsManager::TEXT,
                'description' => __('Specify the required hook if needed.'),
                'input_list' => [
                    'displayHome',
                    'displayTop',
                    'displayBanner',
                    'displayNav1',
                    'displayNav2',
                    'displayNavFullWidth',
                    'displayTopColumn',
                    'displayLeftColumn',
                    'displayRightColumn',
                    'displayFooterBefore',
                    'displayFooter',
                    'displayFooterAfter',
                    'displayProductActions',
                    'displayProductAdditionalInfo',
                    'displayFooterProduct',
                ],
                'condition' => [
                    'module!' => '0',
                ],
            ]
        );

        $this->endControlsSection();
    }

    public function isInCustomerGroups(\Module $module)
    {
        if (!\Group::isFeatureActive()) {
            return true;
        }
        $customer = $this->context->customer;

        if ($customer instanceof \Customer && $customer->isLogged()) {
            $groups = $customer->getGroups();
        } elseif ($customer instanceof \Customer && $customer->isLogged(true)) {
            $groups = [\Configuration::get('PS_GUEST_GROUP')];
        } else {
            $groups = [\Configuration::get('PS_UNIDENTIFIED_GROUP')];
        }
        $id_shop = $this->context->shop->id;

        return (bool) \Db::getInstance()->getValue(
            'SELECT 1 FROM ' . _DB_PREFIX_ . 'module_group ' .
            'WHERE `id_module` = ' . (int) $module->id . ' AND `id_shop` = ' . (int) $id_shop . ' AND `id_group` IN (' . implode(', ', array_map('intval', $groups)) . ')'
        );
    }

    protected function renderModule($module, $hook_name, $hook_args = [])
    {
        $res = '';
        try {
            $mod = \Module::getInstanceByName($module);

            if (!empty($mod->active) && $this->isInCustomerGroups($mod)) {
                if (\Tools::getValue('render') === 'widget') {
                    try {
                        if (method_exists($mod, 'hookDisplayHeader')) {
                            $mod->hookDisplayHeader([]);
                        } elseif (method_exists($mod, 'hookHeader')) {
                            $mod->hookHeader([]);
                        }
                    } catch (\Exception $e) {
                        // do nothing
                    }
                }

                if (in_array(strtolower($hook_name), self::$product_hooks)) {
                    $vars = &$this->context->smarty->tpl_vars;

                    if (isset($vars['product']->value)) {
                        $hook_args['product'] = $vars['product']->value;
                    }
                    if (stripos($hook_name, 'footer') && isset($vars['category']->value)) {
                        $hook_args['category'] = $vars['category']->value;
                    }
                }

                if (method_exists($mod, "hook$hook_name")) {
                    $res = \Hook::coreCallHook($mod, "hook$hook_name", $hook_args);
                } elseif (method_exists($mod, 'renderWidget')) {
                    $res = \Hook::coreRenderWidget($mod, $hook_name, $hook_args);
                } elseif (method_exists($mod, '__call')) {
                    $res = \Hook::coreCallHook($mod, "hook$hook_name", $hook_args);
                }
            }
        } catch (\Exception $e) {
            $res = _PS_MODE_DEV_ ? $e->getMessage() : '';
        } catch (\Error $e) {
            $res = _PS_MODE_DEV_ ? $e->getMessage() : '';
        }

        return $res;
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        if ($settings['module']) {
            $hook = !empty($settings['hook']) ? $settings['hook'] : 'displayCEWidget';
            $html = $this->renderModule($settings['module'], $hook);

            if (!strcasecmp($hook, 'displayProductActions') || !strcasecmp($hook, 'displayProductAdditionalInfo') && 'ps_emailalerts' !== $settings['module']) {
                $html = preg_replace('/<(input|select|textarea) /i', '<$1 form="add-to-cart-or-refresh" ', $html);
            }
            echo "<!-- {$settings['module']} -->\n$html";
        }
    }

    public function renderPlainContent()
    {
    }

    public function __construct(array $data = [], $args = null)
    {
        $this->context = \Context::getContext();

        parent::__construct($data, $args);
    }
}
