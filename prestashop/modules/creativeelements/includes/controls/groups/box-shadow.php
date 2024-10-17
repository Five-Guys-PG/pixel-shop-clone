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
 * Elementor box shadow control.
 *
 * A base control for creating box shadow control. Displays input fields to define
 * the box shadow including the horizontal shadow, vertical shadow, shadow blur,
 * shadow spread, shadow color and the position.
 *
 * @since 1.2.2
 */
class GroupControlBoxShadow extends GroupControlBase
{
    /**
     * Fields.
     *
     * Holds all the box shadow control fields.
     *
     * @since 1.2.2
     * @static
     *
     * @var array Box shadow control fields
     */
    protected static $fields;

    /**
     * Get box shadow control type.
     *
     * Retrieve the control type, in this case `box-shadow`.
     *
     * @since 1.0.0
     * @static
     *
     * @return string Control type
     */
    public static function getType()
    {
        return 'box-shadow';
    }

    /**
     * Init fields.
     *
     * Initialize box shadow control fields.
     *
     * @since 1.2.2
     *
     * @return array Control fields
     */
    protected function initFields()
    {
        $controls = [];

        $controls['box_shadow'] = [
            'label' => _x('Box Shadow', 'Box Shadow Control'),
            'type' => ControlsManager::BOX_SHADOW,
            'selectors' => [
                '{{SELECTOR}}' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
            ],
        ];

        $controls['box_shadow_position'] = [
            'label' => _x('Position', 'Box Shadow Control'),
            'type' => ControlsManager::SELECT,
            'options' => [
                ' ' => _x('Outline', 'Box Shadow Control'),
                'inset' => _x('Inset', 'Box Shadow Control'),
            ],
            'default' => ' ',
            'render_type' => 'ui',
        ];

        return $controls;
    }

    /**
     * Get default options.
     *
     * Retrieve the default options of the box shadow control. Used to return the
     * default options while initializing the box shadow control.
     *
     * @since 1.9.0
     *
     * @return array Default box shadow control options
     */
    protected function getDefaultOptions()
    {
        return [
            'popover' => [
                'starter_title' => _x('Box Shadow', 'Box Shadow Control'),
                'starter_name' => 'box_shadow_type',
                'starter_value' => 'yes',
                'settings' => [
                    'render_type' => 'ui',
                ],
            ],
        ];
    }
}
