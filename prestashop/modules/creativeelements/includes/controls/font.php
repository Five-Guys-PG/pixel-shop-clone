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
 * Elementor font control.
 *
 * A base control for creating font control. Displays font select box. The
 * control allows you to set a list of fonts.
 *
 * @since 1.0.0
 */
class ControlFont extends BaseDataControl
{
    /**
     * Get font control type.
     *
     * Retrieve the control type, in this case `font`.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'font';
    }

    /**
     * Get font control default settings.
     *
     * Retrieve the default settings of the font control. Used to return the default
     * settings while initializing the font control.
     *
     * @since 1.0.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'groups' => Fonts::getFontGroups(),
            'options' => Fonts::getFonts(),
        ];
    }

    /**
     * Render font control output in the editor.
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
            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <select id="<?php echo $control_uid; ?>" class="elementor-control-font-family" data-setting="{{ data.name }}">
                    <option value=""><?php _e('Default'); ?></option>
                    <# _.each( data.groups, function( group_label, group_name ) {
                        var groupFonts = getFontsByGroups( group_name );
                        if ( ! _.isEmpty( groupFonts ) ) { #>
                        <optgroup label="{{ group_label }}">
                            <# _.each( groupFonts, function( fontType, fontName ) { #>
                                <option value="{{ fontName }}">{{{ fontName }}}</option>
                            <# } ); #>
                        </optgroup>
                        <# }
                    }); #>
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
