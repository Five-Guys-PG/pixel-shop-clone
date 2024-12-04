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
 * Elementor popover toggle control.
 *
 * A base control for creating a popover toggle control. By default displays a toggle
 * button to open and close a popover.
 *
 * @since 1.9.0
 */
class ControlPopoverToggle extends BaseDataControl
{
    /**
     * Get popover toggle control type.
     *
     * Retrieve the control type, in this case `popover_toggle`.
     *
     * @since 1.9.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'popover_toggle';
    }

    /**
     * Get popover toggle control default settings.
     *
     * Retrieve the default settings of the popover toggle control. Used to
     * return the default settings while initializing the popover toggle
     * control.
     *
     * @since 1.9.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'return_value' => 'yes',
        ];
    }

    /**
     * Render popover toggle control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.9.0
     */
    public function contentTemplate()
    {
        $control_uid = $this->getControlUid(); ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <input id="<?php echo $control_uid; ?>-custom" class="elementor-control-popover-toggle-toggle" type="radio"
                    name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="{{ data.return_value }}">
                <label class="elementor-control-popover-toggle-toggle-label elementor-control-unit-1"
                    for="<?php echo $control_uid; ?>-custom">
                    <i class="eicon-edit" aria-hidden="true"></i>
                    <span class="elementor-screen-only"><?php _e('Edit'); ?></span>
                </label>
                <input id="<?php echo $control_uid; ?>-default" type="radio"
                    name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="">
                <label class="elementor-control-popover-toggle-reset-label tooltip-target"
                    for="<?php echo $control_uid; ?>-default" data-tooltip="<?php _e('Back to default'); ?>" data-tooltip-pos="s">
                    <i class="eicon-undo" aria-hidden="true"></i>
                    <span class="elementor-screen-only"><?php _e('Back to default'); ?></span>
                </label>
            </div>
        </div>
        <?php
    }
}
