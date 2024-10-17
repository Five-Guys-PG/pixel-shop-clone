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

class AdminCEIconSetsController extends ModuleAdminController
{
    public $bootstrap = true;

    public $table = 'ce_icon_set';

    public $identifier = 'id_ce_icon_set';

    public $className = 'CEIconSet';

    public $multishop_context = Shop::CONTEXT_ALL;

    protected $ce_icon;

    protected $_defaultOrderBy = 'name';

    public function __construct()
    {
        parent::__construct();

        $this->fields_list = [
            'id_ce_icon_set' => [
                'title' => $this->trans('ID', [], 'Admin.Global'),
                'class' => 'fixed-width-xs',
                'align' => 'center',
            ],
            'badge_success' => [
                'title' => '',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'badge_success' => true,
                'orderby' => false,
                'search' => false,
            ],
            'name' => [
                'title' => $this->l('Icon Set'),
            ],
            'prefix' => [
                'title' => $this->l('CSS Prefix'),
                'prefix' => '<code>',
                'suffix' => '</code>',
                'orderby' => false,
                'search' => false,
            ],
        ];

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->l('Delete'),
                'icon' => 'icon-trash text-danger',
                'confirm' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Info'),
            ],
        ];
    }

    public function init()
    {
        if ($id_ce_icon_set = (int) Tools::getValue('id_ce_icon_set')) {
            $this->ce_icon = new CEIconSet($id_ce_icon_set);
        }
        parent::init();
    }

    public function initToolBarTitle()
    {
        $this->context->smarty->tpl_vars['breadcrumbs2']->value['container']['name'] = 'Creative Elements';
        $this->context->smarty->assign('icon', 'icon-list');

        $this->toolbar_title[] = $this->l(
            'add' === $this->display ? 'Add New Icon Set' : ('edit' === $this->display ? 'Edit Icon Set' : 'Icon Sets')
        );
    }

    public function initPageHeaderToolbar()
    {
        if ('add' !== $this->display && 'edit' !== $this->display) {
            $this->page_header_toolbar_btn['addce_icon_set'] = [
                'icon' => 'process-icon-new',
                'desc' => $this->trans('Add new', [], 'Admin.Actions'),
                'href' => self::$currentIndex . '&addce_icon_set&token=' . $this->token,
            ];
        }
        parent::initPageHeaderToolbar();
    }

    public function initModal()
    {
        // Prevent modals
    }

    public function ajaxProcessUpload()
    {
        $results = $this->uploadAndExtractZip();
        $supported_icon_sets = CEIconSet::getSupportedIconSets();
        $icon_sets_path = _CE_PATH_ . 'modules/fonts-manager/icon-sets';

        require_once "$icon_sets_path/icon-set-base.php";

        foreach ($supported_icon_sets as $key => $handler) {
            require_once "$icon_sets_path/$key.php";
            /*
             * @var IconSets\IconSetBase $icon_set_handler
             */
            $icon_set_handler = new $handler($results['directory']);

            if (!$icon_set_handler->isValid()) {
                continue;
            }
            $icon_set_handler->handleNewIconSet();
            // $name = $icon_set_handler->getName();
            $icon_set_handler->moveFiles();
            $config = $icon_set_handler->buildConfig();

            // Notify about duplicate prefix
            if (CEIconSet::prefixExists($config['prefix'])) {
                $config['duplicate_prefix'] = true;
            }
            exit(json_encode([
                'success' => true,
                'config' => $config,
            ]));
        }

        return $this->ajaxError('unsupported_zip_format', $this->l('The zip file provided is not supported!'));
    }

    private function uploadAndExtractZip()
    {
        if (!$zip = Tools::fileAttachment('zip_upload')) {
            return $this->ajaxError('missing_file', $this->l('Click the media icon to upload file'));
        }

        register_shutdown_function(function () use ($zip) {
            Tools::deleteFile($zip['tmp_name']);
        });

        if (strcasecmp(pathinfo($zip['name'], PATHINFO_EXTENSION), 'zip') || 'application/zip' !== mime_content_type($zip['tmp_name'])) {
            return $this->ajaxError('unsupported_file', $this->l('Only zip files are allowed'));
        }
        $extract_to = _PS_UPLOAD_DIR_ . pathinfo($zip['name'], PATHINFO_FILENAME) . '/';

        if (!Tools::ZipExtract($zip['tmp_name'], $extract_to) || !$source_files = array_diff(scandir($extract_to), ['.', '..'])) {
            return $this->ajaxError('incompatible_archive', $this->l('Incompatible archive'));
        }

        return [
            // Find the right folder.
            'directory' => 1 === count($source_files) && is_dir($directory = $extract_to . reset($source_files))
                ? $directory
                : $extract_to,
            'extracted_to' => $extract_to,
        ];
    }

    protected function ajaxError($code, $message)
    {
        exit(json_encode([
            'error_code' => $code,
            'error' => $message,
        ]));
    }

    protected function _childValidation()
    {
        if (Validate::isImageTypeName($name = Tools::getValue('name')) && CEIconSet::nameExists($name, Tools::getValue('id_ce_icon_set'))) {
            $this->errors[] = $this->trans('This name already exists.', [], 'Admin.Design.Notification');
        }
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        if ('add' !== $this->display && 'edit' !== $this->display) {
            return;
        }
        $min = _PS_MODE_DEV_ ? '' : '.min';
        $rtl = $this->context->language->is_rtl ? '-rtl' : '';

        Media::addJsDef([
            'elementorCommonConfig' => [
                'version' => _CE_VERSION_,
                'ajax' => Tools::url($this->context->link->getAdminLink('AdminCEIconSets'), 'ajax'),
                'isRTL' => (bool) $this->context->language->is_rtl,
                'isDebug' => _PS_MODE_DEV_,
                'activeModules' => [],
                'urls' => [
                    'assets' => _MODULE_DIR_ . 'creativeelements/views/',
                ],
            ],
        ]);
        $this->js_files[] = _MODULE_DIR_ . 'creativeelements/views/lib/underscore/underscore.min.js?v=1.8.3';
        $this->js_files[] = _MODULE_DIR_ . 'creativeelements/views/lib/backbone/backbone.min.js?v=1.4.0';
        $this->js_files[] = _MODULE_DIR_ . 'creativeelements/views/lib/backbone/backbone.marionette.min.js?v=2.4.5';
        $this->js_files[] = _MODULE_DIR_ . 'creativeelements/views/lib/dialog/dialog.min.js?v=4.7.6';
        $this->js_files[] = _MODULE_DIR_ . "creativeelements/views/js/common-modules$min.js?v=" . _CE_VERSION_;
        $this->js_files[] = _MODULE_DIR_ . "creativeelements/views/js/common$min.js?v=" . _CE_VERSION_;
        $this->js_files[] = _MODULE_DIR_ . 'creativeelements/views/js/custom-fonts.js?v=' . _CE_VERSION_;

        $this->css_files[_MODULE_DIR_ . "creativeelements/views/css/common$rtl$min.css?v=" . _CE_VERSION_] = 'all';
        $this->css_files[_MODULE_DIR_ . 'creativeelements/views/css/custom-fonts.css?v=' . _CE_VERSION_] = 'all';
    }

    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);

        // Add font previews
        if (!empty($this->_list)) {
            foreach ($this->_list as &$row) {
                $config = json_decode($row['config'], true);

                $row['id'] = $row['id_ce_icon_set'];
                $row['badge_success'] = $config['count'];
                $row['prefix'] = '.' . $config['prefix'];
            }
        }
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function renderForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Icon Set'),
                'icon' => 'iconset-folder',
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'name' => 'name',
                    'placeholder' => $this->l('Enter Icon Set Name'),
                    'required' => true,
                    'col' => 8,
                ],
                [
                    'type' => 'hidden',
                    'name' => 'config',
                ],
                [
                    'type' => 'html',
                    'label' => $this->l('Icons'),
                    'name' => 'add_custom_icon',
                    'html_content' => CESmarty::capture(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_add_custom_icon'),
                    'col' => 8,
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
                    'name' => 'submitAddce_icon_setAndStay',
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
