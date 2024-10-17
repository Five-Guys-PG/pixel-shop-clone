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

define('_CE_VERSION_', '2.11.1');
define('_CE_PATH_', _PS_MODULE_DIR_ . 'creativeelements/');
define('_CE_URL_', (defined('_PS_BO_ALL_THEMES_DIR_') ? _MODULE_DIR_ : 'modules/') . 'creativeelements/');
define('_CE_ASSETS_PATH_', _CE_PATH_ . 'views/');
define('_CE_ASSETS_URL_', _CE_URL_ . 'views/');
define('_CE_TEMPLATES_', _CE_PATH_ . 'views/templates/');

const _CE_CLASSES_ = [
    'CEAssetManager' => 'classes/assets/CEAssetManager.php',
    'CERevision' => 'classes/CERevision.php',
    'CETemplate' => 'classes/CETemplate.php',
    'CETheme' => 'classes/CETheme.php',
    'CEContent' => 'classes/CEContent.php',
    'CEFont' => 'classes/CEFont.php',
    'CEIconSet' => 'classes/CEIconSet.php',
];
spl_autoload_register(function ($class) {
    isset(_CE_CLASSES_[$class]) && require _CE_PATH_ . _CE_CLASSES_[$class];
});
require_once _CE_PATH_ . 'classes/CESmarty.php';
require_once _CE_PATH_ . 'includes/plugin.php';

class CreativeElements extends Module
{
    const VIEWED_PRODUCTS_LIMIT = 50;

    protected static $controller;

    protected static $template;

    protected static $layout;

    public $controllers = [
        'ajax',
        'preview',
    ];

    public function __construct($name = null, $context = null)
    {
        $this->name = 'creativeelements';
        $this->tab = 'content_management';
        $this->version = '2.11.1';
        $this->author = 'WebshopWorks';
        $this->module_key = '7a5ebcc21c1764675f1db5d0f0eacfe5';
        $this->ps_versions_compliancy = ['min' => '1.7.4', 'max' => _PS_VERSION_];
        $this->bootstrap = true;
        $this->displayName = $this->l('Creative Elements - live Theme & Page Builder');
        $this->description = $this->l('The most advanced frontend drag & drop page builder. Create high-end, pixel perfect websites at record speeds. Any theme, any page, any design.');
        parent::__construct();

        $this->checkThemeChange();
    }

    public function install()
    {
        require_once _CE_PATH_ . 'classes/CEDatabase.php';

        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        CEDatabase::initConfigs();

        if (!CEDatabase::createTables()) {
            $this->_errors[] = Db::getInstance()->getMsgError();

            return false;
        }

        if ($res = parent::install() && CEDatabase::updateTabs()) {
            foreach (CEDatabase::getHooks() as $hook) {
                $res = $res && $this->registerHook($hook, null, 1);
            }
        }

        return $res;
    }

    public function uninstall()
    {
        foreach (Tab::getCollectionFromModule($this->name) as $tab) {
            $tab->delete();
        }

        return parent::uninstall();
    }

    public function enable($force_all = false)
    {
        return parent::enable($force_all)
            && Db::getInstance()->update('tab', ['active' => 1], "`module` = 'creativeelements' AND `class_name` != 'AdminCEEditor'");
    }

    public function disable($force_all = false)
    {
        return Db::getInstance()->update('tab', ['active' => 0], "`module` = 'creativeelements'")
            && parent::disable($force_all);
    }

    public function addOverride($classname)
    {
        try {
            return parent::addOverride($classname);
        } catch (Exception $ex) {
            return false;
        }
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminCEThemes'));
    }

