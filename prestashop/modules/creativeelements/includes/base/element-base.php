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
 * Elementor element base.
 *
 * An abstract class to register new Elementor elements. It extended the
 * `Controls_Stack` class to inherit its properties.
 *
 * This abstract class must be extended in order to register new elements.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class ElementBase extends ControlsStack
{
    /**
     * Child elements.
     *
     * Holds all the child elements of the element.
     *
     * @var ElementBase[]
     */
    private $children;

    /**
     * Element render attributes.
     *
     * Holds all the render attributes of the element. Used to store data like
     * the HTML class name and the class value, or HTML element ID name and value.
     *
     * @var array
     */
    private $render_attributes = [];

    /**
     * Element default arguments.
     *
     * Holds all the default arguments of the element. Used to store additional
     * data.
     *
     * @var array
     */
    private $default_args = [];

    /**
     * Is type instance.
     *
     * Whether the element is an instance of that type or not.
     *
     * @var bool
     */
    private $is_type_instance = true;

    /**
     * Depended scripts.
     *
     * Holds all the element depended scripts to enqueue.
     *
     * @since 1.9.0
     *
     * @var array
     */
    private $depended_scripts = [];

    /**
     * Depended styles.
     *
     * Holds all the element depended styles to enqueue.
     *
     * @since 1.9.0
     *
     * @var array
     */
    private $depended_styles = [];

    /**
     * Add script depends.
     *
     * Register new script to enqueue by the handler.
     *
     * @since 1.9.0
     *
     * @param string $handler Depend script handler
     */
    public function addScriptDepends($handler)
    {
        $this->depended_scripts[] = $handler;
    }

    /**
     * Add style depends.
     *
     * Register new style to enqueue by the handler.
     *
     * @since 1.9.0
     *
     * @param string $handler Depend style handler
     */
    public function addStyleDepends($handler)
    {
        $this->depended_styles[] = $handler;
    }

    /**
     * Get script dependencies.
     *
     * Retrieve the list of script dependencies the element requires.
     *
     * @since 1.3.0
     *
     * @return array Element scripts dependencies
     */
    public function getScriptDepends()
    {
        return $this->depended_scripts;
    }

    /**
     * Enqueue scripts.
     *
     * Registers all the scripts defined as element dependencies and enqueues
     * them. Use `get_script_depends()` method to add custom script dependencies.
     *
     * @since 1.3.0
     */
    final public function enqueueScripts()
    {
        foreach ($this->getScriptDepends() as $script) {
            wp_enqueue_script($script);
        }
    }

    /**
     * Get style dependencies.
     *
     * Retrieve the list of style dependencies the element requires.
     *
     * @since 1.9.0
     *
     * @return array Element styles dependencies
     */
    public function getStyleDepends()
    {
        return $this->depended_styles;
    }

    /**
     * Enqueue styles.
     *
     * Registers all the styles defined as element dependencies and enqueues
     * them. Use `get_style_depends()` method to add custom style dependencies.
     *
     * @since 1.9.0
     */
    final public function enqueueStyles()
    {
        foreach ($this->getStyleDepends() as $style) {
            wp_enqueue_style($style);
        }
    }

    /**
     * Get default child type.
     *
     * Retrieve the default child type based on element data.
     *
     * Note that not all elements support children.
     *
     * @since 1.0.0
     * @abstract
     *
     * @param array $element_data Element data
     *
     * @return ElementBase
     */
    abstract protected function _getDefaultChildType(array $element_data);

    /**
     * Before element rendering.
     *
     * Used to add stuff before the element.
     *
     * @since 1.0.0
     */
    public function beforeRender()
    {
    }

    /**
     * After element rendering.
     *
     * Used to add stuff after the element.
     *
     * @since 1.0.0
     */
    public function afterRender()
    {
    }

    /**
     * Get element title.
     *
     * Retrieve the element title.
     *
     * @since 1.0.0
     *
     * @return string Element title
     */
    public function getTitle()
    {
        return '';
    }

    /**
     * Get element icon.
     *
     * Retrieve the element icon.
     *
     * @since 1.0.0
     *
     * @return string Element icon
     */
    public function getIcon()
    {
        return 'eicon-columns';
    }

    public function getHelpUrl()
    {
        return '';
    }

    /**
     * Whether the reload preview is required.
     *
     * Used to determine whether the reload preview is required or not.
     *
     * @since 1.0.0
     *
     * @return bool Whether the reload preview is required
     */
    public function isReloadPreviewRequired()
    {
        return false;
    }

    /**
     * @since 2.3.1
     */
    protected function shouldPrintEmpty()
    {
        return true;
    }

    /**
     * Whether the element returns dynamic content.
     *
     * set to determine whether to cache the element output or not.
     *
     * @since 2.10.2
     *
     * @return bool Whether to cache the element output
     */
    protected function isDynamicContent()
    {
        return true;
    }

    /**
     * Get child elements.
     *
     * Retrieve all the child elements of this element.
     *
     * @since 1.0.0
     *
     * @return ElementBase[] Child elements
     */
    public function getChildren()
    {
        if (null === $this->children) {
            $this->initChildren();
        }

        return $this->children;
    }

    /**
     * Get default arguments.
     *
     * Retrieve the element default arguments. Used to return all the default
     * arguments or a specific default argument, if one is set.
     *
     * @since 1.0.0
     *
     * @param array $item Optional. Default is null
     *
     * @return array Default argument(s)
     */
    public function getDefaultArgs($item = null)
    {
        return self::getItems($this->default_args, $item);
    }

    /**
     * Add new child element.
     *
     * Register new child element to allow hierarchy.
     *
     * @since 1.0.0
     *
     * @param array $child_data Child element data
     * @param array $child_args Child element arguments
     *
     * @return ElementBase|false Child element instance, or false if failed
     */
    public function addChild(array $child_data, array $child_args = [])
    {
        if (null === $this->children) {
            $this->initChildren();
        }

        $child_type = $this->getChildType($child_data);

        if (!$child_type) {
            return false;
        }

        $child = Plugin::$instance->elements_manager->createElementInstance($child_data, $child_args, $child_type);

        if ($child) {
            $this->children[] = $child;
        }

        return $child;
    }

    /**
     * Add render attribute.
     *
     * Used to add attributes to a specific HTML element.
     *
     * The HTML tag is represented by the element parameter, then you need to
     * define the attribute key and the attribute key. The final result will be:
     * `<element attribute_key="attribute_value">`.
     *
     * Example usage:
     *
     * `$this->addRenderAttribute( 'wrapper', 'class', 'custom-widget-wrapper-class' );`
     * `$this->addRenderAttribute( 'widget', 'id', 'custom-widget-id' );`
     * `$this->addRenderAttribute( 'button', [ 'class' => 'custom-button-class', 'id' => 'custom-button-id' ] );`
     *
     * @since 1.0.0
     *
     * @param array|string $element The HTML element
     * @param array|string $key Optional. Attribute key. Default is null
     * @param array|string $value Optional. Attribute value. Default is null
     * @param bool $overwrite Optional. Whether to overwrite existing
     *                        attribute. Default is false, not to overwrite.
     *
     * @return ElementBase Current instance of the element
     */
    public function addRenderAttribute($element, $key = null, $value = null, $overwrite = false)
    {
        if (is_array($element)) {
            foreach ($element as $element_key => $attributes) {
                $this->addRenderAttribute($element_key, $attributes, null, $overwrite);
            }

            return $this;
        }

        if (is_array($key)) {
            foreach ($key as $attribute_key => $attributes) {
                $this->addRenderAttribute($element, $attribute_key, $attributes, $overwrite);
            }

            return $this;
        }

        if (empty($this->render_attributes[$element][$key])) {
            $this->render_attributes[$element][$key] = [];
        }

        settype($value, 'array');

        if ($overwrite) {
            $this->render_attributes[$element][$key] = $value;
        } else {
            $this->render_attributes[$element][$key] = array_merge($this->render_attributes[$element][$key], $value);
        }

        return $this;
    }

    /**
     * Add link render attributes.
     *
     * Used to add link tag attributes to a specific HTML element.
     *
     * The HTML link tag is represented by the element parameter. The `url_control` parameter
     * needs to be an array of link settings in the same format they are set by Elementor's URL control.
     *
     * Example usage:
     *
     * `$this->addLinkAttributes( 'button', $settings['link'] );`
     *
     * @since 2.8.0
     *
     * @param array|string $element The HTML element
     * @param array $url_control Array of link settings
     * @param bool $overwrite Optional. Whether to overwrite existing
     *                        attribute. Default is false, not to overwrite.
     *
     * @return ElementBase Current instance of the element
     */
    public function addLinkAttributes($element, array $url_control, $overwrite = false)
    {
        $attributes = [];

        if (!empty($url_control['url'])) {
            $attributes['href'] = $url_control['url'];
        }

        if (!empty($url_control['is_external'])) {
            $attributes['target'] = '_blank';
        }

        if (!empty($url_control['nofollow'])) {
            $attributes['rel'] = 'nofollow';
        }

        if (!empty($url_control['custom_attributes'])) {
            // Custom URL attributes should come as a string of comma-delimited key|value pairs
            $attributes = array_merge($attributes, Utils::parseCustomAttributes($url_control['custom_attributes']));
        }

        if ($attributes) {
            $this->addRenderAttribute($element, $attributes, $overwrite);
        }

        return $this;
    }

    /**
     * Get Render Attributes
     *
     * Used to retrieve render attribute.
     *
     * The returned array is either all elements and their attributes if no `$element` is specified, an array of all
     * attributes of a specific element or a specific attribute properties if `$key` is specified.
     *
     * Returns null if one of the requested parameters isn't set.
     *
     * @since 2.2.6
     *
     * @param string $element
     * @param string $key
     *
     * @return array
     */
    public function getRenderAttributes($element = '', $key = '')
    {
        $attributes = $this->render_attributes;

        if ($element) {
            if (!isset($attributes[$element])) {
                return null;
            }

            $attributes = $attributes[$element];

            if ($key) {
                if (!isset($attributes[$key])) {
                    return null;
                }

                $attributes = $attributes[$key];
            }
        }

        return $attributes;
    }

    /**
     * Set render attribute.
     *
     * Used to set the value of the HTML element render attribute or to update
     * an existing render attribute.
     *
     * @since 1.0.0
     *
     * @param array|string $element The HTML element
     * @param array|string $key Optional. Attribute key. Default is null
     * @param array|string $value Optional. Attribute value. Default is null
     *
     * @return ElementBase Current instance of the element
     */
    public function setRenderAttribute($element, $key = null, $value = null)
    {
        return $this->addRenderAttribute($element, $key, $value, true);
    }

    /**
     * Remove render attribute.
     *
     * Used to remove an element (with its keys and their values), key (with its values),
     * or value/s from an HTML element's render attribute.
     *
     * @since 2.7.0
     *
     * @param string $element The HTML element
     * @param string $key Optional. Attribute key. Default is null
     * @param array|string $values Optional. Attribute value/s. Default is null
     */
    public function removeRenderAttribute($element, $key = null, $values = null)
    {
        if ($key && !isset($this->render_attributes[$element][$key])) {
            return;
        }

        if ($values) {
            $values = (array) $values;

            $this->render_attributes[$element][$key] = array_diff($this->render_attributes[$element][$key], $values);

            return;
        }

        if ($key) {
            unset($this->render_attributes[$element][$key]);

            return;
        }

        if (isset($this->render_attributes[$element])) {
            unset($this->render_attributes[$element]);
        }
    }

    /**
     * Get render attribute string.
     *
     * Used to retrieve the value of the render attribute.
     *
     * @since 1.0.0
     *
     * @param string $element The element
     *
     * @return string render attribute string, or an empty string if the attribute
     *                is empty or not exist
     */
    public function getRenderAttributeString($element)
    {
        if (empty($this->render_attributes[$element])) {
            return '';
        }

        return Utils::renderHtmlAttributes($this->render_attributes[$element]);
    }

    /**
     * Print render attribute string.
     *
     * Used to output the rendered attribute.
     *
     * @since 2.0.0
     *
     * @param array|string $element The element
     */
    public function printRenderAttributeString($element)
    {
        echo $this->getRenderAttributeString($element); // XSS ok.
    }

    /**
     * Print element.
     *
     * Used to generate the element final HTML on the frontend and the editor.
     *
     * @since 1.0.0
     */
    public function printElement()
    {
        $element_type = $this->getType();

        if ($this->shouldRenderShortcode()) {
            return print "{ce element='" . base64_encode(json_encode($this->getRenderData(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) . "'}";
        }

        // do_action('elementor/frontend/before_render', $this);

        /*
         * Before frontend element render.
         *
         * Fires before Elementor element is rendered in the frontend.
         *
         * The dynamic portion of the hook name, `$element_type`, refers to the element type.
         *
         * @since 1.0.0
         *
         * @param ElementBase $this The element
         */
        do_action("elementor/frontend/{$element_type}/before_render", $this);

        ob_start();
        $this->_printContent();
        $content = ob_get_clean();

        $should_render = !empty($content) || $this->shouldPrintEmpty();

        /*
         * Should the element be rendered for frontend
         *
         * Filters if the element should be rendered on frontend.
         *
         * @since 2.3.3
         *
         * @param bool true The element
         * @param ElementBase $this The element
         */
        $should_render = apply_filters("elementor/frontend/{$element_type}/should_render", $should_render, $this);

        if ($should_render) {
            $this->_addRenderAttributes();

            $this->beforeRender();
            echo $content;
            $this->afterRender();

            $this->enqueueScripts();
            $this->enqueueStyles();
        }

        /*
         * After frontend element render.
         *
         * Fires after Elementor element is rendered in the frontend.
         *
         * The dynamic portion of the hook name, `$element_type`, refers to the element type.
         *
         * @since 1.0.0
         *
         * @param ElementBase $this The element
         */
        do_action("elementor/frontend/{$element_type}/after_render", $this);

        // do_action('elementor/frontend/after_render', $this);
    }

    protected function shouldRenderShortcode()
    {
        if (!apply_filters('elementor/element/should_render_shortcode', false)) {
            return false;
        }

        $data = $this->getData();

        if (!empty($data['settings']['_element_cache'])) {
            return 'yes' === $data['settings']['_element_cache'];
        }

        return $this->isDynamicContent() || !empty($data['settings']['__dynamic__']);
    }

    /**
     * Get the element raw data.
     *
     * Retrieve the raw element data, including the id, type, settings, child
     * elements and whether it is an inner element.
     *
     * The data with the HTML used always to display the data, but the Elementor
     * editor uses the raw data without the HTML in order not to render the data
     * again.
     *
     * @since 1.0.0
     *
     * @param bool $with_html_content Optional. Whether to return the data with
     *                                HTML content or without. Used for caching.
     *                                Default is false, without HTML.
     *
     * @return array Element raw data
     */
    public function getRawData($with_html_content = false)
    {
        $data = $this->getData();

        $elements = [];

        foreach ($this->getChildren() as $child) {
            $elements[] = $child->getRawData($with_html_content);
        }

        return [
            'id' => $this->getId(),
            'elType' => $data['elType'],
            'settings' => $data['settings'],
            'elements' => &$elements,
            'isInner' => $data['isInner'],
        ];
    }

    public function getRenderData()
    {
        $data = $this->getData();
        $settings = &$data['settings'];
        $elements = [];

        foreach ($this->getChildren() as $child) {
            $elements[] = $child->getRenderData();
        }
        $data['elements'] = &$elements;
        $controls = $this->getActiveControls();

        if (!empty($settings['__dynamic__'])) {
            $controls['__dynamic__'] = ['frontend_available' => true];

            foreach ($settings['__dynamic__'] as $name => &$value) {
                unset($settings[$name]);
            }
        }
        // BC Fix: Font Awesome 4
        foreach ($controls as &$control) {
            isset($control['fa4compatibility']) && $controls[$control['fa4compatibility']] = ['frontend_available' => true];
        }
        isset($settings['__fa4_migrated']) && $controls['__fa4_migrated'] = ['frontend_available' => true];

        foreach ($settings as $name => &$value) {
            if (empty($controls[$name])) {
                unset($settings[$name]);
                continue;
            }
            $control = &$controls[$name];

            if (!empty($control['frontend_available']) || !empty($control['prefix_class'])) {
                continue;
            }

            if (isset($control['render_type'])) {
                if ('template' !== $control['render_type']) {
                    unset($settings[$name]);
                }
            } elseif (!empty($control['selectors'])) {
                unset($settings[$name]);
            }
        }

        return $data;
    }

    /**
     * Get unique selector.
     *
     * Retrieve the unique selector of the element. Used to set a unique HTML
     * class for each HTML element. This way Elementor can set custom styles for
     * each element.
     *
     * @since 1.0.0
     *
     * @return string Unique selector
     */
    public function getUniqueSelector()
    {
        return '.elementor-element-' . $this->getId();
    }

    /**
     * Is type instance.
     *
     * Used to determine whether the element is an instance of that type or not.
     *
     * @since 1.0.0
     *
     * @return bool Whether the element is an instance of that type
     */
    public function isTypeInstance()
    {
        return $this->is_type_instance;
    }

    /**
     * Add render attributes.
     *
     * Used to add attributes to the current element wrapper HTML tag.
     *
     * @since 1.3.0
     */
    protected function _addRenderAttributes()
    {
        $id = $this->getId();

        $settings = $this->getSettingsForDisplay();
        $frontend_settings = $this->getFrontendSettings();
        $controls = $this->getControls();

        $this->addRenderAttribute('_wrapper', [
            'class' => [
                'elementor-element',
                'elementor-element-' . $id,
            ],
            'data-id' => $id,
            'data-element_type' => $this->getType(),
        ]);

        $class_settings = [];

        foreach ($settings as $setting_key => $setting) {
            if (isset($controls[$setting_key]['prefix_class'])) {
                $class_settings[$setting_key] = $setting;
            }
        }

        foreach ($class_settings as $setting_key => $setting) {
            if (empty($setting) && '0' !== $setting) {
                continue;
            }

            $this->addRenderAttribute('_wrapper', 'class', $controls[$setting_key]['prefix_class'] . $setting);
        }

        $animation = $this instanceof WidgetBase ? '_animation' : 'animation';

        if (!empty($settings[$animation]) && 'none' !== $settings[$animation]) {
            // Hide the element until the animation begins
            $this->addRenderAttribute('_wrapper', 'class', 'elementor-invisible');
        }

        if (!empty($settings['_element_id'])) {
            $this->addRenderAttribute('_wrapper', 'id', trim($settings['_element_id']));
        }

        if ($frontend_settings) {
            $this->addRenderAttribute('_wrapper', 'data-settings', json_encode($frontend_settings));
        }

        /*
         * After element attribute rendered.
         *
         * Fires after the attributes of the element HTML tag are rendered.
         *
         * @since 2.3.0
         *
         * @param ElementBase $this The element
         */
        do_action('elementor/element/after_add_attributes', $this);
    }

    /**
     * Get default data.
     *
     * Retrieve the default element data. Used to reset the data on initialization.
     *
     * @since 1.0.0
     *
     * @return array Default data
     */
    protected function getDefaultData()
    {
        $data = parent::getDefaultData();

        return array_merge($data, [
            'elements' => [],
            'isInner' => false,
        ]);
    }

    /**
     * Print element content.
     *
     * Output the element final HTML on the frontend.
     *
     * @since 1.0.0
     */
    protected function _printContent()
    {
        foreach ($this->getChildren() as $child) {
            $child->printElement();
        }
    }

    /**
     * Get initial config.
     *
     * Retrieve the current element initial configuration.
     *
     * Adds more configuration on top of the controls list and the tabs assigned
     * to the control. This method also adds element name, type, icon and more.
     *
     * @since 2.9.0
     *
     * @return array The initial config
     */
    protected function getInitialConfig()
    {
        $config = [
            'name' => $this->getName(),
            'elType' => $this->getType(),
            'title' => $this->getTitle(),
            'icon' => $this->getIcon(),
            'reload_preview' => $this->isReloadPreviewRequired(),
            'help_url' => $this->getHelpUrl(),
        ];

        if (!$this->isEditable()) {
            $config['editable'] = false;
        }

        return $config;
    }

    /**
     * Get child type.
     *
     * Retrieve the element child type based on element data.
     *
     * @since 2.0.0
     *
     * @param array $element_data Element ID
     *
     * @return ElementBase|false Child type or false if type not found
     */
    private function getChildType($element_data)
    {
        $child_type = $this->_getDefaultChildType($element_data);

        // If it's not a valid widget ( like a deactivated plugin )
        if (!$child_type) {
            return false;
        }

        /*
         * Element child type.
         *
         * Filters the child type of the element.
         *
         * @since 1.0.0
         *
         * @param ElementBase $child_type   The child element
         * @param array        $element_data The original element ID
         * @param ElementBase $this         The original element
         */
        $child_type = apply_filters('elementor/element/get_child_type', $child_type, $element_data, $this);

        return $child_type;
    }

    /**
     * Initialize children.
     *
     * Initializing the element child elements.
     *
     * @since 2.0.0
     */
    private function initChildren()
    {
        $this->children = [];

        $children_data = $this->getData('elements');

        if (!$children_data) {
            return;
        }

        foreach ($children_data as $child_data) {
            if (!$child_data) {
                continue;
            }

            $this->addChild($child_data);
        }
    }

    /**
     * Element base constructor.
     *
     * Initializing the element base class using `$data` and `$args`.
     *
     * The `$data` parameter is required for a normal instance because of the
     * way Elementor renders data when initializing elements.
     *
     * @since 1.0.0
     *
     * @param array $data Optional. Element data. Default is an empty array
     * @param array|null $args Optional. Element default arguments. Default is null
     */
    public function __construct(array $data = [], $args = null)
    {
        if ($data) {
            $this->is_type_instance = false;
        } elseif ($args) {
            $this->default_args = $args;
        }

        parent::__construct($data);
    }
}
