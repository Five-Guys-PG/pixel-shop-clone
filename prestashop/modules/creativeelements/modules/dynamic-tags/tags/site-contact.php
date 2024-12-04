<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

use CE\CoreXDynamicTagsXTag as Tag;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXDynamicTagsXTagsXSiteContact extends Tag
{
    public function getName()
    {
        return 'site-contact';
    }

    public function getTitle()
    {
        return __('Shop Contact');
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
                    'company' => __('Company'),
                    'address' => __('Address'),
                    'phone' => __('Tel'),
                    'fax' => __('Fax'),
                    'email' => __('Email'),
                ],
            ]
        );
    }

    public function render()
    {
        $type = $this->getSettings('type');

        switch ($type) {
            case 'company':
                echo \Configuration::get('PS_SHOP_NAME');
                break;
            case 'address':
                echo \AddressFormat::generateAddress(\Context::getContext()->shop->getAddress(), [], '<br>');
                break;
            case 'phone':
                echo \Configuration::get('PS_SHOP_PHONE');
                break;
            case 'fax':
                echo \Configuration::get('PS_SHOP_FAX');
                break;
            case 'email':
                echo \Configuration::get('PS_SHOP_EMAIL');
                break;
        }
    }
}
