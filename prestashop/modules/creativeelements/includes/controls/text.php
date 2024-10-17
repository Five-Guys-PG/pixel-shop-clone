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
 * Elementor text control.
 *
 * A base control for creating text control. Displays a simple text input.
 *
 * @since 1.0.0
 */
class ControlText extends BaseDataControl
{
    /**
     * Get text control type.
     *
     * Retrieve the control type, in this case `text`.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'text';
    }

    /**
     * Render text control output in the editor.
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
        <# if ( data.label ) {#>
            <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
        <# } #>
            <div class="elementor-control-input-wrapper elementor-control-unit-5 <?php echo $dynamic_class; ?>">
                <input id="<?php echo $control_uid; ?>" type="{{ data.input_type }}"
                    class="tooltip-target elementor-control-tag-area" data-setting="{{ data.name }}"
                    data-tooltip="{{ data.title }}" title="{{ data.title }}" placeholder="{{ data.placeholder }}"
                    <# if ( data.input_list ) { #>list="{{ data.name }}-list"<# } #>>
            <# if ( Array.isArray( data.input_list ) ) { #>
                <datalist id="{{ data.name }}-list">
                <# data.input_list.forEach( function ( val ) { #>
                    <option value="{{ val }}">
                <# }); #>
                </datalist>
            <# } #>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

    /**
     * Get text control default settings.
     *
     * Retrieve the default settings of the text control. Used to return the
     * default settings while initializing the text control.
     *
     * @since 1.0.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'input_type' => 'text',
            'placeholder' => '',
            'title' => '',
            'input_list' => false,
            'dynamic' => [
                'categories' => [
                    TagsModule::TEXT_CATEGORY,
                ],
            ],
        ];
    }
}
