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

use CE\CoreXBaseXModule as BaseModule;
use CE\ModulesXMotionEffectsXControlsGroup as ControlsGroup;

class ModulesXMotionEffectsXModule extends BaseModule
{
    public function getName()
    {
        return 'motion-effects';
    }

    public function registerControlsGroup($controls_manager)
    {
        $controls_manager->addGroupControl(ControlsGroup::getType(), new ControlsGroup());
    }

    public function addFxControls(ElementBase $element)
    {
        $exclude = [];

        $selector = '{{WRAPPER}}';

        if ($element instanceof ElementSection) {
            $exclude[] = 'motion_fx_mouse';
        } elseif ($element instanceof ElementColumn) {
            $selector .= ' > .elementor-column-wrap';
        } else {
            $selector .= ' > .elementor-widget-container';
        }

        $element->addGroupControl(
            ControlsGroup::getType(),
            [
                'name' => 'motion_fx',
                'selector' => $selector,
                'exclude' => $exclude,
            ]
        );

        $element->addControl(
            'motion_fx_divider',
            [
                'type' => ControlsManager::DIVIDER,
            ]
        );
    }

    public function addBackgroundFxControls(ElementBase $element)
    {
        $element->startInjection([
            'of' => 'background_bg_width_mobile',
        ]);

        $element->addGroupControl(
            GroupControlBackdropFilter::getType(),
            [
                'name' => 'backdrop_filters',
                'selector' => $element instanceof ElementSection ? '{{WRAPPER}}' : '{{WRAPPER}} > .elementor-column-wrap',
                'condition' => [
                    'background_background' => ['classic', 'gradient'],
                ],
            ]
        );

        $element->addGroupControl(
            ControlsGroup::getType(),
            [
                'name' => 'background_motion_fx',
                'exclude' => [
                    'rotateZ_effect',
                    'tilt_effect',
                    'transform_origin_x',
                    'transform_origin_y',
                ],
            ]
        );

        $options = [
            'separator' => 'before',
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'name' => 'background_background',
                        'value' => 'classic',
                    ],
                    [
                        'terms' => [
                            [
                                'name' => 'background_background',
                                'value' => 'gradient',
                            ],
                            [
                                'name' => 'background_color',
                                'operator' => '!==',
                                'value' => '',
                            ],
                            [
                                'name' => 'background_color_b',
                                'operator' => '!==',
                                'value' => '',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $element->updateControl('background_motion_fx_motion_fx_scrolling', $options);

        $element->updateControl('background_motion_fx_motion_fx_mouse', $options);

        $element->endInjection();

        $wrapper = $element->getName() === 'column' ? '{{WRAPPER}} > .elementor-column-wrap' : '{{WRAPPER}}';

        $element->addControl(
            'background_motion_fx_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'conditions' => [
                    'terms' => [
                        $options['conditions'],
                        [
                            'name' => 'background_motion_fx_motion_fx_scrolling',
                            'operator' => '!==',
                            'value' => '',
                        ],
                    ],
                ],
                'selectors' => [
                    "$wrapper > .elementor-motion-effects-container > .elementor-motion-effects-layer" => 'transition-duration: {{SIZE}}s',
                ],
            ],
            [
                'position' => [
                    'of' => 'background_motion_fx_devices',
                    'at' => 'before',
                ],
            ]
        );
    }

    public function addStickyControls(ElementBase $element)
    {
        $element->addControl(
            'sticky',
            [
                'label' => __('Sticky'),
                'type' => ControlsManager::CHOOSE,
                'toggle' => false,
                'options' => [
                    '' => [
                        'title' => __('None'),
                        'icon' => 'eicon-ban',
                    ],
                    'top' => [
                        'title' => __('Top'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => __('Bottom'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => '',
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->addControl(
            'sticky_on',
            [
                'label' => __('Sticky On'),
                'label_block' => 'true',
                'type' => ControlsManager::SELECT2,
                'multiple' => true,
                'default' => ['desktop', 'tablet', 'mobile'],
                'options' => [
                    'desktop' => __('Desktop'),
                    'tablet' => __('Tablet'),
                    'mobile' => __('Mobile'),
                ],
                'condition' => [
                    'sticky!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->addControl(
            'sticky_offset',
            [
                'label' => __('Offset'),
                'type' => ControlsManager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 500,
                'required' => true,
                'condition' => [
                    'sticky!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->addControl(
            'sticky_dynamic_offset',
            [
                'label' => __('Dynamic Offset'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('CSS Selector'),
                'condition' => [
                    'sticky!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->addControl(
            'sticky_effects_offset',
            [
                'label' => __('Effects Offset'),
                'type' => ControlsManager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 1000,
                'required' => true,
                'condition' => [
                    'sticky!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $is_section = $element instanceof ElementSection;

        if ($is_section || $element instanceof WidgetBase) {
            $condition = [
                'sticky!' => '',
            ];

            if ($is_section && Plugin::$instance->editor->isEditMode()) {
                $condition['isInner'] = true;
            }

            $element->addControl(
                'sticky_parent',
                [
                    'label' => __('Stay In Column'),
                    'type' => ControlsManager::SWITCHER,
                    'condition' => $condition,
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );
        }

        $element->addControl(
            'sticky_auto_hide',
            [
                'label' => __('Hide on Scroll'),
                'type' => ControlsManager::POPOVER_TOGGLE,
                'condition' => [
                    'sticky' => 'top',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->startPopover();

        $element->addControl(
            'sticky_auto_hide_offset',
            [
                'label' => __('Offset'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1600,
                    ],
                ],
                'size_units' => ['px', 'vh'],
                'default' => [
                    'size' => 0,
                ],
                'condition' => [
                    'sticky' => 'top',
                    'sticky_auto_hide!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->addControl(
            'sticky_auto_hide_duration',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    's' => [
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.3,
                    'unit' => 's',
                ],
                'condition' => [
                    'sticky' => 'top',
                    'sticky_auto_hide!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->endPopover();

        $element->addControl(
            'sticky_divider',
            [
                'type' => ControlsManager::DIVIDER,
            ]
        );
    }

    public function addAnimationControls(ElementBase $element)
    {
        $animation = $element instanceof WidgetBase ? '_animation' : 'animation';
        $controls = [
            "{$animation}_offset" => [
                'label' => __('Offset'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}}' => '--ce-animation-offset: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    $animation => [
                        'fadeInDown',
                        'fadeInLeft',
                        'fadeInRight',
                        'fadeInUp',
                        'slideInDown',
                        'slideInLeft',
                        'slideInRight',
                        'slideInUp',
                    ],
                ],
            ],
            "{$animation}_scale" => [
                'label' => __('Scale'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--ce-animation-scale: {{SIZE}};',
                ],
                'condition' => [
                    $animation => [
                        'zoomIn',
                        'zoomInDown',
                        'zoomInLeft',
                        'zoomInRight',
                        'zoomInUp',
                        'scaleReveal',
                        'scaleRevealFromDown',
                        'scaleRevealFromLeft',
                        'scaleRevealFromRight',
                        'scaleRevealFromUp',
                    ],
                ],
            ],
            "{$animation}_rotate" => [
                'label' => __('Rotate'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -360,
                        'max' => 360,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--ce-animation-rotate: {{SIZE}}deg;',
                ],
                'condition' => [
                    $animation => [
                        'rotateIn',
                        'rotateInDownLeft',
                        'rotateInDownRight',
                        'rotateInUpLeft',
                        'rotateInUpRight',
                    ],
                ],
            ],
        ];

        $element->startInjection([
            'of' => 'animation_duration',
            'at' => 'before',
        ]);

        foreach ($controls as $id => &$args) {
            $args['device_args'] = [
                ControlsStack::RESPONSIVE_TABLET => [
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => "{$animation}_tablet",
                                'operator' => 'in',
                                'value' => &$args['condition'][$animation],
                            ],
                            [
                                'terms' => [
                                    [
                                        'name' => "{$animation}_tablet",
                                        'value' => '',
                                    ],
                                    [
                                        'name' => $animation,
                                        'operator' => 'in',
                                        'value' => &$args['condition'][$animation],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                ControlsStack::RESPONSIVE_MOBILE => [
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => "{$animation}_mobile",
                                'operator' => 'in',
                                'value' => &$args['condition'][$animation],
                            ],
                            [
                                'terms' => [
                                    [
                                        'name' => "{$animation}_mobile",
                                        'value' => '',
                                    ],
                                    [
                                        'name' => "{$animation}_tablet",
                                        'operator' => 'in',
                                        'value' => &$args['condition'][$animation],
                                    ],
                                ],
                            ],
                            [
                                'terms' => [
                                    [
                                        'name' => "{$animation}_mobile",
                                        'value' => '',
                                    ],
                                    [
                                        'name' => "{$animation}_tablet",
                                        'value' => '',
                                    ],
                                    [
                                        'name' => $animation,
                                        'operator' => 'in',
                                        'value' => &$args['condition'][$animation],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ];
            $element->addResponsiveControl($id, $args);
        }

        $element->endInjection();
    }

    public function __construct()
    {
        add_action('elementor/controls/controls_registered', [$this, 'registerControlsGroup']);

        add_action('elementor/element/section/section_effects/after_section_start', [$this, 'addFxControls']);
        add_action('elementor/element/column/section_effects/after_section_start', [$this, 'addFxControls']);
        add_action('elementor/element/common/section_effects/after_section_start', [$this, 'addFxControls']);

        add_action('elementor/element/section/section_background/before_section_end', [$this, 'addBackgroundFxControls']);
        add_action('elementor/element/column/section_style/before_section_end', [$this, 'addBackgroundFxControls']);

        add_action('elementor/element/section/section_effects/after_section_start', [$this, 'addStickyControls']);
        add_action('elementor/element/common/section_effects/after_section_start', [$this, 'addStickyControls']);

        add_action('elementor/element/section/section_effects/before_section_end', [$this, 'addAnimationControls']);
        add_action('elementor/element/column/section_effects/before_section_end', [$this, 'addAnimationControls']);
        add_action('elementor/element/common/section_effects/before_section_end', [$this, 'addAnimationControls']);
    }
}