    public function hookDisplayBackOfficeHeader($params = [])
    {
        Configuration::get("PS_ALLOW_HTML_\x49FRAME") || Configuration::updateValue("PS_ALLOW_HTML_\x49FRAME", 1);
        Configuration::get('PS_SSL_ENABLED') && !Configuration::get('PS_SSL_ENABLED_EVERYWHERE') && Configuration::set('PS_SSL_ENABLED_EVERYWHERE', 1);

        // Handle migrate
        if ((Configuration::getGlobalValue('ce_migrate') || Tools::getIsset('CEMigrate')) && Db::getInstance()->executeS("SHOW TABLES LIKE '%_ce_meta'")) {
            require_once _CE_PATH_ . 'classes/CEMigrate.php';
            CEMigrate::registerJavascripts();
        }

        $footer_product = '';
        preg_match('`/([^/]+)/(\d+)/edit\b`', $_SERVER['REQUEST_URI'], $req);
        $controller = strtolower(Tools::getValue('controller'));

        switch ($controller) {
            case 'admincetemplates':
                $id_type = CE\UId::TEMPLATE;
                $id = (int) Tools::getValue('id_ce_template');
                break;
            case 'admincethemes':
                $id_type = Tools::getIsset('id_ce_template') ? CE\UId::TEMPLATE : CE\UId::THEME;
                $id = (int) Tools::getValue('id_ce_template', Tools::getValue('id_ce_theme'));
                break;
            case 'admincecontent':
                $id_type = CE\UId::CONTENT;
                $id = (int) Tools::getValue('id_ce_content');
                break;
            case 'admincmscontent':
                if ($req && 'category' === $req[1] || Tools::getIsset('addcms_category') || Tools::getIsset('updatecms_category')) {
                    $id_type = CE\UId::CMS_CATEGORY;
                    $id = (int) Tools::getValue('id_cms_category', $req ? $req[2] : 0);
                    break;
                }
                $id_type = CE\UId::CMS;
                $id = (int) Tools::getValue('id_cms', $req ? $req[2] : 0);
                break;
            case 'adminproducts':
                $id_type = CE\UId::PRODUCT;
                $id = (int) Tools::getValue('id_product', basename(explode('?', $_SERVER['REQUEST_URI'])[0]));

                $footer_product = new CE\UId(CEContent::getFooterProductId($id), CE\UId::CONTENT, 0, Shop::getContext() === Shop::CONTEXT_SHOP ? $this->context->shop->id : 0);
                break;
            case 'admincategories':
                $id_type = CE\UId::CATEGORY;
                $id = (int) Tools::getValue('id_category', $req ? $req[2] : 0);
                break;
            case 'adminmanufacturers':
                $id_type = CE\UId::MANUFACTURER;
                $id = (int) Tools::getValue('id_manufacturer', $req ? $req[2] : 0);
                break;
            case 'adminsuppliers':
                $id_type = CE\UId::SUPPLIER;
                $id = (int) Tools::getValue('id_supplier', $req ? $req[2] : 0);
                break;
            case 'adminxippost':
                $id_type = CE\UId::XIPBLOG_POST;
                $id = (int) Tools::getValue('id_xipposts');
                break;
            case 'adminstblog':
                $id_type = CE\UId::STBLOG_POST;
                $id = (int) Tools::getValue('id_st_blog');
                break;
            case 'adminblogposts':
                if ('advanceblog' === $this->context->controller->module->name) {
                    $id_type = CE\UId::ADVANCEBLOG_POST;
                    $id = (int) Tools::getValue('id_post');
                }
                break;
            case 'adminpsblogblogs':
                $id_type = CE\UId::PSBLOG_POST;
                $id = (int) Tools::getValue('id_psblog_blog');
                break;
            case 'admintvcmspost':
                $id_type = CE\UId::TVCMSBLOG_POST;
                $id = (int) Tools::getValue('id_tvcmsposts');
                break;
            case 'adminetsblogpost':
                $id_type = CE\UId::ETS_BLOG_POST;
                // no break
            case 'adminybcblogpost':
                isset($id_type) || $id_type = CE\UId::YBC_BLOG_POST;
                $id = (int) Tools::getValue('id_post');
                break;
            case 'adminmodules':
                $configure = strtolower(Tools::getValue('configure'));

                if ('ybc_blog' === $configure && Tools::getValue('control') === 'post') {
                    // BC fix for ybc_blog < 4.4.9
                    $id_type = CE\UId::YBC_BLOG_POST;
                    $id = (int) Tools::getValue('id_post');
                    break;
                }
                if ('prestablog' === $configure && Tools::getIsset('editNews')) {
                    $id_type = CE\UId::PRESTABLOG_POST;
                    $id = (int) Tools::getValue('idN');
                    break;
                }
                if ('hiblog' === $configure) {
                    $id_type = CE\UId::HIBLOG_POST;
                    $id = 0;
                    $hideEditor = [];
                    break;
                }
                break;
            case 'adminmaintenance':
                $id_type = CE\UId::CONTENT;
                $id = CEContent::getMaintenanceId();

                $uids = CE\UId::getBuiltList($id, $id_type, $this->context->shop->id);
                $hideEditor = empty($uids) ? $uids : array_keys($uids[$this->context->shop->id]);
                break;
        }

        if (isset($id)) {
            self::$controller = $this->context->controller;
            version_compare(_PS_VERSION_, '1.7.7', '<') && self::$controller->addJquery();
            self::$controller->js_files[] = $this->_path . 'views/js/admin-ce.js?v=' . _CE_VERSION_;
            self::$controller->css_files[$this->_path . 'views/css/admin-ce.css?v=' . _CE_VERSION_] = 'all';

            $uid = new CE\UId($id, $id_type, 0, Shop::getContext() === Shop::CONTEXT_SHOP ? $this->context->shop->id : 0);

            isset($hideEditor) || $hideEditor = $uid->getBuiltLangIdList();

            $display_suppliers = Configuration::get('PS_DISPLAY_SUPPLIERS');
            $display_manufacturers = version_compare(_PS_VERSION_, '1.7.7', '<')
                ? $display_suppliers
                : Configuration::get('PS_DISPLAY_MANUFACTURERS');

            Media::addJsDef([
                'ceAdmin' => [
                    'uid' => "$uid",
                    'hideEditor' => $hideEditor,
                    'footerProduct' => "$footer_product",
                    'i18n' => [
                        'edit' => CE\__('Edit with Creative Elements'),
                        'save' => CE\__('Please save the form before editing with Creative Elements'),
                    ],
                    'editorUrl' => Tools::safeOutput($this->context->link->getAdminLink('AdminCEEditor') . '&uid='),
                    'languages' => Language::getLanguages(true, $uid->id_shop),
                    'editSuppliers' => (int) $display_suppliers,
                    'editManufacturers' => (int) $display_manufacturers,
                ],
            ]);
            $this->context->smarty->assign('edit_width_ce', $this->context->link->getAdminLink('AdminCEEditor'));
        }

        if (1 === $perm = (int) Tools::getValue('perm')) {
            $this->context->controller->errors[] = $this->trans('You do not have permission to view this.', [], 'Admin.Notifications.Error');
        } elseif (2 === $perm) {
            $this->context->controller->errors[] = $this->trans('You do not have permission to edit this.', [], 'Admin.Notifications.Error');
        }

        return $this->context->smarty->fetch(_CE_TEMPLATES_ . 'hook/backoffice_header.tpl');
    }

