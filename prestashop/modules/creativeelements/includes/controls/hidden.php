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
 * Elementor hidden control.
 *
 * A base control for creating hidden control. Used to save additional data in
 * the database without a visual presentation in the panel.
 *
 * @since 1.0.0
 */
class ControlHidden extends BaseDataControl
{
    /**
     * Get hidden control type.
     *
     * Retrieve the control type, in this case `hidden`.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'hidden';
    }

    /**
     * Render hidden control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     */
    public function contentTemplate()
    {
        ?>
        <input type="hidden" data-setting="{{{ data.name }}}" />
        <?php
    }
}
