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

use CE\CoreXBaseXBaseObject as BaseObject;
use CE\CoreXCommonXModulesXAjaxXModule as Ajax;

class CoreXEditorXNoticeBar extends BaseObject
{
    protected function getInitSettings()
    {
        // todo
        if (Plugin::$instance->getInstallTime() > strtotime('-30 days') || true) {
            return [];
        }

        return [
            'muted_period' => 90,
            'option_key' => '_elementor_editor_upgrade_notice_dismissed',
            'message' => __('Love using Elementor? <a href="%s">Learn how you can build better sites with Elementor Pro.</a>'),
            'action_title' => __('Get Pro'),
            'action_url' => 'javascript:void("todo")',
        ];
    }

    final public function getNotice()
    {
        if (!current_user_can('manage_options')) {
            return null;
        }

        $settings = $this->getSettings();

        if (empty($settings['option_key'])) {
            return null;
        }

        $dismissed_time = get_option($settings['option_key']);

        if ($dismissed_time) {
            if ($dismissed_time > strtotime('-' . $settings['muted_period'] . ' days')) {
                return null;
            }

            $this->setNoticeDismissed();
        }

        return $settings;
    }

    public function __construct()
    {
        add_action('elementor/ajax/register_actions', [$this, 'registerAjaxActions']);
    }

    public function setNoticeDismissed()
    {
        update_option($this->getSettings('option_key'), time());
    }

    public function registerAjaxActions(Ajax $ajax)
    {
        $ajax->registerAjaxAction('notice_bar_dismiss', [$this, 'setNoticeDismissed']);
    }
}
