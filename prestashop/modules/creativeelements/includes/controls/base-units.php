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
 * Elementor control base units.
 *
 * An abstract class for creating new unit controls in the panel.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class ControlBaseUnits extends ControlBaseMultiple
{
    const MIN = 2;

    /**
     * Get units control default value.
     *
     * Retrieve the default value of the units control. Used to return the default
     * values while initializing the units control.
     *
     * @since 1.0.0
     *
     * @return array Control default value
     */
    public function getDefaultValue()
    {
        return [
            'unit' => 'px',
        ];
    }

    /**
     * Get units control default settings.
     *
     * Retrieve the default settings of the units control. Used to return the default
     * settings while initializing the units control.
     *
     * @since 1.0.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0.1,
                    'max' => 10,
                    'step' => 0.1,
                ],
                'rem' => [
                    'min' => 0.1,
                    'max' => 10,
                    'step' => 0.1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'deg' => [
                    'min' => 0,
                    'max' => 360,
                    'step' => 1,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
        ];
    }

    /**
     * Print units control settings.
     *
     * Used to generate the units control template in the editor.
     *
     * @since 1.0.0
     */
    protected function printUnitsTemplate()
    {
        ?>
        <# if ( data.size_units && data.size_units.length >= <?php echo static::MIN; ?> ) { #>
        <div class="elementor-units-choices">
            <# _.each( data.size_units, function( unit ) { #>
            <input id="elementor-choose-{{ data._cid + data.name + unit }}" type="radio"
                name="elementor-choose-{{ data.name }}" data-setting="unit" value="{{ unit }}">
            <label class="elementor-units-choices-label" for="elementor-choose-{{ data._cid + data.name + unit }}">
                {{{ unit }}}
            </label>
            <# } ); #>
        </div>
        <# } #>
        <?php
    }
}
