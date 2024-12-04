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

use CE\CoreXBaseXModule as BaseModule;

/**
 * Elementor history module.
 *
 * Elementor history module handler class is responsible for registering and
 * managing Elementor history modules.
 *
 * @since 1.7.0
 */
class ModulesXHistoryXModule extends BaseModule
{
    /**
     * Get module name.
     *
     * Retrieve the history module name.
     *
     * @since 1.7.0
     *
     * @return string Module name
     */
    public function getName()
    {
        return 'history';
    }

    /**
     * Localize settings.
     *
     * Add new localized settings for the history module.
     *
     * Fired by `elementor/editor/localize_settings` filter.
     *
     * @since 1.7.0
     *
     * @param array $settings Localized settings
     *
     * @return array Localized settings
     */
    public function localizeSettings($settings)
    {
        $settings = array_replace_recursive($settings, [
            'i18n' => [
                'history' => __('History'),
                'template' => __('Template'),
                'added' => __('Added'),
                'removed' => __('Removed'),
                'edited' => __('Edited'),
                'moved' => __('Moved'),
                'pasted' => __('Pasted'),
                'editing_started' => __('Editing Started'),
                'style_pasted' => __('Style Pasted'),
                'style_reset' => __('Style Reset'),
                'settings_reset' => __('Settings Reset'),
                'enabled' => __('Enabled'),
                'disabled' => __('Disabled'),
                'all_content' => __('All Content'),
                'elements' => __('Elements'),
            ],
        ]);

        return $settings;
    }

    /**
     * @since 2.3.0
     */
    public function addTemplates()
    {
        Plugin::$instance->common->addTemplate(_CE_PATH_ . 'modules/history/views/history-panel-template.php');
        Plugin::$instance->common->addTemplate(_CE_PATH_ . 'modules/history/views/revisions-panel-template.php');
    }

    /**
     * History module constructor.
     *
     * Initializing Elementor history module.
     *
     * @since 1.7.0
     */
    public function __construct()
    {
        add_filter('elementor/editor/localize_settings', [$this, 'localizeSettings']);

        add_action('elementor/editor/init', [$this, 'addTemplates']);
    }
}
