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

use CE\CoreXSchemesXBase as Base;
use CE\CoreXSettingsXManager as SettingsManager;

/**
 * Elementor color picker scheme.
 *
 * Elementor color picker scheme class is responsible for initializing a scheme
 * for color pickers.
 *
 * @since 1.0.0
 */
class CoreXSchemesXColorPicker extends Base
{
    /**
     * Get color picker scheme type.
     *
     * Retrieve the color picker scheme type.
     *
     * @since 1.0.0
     * @static
     *
     * @return string Color picker scheme type
     */
    public static function getType()
    {
        return 'color-picker';
    }

    /**
     * Get default color picker scheme.
     *
     * Retrieve the default color picker scheme.
     *
     * @since 1.0.0
     *
     * @return array Default color picker scheme
     */
    public function getDefaultScheme()
    {
        return [
            1 => '#6ec1e4',
            2 => '#54595f',
            3 => '#7a7a7a',
            4 => '#61ce70',
            5 => '#4054b2',
            6 => '#23a455',
            7 => '#000',
            8 => '#fff',
        ];
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
        $scheme_value = [];

        $document = Plugin::$instance->documents->getCurrent();
        $uid = $document && $document->getTemplateType() === 'kit'
            ? $document->getId()
            : Plugin::$instance->kits_manager->getActiveId();
        $settings = get_post_meta($uid, '_elementor_page_settings', true) ?: [];

        isset($settings['custom_colors']) || $this->saveCustomColors($uid, $settings, $this->getDefaultScheme());

        // $scheme_value = get_option('elementor_scheme_' . static::getType());
        foreach ($settings['custom_colors'] as $i => &$item) {
            $scheme_value[$i + 1] = $item['color'];
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
        $document = \Tools::getValue('action') === 'elementor_ajax'
            ? Plugin::$instance->documents->get(\Tools::getValue('editor_post_id'))
            : Plugin::$instance->documents->getCurrent();
        $uid = $document && $document->getTemplateType() === 'kit'
            ? $document->getId()
            : Plugin::$instance->kits_manager->getActiveId();
        $settings = get_post_meta($uid, '_elementor_page_settings', true) ?: [];

        $this->saveCustomColors($uid, $settings, $posted);
    }

    private function saveCustomColors($uid, array &$settings, array $colors)
    {
        $settings['custom_colors'] = [];

        foreach ($colors as $i => $color) {
            $settings['custom_colors'][] = [
                '_id' => Utils::generateRandomString(),
                'title' => "Color #$i",
                'color' => $color,
            ];
        }
        $page_settings_manager = SettingsManager::getSettingsManagers('page');
        $page_settings_manager->saveSettings($settings, "$uid");
    }
}
