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

class AdminCETemplatesController extends ModuleAdminController
{
    public $bootstrap = true;

    public $table = 'ce_template';

    public $identifier = 'id_ce_template';

    public $className = 'CETemplate';

    public $multishop_context = Shop::CONTEXT_ALL;

    protected $action_link;

    protected $_defaultOrderBy = 'title';

    protected $_where = "AND a.type != 'kit'";

    public function __construct()
    {
        parent::__construct();

        if ($type = Tools::getValue('type')) {
            if ('all' === $type) {
                unset($this->context->cookie->submitFilterce_template);
                unset($this->context->cookie->cetemplatesce_templateFilter_type);
            } else {
                $this->context->cookie->submitFilterce_template = 1;
                $this->context->cookie->cetemplatesce_templateFilter_type = $type;
            }
        }

        $this->fields_options['import-template'] = [
            'class' => 'ce-import-panel hide',
            'icon' => 'icon-upload',
            'title' => $this->l('Import Template'),
            'description' => $this->l('Choose a JSON template file or a .zip archive of templates, and add them to the list of templates available in your library.'),
            'fields' => [
                'action' => [
                    'type' => 'hidden',
                    'value' => 'import_template',
                    'no_multishop_checkbox' => true,
                ],
                'file' => [
                    'type' => 'file',
                    'title' => $this->l('Template file'),
                    'name' => 'file',
                    'no_multishop_checkbox' => true,
                ],
            ],
            'submit' => [
                'imgclass' => 'import',
                'title' => $this->l('Import Now'),
            ],
        ];

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
                    'page' => $this->l('Page'),
                    'section' => $this->l('Section'),
                    'header' => $this->l('Header'),
                    'footer' => $this->l('Footer'),
                    'page-index' => $this->l('Home Page'),
                    'page-contact' => $this->l('Contact Page'),
                    'page-not-found' => $this->l('404 Page'),
                    'product' => $this->l('Product Page'),
                    'product-quick-view' => $this->l('Quick View'),
                    'product-miniature' => $this->l('Product Miniature'),
                    'listing-category' => $this->l('Category Page'),
                    'listing-manufacturer' => $this->l('Brand Page'),
                    'listing-page' => $this->l('Listing Page'),
                    'listing-no-results' => $this->l('No Results'),
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
            'shortcode' => [
                'title' => $this->l('Shortcode'),
                'class' => 'ce-shortcode',
                'type' => 'editable',
                'orderby' => false,
                'search' => false,
            ],
        ];
        if (!Configuration::get(version_compare(_PS_VERSION_, '1.7.7', '<') ? 'PS_DISPLAY_SUPPLIERS' : 'PS_DISPLAY_MANUFACTURERS')) {
            unset($this->fields_list['type']['list']['listing-manufacturer']);
        }

        $this->bulk_actions = [
            'export' => [
                'text' => $this->trans('Export', [], 'Admin.Actions'),
                'icon' => 'icon-mail-forward',
            ],
            'delete_divider' => [
                'text' => 'divider',
            ],
            'delete' => [
                'text' => $this->l('Delete'),
                'icon' => 'icon-trash text-danger',
                'confirm' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Info'),
            ],
        ];

        $this->action_link = CESmarty::get(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_action_link');
    }

    public function processBulkExport()
    {
        $uids = [];

        foreach ($this->boxes as $id) {
            $uids[] = new CE\UId($id, CE\UId::TEMPLATE);
        }

        CE\Plugin::instance()->templates_manager->getSource('local')->exportMultipleTemplates($uids);
    }

    protected function processUpdateOptions()
    {
        // Process import template
        CE\UId::$_ID = new CE\UId(0, CE\UId::TEMPLATE);

        $res = CE\Plugin::instance()->templates_manager->directImportTemplate();

        if ($res instanceof CE\WPError) {
            $this->errors[] = $res->getMessage();
        } elseif (isset($res[1]['template_id'])) {
            // More templates
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminCETemplates', true, [], ['conf' => 18]));
        } elseif (isset($res[0]['template_id'])) {
            // Simple template
            $id = substr($res[0]['template_id'], 0, -6);

            Tools::redirectAdmin($this->context->link->getAdminLink('kit' === $res[0]['type'] ? 'AdminCEThemes' : 'AdminCETemplates', true, [], [
                'id_ce_template' => $id,
                'updatece_template' => 1,
                'conf' => 18,
            ]));
        } else {
            $this->errors[] = $this->l('Unknown error during import!');
        }
    }

    public function ajaxProcessMigrate()
    {
        if ($ids = Tools::getValue('ids')) {
            require_once _CE_PATH_ . 'classes/CEMigrate.php';

            $done = [];

            foreach ($ids as $id) {
                CEMigrate::moveTemplate($id) && $done[] = (int) $id;
            }
            $res = CEMigrate::removeIds('template', $done);

            exit(json_encode($res));
        }
    }

