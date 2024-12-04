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

class AdminCEThemesController extends ModuleAdminController
{
    public $bootstrap = true;

    protected $type;

    protected $action_link;

    protected $hasManufacturers;

    protected $_defaultOrderBy = 'title';

    public function __construct()
    {
        parent::__construct();

        if ((Tools::getIsset('updatece_theme') || Tools::getIsset('addce_theme')) && Shop::getContextShopID() === null) {
            $this->displayWarning(
                $this->trans('You are in a multistore context: any modification will impact all your shops, or each shop of the active group.', [], 'Admin.Catalog.Notification')
            );
        }

        if ($type = Tools::getValue('type')) {
            if ('template' === $type) {
                unset($this->context->cookie->submitFilterce_theme);
                unset($this->context->cookie->cethemesce_themeFilter_type);
            } else {
                $this->context->cookie->submitFilterce_theme = 1;
                // Fix: Remove Listing Page from Page tab
                $this->context->cookie->cethemesce_themeFilter_type = 'page' === $type ? 'page-' : $type;
            }
        }

        $this->type = $type ?: $this->context->cookie->cethemesce_themeFilter_type;
        $this->hasManufacturers = Configuration::get(version_compare(_PS_VERSION_, '1.7.7', '<') ? 'PS_DISPLAY_SUPPLIERS' : 'PS_DISPLAY_MANUFACTURERS');

        $id_lang = $this->context->language->id;
        $id_shop = $this->context->shop->id;

        if ('kit' === $this->type) {
            $this->table = 'ce_template';
            $this->identifier = 'id_ce_template';
            $this->className = 'CETemplate';
            $this->action_link = CESmarty::get(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_action_link');
            $this->_where = "AND a.`type` = 'kit'";

            $this->fields_list = [
                'id_ce_template' => [
                    'title' => $this->trans('ID', [], 'Admin.Global'),
                    'class' => 'fixed-width-xs',
                    'align' => 'center',
                ],
                'title' => [
                    'title' => $this->trans('Title', [], 'Admin.Global'),
                ],
                'type' => [
                    'title' => $this->trans('Type', [], 'Admin.Catalog.Feature'),
                    'class' => 'fixed-width-lg',
                    'type' => 'select',
                    'list' => [
                        'kit' => $this->l('Theme Style'),
                    ],
                    'filter_key' => 'type',
                ],
                'date_add' => [
                    'title' => $this->trans('Created on', [], 'Modules.Facetedsearch.Admin'),
                    'class' => 'fixed-width-lg',
                    'type' => 'datetime',
                ],
                'date_upd' => [
                    'title' => $this->l('Modified on'),
                    'class' => 'fixed-width-lg',
                    'type' => 'datetime',
                ],
                'active' => [
                    'title' => $this->trans('Status', [], 'Admin.Global'),
                    'class' => 'fixed-width-xs',
                    'align' => 'center',
                    'active' => 'status',
                    'type' => 'bool',
                ],
            ];
        } else {
            $this->table = 'ce_theme';
            $this->identifier = 'id_ce_theme';
            $this->className = 'CETheme';
            $this->lang = true;

            $this->_select = 'sa.*';
            $this->_join = 'LEFT JOIN ' . _DB_PREFIX_ . 'ce_theme_shop sa ON sa.`id_ce_theme` = a.`id_ce_theme` AND b.`id_shop` = sa.`id_shop`';
            $this->_where = 'AND sa.id_shop = ' . (int) $id_shop;
            $this->hasManufacturers || $this->_where .= ' AND a.`type` <> "listing-manufacturer"';

            $this->fields_list = [
                'id_ce_theme' => [
                    'title' => $this->trans('ID', [], 'Admin.Global'),
                    'class' => 'fixed-width-xs',
                    'align' => 'center',
                ],
                'title' => [
                    'title' => $this->trans('Title', [], 'Admin.Global'),
                ],
                'type' => [
                    'title' => $this->trans('Type', [], 'Admin.Catalog.Feature'),
                    'class' => 'fixed-width-lg',
                    'type' => 'select',
                    'list' => [
                        'header' => $this->l('Header'),
                        'footer' => $this->l('Footer'),
                        'page-' => $this->l('Page'),
                        'page-index' => '&emsp;' . $this->l('Home Page'),
                        'page-contact' => '&emsp;' . $this->l('Contact Page'),
                        'page-not-found' => '&emsp;' . $this->l('404 Page'),
                        'prod' => $this->l('Product'),
                        'product' => '&emsp;' . $this->l('Product Page'),
                        'product-quick-view' => '&emsp;' . $this->l('Quick View'),
                        'product-miniature' => '&emsp;' . $this->l('Product Miniature'),
                        'list' => $this->l('Listing'),
                        'listing-category' => '&emsp;' . $this->l('Category Page'),
                        'listing-manufacturer' => '&emsp;' . $this->l('Brand Page'),
                        'listing-page' => '&emsp;' . $this->l('Listing Page'),
                        'listing-no-results' => '&emsp;' . $this->l('No Results'),
                    ],
                    'filter_key' => 'type',
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
        }

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->trans('Delete selected', [], 'Admin.Notifications.Info'),
                'icon' => 'icon-trash text-danger',
                'confirm' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Info'),
            ],
        ];

        $listing_pages = CETheme::getOptions('listing-page', $id_lang, $id_shop);

        $this->fields_options['theme_settings'] = [
            'class' => 'ce-theme-panel',
            'icon' => 'icon-cog',
            'title' => $this->l('Theme Settings'),
            'tabs' => [
                'site' => $this->l('Site'),
                'page' => $this->l('Page'),
                'prod' => $this->l('Product'),
                'list' => $this->l('Listing'),
            ],
            'fields' => [
                'CE_HEADER' => [
                    'tab' => 'site',
                    'title' => $this->l('Header'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], CETheme::getOptions('header', $id_lang, $id_shop)),
                ],
                'CE_FOOTER' => [
                    'tab' => 'site',
                    'title' => $this->l('Footer'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], CETheme::getOptions('footer', $id_lang, $id_shop)),
                ],
                'elementor_active_kit' => [
                    'tab' => 'site',
                    'title' => $this->l('Theme Style'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->trans('Add new', [], 'Admin.Actions')],
                    ], CETemplate::getKitOptions()),
                ],
                'CE_PAGE_INDEX' => [
                    'tab' => 'page',
                    'title' => $this->l('Home Page'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], CETheme::getOptions('page-index', $id_lang, $id_shop)),
                ],
                'CE_PAGE_CONTACT' => [
                    'tab' => 'page',
                    'title' => $this->l('Contact Page'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], CETheme::getOptions('page-contact', $id_lang, $id_shop)),
                ],
                'CE_PAGE_NOT_FOUND' => [
                    'tab' => 'page',
                    'title' => $this->l('404 Page'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], CETheme::getOptions('page-not-found', $id_lang, $id_shop)),
                ],
                'CE_PRODUCT' => [
                    'tab' => 'prod',
                    'title' => $this->l('Product Page'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], CETheme::getOptions('product', $id_lang, $id_shop)),
                ],
                'CE_PRODUCT_QUICK_VIEW' => [
                    'tab' => 'prod',
                    'title' => $this->l('Quick View'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], CETheme::getOptions('product-quick-view', $id_lang, $id_shop)),
                ],
                'CE_PRODUCT_MINIATURE' => [
                    'tab' => 'prod',
                    'title' => $this->l('Miniature'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], CETheme::getOptions('product-miniature', $id_lang, $id_shop)),
                ],
                'CE_LISTING_CATEGORY' => [
                    'tab' => 'list',
                    'title' => $this->l('Category Page'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], CETheme::getOptions('listing-category', $id_lang, $id_shop), $listing_pages),
                ],
                'CE_LISTING_MANUFACTURER' => [
                    'tab' => 'list',
                    'title' => $this->l('Brand Page'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], CETheme::getOptions('listing-manufacturer', $id_lang, $id_shop), $listing_pages),
                ],
                'CE_LISTING_SEARCH' => [
                    'tab' => 'list',
                    'title' => $this->l('Search Page'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], $listing_pages),
                ],
                'CE_LISTING_PRICES_DROP' => [
                    'tab' => 'list',
                    'title' => $this->l('Prices Drop'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], $listing_pages),
                ],
                'CE_LISTING_NEW_PRODUCTS' => [
                    'tab' => 'list',
                    'title' => $this->l('New Products'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], $listing_pages),
                ],
                'CE_LISTING_BEST_SALES' => [
                    'tab' => 'list',
                    'title' => $this->l('Best Sellers'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], $listing_pages),
                ],
                'CE_LISTING_NO_RESULTS' => [
                    'tab' => 'list',
                    'title' => $this->l('No Results'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge([
                        ['value' => '', 'name' => $this->l('Default')],
                    ], CETheme::getOptions('listing-no-results', $id_lang, $id_shop)),
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];
        if (!$this->hasManufacturers) {
            unset(
                $this->fields_list['type']['list']['listing-manufacturer'],
                $this->fields_options['theme_settings']['fields']['CE_LISTING_MANUFACTURER']
            );
        }
        if (!Configuration::get('PS_DISPLAY_BEST_SELLERS') || Configuration::get('PS_CATALOG_MODE')) {
            unset($this->fields_options['theme_settings']['fields']['CE_LISTING_BEST_SALES']);
        }
    }

    protected function processUpdateOptions()
    {
        parent::processUpdateOptions();

        if (!Configuration::get('elementor_active_kit')) {
            $id_kit = substr(CE\Plugin::instance()->kits_manager->getActiveId(), 0, -6);
            ${'_POST'}['elementor_active_kit'] = $id_kit;
            $this->fields_options['theme_settings']['fields']['elementor_active_kit']['list'][] = [
                'value' => $id_kit,
                'name' => "#$id_kit {$this->l('Default')}",
            ];
        }
    }

    public function initHeader()
    {
        parent::initHeader();

        $id_lang = $this->context->language->id;
        $link = $this->context->link->getAdminLink('AdminCEThemes');
        $new = Tools::getIsset('addce_theme');
        $tabs = &$this->context->smarty->tpl_vars['tabs']->value;

        foreach ($tabs as &$tab0) {
            foreach ($tab0['sub_tabs'] as &$tab1) {
                if ('AdminParentCEContent' !== $tab1['class_name']) {
                    continue;
                }
                foreach ($tab1['sub_tabs'] as &$tab2) {
                    if ('AdminCEThemes' !== $tab2['class_name']) {
                        continue;
                    }
                    $sub_tabs = &$tab2['sub_tabs'];
                    $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminCEThemes'));

                    $tab['name'] = $this->l('Template');
                    $tab['current'] = $new || (!$this->type || 'template' === $this->type) && !$this->object;
                    $tab['href'] = "$link&type=template";
                    $sub_tabs[] = $tab;

                    $type = $this->object ? $this->object->type : $this->type;

                    $tab['name'] = $this->l('Header');
                    $tab['current'] = !$new && 'header' === $type;
                    $tab['href'] = "$link&type=header";
                    $sub_tabs[] = $tab;

                    $tab['name'] = $this->l('Footer');
                    $tab['current'] = !$new && 'footer' === $type;
                    $tab['href'] = "$link&type=footer";
                    $sub_tabs[] = $tab;

                    $tab['name'] = $this->l('Page');
                    $tab['current'] = !$new && strpos($type, 'page') === 0;
                    $tab['href'] = "$link&type=page";
                    $sub_tabs[] = $tab;

                    $tab['name'] = $this->l('Product');
                    $tab['current'] = !$new && strpos($type, 'prod') === 0;
                    $tab['href'] = "$link&type=prod";
                    $sub_tabs[] = $tab;

                    $tab['name'] = $this->l('Listing');
                    $tab['current'] = !$new && strpos($type, 'list') === 0;
                    $tab['href'] = "$link&type=list";
                    $sub_tabs[] = $tab;

                    $tab['name'] = $this->l('Theme Style');
                    $tab['current'] = !$new && 'kit' === $type;
                    $tab['href'] = "$link&type=kit";
                    $sub_tabs[] = $tab;

                    return;
                }
            }
        }
    }

    public function initToolBarTitle()
    {
        if ('add' === $this->display) {
            $this->page_header_toolbar_title = $this->l('Add New');
        } elseif ('edit' === $this->display) {
            $this->page_header_toolbar_title = 'kit' === $this->type
                ? sprintf($this->l('Edit %s'), $this->l('Theme Style'))
                : $this->l('Edit Template');
        } else {
            $this->page_header_toolbar_title = $this->l('Theme Builder');
        }

        $this->context->smarty->assign('icon', 'icon-list');

        $this->toolbar_title[] = $this->l('kit' === $this->type ? 'List' : 'Templates List');
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display) || 'options' === $this->display) {
            $this->page_header_toolbar_btn["add{$this->table}"] = [
                'href' => self::$currentIndex . "&add{$this->table}&token={$this->token}",
                'desc' => $this->trans('Add new', [], 'Admin.Actions'),
                'icon' => 'process-icon-new',
            ];
        }
        parent::initPageHeaderToolbar();
    }

    public function initModal()
    {
        // Prevent modals
    }

    public function initContent()
    {
        $this->context->smarty->assign('current_tab_level', 3);

        return parent::initContent();
    }

    public function processFilter()
    {
        if (in_array($this->context->cookie->cethemesce_themeFilter_type, ['page-', 'prod', 'list'])) {
            // Trick for type filtering, use LIKE instead of =
            $this->fields_list['type']['type'] = 'text';
        }
        parent::processFilter();

        $this->fields_list['type']['type'] = 'select';
    }

    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);

        // Translate template types
        if (!empty($this->_list)) {
            $type = &$this->fields_list['type']['list'];

            foreach ($this->_list as &$row) {
                empty($type[$row['type']]) || $row['type'] = str_replace('&emsp;', '', $type[$row['type']]);
            }
        }
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->action_link && $this->addRowAction('export');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function displayExportLink($token, $id, $name = null)
    {
        $link = $this->context->link->getAdminLink('AdminCEEditor', true, [], [
            'ajax' => 1,
            'action' => 'elementor_library_direct_actions',
            'library_action' => 'export_template',
            'source' => 'local',
            'template_id' => "{$id}010000",
        ]);

        return sprintf($this->action_link, Tools::safeOutput($link), '_self', 'mail-forward', $this->trans('Export', [], 'Admin.Actions'));
    }

    public function renderForm()
    {
        $kit = 'kit' === $this->type;
        $col = !$kit && count(Language::getLanguages(false, false, true)) > 1 ? 9 : 7;

        version_compare(_PS_VERSION_, '1.7.8', '<') || --$col;

        $this->fields_form = [
            'legend' => [
                'title' => $this->l($kit ? 'Theme Style' : 'Template'),
                'icon' => 'icon-edit',
            ],
            'input' => [
                'title' => [
                    'type' => 'text',
                    'label' => $this->trans('Title', [], 'Admin.Global'),
                    'name' => 'title',
                    'lang' => !$kit,
                    'col' => $col,
                ],
                'type' => [
                    'type' => 'select',
                    'label' => $this->trans('Type', [], 'Admin.Catalog.Feature'),
                    'name' => 'type',
                    'required' => true,
                    'disabled' => !empty($this->object->type),
                    'options' => $kit ? [
                        'default' => ['value' => 'kit', 'label' => $this->l('Theme Style')],
                        'query' => [],
                    ] : [
                        'default' => ['value' => '', 'label' => $this->l('Select...')],
                        'optiongroup' => [
                            'query' => [
                                'site' => [
                                    'label' => $this->l('Site'),
                                    'query' => [
                                        ['value' => 'header', 'label' => $this->l('Header')],
                                        ['value' => 'footer', 'label' => $this->l('Footer')],
                                    ],
                                ],
                                'page' => [
                                    'label' => $this->l('Page'),
                                    'query' => [
                                        ['value' => 'page-index', 'label' => $this->l('Home Page')],
                                        ['value' => 'page-contact', 'label' => $this->l('Contact Page')],
                                        ['value' => 'page-not-found', 'label' => $this->l('404 Page')],
                                    ],
                                ],
                                'product' => [
                                    'label' => $this->l('Product'),
                                    'query' => [
                                        ['value' => 'product', 'label' => $this->l('Product Page')],
                                        ['value' => 'product-quick-view', 'label' => $this->l('Quick View')],
                                        ['value' => 'product-miniature', 'label' => $this->l('Miniature')],
                                    ],
                                ],
                                'listing' => [
                                    'label' => $this->l('Listing'),
                                    'query' => [
                                        'category' => [
                                            'value' => 'listing-category',
                                            'label' => $this->l('Category Page'),
                                        ],
                                        'manufacturer' => [
                                            'value' => 'listing-manufacturer',
                                            'label' => $this->l('Brand Page'),
                                        ],
                                        'page' => [
                                            'value' => 'listing-page',
                                            'label' => $this->l('Listing Page'),
                                        ],
                                        'no-results' => [
                                            'value' => 'listing-no-results',
                                            'label' => $this->l('No Results'),
                                        ],
                                    ],
                                ],
                            ],
                            'label' => 'label',
                        ],
                        'options' => [
                            'query' => 'query',
                            'name' => 'label',
                            'id' => 'value',
                        ],
                    ],
                    'col' => 3,
                ],
                'content' => [
                    'type' => 'textarea',
                    'label' => $this->l($kit ? 'Style' : 'Content'),
                    'name' => 'content',
                    'lang' => !$kit,
                    'col' => $col,
                    'class' => 'hidden',
                ],
                'active' => [
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
                    'name' => "submitAdd{$this->table}AndStay",
                    'class' => 'btn btn-default pull-right',
                ],
            ],
        ];
        if (!$kit && !$this->hasManufacturers) {
            unset($this->fields_form['input']['type']['options']['optiongroup']['query']['listing']['query']['manufacturer']);
        }
        if (!$kit && Shop::isFeatureActive()) {
            $this->fields_form['input']['shop'] = [
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
