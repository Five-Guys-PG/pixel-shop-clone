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

use PrestaShop\PrestaShop\Adapter\Presenter\AbstractLazyArray;

require_once _CE_PATH_ . 'classes/wrappers/UId.php';
require_once _CE_PATH_ . 'classes/wrappers/Post.php';
require_once _CE_PATH_ . 'classes/wrappers/Error.php';

const AUTOSAVE_INTERVAL = 60;

class Helper
{
    public static $actions = [];

    public static $actions_fired = [];

    public static $filters = [];

    // public static $current_filter = [];

    public static $styles = [];

    public static $inline_styles = [];

    public static $scripts = [];

    public static $head_styles = [];

    public static $head_scripts = [];

    public static $body_scripts = [];

    public static $productHooks = [
        'displayfooterproduct',
        'displayproductadditionalinfo',
        'displayproductlistreviews',
        'displayproductpriceblock',
        'displayafterproductthumbs',
        'displayleftcolumnproduct',
        'displayrightcolumnproduct',
    ];

    public static $section_stack = [null];

    public static function getFirstTabIndex($section)
    {
        foreach ($section->render_tabs as $index => $should_render) {
            if ($should_render) {
                return $index;
            }
        }

        return 0;
    }

    public static function getColorAttributeGroupNames($id_lang, $id_shop)
    {
        if (\Cache::isStored($cache_id = "CE\Helper::getColorAttributeGroupNames-$id_lang-$id_shop")) {
            return \Cache::retrieve($cache_id);
        }
        \Cache::store($cache_id, $result = \Db::getInstance()->executeS('
            SELECT IF(f.`url_name`, f.`url_name`, l.`public_name`) AS `name` FROM ' . _DB_PREFIX_ . 'attribute_group a
            INNER JOIN ' . _DB_PREFIX_ . 'attribute_group_shop s ON a.`id_attribute_group` = s.`id_attribute_group` AND s.`id_shop` = ' . (int) $id_shop . '
            INNER JOIN ' . _DB_PREFIX_ . 'attribute_group_lang l ON a.`id_attribute_group` = l.`id_attribute_group` AND l.`id_lang` = ' . (int) $id_lang . '
            LEFT JOIN ' . _DB_PREFIX_ . 'layered_indexable_attribute_group_lang_value f ON a.`id_attribute_group` = f.`id_attribute_group` AND f.`id_lang` = ' . (int) $id_lang . '
            WHERE a.`is_color_group` = 1
        '));

        return $result;
    }

    public static function getLastUpdatedProductId($id_shop)
    {
        return \Db::getInstance()->getValue(
            'SELECT `id_product` FROM ' . _DB_PREFIX_ . 'product_shop WHERE `id_shop` = ' . (int) $id_shop . ' ORDER BY `active` DESC, `date_upd` DESC'
        );
    }

    public static function getLastUpdatedManufacturerId()
    {
        return \Db::getInstance()->getValue(
            'SELECT `id_manufacturer` FROM ' . _DB_PREFIX_ . 'manufacturer WHERE `active` = 1 ORDER BY `date_upd` DESC'
        );
    }

    public static function getAjaxLink()
    {
        return preg_replace('/\buid=\d+(&footerProduct=\d+)?/', 'ajax=1', $_SERVER['REQUEST_URI']);
    }

    public static function getAjaxProductsListLink()
    {
        if (!is_admin()) {
            return '';
        }

        if (version_compare(_PS_VERSION_, '1.7.6', '<')) {
            $link = 'ajax_products_list.php?';
            $args = [];
        } else {
            $link = 'index.php?';
            $args = [
                'controller' => 'AdminProducts',
                'token' => \Tools::getAdminTokenLite('AdminProducts'),
                'ajax' => 1,
                'action' => 'productsList',
            ];
        }

        return $link . http_build_query($args + [
            'forceJson' => 1,
            'disableCombination' => 1,
            'excludeVirtuals' => 0,
            'exclude_packs' => 0,
            'limit' => 20,
        ]);
    }

    public static function getWrapfix()
    {
        $wrapfix = [
            'classic' => version_compare(_PS_VERSION_, '1.7.6', '<') ? 'featured-products' : 'products',
        ];

        return isset($wrapfix[_THEME_NAME_]) ? $wrapfix[_THEME_NAME_] : '';
    }

    public static function getMediaLink($url, $full = false)
    {
        if ($url && strpos($url, '://') === false) {
            $url = __PS_BASE_URI__ . $url;

            if (_MEDIA_SERVER_1_ || $full) {
                $url = \Context::getContext()->link->getMediaLink($url);
            }
        }

        return $url;
    }

    public static function getProductImageLink($image, $size = '')
    {
        $id = (int) $image['id_image'];

        return self::getMediaLink('img/p/' . implode('/', str_split("$id")) . ($size ? "/$id-$size.jpg" : "/$id.jpg"));
    }

    public static function getNoImage()
    {
        static $image;

        if (null === $image) {
            $context = \Context::getContext();
            $imageRetriever = new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($context->link);

            $image = method_exists($imageRetriever, 'getNoPictureImage')
                ? $imageRetriever->getNoPictureImage($context->language)
                : $imageRetriever->getImage(
                    new \Product(0, false, $context->language->id),
                    $context->language->iso_code . '-default'
                );
        }

        return $image;
    }

    public static function getSettingsLink()
    {
        return \Context::getContext()->link->getAdminLink('AdminCESettings');
    }

    public static function getClearAllLink()
    {
        list($url, $query) = explode('?', $_SERVER['REQUEST_URI'], 2);
        parse_str($query, $args);
        unset($args['q'], $args['page'], $args['from-xhr']);

        return $args ? "$url?" . http_build_query($args) : $url;
    }

    public static function getFacets()
    {
        static $facets;

        if (null !== $facets) {
            return $facets;
        }
        $context = \Context::getContext();
        $result = $context->smarty->tpl_vars['listing']->value['result'];

        return $facets = (
            $facetCollection = $result->getFacetCollection()
        ) ? \Closure::bind(function () use ($facetCollection) {
            $facets = [];

            foreach ($facetCollection->getFacets() as $facet) {
                $fa = $facet->toArray();

                if ('feature' === $fa['type']) {
                    // Fix: Merge custom features
                    $filters = [];

                    foreach ($fa['filters'] as &$filter) {
                        $label = $filter['label'];
                        isset($filters[$label]) ? ++$filters[$label]['magnitude'] : $filters[$label] = &$filter;
                    }
                    $fa['filters'] = array_values($filters);
                }
                foreach ($fa['filters'] as &$filter) {
                    $filter['nextEncodedFacetsURL'] = ${'this'}->updateQueryString($filter['nextEncodedFacets']
                        ? ['q' => $filter['nextEncodedFacets'], 'page' => null]
                        : ['q' => null]
                    );
                }
                $facets[] = $fa;
            }

            return $facets;
        }, $context->controller, 'FrontController')->__invoke() : [];
    }

    public static function isAdminImport()
    {
        return isset(${'_POST'}['submitOptionsce_template']);
    }

    public static function minifyHtml($content)
    {
        $content = preg_replace('/>\s+</', '><', trim($content));

        return preg_replace_callback('/="{&quot;[^"]+"/', function ($match) {
            $json = str_replace('&quot;', '"', $match[0]);
            $json[strlen($json) - 1] = $json[1] = "'";

            return $json;
        }, $content);
    }
}

function callback_hash(callable $callback)
{
    if (is_array($callback)) {
        if (is_string($callback[0])) {
            return strtolower(implode('::', $callback));
        }
        if (is_object($callback[0])) {
            return spl_object_hash($callback[0]) . '->' . strtolower($callback[1]);
        }
    }
    if (is_object($callback)) {
        return spl_object_hash($callback);
    }

    return strtolower($callback);
}

function add_action($tag, $callback, $priority = 10)
{
    $p = (int) $priority;

    isset(Helper::$actions[$tag]) || Helper::$actions[$tag] = [10 => []];
    isset(Helper::$actions[$tag][$p]) || Helper::$actions[$tag][$p] = [];

    if (is_string($callback) && '\\' !== $callback[0]) {
        $callback = '\\' . __NAMESPACE__ . '\\' . $callback;
    }
    Helper::$actions[$tag][$p][callback_hash($callback)] = $callback;
}

function remove_action($tag, $callback, $priority = 10)
{
    if (is_string($callback) && '\\' !== $callback[0]) {
        $callback = '\\' . __NAMESPACE__ . '\\' . $callback;
    }
    unset(Helper::$actions[$tag][(int) $priority][callback_hash($callback)]);

    return true;
}

function do_action($tag, ...$args)
{
    isset(Helper::$actions_fired[$tag]) || Helper::$actions_fired[$tag] = 0;
    ++Helper::$actions_fired[$tag];

    if (isset(Helper::$actions[$tag])) {
        $actions = &Helper::$actions[$tag];

        $priorities = array_keys($actions);
        sort($priorities, SORT_NUMERIC);

        foreach ($priorities as $p) {
            foreach ($actions[$p] as $callback) {
                $callback(...$args);
            }
        }
    }
}

function did_action($tag)
{
    return isset(Helper::$actions_fired[$tag]) ? Helper::$actions_fired[$tag] : 0;
}

function add_filter($tag, $callback, $priority = 10)
{
    $p = (int) $priority;

    isset(Helper::$filters[$tag]) || Helper::$filters[$tag] = [10 => []];
    isset(Helper::$filters[$tag][$p]) || Helper::$filters[$tag][$p] = [];

    if (is_string($callback) && '\\' !== $callback[0]) {
        $callback = '\\' . __NAMESPACE__ . '\\' . $callback;
    }
    Helper::$filters[$tag][$p][callback_hash($callback)] = $callback;
}

function has_filter($tag, $function_to_check = false)
{
    if ($function_to_check) {
        throw new \RuntimeException('TODO');
    }

    return isset(Helper::$filters[$tag]);
}

function remove_filter($tag, $callback, $priority = 10)
{
    if (is_string($callback) && '\\' !== $callback[0]) {
        $callback = '\\' . __NAMESPACE__ . '\\' . $callback;
    }
    unset(Helper::$filters[$tag][(int) $priority][callback_hash($callback)]);

    return true;
}

// function current_filter()
// {
//     return end(Helper::$current_filter);
// }

function apply_filters($tag, $value, ...$args)
{
    if (isset(Helper::$filters[$tag])) {
        // Helper::$current_filter[] = $tag;
        $filters = &Helper::$filters[$tag];

        $priorities = array_keys($filters);
        sort($priorities, SORT_NUMERIC);

        foreach ($priorities as $p) {
            foreach ($filters[$p] as $callback) {
                $value = $callback($value, ...$args);
            }
        }
        // array_pop(Helper::$current_filter);
    }

    return $value;
}

function remove_all_filters($tag, $priority = false)
{
    if ($priority) {
        unset(Helper::$filters[$tag][$priority]);
    } else {
        unset(Helper::$filters[$tag]);
    }

    return true;
}

function __return_true()
{
    return true;
}

function wp_add_inline_style($handle, $data)
{
    isset(Helper::$inline_styles[$handle]) || Helper::$inline_styles[$handle] = [];

    Helper::$inline_styles[$handle][] = $data;

    return true;
}

function wp_register_style($handle, $src, array $deps = [], $ver = false, $media = 'all')
{
    if (!isset(Helper::$styles[$handle])) {
        Helper::$styles[$handle] = [
            'hndl' => $handle,
            'src' => $src,
            'deps' => $deps,
            'ver' => $ver,
            'media' => $media,
        ];
    }

    return true;
}

function wp_register_script($handle, $src, array $deps = [], $ver = false, $in_footer = false)
{
    if (!isset(Helper::$scripts[$handle])) {
        Helper::$scripts[$handle] = [
            'hndl' => $handle,
            'src' => $src,
            'deps' => $deps,
            'ver' => $ver,
            'head' => !$in_footer,
            'l10n' => [],
        ];
    }

    return true;
}

if (is_admin() || \Tools::getValue('render') === 'widget') {
    function wp_enqueue_style($handle, $src = '', array $deps = [], $ver = false, $media = 'all')
    {
        $src && wp_register_style($handle, $src, $deps, $ver, $media);

        if (!empty(Helper::$styles[$handle]) && empty(Helper::$head_styles[$handle])) {
            foreach (Helper::$styles[$handle]['deps'] as $dep) {
                wp_enqueue_style($dep);
            }

            Helper::$head_styles[$handle] = &Helper::$styles[$handle];
            unset(Helper::$styles[$handle]);
        }
    }

    function wp_enqueue_script($handle, $src = '', array $deps = [], $ver = false, $in_footer = false)
    {
        $src && wp_register_script($handle, $src, $deps, $ver, $in_footer);

        if (!empty(Helper::$scripts[$handle]) && empty(Helper::$head_scripts[$handle]) && empty(Helper::$body_scripts[$handle])) {
            foreach (Helper::$scripts[$handle]['deps'] as $dep) {
                wp_enqueue_script($dep);
            }

            if (Helper::$scripts[$handle]['head']) {
                Helper::$head_scripts[$handle] = &Helper::$scripts[$handle];
            } else {
                Helper::$body_scripts[$handle] = &Helper::$scripts[$handle];
            }
            unset(Helper::$scripts[$handle]);
        }
    }
} else {
    function wp_enqueue_style($handle, $src = '', array $deps = [], $ver = false, $media = 'all')
    {
        $src && wp_register_style($handle, $src, $deps, $ver, $media);

        if (!empty(Helper::$styles[$handle])) {
            $args = &Helper::$styles[$handle];
            $path = $args['src'];
            $params = [
                'server' => strpos($path, '://') === false ? 'local' : 'remote',
                'media' => $args['media'],
                'priority' => (int) \Configuration::get('elementor_assets_priority', null, null, null, 50),
            ];

            foreach ($args['deps'] as $dep) {
                wp_enqueue_style($dep);
            }
            empty($args['ver']) || $path .= (strpos($path, '?') === false ? '?v=' : '&v=') . $args['ver'];

            \CEAssetManager::instance()->registerStylesheet($handle, $path, $params);

            unset(Helper::$styles[$handle]);
        }
    }
    function wp_enqueue_script($handle, $src = '', array $deps = [], $ver = false, $in_footer = false)
    {
        $src && wp_register_script($handle, $src, $deps, $ver, $in_footer);

        if (!empty(Helper::$scripts[$handle])) {
            $args = &Helper::$scripts[$handle];
            $path = $args['src'];
            $params = [
                'server' => strpos($path, '://') === false ? 'local' : 'remote',
                'position' => $args['head'] ? 'head' : 'bottom',
                'priority' => (int) \Configuration::get('elementor_assets_priority', null, null, null, 50),
            ];

            foreach ($args['deps'] as $dep) {
                wp_enqueue_script($dep);
            }
            empty($args['ver']) || $path .= (strpos($path, '?') === false ? '?v=' : '&v=') . $args['ver'];

            \CEAssetManager::instance()->registerJavascript($handle, $path, $params);

            empty($args['l10n']) || \Media::addJsDef($args['l10n']);

            unset(Helper::$scripts[$handle]);
        }
    }
}

function wp_localize_script($handle, $object_name, $l10n)
{
    if (isset(Helper::$scripts[$handle])) {
        Helper::$scripts[$handle]['l10n'][$object_name] = $l10n;
    } else {
        throw new \PrestaShopException('Missing script handle: ' . $handle);
    }
}

function wp_enqueue_scripts()
{
    if (is_admin()) {
        wp_enqueue_script('jquery', _CE_ASSETS_URL_ . 'lib/jquery/jquery.js', [], '1.12.4');
        wp_enqueue_script('jquery-ui', _CE_ASSETS_URL_ . 'lib/jquery/jquery-ui.min.js', ['jquery'], '1.11.4.custom', true);

        wp_enqueue_script('jquery-fancybox', _PS_JS_DIR_ . 'jquery/plugins/fancybox/jquery.fancybox.js', ['jquery'], false, true);
        wp_enqueue_style('jquery-fancybox', _PS_JS_DIR_ . 'jquery/plugins/fancybox/jquery.fancybox.css');

        wp_register_script('underscore', _CE_ASSETS_URL_ . 'lib/underscore/underscore.min.js', [], '1.8.3', true);
        wp_register_script('backbone', _CE_ASSETS_URL_ . 'lib/backbone/backbone.min.js', ['jquery', 'underscore'], '1.4.0', true);
    }
    do_action('wp_enqueue_scripts');
}

function wp_print_styles()
{
    while ($args = array_shift(Helper::$head_styles)) {
        $id = $args['hndl'];

        empty($args['ver']) || $args['src'] .= (strpos($args['src'], '?') === false ? '?v=' : '&v=') . $args['ver'];

        echo '<link rel="stylesheet" id="' . $id . '-css" href="' . $args['src'] . '" media="' . $args['media'] . '">' .
            PHP_EOL;

        if (!empty(Helper::$inline_styles[$id])) {
            echo '<style id="' . $id . '-inline-css">' . implode("\n", Helper::$inline_styles[$id]) . "</style>\n";
        }
    }
}

function wp_print_head_scripts()
{
    while ($args = array_shift(Helper::$head_scripts)) {
        _print_script($args);
    }
}

function wp_print_footer_scripts()
{
    while ($args = array_shift(Helper::$body_scripts)) {
        _print_script($args);
    }
}

function _print_script(&$args)
{
    if (!empty($args['l10n'])) {
        echo '<script>' . PHP_EOL;

        foreach ($args['l10n'] as $key => &$value) {
            // Fix: Allow escaped slashes for embed code
            $json = json_encode($value, JSON_UNESCAPED_UNICODE);
            // Fix: Line too long
            echo "var $key = " . str_replace('}},"', "}},\n\"", $json) . ";\n";
        }
        echo '</script>' . PHP_EOL;
    }
    empty($args['ver']) || $args['src'] .= (strpos($args['src'], '?') === false ? '?v=' : '&v=') . $args['ver'];

    empty($args['src']) || print '<script src="' . $args['src'] . '" id="' . $args['hndl'] . '-js"></script>' . PHP_EOL;
}

function print_og_image()
{
    if ($og_image = get_post_meta(get_the_ID(), '_og_image', true)) {
        $og_image_url = Helper::getMediaLink($og_image, true);

        echo '<meta property="og:image" content="' . esc_attr($og_image_url) . '">' . PHP_EOL;
    }
}

function set_transient($transient, $value, $expiration = 0)
{
    $expiration = (int) $expiration;
    $tr_timeout = '_tr_to_' . $transient;
    $tr_option = '_tr_' . $transient;
    $id_shop = \Context::getContext()->shop->id;

    if (false === get_post_meta($id_shop, $tr_option, true)) {
        if ($expiration) {
            update_post_meta($id_shop, $tr_timeout, time() + $expiration);
        }
        $result = update_post_meta($id_shop, $tr_option, $value);
    } else {
        $update = true;
        if ($expiration) {
            if (false === get_post_meta($id_shop, $tr_timeout, true)) {
                update_post_meta($id_shop, $tr_timeout, time() + $expiration);
                $result = update_post_meta($id_shop, $tr_option, $value);
                $update = false;
            } else {
                update_post_meta($id_shop, $tr_timeout, time() + $expiration);
            }
        }
        if ($update) {
            $result = update_post_meta($id_shop, $tr_option, $value);
        }
    }

    return $result;
}

function get_transient($transient)
{
    $tr_option = '_tr_' . $transient;
    $tr_timeout = '_tr_to_' . $transient;
    $id_shop = \Context::getContext()->shop->id;
    $timeout = get_post_meta($id_shop, $tr_timeout, true);

    if (false !== $timeout && $timeout < time()) {
        delete_option($tr_option);
        delete_option($tr_timeout);

        return false;
    }

    return get_post_meta($id_shop, $tr_option, true);
}

function __($text, $module = 'creativeelements')
{
    return translate($text, $module);
}

function _e($text, $module = 'creativeelements')
{
    echo translate($text, $module);
}

function _x($text, $ctx, $module = 'creativeelements')
{
    return translate($text, $module, $ctx);
}

function _n($single, $plural, $number, $module = 'creativeelements')
{
    return translate($number > 1 ? $plural : $single, $module);
}

$context = \Context::getContext();

if (isset(${'_GET'}['en']) || 'en' === $locale = substr($context->language->locale ?: $context->language->iso_code, 0, 2)) {
    define('_CE_LOCALE_', 'en-EN');

    function translate($text, $module = 'creativeelements', $ctx = '')
    {
        return $text;
    }

    function esc_attr_e($text, $module = 'creativeelements')
    {
        echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
} else {
    define('_CE_LOCALE_', "$locale-" . strtoupper($locale));

    function translate($text, $module = 'creativeelements', $ctx = '')
    {
        $src = $ctx ? str_replace(' ', '_', strtolower($ctx)) : '';

        return stripslashes(\Translate::getModuleTranslation($module, $text, $src, null, true, _CE_LOCALE_));
    }

    function esc_attr_e($text, $module = 'creativeelements')
    {
        echo \Translate::getModuleTranslation($module, $text, '', null, false, _CE_LOCALE_);
    }
}

if ($context->controller instanceof \AdminCEEditorController) {
    $uid = get_the_ID();

    if (!empty($uid->id_lang) && $uid->id_lang != $context->language->id) {
        $context->language = new \Language($uid->id_lang);
    }
}

function esc_attr($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function esc_html($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function esc_url($url)
{
    if (!$url) {
        return $url;
    }
    $url = str_replace(' ', '%20', $url);
    $url = preg_replace('`[^-\w~+.?#=!&;,/:%@$|*\'()\[\]\\x80-\\xff]`', '', $url);
    if ('' === $url) {
        return $url;
    }
    $url = str_replace(';//', '://', $url);
    if (strpos($url, ':') === false && !in_array($url[0], ['/', '#', '?']) && !preg_match('/^[a-z0-9-]+?\.php/i', $url)) {
        $url = 'http://' . $url;
    }

    return $url;
}

function sanitize_html_class($class, $fallback = '')
{
    return ($class = preg_replace('/%[a-f\d]{2}|[^\w\-]/i', '', $class)) ? $class : $fallback;
}

function home_url()
{
    return __PS_BASE_URI__;
}

function wp_send_json($response, $status_code = null)
{
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');

        null !== $status_code && http_response_code($status_code);
    }
    exit(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
}

function wp_send_json_success($data = null, $status_code = null)
{
    $response = ['success' => true];
    if (isset($data)) {
        $response['data'] = $data;
    }
    wp_send_json($response, $status_code);
}

function wp_send_json_error($data = null, $status_code = null)
{
    $response = ['success' => false];
    if (isset($data)) {
        $response['data'] = $data;
    }
    wp_send_json($response, $status_code);
}

function get_locale()
{
    return \Context::getContext()->language->iso_code;
}

function is_rtl()
{
    return (bool) \Context::getContext()->language->is_rtl;
}

function is_admin()
{
    // fix: _PS_ADMIN_DIR_ is defined on frontend by cronjobs module
    return defined('_PS_BO_ALL_THEMES_DIR_');
}

function is_singular()
{
    return UId::$_ID && UId::$_ID->id_type !== UId::CONTENT;
}

function wp_doing_ajax()
{
    return isset(${'_GET'}['ajax']) || isset(${'_POST'}['ajax']);
}

function wp_referer()
{
    $protocol = \Tools::getShopProtocol();
    $id_shop = \Configuration::getGlobalValue('PS_SHOP_DEFAULT');

    return $protocol . ('http://' === $protocol ? \ShopUrl::getMainShopDomain($id_shop) : \ShopUrl::getMainShopDomainSSL($id_shop));
}

function wp_http()
{
    $context = \Context::getContext();
    $module_key = $context->controller->module->module_key;

    return [
        'user_agent' => $_SERVER['SERVER_SOFTWARE'] .
            ' PrestaShop/' . _PS_VERSION_ . ' CreativeElements/' . _CE_VERSION_,
        'max_redirects' => 5,
        'header' => [
            'Cache-Control: no-cache',
            'Cookie: ' . call_user_func('md5', substr(_COOKIE_KEY_, 0, 24) . $module_key) .
                '=' . hash('sha256', _COOKIE_IV_ . date('Y-m-d')),
            'Pragma: no-cache',
            'Referer: ' . wp_referer(),
        ],
    ];
}

function is_preview()
{
    return (bool) \CreativeElements::getPreviewUId(false);
}

function is_customize_preview()
{
    return \Context::getContext()->controller instanceof \CreativeElementsPreviewModuleFrontController;
}

function get_option($option, $default = false)
{
    if (false === $res = \Configuration::get($option)) {
        return $default;
    }

    return isset($res[0]) && ('{' === $res[0] || '[' === $res[0]) ? json_decode($res, true) : $res;
}

function update_option($option, $value)
{
    if (is_array($value) || is_object($value)) {
        $value = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    $purify = \Configuration::get('PS_USE_HTMLPURIFIER');
    $purify && \Configuration::set('PS_USE_HTMLPURIFIER', 0);

    $res = \Configuration::updateValue($option, [$value], true);

    if (\Shop::CONTEXT_SHOP !== $shop_ctx = \Shop::getContext()) {
        $groups = \Shop::CONTEXT_ALL === $shop_ctx ? new \stdClass() : false;

        foreach (\Shop::getContextListShopID() as $id_shop) {
            $id_shop_group = \Shop::getGroupFromShop($id_shop);

            if ($groups && empty($groups->$id_shop_group)) {
                $groups->$id_shop_group = true;

                $res &= \Configuration::updateValue($option, [$value], true, $id_shop_group);
            }
            $res &= \Configuration::updateValue($option, [$value], true, $id_shop_group, $id_shop);
        }
    }
    $purify && \Configuration::set('PS_USE_HTMLPURIFIER', 1);

    return $res;
}

function delete_option($option)
{
    return \Configuration::deleteByName($option);
}

function get_current_user_id()
{
    static $id_employee;

    if (null === $id_employee) {
        if (is_admin()) {
            $ctx = \Context::getContext();
            $id_employee = isset($ctx->employee->id) ? (int) $ctx->employee->id : 0;
        } else {
            $id_employee = \CreativeElements::getPreviewUId(false) ? (int) $_REQUEST['id_employee'] : 0;
        }
    }

    return $id_employee;
}

function get_user_by($field, $value)
{
    if ('id' !== $field) {
        throw new \RuntimeException('TODO');
    }
    if (!\Validate::isLoadedObject($user = new \Employee($value))) {
        return false;
    }

    return (object) [
        'ID' => $user->id,
        'display_name' => $user->firstname . ' ' . $user->lastname,
        'roles' => [],
    ];
}

function wp_upload_dir()
{
    return [
        'basedir' => _CE_PATH_ . 'views',
        'baseurl' => _CE_URL_ . 'views',
    ];
}

function wp_remote_post($url, array $args = [])
{
    $http = array_merge_recursive(wp_http(), [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/x-www-form-urlencoded',
        ],
        'content' => empty($args['body']) ? '' : http_build_query($args['body']),
        'timeout' => empty($args['timeout']) ? 5 : $args['timeout'],
    ]);

    if (function_exists('curl_version')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => (array) $http['header'],
            CURLOPT_USERAGENT => $http['user_agent'],
            CURLOPT_POSTFIELDS => $http['content'],
            CURLOPT_MAXREDIRS => $http['max_redirects'],
            CURLOPT_TIMEOUT => $http['timeout'],
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);

        if (false !== $resp) {
            return $resp;
        }
    }

    $http['header'] = implode("\r\n", $http['header']);

    return in_array(ini_get('allow_url_fopen'), ['On', 'on', '1'])
        ? @call_user_func('file_get_contents', $url, false, stream_context_create(['http' => $http]))
        : false;
}

function wp_remote_get($url, array $args = [])
{
    $http = array_merge(wp_http(), [
        'method' => 'GET',
        'timeout' => empty($args['timeout']) ? 5 : $args['timeout'],
    ]);

    if (!empty($args['body'])) {
        $url = add_query_arg($args['body'], $url);
    }

    if (function_exists('curl_version')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => (array) $http['header'],
            CURLOPT_USERAGENT => $http['user_agent'],
            CURLOPT_MAXREDIRS => $http['max_redirects'],
            CURLOPT_TIMEOUT => $http['timeout'],
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);

        if (false !== $resp) {
            return $resp;
        }
    }

    $http['header'] = implode("\r\n", $http['header']);

    return in_array(ini_get('allow_url_fopen'), ['On', 'on', '1'])
        ? @call_user_func('file_get_contents', $url, false, stream_context_create(['http' => $http]))
        : false;
}

const MINUTE_IN_SECONDS = 60;
const HOUR_IN_SECONDS = 3600;
const DAY_IN_SECONDS = 86400;
const WEEK_IN_SECONDS = 604800;
const MONTH_IN_SECONDS = 2592000;
const YEAR_IN_SECONDS = 31536000;

function human_time_diff($from, $to = '')
{
    empty($to) && $to = time();
    $diff = (int) abs($to - $from);

    if ($diff < MINUTE_IN_SECONDS) {
        $secs = $diff;
        if ($secs <= 1) {
            $secs = 1;
        }
        $since = sprintf(_n('%s sec', '%s secs', $secs), $secs);
    } elseif ($diff < HOUR_IN_SECONDS) {
        $mins = round($diff / MINUTE_IN_SECONDS);
        if ($mins <= 1) {
            $mins = 1;
        }
        $since = sprintf(_n('%s min', '%s mins', $mins), $mins);
    } elseif ($diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS) {
        $hours = round($diff / HOUR_IN_SECONDS);
        if ($hours <= 1) {
            $hours = 1;
        }
        $since = sprintf(_n('%s hour', '%s hours', $hours), $hours);
    } elseif ($diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS) {
        $days = round($diff / DAY_IN_SECONDS);
        if ($days <= 1) {
            $days = 1;
        }
        $since = sprintf(_n('%s day', '%s days', $days), $days);
    } elseif ($diff < MONTH_IN_SECONDS && $diff >= WEEK_IN_SECONDS) {
        $weeks = round($diff / WEEK_IN_SECONDS);
        if ($weeks <= 1) {
            $weeks = 1;
        }
        $since = sprintf(_n('%s week', '%s weeks', $weeks), $weeks);
    } elseif ($diff < YEAR_IN_SECONDS && $diff >= MONTH_IN_SECONDS) {
        $months = round($diff / MONTH_IN_SECONDS);
        if ($months <= 1) {
            $months = 1;
        }
        $since = sprintf(_n('%s month', '%s months', $months), $months);
    } elseif ($diff >= YEAR_IN_SECONDS) {
        $years = round($diff / YEAR_IN_SECONDS);
        if ($years <= 1) {
            $years = 1;
        }
        $since = sprintf(_n('%s year', '%s years', $years), $years);
    }

    return $since;
}

function add_query_arg(array $args, $url)
{
    $args && $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($args);

    return $url;
}

function do_shortcode($content)
{
    if (false === strpos($content, '{')) {
        return $content;
    }

    return preg_replace_callback(
        '`(<p>\s*)?\{(\w+|\$\w+(?:\.\w+|->\w+)*)([^}]*)\}(\s*</p>)?`',
        'CE\parse_shortcode',
        $content
    );
}

function parse_shortcode($match)
{
    isset($match[4]) || $match[4] = '';

    if ('$' === $match[2][0]) {
        $result = parse_var($match[2]);
        $modifier = trim($match[3]);

        if ($modifier && $modifier !== 'nofilter' && $modifier !== '|json_encode') {
            $result = "Unknown modifier: $modifier";
        } elseif ($modifier === '|json_encode') {
            $result = esc_attr(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        } elseif (is_array($result) || is_object($result)) {
            ob_start();
            dump($result instanceof AbstractLazyArray ? $result->jsonSerialize() : $result);
            $result = ob_get_clean();
        } elseif (!$modifier) {
            $result = esc_attr((string) $result);
        }

        return ($match[1] xor $match[4]) ? $match[1] . $result . $match[4] : $result;
    }
    if (!function_exists($func = 'smarty' . $match[2])
        || !preg_match_all('`\s+(\w+)\s*=\s*(\$?\w+|".*?"|\'.*?\'|\[.*?\])`', $match[3], $args, PREG_SET_ORDER)
    ) {
        return $match[0];
    }
    $params = [];
    $smarty = null;

    foreach ($args as $arg) {
        if ('[' === $arg[2][0]) {
            $array = [];
            $count = preg_match_all(
                '`\s*,\s*(?:(\w+|".*?"|\'.*?\')\s*=>\s*)?(\w+|".*?"|\'.*?\')`',
                ',' . trim($arg[2], '[]'),
                $elems,
                PREG_SET_ORDER
            );
            if ($count) {
                foreach ($elems as $elem) {
                    $val = parse_native($elem[2]);

                    if ($elem[1]) {
                        $key = parse_native($elem[1]);

                        $array[$key] = $val;
                    } else {
                        $array[] = $val;
                    }
                }
            }
            $params[$arg[1]] = $array;
        } else {
            $params[$arg[1]] = parse_native($arg[2]);
        }
    }
    $ajax = \Tools::getIsset('ajax');
    $edit = Plugin::$instance->editor->isEditMode();

    if ($edit && !$ajax) {
        ${'_GET'}['ajax'] = 1;
    }

    try {
        $result = $func($params, $smarty);
    } catch (\Exception $ex) {
        $result = _PS_MODE_DEV_ ? $ex->getMessage() : '';
    }

    if ($edit && !$ajax) {
        unset(${'_GET'}['ajax']);
    }

    return ($match[1] xor $match[4]) ? $match[1] . $result . $match[4] : $result;
}

function parse_native($native)
{
    if ('$' === $native[0]) {
        return parse_var($native);
    }
    if ("'" === $native[0]) {
        return str_replace('\\\\', '\\', trim($native, "'"));
    }
    $result = json_decode($native);

    return null !== $result || 'null' === $native ? $result : $native;
}

function parse_var($var)
{
    static $smarty;

    preg_match_all('/(^\$|\.|->)(\w+)/', $var, $matches);

    $vars = &$GLOBALS['smarty']->tpl_vars;
    $count = count($matches[0]);
    $prefixes = &$matches[1];
    $keys = &$matches[2];
    $key = $count ? $keys[0] : '';

    if ('smarty' === $key) {
        null === $smarty && $smarty = [
            'get' => &${'_GET'},
            'post' => &${'_POST'},
            'cookie' => &${'_COOKIE'},
            'request' => &$_REQUEST,
            'server' => &$_SERVER,
            'now' => time(),
        ];
        $result = &$smarty;
    } elseif (isset($vars[$key])) {
        $result = $vars[$key]->value;
    } else {
        return null;
    }

    for ($i = 1; $i < $count; ++$i) {
        $key = $keys[$i];
        $prefix = $prefixes[$i];

        if ('.' === $prefix && (is_array($result) || $result instanceof AbstractLazyArray) && isset($result[$key])) {
            $result = $result[$key];
        } elseif ('->' === $prefix && isset($result->$key)) {
            $result = $result->$key;
        } else {
            return null;
        }
    }

    return $result;
}

function wp_nonce_tick()
{
    return ceil(time() / (DAY_IN_SECONDS / 2));
}

function wp_create_nonce($action = -1)
{
    $employee = \Context::getContext()->employee;
    $id = isset($employee->id) ? (int) $employee->id : 0;
    $tick = wp_nonce_tick();
    $salt = \Tools::hashIV('nonce');

    return substr(call_user_func('hash_hmac', 'md5', "$tick|$action|$id|" . _COOKIE_KEY_, $salt), -12, 10);
}

function wp_verify_nonce($nonce, $action = -1)
{
    return wp_create_nonce($action) === $nonce;
}

function wp_kses_post($html)
{
    static $purifier;

    if (null === $purifier) {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Attr.EnableID', true);
        $config->set('Attr.AllowedRel', ['nofollow']);
        $config->set('HTML.Trusted', true);
        $config->set('Cache.SerializerPath', _PS_CACHE_DIR_ . 'purifier');
        $config->set('Attr.AllowedFrameTargets', ['_blank', '_self', '_parent', '_top']);

        // http://developers.whatwg.org/the-video-element.html#the-video-element
        if ($def = $config->getHTMLDefinition(true)) {
            $def->addElement('video', 'Block', 'Optional: (source, Flow) | (Flow, source) | Flow', 'Common', [
                'src' => 'URI',
                'type' => 'Text',
                'width' => 'Length',
                'height' => 'Length',
                'poster' => 'URI',
                'preload' => 'Enum#auto,metadata,none',
                'controls' => 'Bool',
            ]);
            $def->addElement('source', 'Block', 'Flow', 'Common', [
                'src' => 'URI',
                'type' => 'Text',
            ]);
            $def->addElement('style', 'Block', 'Flow', 'Common', ['type' => 'Text']);
        }
        $purifier = new \HTMLPurifier($config);
    }

    return $purifier->purify($html);
}
