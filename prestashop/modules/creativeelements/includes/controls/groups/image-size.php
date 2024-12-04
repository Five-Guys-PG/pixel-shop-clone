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
 * Elementor image size control.
 *
 * A base control for creating image size control. Displays input fields to define
 * one of the default image sizes (thumbnail, medium, medium_large, large) or custom
 * image dimensions.
 *
 * @since 1.0.0
 */
class GroupControlImageSize extends GroupControlBase
{
    /**
     * Fields.
     *
     * Holds all the image size control fields.
     *
     * @since 1.2.2
     * @static
     *
     * @var array Image size control fields
     */
    protected static $fields;

    /**
     * Get image size control type.
     *
     * Retrieve the control type, in this case `image-size`.
     *
     * @since 1.0.0
     * @static
     *
     * @return string Control type
     */
    public static function getType()
    {
        return 'image-size';
    }

    /**
     * Get attachment image HTML.
     *
     * Retrieve the attachment image HTML code.
     *
     * Note that some widgets use the same key for the media control that allows
     * the image selection and for the image size control that allows the user
     * to select the image size, in this case the third parameter should be null
     * or the same as the second parameter. But when the widget uses different
     * keys for the media control and the image size control, when calling this
     * method you should pass the keys.
     *
     * @since 1.0.0
     * @static
     *
     * @param array $settings Control settings
     * @param string $image_key Optional. Settings key for image. Default
     *                          is null. If not defined uses image size key
     *                          as the image key.
     * @param string $class Optional. Class attribute
     *
     * @return string Image HTML
     */
    public static function getAttachmentImageHtml($settings, $setting_key = 'image', $class = '')
    {
        if (empty($settings[$setting_key]['url'])) {
            return '';
        }
        $attr = [
            'src' => Helper::getMediaLink($settings[$setting_key]['url']),
            'alt' => isset($settings[$setting_key]['alt']) ? $settings[$setting_key]['alt'] : '',
        ];
        empty($settings[$setting_key]['loading'])
            && $attr['loading'] = 'lazy';
        empty($settings[$setting_key]['title'])
            || $attr['title'] = $settings[$setting_key]['title'];
        empty($settings[$setting_key]['width'])
            || $attr['width'] = $settings[$setting_key]['width'];
        empty($settings[$setting_key]['height'])
            || $attr['height'] = $settings[$setting_key]['height'];
        empty($settings['hover_animation'])
            || $class .= ($class ? ' ' : '') . 'elementor-animation-' . $settings['hover_animation'];
        $class && $attr['class'] = $class;

        return '<img ' . Utils::renderHtmlAttributes($attr) . '>';
    }

    /**
     * Get all image sizes.
     *
     * @since 2.0.0
     *
     * @param string $type Image type [`products`, `categories`, `manufacturers`, `suppliers`, `stores`]
     * @param bool $full Add full size option (Optional)
     *
     * @return array
     */
    public static function getAllImageSizes($type, $full = false)
    {
        static $options = [];

        if (empty($options[$type])) {
            $options[$type] = [];
            $sizes = \ImageType::getImagesTypes($type);

            usort($sizes, function ($a, $b) {
                return ($b['width'] * $b['height']) - ($a['width'] * $a['height']);
            });
            foreach ($sizes as &$size) {
                $options[$type][$size['name']] = "{$size['name']} ({$size['width']} × {$size['height']})";
            }
        }

        return $full ? ['' => _x('Full', 'Image Size Control')] + $options[$type] : $options[$type];
    }

    // public static function getAttachmentImageSrc($attachment_id, $image_size_key, array $settings)

    // protected function getChildDefaultArgs()

    /**
     * Init fields.
     *
     * Initialize image size control fields.
     *
     * @since 1.2.2
     *
     * @return array Control fields
     */
    protected function initFields()
    {
        $fields = [];

        $fields['size'] = [
            'label' => _x('Image Size', 'Image Size Control'),
            'type' => ControlsManager::SELECT,
        ];

        // $fields['custom_dimension'] = [
        //     'label' => _x('Image Dimension', 'Image Size Control'),
        //     'type' => ControlsManager::IMAGE_DIMENSIONS,
        //     'description' => __('You can crop the original image size to any custom size. You can also set a single value for height or width in order to keep the original size ratio.'),
        //     'condition' => [
        //         'size' => 'custom',
        //     ],
        //     'separator' => 'none',
        // ];

        return $fields;
    }

    // protected function prepareFields($fields)

    // private function getImageSizes()

    // protected function getDefaultOptions()
}
