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
 * Elementor media control.
 *
 * A base control for creating a media chooser control. Based on the PrestaShop
 * file manager. Used to select an image from the PrestaShop file manager.
 *
 * @since 1.0.0
 */
class ControlMedia extends ControlBaseMultiple
{
    /**
     * Get media control type.
     *
     * Retrieve the control type, in this case `media`.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'media';
    }

    /**
     * Get media control default values.
     *
     * Retrieve the default value of the media control. Used to return the default
     * values while initializing the media control.
     *
     * @since 1.0.0
     *
     * @return array Control default value
     */
    public function getDefaultValue()
    {
        return [
            'id' => '',
            'url' => '',
        ];
    }

    /**
     * Import media images.
     *
     * Used to import media control files from external sites while importing
     * Elementor template JSON file, and replacing the old data.
     *
     * @since 1.0.0
     *
     * @param array $settings Control settings
     *
     * @return array Control settings
     */
    public function onImport($settings)
    {
        if (empty($settings['url'])) {
            return $settings;
        }

        $settings = Plugin::$instance->templates_manager->getImportImagesInstance()->import($settings);

        if (!$settings) {
            $settings = [
                'id' => '',
                'url' => Utils::getPlaceholderImageSrc(),
            ];
        }

        return $settings;
    }

    public function onExport($settings)
    {
        if (!empty($settings['url'])) {
            $settings['url'] = Helper::getMediaLink($settings['url'], true);
        }

        return $settings;
    }

    /**
     * Render media control output in the editor.
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
        <div class="elementor-control-field elementor-control-media{{ data.seo ? ' ce-control-media-seo' : '' }}">
            <label class="elementor-control-title">{{{ data.label }}}</label>
        <# if ( 'image' === data.media_type || 'video' === data.media_type ) { #>
            <div class="elementor-units-choices">
                <label class="elementor-units-choices-label elementor-control-media-url"><?php _e('URL'); ?><i class="eicon-edit"></i></label>
                <input type="radio" id="elementor-control-media-url-{{ data._cid }}" value="{{ data.controlValue.url }}">
            </div>
            <div class="elementor-control-input-wrapper elementor-aspect-ratio-219">
                <div class="elementor-control-media__content elementor-control-tag-area elementor-control-preview-area elementor-fit-aspect-ratio">
                    <div class="elementor-control-media-upload-button elementor-control-media__content__upload-button elementor-fit-aspect-ratio">
                        <i class="eicon-plus-circle" aria-hidden="true"></i>
                    </div>
                    <div class="elementor-control-media-area elementor-fit-aspect-ratio">
                        <div class="elementor-control-media__remove elementor-control-media__content__remove" title="<?php _e('Remove'); ?>">
                            <i class="eicon-trash"></i>
                        </div>
                    <# if( 'image' === data.media_type ) { #>
                        <div class="elementor-control-media__preview elementor-fit-aspect-ratio"></div>
                    <# } else if( 'video' === data.media_type ) { #>
                        <video class="elementor-control-media-video" preload="metadata"></video>
                        <i class="eicon-video-camera"></i>
                    <# } #>
                    </div>
                    <div class="elementor-control-media__tools elementor-control-dynamic-switcher-wrapper">
                    <# if( 'image' === data.media_type ) { #>
                        <div class="elementor-control-media__tool elementor-control-media__replace"><?php _e('Choose Image'); ?></div>
                        <# if( data.seo ) { #>
                            <div class="elementor-control-media__tool elementor-control-media__options elementor-control-unit-1 tooltip-target" data-tooltip="<?php _e('Settings'); ?>">
                                <i class="eicon-cog" aria-hidden="true"></i>
                            </div>
                        <# } #>
                    <# } else if( 'video' === data.media_type ) { #>
                        <div class="elementor-control-media__tool elementor-control-media__replace"><?php _e('Choose Video'); ?></div>
                    <# } #>
                    </div>
                </div>
            </div>
            <# if( data.seo ) { #>
                <div class="ce-control-media-options elementor-hidden">
                    <div class="ce-control-media-option">
                        <label for="<?php echo $this->getControlUid('alt'); ?>" class="elementor-control-media__loading-label"><?php _e('Alt'); ?></label>
                        <input type="text" id="<?php echo $this->getControlUid('alt'); ?>" class="elementor-control-unit-5" data-setting="alt">
                    </div>
                    <div class="ce-control-media-option">
                        <label for="<?php echo $this->getControlUid('title'); ?>" class="elementor-control-media__loading-label"><?php _e('Title'); ?></label>
                        <input type="text" id="<?php echo $this->getControlUid('title'); ?>" class="elementor-control-unit-5" data-setting="title">
                    </div>
                    <div class="ce-control-media-option">
                        <label for="<?php echo $this->getControlUid('loading'); ?>" class="elementor-control-media__loading-label"><?php _e('Loading'); ?></label>
                        <select id="<?php echo $this->getControlUid('loading'); ?>" class="elementor-control-unit-5" data-setting="loading">
                            <option value=""><?php _e('Lazy'); ?></option>
                            <option value="eager"><?php _e('Eager'); ?></option>
                        </select>
                    </div>
                </div>
            <# } #>
        <# } else { #>
            <div class="elementor-control-media__file elementor-control-preview-area">
                <div class="elementor-control-media__file__content">
                    <div class="elementor-control-media__file__content__label"><?php _e('Click the media icon to upload file'); ?></div>
                    <div class="elementor-control-media__file__content__info">
                        <div class="elementor-control-media__file__content__info__icon">
                            <i class="eicon-document-file"></i>
                        </div>
                        <div class="elementor-control-media__file__content__info__name"></div>
                    </div>
                </div>
                <div class="elementor-control-media__file__controls">
                    <div class="elementor-control-media__remove elementor-control-media__file__controls__remove" title="<?php _e('Remove'); ?>">
                        <i class="eicon-trash-o"></i>
                    </div>
                    <div class="elementor-control-media__file__controls__upload-button elementor-control-media-upload-button" title="<?php _e('Upload'); ?>">
                        <i class="eicon-upload"></i>
                    </div>
                </div>
            </div>
        <# } #>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
            <input type="hidden" data-setting="{{ data.name }}" />
        </div>
        <?php
    }

    /**
     * Get media control default settings.
     *
     * Retrieve the default settings of the media control. Used to return the default
     * settings while initializing the media control.
     *
     * @since 1.0.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'label_block' => true,
            'media_type' => 'image',
            'dynamic' => [
                'categories' => [TagsModule::IMAGE_CATEGORY],
                'returnType' => 'object',
            ],
        ];
    }

    /**
     * Get media control image title.
     *
     * Retrieve the `title` of the image selected by the media control.
     *
     * @deprecated since 2.11.0 and will be removed in next mayor version.
     * @static
     *
     * @param array $instance Media attachment
     *
     * @return string Image title
     */
    public static function getImageTitle($instance)
    {
        return !empty($instance['title']) ? esc_attr($instance['title']) : '';
    }

    /**
     * Get media control image alt.
     *
     * Retrieve the `alt` value of the image selected by the media control.
     *
     * @deprecated since 2.11.0 and will be removed in next mayor version.
     * @static
     *
     * @param array $instance Media attachment
     *
     * @return string Image alt
     */
    public static function getImageAlt($instance)
    {
        return !empty($instance['alt']) ? esc_attr($instance['alt']) : '';
    }
}
