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

class AdminCEContentController extends ModuleAdminController
{
    public $bootstrap = true;

    public $table = 'ce_content';

    public $identifier = 'id_ce_content';

    public $className = 'CEContent';

    public $lang = true;

    protected $_defaultOrderBy = 'title';

    protected $hooks = [
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
        'displayAfterBodyOpeningTag',
        'displayShoppingCart',
        'displayShoppingCartFooter',
        'displayShoppingCartFooter',
        'displayFooterProduct',
        'displayNotFound',
    ];

    public function __construct()
    {
        parent::__construct();

        if ((Tools::getIsset('updatece_content') || Tools::getIsset('addce_content')) && Shop::getContextShopID() === null) {
            $this->displayWarning(
                $this->trans('You are in a multistore context: any modification will impact all your shops, or each shop of the active group.', [], 'Admin.Catalog.Notification')
            );
        }

        $this->_select = 'sa.*';
        $this->_join = 'LEFT JOIN ' . _DB_PREFIX_ . 'ce_content_shop sa ON sa.`id_ce_content` = a.`id_ce_content` AND b.`id_shop` = sa.`id_shop`';
        $this->_where = 'AND sa.`id_shop` = ' . (int) $this->context->shop->id . ' AND a.`id_product` = 0';

        $this->fields_list = [
            'id_ce_content' => [
                'title' => $this->trans('ID', [], 'Admin.Global'),
                'class' => 'fixed-width-xs',
                'align' => 'center',
            ],
            'title' => [
                'title' => $this->trans('Title', [], 'Admin.Global'),
            ],
            'hook' => [
                'title' => $this->trans('Position', [], 'Admin.Global'),
                'class' => 'fixed-width-xl',
            ],
            'date_add' => [
                'title' => $this->trans('Created on', [], 'Modules.Facetedsearch.Admin'),
                'filter_key' => 'sa!date_add',
                'class' => 'fixed-width-lg',
                'type' => 'datetime',
            ],
            'date_upd' => [
                'title' => $this->l('Modified on'),
                'filter_key' => 'sa!date_upd',
                'class' => 'fixed-width-lg',
                'type' => 'datetime',
            ],
            'active' => [
                'title' => $this->trans('Status', [], 'Admin.Global'),
                'filter_key' => 'sa!active',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
            ],
        ];

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->trans('Delete selected', [], 'Admin.Notifications.Info'),
                'icon' => 'icon-trash text-danger',
                'confirm' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Info'),
            ],
        ];
    }

    public function ajaxProcessHideEditor()
    {
        $id = (int) Tools::getValue('id');
        $id_type = (int) Tools::getValue('idType');

        $uids = CE\UId::getBuiltList($id, $id_type, $this->context->shop->id);
        $res = empty($uids) ? $uids : array_keys($uids[$this->context->shop->id]);

        exit(json_encode($res));
    }

    public function ajaxProcessMigrate()
    {
        if ($ids = Tools::getValue('ids')) {
            require_once _CE_PATH_ . 'classes/CEMigrate.php';

            $done = [];

            foreach ($ids as $id) {
                CEMigrate::moveContent($id, $this->module) && $done[] = (int) $id;
            }
            $res = CEMigrate::removeIds('content', $done);

            exit(json_encode($res));
        }
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        version_compare(_PS_VERSION_, '1.7.7', '<') && $this->addJquery();
        $this->js_files[] = _MODULE_DIR_ . 'creativeelements/views/lib/e-select2/js/e-select2.full.min.js?v=4.0.6-rc1';
        $this->css_files[_MODULE_DIR_ . 'creativeelements/views/lib/e-select2/css/e-select2.min.css?v=4.0.6-rc1'] = 'all';
    }

    public function initHeader()
    {
        parent::initHeader();

        $display_suppliers = Configuration::get('PS_DISPLAY_SUPPLIERS');
        $display_manufacturers = version_compare(_PS_VERSION_, '1.7.7', '<')
            ? $display_suppliers
            : Configuration::get('PS_DISPLAY_MANUFACTURERS');
        $id_lang = $this->context->language->id;
        $link = $this->context->link;
        $tabs = &$this->context->smarty->tpl_vars['tabs']->value;

        foreach ($tabs as &$tab0) {
            foreach ($tab0['sub_tabs'] as &$tab1) {
                if ('AdminParentCEContent' === $tab1['class_name']) {
                    foreach ($tab1['sub_tabs'] as &$tab2) {
                        if ('AdminCEContent' === $tab2['class_name']) {
                            $sub_tabs = &$tab2['sub_tabs'];

                            $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminCEContent'));
                            $tab['current'] = true;
                            $tab['href'] = $link->getAdminLink('AdminCEContent');
                            $sub_tabs[] = $tab;

                            $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminCmsContent'));
                            $tab['current'] = '';
                            $tab['href'] = $link->getAdminLink('AdminCmsContent');
                            $sub_tabs[] = $tab;

                            $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminProducts'));
                            $tab['current'] = '';
                            $tab['href'] = $link->getAdminLink('AdminProducts');
                            $sub_tabs[] = $tab;

                            $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminCategories'));
                            $tab['current'] = '';
                            $tab['href'] = $link->getAdminLink('AdminCategories');
                            $sub_tabs[] = $tab;

                            if ($display_manufacturers) {
                                $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminManufacturers'));
                                $tab['current'] = '';
                                $tab['href'] = $link->getAdminLink('AdminManufacturers');
                                $sub_tabs[] = $tab;
                            }

                            if ($display_suppliers) {
                                $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminSuppliers'));
                                $tab['current'] = '';
                                $tab['href'] = $link->getAdminLink('AdminSuppliers');
                                $sub_tabs[] = $tab;
                            }

                            return;
                        }
                    }
                }
            }
        }
    }

    public function initToolBarTitle()
    {
        if ('add' === $this->display) {
            $this->page_header_toolbar_title = $this->l('Add New');
        } elseif ('edit' === $this->display) {
            $this->page_header_toolbar_title = sprintf($this->l('Edit %s'), $this->l('Content'));
        } else {
            $this->page_header_toolbar_title = $this->l('Place Content Anywhere');
        }

        $this->context->smarty->assign('icon', 'icon-list');

        $this->toolbar_title[] = $this->l('Contents List');
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['addce_content'] = [
                'href' => self::$currentIndex . '&addce_content&token=' . $this->token,
                'desc' => $this->trans('Add new', [], 'Admin.Actions'),
                'icon' => 'process-icon-new',
            ];
        }
        parent::initPageHeaderToolbar();
    }

    public function initContent()
    {
        $this->context->smarty->assign([
            'current_tab_level' => 3,
            'ce_hooks' => $this->hooks,
        ]);

        return parent::initContent();
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function renderForm()
    {
        $col = count(Language::getLanguages(false, false, true)) > 1 ? 9 : 7;

        version_compare(_PS_VERSION_, '1.7.8', '<') || --$col;

        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Content'),
                'icon' => 'icon-edit',
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->trans('Title', [], 'Admin.Global'),
                    'name' => 'title',
                    'lang' => true,
                    'col' => $col,
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Position', [], 'Admin.Global'),
                    'name' => 'hook',
                    'required' => true,
                    'col' => 3,
                ],
                [
                    'type' => 'textarea',
                    'label' => $this->l('Content'),
                    'name' => 'content',
                    'lang' => true,
                    'col' => $col,
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Status', [], 'Admin.Global'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', [], 'Admin.Global'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', [], 'Admin.Global'),
                        ],
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save', [], 'Admin.Actions'),
            ],
            'buttons' => [
                'save_and_stay' => [
                    'type' => 'submit',
                    'title' => $this->trans('Save and stay', [], 'Admin.Actions'),
                    'icon' => 'process-icon-save',
                    'name' => 'submitAddce_contentAndStay',
                    'class' => 'btn btn-default pull-right',
                ],
            ],
        ];

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = [
                'type' => 'shop',
                'label' => $this->trans('Shop association', [], 'Admin.Global'),
                'name' => 'checkBoxShopAsso',
            ];
        }

        return parent::renderForm();
    }

    protected function l($string, $module = 'creativeelements', $addslashes = false, $htmlentities = true)
    {
        $js = $addslashes || !$htmlentities;
        $str = Translate::getModuleTranslation($module, $string, '', null, $js, _CE_LOCALE_);

        return $htmlentities ? $str : stripslashes($str);
    }
}