    public function hookActionFrontControllerAfterInit($params = [])
    {
        // BC Fix for PS 1.7.3 - 1.7.6
        $this->hookActionFrontControllerInitAfter($params);
    }

    public function hookActionFrontControllerInitAfter($params = [])
    {
        if (null !== self::$controller) {
            return;
        }
        self::$controller = $this->context->controller;

        $tpl_dir = $this->context->smarty->getTemplateDir();
        array_unshift($tpl_dir, _CE_TEMPLATES_ . 'front/theme/');
        $this->context->smarty->setTemplateDir($tpl_dir);
        // Update template finder directories
        Closure::bind(function () use (&$tpl_dir) {
            Closure::bind(function () use (&$tpl_dir) {
                $this->directories = &$tpl_dir;
            }, $this->templateFinder, 'TemplateFinderCore')->__invoke();
        }, self::$controller, 'FrontControllerCore')->__invoke();

        if (Tools::getIsset('id_miniature') && self::hasAdminToken('AdminCEThemes')) {
            Configuration::set('elementor_element_cache_ttl', 0);

            $id_miniature = (int) Tools::getValue('id_miniature');
        } else {
            $id_miniature = Configuration::get('CE_PRODUCT_MINIATURE');
        }

        if ($id_miniature) {
            $this->context->smarty->assign(
                'CE_PRODUCT_MINIATURE_UID',
                new CE\UId($id_miniature, CE\UId::THEME, $this->context->language->id, $this->context->shop->id)
            );
        }
    }

    public function hookHeader()
    {
        // Compatibility fix for PS 1.7.7.x upgrade
        return $this->hookDisplayHeader();
    }

    public function hookDisplayHeader()
    {
        $this->hookActionFrontControllerInitAfter();

        CE\Plugin::instance();
        CE\did_action('template_redirect') || CE\do_action('template_redirect');

        if (self::$controller instanceof ProductController) {
            $this->addViewedProduct(self::$controller->getProduct()->id);
        }

        if ($uid_preview = self::getPreviewUId(false)) {
            if (CE\UId::TEMPLATE !== $uid_preview->id_type) {
                // Compatibility fix with Alysum theme
                unset(${'_GET'}['preview_id']);
            }
            if (CE\UId::CONTENT === $uid_preview->id_type && Tools::getIsset('maintenance') && !Tools::getIsset('render')) {
                // Preview Maintenance
                $this->displayMaintenancePage();
            }
        }

        // PS fix: OverrideLayoutTemplate hook doesn't exec on 403/404 page
        if (200 !== $resp_code = http_response_code()) {
            404 === $resp_code && self::$template = 'errors/404';

            $this->hookOverrideLayoutTemplate();
        }
    }

