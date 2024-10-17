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

use CE\CoreXDynamicTagsXTag as Tag;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXDynamicTagsXTagsXRequestParameter extends Tag
{
    public function getName()
    {
        return 'request-arg';
    }

    public function getTitle()
    {
        return __('Request Parameter');
    }

    public function getGroup()
    {
        return Module::SITE_GROUP;
    }

    public function getCategories()
    {
        return [Module::TEXT_CATEGORY];
    }

    public function getPanelTemplateSettingKey()
    {
        return 'param_name';
    }

    protected function _registerControls()
    {
        $this->addControl(
            'request_type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'default' => 'get',
                'options' => [
                    'get' => 'GET',
                    'post' => 'POST',
                    'query_var' => 'REQUEST',
                ],
            ]
        );
        $this->addControl(
            'param_name',
            [
                'label' => __('Parameter Name'),
                'type' => ControlsManager::TEXT,
            ]
        );
    }

    public function render()
    {
        $settings = $this->getSettings();

        if (empty($settings['param_name'])) {
            return;
        }
        $param_name = $settings['param_name'];
        $value = '';

        switch ($settings['request_type']) {
            case 'get':
                empty(${'_GET'}[$param_name]) || $value = ${'_GET'}[$param_name];
                break;
            case 'post':
                empty(${'_POST'}[$param_name]) || $value = ${'_POST'}[$param_name];
                break;
            case 'query_var':
                $value = \Tools::getValue($param_name);
                break;
        }
        echo htmlentities(wp_kses_post($value));
    }
}
