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

class WPPost
{
    private $id;
    private $uid;
    private $model;

    public $_obj;

    public $post_author = 0;
    public $post_parent = 0;
    public $post_date = '';
    public $post_modified = '';
    public $post_title = '';
    public $post_excerpt = '';
    public $post_content = '';
    public $template_type = 'post';

    private function __construct()
    {
    }

    public function __get($prop)
    {
        switch ($prop) {
            case 'uid':
                $val = $this->uid;
                break;
            case 'ID':
            case 'post_ID':
                $val = $this->id;
                break;
            case 'post_type':
                $val = $this->model;
                break;
            case 'post_status':
                $active = property_exists($this->_obj, 'active') ? 'active' : (
                    property_exists($this->_obj, 'enabled') ? 'enabled' : 'actif'
                );
                $val = $this->_obj->$active ? 'publish' : 'private';
                break;
            default:
                throw new \RuntimeException('Unknown property: ' . $prop);
        }

        return $val;
    }

    public function __set($prop, $val)
    {
        switch ($prop) {
            case 'ID':
            case 'post_ID':
                // allow change only when starts with zero
                empty($this->id[0]) && $this->id = "$val";
                break;
            case 'post_type':
                // readonly
                break;
            case 'post_status':
                $active = property_exists($this->_obj, 'active') ? 'active' : 'actif';
                $this->_obj->$active = 'publish' === $val;
                break;
            default:
                throw new \RuntimeException('Unknown property: ' . $prop);
        }
    }

    public function getLangId()
    {
        return (int) substr($this->id, -4, 2);
    }

    public static function getInstance(UId $uid, array &$postarr = [])
    {
        $self = new self();
        $self->id = "$uid";
        $self->uid = $uid;
        $self->model = $uid->getModel();
        $objectModel = '\\' . $self->model;

        if ($postarr) {
            $obj = (object) $postarr;
        } elseif ($uid->id_type <= UId::TEMPLATE) {
            $obj = new $objectModel($uid->id ? $uid->id : null);
        } elseif ($uid->id_type === UId::PRODUCT) {
            $obj = new \Product($uid->id, false, $uid->id_lang, $uid->id_shop);
        } else {
            $obj = new $objectModel($uid->id, $uid->id_lang, $uid->id_shop);
        }
        $self->_obj = $obj;

        if (in_array($uid->id_type, [UId::REVISION, UId::TEMPLATE, UId::THEME])) {
            $self->template_type = &$obj->type;
        } elseif ($uid->id_type === UId::CONTENT) {
            $self->template_type = 'content';
        }

        property_exists($obj, 'id_employee') && $self->post_author = &$obj->id_employee;
        property_exists($obj, 'parent') && $self->post_parent = &$obj->parent;
        property_exists($obj, 'date_add') && $self->post_date = &$obj->date_add;
        property_exists($obj, 'date_upd') && $self->post_modified = &$obj->date_upd;
        // property_exists($obj, 'description_short') && $self->post_excerpt = &$obj->description_short;

        if (property_exists($obj, 'title')) {
            $self->post_title = &$obj->title;
        } elseif (property_exists($obj, 'name')) {
            $self->post_title = &$obj->name;
        } elseif (property_exists($obj, 'meta_title')) {
            $self->post_title = &$obj->meta_title;
        }

        if (property_exists($obj, 'content')) {
            $self->post_content = &$obj->content;
        } elseif (property_exists($obj, 'description')) {
            $self->post_content = &$obj->description;
        } elseif (property_exists($obj, 'post_content')) {
            $self->post_content = &$obj->post_content;
        }

        return $self;
    }
}

function get_post($post = null, $output = 'OBJECT', $filter = 'raw')
{
    if (null === $post || 0 === $post) {
        $post = get_the_ID();
    }

    if (false === $post || $post instanceof WPPost) {
        $_post = $post;
    } elseif ($post instanceof UId) {
        $_post = WPPost::getInstance($post);
    } elseif (is_numeric($post)) {
        $_post = WPPost::getInstance(UId::parse($post));
    } else {
        _doing_it_wrong(__CLASS__ . '::' . __FUNCTION__, 'Invalid $post argument!');
    }

    if (!$_post) {
        return null;
    }
    if ('OBJECT' !== $output || 'raw' !== $filter) {
        throw new \RuntimeException('todo');
    }

    return $_post;
}

function get_post_status($post = null)
{
    if ($_post = get_post($post)) {
        return $_post->post_status;
    }

    return false;
}

