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
 * Elementor common widget.
 *
 * Elementor base widget that gives you all the advanced options of the basic
 * widget.
 *
 * @since 1.0.0
 */
class WidgetCommon extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve common widget name.
     *
     * @since 1.0.0
     *
     * @return string Widget name
     */
    public function getName()
    {
        return 'common';
    }

    /**
     * Show in panel.
     *
     * Whether to show the common widget in the panel or not.
     *
     * @since 1.0.0
     *
     * @return bool Whether to show the widget in the panel
     */
    public function showInPanel()
    {
        return false;
    }

    /**
     * Register common widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            '_section_style',
            [
                'label' => __('Layout'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        // Element Name for the Navigator
        $this->addControl(
            '_title',
            [
                'label' => __('Title'),
                'type' => ControlsManager::HIDDEN,
                'render_type' => 'none',
            ]
        );

        $this->addResponsiveControl(
            '_margin',
            [
                'label' => __('Margin'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-widget-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-widget-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_element_width',
            [
                'label' => __('Width'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'inherit' => __('Full Width') . ' (100%)',
                    'auto' => __('Inline') . ' (auto)',
                    'initial' => __('Custom'),
                    'calc' => __('Calculate'),
                ],
                'selectors_dictionary' => [
                    'inherit' => '100%',
                    'calc' => 'initial',
                ],
                'prefix_class' => 'elementor-widget%s__width-',
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{VALUE}}; max-width: {{VALUE}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_element_custom_width',
            [
                'label' => __('Custom Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    '_element_width' => 'initial',
                ],
                'device_args' => [
                    ControlsStack::RESPONSIVE_TABLET => [
                        'condition' => [
                            '_element_width_tablet' => 'initial',
                        ],
                    ],
                    ControlsStack::RESPONSIVE_MOBILE => [
                        'condition' => [
                            '_element_width_mobile' => 'initial',
                        ],
                    ],
                ],
                'size_units' => ['px', '%', 'vw'],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_element_calc_width',
            [
                'label' => 'Calc',
                'type' => ControlsManager::TEXT,
                'placeholder' => __('e.g:') . ' 100% - 20px',
                'condition' => [
                    '_element_width' => 'calc',
                ],
                'device_args' => [
                    ControlsStack::RESPONSIVE_TABLET => [
                        'condition' => [
                            '_element_width_tablet' => 'calc',
                        ],
                    ],
                    ControlsStack::RESPONSIVE_MOBILE => [
                        'condition' => [
                            '_element_width_mobile' => 'calc',
                        ],
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: calc({{VALUE}}); max-width: calc({{VALUE}})',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_element_min_width',
            [
                'label' => __('Min Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    '_element_width' => 'calc',
                ],
                'device_args' => [
                    ControlsStack::RESPONSIVE_TABLET => [
                        'condition' => [
                            '_element_width_tablet' => 'calc',
                        ],
                    ],
                    ControlsStack::RESPONSIVE_MOBILE => [
                        'condition' => [
                            '_element_width_mobile' => 'calc',
                        ],
                    ],
                ],
                'size_units' => ['px', '%', 'vw'],
                'selectors' => [
                    '{{WRAPPER}}' => 'min-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_element_vertical_align',
            [
                'label' => __('Vertical Align'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Start'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => __('End'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'stretch' => [
                        'title' => __('Stretch'),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                ],
                'condition' => [
                    '_position' => '',
                    '_element_width!' => '',
                ],
                'device_args' => [
                    ControlsStack::RESPONSIVE_TABLET => [
                        'condition' => [
                            '_position' => '',
                            '_element_width_tablet!' => '',
                        ],
                    ],
                    ControlsStack::RESPONSIVE_MOBILE => [
                        'condition' => [
                            '_position' => '',
                            '_element_width_mobile!' => '',
                        ],
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'align-self: {{VALUE}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_flex_order',
            [
                'label' => __('Order'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'start' => [
                        'title' => __('Start'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => __('End'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'custom' => [
                        'title' => __('Custom'),
                        'icon' => 'eicon-ellipsis-v',
                    ],
                ],
                'selectors_dictionary' => [
                    'start' => 'order: -99999;',
                    'end' => 'order: 99999;',
                    'custom' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '{{VALUE}}',
                ],
                'condition' => [
                    '_position' => '',
                ],
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            '_flex_order_custom',
            [
                'label' => __('Custom Order'),
                'type' => ControlsManager::NUMBER,
                'condition' => [
                    '_position' => '',
                    '_flex_order' => 'custom',
                ],
                'device_args' => [
                    ControlsStack::RESPONSIVE_TABLET => [
                        'condition' => [
                            '_position' => '',
                            '_flex_order_tablet' => 'custom',
                        ],
                    ],
                    ControlsStack::RESPONSIVE_MOBILE => [
                        'condition' => [
                            '_position' => '',
                            '_flex_order_mobile' => 'custom',
                        ],
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'order: {{VALUE}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_flex_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'none' => [
                        'title' => __('None'),
                        'icon' => 'eicon-ban',
                    ],
                    'grow' => [
                        'title' => __('Grow'),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                    'custom' => [
                        'title' => __('Custom'),
                        'icon' => 'eicon-ellipsis-v',
                    ],
                ],
                'selectors_dictionary' => [
                    'none' => 'flex-grow: 0;',
                    'grow' => 'flex-grow: 1;',
                    'custom' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '{{VALUE}}',
                ],
                'condition' => [
                    '_position' => '',
                    '_element_width!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_flex_grow',
            [
                'label' => __('Grow'),
                'type' => ControlsManager::NUMBER,
                'min' => 0,
                'default' => 1,
                'condition' => [
                    '_position' => '',
                    '_element_width!' => '',
                    '_flex_size' => 'custom',
                ],
                'device_args' => [
                    ControlsStack::RESPONSIVE_TABLET => [
                        'condition' => [
                            '_position' => '',
                            '_element_width_tablet!' => '',
                            '_flex_size_tablet' => 'custom',
                        ],
                    ],
                    ControlsStack::RESPONSIVE_MOBILE => [
                        'condition' => [
                            '_position' => '',
                            '_element_width_mobile!' => '',
                            '_flex_size_mobile' => 'custom',
                        ],
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'flex-grow: {{VALUE}};',
                ],
            ]
        );

        ($is_admin = is_admin()) && $this->addControl(
            '_position_description',
            [
                'raw' => '<strong>' . __('Please note!') . '</strong> ' .
                    __('Custom positioning is not considered best practice for responsive web design and should not be used too frequently.'),
                'type' => ControlsManager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'render_type' => 'ui',
                'condition' => [
                    '_position!' => '',
                ],
            ]
        );

        $this->addControl(
            '_position',
            [
                'label' => __('Position'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'absolute' => __('Absolute'),
                    'fixed' => __('Fixed'),
                ],
                'prefix_class' => 'elementor-',
                'frontend_available' => true,
            ]
        );

        $start = is_rtl() ? __('Right') : __('Left');
        $end = !is_rtl() ? __('Right') : __('Left');

        $this->addControl(
            '_offset_orientation_h',
            [
                'label' => __('Horizontal Orientation'),
                'type' => ControlsManager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
                'condition' => [
                    '_position!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_offset_x',
            [
                'label' => __('Offset'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => '0',
                ],
                'size_units' => ['px', '%', 'vw', 'vh'],
                'selectors' => [
                    'body:not(.lang-rtl) {{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}}',
                    'body.lang-rtl {{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    '_offset_orientation_h!' => 'end',
                    '_position!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_offset_x_end',
            [
                'label' => __('Offset'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => '0',
                ],
                'size_units' => ['px', '%', 'vw', 'vh'],
                'selectors' => [
                    'body:not(.lang-rtl) {{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}}',
                    'body.lang-rtl {{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    '_offset_orientation_h' => 'end',
                    '_position!' => '',
                ],
            ]
        );

        $this->addControl(
            '_offset_orientation_v',
            [
                'label' => __('Vertical Orientation'),
                'type' => ControlsManager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => __('Top'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => __('Bottom'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
                'condition' => [
                    '_position!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_offset_y',
            [
                'label' => __('Offset'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'vh', 'vw'],
                'default' => [
                    'size' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    '_offset_orientation_v!' => 'end',
                    '_position!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_offset_y_end',
            [
                'label' => __('Offset'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'vh', 'vw'],
                'default' => [
                    'size' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    '_offset_orientation_v' => 'end',
                    '_position!' => '',
                ],
            ]
        );

        $this->addControl(
            '_z_index',
            [
                'label' => __('Z-Index'),
                'type' => ControlsManager::NUMBER,
                'min' => 0,
                'selectors' => [
                    '{{WRAPPER}}' => 'z-index: {{VALUE}};',
                ],
                'separator' => 'before',
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
            '_css_classes',
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

        $this->endControlsSection();

        $this->startControlsSection(
            'section_effects',
            [
                'label' => __('Motion Effects'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->addResponsiveControl(
            '_animation',
            [
                'label' => __('Entrance Animation'),
                'type' => ControlsManager::ANIMATION,
                'frontend_available' => true,
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
                    '_animation!' => ['', 'none'],
                ],
            ]
        );

        $this->addControl(
            '_animation_delay',
            [
                'label' => __('Animation Delay') . ' (ms)',
                'type' => ControlsManager::NUMBER,
                'min' => 0,
                'step' => 100,
                'condition' => [
                    '_animation!' => ['', 'none'],
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $this->endControlsSection();

        $this->registerTransformSection();

        $this->startControlsSection(
            '_section_background',
            [
                'label' => __('Background'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->startControlsTabs('_tabs_background');

        $this->startControlsTab(
            '_tab_background_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => '_background',
                'selector' => '{{WRAPPER}} > .elementor-widget-container',
            ]
        );

        $this->addGroupControl(
            GroupControlBackdropFilter::getType(),
            [
                'name' => '_backdrop_filters',
                'selector' => '{{WRAPPER}} > .elementor-widget-container',
                'condition' => [
                    '_background_background' => ['classic', 'gradient'],
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            '_tab_background_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => '_background_hover',
                'selector' => '{{WRAPPER}}:hover .elementor-widget-container',
            ]
        );

        $this->addControl(
            '_background_hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => '--e-background-transition-duration: {{SIZE}}s',
                ],
                'separator' => 'before',
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            '_section_border',
            [
                'label' => __('Border'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->startControlsTabs('_tabs_border');

        $this->startControlsTab(
            '_tab_border_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => '_border',
                'selector' => '{{WRAPPER}} > .elementor-widget-container',
            ]
        );

        $this->addResponsiveControl(
            '_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-widget-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => '_box_shadow',
                'selector' => '{{WRAPPER}} > .elementor-widget-container',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            '_tab_border_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => '_border_hover',
                'selector' => '{{WRAPPER}}:hover .elementor-widget-container',
            ]
        );

        $this->addResponsiveControl(
            '_border_radius_hover',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}}:hover > .elementor-widget-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => '_box_shadow_hover',
                'selector' => '{{WRAPPER}}:hover .elementor-widget-container',
            ]
        );

        $this->addControl(
            '_border_hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'separator' => 'before',
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => '--e-border-transition-duration: {{SIZE}}s',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            '_section_responsive',
            [
                'label' => __('Visibility'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $is_admin && $this->addControl(
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

    private function registerTransformSection()
    {
        $this->startControlsSection(
            '_section_transform',
            [
                'label' => __('Transform'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->startControlsTabs('_tabs_positioning');

        $transform_prefix_class = 'e-';
        $transform_return_value = 'transform';
        $transform_selectors = [
            '' => '.elementor-element-{{ID}} > .elementor-widget-container',
            '_hover' => '{{HOVER}}',
        ];

        foreach ($transform_selectors as $tab => $selector) {
            $this->startControlsTab(
                "_tab_positioning$tab",
                [
                    'label' => __(!$tab ? 'Normal' : 'Hover'),
                ]
            );

            if ('_hover' === $tab) {
                $this->addControl(
                    '_transform_trigger_hover',
                    [
                        'label' => __('Trigger by'),
                        'type' => ControlsManager::SELECT,
                        'options' => [
                            '' => __('Widget'),
                            'column' => __('Column'),
                            'section' => __('Section'),
                        ],
                    ]
                );
            }

            $this->addResponsiveControl(
                "_transform_opacity$tab",
                [
                    'label' => __('Opacity'),
                    'type' => ControlsManager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 1,
                            'step' => 0.01,
                        ],
                    ],
                    'selectors' => [
                        $selector => 'opacity: {{SIZE}};',
                    ],
                ]
            );

            $this->addControl(
                "_transform_rotate_popover$tab",
                [
                    'label' => __('Rotate'),
                    'type' => ControlsManager::POPOVER_TOGGLE,
                    'prefix_class' => $transform_prefix_class,
                    'return_value' => $transform_return_value,
                ]
            );

            $this->startPopover();

            $this->addResponsiveControl(
                "_transform_rotateZ_effect$tab",
                [
                    'label' => __('Rotate'),
                    'type' => ControlsManager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => -360,
                            'max' => 360,
                        ],
                    ],
                    'selectors' => [
                        $selector => '--e-transform-rotateZ: {{SIZE}}deg',
                    ],
                    'condition' => [
                        "_transform_rotate_popover$tab!" => '',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->addControl(
                "_transform_rotate_3d$tab",
                [
                    'label' => __('3D Rotate'),
                    'type' => ControlsManager::SWITCHER,
                    'selectors' => [
                        $selector => '--e-transform-rotateX: 1deg;  --e-transform-perspective: 20px;',
                    ],
                    'condition' => [
                        "_transform_rotate_popover$tab!" => '',
                    ],
                ]
            );

            $this->addResponsiveControl(
                "_transform_rotateX_effect$tab",
                [
                    'label' => __('Rotate X'),
                    'type' => ControlsManager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => -360,
                            'max' => 360,
                        ],
                    ],
                    'condition' => [
                        "_transform_rotate_3d$tab!" => '',
                        "_transform_rotate_popover$tab!" => '',
                    ],
                    'selectors' => [
                        $selector => '--e-transform-rotateX: {{SIZE}}deg;',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->addResponsiveControl(
                "_transform_rotateY_effect$tab",
                [
                    'label' => __('Rotate Y'),
                    'type' => ControlsManager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => -360,
                            'max' => 360,
                        ],
                    ],
                    'condition' => [
                        "_transform_rotate_3d$tab!" => '',
                        "_transform_rotate_popover$tab!" => '',
                    ],
                    'selectors' => [
                        $selector => '--e-transform-rotateY: {{SIZE}}deg;',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->addResponsiveControl(
                "_transform_perspective_effect$tab",
                [
                    'label' => __('Perspective'),
                    'type' => ControlsManager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 1000,
                        ],
                    ],
                    'condition' => [
                        "_transform_rotate_popover$tab!" => '',
                        "_transform_rotate_3d$tab!" => '',
                    ],
                    'selectors' => [
                        $selector => '--e-transform-perspective: {{SIZE}}px',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->endPopover();

            $this->addControl(
                "_transform_translate_popover$tab",
                [
                    'label' => __('Offset'),
                    'type' => ControlsManager::POPOVER_TOGGLE,
                    'prefix_class' => $transform_prefix_class,
                    'return_value' => $transform_return_value,
                ]
            );

            $this->startPopover();

            $this->addResponsiveControl(
                "_transform_translateX_effect$tab",
                [
                    'label' => __('Offset X'),
                    'type' => ControlsManager::SLIDER,
                    'size_units' => ['%', 'px'],
                    'range' => [
                        '%' => [
                            'min' => -100,
                        ],
                        'px' => [
                            'min' => -1000,
                            'max' => 1000,
                        ],
                    ],
                    'condition' => [
                        "_transform_translate_popover$tab!" => '',
                    ],
                    'selectors' => [
                        $selector => '--e-transform-translateX: {{SIZE}}{{UNIT}};',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->addResponsiveControl(
                "_transform_translateY_effect$tab",
                [
                    'label' => __('Offset Y'),
                    'type' => ControlsManager::SLIDER,
                    'size_units' => ['%', 'px'],
                    'range' => [
                        '%' => [
                            'min' => -100,
                        ],
                        'px' => [
                            'min' => -1000,
                            'max' => 1000,
                        ],
                    ],
                    'condition' => [
                        "_transform_translate_popover$tab!" => '',
                    ],
                    'selectors' => [
                        $selector => '--e-transform-translateY: {{SIZE}}{{UNIT}};',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->endPopover();

            $this->addControl(
                "_transform_scale_popover$tab",
                [
                    'label' => __('Scale'),
                    'type' => ControlsManager::POPOVER_TOGGLE,
                    'prefix_class' => $transform_prefix_class,
                    'return_value' => $transform_return_value,
                ]
            );

            $this->startPopover();

            $this->addControl(
                "_transform_keep_proportions$tab",
                [
                    'label' => __('Keep Proportions'),
                    'type' => ControlsManager::SWITCHER,
                    'default' => 'yes',
                    'render_type' => 'ui',
                ]
            );

            $this->addResponsiveControl(
                "_transform_scale_effect$tab",
                [
                    'label' => __('Scale'),
                    'type' => ControlsManager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 2,
                            'step' => 0.1,
                        ],
                    ],
                    'condition' => [
                        "_transform_scale_popover$tab!" => '',
                        "_transform_keep_proportions$tab!" => '',
                    ],
                    'selectors' => [
                        $selector => '--e-transform-scale: {{SIZE}};',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->addResponsiveControl(
                "_transform_scaleX_effect$tab",
                [
                    'label' => __('Scale X'),
                    'type' => ControlsManager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 2,
                            'step' => 0.1,
                        ],
                    ],
                    'condition' => [
                        "_transform_scale_popover$tab!" => '',
                        "_transform_keep_proportions$tab" => '',
                    ],
                    'selectors' => [
                        $selector => '--e-transform-scaleX: {{SIZE}};',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->addResponsiveControl(
                "_transform_scaleY_effect$tab",
                [
                    'label' => __('Scale Y'),
                    'type' => ControlsManager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 2,
                            'step' => 0.1,
                        ],
                    ],
                    'condition' => [
                        "_transform_scale_popover$tab!" => '',
                        "_transform_keep_proportions$tab" => '',
                    ],
                    'selectors' => [
                        $selector => '--e-transform-scaleY: {{SIZE}};',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->endPopover();

            $this->addControl(
                "_transform_skew_popover$tab",
                [
                    'label' => __('Skew'),
                    'type' => ControlsManager::POPOVER_TOGGLE,
                    'prefix_class' => $transform_prefix_class,
                    'return_value' => $transform_return_value,
                ]
            );

            $this->startPopover();

            $this->addResponsiveControl(
                "_transform_skewX_effect$tab",
                [
                    'label' => __('Skew X'),
                    'type' => ControlsManager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => -360,
                            'max' => 360,
                        ],
                    ],
                    'condition' => [
                        "_transform_skew_popover$tab!" => '',
                    ],
                    'selectors' => [
                        $selector => '--e-transform-skewX: {{SIZE}}deg;',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->addResponsiveControl(
                "_transform_skewY_effect$tab",
                [
                    'label' => __('Skew Y'),
                    'type' => ControlsManager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => -360,
                            'max' => 360,
                        ],
                    ],
                    'condition' => [
                        "_transform_skew_popover$tab!" => '',
                    ],
                    'selectors' => [
                        $selector => '--e-transform-skewY: {{SIZE}}deg;',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->endPopover();

            $this->addControl(
                "_transform_flipX_effect$tab",
                [
                    'label' => __('Flip Horizontal'),
                    'type' => ControlsManager::SWITCHER,
                    'return_value' => $transform_return_value,
                    'prefix_class' => $transform_prefix_class,
                    'selectors' => [
                        $selector => '--e-transform-flipX: -1',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->addControl(
                "_transform_flipY_effect$tab",
                [
                    'label' => __('Flip Vertical'),
                    'type' => ControlsManager::SWITCHER,
                    'return_value' => $transform_return_value,
                    'prefix_class' => $transform_prefix_class,
                    'selectors' => [
                        $selector => '--e-transform-flipY: -1',
                    ],
                    'frontend_available' => true,
                ]
            );

            if ('_hover' === $tab) {
                $this->addControl(
                    '_transform_transition_hover',
                    [
                        'label' => __('Transition Duration') . ' (ms)',
                        'type' => ControlsManager::SLIDER,
                        'range' => [
                            'px' => [
                                'min' => 100,
                                'max' => 10000,
                                'step' => 10,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} > .elementor-widget-container' => '--e-transform-transition-duration: {{SIZE}}ms',
                        ],
                    ]
                );

                $this->addControl(
                    '_transform_transition_delay_hover',
                    [
                        'label' => __('Transition Delay') . ' (ms)',
                        'type' => ControlsManager::SLIDER,
                        'range' => [
                            'px' => [
                                'max' => 5000,
                                'step' => 10,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} > .elementor-widget-container' => '--e-transform-transition-delay: {{SIZE}}ms',
                        ],
                    ]
                );
            }

            ${"transform_origin_conditions$tab"} = [
                [
                    'name' => "_transform_scale_popover$tab",
                    'operator' => '!=',
                    'value' => '',
                ],
                [
                    'name' => "_transform_rotate_popover$tab",
                    'operator' => '!=',
                    'value' => '',
                ],
                [
                    'name' => "_transform_flipX_effect$tab",
                    'operator' => '!=',
                    'value' => '',
                ],
                [
                    'name' => "_transform_flipY_effect$tab",
                    'operator' => '!=',
                    'value' => '',
                ],
            ];

            $this->endControlsTab();

            $transform_return_value .= '-hover';
        }

        $this->endControlsTabs();

        $transform_origin_conditions = [
            'relation' => 'or',
            'terms' => array_merge($transform_origin_conditions, $transform_origin_conditions_hover),
        ];

        // Will override motion effect transform-origin
        $this->addResponsiveControl(
            'motion_fx_transform_x_anchor_point',
            [
                'label' => __('X Anchor Point'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'conditions' => $transform_origin_conditions,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}}' => '--e-transform-origin-x: {{VALUE}}',
                ],
            ]
        );

        // Will override motion effect transform-origin
        $this->addResponsiveControl(
            'motion_fx_transform_y_anchor_point',
            [
                'label' => __('Y Anchor Point'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'top' => [
                        'title' => __('Top'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __('Bottom'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'conditions' => $transform_origin_conditions,
                'selectors' => [
                    '{{WRAPPER}}' => '--e-transform-origin-y: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsSection();
    }
}
