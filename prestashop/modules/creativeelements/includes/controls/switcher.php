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
 * Elementor switcher control.
 *
 * A base control for creating switcher control. Displays an on/off switcher,
 * basically a fancy UI representation of a checkbox.
 *
 * @since 1.0.0
 */
class ControlSwitcher extends BaseDataControl
{
    /**
     * Get switcher control type.
     *
     * Retrieve the control type, in this case `switcher`.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'switcher';
    }

    /**
     * Render switcher control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     */
    public function contentTemplate()
    {
        $control_uid = $this->getControlUid(); ?>
        <div class="elementor-control-field">
            <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <label class="elementor-switch elementor-control-unit-2">
                    <input id="<?php echo $control_uid; ?>" class="elementor-switch-input" type="checkbox"
                        data-setting="{{ data.name }}" value="{{ data.return_value }}">
                    <span class="elementor-switch-label"
                        data-on="{{ data.label_on }}" data-off="{{ data.label_off }}"></span>
                    <span class="elementor-switch-handle"></span>
                </label>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

    /**
     * Get switcher control default settings.
     *
     * Retrieve the default settings of the switcher control. Used to return the
     * default settings while initializing the switcher control.
     *
     * @since 1.0.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'label_off' => __('No'),
            'label_on' => __('Yes'),
            'return_value' => 'yes',
        ];
    }
}
