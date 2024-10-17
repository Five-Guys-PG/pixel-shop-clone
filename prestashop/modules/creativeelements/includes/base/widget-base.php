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

use CE\CoreXSettingsXManager as SettingsManager;

/**
 * Elementor widget base.
 *
 * An abstract class to register new Elementor widgets. It extended the
 * `ElementBase` class to inherit its properties.
 *
 * This abstract class must be extended in order to register new widgets.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class WidgetBase extends ElementBase
{
    const REMOTE_RENDER = false;

    private static $render_method = 'render';

    public static function setRenderMethod($method)
    {
        self::$render_method = $method;
    }

    public static function getRenderMethod()
    {
        return self::$render_method;
    }

    /**
     * Whether the widget has content.
     *
     * Used in cases where the widget has no content. When widgets uses only
     * skins to display dynamic content generated on the server.
     * Default is true, the widget has content template.
     *
     * @var bool
     */
    protected $_has_template_content = true;

    private $is_first_section = true;

    /**
     * Get element type.
     *
     * Retrieve the element type, in this case `widget`.
     *
     * @since 1.0.0
     * @static
     *
     * @return string The type
     */
    public static function getType()
    {
        return 'widget';
    }

    /**
     * Get widget icon.
     *
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @return string Widget icon
     */
    public function getIcon()
    {
        return 'eicon-apps';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the widget keywords.
     *
     * @since 1.0.10
     *
     * @return array Widget keywords
     */
    public function getKeywords()
    {
        return [];
    }

    /**
     * Get widget categories.
     *
     * Retrieve the widget categories.
     *
     * @since 1.0.10
     *
     * @return array Widget categories
     */
    public function getCategories()
    {
        return ['general'];
    }

    /**
     * Widget base constructor.
     *
     * Initializing the widget base class.
     *
     * @since 1.0.0
     *
     * @param array $data Widget data. Default is an empty array
     * @param array|null $args Optional. Widget default arguments. Default is null
     *
     * @throws \Exception if arguments are missing when initializing a full widget instance
     */
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $is_type_instance = $this->isTypeInstance();

        if (!$is_type_instance && null === $args) {
            throw new \Exception('`$args` argument is required when initializing a full widget instance.');
        }

        if ($is_type_instance) {
            $this->_registerSkins();

            $widget_name = $this->getName();

            /*
             * Widget skin init.
             *
             * Fires when Elementor widget is being initialized.
             *
             * The dynamic portion of the hook name, `$widget_name`, refers to the widget name.
             *
             * @since 1.0.0
             *
             * @param WidgetBase $this The current widget
             */
            do_action("elementor/widget/{$widget_name}/skins_init", $this);
        }
    }

    /**
     * Get stack.
     *
     * Retrieve the widget stack of controls.
     *
     * @since 1.9.2
     *
     * @param bool $with_common_controls Optional. Whether to include the common controls. Default is true
     *
     * @return array Widget stack of controls
     */
    public function getStack($with_common_controls = true)
    {
        $stack = parent::getStack();

        if ($with_common_controls && 'common' !== $this->getUniqueName()) {
            /* @var WidgetCommon $common_widget */
            $common_widget = Plugin::$instance->widgets_manager->getWidgetTypes('common');

            $stack['controls'] = array_merge($stack['controls'], $common_widget->getControls());

            $stack['tabs'] = array_merge($stack['tabs'], $common_widget->getTabsControls());
        }

        return $stack;
    }

    /**
     * Get widget controls pointer index.
     *
     * Retrieve widget pointer index where the next control should be added.
     *
     * While using injection point, it will return the injection point index. Otherwise index of the last control of the
     * current widget itself without the common controls, plus one.
     *
     * @since 1.9.2
     *
     * @return int Widget controls pointer index
     */
    public function getPointerIndex()
    {
        $injection_point = $this->getInjectionPoint();

        if (null !== $injection_point) {
            return $injection_point['index'];
        }

        return count($this->getStack(false)['controls']);
    }

    /**
     * Show in panel.
     *
     * Whether to show the widget in the panel or not. By default returns true.
     *
     * @since 1.0.0
     *
     * @return bool Whether to show the widget in the panel or not
     */
    public function showInPanel()
    {
        return true;
    }

    /**
     * Start widget controls section.
     *
     * Used to add a new section of controls to the widget. Regular controls and
     * skin controls.
     *
     * Note that when you add new controls to widgets they must be wrapped by
     * `start_controls_section()` and `end_controls_section()`.
     *
     * @since 1.0.0
     *
     * @param string $section_id Section ID
     * @param array $args Section arguments Optional
     */
    public function startControlsSection($section_id, array $args = [])
    {
        parent::startControlsSection($section_id, $args);

        if ($this->is_first_section) {
            $this->registerSkinControl();

            $this->is_first_section = false;
        }
    }

    /**
     * Register the Skin Control if the widget has skins.
     *
     * An internal method that is used to add a skin control to the widget.
     * Added at the top of the controls section.
     *
     * @since 2.0.0
     */
    protected function registerSkinControl()
    {
        $skins = $this->getSkins();

        if (!empty($skins)) {
            $skin_options = [];

            if ($this->_has_template_content) {
                $skin_options[''] = __('Default');
            }

            foreach ($skins as $skin_id => $skin) {
                $skin_options[$skin_id] = $skin->getTitle();
            }

            // Get the first item for default value
            $default_value = array_keys($skin_options);
            $default_value = array_shift($default_value);

            if (1 >= count($skin_options)) {
                $this->addControl(
                    '_skin',
                    [
                        'label' => __('Skin'),
                        'type' => ControlsManager::HIDDEN,
                        'default' => $default_value,
                    ]
                );
            } else {
                $this->addControl(
                    '_skin',
                    [
                        'label' => __('Skin'),
                        'type' => ControlsManager::SELECT,
                        'default' => $default_value,
                        'options' => $skin_options,
                    ]
                );
            }
        }
    }

    /**
     * Register widget skins.
     *
     * This method is activated while initializing the widget base class. It is
     * used to assign skins to widgets with `addSkin()` method.
     *
     * Usage:
     *
     *    protected function _registerSkins() {
     *        $this->addSkin( new SkinClassic( $this ) );
     *    }
     *
     * @since 1.7.12
     */
    protected function _registerSkins()
    {
    }

    /**
     * Get initial config.
     *
     * Retrieve the current widget initial configuration.
     *
     * Adds more configuration on top of the controls list, the tabs assigned to
     * the control, element name, type, icon and more. This method also adds
     * widget type, keywords and categories.
     *
     * @since 2.9.0
     *
     * @return array The initial widget config
     */
    protected function getInitialConfig()
    {
        $config = [
            'widget_type' => $this->getName(),
            'keywords' => $this->getKeywords(),
            'categories' => $this->getCategories(),
            'html_wrapper_class' => $this->getHtmlWrapperClass(),
            'show_in_panel' => $this->showInPanel(),
            'is_dynamic_content' => $this->isDynamicContent(),
        ];

        $stack = Plugin::$instance->controls_manager->getElementStack($this);

        if ($stack) {
            $config['controls'] = $this->getStack(false)['controls'];
            $config['tabs_controls'] = $this->getTabsControls();
        }

        return array_merge(parent::getInitialConfig(), $config);
    }

    /**
     * @since 2.3.1
     */
    protected function shouldPrintEmpty()
    {
        return false;
    }

    /**
     * Print widget content template.
     *
     * Used to generate the widget content template on the editor, using a
     * Backbone JavaScript template.
     *
     * @since 2.0.0
     *
     * @param string $template_content Template content
     */
    protected function printTemplateContent($template_content)
    {
        ?>
        <div class="elementor-widget-container"><?php echo $template_content; ?></div>
        <?php
    }

    /**
     * Parse text editor.
     *
     * Parses the content from rich text editor with shortcodes.
     *
     * @since 1.0.0
     *
     * @param string $content Text editor content
     *
     * @return string Parsed content
     */
    protected function parseTextEditor($content)
    {
        /* This filter is documented in wp-includes/widgets/class-wp-widget-text.php */
        $content = apply_filters('widget_text', $content, $this->getSettings());

        if ('renderSmarty' === self::$render_method) {
            return $content;
        }

        // if ($GLOBALS['wp_embed'] instanceof \WP_Embed) {
        //     $content = $GLOBALS['wp_embed']->autoembed($content);
        // }

        return 'render' === self::$render_method ? do_shortcode($content) : $content;
    }

    /**
     * Get HTML wrapper class.
     *
     * Retrieve the widget container class. Can be used to override the
     * container class for specific widgets.
     *
     * @since 2.0.9
     */
    protected function getHtmlWrapperClass()
    {
        return 'elementor-widget-' . $this->getName();
    }

    /**
     * Add widget render attributes.
     *
     * Used to add attributes to the current widget wrapper HTML tag.
     *
     * @since 1.0.0
     */
    protected function _addRenderAttributes()
    {
        parent::_addRenderAttributes();

        $this->addRenderAttribute('_wrapper', 'class', [
            'elementor-widget',
            $this->getHtmlWrapperClass(),
        ]);

        $this->addRenderAttribute('_wrapper', 'data-widget_type', $this->getName() . '.' . ($this->getSettings('_skin') ?: 'default'));
    }

    // public function addLightboxDataToImageLink($link_html, $id)

    /**
     * Add Light-Box attributes.
     *
     * Used to add Light-Box-related data attributes to links that open media files.
     *
     * @param array|string $element The link HTML element
     * @param int $id The ID of the image
     * @param string $lightbox_setting_key The setting key that dictates weather to open the image in a lightbox
     * @param string $group_id Unique ID for a group of lightbox images
     * @param bool $overwrite Optional. Whether to overwrite existing
     *                        attribute. Default is false, not to overwrite.
     *
     * @return WidgetBase Current instance of the widget
     *
     * @since 2.9.0
     */
    public function addLightboxDataAttributes($element, $id = null, $lightbox_setting_key = null, $group_id = null, $overwrite = false)
    {
        $general_settings_model = SettingsManager::getSettingsManagers('general')->getModel();
        $is_global_image_lightbox_enabled = (bool) $general_settings_model->getSettings('elementor_global_image_lightbox');

        if ('no' === $lightbox_setting_key) {
            if ($is_global_image_lightbox_enabled) {
                $this->addRenderAttribute($element, 'data-elementor-open-lightbox', 'no');
            }

            return $this;
        }

        if ('yes' !== $lightbox_setting_key && !$is_global_image_lightbox_enabled) {
            return $this;
        }

        $attributes['data-elementor-open-lightbox'] = 'yes';

        if ($group_id) {
            $attributes['data-elementor-lightbox-slideshow'] = $group_id;
        }

        // if ($id) {
        //     $lightbox_image_attributes = Plugin::$instance->images_manager->getLightboxImageAttributes($id);

        //     if (isset($lightbox_image_attributes['title'])) {
        //         $attributes['data-elementor-lightbox-title'] = $lightbox_image_attributes['title'];
        //     }

        //     if (isset($lightbox_image_attributes['description'])) {
        //         $attributes['data-elementor-lightbox-description'] = $lightbox_image_attributes['description'];
        //     }
        // }

        $this->addRenderAttribute($element, $attributes, null, $overwrite);

        return $this;
    }

    /**
     * Render widget output on the frontend.
     *
     * Used to generate the final HTML displayed on the frontend.
     *
     * Note that if skin is selected, it will be rendered by the skin itself,
     * not the widget.
     *
     * @since 1.0.0
     */
    public function renderContent()
    {
        if (static::REMOTE_RENDER && is_admin() && 'render' === self::$render_method) {
            return print '<div class="ce-remote-render"></div>';
        }

        // do_action('elementor/widget/before_render_content', $this);

        ob_start();

        $skin = $this->getCurrentSkin();
        if ($skin) {
            $skin->setParent($this);
            $skin->{self::$render_method}();
        } else {
            $this->{self::$render_method}();
        }

        $widget_content = ob_get_clean();

        if (!$widget_content) {
            return;
        }
        // Tweak: Container attributes are extendable
        $this->addRenderAttribute('_container', 'class', 'elementor-widget-container');

        echo "<div {$this->getRenderAttributeString('_container')}>";

        // echo apply_filters('elementor/widget/render_content', $widget_content, $this);
        echo $widget_content;

        echo '</div>';
    }

    /**
     * Render widget smarty template.
     *
     * @since 2.5.10
     */
    protected function renderSmarty()
    {
        $this->render();
    }

    /**
     * Render widget plain content.
     *
     * Elementor saves the page content in a unique way, but it's not the way
     * PretaShop saves data. This method is used to save generated HTML to the
     * database as plain content the PretaShop way.
     *
     * When rendering plain content, it allows other PretaShop modules to
     * interact with the content - to search, check SEO and other purposes. It
     * also allows the site to keep working even if Elementor is deactivated.
     *
     * Note that if the widget uses shortcodes to display the data, the best
     * practice is to return the shortcode itself.
     *
     * Also note that if the widget don't display any content it should return
     * an empty string.
     *
     * @since 1.0.0
     */
    public function renderPlainContent()
    {
        $this->renderContent();
    }

    /**
     * Before widget rendering.
     *
     * Used to add stuff before the widget `_wrapper` element.
     *
     * @since 1.0.0
     */
    public function beforeRender()
    {
        ?>
        <div <?php $this->printRenderAttributeString('_wrapper'); ?>>
        <?php
    }

    /**
     * After widget rendering.
     *
     * Used to add stuff after the widget `_wrapper` element.
     *
     * @since 1.0.0
     */
    public function afterRender()
    {
        ?>
        </div>
        <?php
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
        $data = parent::getRawData($with_html_content);

        unset($data['isInner']);

        $data['widgetType'] = $this->getData('widgetType');

        if ($with_html_content) {
            ob_start();

            $this->renderContent();

            $data['htmlCache'] = ob_get_clean();
        }

        return $data;
    }

    /**
     * Print widget content.
     *
     * Output the widget final HTML on the frontend.
     *
     * @since 1.0.0
     */
    protected function _printContent()
    {
        $this->renderContent();
    }

    /**
     * Get default data.
     *
     * Retrieve the default widget data. Used to reset the data on initialization.
     *
     * @since 1.0.0
     *
     * @return array Default data
     */
    protected function getDefaultData()
    {
        $data = parent::getDefaultData();

        $data['widgetType'] = '';

        return $data;
    }

    /**
     * Get default child type.
     *
     * Retrieve the widget child type based on element data.
     *
     * @since 1.0.0
     *
     * @param array $element_data Widget ID
     *
     * @return array|false Child type or false if it's not a valid widget
     */
    protected function _getDefaultChildType(array $element_data)
    {
        return Plugin::$instance->elements_manager->getElementTypes('section');
    }

    /**
     * Get repeater setting key.
     *
     * Retrieve the unique setting key for the current repeater item. Used to connect the current element in the
     * repeater to it's settings model and it's control in the panel.
     *
     * @since 1.8.0
     *
     * @param string $setting_key The current setting key inside the repeater item (e.g. `tab_title`)
     * @param string $repeater_key The repeater key containing the array of all the items in the repeater (e.g. `tabs`)
     * @param int $repeater_item_index The current item index in the repeater array (e.g. `3`)
     *
     * @return string The repeater setting key (e.g. `tabs.3.tab_title`)
     */
    protected function getRepeaterSettingKey($setting_key, $repeater_key, $repeater_item_index)
    {
        return implode('.', [$repeater_key, $repeater_item_index, $setting_key]);
    }

    /**
     * Add inline editing attributes.
     *
     * Define specific area in the element to be editable inline. The element can have several areas, with this method
     * you can set the area inside the element that can be edited inline. You can also define the type of toolbar the
     * user will see, whether it will be a basic toolbar or an advanced one.
     *
     * Note: When you use wysiwyg control use the advanced toolbar, with textarea control use the basic toolbar. Text
     * control should not have toolbar.
     *
     * PHP usage (inside `WidgetBase::render()` method):
     *
     *    $this->addInlineEditingAttributes( 'text', 'advanced' );
     *    echo '<div ' . $this->getRenderAttributeString( 'text' ) . '>' . $this->getSettings( 'text' ) . '</div>';
     *
     * @since 1.8.0
     *
     * @param string $key Element key
     * @param string $toolbar Optional. Toolbar type. Accepted values are `advanced`, `basic` or `none`. Default is
     *                        `basic`.
     */
    protected function addInlineEditingAttributes($key, $toolbar = 'basic')
    {
        if (!Plugin::$instance->editor->isEditMode()) {
            return;
        }

        $this->addRenderAttribute($key, [
            'class' => 'elementor-inline-editing',
            'data-elementor-setting-key' => $key,
        ]);

        if ('basic' !== $toolbar) {
            $this->addRenderAttribute($key, [
                'data-elementor-inline-editing-toolbar' => $toolbar,
            ]);
        }
    }

    /**
     * Add new skin.
     *
     * Register new widget skin to allow the user to set custom designs. Must be
     * called inside the `_register_skins()` method.
     *
     * @since 1.0.0
     *
     * @param SkinBase $skin Skin instance
     */
    public function addSkin(SkinBase $skin)
    {
        Plugin::$instance->skins_manager->addSkin($this, $skin);
    }

    /**
     * Get single skin.
     *
     * Retrieve a single skin based on skin ID, from all the skin assigned to
     * the widget. If the skin does not exist or not assigned to the widget,
     * return false.
     *
     * @since 1.0.0
     *
     * @param string $skin_id Skin ID
     *
     * @return string|false Single skin, or false
     */
    public function getSkin($skin_id)
    {
        $skins = $this->getSkins();
        if (isset($skins[$skin_id])) {
            return $skins[$skin_id];
        }

        return false;
    }

    /**
     * Get current skin ID.
     *
     * Retrieve the ID of the current skin.
     *
     * @since 1.0.0
     *
     * @return string Current skin
     */
    public function getCurrentSkinId()
    {
        return $this->getSettings('_skin');
    }

    /**
     * Get current skin.
     *
     * Retrieve the current skin, or if non exist return false.
     *
     * @since 1.0.0
     *
     * @return SkinBase|false Current skin or false
     */
    public function getCurrentSkin()
    {
        return $this->getSkin($this->getCurrentSkinId());
    }

    /**
     * Remove widget skin.
     *
     * Unregister an existing skin and remove it from the widget.
     *
     * @since 1.0.0
     *
     * @param string $skin_id Skin ID
     *
     * @return WPError|true Whether the skin was removed successfully from the widget
     */
    public function removeSkin($skin_id)
    {
        return Plugin::$instance->skins_manager->removeSkin($this, $skin_id);
    }

    /**
     * Get widget skins.
     *
     * Retrieve all the skin assigned to the widget.
     *
     * @since 1.0.0
     *
     * @return SkinBase[]
     */
    public function getSkins()
    {
        return Plugin::$instance->skins_manager->getSkins($this);
    }

    /**
     * Init controls.
     *
     * Reset the `is_first_section` flag to true, so when the Stacks are cleared
     * all the controls will be registered again with their skins and settings.
     *
     * @since 2.10.0
     */
    protected function initControls()
    {
        $this->is_first_section = true;

        parent::initControls();
    }

    /**
     * @param string $plugin_title Plugin's title
     * @param string $since Plugin version widget was deprecated
     * @param string $last Plugin version in which the widget will be removed
     * @param string $replacement Widget replacement
     */
    protected function deprecatedNotice($plugin_title, $since, $last = '', $replacement = '')
    {
        $this->startControlsSection('Deprecated',
            [
                'label' => __('Deprecated'),
            ]
        );

        $this->addControl(
            'deprecated_notice',
            [
                'type' => ControlsManager::DEPRECATED_NOTICE,
                'widget' => $this->getTitle(),
                'since' => $since,
                'last' => $last,
                'plugin' => $plugin_title,
                'replacement' => $replacement,
            ]
        );

        $this->endControlsSection();
    }
}
