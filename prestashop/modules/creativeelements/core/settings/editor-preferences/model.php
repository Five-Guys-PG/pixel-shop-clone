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

use CE\CoreXSettingsXBaseXModel as BaseModel;

class CoreXSettingsXEditorPreferencesXModel extends BaseModel
{
    /**
     * Get element name.
     *
     * Retrieve the element name.
     *
     * @return string The name
     *
     * @since 2.8.0
     */
    public function getName()
    {
        return 'editor-preferences';
    }

    /**
     * Get panel page settings.
     *
     * Retrieve the page setting for the current panel.
     *
     * @since 2.8.0
     */
    public function getPanelPageSettings()
    {
        return [
            'title' => __('Editor Preferences'),
        ];
    }

    /**
     * @since 2.8.0
     */
    protected function _registerControls()
    {
        $this->startControlsSection('preferences', [
            'tab' => ControlsManager::TAB_SETTINGS,
            'label' => __('Preferences'),
        ]);

        $this->addControl(
            'ui_theme',
            [
                'label' => __('UI Theme'),
                'type' => ControlsManager::SELECT,
                'description' => __('Set light or dark mode, or use Auto Detect to sync it with your OS setting.'),
                'default' => 'auto',
                'options' => [
                    'auto' => __('Auto Detect'),
                    'light' => __('Light'),
                    'dark' => __('Dark'),
                ],
            ]
        );

        $this->addControl(
            'edit_buttons',
            [
                'label' => __('Editing Handles'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'description' => __('Show editing handles when hovering over the element edit button.'),
            ]
        );

        $this->addControl(
            'lightbox_in_editor',
            [
                'label' => __('Enable Lightbox In Editor'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->endControlsSection();
    }
}
