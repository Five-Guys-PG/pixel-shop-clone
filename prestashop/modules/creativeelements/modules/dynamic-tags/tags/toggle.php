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

use CE\CoreXDynamicTagsXDataTag as DataTag;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXDynamicTagsXTagsXToggle extends DataTag
{
    public function getName()
    {
        return 'toggle';
    }

    public function getTitle()
    {
        return __('Toggle');
    }

    public function getGroup()
    {
        return Module::ACTION_GROUP;
    }

    public function getCategories()
    {
        return [Module::URL_CATEGORY];
    }

    public function getPanelTemplateSettingKey()
    {
        return 'target';
    }

    public function _registerControls()
    {
        $this->addControl(
            'target',
            [
                'show_label' => false,
                'label_block' => true,
                'type' => ControlsManager::SELECT,
                'groups' => [
                    '' => __('Select...'),
                    'widget' => [
                        'label' => __('Widget'),
                        'options' => [
                            'search' => __('Search') . ' - ' . __('Topbar'),
                            'cart' => __('Shopping Cart') . ' - ' . __('Sidebar'),
                            'filters' => __('Filters') . ' - ' . __('Sidebar'),
                        ],
                    ],
                    'advanced' => [
                        'label' => __('Advanced'),
                        'options' => [
                            '0' => __('CSS Classes'),
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'selector',
            [
                'label' => __('CSS Selector'),
                'type' => ControlsManager::TEXT,
                'condition' => [
                    'target' => '0',
                ],
            ]
        );

        $this->addControl(
            'class',
            [
                'label' => __('CSS Classes'),
                'type' => ControlsManager::TEXT,
                'title' => __('Add your custom class WITHOUT the dot. e.g: my-class'),
                'condition' => [
                    'target' => '0',
                ],
            ]
        );
    }

    public function getValue(array $options = [])
    {
        return Plugin::$instance->frontend->createActionHash('toggle', $this->getSettings());
    }
}
