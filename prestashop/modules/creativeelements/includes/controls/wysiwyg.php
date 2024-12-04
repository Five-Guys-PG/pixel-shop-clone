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
 * Elementor WYSIWYG control.
 *
 * A base control for creating WYSIWYG control. Displays a PrestaShop WYSIWYG
 * (TinyMCE) editor.
 *
 * @since 1.0.0
 */
class ControlWysiwyg extends BaseDataControl
{
    /**
     * Get wysiwyg control type.
     *
     * Retrieve the control type, in this case `wysiwyg`.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'wysiwyg';
    }

    /**
     * Render wysiwyg control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     */
    public function contentTemplate()
    {
        ?>
        <div class="elementor-control-field">
            <div class="elementor-control-title">{{{ data.label }}}</div>
            <div class=
                "elementor-control-input-wrapper elementor-control-dynamic-switcher-wrapper elementor-control-tag-area"
            ></div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

    /**
     * Retrieve textarea control default settings.
     *
     * Get the default settings of the textarea control. Used to return the
     * default settings while initializing the textarea control.
     *
     * @since 2.0.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'label_block' => true,
            'dynamic' => [
                'active' => true,
                'categories' => [TagsModule::TEXT_CATEGORY],
            ],
        ];
    }
}
