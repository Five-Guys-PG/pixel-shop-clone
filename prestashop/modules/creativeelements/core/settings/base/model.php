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
 * Elementor settings base model.
 *
 * Elementor settings base model handler class is responsible for registering
 * and managing Elementor settings base models.
 *
 * @since 1.6.0
 * @abstract
 */
abstract class CoreXSettingsXBaseXModel extends ControlsStack
{
    /**
     * Get panel page settings.
     *
     * Retrieve the page setting for the current panel.
     *
     * @since 1.6.0
     * @abstract
     */
    abstract public function getPanelPageSettings();
}