    protected function addViewedProduct($id_product)
    {
        $products = isset($this->context->cookie->ceViewedProducts)
            ? explode(',', $this->context->cookie->ceViewedProducts)
            : []
        ;
        if (in_array($id_product, $products)) {
            $products = array_diff($products, [$id_product]);
        }
        array_unshift($products, (int) $id_product);

        while (count($products) > static::VIEWED_PRODUCTS_LIMIT) {
            array_pop($products);
        }
        $this->context->cookie->ceViewedProducts = implode(',', $products);

        if (Tools::getValue('action') === 'quickview') {
            $this->context->cookie->write();
        }
    }

    public function hookDisplayOverrideTemplate($params = [])
    {
        null === self::$template && self::$template = $params['template_file'];
    }

    public static function skipOverrideLayoutTemplate()
    {
        self::$layout = '';
    }

    public function hookOverrideLayoutTemplate($params = [])
    {
        if (null !== self::$layout || !self::$template && 'front' === self::$controller->controller_type || !CE\did_action('template_redirect')) {
            return self::$layout;
        }
        self::$layout = '';
        $ce = CE\Plugin::$instance;
        $ce->kits_manager->frontendBeforeEnqueueStyles();

        if (self::isMaintenance()) {
            return self::$layout;
        }

        // Page Content
        $vars = &$this->context->smarty->tpl_vars;
        $uid_preview = self::getPreviewUId(false);
        $controller = self::$controller;
        $id_lang = $this->context->language->id;
        $id_shop = $this->context->shop->id;
        $front = http_response_code() !== 200 ? '' : strtolower(
            preg_replace('/(ModuleFront)?Controller(Override)?$/i', '', get_class($controller))
        );
        switch ($front) {
            case 'creativeelementspreview':
                $model = $uid_preview->getModel();
                $key = $model::${'definition'}['table'];

                if (isset($vars[$key]->value['id'])) {
                    $id = $vars[$key]->value['id'];
                    $desc = ['description' => &$vars[$key]->value['content']];
                }
                break;
            case 'cms' === $front && 2 === $controller->assignCase:
                $model = 'CMSCategory';

                if (isset($vars['cms_category']->value['id'])) {
                    $id = $vars['cms_category']->value['id'];
                    $desc = &$vars['cms_category']->value;
                }
                break;
            case 'cms':
                $model = $front;

                if (isset($vars[$model]->value['id'])) {
                    $id = $vars[$model]->value['id'];
                    $desc = ['description' => &$vars[$model]->value['content']];

                    CE\add_action('wp_head', 'print_og_image');
                }
                break;
            case 'product':
            case $controller instanceof ProductController:
                $front = 'product';
                // no break
            case 'category':
            case 'manufacturer':
            case 'supplier':
                $model = $front;

                if (isset($vars[$model]->value['id'])) {
                    $id = $vars[$model]->value['id'];
                    $desc = &$vars[$model]->value;
                }
                break;
            case 'ybc_blogblog':
                $model = 'Ybc_blog_post_class';
                $key = 'blog_post';

                if (isset($vars['post_detail_content'])) {
                    $key = 'blog_post_header';
                    $blog_content = &$vars['post_detail_content']->value;
                }
                if (isset($vars[$key]->value['id_post'])) {
                    $id = $vars[$key]->value['id_post'];
                    $desc = &$vars[$key]->value;

                    if (Tools::getIsset('adtoken') && self::hasAdminToken('AdminModules')) {
                        // override post status for preview
                        $vars[$key]->value['enabled'] = 1;
                    }
                }
                break;
            case 'ets_blogblog':
                $model = 'Ets_blog_post';
                $key = 'blog_post';

                if (isset($vars[$key]->value['id_post'])) {
                    $id = $vars[$key]->value['id_post'];
                    $desc = &$vars[$key]->value;
                }
                isset($vars['blog_content']) && $blog_content = &$vars['blog_content']->value;
                break;
            case 'xipblogsingle':
                $model = 'XipPostsClass';

                if (isset($vars['xipblogpost']->value['id_xipposts'])) {
                    $id = $vars['xipblogpost']->value['id_xipposts'];
                    $desc = ['description' => &$vars['xipblogpost']->value['post_content']];
                }
                break;
            case 'stblogarticle':
                $model = 'StBlogClass';

                if (isset($vars['blog']->value['id'])) {
                    $id = $vars['blog']->value['id'];
                    $desc = ['description' => &$vars['blog']->value['content']];
                    break;
                }
                $blog = Closure::bind(function () {
                    return $this->blog;
                }, $controller, "{$front}ModuleFrontController")->__invoke();

                if (isset($blog->id)) {
                    $id = $blog->id;
                    $desc = ['description' => &$blog->content];
                }
                break;
            case 'advanceblogdetail':
                $model = 'BlogPosts';

                if (isset($vars['postData']->value['id_post'])) {
                    $id = $vars['postData']->value['id_post'];
                    $desc = ['description' => &$vars['postData']->value['post_content']];
                }
                break;
            case 'prestablogblog':
            case 'prestablog' . strtolower(Configuration::get('prestablog_urlblog')) === $front:
                $model = 'NewsClass';
                $news = Closure::bind(function () {
                    return $this->news;
                }, $controller, str_replace('Override', '', get_class($controller)))->__invoke();

                if (isset($news->id)) {
                    $id = $news->id;

                    if (isset($vars['tpl_unique'])) {
                        $desc = ['description' => &$vars['tpl_unique']->value];
                    } else {
                        $desc = ['description' => &$news->content];
                    }
                }
                break;
            case 'hiblogpostdetails':
                $model = 'HiBlogPost';

                if (isset($vars['post']->value['id_post'])) {
                    $id = $vars['post']->value['id_post'];
                    $desc = &$vars['post']->value;

                    if (Tools::getIsset('adtoken') && self::hasAdminToken('AdminModules')) {
                        // override post status for preview
                        $vars['post']->value['enabled'] = 1;
                    }
                }
                break;
            case 'tvcmsblogsingle':
                $model = 'TvcmsPostsClass';

                if (isset($vars['tvcmsblogpost']->value['id_tvcmsposts'])) {
                    $id = $vars['tvcmsblogpost']->value['id_tvcmsposts'];
                    $desc = ['description' => &$vars['tvcmsblogpost']->value['post_content']];
                }
                break;
            case 'pm_advancedsearch4searchresults':
                $model = 'category';

                if (isset($vars[$model]->value['id'])) {
                    $id = $vars[$model]->value['id'];
                    $desc = &$vars[$model]->value;
                }
                break;
        }

        if (isset($id)) {
            // Init the ID
            if ($uid_preview && $uid_preview->id == $id && $uid_preview->id_type === CE\UId::getTypeId($model)) {
                CE\UId::$_ID = $uid_preview;
                $desc['description'] = CE\apply_filters('the_content', $desc['description']);
            } elseif (!CE\UId::$_ID || in_array(CE\UId::$_ID->id_type, [CE\UId::CONTENT, CE\UId::THEME, CE\UId::TEMPLATE])) {
                CE\UId::$_ID = new CE\UId($id, CE\UId::getTypeId($model), $id_lang, $id_shop);
                $desc['description'] = $ce->frontend->getBuilderContent(CE\UId::$_ID) ?: $desc['description'];
            }
            CE\UId::$_ID && $this->addBodyClasses('elementor-page', CE\UId::$_ID->toDefault());
            // ets_blog & ybc_blog 4.4.9+ fix
            isset($blog_content) && $blog_content = str_replace('<!--CE-POST-->', $desc['description'], $blog_content);
        }

        // Ajax render widgets & tags
        if ($uid_preview && $render = Tools::getValue('render')) {
            ($response = 'widget' === $render
                ? $ce->widgets_manager->ajaxRenderWidget()
                : $ce->dynamic_tags->ajaxRenderTags()
            ) && http_response_code(200);

            exit(json_encode($response));
        }

        // Theme Builder
        $theme = [
            'header' => Configuration::get('CE_HEADER'),
            'footer' => Configuration::get('CE_FOOTER'),
        ];
        $pages = [
            'index' => 'page-index',
            'contact' => 'page-contact',
            'errors/404' => 'page-not-found',
            'catalog/product' => 'product',
            'catalog/listing/category' => 'listing-category',
            'catalog/listing/manufacturer' => 'listing-manufacturer',
            'catalog/listing/prices-drop' => 'listing-prices-drop',
            'catalog/listing/new-products' => 'listing-new-products',
            'catalog/listing/best-sales' => 'listing-best-sales',
            'catalog/listing/search' => 'listing-search',
        ];
        foreach ($pages as $tpl => $doc_type) {
            if (self::$template === $tpl) {
                $theme[$doc_type] = Configuration::get(self::getThemeVarName($doc_type));
                break;
            }
        }
        $uid = CE\UId::$_ID;

        if ($uid_preview && (CE\UId::THEME === $uid_preview->id_type || CE\UId::TEMPLATE === $uid_preview->id_type)) {
            CE\UId::$_ID = $uid_preview;
            $preview = CE\apply_filters('the_content', '');
            $type_preview = $ce->documents->getDocForFrontend($uid_preview)->getTemplateType();
            'listing-no-results' === $type_preview && Configuration::set('CE_LISTING_NO_RESULTS', $uid_preview->id);
            'listing-page' === $type_preview && $type_preview = "listing-{$controller->php_self}";
            $this->context->smarty->assign(self::getThemeVarName($type_preview), $preview);
            unset($desc);

            if (strpos($type_preview, 'page-') === 0) {
                // Static pages
                $uid = CE\UId::$_ID;
                $desc = ['description' => &$preview];

                CE\add_action('wp_head', 'print_og_image');
            } elseif ('header' !== $type_preview && 'footer' !== $type_preview) {
                // Product or Listing templates
                $desc = ['description' => &$preview];

                if ('product-quick-view' === $type_preview) {
                    $this->context->smarty->assign('CE_PRODUCT_QUICK_VIEW_ID', $uid_preview->id);
                }
            }
            in_array($type_preview, $pages) && $this->addBodyClasses('ce-theme', $uid_preview->id);
            unset($theme[$type_preview]);
        }

        if (isset($pages[self::$template]) && !empty($theme[$pages[self::$template]]) && (new CETheme($theme[$doc_type], $id_lang, $id_shop))->active) {
            $doc_type = $pages[self::$template];
            CE\UId::$_ID = new CE\UId($theme[$doc_type], CE\UId::THEME, $id_lang, $id_shop);
            $this->context->smarty->assign($theme_var = self::getThemeVarName($doc_type), $ce->frontend->getBuilderContent(CE\UId::$_ID));
            // Set $desc only if not product miniature / quickview
            if (empty($type_preview) || strpos($type_preview, 'product-') !== 0) {
                unset($desc);
                $desc = ['description' => &$vars[$theme_var]->value];
            }
            if (in_array(self::$template, ['index', 'contact', 'errors/404'])) {
                $uid = CE\UId::$_ID;
                CE\add_action('wp_head', 'print_og_image');
            }
            $this->addBodyClasses('ce-theme', CE\UId::$_ID->id);
            unset($theme[$doc_type]);
        }

        if (empty($params['content_only']) && self::$layout = CE\apply_filters('template_include', self::$layout)) {
            $body_classes = &$vars['page']->value['body_classes'];
            $body_classes[basename(self::$layout, '.tpl')] = 1;

            if (isset($params['default_layout'])) {
                unset($body_classes[basename($params['default_layout'], '.tpl')]);
            }
        }

        if (strrpos(self::$layout, 'layout-canvas') !== false) {
            isset($desc) && $this->context->smarty->assign('ce_desc', $desc);
        } else {
            foreach ($theme as $doc_type => $id_ce_theme) {
                if (!(new CETheme($id_ce_theme, $id_lang, $id_shop))->active) {
                    continue;
                }
                CE\UId::$_ID = new CE\UId($id_ce_theme, CE\UId::THEME, $id_lang, $id_shop);
                $id_ce_theme && $this->context->smarty->assign(self::getThemeVarName($doc_type), $ce->frontend->getBuilderContent(CE\UId::$_ID));

                if ('footer' === $doc_type && $id_ce_theme && method_exists($wishlist = Module::getInstanceByName('blockwishlist') ?: '', 'hookDisplayFooter') && $wishlist->active) {
                    // Wishlist fix for missing displayFooter hook
                    empty($wishlist->smarty->tpl_vars['addUrl']) && $vars['CE_FOOTER']->value .= $wishlist->hookDisplayFooter([]);
                }
            }
        }
        CE\UId::$_ID = $uid;

        return self::$layout;
    }

