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
 * Elementor URL control.
 *
 * A base control for creating url control. Displays a URL input with the
 * ability to set the target of the link to `_blank` to open in a new tab.
 *
 * @since 1.0.0
 */
class ControlUrl extends ControlBaseMultiple
{
    /**
     * Get url control type.
     *
     * Retrieve the control type, in this case `url`.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'url';
    }

    /**
     * Get url control default values.
     *
     * Retrieve the default value of the url control. Used to return the default
     * values while initializing the url control.
     *
     * @since 1.0.0
     *
     * @return array Control default value
     */
    public function getDefaultValue()
    {
        return [
            'url' => '',
            'is_external' => '',
            'nofollow' => '',
            'custom_attributes' => '',
        ];
    }

    /**
     * Get url control default settings.
     *
     * Retrieve the default settings of the url control. Used to return the default
     * settings while initializing the url control.
     *
     * @since 1.0.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'label_block' => true,
            'placeholder' => __('Paste URL or type'),
            'autocomplete' => true,
            'options' => ['is_external', 'nofollow', 'custom_attributes'],
            'dynamic' => [
                'categories' => [TagsModule::URL_CATEGORY],
                'property' => 'url',
            ],
        ];
    }

    /**
     * Render url control output in the editor.
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
        $is_external_control_uid = $this->getControlUid('is_external');
        $nofollow_control_uid = $this->getControlUid('nofollow');
        $custom_attributes_uid = $this->getControlUid('custom_attributes');
        ?>
        <div class="elementor-control-field elementor-control-url-external-{{{ data.options.length ? 'show' : 'hide' }}}">
            <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper elementor-control-dynamic-switcher-wrapper">
                <i class="elementor-control-url-autocomplete-spinner eicon-loading eicon-animation-spin" aria-hidden="true"></i>
                <input id="<?php echo $control_uid; ?>" class="elementor-control-tag-area elementor-input" data-setting="url" placeholder="{{ data.placeholder }}">
                <div class="elementor-control-url-more tooltip-target elementor-control-unit-1" data-tooltip="<?php _e('Link Options'); ?>">
                    <i class="eicon-cog" aria-hidden="true"></i>
                </div>
            </div>
            <div class="elementor-control-url-more-options">
                <div class="elementor-control-url-option">
                    <input id="<?php echo $is_external_control_uid; ?>" type="checkbox" class="elementor-control-url-option-input" data-setting="is_external">
                    <label for="<?php echo $is_external_control_uid; ?>"><?php _e('Open in new window'); ?></label>
                </div>
                <div class="elementor-control-url-option">
                    <input id="<?php echo $nofollow_control_uid; ?>" type="checkbox" class="elementor-control-url-option-input" data-setting="nofollow">
                    <label for="<?php echo $nofollow_control_uid; ?>"><?php _e('Add nofollow'); ?></label>
                </div>
                <div class="elementor-control-url__custom-attributes">
                    <label for="<?php echo $custom_attributes_uid; ?>" class="elementor-control-url__custom-attributes-label"><?php _e('Custom Attributes'); ?></label>
                    <input type="text" id="<?php echo $custom_attributes_uid; ?>" class="elementor-control-unit-5" placeholder="key|value,key|value..." data-setting="custom_attributes">
                </div>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
