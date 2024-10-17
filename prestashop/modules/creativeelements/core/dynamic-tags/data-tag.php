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

use CE\CoreXDynamicTagsXBaseTag as BaseTag;

/**
 * Elementor base data tag.
 *
 * An abstract class to register new Elementor data tags.
 *
 * @since 2.0.0
 * @abstract
 */
abstract class CoreXDynamicTagsXDataTag extends BaseTag
{
    private static $getter_method = 'getValue';

    public static function setGetterMethod($method)
    {
        self::$getter_method = $method;
    }

    /**
     * @since 2.0.0
     * @abstract
     *
     * @param array $options
     */
    abstract protected function getValue(array $options = []);

    /**
     * @since 2.5.10
     *
     * @return mixed
     */
    protected function getSmartyValue(array $options = [])
    {
        return '{literal}' . $this->getValue($options) . '{/literal}';
    }

    /**
     * @since 2.0.0
     */
    final public function getContentType()
    {
        return 'plain';
    }

    /**
     * @since 2.0.0
     *
     * @param array $options
     *
     * @return mixed
     */
    public function getContent(array $options = [])
    {
        return static::REMOTE_RENDER && is_admin() && 'getValue' === self::$getter_method ? null : $this->{self::$getter_method}($options);
    }
}