    protected function addBodyClasses($class, $id)
    {
        $body_classes = &$this->context->smarty->tpl_vars['page']->value['body_classes'];
        $body_classes[$class] = 1;
        $body_classes["$class-$id"] = 1;
    }

    protected function displayMaintenancePage()
    {
        Configuration::set('PS_SHOP_ENABLE', false);
        Configuration::set('PS_MAINTENANCE_IP', '');
        Configuration::set('PS_MAINTENANCE_ALLOW_ADMINS', false);

        $displayMaintenancePage = new ReflectionMethod($this->context->controller, 'displayMaintenancePage');
        $displayMaintenancePage->setAccessible(true);
        $displayMaintenancePage->invoke($this->context->controller);
    }

    public function hookDisplayMaintenance($params = [])
    {
        if (self::getPreviewUId(false)) {
            http_response_code(200);
            header_remove('Retry-After');
        }

        CE\add_filter('the_content', function () {
            $uid = CE\get_the_ID();
            $this->context->smarty->assign('ce_content', new CEContent($uid->id, $uid->id_lang, $uid->id_shop));

            CE\remove_all_filters('the_content');
        }, 0);

        if (!$maintenance = $this->renderContent('displayMaintenance')) {
            return;
        }
        self::$controller->registerJavascript('jquery', 'js/jquery/jquery-1.11.0.min.js');

        $this->context->smarty->assign([
            'iso_code' => $this->context->language->iso_code,
            'favicon' => Configuration::get('PS_FAVICON'),
            'favicon_update_time' => Configuration::get('PS_IMG_UPDATE_TIME'),
        ]);

        return $maintenance;
    }

