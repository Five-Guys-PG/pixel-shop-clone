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
 * Elementor template library source base.
 *
 * Elementor template library source base handler class is responsible for
 * initializing all the methods controlling the source of Elementor templates.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class TemplateLibraryXSourceBase
{
    /**
     * User meta.
     *
     * Holds the current user meta data.
     *
     * @var array
     */
    private $user_meta;

    /**
     * Get template ID.
     *
     * Retrieve the template ID.
     *
     * @since 1.0.0
     * @abstract
     */
    abstract public function getId();

    /**
     * Get template title.
     *
     * Retrieve the template title.
     *
     * @since 1.0.0
     * @abstract
     */
    abstract public function getTitle();

    /**
     * Register template data.
     *
     * Used to register custom template data like a post type, a taxonomy or any
     * other data.
     *
     * @since 1.0.0
     */
    public function registerData()
    {
    }

    /**
     * Get templates.
     *
     * Retrieve templates from the template library.
     *
     * @since 1.0.0
     * @abstract
     *
     * @param array $args Optional. Filter templates list based on a set of
     *                    arguments. Default is an empty array.
     */
    abstract public function getItems($args = []);

    /**
     * Get template.
     *
     * Retrieve a single template from the template library.
     *
     * @since 1.0.0
     * @abstract
     *
     * @param int $template_id The template ID
     */
    abstract public function getItem($template_id);

    /**
     * Get template data.
     *
     * Retrieve a single template data from the template library.
     *
     * @since 1.5.0
     * @abstract
     *
     * @param array $args Custom template arguments
     */
    abstract public function getData(array $args);

    /**
     * Delete template.
     *
     * Delete template from the database.
     *
     * @since 1.0.0
     * @abstract
     *
     * @param int $template_id The template ID
     */
    abstract public function deleteTemplate($template_id);

    /**
     * Save template.
     *
     * Save new or update existing template on the database.
     *
     * @since 1.0.0
     * @abstract
     *
     * @param array $template_data The template data
     */
    abstract public function saveItem($template_data);

    /**
     * Update template.
     *
     * Update template on the database.
     *
     * @since 1.0.0
     * @abstract
     *
     * @param array $new_data New template data
     */
    abstract public function updateItem($new_data);

    /**
     * Export template.
     *
     * Export template to a file.
     *
     * @since 1.0.0
     * @abstract
     *
     * @param int $template_id The template ID
     */
    abstract public function exportTemplate($template_id);

    /**
     * Template library source base constructor.
     *
     * Initializing the template library source base by registering custom
     * template data.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->registerData();
    }

    /**
     * Mark template as favorite.
     *
     * Update user meta containing his favorite templates. For a given template
     * ID, add the template to the favorite templates or remove it from the
     * favorites, based on the `favorite` parameter.
     *
     * @since 1.9.0
     *
     * @param int $template_id The template ID
     * @param bool $favorite Optional. Whether the template is marked as
     *                       favorite, or not. Default is true.
     *
     * @return int|bool user meta ID if the key didn't exist, true on successful
     *                  update, false on failure
     */
    public function markAsFavorite($template_id, $favorite = true)
    {
        $favorites_templates = $this->getUserMeta('favorites');

        if (!$favorites_templates) {
            $favorites_templates = [];
        }

        if ($favorite) {
            $favorites_templates[$template_id] = $favorite;
        } elseif (isset($favorites_templates[$template_id])) {
            unset($favorites_templates[$template_id]);
        }

        return $this->updateUserMeta('favorites', $favorites_templates);
    }

    /**
     * Get current user meta.
     *
     * Retrieve Elementor meta data for the current user.
     *
     * @since 1.9.0
     *
     * @param string $item Optional. User meta key. Default is null
     *
     * @return array|null An array of user meta data, or null otherwise
     */
    public function getUserMeta($item = null)
    {
        if (null === $this->user_meta) {
            $this->user_meta = get_user_meta(get_current_user_id(), $this->getUserMetaPrefix(), true);
        }

        if (!$this->user_meta) {
            $this->user_meta = [];
        }

        if ($item) {
            if (isset($this->user_meta[$item])) {
                return $this->user_meta[$item];
            }

            return null;
        }

        return $this->user_meta;
    }

    /**
     * Update current user meta.
     *
     * Update user meta data based on meta key an value.
     *
     * @since 1.9.0
     *
     * @param string $key Optional. User meta key
     * @param mixed $value Optional. User meta value
     *
     * @return int|bool user meta ID if the key didn't exist, true on successful
     *                  update, false on failure
     */
    public function updateUserMeta($key, $value)
    {
        $meta = $this->getUserMeta();

        $meta[$key] = $value;

        $this->user_meta = $meta;

        return update_user_meta(get_current_user_id(), $this->getUserMetaPrefix(), $meta);
    }

    /**
     * Replace elements IDs.
     *
     * For any given Elementor content/data, replace the IDs with new randomly
     * generated IDs.
     *
     * @since 1.0.0
     *
     * @param array $content Any type of Elementor data
     *
     * @return mixed Iterated data
     */
    protected function replaceElementsIds($content)
    {
        return Plugin::$instance->db->iterateData($content, function ($element) {
            $element['id'] = Utils::generateRandomString();

            return $element;
        });
    }

    /**
     * Get Elementor library user meta prefix.
     *
     * Retrieve user meta prefix used to save Elementor data.
     *
     * @since 1.9.0
     *
     * @return string User meta prefix
     */
    protected function getUserMetaPrefix()
    {
        return 'elementor_library_' . $this->getId();
    }

    /**
     * Process content for export/import.
     *
     * Process the content and all the inner elements, and prepare all the
     * elements data for export/import.
     *
     * @since 1.5.0
     *
     * @param array $content A set of elements
     * @param string $method accepts either `onExport` to export data or
     *                       `onImport` to import data
     *
     * @return mixed Processed content data
     */
    protected function processExportImportContent($content, $method)
    {
        return Plugin::$instance->db->iterateData(
            $content,
            function ($element_data) use ($method) {
                $element = Plugin::$instance->elements_manager->createElementInstance($element_data);

                // If the widget/element isn't exist, like a plugin that creates a widget but deactivated
                if (!$element) {
                    return null;
                }

                return $this->processElementExportImportContent($element, $method);
            }
        );
    }

    /**
     * Process single element content for export/import.
     *
     * Process any given element and prepare the element data for export/import.
     *
     * @since 1.5.0
     *
     * @param ControlsStack $element
     * @param string $method
     *
     * @return array Processed element data
     */
    protected function processElementExportImportContent(ControlsStack $element, $method)
    {
        $element_data = $element->getData();

        if (method_exists($element, $method)) {
            // TODO: Use the internal element data without parameters.
            $element_data = $element->{$method}($element_data);
        }

        foreach ($element->getControls() as $control) {
            $control_class = Plugin::$instance->controls_manager->getControl($control['type']);

            // If the control isn't exist, like a plugin that creates the control but deactivated.
            if (!$control_class) {
                return $element_data;
            }

            if (method_exists($control_class, $method)) {
                $element_data['settings'][$control['name']] = $control_class->{$method}($element->getSettings($control['name']), $control);
            }

            // On Export, check if the control has an argument 'export' => false.
            if ('onExport' === $method && isset($control['export']) && false === $control['export']) {
                unset($element_data['settings'][$control['name']]);
            }
        }

        return $element_data;
    }
}
