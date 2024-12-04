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

class ModulesXDynamicTagsXTagsXUserInfo extends Tag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'user-info';
    }

    public function getTitle()
    {
        return __('Customer Info');
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
        return 'type';
    }

    protected function _registerControls()
    {
        $this->addControl(
            'type',
            [
                'label' => __('Field'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Select...'),
                    'id' => __('ID'),
                    'display_name' => __('Display Name'),
                    'first_name' => __('First Name'),
                    'last_name' => __('Last Name'),
                    'birthday' => __('Birthday'),
                    'company' => __('Company'),
                    'email' => __('Email'),
                    'url' => __('Website'),
                ],
            ]
        );
    }

    public function render()
    {
        $customer = \Context::getContext()->customer;

        if (empty($customer->id)) {
            return;
        }
        $type = $this->getSettings('type');

        switch ($type) {
            case 'id':
            case 'email':
            case 'birthday':
            case 'company':
                echo $customer->$type;
                break;
            case 'first_name':
            case 'last_name':
                $field = str_replace('_', '', $type);
                echo $customer->$field;
                break;
            case 'display_name':
                echo $customer->firstname . ' ' . $customer->lastname;
                break;
            case 'url':
                echo $customer->website;
        }
    }
}