    public function hookDisplayHome()
    {
        return $this->renderContent('displayHome');
    }

    public function hookDisplayFooterProduct($params = [])
    {
        return $this->renderContent('displayFooterProduct');
    }

    public function __call($method, $args)
    {
        if (stripos($method, 'hookActionObject') === 0 && stripos($method, 'DeleteAfter') !== false) {
            $this->hookActionObjectDeleteAfter(...$args);
        } elseif (stripos($method, 'hook') === 0) {
            // render hook only after Header init
            if (null !== self::$layout) {
                return $this->renderContent(substr($method, 4));
            }
        } else {
            throw new Exception('Can not find method: ' . $method);
        }
    }

    public function renderContent($hook_name)
    {
        if (!$hook_name) {
            return;
        }
        $out = '';
        $id_product = Tools::getValue('id_product', 0);
        $preview = self::getPreviewUId(false);
        $rows = CEContent::getIdsByHook($hook_name, $this->context->language->id, $this->context->shop->id, $id_product, $preview);

        if ($rows) {
            $uid = CE\UId::$_ID;

            foreach ($rows as &$row) {
                $id_shop = $preview && $row['id'] == $preview->id && CE\UId::CONTENT === $preview->id_type
                    ? $preview->id_shop
                    : $this->context->shop->id;
                CE\UId::$_ID = new CE\UId($row['id'], CE\UId::CONTENT, $this->context->language->id, $id_shop);

                if ('displayMaintenance' === $hook_name && !$preview && CE\get_post_meta(CE\UId::$_ID, '_elementor_edit_mode', true)) {
                    $this->hookActionFrontControllerInitAfter();

                    CE\Plugin::instance();
                    CE\did_action('template_redirect') || CE\do_action('template_redirect');
                }

                $out .= CE\apply_filters('the_content', '');
            }
            CE\UId::$_ID = $uid;
        }

        return $out;
    }

