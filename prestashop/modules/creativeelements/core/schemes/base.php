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
 * Elementor scheme base.
 *
 * An abstract class implementing the scheme interface, responsible for
 * creating new schemes.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class CoreXSchemesXBase
{
    /**
     * DB option name for the time when the scheme was last updated.
     */
    const LAST_UPDATED_META = '_elementor_scheme_last_updated';

    /**
     * Get scheme type.
     *
     * Retrieve the scheme type.
     *
     * @since 2.8.0
     * @static
     */
    public static function getType()
    {
        return '';
    }

    /**
     * Get default scheme.
     *
     * Retrieve the default scheme.
     *
     * @since 2.8.0
     */
    abstract public function getDefaultScheme();

    /**
     * Get description.
     *
     * Retrieve the scheme description.
     *
     * @since 1.0.0
     * @static
     *
     * @return string Scheme description
     */
    public static function getDescription()
    {
        return '';
    }

    /**
     * Get scheme value.
     *
     * Retrieve the scheme value.
     *
     * @since 1.0.0
     *
     * @return array Scheme value
     */
    public function getSchemeValue()
    {
        $scheme_value = get_option('elementor_scheme_' . static::getType());

        if (!$scheme_value) {
            $scheme_value = $this->getDefaultScheme();

            update_option('elementor_scheme_' . static::getType(), $scheme_value);
        }

        return $scheme_value;
    }

    /**
     * Save scheme.
     *
     * Update Elementor scheme in the database, and update the last updated
     * scheme time.
     *
     * @since 1.0.0
     *
     * @param array $posted
     */
    public function saveScheme(array $posted)
    {
        update_option('elementor_scheme_' . static::getType(), $posted);

        update_option(self::LAST_UPDATED_META, time());
    }

    /**
     * Get scheme.
     *
     * Retrieve the scheme.
     *
     * @since 1.0.0
     *
     * @return array The scheme
     */
    public function getScheme()
    {
        $scheme = [];

        foreach ($this->getSchemeValue() as $scheme_key => $scheme_value) {
            $scheme[$scheme_key] = [
                'value' => $scheme_value,
            ];
        }

        return $scheme;
    }
}
