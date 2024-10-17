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

use CE\CoreXSettingsXBaseXManager as BaseManager;

class CoreXSettingsXEditorPreferencesXManager extends BaseManager
{
    const META_KEY = 'elementor_preferences';

    /**
     * Get model for config.
     *
     * Retrieve the model for settings configuration.
     *
     * @since 2.8.0
     *
     * @return BaseModel The model object
     */
    public function getModelForConfig()
    {
        return $this->getModel();
    }

    /**
     * Get manager name.
     *
     * Retrieve settings manager name.
     *
     * @since 2.8.0
     */
    public function getName()
    {
        return 'editorPreferences';
    }

    /**
     * Get saved settings.
     *
     * Retrieve the saved settings from the database.
     *
     * @since 2.8.0
     *
     * @param int $id
     *
     * @return array
     */
    protected function getSavedSettings($id)
    {
        $settings = [];

        if ($user_id = get_current_user_id()) {
            $settings = get_user_meta($user_id, self::META_KEY, true) ?: [];
        }

        return $settings;
    }

    /**
     * Save settings to DB.
     *
     * Save settings to the database.
     *
     * @param array $settings Settings
     * @param int $id Post ID
     *
     * @since 2.8.0
     */
    protected function saveSettingsToDb(array $settings, $id)
    {
        update_user_meta(get_current_user_id(), self::META_KEY, $settings);
    }
}
