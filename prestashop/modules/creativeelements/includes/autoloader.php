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
 * Elementor autoloader.
 *
 * Elementor autoloader handler class is responsible for loading the different
 * classes needed to run the plugin.
 *
 * @since 1.6.0
 */
class Autoloader
{
    /**
     * Classes map.
     *
     * Maps Elementor classes to file names.
     *
     * @since 1.6.0
     * @static
     *
     * @var array Classes used by elementor
     */
    private static $classes_map;

    /**
     * Classes aliases.
     *
     * Maps Elementor classes to aliases.
     *
     * @since 1.6.0
     * @static
     *
     * @var array Classes aliases
     */
    private static $classes_aliases;

    /**
     * Run autoloader.
     *
     * Register a function as `__autoload()` implementation.
     *
     * @since 1.6.0
     * @static
     */
    public static function run()
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /**
     * Get classes aliases.
     *
     * Retrieve the classes aliases names.
     *
     * @since 1.6.0
     * @static
     *
     * @return array Classes aliases
     */
    public static function getClassesAliases()
    {
        if (!self::$classes_aliases) {
            self::initClassesAliases();
        }

        return self::$classes_aliases;
    }

    public static function getClassesMap()
    {
        if (!self::$classes_map) {
            self::initClassesMap();
        }

        return self::$classes_map;
    }

    private static function initClassesMap()
    {
        self::$classes_map = [
            'Api' => 'includes/api.php',
            'BaseControl' => 'includes/controls/base.php',
            'BaseDataControl' => 'includes/controls/base-data.php',
            'BaseUIControl' => 'includes/controls/base-ui.php',
            'Conditions' => 'includes/conditions.php',
            'ControlsManager' => 'includes/managers/controls.php',
            'ControlsStack' => 'includes/base/controls-stack.php',
            'DB' => 'includes/db.php',
            'ElementsManager' => 'includes/managers/elements.php',
            'Embed' => 'includes/embed.php',
            'Fonts' => 'includes/fonts.php',
            'Frontend' => 'includes/frontend.php',
            'GroupControlBase' => 'includes/controls/groups/base.php',
            'GroupControlInterface' => 'includes/interfaces/group-control.php',
            'Heartbeat' => 'includes/heartbeat.php',
            // 'ImagesManager' => 'includes/managers/image.php',
            'Preview' => 'includes/preview.php',
            'Shapes' => 'includes/shapes.php',
            'SkinsManager' => 'includes/managers/skins.php',
            'IconsManager' => 'includes/managers/icons.php',
            'Stylesheet' => 'includes/stylesheet.php',
            'TemplateLibraryXClassesXImportImages' => 'includes/template-library/classes/class-import-images.php',
            'TemplateLibraryXManager' => 'includes/template-library/manager.php',
            'TemplateLibraryXSourceBase' => 'includes/template-library/sources/base.php',
            'TemplateLibraryXSourceLocal' => 'includes/template-library/sources/local.php',
            'TemplateLibraryXSourceRemote' => 'includes/template-library/sources/remote.php',
            'User' => 'includes/user.php',
            'Utils' => 'includes/utils.php',
            'WidgetsManager' => 'includes/managers/widgets.php',
        ];

        $controls_names = ControlsManager::getControlsNames();

        $controls_names = array_merge($controls_names, [
            'base_multiple',
            'base_units',
        ]);

        foreach ($controls_names as $control_name) {
            $class_name = 'Control' . self::normalizeClassName($control_name, '_');

            self::$classes_map[$class_name] = 'includes/controls/' . str_replace('_', '-', $control_name) . '.php';
        }

        $controls_groups_names = ControlsManager::getGroupsNames();

        foreach ($controls_groups_names as $group_name) {
            $class_name = 'GroupControl' . self::normalizeClassName(str_replace('-', '_', $group_name), '_');

            self::$classes_map[$class_name] = 'includes/controls/groups/' . $group_name . '.php';
        }
    }

    /**
     * Normalize Class Name
     *
     * Used to convert control names to class name,
     * a ucwords polyfill for php versions not supporting delimiter parameter
     * reference : https://github.com/elementor/elementor/issues/7310#issuecomment-469593385
     *
     * @param $string
     * @param string $delimiter
     *
     * @todo Remove once we bump minimum php version to 5.6
     *
     * @return mixed
     */
    private static function normalizeClassName($string, $delimiter = ' ')
    {
        return str_replace(' ', '', ucwords(str_replace($delimiter, ' ', $string)));
    }

    private static function initClassesAliases()
    {
        self::$classes_aliases = [
            'Ajax' => [
                'replacement' => 'CoreXCommonXModulesXAjaxXModule',
                'version' => '2.3.0',
            ],
            'Editor' => [
                'replacement' => 'CoreXEditorXEditor',
                'version' => '2.6.0',
            ],
            'SchemeColor' => [
                'replacement' => 'CoreXSchemesXColor',
                'version' => '2.8.0',
            ],
            'SchemeColorPicker' => [
                'replacement' => 'CoreXSchemesXColorPicker',
                'version' => '2.8.0',
            ],
            'SchemeTypography' => [
                'replacement' => 'CoreXSchemesXTypography',
                'version' => '2.8.0',
            ],
        ];
    }

    /**
     * Load class.
     *
     * For a given class name, require the class file.
     *
     * @since 1.6.0
     * @static
     *
     * @param string $relative_class_name Class name
     */
    private static function loadClass($relative_class_name)
    {
        $classes_map = self::getClassesMap();

        if (isset($classes_map[$relative_class_name])) {
            $filename = _CE_PATH_ . '/' . $classes_map[$relative_class_name];
        } else {
            $filename = strtolower(preg_replace(
                ['/X/', '/([a-z])([A-Z])/'],
                ['/', '$1-$2'],
                $relative_class_name
            ));

            $filename = _CE_PATH_ . $filename . '.php';
        }

        if (is_readable($filename)) {
            require $filename;
        }
    }

    /**
     * Autoload.
     *
     * For a given class, check if it exist and load it.
     *
     * @since 1.6.0
     * @static
     *
     * @param string $class Class name
     */
    private static function autoload($class)
    {
        if (0 !== strpos($class, __NAMESPACE__ . '\\')) {
            return;
        }

        $relative_class_name = substr($class, 3);

        $classes_aliases = self::getClassesAliases();

        $has_class_alias = isset($classes_aliases[$relative_class_name]);

        // Backward Compatibility: Save old class name for set an alias after the new class is loaded
        if ($has_class_alias) {
            $alias_data = $classes_aliases[$relative_class_name];

            $relative_class_name = $alias_data['replacement'];
        }

        $final_class_name = __NAMESPACE__ . '\\' . $relative_class_name;

        if (!class_exists($final_class_name)) {
            self::loadClass($relative_class_name);
        }

        if ($has_class_alias) {
            class_alias($final_class_name, $class);

            // Utils::handle_deprecation($class, $alias_data['version'], $final_class_name);
        }
    }
}