    public function registerHook($hook_name, $shop_list = null, $position = null)
    {
        $res = parent::registerHook($hook_name, $shop_list);

        if ($res && is_numeric($position)) {
            $this->updatePosition(Hook::getIdByName($hook_name), 0, $position);
        }

        return $res;
    }

    public function unregisterHook($hook_id, $shop_list = null)
    {
        try {
            $result = Hook::unregisterHook($this, $hook_id, $shop_list);
        } catch (PrestaShopObjectNotFoundException $ex) {
            $result = Db::getInstance()->delete(
                'hook_module',
                '`id_module` = ' . (int) $this->id . ' AND `id_hook` = ' . (int) $hook_id .
                ($shop_list ? ' AND `id_shop` IN (' . implode(',', array_map('intval', $shop_list)) . ')' : '')
            );
            $this->cleanPositions($hook_id, $shop_list);
        }

        return $result;
    }

    public function hookCETemplate($params = [])
    {
        if (!empty($params['id'])) {
            $uid = new CE\UId($params['id'], CE\UId::TEMPLATE);

            return CE\Plugin::$instance->frontend->getBuilderContentForDisplay($uid);
        }
    }

    public function hookActionClearCompileCache()
    {
        Db::getInstance()->delete('ce_meta', "`name` = '_ce_element_cache'");
    }

    public function hookActionObjectDeleteAfter($params = [])
    {
        $model = get_class($params['object']);
        $id_type = CE\UId::getTypeId($model);
        $id_start = sprintf('%d%02d', $params['object']->id, $id_type);

        // Delete meta data
        Db::getInstance()->delete('ce_meta', "`id` LIKE '{$id_start}____'");

        // Delete CSS files
        array_map('unlink', glob(_CE_PATH_ . "views/css/ce/$id_start????.css", GLOB_NOSORT));

        // Delete revisions
        $id_type && CERevision::deleteAllByParent("{$id_start}____");
    }

