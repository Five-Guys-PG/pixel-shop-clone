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

class ModulesXMotionEffectsXControlsGroup extends GroupControlBase
{
    protected static $fields;

    public static function getType()
    {
        return 'motion_fx';
    }

    protected function initFields()
    {
        $fields = [
            'motion_fx_scrolling' => [
                'label' => __('Scrolling Effects'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Off'),
                'label_on' => __('On'),
                'render_type' => 'ui',
                'frontend_available' => true,
            ],
        ];

        $this->prepareEffects('scrolling', $fields);

        $transform_origin_conditions = [
            'terms' => [
                [
                    'name' => 'motion_fx_scrolling',
                    'value' => 'yes',
                ],
                [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'rotateZ_effect',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'scale_effect',
                            'value' => 'yes',
                        ],
                    ],
                ],
            ],
        ];

        $fields['transform_origin_x'] = [
            'label' => __('X Anchor Point'),
            'type' => ControlsManager::CHOOSE,
            'default' => 'center',
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
            'label_block' => false,
            'toggle' => false,
            'render_type' => 'ui',
        ];

        $fields['transform_origin_y'] = [
            'label' => __('Y Anchor Point'),
            'type' => ControlsManager::CHOOSE,
            'default' => 'center',
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
                '{{SELECTOR}}' => 'transform-origin: {{transform_origin_x.VALUE}} {{VALUE}}',
            ],
            'label_block' => false,
            'toggle' => false,
        ];

        $fields['devices'] = [
            'label' => __('Apply Effects On'),
            'type' => ControlsManager::SELECT2,
            'multiple' => true,
            'label_block' => 'true',
            'default' => ['desktop', 'tablet', 'mobile'],
            'options' => [
                'desktop' => __('Desktop'),
                'tablet' => __('Tablet'),
                'mobile' => __('Mobile'),
            ],
            'condition' => [
                'motion_fx_scrolling!' => '',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ];

        $fields['range'] = [
            'label' => __('Effects Relative To'),
            'type' => ControlsManager::SELECT,
            'options' => [
                '' => __('Default'),
                'viewport' => __('Viewport'),
                'page' => __('Entire Page'),
            ],
            'condition' => [
                'motion_fx_scrolling!' => '',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ];

        $fields['motion_fx_mouse'] = [
            'label' => __('Mouse Effects'),
            'type' => ControlsManager::SWITCHER,
            'label_off' => __('Off'),
            'label_on' => __('On'),
            'separator' => 'before',
            'render_type' => 'none',
            'frontend_available' => true,
        ];

        $this->prepareEffects('mouse', $fields);

        return $fields;
    }

    protected function getDefaultOptions()
    {
        return [
            'popover' => false,
        ];
    }

    private function getScrollingEffects()
    {
        return [
            'translateY' => [
                'label' => __('Vertical Scroll'),
                'fields' => [
                    'direction' => [
                        'label' => __('Direction'),
                        'type' => ControlsManager::SELECT,
                        'options' => [
                            '' => __('Up'),
                            'negative' => __('Down'),
                        ],
                    ],
                    'speed' => [
                        'label' => __('Speed'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'size' => 4,
                        ],
                        'range' => [
                            'px' => [
                                'max' => 10,
                                'step' => 0.1,
                            ],
                        ],
                    ],
                    'affectedRange' => [
                        'label' => __('Viewport'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'sizes' => [
                                'start' => 0,
                                'end' => 100,
                            ],
                            'unit' => '%',
                        ],
                        'labels' => [
                            __('Bottom'),
                            __('Top'),
                        ],
                        'scales' => 1,
                        'handles' => 'range',
                    ],
                ],
            ],
            'translateX' => [
                'label' => __('Horizontal Scroll'),
                'fields' => [
                    'direction' => [
                        'label' => __('Direction'),
                        'type' => ControlsManager::SELECT,
                        'options' => [
                            '' => __('Left'),
                            'negative' => __('Right'),
                        ],
                    ],
                    'speed' => [
                        'label' => __('Speed'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'size' => 4,
                        ],
                        'range' => [
                            'px' => [
                                'max' => 10,
                                'step' => 0.1,
                            ],
                        ],
                    ],
                    'affectedRange' => [
                        'label' => __('Viewport'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'sizes' => [
                                'start' => 0,
                                'end' => 100,
                            ],
                            'unit' => '%',
                        ],
                        'labels' => [
                            __('Bottom'),
                            __('Top'),
                        ],
                        'scales' => 1,
                        'handles' => 'range',
                    ],
                ],
            ],
            'opacity' => [
                'label' => __('Transparency'),
                'fields' => [
                    'direction' => [
                        'label' => __('Direction'),
                        'type' => ControlsManager::SELECT,
                        'default' => 'out-in',
                        'options' => [
                            'out-in' => 'Fade In',
                            'in-out' => 'Fade Out',
                            'in-out-in' => 'Fade Out In',
                            'out-in-out' => 'Fade In Out',
                        ],
                    ],
                    'level' => [
                        'label' => __('Level'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'size' => 10,
                        ],
                        'range' => [
                            'px' => [
                                'min' => 1,
                                'max' => 10,
                                'step' => 0.1,
                            ],
                        ],
                    ],
                    'range' => [
                        'label' => __('Viewport'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'sizes' => [
                                'start' => 20,
                                'end' => 80,
                            ],
                            'unit' => '%',
                        ],
                        'labels' => [
                            __('Bottom'),
                            __('Top'),
                        ],
                        'scales' => 1,
                        'handles' => 'range',
                    ],
                ],
            ],
            'blur' => [
                'label' => __('Blur'),
                'fields' => [
                    'direction' => [
                        'label' => __('Direction'),
                        'type' => ControlsManager::SELECT,
                        'default' => 'out-in',
                        'options' => [
                            'out-in' => 'Fade In',
                            'in-out' => 'Fade Out',
                            'in-out-in' => 'Fade Out In',
                            'out-in-out' => 'Fade In Out',
                        ],
                    ],
                    'level' => [
                        'label' => __('Level'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'size' => 7,
                        ],
                        'range' => [
                            'px' => [
                                'min' => 1,
                                'max' => 15,
                            ],
                        ],
                    ],
                    'range' => [
                        'label' => __('Viewport'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'sizes' => [
                                'start' => 20,
                                'end' => 80,
                            ],
                            'unit' => '%',
                        ],
                        'labels' => [
                            __('Bottom'),
                            __('Top'),
                        ],
                        'scales' => 1,
                        'handles' => 'range',
                    ],
                ],
            ],
            'rotateZ' => [
                'label' => __('Rotate'),
                'fields' => [
                    'direction' => [
                        'label' => __('Direction'),
                        'type' => ControlsManager::SELECT,
                        'options' => [
                            '' => __('Left'),
                            'negative' => __('Right'),
                        ],
                    ],
                    'speed' => [
                        'label' => __('Speed'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'size' => 1,
                        ],
                        'range' => [
                            'px' => [
                                'max' => 10,
                                'step' => 0.1,
                            ],
                        ],
                    ],
                    'affectedRange' => [
                        'label' => __('Viewport'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'sizes' => [
                                'start' => 0,
                                'end' => 100,
                            ],
                            'unit' => '%',
                        ],
                        'labels' => [
                            __('Bottom'),
                            __('Top'),
                        ],
                        'scales' => 1,
                        'handles' => 'range',
                    ],
                ],
            ],
            'scale' => [
                'label' => __('Scale'),
                'fields' => [
                    'direction' => [
                        'label' => __('Direction'),
                        'type' => ControlsManager::SELECT,
                        'default' => 'out-in',
                        'options' => [
                            'out-in' => 'Scale Up',
                            'in-out' => 'Scale Down',
                            'in-out-in' => 'Scale Down Up',
                            'out-in-out' => 'Scale Up Down',
                        ],
                    ],
                    'speed' => [
                        'label' => __('Speed'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'size' => 4,
                        ],
                        'range' => [
                            'px' => [
                                'min' => -10,
                                'max' => 10,
                            ],
                        ],
                    ],
                    'range' => [
                        'label' => __('Viewport'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'sizes' => [
                                'start' => 20,
                                'end' => 80,
                            ],
                            'unit' => '%',
                        ],
                        'labels' => [
                            __('Bottom'),
                            __('Top'),
                        ],
                        'scales' => 1,
                        'handles' => 'range',
                    ],
                ],
            ],
        ];
    }

    private function getMouseEffects()
    {
        return [
            'mouseTrack' => [
                'label' => __('Mouse Track'),
                'fields' => [
                    'direction' => [
                        'label' => __('Direction'),
                        'type' => ControlsManager::SELECT,
                        'options' => [
                            '' => __('Opposite'),
                            'negative' => __('Direct'),
                        ],
                    ],
                    'speed' => [
                        'label' => __('Speed'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'size' => 1,
                        ],
                        'range' => [
                            'px' => [
                                'max' => 10,
                                'step' => 0.1,
                            ],
                        ],
                    ],
                ],
            ],
            'tilt' => [
                'label' => __('3D Tilt'),
                'fields' => [
                    'direction' => [
                        'label' => __('Direction'),
                        'type' => ControlsManager::SELECT,
                        'options' => [
                            '' => __('Direct'),
                            'negative' => __('Opposite'),
                        ],
                    ],
                    'speed' => [
                        'label' => __('Speed'),
                        'type' => ControlsManager::SLIDER,
                        'default' => [
                            'size' => 4,
                        ],
                        'range' => [
                            'px' => [
                                'max' => 10,
                                'step' => 0.1,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    private function prepareEffects($effects_group, array &$fields)
    {
        $effects = $this->{"get{$effects_group}effects"}();

        foreach ($effects as $effect_name => $effect_args) {
            $args = [
                'label' => $effect_args['label'],
                'type' => ControlsManager::POPOVER_TOGGLE,
                'condition' => [
                    "motion_fx_$effects_group!" => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ];

            if (!empty($effect_args['separator'])) {
                $args['separator'] = $effect_args['separator'];
            }

            $fields[$effect_name . '_effect'] = $args;

            $effect_fields = $effect_args['fields'];
            $first_field = &$effect_fields[key($effect_fields)];
            $first_field['popover']['start'] = true;

            end($effect_fields);
            $last_field = &$effect_fields[key($effect_fields)];
            $last_field['popover']['end'] = true;

            reset($effect_fields);

            foreach ($effect_fields as $field_name => $field) {
                $field = array_merge($field, [
                    'condition' => [
                        "motion_fx_$effects_group!" => '',
                        "{$effect_name}_effect!" => '',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]);

                $fields[$effect_name . '_' . $field_name] = $field;
            }
        }
    }
}
