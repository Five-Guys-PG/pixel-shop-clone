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

use CE\CoreXFilesXCSSXBase as CSSFile;
use CE\CoreXSettingsXBaseXManager as Manager;

abstract class CoreXSettingsXBaseXCssManager extends Manager
{
    /**
     * Get CSS file name.
     *
     * Retrieve CSS file name for the settings base css manager.
     *
     * @since 2.8.0
     * @abstract
     *
     * @return string CSS file name
     */
    abstract protected function getCssFileName();

    /**
     * Get model for CSS file.
     *
     * Retrieve the model for the CSS file.
     *
     * @since 2.8.0
     * @abstract
     *
     * @param CSSFile $css_file The requested CSS file
     *
     * @return CSSModel
     */
    abstract protected function getModelForCssFile(CSSFile $css_file);

    /**
     * Get CSS file for update.
     *
     * Retrieve the CSS file before updating it.
     *
     * @since 2.8.0
     * @abstract
     *
     * @param int $id Post ID
     *
     * @return CSSFile
     */
    abstract protected function getCssFileForUpdate($id);

    /**
     * Settings base manager constructor.
     *
     * Initializing Elementor settings base css manager.
     *
     * @since 2.8.0
     */
    public function __construct()
    {
        parent::__construct();

        $name = $this->getCssFileName();

        add_action("elementor/css-file/{$name}/parse", [$this, 'addSettingsCssRules']);
    }

    /**
     * Save settings.
     *
     * Save settings to the database and update the CSS file.
     *
     * @since 2.8.0
     *
     * @param array $settings Settings
     * @param int $id Optional. Post ID. Default is `0`
     */
    public function saveSettings(array $settings, $id = 0)
    {
        parent::saveSettings($settings, $id);

        $css_file = $this->getCssFileForUpdate($id);

        if ($css_file) {
            $css_file->update();
        }
    }

    /**
     * Add settings CSS rules.
     *
     * Add new CSS rules to the settings manager.
     *
     * Fired by `elementor/css-file/{$name}/parse` action.
     *
     * @since 2.8.0
     *
     * @param CSSFile $css_file The requested CSS file
     */
    public function addSettingsCssRules(CSSFile $css_file)
    {
        $model = $this->getModelForCssFile($css_file);

        $css_file->addControlsStackStyleRules(
            $model,
            $model->getStyleControls(),
            $model->getSettings(),
            ['{{WRAPPER}}'],
            [$model->getCssWrapperSelector()]
        );
    }
}
