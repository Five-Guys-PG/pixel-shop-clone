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

class AdminCEFontsController extends ModuleAdminController
{
    public $bootstrap = true;

    public $table = 'ce_font';

    public $identifier = 'id_ce_font';

    public $className = 'CEFont';

    public $multishop_context = Shop::CONTEXT_ALL;

    protected $ce_font;

    protected $_defaultOrderBy = 'family';

    public function __construct()
    {
        parent::__construct();

        $this->fields_list = [
            'id_ce_font' => [
                'title' => $this->trans('ID', [], 'Admin.Global'),
                'class' => 'fixed-width-xs',
                'align' => 'center',
            ],
            'family' => [
                'title' => $this->l('Font Name'),
            ],
            'preview' => [
                'title' => $this->l('Preview'),
                'class' => 'ce-font-preview',
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
        if ($id_ce_font = (int) Tools::getValue('id_ce_font')) {
            $this->ce_font = new CEFont($id_ce_font);
        }
        parent::init();
    }

    public function initToolBarTitle()
    {
        $this->context->smarty->tpl_vars['breadcrumbs2']->value['container']['name'] = 'Creative Elements';
        $this->context->smarty->assign('icon', 'icon-list');

        $this->toolbar_title[] = $this->l(
            'add' === $this->display ? 'Add New Font' : ('edit' === $this->display ? 'Edit Font' : 'Fonts List')
        );
    }

    public function initPageHeaderToolbar()
    {
        if ('add' !== $this->display && 'edit' !== $this->display) {
            $this->page_header_toolbar_btn['addce_font'] = [
                'icon' => 'process-icon-new',
                'desc' => $this->trans('Add new', [], 'Admin.Actions'),
                'href' => self::$currentIndex . '&addce_font&token=' . $this->token,
            ];
        }
        parent::initPageHeaderToolbar();
    }

    public function initModal()
    {
        // Prevent modals
    }

    protected function _childValidation()
    {
        if (Validate::isImageTypeName($family = Tools::getValue('family')) && CEFont::familyExists($family, Tools::getValue('id_ce_font'))) {
            $this->errors[] = $this->trans('This name already exists.', [], 'Admin.Design.Notification');
        }
    }

    public function validateRules($class_name = false)
    {
        parent::validateRules($class_name);

        if (empty($this->errors)) {
            $font_face = &${'_POST'}['font_face'];

            if (!empty($_FILES['font_face']['name'])) {
                $upload_error = [];
                $fonts_url = 'modules/creativeelements/views/fonts/';
                $fonts_dir = _PS_ROOT_DIR_ . '/' . $fonts_url;

                foreach ($_FILES['font_face']['name'] as $i => &$font_name) {
                    $tmp_name = &$_FILES['font_face']['tmp_name'][$i];
                    $error_code = &$_FILES['font_face']['error'][$i];

                    foreach (CEFont::getAllowedExt() as $ext) {
                        if (empty($font_name[$ext]['file']) || empty($tmp_name[$ext]['file'])) {
                            continue;
                        }

                        if (empty($error_code[$ext]['file'])
                            && strtolower(pathinfo($font_name[$ext]['file'], PATHINFO_EXTENSION)) === $ext
                            && move_uploaded_file($tmp_name[$ext]['file'], $fonts_dir . $font_name[$ext]['file'])
                        ) {
                            $font_face[$i][$ext]['url'] = $fonts_url . $font_name[$ext]['file'];
                        } else {
                            $font_face[$i][$ext]['url'] = '';
                            $upload_error[] = $font_name[$ext]['file'];
                        }
                    }
                }

                if ($upload_error) {
                    $this->errors[] = $this->trans('An error occurred during the file upload process.', [], 'Admin.Notifications.Error') .
                        ' (' . implode(', ', $upload_error) . ')';
                }
            }
            ${'_POST'}['files'] = json_encode($font_face);
        }
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        if ($font_face = (string) $this->ce_font) {
            $font_face = str_replace('{{BASE}}', $this->context->link->getMediaLink(__PS_BASE_URI__), $font_face);
            $font_face = str_replace(["\n", "\t"], '', $font_face);

            $this->css_files['data:text/css,' . $font_face] = 'all';
        }
        $this->css_files[_MODULE_DIR_ . 'creativeelements/views/css/custom-fonts.css?v=' . _CE_VERSION_] = 'all';
        $this->js_files[] = _MODULE_DIR_ . 'creativeelements/views/js/custom-fonts.js?v=' . _CE_VERSION_;
    }

    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);

        // Add font previews
        if (!empty($this->_list)) {
            foreach ($this->_list as &$row) {
                $row['id'] = $row['id_ce_font'];
                $row['preview'] = new CEFont($row['id']);
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
                'title' => $this->l('Font'),
                'icon' => 'icon-font',
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Font Name'),
                    'name' => 'family',
                    'placeholder' => $this->l('Enter Font Family'),
                    'required' => true,
                    'col' => 8,
                ],
                [
                    'type' => 'hidden',
                    'name' => 'files',
                ],
                [
                    'type' => 'html',
                    'label' => $this->l('Font Files'),
                    'name' => 'add_custom_font',
                    'html_content' => CESmarty::capture(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_add_custom_font'),
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
                    'name' => 'submitAddce_fontAndStay',
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
