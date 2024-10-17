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
 * Elementor divider control.
 *
 * A base control for creating divider control. Displays horizontal line in
 * the panel.
 *
 * @since 2.0.0
 */
class ControlDivider extends BaseUIControl
{
    /**
     * Get divider control type.
     *
     * Retrieve the control type, in this case `divider`.
     *
     * @since 2.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'divider';
    }

    /**
     * Get divider control default settings.
     *
     * Retrieve the default settings of the divider control. Used to
     * return the default settings while initializing the divider
     * control.
     *
     * @since 2.0.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'separator' => 'none',
        ];
    }

    /**
     * Render divider control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 2.0.0
     */
    public function contentTemplate()
    {
    }
}
