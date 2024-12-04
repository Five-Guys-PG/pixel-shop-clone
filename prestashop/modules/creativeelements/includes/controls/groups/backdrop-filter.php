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

class GroupControlBackdropFilter extends GroupControlBase
{
    protected static $fields;

    public static function getType()
    {
        return 'backdrop-filter';
    }

    protected function initFields()
    {
        $controls = [];

        $controls['blur'] = [
            'label' => _x('Blur', 'Filter Control'),
            'type' => ControlsManager::SLIDER,
            'required' => 'true',
            'range' => [
                'px' => [
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
            'default' => [
                'size' => 0,
            ],
            'selectors' => [
                '{{SELECTOR}}' => '-webkit-' .
                    'backdrop-filter: brightness({{brightness.SIZE}}%) contrast({{contrast.SIZE}}%) saturate({{saturate.SIZE}}%) blur({{blur.SIZE}}px) hue-rotate({{hue.SIZE}}deg); ' .
                    'backdrop-filter: brightness({{brightness.SIZE}}%) contrast({{contrast.SIZE}}%) saturate({{saturate.SIZE}}%) blur({{blur.SIZE}}px) hue-rotate({{hue.SIZE}}deg);',
            ],
        ];

        $controls['brightness'] = [
            'label' => _x('Brightness', 'Filter Control'),
            'type' => ControlsManager::SLIDER,
            'render_type' => 'ui',
            'required' => 'true',
            'default' => [
                'size' => 100,
            ],
            'range' => [
                'px' => [
                    'max' => 200,
                ],
            ],
            'separator' => 'none',
        ];

        $controls['contrast'] = [
            'label' => _x('Contrast', 'Filter Control'),
            'type' => ControlsManager::SLIDER,
            'render_type' => 'ui',
            'required' => 'true',
            'default' => [
                'size' => 100,
            ],
            'range' => [
                'px' => [
                    'max' => 200,
                ],
            ],
            'separator' => 'none',
        ];

        $controls['saturate'] = [
            'label' => _x('Saturation', 'Filter Control'),
            'type' => ControlsManager::SLIDER,
            'render_type' => 'ui',
            'required' => 'true',
            'default' => [
                'size' => 100,
            ],
            'range' => [
                'px' => [
                    'max' => 200,
                ],
            ],
            'separator' => 'none',
        ];

        $controls['hue'] = [
            'label' => _x('Hue', 'Filter Control'),
            'type' => ControlsManager::SLIDER,
            'render_type' => 'ui',
            'required' => 'true',
            'default' => [
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'max' => 360,
                ],
            ],
            'separator' => 'none',
        ];

        return $controls;
    }

    protected function getDefaultOptions()
    {
        return [
            'popover' => [
                'starter_name' => 'backdrop',
                'starter_title' => _x('Backdrop Filters', 'Filter Control'),
                'settings' => [
                    'render_type' => 'ui',
                ],
            ],
        ];
    }
}
