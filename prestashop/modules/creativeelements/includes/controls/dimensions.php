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
 * Elementor dimension control.
 *
 * A base control for creating dimension control. Displays input fields for top,
 * right, bottom, left and the option to link them together.
 *
 * @since 1.0.0
 */
class ControlDimensions extends ControlBaseUnits
{
    const MIN = 1;

    /**
     * Get dimensions control type.
     *
     * Retrieve the control type, in this case `dimensions`.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'dimensions';
    }

    /**
     * Get dimensions control default values.
     *
     * Retrieve the default value of the dimensions control. Used to return the
     * default values while initializing the dimensions control.
     *
     * @since 1.0.0
     *
     * @return array Control default value
     */
    public function getDefaultValue()
    {
        return array_merge(parent::getDefaultValue(), [
            'top' => '',
            'right' => '',
            'bottom' => '',
            'left' => '',
            'isLinked' => true,
        ]);
    }

    /**
     * Get dimensions control default settings.
     *
     * Retrieve the default settings of the dimensions control. Used to return the
     * default settings while initializing the dimensions control.
     *
     * @since 1.0.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return array_merge(parent::getDefaultSettings(), [
            'label_block' => true,
            'allowed_dimensions' => 'all',
            'placeholder' => '',
        ]);
    }

    /**
     * Render dimensions control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     */
    public function contentTemplate()
    {
        $dimensions = [
            'top' => __('Top'),
            'right' => __('Right'),
            'bottom' => __('Bottom'),
            'left' => __('Left'),
        ]; ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <?php $this->printUnitsTemplate(); ?>
            <div class="elementor-control-input-wrapper">
                <ul class="elementor-control-dimensions">
                    <?php foreach ($dimensions as $dimension_key => $dimension_title) { ?>
                        <?php $control_uid = $this->getControlUid($dimension_key); ?>
                        <li class="elementor-control-dimension">
                            <input id="<?php echo $control_uid; ?>" type="number" data-setting="<?php echo esc_attr($dimension_key); ?>" placeholder="<#
                                if ( _.isObject( data.placeholder ) ) {
                                    if ( ! _.isUndefined( data.placeholder.<?php echo $dimension_key; ?> ) ) {
                                        print( data.placeholder.<?php echo $dimension_key; ?> );
                                    }
                                } else {
                                    print( data.placeholder );
                                } #>"
                                <# if ( -1 === _.indexOf( allowed_dimensions, '<?php echo $dimension_key; ?>' ) ) { #>disabled<# } #>
                            />
                            <label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-dimension-label"><?php echo $dimension_title; ?></label>
                        </li>
                    <?php } ?>
                    <li>
                        <button class="elementor-link-dimensions tooltip-target" data-tooltip="<?php esc_attr_e('Link values together'); ?>">
                            <span class="elementor-linked">
                                <i class="eicon-link" aria-hidden="true"></i>
                                <span class="elementor-screen-only"><?php _e('Link values together'); ?></span>
                            </span>
                            <span class="elementor-unlinked">
                                <i class="eicon-chain-broken" aria-hidden="true"></i>
                                <span class="elementor-screen-only"><?php _e('Unlinked values'); ?></span>
                            </span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
