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

class AdminCEEditorController extends ModuleAdminController
{
    public $name = 'AdminCEEditor';

    public $display_header = false;

    public $content_only = true;

    /** @var CE\UId */
    protected $uid;

    public function initShopContext()
    {
        require_once _PS_MODULE_DIR_ . 'creativeelements/classes/wrappers/UId.php';

        Tools::getIsset('uid') && $this->uid = CE\UId::parse(Tools::getValue('uid'));

        if (!empty($this->uid->id_shop) && $this->uid->id_type > CE\UId::TEMPLATE && Shop::getContext() > 1) {
            ${'_POST'}['setShopContext'] = 's-' . $this->uid->id_shop;
        }
        parent::initShopContext();
    }

    public function init()
    {
        if (isset($this->context->cookie->last_activity)) {
            if ($this->context->cookie->last_activity + 900 < time()) {
                $this->context->employee->logout();
            } else {
                $this->context->cookie->last_activity = time();
            }
        }

        if (!isset($this->context->employee) || !$this->context->employee->isLoggedBack()) {
            if (isset($this->context->employee)) {
                $this->context->employee->logout();
            }
            $redirect = ($uid = CE\UId::parse(Tools::getValue('uid'))) ? "&redirect={$uid->getAdminController()}" : '';

            Tools::redirectAdmin($this->context->link->getAdminLink('AdminLogin') . $redirect);
        }

        $this->initProcess();
    }

    public function initCursedPage()
    {
        if ($this->ajax) {
            CE\wp_send_json_error('token_expired');
        }
        parent::initCursedPage();
    }

