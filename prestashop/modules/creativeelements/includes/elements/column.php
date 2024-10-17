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

use CE\CoreXSchemesXManager as SchemesManager;

/**
 * Elementor column element.
 *
 * Elementor column handler class is responsible for initializing the column
 * element.
 *
 * @since 1.0.0
 */
class ElementColumn extends ElementBase
{
    /**
     * Get column name.
     *
     * Retrieve the column name.
     *
     * @since 1.0.0
     *
     * @return string Column name
     */
    public function getName()
    {
        return 'column';
    }

    /**
     * Get element type.
     *
     * Retrieve the element type, in this case `column`.
     *
     * @since 2.1.0
     * @static
     *
     * @return string The type
     */
    public static function getType()
    {
        return 'column';
    }

    /**
     * Get column title.
     *
     * Retrieve the column title.
     *
     * @since 1.0.0
     *
     * @return string Column title
     */
    public function getTitle()
    {
        return __('Column');
    }

    /**
     * Get column icon.
     *
     * Retrieve the column icon.
     *
     * @since 1.0.0
     *
     * @return string Column icon
     */
    public function getIcon()
    {
        return 'eicon-column';
    }

    protected function isDynamicContent()
    {
        return false;
    }

    /**
     * @since 2.5.9
     */
    protected function shouldPrintEmpty()
    {
        return !Helper::$section_stack[0] || !Helper::$section_stack[0]->getSettings('tabs');
    }

    /**
     * Get initial config.
     *
     * Retrieve the current section initial configuration.
     *
     * Adds more configuration on top of the controls list, the tabs assigned to
     * the control, element name, type, icon and more. This method also adds
     * section presets.
     *
     * @since 2.9.0
     *
     * @return array The initial config
     */
    protected function getInitialConfig()
    {
        $config = parent::getInitialConfig();

        $config['controls'] = $this->getControls();
        $config['tabs_controls'] = $this->getTabsControls();

        return $config;
    }

    public function isControlVisible($control, $values = null)
    {
        if (null === $values && !empty($control['check_section']) && Helper::$section_stack[0]) {
            // Use Tabbed Section settings
            $values = Helper::$section_stack[0]->getSettings();
        }

        return parent::isControlVisible($control, $values);
    }

    /**
     * Register column controls.
     *
     * Used to add new controls to the column element.
     *
     * @since 1.0.0
     */
    protected function _registerControls()
    {
        // Section Layout.
        $this->startControlsSection(
            'layout',
            [
                'label' => __('Layout'),
                'tab' => ControlsManager::TAB_LAYOUT,
            ]
        );

        $this->addControl(
            'tab_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['svg'],
                'render_type' => 'none',
                'style_transfer' => false,
                'check_section' => true,
                'condition' => [
                    'tabs!' => '',
                ],
            ]
        );

        // Element Name for the Navigator
        $this->addControl(
            '_title',
            [
                'label' => __('Title'),
                'type' => ControlsManager::TEXT,
                'render_type' => 'none',
                'style_transfer' => false,
            ]
        );

