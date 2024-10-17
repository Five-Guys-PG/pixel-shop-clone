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
 * Elementor section control.
 *
 * A base control for creating section control. Displays a header that
 * functions as a toggle to show or hide a set of controls.
 *
 * Note: Do not use it directly, instead use `$widget->startControlsSection()`
 * and `$widget->endControlsSection()` to wrap a set of controls.
 *
 * @since 1.0.0
 */
class ControlSection extends BaseUIControl
{
    /**
     * Get section control type.
     *
     * Retrieve the control type, in this case `section`.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'section';
    }

    /**
     * Render section control output in the editor.
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
        <div class="elementor-panel-heading">
            <div class="elementor-panel-heading-toggle elementor-section-toggle" data-collapse_id="{{ data.name }}">
                <i class="eicon" aria-hidden="true"></i>
            </div>
            <div class="elementor-panel-heading-title elementor-section-title">{{{ data.label }}}</div>
        </div>
        <?php
    }

    /**
     * Get repeater control default settings.
     *
     * Retrieve the default settings of the repeater control. Used to return the
     * default settings while initializing the repeater control.
     *
     * @since 1.0.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'separator' => 'none',
        ];
    }
}