    public function initProcess()
    {
        header('Cache-Control: no-store, no-cache');

        $this->ajax = Tools::getIsset('ajax');
        $this->action = Tools::getValue('action', '');
        $this->tabAccess = Profile::getProfileAccess($this->context->employee->id_profile, $this->id);

        if (!$this->access('view')) {
            return self::redirectBack(['perm' => 1]);
        } elseif (!$this->access('edit')) {
            return self::redirectBack(['perm' => 2]);
        }

        if (isset($_SERVER['HTTP_SEC_FETCH_DEST']) && "\x69frame" === $_SERVER['HTTP_SEC_FETCH_DEST'] && !Tools::getIsset('uid')) {
            exit(CESmarty::get(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_fancybox_close'));
        }

        if (Shop::isFeatureActive() && $this->uid && !$this->ajax) {
            $domain = Tools::getShopProtocol() === 'http://' ? 'domain' : 'domain_ssl';

            if ($this->context->shop->$domain !== $_SERVER['HTTP_HOST'] && $this->viewAccess()) {
                CE\update_post_meta(0, 'cookie', $this->context->cookie->getAll());

                $id_shop = $this->uid->id_shop ?: $this->uid->getDefaultShopId();
                $admin = basename(_PS_ADMIN_DIR_);

                Tools::redirectAdmin(
                    $this->context->link->getModuleLink('creativeelements', 'preview', [
                        'id_employee' => $this->context->employee->id,
                        'cetoken' => Tools::getAdminTokenLite('AdminCEEditor'),
                        'redirect' => urlencode(
                            $this->context->link->getAdminBaseLink($id_shop) . $admin . explode($admin, $_SERVER['REQUEST_URI'], 2)[1]
                        ),
                    ], true, $this->uid->id_lang ?: null, $id_shop ?: null)
                );
            }
        }
        CE\Plugin::instance();
    }

    public function setMedia($isNewTheme = false)
    {
    }

    public function postProcess()
    {
        $process = 'process' . Tools::toCamelCase($this->action, true);

        if ($this->ajax) {
            method_exists($this, "ajax$process") && $this->{"ajax$process"}();

            if ('elementor_ajax' === $this->action) {
                CE\add_action('elementor/ajax/register_actions', [$this, 'registerAjaxActions']);
            }
            CE\do_action('wp_ajax_' . $this->action);
        } elseif ($this->action && method_exists($this, $process)) {
            // Call process
            return $this->$process();
        }

        return false;
    }

    public function initContent()
    {
        CE\add_action('elementor/editor/before_enqueue_scripts', [$this, 'beforeEnqueueScripts']);

        CE\Plugin::instance()->editor->init() || self::redirectBack(['perm' => 2]);
    }

    public function beforeEnqueueScripts()
    {
        $suffix = _PS_MODE_DEV_ ? '' : '.min';

        // Enqueue CE assets
        CE\wp_enqueue_style('ce-editor', _CE_ASSETS_URL_ . 'css/editor-ce.css', [], _CE_VERSION_);
        CE\wp_register_script('ce-editor', _CE_ASSETS_URL_ . 'js/editor-ce.js', [], _CE_VERSION_, true);
        CE\wp_localize_script('ce-editor', 'baseDir', __PS_BASE_URI__);
        CE\wp_enqueue_script('ce-editor');

        // Enqueue TinyMCE assets
        CE\wp_enqueue_style('material-icons', _CE_ASSETS_URL_ . 'lib/material-icons/material-icons.css', [], '1.012');
        CE\wp_enqueue_style('tinymce-theme', _CE_ASSETS_URL_ . "lib/tinymce/ps-theme{$suffix}.css", [], _CE_VERSION_);

        CE\wp_register_script('tinymce', _PS_JS_DIR_ . 'tiny_mce/tinymce.min.js', ['jquery'], false, true);
        CE\wp_register_script('tinymce-inc', _CE_ASSETS_URL_ . 'lib/tinymce/tinymce.inc.js', ['tinymce'], _CE_VERSION_, true);

        CE\wp_localize_script('tinymce', 'baseAdminDir', __PS_BASE_URI__ . basename(_PS_ADMIN_DIR_) . '/');
        CE\wp_localize_script('tinymce', 'iso_user', CE\get_locale());

        CE\wp_enqueue_script('tinymce-inc');
    }

    public function processBackToPsEditor()
    {
        if (CE\current_user_can('edit', $this->uid)) {
            CE\Plugin::instance()->db->setIsElementorPage($this->uid, false);
        }
        self::redirectBack();
    }

    public function processAddFooterProduct()
    {
        if (!$this->uid->id || $this->uid->id_type != CE\UId::PRODUCT) {
            return self::redirectBack();
        }

        $content = new CEContent();
        $content->hook = 'displayFooterProduct';
        $content->id_product = $this->uid->id;
        $content->active = true;
        $content->title = [];
        $content->content = [];

        foreach (Language::getLanguages(false) as $lang) {
            $content->title[$lang['id_lang']] = 'Product Footer #' . $this->uid->id;
        }
        $content->save();

        $uid = new CE\UId($content->id, CE\UId::CONTENT, $this->uid->id_lang, $this->uid->id_shop);

        Tools::redirectAdmin(
            $this->context->link->getAdminLink('AdminCEEditor') . "&uid=$uid&footerProduct={$content->id_product}"
        );
    }

    public function processAddMaintenance()
    {
        if (!$uid = Tools::getValue('uid')) {
            return self::redirectBack();
        }

        $content = new CEContent();
        $content->hook = 'displayMaintenance';
        $content->active = true;
        $content->title = [];
        $content->content = [];

        foreach (Language::getLanguages(false) as $lang) {
            $id_lang = $lang['id_lang'];

            $content->title[$id_lang] = 'Maintenance';
            $content->content[$id_lang] = (string) Configuration::get('PS_MAINTENANCE_TEXT', $id_lang);
        }
        $content->save();

        $id_lang = substr($uid, -4, 2);
        $id_shop = substr($uid, -2);
        $uid = new CE\UId($content->id, CE\UId::CONTENT, $id_lang, $id_shop);

        Tools::redirectAdmin($this->context->link->getAdminLink('AdminCEEditor') . "&uid=$uid");
    }

    public function ajaxProcessHeartbeat()
    {
        $response = [];
        $data = isset(${'_POST'}['data']) ? (array) ${'_POST'}['data'] : [];
        $screen_id = Tools::getValue('screen_id', 'front');

        $data && $response = CE\apply_filters('heartbeat_received', $response, $data, $screen_id);

        $response = CE\apply_filters('heartbeat_send', $response, $screen_id);

        CE\do_action('heartbeat_tick', $response, $screen_id);

        $response['server_time'] = time();

        CE\wp_send_json($response);
    }

    public function ajaxProcessAutocompleteLink()
    {
        $context = Context::getContext();
        $display_suppliers = Configuration::get('PS_DISPLAY_SUPPLIERS');
        $display_manufacturers = version_compare(_PS_VERSION_, '1.7.7', '<')
            ? $display_suppliers
            : Configuration::get('PS_DISPLAY_MANUFACTURERS');
        $search = Tools::getValue('search');
        $id_lang = $context->language->id;
        $id_shop = $context->shop->id;

        $rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('(
            SELECT m.`id_meta` AS `ID`, ml.`title`, m.`page` AS `permalink`, "Page" AS `info` FROM ' . _DB_PREFIX_ . 'meta m
            LEFT JOIN ' . _DB_PREFIX_ . 'meta_lang ml ON m.`id_meta` = ml.`id_meta`
            WHERE ml.`id_lang` = ' . (int) $id_lang . ' AND ml.`id_shop` = ' . (int) $id_shop . ' AND ml.`title` LIKE "%' . pSQL($search) . '%" LIMIT 10
        ) UNION (
            SELECT `id_cms` AS `ID`, `meta_title` AS `title`, `link_rewrite` AS `permalink`, "CMS" AS `info` FROM ' . _DB_PREFIX_ . 'cms_lang
            WHERE `id_lang` = ' . (int) $id_lang . ' AND `id_shop` = ' . (int) $id_shop . ' AND `meta_title` LIKE "%' . pSQL($search) . '%" LIMIT 10
        ) UNION (
            SELECT `id_cms_category` AS `ID`, `name` AS `title`, `link_rewrite` AS `permalink`, "CMS Category" AS `info` FROM ' . _DB_PREFIX_ . 'cms_category_lang
            WHERE `id_lang` = ' . (int) $id_lang . ' AND `id_shop` = ' . (int) $id_shop . ' AND `name` LIKE "%' . pSQL($search) . '%" LIMIT 10
        ) UNION (
            SELECT `id_product` AS `ID`, `name` AS `title`, "" AS `permalink`, "Product" AS `info` FROM ' . _DB_PREFIX_ . 'product_lang
            WHERE `id_lang` = ' . (int) $id_lang . ' AND `id_shop` = ' . (int) $id_shop . ' AND `name` LIKE "%' . pSQL($search) . '%" LIMIT 10
        ) UNION (
            SELECT `id_category` AS `ID`, `name` AS `title`, `link_rewrite` AS `permalink`, "Category" AS `info` FROM ' . _DB_PREFIX_ . 'category_lang
            WHERE `id_lang` = ' . (int) $id_lang . ' AND `id_shop` = ' . (int) $id_shop . ' AND `name` LIKE "%' . pSQL($search) . '%" LIMIT 10
        )' . (!$display_manufacturers ? '' : ' UNION (
            SELECT `id_manufacturer` AS `ID`, `name` AS `title`, "" AS `permalink`, "Brand" AS `info` FROM ' . _DB_PREFIX_ . 'manufacturer
            WHERE `active` = 1 AND `name` LIKE "%' . pSQL($search) . '%" LIMIT 10
        )') . (!$display_suppliers ? '' : ' UNION (
            SELECT `id_supplier` AS `ID`, `name` AS `title`, "" AS `permalink`, "Supplier" AS `info` FROM ' . _DB_PREFIX_ . 'supplier
            WHERE `active` = 1 AND `name` LIKE "%' . pSQL($search) . '%" LIMIT 10
        )'));

        if ($rows) {
            foreach ($rows as &$row) {
                switch ($row['info']) {
                    case 'CMS':
                        $row['permalink'] = $context->link->getCMSLink($row['ID'], $row['permalink'], null, $id_lang, $id_shop);
                        break;
                    case 'CMS Category':
                        $row['permalink'] = $context->link->getCMSCategoryLink($row['ID'], $row['permalink'], $id_lang, $id_shop);
                        break;
                    case 'Product':
                        $product = new Product($row['ID'], false, $id_lang, $id_shop);
                        $row['permalink'] = $context->link->getProductLink($product);
                        break;
                    case 'Category':
                        $row['permalink'] = $context->link->getCategoryLink($row['ID'], $row['permalink'], $id_lang, null, $id_shop);
                        break;
                    case 'Brand':
                        $row['permalink'] = $context->link->getManufacturerLink($row['ID'], Tools::str2url($row['title']), $id_lang, $id_shop);
                        break;
                    case 'Supplier':
                        $row['permalink'] = $context->link->getSupplierLink($row['ID'], Tools::str2url($row['title']), $id_lang, $id_shop);
                        break;
                    default:
                        $row['permalink'] = $context->link->getPageLink($row['permalink'], null, $id_lang, null, false, $id_shop);
                        break;
                }
                $row['info'] = CE\__($row['info']);
            }
        }
        exit(json_encode($rows));
    }

    public function ajaxProcessCmsList()
    {
        $context = Context::getContext();
        $query = Tools::getValue('q');
        $limit = Tools::getValue('limit', 20);
        $rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT `id_cms` AS `id`, CONCAT("#", `id_cms`, " ", `meta_title`) AS `name` FROM ' . _DB_PREFIX_ . 'cms_lang
            WHERE `id_lang` = ' . (int) $context->language->id . ' AND `id_shop` = ' . (int) $context->shop->id . ' AND `meta_title` LIKE "%' . pSQL($query) . '%"
            LIMIT ' . (int) $limit
        );
        exit(json_encode($rows));
    }

    public function ajaxProcessGetCmsById()
    {
        if (!$ids = Tools::getValue('ids')) {
            return [];
        }
        $context = Context::getContext();
        $limit = count($ids);
        $rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT `id_cms` AS `id`, CONCAT("#", `id_cms`, " ", `meta_title`) AS `name` FROM ' . _DB_PREFIX_ . 'cms_lang
            WHERE `id_lang` = ' . (int) $context->language->id . ' AND `id_shop` = ' . (int) $context->shop->id . ' AND `id_cms` IN (' . implode(',', array_map('intval', (array) $ids)) . ')
            LIMIT ' . (int) $limit
        );
        exit(json_encode($rows));
    }

