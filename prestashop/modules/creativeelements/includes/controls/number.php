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
 * Elementor number control.
 *
 * A base control for creating a number control. Displays a simple number input.
 *
 * @since 1.0.0
 */
class ControlNumber extends BaseDataControl
{
    /**
     * Get number control type.
     *
     * Retrieve the control type, in this case `number`.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'number';
    }

    /**
     * Get number control default settings.
     *
     * Retrieve the default settings of the number control. Used to return the
     * default settings while initializing the number control.
     *
     * @since 1.5.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'min' => '',
            'max' => '',
            'step' => '',
            'placeholder' => '',
            'title' => '',
            'dynamic' => [
                'categories' => ['number'],
            ],
        ];
    }

    /**
     * Render number control output in the editor.
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
            <div class="elementor-control-input-wrapper elementor-control-dynamic-switcher-wrapper">
                <input class="tooltip-target elementor-control-tag-area elementor-control-unit-2"
                    id="<?php echo $control_uid; ?>" data-setting="{{ data.name }}"
                    type="number" min="{{ data.min }}" max="{{ data.max }}" step="{{ data.step }}"
                    placeholder="{{ data.placeholder }}" data-tooltip="{{ data.title }}" title="{{ data.title }}">
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
