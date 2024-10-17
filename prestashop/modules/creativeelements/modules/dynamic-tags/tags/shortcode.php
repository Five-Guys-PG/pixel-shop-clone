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

class ModulesXDynamicTagsXTagsXShortcode extends Tag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'shortcode';
    }

    public function getTitle()
    {
        return __('Shortcode');
    }

    public function getGroup()
    {
        return Module::SITE_GROUP;
    }

    public function getCategories()
    {
        return [
            Module::TEXT_CATEGORY,
            Module::URL_CATEGORY,
            // Module::POST_META_CATEGORY,
            Module::NUMBER_CATEGORY,
        ];
    }

    public function getPanelTemplateSettingKey()
    {
        return 'shortcode';
    }

    protected function _registerControls()
    {
        $this->addControl(
            'shortcode',
            [
                'label' => __('Shortcode'),
                'type' => ControlsManager::TEXTAREA,
                'placeholder' => "{hook h='displayShortcode'}",
            ]
        );
    }

    public function render()
    {
        echo do_shortcode($this->getSettings('shortcode'));
    }

    public function renderSmarty()
    {
        echo $this->getSettings('shortcode');
    }
}
