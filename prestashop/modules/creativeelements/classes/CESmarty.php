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

class CESmarty
{
    protected static $tpls = [];

    protected static function getTemplate($path)
    {
        if (isset(self::$tpls[$path])) {
            return self::$tpls[$path];
        }

        $tpl = $GLOBALS['smarty']->createTemplate($path);
        CE\do_action('smarty/before_fetch', $tpl->smarty);
        $tpl->fetch();
        CE\do_action('smarty/after_fetch', $tpl->smarty);

        return self::$tpls[$path] = $tpl;
    }

    public static function call($path, $func, array $params = [], $nocache = true)
    {
        $tpl = self::getTemplate($path);
        CE\do_action('smarty/before_call', $tpl->smarty);
        $tpl->smarty->ext->_tplFunction->callTemplateFunction($tpl, $func, $params, $nocache);
        CE\do_action('smarty/after_call', $tpl->smarty);
    }

    public static function capture($path, $func, array $params = [], $nocache = true)
    {
        ob_start();

        self::call($path, $func, $params, $nocache);

        return ob_get_clean();
    }

    public static function get($path, $capture)
    {
        $tpl = self::getTemplate($path);

        return $tpl->smarty->ext->_capture->getBuffer($tpl, $capture);
    }

    public static function write($path, $capture)
    {
        $tpl = self::getTemplate($path);

        echo $tpl->smarty->ext->_capture->getBuffer($tpl, $capture);
    }

    public static function printf($path, $capture, ...$args)
    {
        printf(self::get($path, $capture), ...$args);
    }

    public static function sprintf($path, $capture, ...$args)
    {
        return sprintf(self::get($path, $capture), ...$args);
    }
}

function smartyInclude(array $params)
{
    if (empty($params['file'])) {
        return;
    }

    $file = $params['file'];

    try {
        if (strrpos($file, '../') !== false || strcasecmp(substr($file, -4), '.tpl') !== 0) {
            throw new Exception();
        }

        if (strpos($file, 'module:') === 0) {
            $file = substr($file, 7);

            if (!file_exists($path = _PS_THEME_DIR_ . "modules/$file")
                && (!_PARENT_THEME_NAME_
                    || !file_exists($path = _PS_PARENT_THEME_DIR_ . "modules/$file"))
                && !file_exists($path = _PS_MODULE_DIR_ . $file)
            ) {
                throw new Exception();
            }
        } elseif (_PARENT_THEME_NAME_ && strpos($file, 'parent:') === 0) {
            $file = substr($file, 7);

            if (!file_exists($path = _PS_PARENT_THEME_DIR_ . "templates/$file")) {
                throw new Exception();
            }
        } elseif (!file_exists($path = _PS_THEME_DIR_ . "templates/$file")
            && (!_PARENT_THEME_NAME_ || !file_exists($path = _PS_PARENT_THEME_DIR_ . "templates/$file"))
        ) {
            throw new Exception();
        }

        $cache_id = isset($params['cache_id']) ? $params['cache_id'] : null;
        $compile_id = isset($params['compile_id']) ? $params['compile_id'] : null;
        unset($params['file'], $params['cache_id'], $params['compile_id']);

        $out = $GLOBALS['smarty']->fetch($path, $cache_id, $compile_id, $params);
    } catch (Exception $ex) {
        $out = $ex->getMessage() ?: "Failed including: '$file'";
    }

    return $out;
}

if (!function_exists('smartyWidget')) {
    function smartyWidget(array $params)
    {
        if (!isset($params['name'])) {
            throw new Exception('Smarty helper `render_widget` expects at least the `name` parameter.');
        }
        $module = Module::getInstanceByName($params['name']);

        if (!$module instanceof PrestaShop\PrestaShop\Core\Module\WidgetInterface) {
            throw new Exception(sprintf('Module `%1$s` is not a WidgetInterface.', $params['name']));
        }
        unset($params['name']);

        return Hook::coreRenderWidget($module, isset($params['hook']) ? $params['hook'] : null, $params);
    }
}

function smartyL(array $params)
{
    return empty($params['d']) ? $params['s'] : smartyTranslate($params, null);
}

function smartyCE(array $params)
{
    if (empty($params['element'])) {
        return;
    }

    $data = json_decode(base64_decode($params['element']), true);

    if (!$data || !is_array($data)) {
        return;
    }

    ob_start();

    if ($element = CE\Plugin::$instance->elements_manager->createElementInstance($data)) {
        $element->printElement();
    }

    return ob_get_clean();
}

function ce__($text, $module = 'creativeelements')
{
    return CE\translate($text, $module);
}

function ce_new($class, ...$args)
{
    return new $class(...$args);
}

function ce_enqueue_miniature($uid)
{
    static $enqueued = [];

    if (isset($enqueued[$uid])) {
        return;
    }
    $enqueued[$uid] = true;

    if (CreativeElements::getPreviewUId()
        || CreativeElements::hasAdminToken('AdminCEThemes') && Tools::getValue('id_miniature') === substr($uid, 0, -6)
    ) {
        $preview_id = ($autosave = CE\wp_get_post_autosave($uid, (int) Tools::getValue('id_employee'))) ? $autosave->ID : $uid;
    } else {
        $preview_id = false;
    }
    CE\Plugin::instance()->frontend->hasElementorInPage(true);

    (new CE\ModulesXCatalogXFilesXCSSXMiniature($uid, $preview_id))
        ->{Tools::getValue('render') === 'widget' ? 'printCss' : 'enqueue'}();
}

function array_export($array)
{
    echo preg_replace(['/\barray\s*\(/i', '/,\r?(\n\s*)\)/'], ['[', '$1]'], var_export($array, true));
}

function _q_c_($if, $then, $else)
{
    return $if ? $then : $else;
}