function wp_update_post($postarr = [], $wp_error = false)
{
    if (is_array($postarr)) {
        $id_key = isset($postarr['ID']) ? 'ID' : 'post_ID';

        if (empty($postarr[$id_key])) {
            _doing_it_wrong(__FUNCTION__, 'ID is missing!');
        }
        $post = get_post($postarr[$id_key]);

        foreach ($postarr as $key => $value) {
            $post->$key = $value;
        }
    } elseif ($postarr instanceof WPPost) {
        $post = $postarr;
    }

    if (!isset($post) || $wp_error) {
        throw new \RuntimeException('TODO');
    }
    // Fix for required lang properties must be defined on default language
    $id_lang_def = \Configuration::get('PS_LANG_DEFAULT');
    \Configuration::set('PS_LANG_DEFAULT', $post->uid->id_lang);

    try {
        // Fix: category groups would lose after save
        if ($post->_obj instanceof \Category) {
            $post->_obj->groupBox = $post->_obj->getGroups();
        }
        $res = @$post->_obj->update();
    } catch (\Exception $ex) {
        $res = false;
    }
    \Configuration::set('PS_LANG_DEFAULT', $id_lang_def);

    return $res ? $post->ID : 0;
}

function wp_insert_post(array $postarr, $wp_error = false)
{
    $is_revision = 'CERevision' === $postarr['post_type'];

    if ($wp_error || !$is_revision && 'CETemplate' !== $postarr['post_type']) {
        throw new \RuntimeException('TODO');
    }
    $uid = new UId(0, $is_revision ? UId::REVISION : UId::TEMPLATE);
    $post = WPPost::getInstance($uid);
    $context = \Context::getContext();
    $postarr['post_author'] = !empty($context->employee->id) ? (int) $context->employee->id : 0;

    foreach ($postarr as $key => &$value) {
        $post->$key = $value;
    }
    if ($post->_obj->add()) {
        $uid->id = $post->_obj->id;
        $post->ID = "$uid";
    } else {
        $post->ID = 0;
    }

    return $post->ID;
}

function wp_delete_post($postid, $force_delete = false)
{
    $post = get_post($postid);

    return $post->_obj->delete() || $force_delete ? $post : false;
}

function get_post_meta($id, $key = '', $single = false)
{
    if (false === $id) {
        return $id;
    }
    $id = ($uid = UId::parse($id)) ? $uid->toDefault() : preg_replace('/\D+/', '', $id);

    if (!is_numeric($id)) {
        _doing_it_wrong(__FUNCTION__, 'Id must be numeric!');
    }
    if (!$key) {
        $res = [];
        $rows = \Db::getInstance()->executeS(
            'SELECT `name`, `value` FROM ' . _DB_PREFIX_ . 'ce_meta WHERE `id` = ' . pSQL($id)
        );
        if ($rows) {
            foreach ($rows as &$row) {
                $key = &$row['name'];
                $val = &$row['value'];

                isset($res[$key]) || $res[$key] = [];
                $res[$key][] = $val && ('{' === $val[0] || '[' === $val[0] || '"' === $val[0]) ? json_decode($val, true) : $val;
            }
        }

        return $res;
    }
    if (!$single) {
        throw new \RuntimeException('TODO');
    }
    $val = \Db::getInstance()->getValue(
        'SELECT `value` FROM ' . _DB_PREFIX_ . 'ce_meta WHERE `id` = ' . pSQL($id) . ' AND `name` = "' . pSQL($key) . '"'
    );

    return $val && ('{' === $val[0] || '[' === $val[0] || '"' === $val[0]) ? json_decode($val, true) : $val;
}

function update_post_meta($id, $key, $value, $prev_value = '')
{
    if ($prev_value) {
        throw new \RuntimeException('TODO');
    }
    $db = \Db::getInstance();
    $res = true;
    $ids = ($uid = UId::parse($id)) ? $uid->getListByShopContext() : (array) $id;
    $data = [
        'name' => $db->escape($key),
        'value' => $db->escape(is_array($value) || is_object($value) ? json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $value, true),
    ];
    foreach ($ids as $id) {
        $data['id'] = $db->escape($id);
        $id_ce_meta = $db->getValue(
            'SELECT `id_ce_meta` FROM ' . _DB_PREFIX_ . 'ce_meta WHERE `id` = ' . pSQL($id) . ' AND `name` = "' . pSQL($key) . '"'
        );
        if ($id_ce_meta) {
            $data['id_ce_meta'] = (int) $id_ce_meta;
            $type = \Db::REPLACE;
        } else {
            unset($data['id_ce_meta']);
            $type = \Db::INSERT;
        }
        $res &= $db->insert('ce_meta', $data, false, true, $type);
    }

    return $res;
}