    public function ajaxProcessGetProductsById()
    {
        if (!$ids = Tools::getValue('ids')) {
            return [];
        }
        $context = Context::getContext();
        $results = [];
        $items = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT p.`id_product`, pl.`link_rewrite`, p.`reference`, pl.`name`, s.`id_image` id_image FROM `' . _DB_PREFIX_ . 'product` p ' .
            Shop::addSqlAssociation('product', 'p') . '
            LEFT JOIN ' . _DB_PREFIX_ . 'product_lang pl ON pl.`id_product` = p.`id_product` AND pl.`id_lang` = ' . (int) $context->language->id . Shop::addSqlRestrictionOnLang('pl') . '
            LEFT JOIN ' . _DB_PREFIX_ . 'image_shop s ON s.`id_product` = p.`id_product` AND s.`cover` = 1 AND s.`id_shop` = ' . (int) $context->shop->id . '
            LEFT JOIN ' . _DB_PREFIX_ . 'image_lang l ON s.`id_image` = l.`id_image` AND l.`id_lang` = ' . (int) $context->language->id . '
            WHERE p.`id_product` IN (' . implode(',', array_map('intval', (array) $ids)) . ') GROUP BY p.`id_product`
        ');
        if ($items) {
            $protocol = Tools::getShopProtocol();
            $image_size = ImageType::getFormattedName('home');

            foreach ($items as &$item) {
                if (false !== $key = array_search($item['id_product'], $ids)) {
                    unset($ids[$key]);
                }
                $results[] = [
                    'id' => $item['id_product'],
                    'id_product' => $item['id_product'],
                    'name' => $item['name'] . (!empty($item['reference']) ? ' (ref: ' . $item['reference'] . ')' : ''),
                    'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                    'image' => str_replace('http://', $protocol, $context->link->getImageLink($item['link_rewrite'], $item['id_image'], $image_size)),
                ];
            }
        }
        foreach ($ids as $id) {
            $results[] = [
                'id' => 0,
                'id_product' => $id,
                'name' => "#$id Not Found",
            ];
        }
        exit(json_encode($results));
    }

