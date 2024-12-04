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

use CE\CoreXDynamicTagsXDataTag as DataTag;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXDynamicTagsXTagsXCarousel extends DataTag
{
    public function getName()
    {
        return 'carousel';
    }

    public function getTitle()
    {
        return __('Carousel');
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
        return 'action';
    }

    public function _registerControls()
    {
        $this->addControl(
            'action',
            [
                'label' => __('Action'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'next' => __('Next'),
                    'prev' => __('Previous'),
                    'goto' => __('Go To'),
                    'play' => __('Play'),
                    'pause' => __('Pause'),
                ],
                'default' => 'next',
            ]
        );

        $this->addControl(
            'goto',
            [
                'label' => __('Slide'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'condition' => [
                    'action' => 'goto',
                ],
            ]
        );
    }

    protected function registerAdvancedSection()
    {
        $this->startControlsSection(
            'advanced',
            [
                'label' => __('Advanced'),
            ]
        );

        $this->addControl(
            'selector',
            [
                'label' => __('Selector'),
                'type' => ControlsManager::TEXT,
                'label_block' => true,
                'placeholder' => __('e.g. #primary / .wrapper / main etc'),
            ]
        );

        $this->endControlsSection();
    }

    public function getValue(array $options = [])
    {
        return Plugin::$instance->frontend->createActionHash('carousel', $this->getSettings());
    }
}