function delete_post_meta($id, $key, $value = '')
{
    if ($value) {
        throw new \RuntimeException('TODO');
    }
    $ids = ($uid = UId::parse($id)) ? $uid->getListByShopContext() : (array) $id;

    foreach ($ids as &$id) {
        $id = preg_replace('/\D+/', '', $id);
    }
    if (count($ids) > 1) {
        $in = 'IN';
        $ids = '(' . implode(', ', $ids) . ')';
    } else {
        $in = '=';
        $ids = $ids[0];
    }
    $key = preg_replace('/[^\w\%]+/', '', $key);
    $like = strpos($key, '%') === false ? '=' : 'LIKE';

    return \Db::getInstance()->delete('ce_meta', "`id` $in $ids AND `name` $like '$key'");
}

function get_post_type($post = null)
{
    $uid = uidval($post, null);

    return $uid ? $uid->getModel() : false;
}

function get_post_type_object($post_type)
{
    // todo
    if (!$post_type) {
        return;
    }
    switch ($post_type) {
        case 'CERevision':
        case 'CETemplate':
        case 'CETheme':
            $name = __('Template');
            break;
        case 'CEContent':
            $name = __('Content');
            break;
        case 'Product':
        case 'Category':
        case 'Manufacturer':
        case 'Supplier':
            $name = __($post_type);
            break;
        case 'CMS':
            $name = 'CMS ' . __('Page');
            break;
        case 'CMSCategory':
            $name = 'CMS ' . __('Category');
            break;
        default:
            $name = __('Post');
            break;
    }

    return (object) [
        'cap' => (object) [
            'edit_post' => "edit_$post_type",
            'edit_posts' => "edit_$post_type",
            'publish_posts' => "edit_$post_type",
        ],
        'labels' => (object) [
            'singular_name' => $name,
        ],
    ];
}

function current_user_can($capability, $args = null)
{
    if (is_admin()) {
        $employee = \Context::getContext()->employee;
    } elseif ($id_employee = get_current_user_id()) {
        $employee = new \Employee($id_employee);
    }

    if (empty($employee->id_profile)) {
        return false;
    }
    if ('manage_options' === $capability) {
        return true;
    }
    $cap = explode('_', $capability, 2);
    $capability = $cap[0];
    $model = isset($cap[1]) ? $cap[1] : '';

    if (null === $args && $model) {
        $controller = UId::getAdminControllerByModel($model);
    } elseif ($uid = uidval($args, false)) {
        $controller = $uid->getAdminController();
        $model = $uid->getModel();
    } else {
        return false;
    }

    if ('AdminModules' === $controller) {
        $id_module = \Module::getModuleIdByName(UId::getModuleByModel($model));
        $action = 'view' === $capability ?: 'configure';
        $result = \Module::getPermissionStatic($id_module, $action, $employee);
    } else {
        $id_tab = \Tab::getIdFromClassName($controller);
        $access = \Profile::getProfileAccess($employee->id_profile, $id_tab);
        $result = '1' === $access[$capability];
    }

    return $result;
}

function wp_set_post_lock($post_id)
{
    if (!$user_id = get_current_user_id()) {
        return false;
    }
    $now = time();

    update_post_meta($post_id, '_edit_lock', "$now:$user_id");

    return [$now, $user_id];
}

function wp_check_post_lock($post_id)
{
    if (!$lock = get_post_meta($post_id, '_edit_lock', true)) {
        return false;
    }
    list($time, $user) = explode(':', $lock);

    if (empty($user)) {
        return false;
    }
    $time_window = apply_filters('wp_check_post_lock_window', 150);

    if ($time && $time > time() - $time_window && $user != get_current_user_id()) {
        return (int) $user;
    }

    return false;
}

function wp_create_post_autosave(array $post_data)
{
    $post_id = isset($post_data['ID']) ? $post_data['ID'] : $post_data['post_ID'];

    unset($post_data['ID'], $post_data['post_ID']);

    // Autosave already deleted in saveEditor method
    $autosave = wp_get_post_autosave($post_id, get_current_user_id());

    if ($autosave) {
        foreach ($post_data as $key => $value) {
            $autosave->$key = $value;
        }

        return $autosave->_obj->update() ? $autosave->ID : 0;
    }
    $post_data['post_type'] = 'CERevision';
    $post_data['post_status'] = 'private';
    $post_data['post_parent'] = $post_id;

    $autosave_id = wp_insert_post($post_data);

    if ($autosave_id) {
        do_action('_wp_put_post_revision', $autosave_id);

        return $autosave_id;
    }

    return 0;
}

function wp_is_post_autosave($post)
{
    $uid = uidval($post);

    if (UId::REVISION !== $uid->id_type) {
        return false;
    }

    return \Db::getInstance()->getValue(
        'SELECT `parent` FROM ' . _DB_PREFIX_ . 'ce_revision WHERE `id_ce_revision` = ' . (int) $uid->id . ' AND `active` = 0'
    );
}