    public function registerAjaxActions($ajax_manager)
    {
        $ajax_manager->registerAjaxAction('get_language_content', [$this, 'ajaxGetLanguageContent']);

        CE\add_filter('elementor/api/get_templates/body_args', [$this, 'filterApiGetTemplateArgs']);
        CE\add_filter('elementor/api/get_templates/content', [$this, 'filterApiGetTemplateContent']);
        CE\add_action('elementor/document/after_save', [$this, 'onAfterSaveDocument']);
    }

    public function ajaxGetLanguageContent($request)
    {
        $data = null;

        if (!empty($request['uid']) && $data = CE\get_post_meta($request['uid'], '_elementor_data', true)) {
            CE\Plugin::$instance->db->iterateData($data, function ($element) {
                $element['id'] = CE\Utils::generateRandomString();

                return $element;
            });
        }

        return is_array($data) ? $data : [];
    }

    public function filterApiGetTemplateArgs($body_args)
    {
        $id_shop = Configuration::getGlobalValue('PS_SHOP_DEFAULT');
        $license_key = Configuration::getGlobalValue('CE_LICENSE');

        if ($license_key) {
            $body_args['license'] = $license_key;
            $body_args['domain'] = Tools::getShopProtocol() === 'http://'
                ? ShopUrl::getMainShopDomain($id_shop)
                : ShopUrl::getMainShopDomainSSL($id_shop)
            ;
        }

        return $body_args;
    }

    public function filterApiGetTemplateContent($content)
    {
        if (isset($content['error'])) {
            if (isset($content['code']) && 419 == $content['code']) {
                Configuration::updateGlobalValue('CE_LICENSE', '');
            }
            CE\wp_send_json_error($content['error']);
        } else {
            $documents = CE\Plugin::$instance->documents;
            $documents->switchToDocument($documents->get(CE\get_the_ID()));
        }

        return $content;
    }

    public function onAfterSaveDocument($document)
    {
        // Set edit mode to builder only at save
        CE\Plugin::$instance->db->setIsElementorPage($document->getPost()->uid);
    }

    protected static function redirectBack(array $args = [])
    {
        $back = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';

        Tools::redirectAdmin($args ? Tools::url($back, http_build_query($args)) : $back);
    }
}
