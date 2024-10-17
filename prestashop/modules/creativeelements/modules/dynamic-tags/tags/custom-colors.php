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

class ModulesXDynamicTagsXTagsXCustomColors extends DataTag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'custom-colors';
    }

    public function getTitle()
    {
        return __('Picked Colors');
    }

    public function getGroup()
    {
        return Module::SITE_GROUP;
    }

    public function getCategories()
    {
        return [Module::GALLERY_CATEGORY];
    }

    protected function _registerControls()
    {
        $this->addControl(
            'show_caption',
            [
                'label' => __('Caption'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'default' => 'yes',
            ]
        );
    }

    public function getValue(array $options = [])
    {
        $kit_settings = get_post_meta(Plugin::$instance->kits_manager->getActiveId(), '_elementor_page_settings', true);

        if (empty($kit_settings['custom_colors'])) {
            return [];
        }
        $items = [];

        foreach ($kit_settings['custom_colors'] as &$custom_color) {
            $color = urlencode($custom_color['color']);
            $items[] = [
                'image' => [
                    'id' => '',
                    'url' => 'data:image/svg+xml,' .
                        "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1' style='background:$color'></svg>",
                    'alt' => $custom_color['color'],
                ],
                'link' => [
                    'url' => '',
                ],
                'caption' => $this->getSettings('show_caption') ? $custom_color['color'] : '',
            ];
        }

        return $items;
    }
}