function wp_get_post_autosave($post_id, $user_id = 0)
{
    $uid = uidval($post_id);

    if (UId::REVISION === $uid->id_type) {
        return false;
    }
    $parent = $uid->toDefault();
    $id_employee = $user_id ?: get_current_user_id();
    $id = \Db::getInstance()->getValue(
        'SELECT `id_ce_revision` FROM ' . _DB_PREFIX_ . 'ce_revision WHERE `parent` = ' . pSQL($parent) . ' AND `active` = 0 AND `id_employee` = ' . (int) $id_employee
    );

    return $id ? WPPost::getInstance(new UId($id, UId::REVISION)) : false;
}

function wp_get_post_parent_id($post_id)
{
    if (!$post = get_post($post_id)) {
        return false;
    }

    return !empty($post->_obj->parent) ? $post->_obj->parent : 0;
}

function wp_get_post_revisions($post_id, $args = null)
{
    $revisions = [];
    $uid = uidval($post_id);
    $parent = $uid->toDefault();
    $id_employee = \Context::getContext()->employee->id;

    $query = new \DbQuery();
    $query->select($fields = !empty($args['fields']) && 'ids' === $args['fields'] ? '`id_ce_revision`' : '*')->from('ce_revision');
    $query->where('`parent` = ' . pSQL($parent))->where('`active` = 1 OR `id_employee` = ' . (int) $id_employee);
    $query->orderBy('`date_upd` DESC');
    empty($args['posts_per_page']) || $query->limit(1, $args['posts_per_page']);

    if ($rows = \Db::getInstance()->executeS($query)) {
        foreach ($rows as &$row) {
            $uid = new UId($row['id_ce_revision'], UId::REVISION);

            if ('*' === $fields) {
                $row['id'] = $row['id_ce_revision'];
                $revisions[] = WPPost::getInstance($uid, $row);
            } else {
                $revisions[] = "$uid";
            }
        }
    }

    return $revisions;
}

function wp_is_post_revision($post)
{
    $revision = get_post($post);

    return !empty($revision->_obj->parent) ? $revision->_obj->parent : false;
}

function wp_save_post_revision(WPPost $post)
{
    if (UId::REVISION === $post->uid->id_type) {
        return;
    }

    $revisions_to_keep = (int) \Configuration::get('elementor_max_revisions');

    if (!$revisions_to_keep) {
        return;
    }

    $db = \Db::getInstance();
    $id_employee = \Context::getContext()->employee->id;

    foreach (array_reverse($post->uid->getListByShopContext(true)) as $parent) {
        $revisions = $db->executeS(
            'SELECT `id_ce_revision` AS id FROM ' . _DB_PREFIX_ . 'ce_revision WHERE `parent` = ' . pSQL($parent) . ' AND `active` = 1 ORDER BY `date_upd` DESC'
        );
        $return = wp_insert_post([
            'post_type' => 'CERevision',
            'post_status' => 'publish',
            'post_author' => $id_employee,
            'post_parent' => "$parent",
            'post_title' => $post->post_title,
            'post_content' => $post->post_content,
            'template_type' => $post->template_type,
        ]);
        if (!$return) {
            $return = 0;
            continue;
        }
        do_action('_wp_put_post_revision', $return);

        for ($i = $revisions_to_keep - 1; isset($revisions[$i]); ++$i) {
            wp_delete_post_revision(new UId($revisions[$i]['id'], UId::REVISION));
        }
    }

    return $return;
}

function wp_delete_post_revision($revision_id)
{
    $revision = get_post($revision_id);

    if ('CERevision' !== $revision->post_type) {
        return false;
    }

    return $revision->_obj->delete();
}

function get_post_statuses()
{
    return [
        'private' => __('Disabled'),
        'publish' => __('Enabled'),
    ];
}

function get_page_templates($post = null, $post_type = 'CMS')
{
    $templates = [
        'theme' => [
            'label' => __('Theme'),
            'options' => [
                'default' => __('Default'),
            ],
        ],
    ];
    foreach (\Context::getContext()->shop->theme->get('meta.available_layouts') as $name => &$layout) {
        $templates['theme']['options'][$name] = 'layout-full-width' === $name ? __('One Column') : $layout['name'];
    }
    $post_type = $post ? $post->post_type : $post_type;

    if ('Product' === $post_type && \Configuration::get('CE_PRODUCT')) {
        $templates = [];
    }

    return apply_filters("theme_{$post_type}_templates", $templates, $post);
}

function get_post_types_by_support($feature)
{
    if ('elementor' !== $feature) {
        throw new \RuntimeException('Unknown feature: ' . $feature);
    }

    return [
        'CETemplate',
        'CETheme',
        'CMS',
        'CMSCategory',
    ];
}

function post_type_exists($post_type)
{
    return UId::getTypeId($post_type) >= 0;
}

function setup_postdata($uid)
{
    UId::$_ID = uidval($uid);
}