        $this->addResponsiveControl(
            '_inline_size',
            [
                'label' => __('Column Width') . ' (%)',
                'type' => ControlsManager::NUMBER,
                'min' => 2,
                'max' => 98,
                'required' => true,
                'device_args' => [
                    ControlsStack::RESPONSIVE_TABLET => [
                        'max' => 100,
                        'required' => false,
                    ],
                    ControlsStack::RESPONSIVE_MOBILE => [
                        'max' => 100,
                        'required' => false,
                    ],
                ],
                'min_affected_device' => [
                    ControlsStack::RESPONSIVE_DESKTOP => ControlsStack::RESPONSIVE_TABLET,
                    ControlsStack::RESPONSIVE_TABLET => ControlsStack::RESPONSIVE_TABLET,
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{VALUE}}%',
                ],
            ]
        );

        $this->addResponsiveControl(
            'content_position',
            [
                'label' => __('Vertical Align'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'top' => __('Top'),
                    'center' => __('Middle'),
                    'bottom' => __('Bottom'),
                    'space-between' => __('Space Between'),
                    'space-around' => __('Space Around'),
                    'space-evenly' => __('Space Evenly'),
                    'stretch' => __('Stretch'),
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    // TODO: The following line is for BC since 2.7.0
                    '.elementor-bc-flex-widget {{WRAPPER}}.elementor-column .elementor-column-wrap' => 'align-items: {{VALUE}}',
                    // This specificity is intended to make sure column css overwrites section css on vertical alignment (content_position)
                    '{{WRAPPER}}.elementor-column.elementor-element[data-element_type="column"] > .elementor-column-wrap.elementor-element-populated > .elementor-widget-wrap' => 'align-content: {{VALUE}}; align-items: {{VALUE}};',
                ],
                'prefix_class' => 'ce%s-valign-',
            ]
        );

        $this->addResponsiveControl(
            'align',
            [
                'label' => __('Horizontal Align'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'flex-start' => __('Start'),
                    'center' => __('Center'),
                    'flex-end' => __('End'),
                    'space-between' => __('Space Between'),
                    'space-around' => __('Space Around'),
                    'space-evenly' => __('Space Evenly'),
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-column > .elementor-column-wrap > .elementor-widget-wrap' => 'justify-content: {{VALUE}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'space_between_widgets',
            [
                'label' => __('Widgets Space') . ' (px)',
                'type' => ControlsManager::NUMBER,
                'placeholder' => 20,
                'selectors' => [
                    // Need the full path for exclude the inner section
                    '{{WRAPPER}} > .elementor-column-wrap > .elementor-widget-wrap > .elementor-widget:not(.elementor-widget__width-auto):not(.elementor-widget__width-initial, .elementor-widget__width-calc):not(:last-child):not(.elementor-absolute)' => 'margin-bottom: {{VALUE}}px',
                ],
            ]
        );

        $possible_tags = [
            'div',
            'header',
            'footer',
            'main',
            'article',
            'section',
            'aside',
            'nav',
        ];

        $options = [
            '' => __('Default'),
        ] + array_combine($possible_tags, $possible_tags);

        $this->addControl(
            'html_tag',
            [
                'label' => __('HTML Tag'),
                'type' => ControlsManager::SELECT,
                'options' => &$options,
                'render_type' => 'none',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style',
            [
                'label' => __('Background'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('tabs_background');

        $this->startControlsTab(
            'tab_background_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background',
                'types' => ['classic', 'gradient', 'slideshow'],
                'selector' => '{{WRAPPER}}:not(.elementor-motion-effects-element-type-background) > .elementor-column-wrap, ' .
                    '{{WRAPPER}} > .elementor-column-wrap > .elementor-motion-effects-container > .elementor-motion-effects-layer',
                'fields_options' => [
                    'background' => [
                        'frontend_available' => true,
                    ],
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_background_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background_hover',
                'selector' => '{{WRAPPER}}:hover > .elementor-element-populated',
            ]
        );

        $this->addControl(
            'background_hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'render_type' => 'ui',
                'separator' => 'before',
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        // Section Column Background Overlay.
        $this->startControlsSection(
            'section_background_overlay',
            [
                'label' => __('Background Overlay'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'background_background' => ['classic', 'gradient'],
                ],
            ]
        );

        $this->startControlsTabs('tabs_background_overlay');

        $this->startControlsTab(
            'tab_background_overlay_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background_overlay',
                'selector' => '{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay',
            ]
        );

        $this->addControl(
            'background_overlay_opacity',
            [
                'label' => __('Opacity'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => .5,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay' => 'opacity: {{SIZE}};',
                ],
                'condition' => [
                    'background_overlay_background' => ['classic', 'gradient'],
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay',
            ]
        );

        $this->addControl(
            'overlay_blend_mode',
            [
                'label' => __('Blend Mode'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Normal'),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-element-populated > .elementor-background-overlay' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_background_overlay_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background_overlay_hover',
                'selector' => '{{WRAPPER}}:hover > .elementor-element-populated >  .elementor-background-overlay',
            ]
        );

        $this->addControl(
            'background_overlay_hover_opacity',
            [
                'label' => __('Opacity'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => .5,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}:hover > .elementor-element-populated >  .elementor-background-overlay' => 'opacity: {{SIZE}};',
                ],
                'condition' => [
                    'background_overlay_hover_background' => ['classic', 'gradient'],
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'css_filters_hover',
                'selector' => '{{WRAPPER}}:hover > .elementor-element-populated >  .elementor-background-overlay',
            ]
        );

        $this->addControl(
            'background_overlay_hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'render_type' => 'ui',
                'separator' => 'before',
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_border',
            [
                'label' => __('Border'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('tabs_border');

        $this->startControlsTab(
            'tab_border_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} > .elementor-element-populated',
            ]
        );

        $this->addResponsiveControl(
            'border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-element-populated, ' .
                    '{{WRAPPER}} > .elementor-element-populated > .elementor-background-overlay, ' .
                    '{{WRAPPER}} > .elementor-element-populated > .elementor-motion-effects-container, ' .
                    '{{WRAPPER}} > .elementor-background-slideshow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} > .elementor-element-populated',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_border_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'border_hover',
                'selector' => '{{WRAPPER}}:hover > .elementor-element-populated',
            ]
        );

        $this->addResponsiveControl(
            'border_radius_hover',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}}:hover > .elementor-element-populated, {{WRAPPER}}:hover > .elementor-element-populated > .elementor-background-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'box_shadow_hover',
                'selector' => '{{WRAPPER}}:hover > .elementor-element-populated',
            ]
        );

        $this->addControl(
            'border_hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'separator' => 'before',
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'background_background',
                            'operator' => '!==',
                            'value' => '',
                        ],
                        [
                            'name' => 'border_border',
                            'operator' => '!==',
                            'value' => '',
                        ],
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-element-populated' => 'transition: background {{background_hover_transition.SIZE}}s, border {{SIZE}}s, border-radius {{SIZE}}s, box-shadow {{SIZE}}s',
                    '{{WRAPPER}} > .elementor-element-populated > .elementor-background-overlay' => 'transition: background {{background_overlay_hover_transition.SIZE}}s, border-radius {{SIZE}}s, opacity {{background_overlay_hover_transition.SIZE}}s',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        // Section Typography.
        $this->startControlsSection(
            'section_typo',
            [
                'label' => __('Typography'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        if (in_array(SchemeColor::getType(), SchemesManager::getEnabledSchemes(), true)) {
            $this->addControl(
                'colors_warning',
                [
                    'type' => ControlsManager::RAW_HTML,
                    'raw' => __('Note: The following set of controls has been deprecated. Those controls are only visible if they were previously populated.'),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
                ]
            );
        }

        $this->addControl(
            'heading_color',
            [
                'label' => __('Heading Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-element-populated .elementor-heading-title' => 'color: {{VALUE}};',
                ],
                'separator' => 'none',
            ]
        );

        $this->addControl(
            'color_text',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} > .elementor-element-populated' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'color_link',
            [
                'label' => __('Link Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-element-populated a:not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'color_link_hover',
            [
                'label' => __('Link Hover Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-element-populated a:not(#e):hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'text_align',
            [
                'label' => __('Text Align'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-element-populated' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();

        // Section Advanced.
        $this->startControlsSection(
            'section_advanced',
            [
                'label' => __('Advanced'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->addResponsiveControl(
            'margin',
            [
                'label' => __('Margin'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-element-populated' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-element-populated' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'z_index',
            [
                'label' => __('Z-Index'),
                'type' => ControlsManager::NUMBER,
                'min' => 0,
                'selectors' => [
                    '{{WRAPPER}}' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            '_element_id',
            [
                'label' => __('CSS ID'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    // 'active' => true,
                ],
                'title' => __('Add your custom id WITHOUT the Pound key. e.g: my-id'),
                'style_transfer' => false,
                'classes' => 'elementor-control-direction-ltr',
            ]
        );

        $this->addControl(
            'css_classes',
            [
                'label' => __('CSS Classes'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    // 'active' => true,
                ],
                'prefix_class' => '',
                'title' => __('Add your custom class WITHOUT the dot. e.g: my-class'),
                'classes' => 'elementor-control-direction-ltr',
            ]
        );

        // TODO: Backward comparability for deprecated controls
        $this->addControl(
            'screen_sm',
            [
                'type' => ControlsManager::HIDDEN,
            ]
        );

        $this->addControl(
            'screen_sm_width',
            [
                'type' => ControlsManager::HIDDEN,
                'condition' => [
                    'screen_sm' => ['custom'],
                ],
                'prefix_class' => 'elementor-sm-',
            ]
        );
        // END Backward comparability

        $this->endControlsSection();

        $this->startControlsSection(
            'section_effects',
            [
                'label' => __('Motion Effects'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->addResponsiveControl(
            'animation',
            [
                'label' => __('Entrance Animation'),
                'type' => ControlsManager::ANIMATION,
                'frontend_available' => true,
                'exclude' => [
                    'Revealing',
                    'Sliding & Revealing',
                    'Scaling & Revealing',
                ],
            ]
        );

        $this->addControl(
            'animation_duration',
            [
                'label' => __('Animation Duration'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'slow' => __('Slow'),
                    '' => __('Normal'),
                    'fast' => __('Fast'),
                ],
                'prefix_class' => 'animated-',
                'condition' => [
                    'animation!' => ['', 'none'],
                ],
            ]
        );

        $this->addControl(
            'animation_delay',
            [
                'label' => __('Animation Delay') . ' (ms)',
                'type' => ControlsManager::NUMBER,
                'min' => 0,
                'step' => 100,
                'condition' => [
                    'animation!' => ['', 'none'],
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            '_section_responsive',
            [
                'label' => __('Visibility'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        is_admin() && $this->addControl(
            'responsive_description',
            [
                'raw' => '<strong>' . __('Please note!') . '</strong> ' .
                    __('These options are not considered best practice for responsive web design and should not be used too frequently.'),
                'type' => ControlsManager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'hide_desktop',
                            'value' => 'hidden-desktop',
                        ],
                        [
                            'name' => 'hide_tablet',
                            'value' => 'hidden-tablet',
                        ],
                        [
                            'name' => 'hide_mobile',
                            'value' => 'hidden-phone',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'hide_desktop',
            [
                'label' => __('Hide On Desktop'),
                'type' => ControlsManager::SWITCHER,
                'prefix_class' => 'elementor-',
                'label_on' => __('Hide'),
                'label_off' => __('Show'),
                'return_value' => 'hidden-desktop',
            ]
        );

        $this->addControl(
            'hide_tablet',
            [
                'label' => __('Hide On Tablet'),
                'type' => ControlsManager::SWITCHER,
                'prefix_class' => 'elementor-',
                'label_on' => __('Hide'),
                'label_off' => __('Show'),
                'return_value' => 'hidden-tablet',
            ]
        );

        $this->addControl(
            'hide_mobile',
            [
                'label' => __('Hide On Mobile'),
                'type' => ControlsManager::SWITCHER,
                'prefix_class' => 'elementor-',
                'label_on' => __('Hide'),
                'label_off' => __('Show'),
                'return_value' => 'hidden-phone',
                'description' => __('Responsive visibility will take effect only on preview or live page, and not while editing in Creative Elements.'),
            ]
        );

        $this->endControlsSection();

        // Plugin::$instance->controls_manager->addCustomAttributesControls($this);

        Plugin::$instance->controls_manager->addCustomCssControls($this);
    }

    /**
     * Render column output in the editor.
     *
     * Used to generate the live preview, using a Backbone JavaScript template.
     *
     * @since 2.9.0
     */
    protected function contentTemplate()
    {
        ?>
        <# view._index || view.$el.addClass('elementor-active') #>
        <div class="elementor-column-wrap">
            <div class="elementor-background-overlay"></div>
            <div class="elementor-widget-wrap"></div>
        </div>
        <?php
    }

    /**
     * Before column rendering.
     *
     * Used to add stuff before the column element.
     *
     * @since 1.0.0
     */
    public function beforeRender()
    {
        $settings = $this->getSettingsForDisplay();

        $has_background_overlay = in_array($settings['background_overlay_background'], ['classic', 'gradient'], true)
            || in_array($settings['background_overlay_hover_background'], ['classic', 'gradient'], true);

        $column_wrap_classes = ['elementor-column-wrap'];

        if ($this->getChildren()) {
            $column_wrap_classes[] = 'elementor-element-populated';
        }

        $section = Helper::$section_stack[0];

        if ($section && $section->getSettings('tabs') && array_search($this, $section->getChildren(), true) === Helper::getFirstTabIndex($section)) {
            $this->addRenderAttribute('_wrapper', 'class', 'elementor-active');
        }

        $this->addRenderAttribute([
            '_inner_wrapper' => [
                'class' => $column_wrap_classes,
            ],
            '_widget_wrapper' => [
                'class' => ['elementor-widget-wrap'],
            ],
            '_background_overlay' => [
                'class' => ['elementor-background-overlay'],
            ],
        ]); ?>
        <<?php echo $this->getHtmlTag(); ?> <?php $this->printRenderAttributeString('_wrapper'); ?>>
            <div <?php $this->printRenderAttributeString('_inner_wrapper'); ?>>
        <?php if ($has_background_overlay) { ?>
            <div <?php $this->printRenderAttributeString('_background_overlay'); ?>></div>
        <?php } ?>
        <div <?php $this->printRenderAttributeString('_widget_wrapper'); ?>>
        <?php
    }

    /**
     * After column rendering.
     *
     * Used to add stuff after the column element.
     *
     * @since 1.0.0
     */
    public function afterRender()
    {
        ?>
                </div>
            </div>
        </<?php echo $this->getHtmlTag(); ?>>
        <?php
    }

    /**
     * Add column render attributes.
     *
     * Used to add attributes to the current column wrapper HTML tag.
     *
     * @since 1.3.0
     */
    protected function _addRenderAttributes()
    {
        parent::_addRenderAttributes();

        $is_inner = $this->getData('isInner');

        $column_type = !empty($is_inner) ? 'inner' : 'top';

        $this->addRenderAttribute('_wrapper', 'class', [
            'elementor-column',
            'elementor-col-' . (Helper::$section_stack[0] && Helper::$section_stack[0]->getSettings('tabs')
                ? 100
                : $this->getSettings('_column_size')
            ),
            'elementor-' . $column_type . '-column',
        ]);
        // Slideshow Optimization
        if ('slideshow' === $this->getSettings('background_background')
            && ($slideshow = $this->getSettings('background_slideshow_gallery'))
            && !empty($slideshow[0]['image']['url'])
        ) {
            $this->addRenderAttribute('_wrapper', 'style', 'background-image: url("' . Helper::getMediaLink($slideshow[0]['image']['url']) . '");');
        }
    }

    /**
     * Get default child type.
     *
     * Retrieve the column child type based on element data.
     *
     * @since 1.0.0
     *
     * @param array $element_data Element ID
     *
     * @return ElementBase Column default child type
     */
    protected function _getDefaultChildType(array $element_data)
    {
        if ('section' === $element_data['elType']) {
            return Plugin::$instance->elements_manager->getElementTypes('section');
        }

        return Plugin::$instance->widgets_manager->getWidgetTypes($element_data['widgetType']);
    }

    /**
     * Get HTML tag.
     *
     * Retrieve the column element HTML tag.
     *
     * @since 1.5.3
     *
     * @return string Column HTML tag
     */
    private function getHtmlTag()
    {
        $html_tag = $this->getSettings('html_tag');

        if (empty($html_tag)) {
            $html_tag = 'div';
        }

        return $html_tag;
    }
}
