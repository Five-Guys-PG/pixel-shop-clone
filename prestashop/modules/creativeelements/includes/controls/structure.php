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
 * Elementor structure control.
 *
 * A base control for creating structure control. A private control for section
 * columns structure.
 *
 * @since 1.0.0
 */
class ControlStructure extends BaseDataControl
{
    /**
     * Get structure control type.
     *
     * Retrieve the control type, in this case `structure`.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'structure';
    }

    /**
     * Render structure control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     */
    public function contentTemplate()
    {
        $preset_control_uid = $this->getControlUid('{{ preset.key }}'); ?>
        <# var morePresets = getMorePresets(); #>
        <div class="elementor-control-field">
            <div class="elementor-control-input-wrapper">
            <# if ( morePresets.length ) { #>
                <div class="elementor-control-structure-presets">
                <# _.each( morePresets, function( preset ) { #>
                    <div class="elementor-control-structure-preset-wrapper">
                        <input id="<?php echo $preset_control_uid; ?>" type="radio" data-setting="structure"
                            name="elementor-control-structure-preset-{{ data._cid }}" value="{{ preset.key }}">
                        <label for="<?php echo $preset_control_uid; ?>" class="elementor-control-structure-preset">
                            {{{ elementor.presetsFactory.getPresetSVG( preset.preset, 102, 42 ).outerHTML }}}
                        </label>
                        <div class="elementor-control-structure-preset-title">{{{ preset.preset.join( ', ' ) }}}</div>
                    </div>
                <# } ); #>
                </div>
            <# } #>
            </div>
            <div class="elementor-control-structure-reset">
                <i class="eicon-undo" aria-hidden="true"></i>
                <?php _e('Reset'); ?>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

    /**
     * Get structure control default settings.
     *
     * Retrieve the default settings of the structure control. Used to return the
     * default settings while initializing the structure control.
     *
     * @since 1.0.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'separator' => 'none',
            'label_block' => true,
            'show_label' => false,
        ];
    }
}
