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
 * Elementor utils.
 *
 * Elementor utils handler class is responsible for different utility methods
 * used by Elementor.
 *
 * @since 1.0.0
 */
class Utils
{
    // const DEPRECATION_RANGE = 0.4;

    /**
     * Is script debug.
     *
     * Whether script debug is enabled or not.
     *
     * @since 1.0.0
     * @static
     *
     * @return bool True if it's a script debug is active, false otherwise
     */
    public static function isScriptDebug()
    {
        return _PS_MODE_DEV_;
    }

    // public static function getProLink($link)

    // public static function replaceUrls($from, $to)

    /**
     * Is post supports Elementor.
     *
     * Whether the post supports editing with Elementor.
     *
     * @since 1.0.0
     * @static
     *
     * @param int $post_id Optional. Post ID. Default is `0`
     *
     * @return string True if post supports editing with Elementor, false otherwise
     */
    public static function isPostSupport($post_id = 0)
    {
        $post_type = get_post_type($post_id);

        $is_supported = self::isPostTypeSupport($post_type);

        // $is_supported = apply_filters('elementor/utils/is_post_support', $is_supported, $post_id, $post_type);

        return $is_supported;
    }

    /**
     * Is post type supports Elementor.
     *
     * Whether the post type supports editing with Elementor.
     *
     * @since 2.2.0
     * @static
     *
     * @param string $post_type Post Type
     *
     * @return string True if post type supports editing with Elementor, false otherwise
     */
    public static function isPostTypeSupport($post_type)
    {
        if (!post_type_exists($post_type)) {
            return false;
        }

        // if (!post_type_supports($post_type, 'elementor')) {
        //     return false;
        // }

        return true;
    }

    /**
     * Get placeholder image source.
     *
     * Retrieve the source of the placeholder image.
     *
     * @since 1.0.0
     * @static
     *
     * @return string The source of the default placeholder image used by Elementor
     */
    public static function getPlaceholderImageSrc()
    {
        $placeholder_image = basename(_MODULE_DIR_) . '/creativeelements/views/img/placeholder.png';

        // $placeholder_image = apply_filters('elementor/utils/get_placeholder_image_src', $placeholder_image);

        return $placeholder_image;
    }

    /**
     * Generate random string.
     *
     * Returns a string containing a hexadecimal representation of random number.
     *
     * @since 1.0.0
     * @static
     *
     * @return string Random string
     */
    public static function generateRandomString()
    {
        return dechex(rand());
    }

    // public static function doNotCache();

    /**
     * Get timezone string.
     *
     * Retrieve timezone string from the database.
     *
     * @since 1.0.0
     * @static
     *
     * @return string Timezone string
     */
    public static function getTimezoneString()
    {
        return \Configuration::get('PS_TIMEZONE');
    }

    // public static function getCreateNewPostUrl($post_type = 'page')

    /**
     * Get post autosave.
     *
     * Retrieve an autosave for any given post.
     *
     * @since 1.9.2
     * @static
     *
     * @param int $post_id Post ID
     * @param int $user_id Optional. User ID. Default is `0`
     *
     * @return WPPost|false Post autosave or false
     */
    public static function getPostAutosave($post_id, $user_id = 0)
    {
        $autosave = wp_get_post_autosave($post_id, $user_id);

        return $autosave && strtotime($autosave->post_modified) > strtotime(get_post($post_id)->post_modified) ? $autosave : false;
    }

    /**
     * Is CPT supports custom templates.
     *
     * Whether the Custom Post Type supports templates.
     *
     * @since 2.0.0
     * @static
     *
     * @return bool True is templates are supported, False otherwise
     */
    public static function isCptCustomTemplatesSupported(WPPost $post)
    {
        // return method_exists(wp_get_theme(), 'get_post_templates');

        return UId::CONTENT !== $post->uid->id_type
            && $post->template_type !== 'product-quick-view'
            && $post->template_type !== 'product-miniature';
    }

    /**
     * @since 2.1.2
     * @static
     */
    public static function arrayInject($array, $key, $insert)
    {
        $length = array_search($key, array_keys($array), true) + 1;

        return array_slice($array, 0, $length, true) + $insert + array_slice($array, $length, null, true);
    }

    /**
     * Render html attributes
     *
     * @static
     *
     * @param array $attributes
     *
     * @return string
     */
    public static function renderHtmlAttributes(array $attributes)
    {
        $rendered_attributes = [];

        foreach ($attributes as $attribute_key => $attribute_values) {
            if ([] === $attribute_values) {
                $rendered_attributes[] = $attribute_key;

                continue;
            }
            if (is_array($attribute_values)) {
                $attribute_values = implode(' ', $attribute_values);
            }

            $rendered_attributes[] = $attribute_key . '="' . esc_attr($attribute_values) . '"';
        }

        return implode(' ', $rendered_attributes);
    }

    // public static function getMetaViewport($context = '')

    // public static function printJsConfig($handle, $js_var, $config) - Use wp_localize_script instead

    // public static function handleDeprecation($item, $version, $replacement = null)

    // public static function isEmpty(&$source, $key = false)

    public static function hasPro()
    {
        return true;
    }

    /**
     * Convert HTMLEntities to UTF-8 characters
     *
     * @param $string
     *
     * @return string
     */
    public static function urlencodeHtmlEntities($string)
    {
        $entities_dictionary = [
            '&#145;' => "'", // Opening single quote
            '&#146;' => "'", // Closing single quote
            '&#147;' => '"', // Closing double quote
            '&#148;' => '"', // Opening double quote
            '&#8216;' => "'", // Closing single quote
            '&#8217;' => "'", // Opening single quote
            '&#8218;' => "'", // Single low quote
            '&#8220;' => '"', // Closing double quote
            '&#8221;' => '"', // Opening double quote
            '&#8222;' => '"', // Double low quote
        ];

        // Decode decimal entities
        $string = str_replace(array_keys($entities_dictionary), array_values($entities_dictionary), $string);

        return rawurlencode(html_entity_decode($string, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    /**
     * Parse attributes that come as a string of comma-delimited key|value pairs.
     * Removes Javascript events and unescaped `href` attributes.
     *
     * @param string $attributes_string
     * @param string $delimiter Default comma `,`
     *
     * @return array
     */
    public static function parseCustomAttributes($attributes_string, $delimiter = ',')
    {
        $attributes = explode($delimiter, $attributes_string);
        $result = [];

        foreach ($attributes as $attribute) {
            $attr_key_value = explode('|', $attribute);

            $attr_key = \Tools::strtolower($attr_key_value[0]);

            // Remove any not allowed characters.
            preg_match('/[-_a-z0-9]+/', $attr_key, $attr_key_matches);

            if (empty($attr_key_matches[0])) {
                continue;
            }

            $attr_key = $attr_key_matches[0];

            // Avoid Javascript events and unescaped href.
            if ('href' === $attr_key || 'on' === substr($attr_key, 0, 2)) {
                continue;
            }

            if (isset($attr_key_value[1])) {
                $attr_value = trim($attr_key_value[1]);
            } else {
                $attr_value = '';
            }

            $result[$attr_key] = $attr_value;
        }

        return $result;
    }
}
