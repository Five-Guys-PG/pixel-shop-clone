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
 * Elementor Icons control.
 *
 * A base control for creating a Icons chooser control.
 * Used to select an Icon.
 *
 * Usage: @see https://developers.elementor.com/elementor-controls/icons-control
 *
 * @since 2.6.0
 */
class ControlIcons extends ControlBaseMultiple
{
    /**
     * Get media control type.
     *
     * Retrieve the control type, in this case `media`.
     *
     * @since 2.6.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'icons';
    }

    /**
     * Get Icons control default values.
     *
     * Retrieve the default value of the Icons control. Used to return the default
     * values while initializing the Icons control.
     *
     * @since 2.6.0
     *
     * @return array Control default value
     */
    public function getDefaultValue()
    {
        return [
            'value' => '',
            'library' => '',
        ];
    }

    /**
     * Render Icons control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 2.6.0
     */
    public function contentTemplate()
    {
        ?>
        <# if ( 'inline' === data.skin ) { #>
            <?php $this->renderInlineSkin(); ?>
        <# } else { #>
            <?php $this->renderMediaSkin(); ?>
        <# } #>
        <?php
    }

    public function renderMediaSkin()
    {
        ?>
        <div class="elementor-control-field elementor-control-media">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper elementor-aspect-ratio-219">
                <div class="elementor-control-media__content elementor-control-tag-area elementor-control-preview-area elementor-fit-aspect-ratio">
                    <div class="elementor-control-media-upload-button elementor-control-media__content__upload-button elementor-fit-aspect-ratio">
                        <i class="eicon-plus-circle" aria-hidden="true"></i>
                    </div>
                    <div class="elementor-control-media-area elementor-fit-aspect-ratio">
                        <div class="elementor-control-media__remove elementor-control-media__content__remove" title="<?php _e('Remove'); ?>">
                            <i class="eicon-trash"></i>
                        </div>
                        <div class="elementor-control-media__preview elementor-fit-aspect-ratio"></div>
                    </div>
                    <div class="elementor-control-media__tools elementor-control-dynamic-switcher-wrapper">
                        <div class="elementor-control-icon-picker elementor-control-media__tool"><?php _e('Icon Library'); ?></div>
                        <div class="elementor-control-svg-uploader elementor-control-media__tool"><?php _e('Upload SVG'); ?></div>
                        <input type="hidden" id="elementor-control-media-url-{{ data._cid }}" value="{{ data.controlValue.value && data.controlValue.value.url || '' }}">
                    </div>
                </div>
            </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
            <input type="hidden" data-setting="{{ data.name }}"/>
        </div>
        <?php
    }

    public function renderInlineSkin()
    {
        $control_uid = $this->getControlUid(); ?>
        <div class="elementor-control-field elementor-control-inline-icon">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <div class="elementor-choices">
                <# if ( ! data.exclude_inline_options.includes( 'none' ) ) { #>
                    <input id="<?php echo $control_uid; ?>-none" type="radio" value="none">
                    <label class="elementor-choices-label elementor-control-unit-1 tooltip-target elementor-control-icons--inline__none" for="<?php echo $control_uid; ?>-none" data-tooltip="<?php _e('None'); ?>" title="<?php _e('None'); ?>">
                        <i class="eicon-ban" aria-hidden="true"></i>
                        <span class="elementor-screen-only"><?php _e('None'); ?></span>
                    </label>
                <# }
                if ( ! data.exclude_inline_options.includes( 'svg' ) ) { #>
                    <input id="<?php echo $control_uid; ?>-svg" type="radio" value="svg">
                    <label class="elementor-choices-label elementor-control-unit-1 tooltip-target elementor-control-icons--inline__svg" for="<?php echo $control_uid; ?>-svg" data-tooltip="<?php _e('Upload SVG'); ?>" title="<?php _e('Upload SVG'); ?>">
                        <i class="eicon-upload" aria-hidden="true"></i>
                        <span class="elementor-screen-only"><?php _e('Upload SVG'); ?></span>
                    </label>
                    <input type="hidden" id="elementor-control-media-url-{{ data._cid }}" value="{{ data.controlValue.value && data.controlValue.value.url || '' }}">
                <# }
                if ( ! data.exclude_inline_options.includes( 'icon' ) ) { #>
                    <input id="<?php echo $control_uid; ?>-icon" type="radio" value="icon">
                    <label class="elementor-choices-label elementor-control-unit-1 tooltip-target elementor-control-icons--inline__icon" for="<?php echo $control_uid; ?>-icon" data-tooltip="<?php _e('Icon Library'); ?>" title="<?php _e('Icon Library'); ?>">
                        <span class="elementor-control-icons--inline__displayed-icon">
                            <i class="eicon-icons-solid" aria-hidden="true"></i>
                        </span>
                        <span class="elementor-screen-only"><?php _e('Icon Library'); ?></span>
                    </label>
                <# } #>
                </div>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

    /**
     * Get Icons control default settings.
     *
     * Retrieve the default settings of the Icons control. Used to return the default
     * settings while initializing the Icons control.
     *
     * @since 2.6.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'label_block' => true,
            'dynamic' => [
                'categories' => ['image'],
                'returnType' => 'object',
            ],
            'search_bar' => true,
            'recommended' => false,
            'skin' => 'media',
            'exclude_inline_options' => [],
        ];
    }

    // public function supportSvgImport($mimes)

    public function onImport($settings)
    {
        if (empty($settings['library']) || 'svg' !== $settings['library'] || empty($settings['value']['url'])) {
            return $settings;
        }

        // add_filter('upload_mimes', [$this, 'supportSvgImport'], 100);

        $imported = Plugin::$instance->templates_manager->getImportImagesInstance()->import($settings['value']);

        // remove_filter('upload_mimes', [$this, 'supportSvgImport'], 100);

        if (!$imported) {
            $settings['value'] = '';
            $settings['library'] = '';
        } else {
            $settings['value'] = $imported;
        }

        return $settings;
    }
}
