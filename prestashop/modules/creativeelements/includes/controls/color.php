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
 * Elementor color control.
 *
 * A base control for creating color control. Displays a color picker field with
 * an alpha slider. Includes a customizable color palette that can be preset by
 * the user. Accepts a `scheme` argument that allows you to set a value from the
 * active color scheme as the default value returned by the control.
 *
 * @since 1.0.0
 */
class ControlColor extends BaseDataControl
{
    /**
     * Get color control type.
     *
     * Retrieve the control type, in this case `color`.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'color';
    }

    /**
     * Render color control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     */
    public function contentTemplate()
    {
        $dynamic_class = 'elementor-control-dynamic-switcher-wrapper'; ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label || '' }}}</label>
            <div class="elementor-control-input-wrapper <?php echo $dynamic_class; ?> elementor-control-unit-5">
                <div class="elementor-color-picker-placeholder"></div>
            </div>
        </div>
        <?php
    }

    /**
     * Get color control default settings.
     *
     * Retrieve the default settings of the color control. Used to return the default
     * settings while initializing the color control.
     *
     * @since 1.0.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'alpha' => true,
            'scheme' => '',
            'dynamic' => [
                'categories' => ['color'],
                // 'active' => true,
            ],
        ];
    }
}
