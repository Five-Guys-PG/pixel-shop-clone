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

abstract class BaseIconFont
{
    /**
     * Get Icon type.
     *
     * Retrieve the icon type.
     *
     * @abstract
     */
    abstract public function getType();

    /**
     * Enqueue Icon scripts and styles.
     *
     * Used to register and enqueue custom scripts and styles used by the Icon.
     */
    abstract public function enqueue();

    /**
     * get_css_prefix
     *
     * @return string
     */
    abstract public function getCssPrefix();

    abstract public function getIcons();

    public function __construct()
    {
    }
}
