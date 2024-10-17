<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2024 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Elementor module.
 *
 * An abstract class that provides the needed properties and methods to
 * manage and handle modules in inheriting classes.
 *
 * @since 1.7.0
 * @abstract
 */
abstract class CoreXBaseXModule extends CoreXBaseXBaseObject
{
    /**
     * Module components.
     *
     * Holds the module components.
     *
     * @since 1.7.0
     *
     * @var array
     */
    private $components = [];

    /**
     * Module instance.
     *
     * Holds the module instance.
     *
     * @since 1.7.0
     *
     * @var Module
     */
    protected static $_instances = [];

    /**
     * Get module name.
     *
     * Retrieve the module name.
     *
     * @since 1.7.0
     * @abstract
     *
     * @return string Module name
     */
    abstract public function getName();

    /**
     * Instance.
     *
     * Ensures only one instance of the module class is loaded or can be loaded.
     *
     * @since 1.7.0
     * @static
     *
     * @return Module An instance of the class
     */
    public static function instance()
    {
        $class_name = static::className();

        if (empty(static::$_instances[$class_name])) {
            static::$_instances[$class_name] = new static();
        }

        return static::$_instances[$class_name];
    }

    /**
     * @since 2.0.0
     * @static
     */
    public static function isActive()
    {
        return true;
    }

    /**
     * Class name.
     *
     * Retrieve the name of the class.
     *
     * @since 1.7.0
     * @static
     */
    public static function className()
    {
        return get_called_class();
    }

    /**
     * Clone.
     *
     * Disable class cloning and throw an error on object clone.
     *
     * The whole idea of the singleton design pattern is that there is a single
     * object. Therefore, we don't want the object to be cloned.
     *
     * @since 1.7.0
     */
    public function __clone()
    {
        // Cloning instances of the class is forbidden
        _doing_it_wrong(__FUNCTION__, __('Something went wrong.'), '1.0.0');
    }

    /**
     * Wakeup.
     *
     * Disable unserializing of the class.
     *
     * @since 1.7.0
     */
    public function __wakeup()
    {
        // Unserializing instances of the class is forbidden
        _doing_it_wrong(__FUNCTION__, __('Something went wrong.'), '1.0.0');
    }

    /**
     * Add module component.
     *
     * Add new component to the current module.
     *
     * @since 1.7.0
     *
     * @param string $id Component ID
     * @param mixed $instance An instance of the component
     */
    public function addComponent($id, $instance)
    {
        $this->components[$id] = $instance;
    }

    /**
     * @since 2.3.0
     *
     * @return Module[]
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * Get module component.
     *
     * Retrieve the module component.
     *
     * @since 1.7.0
     *
     * @param string $id Component ID
     *
     * @return mixed an instance of the component, or `false` if the component
     *               doesn't exist
     */
    public function getComponent($id)
    {
        if (isset($this->components[$id])) {
            return $this->components[$id];
        }

        return false;
    }

    /**
     * Get assets url.
     *
     * @since 2.3.0
     *
     * @param string $file_name
     * @param string $file_extension
     * @param string $relative_url Optional. Default is null
     * @param string $add_min_suffix Optional. Default is 'default'
     *
     * @return string
     */
    final protected function getAssetsUrl($file_name, $file_extension, $relative_url = null, $add_min_suffix = 'default')
    {
        // static $is_test_mode = null;

        // if (null === $is_test_mode) {
        //     $is_test_mode = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG || defined('ELEMENTOR_TESTS') && ELEMENTOR_TESTS;
        // }

        if (!$relative_url) {
            $relative_url = $this->getAssetsRelativeUrl() . $file_extension . '/';
        }

        $url = $this->getAssetsBaseUrl() . $relative_url . $file_name;

        if ('default' === $add_min_suffix) {
            $add_min_suffix = !_PS_MODE_DEV_;
        }

        if ($add_min_suffix) {
            $url .= '.min';
        }

        return $url . '.' . $file_extension;
    }

    /**
     * Get js assets url
     *
     * @since 2.3.0
     *
     * @param string $file_name
     * @param string $relative_url Optional. Default is null
     * @param string $add_min_suffix Optional. Default is 'default'
     *
     * @return string
     */
    final protected function getJsAssetsUrl($file_name, $relative_url = null, $add_min_suffix = 'default')
    {
        return $this->getAssetsUrl($file_name, 'js', $relative_url, $add_min_suffix);
    }

    /**
     * Get css assets url
     *
     * @since 2.3.0
     *
     * @param string $file_name
     * @param string $relative_url Optional. Default is null
     * @param string $add_min_suffix Optional. Default is 'default'
     * @param bool $add_direction_suffix Optional. Default is `false`
     *
     * @return string
     */
    final protected function getCssAssetsUrl($file_name, $relative_url = null, $add_min_suffix = 'default', $add_direction_suffix = false)
    {
        static $direction_suffix = null;

        if (!$direction_suffix) {
            $direction_suffix = is_rtl() ? '-rtl' : '';
        }

        if ($add_direction_suffix) {
            $file_name .= $direction_suffix;
        }

        return $this->getAssetsUrl($file_name, 'css', $relative_url, $add_min_suffix);
    }

    /**
     * Get assets base url
     *
     * @since 2.6.0
     *
     * @return string
     */
    protected function getAssetsBaseUrl()
    {
        return _CE_URL_;
    }

    /**
     * Get assets relative url
     *
     * @since 2.3.0
     *
     * @return string
     */
    protected function getAssetsRelativeUrl()
    {
        return 'views/';
    }
}