    public function initHeader()
    {
        parent::initHeader();

        $id_lang = $this->context->language->id;
        $link = $this->context->link->getAdminLink('AdminCETemplates');
        $type = $this->context->cookie->cetemplatesce_templateFilter_type;
        $new = Tools::getIsset('addce_template');
        $tabs = &$this->context->smarty->tpl_vars['tabs']->value;
        $sections = [
            'section',
            'header',
            'footer',
            'product-quick-view',
            'product-miniature',
        ];
        foreach ($tabs as &$tab0) {
            foreach ($tab0['sub_tabs'] as &$tab1) {
                if ('AdminParentCEContent' !== $tab1['class_name']) {
                    continue;
                }
                foreach ($tab1['sub_tabs'] as &$tab2) {
                    if ('AdminCETemplates' !== $tab2['class_name']) {
                        continue;
                    }
                    $sub_tabs = &$tab2['sub_tabs'];
                    $tab = Tab::getTab($id_lang, Tab::getIdFromClassName('AdminCETemplates'));

                    $tab['name'] = $this->l('Template');
                    $tab['current'] = $new || (!in_array($type, ['section', 'page']) || !$type) && !$this->object;
                    $tab['href'] = "$link&type=all";
                    $sub_tabs[] = $tab;

                    $tab['name'] = $this->l('Page');
                    $tab['current'] = !$new && ($this->object ? !in_array($this->object->type, $sections) : 'page' === $type);
                    $tab['href'] = "$link&type=page";
                    $sub_tabs[] = $tab;

                    $tab['name'] = $this->l('Section');
                    $tab['current'] = !$new && ($this->object ? in_array($this->object->type, $sections) : 'section' === $type);
                    $tab['href'] = "$link&type=section";
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
            $this->page_header_toolbar_title = $this->l('Edit Template');
        } else {
            $this->page_header_toolbar_title = $this->l('My Templates');
        }

        $this->context->smarty->assign('icon', 'icon-list');

        $this->toolbar_title[] = $this->l('Templates List');
    }

    public function initPageHeaderToolbar()
    {
        if ('add' !== $this->display && 'edit' !== $this->display) {
            $this->page_header_toolbar_btn['addce_template'] = [
                'icon' => 'process-icon-new',
                'desc' => $this->trans('Add new', [], 'Admin.Actions'),
                'href' => self::$currentIndex . '&addce_template&token=' . $this->token,
            ];
            $this->page_header_toolbar_btn['importce_template'] = [
                'icon' => 'process-icon-import',
                'desc' => $this->trans('Import', [], 'Admin.Actions'),
                'href' => 'javascript:ceAdmin.onClickImport()',
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

    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);

        // Translate template types
        if (!empty($this->_list)) {
            $type = &$this->fields_list['type']['list'];

            foreach ($this->_list as &$row) {
                $row['id'] = $row['id_ce_template'];
                $row['shortcode'] = "{hook h='CETemplate' id={$row['id']}}";
                empty($type[$row['type']]) || $row['type'] = $type[$row['type']];
            }
        }
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('preview');
        $this->addRowAction('export');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function displayPreviewLink($token, $id, $name = null)
    {
        $link = $this->context->link->getModuleLink('creativeelements', 'preview', [
            'id_employee' => $this->context->employee->id,
            'cetoken' => Tools::getAdminTokenLite('AdminCETemplates'),
            'preview_id' => "{$id}010000",
        ], null, null, null, true);

        return sprintf($this->action_link, Tools::safeOutput($link), '_blank', 'eye', $this->trans('Preview', [], 'Admin.Actions'));
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
        $col = version_compare(_PS_VERSION_, '1.7.8', '<') ? 7 : 6;

        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Template'),
                'icon' => 'icon-edit',
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->trans('Title', [], 'Admin.Global'),
                    'name' => 'title',
                    'col' => $col,
                ],
                [
                    'type' => 'select',
                    'label' => $this->trans('Type', [], 'Admin.Catalog.Feature'),
                    'name' => 'type',
                    'disabled' => $disabled = !empty($this->object->type),
                    'options' => [
                        'default' => ['value' => '', 'label' => $this->l('Select...')],
                        'query' => $disabled ? [
                            ['value' => $this->object->type, 'label' => $this->fields_list['type']['list'][$this->object->type]],
                        ] : [
                            ['value' => 'page', 'label' => $this->l('Page')],
                            ['value' => 'section', 'label' => $this->l('Section')],
                        ],
                        'id' => 'value',
                        'name' => 'label',
                    ],
                    'col' => 3,
                ],
                [
                    'type' => 'textarea',
                    'label' => $this->l('Content'),
                    'name' => 'content',
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
                    'default_value' => 1,
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
                    'name' => 'submitAddce_templateAndStay',
                    'class' => 'btn btn-default pull-right',
                ],
            ],
        ];

        return parent::renderForm();
    }

    protected function l($string, $module = 'creativeelements', $addslashes = false, $htmlentities = true)
    {
        $js = $addslashes || !$htmlentities;
        $str = Translate::getModuleTranslation($module, $string, '', null, $js, _CE_LOCALE_);

        return $htmlentities ? $str : stripslashes($str);
    }
}
