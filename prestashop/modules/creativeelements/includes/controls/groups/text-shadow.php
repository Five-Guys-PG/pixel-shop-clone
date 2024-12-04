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
 * Elementor text shadow control.
 *
 * A base control for creating text shadow control. Displays input fields to define
 * the text shadow including the horizontal shadow, vertical shadow, shadow blur and
 * shadow color.
 *
 * @since 1.6.0
 */
class GroupControlTextShadow extends GroupControlBase
{
    /**
     * Fields.
     *
     * Holds all the text shadow control fields.
     *
     * @since 1.6.0
     * @static
     *
     * @var array Text shadow control fields
     */
    protected static $fields;

    /**
     * Get text shadow control type.
     *
     * Retrieve the control type, in this case `text-shadow`.
     *
     * @since 1.6.0
     * @static
     *
     * @return string Control type
     */
    public static function getType()
    {
        return 'text-shadow';
    }

    /**
     * Init fields.
     *
     * Initialize text shadow control fields.
     *
     * @since 1.6.0
     *
     * @return array Control fields
     */
    protected function initFields()
    {
        $controls = [];

        $controls['text_shadow'] = [
            'label' => _x('Text Shadow', 'Text Shadow Control'),
            'type' => ControlsManager::TEXT_SHADOW,
            'selectors' => [
                '{{SELECTOR}}' => 'text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};',
            ],
        ];

        return $controls;
    }

    /**
     * Get default options.
     *
     * Retrieve the default options of the text shadow control. Used to return the
     * default options while initializing the text shadow control.
     *
     * @since 1.9.0
     *
     * @return array Default text shadow control options
     */
    protected function getDefaultOptions()
    {
        return [
            'popover' => [
                'starter_title' => _x('Text Shadow', 'Text Shadow Control'),
                'starter_name' => 'text_shadow_type',
                'starter_value' => 'yes',
                'settings' => [
                    'render_type' => 'ui',
                ],
            ],
        ];
    }
}
