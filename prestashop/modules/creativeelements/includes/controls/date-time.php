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

use CE\ModulesXDynamicTagsXModule as TagsModule;

/**
 * Elementor date/time control.
 *
 * A base control for creating date time control. Displays a date/time picker
 * based on the Flatpickr library @see https://chmln.github.io/flatpickr/ .
 *
 * @since 1.0.0
 */
class ControlDateTime extends BaseDataControl
{
    /**
     * Get date time control type.
     *
     * Retrieve the control type, in this case `date_time`.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'date_time';
    }

    /**
     * Get date time control default settings.
     *
     * Retrieve the default settings of the date time control. Used to return the
     * default settings while initializing the date time control.
     *
     * @since 1.8.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'label_block' => true,
            'picker_options' => [],
            'dynamic' => [
                'categories' => [TagsModule::DATE_TIME_CATEGORY],
            ],
        ];
    }

    /**
     * Render date time control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     */
    public function contentTemplate()
    {
        $control_uid = $this->getControlUid();
        $dynamic_class = 'elementor-control-dynamic-switcher-wrapper'; ?>
        <div class="elementor-control-field">
            <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper elementor-control-unit-5 <?php echo $dynamic_class; ?>">
                <input id="<?php echo $control_uid; ?>" class="elementor-date-time-picker elementor-control-tag-area flatpickr"
                    type="text" placeholder="{{ data.placeholder }}" data-setting="{{ data.name }}">
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
