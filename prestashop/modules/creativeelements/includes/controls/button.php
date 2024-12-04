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
 * Elementor button control.
 *
 * A base control for creating a button control. Displays a button that can
 * trigger an event.
 *
 * @since 1.9.0
 */
class ControlButton extends BaseUIControl
{
    /**
     * Get button control type.
     *
     * Retrieve the control type, in this case `button`.
     *
     * @since 1.9.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'button';
    }

    /**
     * Get button control default settings.
     *
     * Retrieve the default settings of the button control. Used to
     * return the default settings while initializing the button
     * control.
     *
     * @since 1.9.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'text' => '',
            'event' => '',
            'link' => [],
            'button_type' => 'default',
        ];
    }

    /**
     * Render button control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.9.0
     */
    public function contentTemplate()
    {
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
            <# if ( data.link.url ) { #>
                <a href="{{ data.link.url }}" class="elementor-button elementor-button-{{{ data.button_type }}}"
                    target="{{{ data.link.is_external ? '_blank' : '_self' }}}">{{{ data.text }}}</a>
            <# } else { #>
                <button type="button" class="elementor-button elementor-button-{{{ data.button_type }}}"
                    data-event="{{{ data.event }}}">{{{ data.text }}}</button>
            <# } #>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
