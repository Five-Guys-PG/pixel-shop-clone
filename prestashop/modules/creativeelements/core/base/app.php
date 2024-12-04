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
 * Base App
 *
 * Base app utility class that provides shared functionality of apps.
 *
 * @since 2.3.0
 */
abstract class CoreXBaseXApp extends CoreXBaseXModule
{
    /**
     * Print config.
     *
     * Used to print the app and its components settings as a JavaScript object.
     *
     * @param string $handle Optional
     *
     * @since 2.3.0
     * @since 2.6.0 added the `$handle` parameter
     */
    final protected function printConfig($handle = null)
    {
        $name = $this->getName();

        $js_var = 'frontend' === $name ? 'ceFrontendConfig' : 'elementor' . str_replace(' ', '', ucwords(str_replace('-', ' ', $name))) . 'Config';

        $config = $this->getSettings() + $this->getComponentsConfig();

        if (!$handle) {
            $handle = 'elementor-' . $name;
        }

        wp_localize_script($handle, $js_var, $config);
    }

    /**
     * Get components config.
     *
     * Retrieves the app components settings.
     *
     * @since 2.3.0
     *
     * @return array
     */
    private function getComponentsConfig()
    {
        $settings = [];

        foreach ($this->getComponents() as $id => $instance) {
            $settings[$id] = $instance->getSettings();
        }

        return $settings;
    }
}
