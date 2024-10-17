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
 * Elementor text stroke control.
 *
 * A group control for creating a stroke effect on text. Displays input fields to define
 * the text stroke and color stroke.
 *
 * @since 2.5.9
 */
class GroupControlTextStroke extends GroupControlBase
{
    /**
     * Fields.
     *
     * Holds all the text stroke control fields.
     *
     * @since 2.5.9
     * @static
     *
     * @var array Text Stroke control fields
     */
    protected static $fields;

    /**
     * Get text stroke control type.
     *
     * Retrieve the control type, in this case `text-stroke`.
     *
     * @since 2.5.9
     * @static
     *
     * @return string Control type
     */
    public static function getType()
    {
        return 'text-stroke';
    }

    /**
     * Init fields.
     *
     * Initialize text stroke control fields.
     *
     * @since 2.5.9
     *
     * @return array Control fields
     */
    protected function initFields()
    {
        $controls = [];

        $controls['text_stroke'] = [
            'label' => __('Text Stroke'),
            'type' => ControlsManager::SLIDER,
            'size_units' => ['px', 'em', 'rem'],
            'range' => [
                'px' => [
                    'max' => 10,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 1,
                ],
            ],
            'responsive' => true,
            'selector' => '{{WRAPPER}}',
            'selectors' => [
                '{{SELECTOR}}' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}}; stroke-width: {{SIZE}}{{UNIT}};',
            ],
        ];

        $controls['stroke_color'] = [
            'label' => __('Stroke Color'),
            'type' => ControlsManager::COLOR,
            'default' => '#000',
            'selector' => '{{WRAPPER}}',
            'selectors' => [
                '{{SELECTOR}}' => '-webkit-text-stroke-color: {{VALUE}}; stroke: {{VALUE}};',
            ],
        ];

        return $controls;
    }

    /**
     * Get default options.
     *
     * Retrieve the default options of the text stroke control. Used to return the
     * default options while initializing the text stroke control.
     *
     * @since 2.5.9
     *
     * @return array Default text stroke control options
     */
    protected function getDefaultOptions()
    {
        return [
            'popover' => [
                'starter_title' => _x('Text Stroke', 'Text Stroke Control'),
                'starter_name' => 'text_stroke_type',
                'starter_value' => 'yes',
                'settings' => [
                    'render_type' => 'ui',
                ],
            ],
        ];
    }
}