    public function hookActionObjectProductDeleteAfter($params = [])
    {
        $id_product = $params['object']->id;
        $this->hookActionObjectDeleteAfter($params);

        // Delete displayFooterProduct content
        if ($id_ce_content = CEContent::getFooterProductId($id_product)) {
            $content = new CEContent($id_ce_content);
            Validate::isLoadedObject($content) && $content->delete();
        }
        // Remove deleted product ID from page settings
        Db::getInstance()->execute(
            'UPDATE ' . _DB_PREFIX_ . 'ce_meta ' .
            'SET `value` = REPLACE(`value`, \'"preview_id":"' . (int) $id_product . '"\', \'"preview_id":""\') ' .
            'WHERE `name` = "_elementor_page_settings" AND `value` LIKE \'%"preview_id":"' . (int) $id_product . '"%\''
        );
    }

    public function hookActionProductAdd($params = [])
    {
        if (isset($params['request']) && $params['request']->attributes->get('action') === 'duplicate') {
            $id_product_duplicate = (int) $params['request']->attributes->get('id');
        } elseif (Tools::getIsset('duplicateproduct')) {
            $id_product_duplicate = (int) Tools::getValue('id_product');
        }

        if (isset($id_product_duplicate, $params['id_product']) && $built_list = CE\UId::getBuiltList($id_product_duplicate, CE\UId::PRODUCT)) {
            $db = CE\Plugin::instance()->db;
            $uid = new CE\UId($params['id_product'], CE\UId::PRODUCT, 0);

            foreach ($built_list as $id_shop => &$langs) {
                foreach ($langs as $id_lang => $uid_from) {
                    $uid->id_lang = $id_lang;
                    $uid->id_shop = $id_shop;

                    $db->copyElementorMeta($uid_from, $uid);
                }
            }
        }
    }

    public function hookFilterProductSearch($params)
    {
        if (isset($_REQUEST['q']) && preg_match_all('`(?:^|/)([^-]+)`', $_REQUEST['q'], $filters)) {
            $colors = CE\Helper::getColorAttributeGroupNames($this->context->language->id, $this->context->shop->id);

            if (!array_intersect(array_column($colors, 'name'), array_map('urldecode', $filters[1]))) {
                return;
            }
            foreach ($params['searchVariables']['products'] as $product) {
                if (!$product['images']) {
                    continue;
                }
                foreach ($product['images'] as $image) {
                    if ($product['cover_image_id'] === $image['id_image']) {
                        continue 2;
                    }
                }
                $product['cover'] = $product['images'][0];
            }
        }
    }

    protected function checkThemeChange()
    {
        if (!$theme = Configuration::get('CE_THEME')) {
            Configuration::updateValue('CE_THEME', _THEME_NAME_);
        } elseif (_THEME_NAME_ !== $theme) {
            require_once _CE_PATH_ . 'classes/CEDatabase.php';

            // register missing hooks after changing theme
            foreach (CEDatabase::getHooks() as $hook) {
                $this->registerHook($hook, null, 1);
            }
            Configuration::updateValue('CE_THEME', _THEME_NAME_);
        }
    }

    public static function getPreviewUId($embed = true)
    {
        static $res = null;

        if (null === $res && $res = isset($_REQUEST['preview_id']) && $uid = CE\UId::parse($_REQUEST['preview_id'])) {
            $res = self::hasAdminToken($uid->getAdminController()) ? $uid : false;
        }

        return !$embed || Tools::getIsset('ver') ? $res : false;
    }

    public static function hasAdminToken($tab)
    {
        $key = 'AdminBlogPosts' === $tab ? 'blogtoken' : (strpos($tab, 'AdminCE') === 0 ? 'cetoken' : 'adtoken');

        return (int) _PS_VERSION_ < 9
            ? Tools::getValue($key) === Tools::getAdminToken($tab . (int) Tab::getIdFromClassName($tab) . (int) Tools::getValue('id_employee'))
            : self::$controller->getContainer()->get('PrestaShopBundle\Security\Admin\LegacyAdminTokenValidator')
                ->isTokenValid($tab, (int) Tools::getValue('id_employee'), Tools::getValue($key));
    }

    public static function getThemeVarName($doc_type)
    {
        if ('product' === $doc_type && Tools::getValue('quickview')) {
            return 'CE_PRODUCT_QUICK_VIEW';
        } elseif ('product' === $doc_type && Tools::getValue('miniature')) {
            return 'CE_PRODUCT_MINIATURE';
        }

        return 'CE_' . strtoupper(str_replace('-', '_', $doc_type));
    }

    public static function isMaintenance()
    {
        return !Configuration::get('PS_SHOP_ENABLE')
            && !(Configuration::get('PS_MAINTENANCE_ALLOW_ADMINS') && (int) (new Cookie('psAdmin'))->id_employee)
            && !in_array(Tools::getRemoteAddr(), array_map(version_compare(_PS_VERSION_, '1.7.8', '<') ? 'strval' : 'trim', explode(',', Configuration::get('PS_MAINTENANCE_IP'))));
    }
}
