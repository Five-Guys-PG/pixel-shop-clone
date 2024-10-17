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
 * Elementor select2 control.
 *
 * A base control for creating select2 control. Displays a select box control
 * based on select2 jQuery plugin @see https://select2.github.io/ .
 * It accepts an array in which the `key` is the value and the `value` is the
 * option name. Set `multiple` to `true` to allow multiple value selection.
 *
 * @since 1.0.0
 */
class ControlSelect2 extends BaseDataControl
{
    /**
     * Get select2 control type.
     *
     * Retrieve the control type, in this case `select2`.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'select2';
    }

    /**
     * Get select2 control default settings.
     *
     * Retrieve the default settings of the select2 control. Used to return the
     * default settings while initializing the select2 control.
     *
     * @since 1.8.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'options' => [],
            'multiple' => false,
            'select2options' => [],
        ];
    }

    /**
     * Render select2 control output in the editor.
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
        <#
        if (data.select2options.tags && !data.multiple && Object.keys(data.options).indexOf(data.controlValue) < 0) {
            data.options[data.controlValue] = data.controlValue;
        }
        #>
        <div class="elementor-control-field">
            <# if ( data.label ) { #>
                <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <# } #>
            <# if ( data.select2options.ajax ) { #>
                <span class="elementor-control-loading">
                    <i class="eicon-loading eicon-animation-spin"></i>
                </span>
            <# } #>
            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
                <select id="<?php echo $control_uid; ?>" class="elementor-select2" type="select2"
                    {{ multiple }} data-setting="{{ data.name }}">
                <# _.each( data.options, function( option_title, option_value ) {
                    var value = data.controlValue;
                    if ( typeof value === 'string' ) {
                        var selected = ( option_value === value ) ? 'selected' : '';
                    } else if ( null !== value ) {
                        var value = _.values( value );
                        var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
                    }
                    #>
                    <option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
                <# } ); #>
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
