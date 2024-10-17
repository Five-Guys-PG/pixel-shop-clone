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

class ModulesXPremiumXWidgetsXImageSlider extends WidgetBase
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'image-slider';
    }

    public function getTitle()
    {
        return __('Image Slider');
    }

    public function getIcon()
    {
        return 'eicon-slides';
    }

    public function getCategories()
    {
        return ['premium'];
    }

    protected function isDynamicContent()
    {
        return false;
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_image_slider',
            [
                'label' => __('Image Slider'),
            ]
        );

        $this->addControl(
            'speed',
            [
                'label' => __('Speed') . ' (ms)',
                'type' => ControlsManager::NUMBER,
                'default' => '5000',
            ]
        );

        $this->addControl(
            'pause',
            [
                'label' => __('Pause on Hover'),
                'type' => ControlsManager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => __('Yes'),
                    '' => __('No'),
                ],
            ]
        );

        $this->addControl(
            'wrap',
            [
                'label' => __('Loop forever'),
                'type' => ControlsManager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => __('Yes'),
                    '' => __('No'),
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_slides_list',
            [
                'label' => __('Slides List'),
            ]
        );

        $modules = basename(_MODULE_DIR_);
        $ample = 'ps_imageslider/images/sample';
        $img = Utils::getPlaceholderImageSrc();
        $desc = '<h2>EXCEPTEUR OCCAECAT</h2>' .
            '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. ' .
            'Proin tristique in tortor et dignissim. Quisque non tempor leo. Maecenas egestas sem elit</p>';

        $this->addControl(
            'slides',
            [
                'type' => ControlsManager::REPEATER,
                'default' => [
                    [
                        'image' => [
                            'url' => file_exists(_PS_MODULE_DIR_ . $ample . '-1.jpg') ? "$modules/$ample-1.jpg" : $img,
                            'id' => 0,
                        ],
                        'title' => 'SAMPLE 1',
                        'description' => $desc,
                        'active' => '1',
                    ],
                    [
                        'image' => [
                            'url' => file_exists(_PS_MODULE_DIR_ . $ample . '-2.jpg') ? "$modules/$ample-2.jpg" : $img,
                            'id' => 0,
                        ],
                        'title' => 'SAMPLE 2',
                        'description' => $desc,
                        'active' => '1',
                    ],
                    [
                        'image' => [
                            'url' => file_exists(_PS_MODULE_DIR_ . $ample . '-3.jpg') ? "$modules/$ample-3.jpg" : $img,
                            'id' => 0,
                        ],
                        'title' => 'SAMPLE 3',
                        'legend' => 'Excepteur Occaecat',
                        'description' => $desc,
                        'active' => '1',
                    ],
                ],
                'fields' => [
                    [
                        'name' => 'image',
                        'label' => __('Choose Image'),
                        'type' => ControlsManager::MEDIA,
                        'seo' => true,
                        'default' => [
                            'url' => Utils::getPlaceholderImageSrc(),
                        ],
                    ],
                    [
                        'name' => 'title',
                        'label' => __('Title & Description'),
                        'label_block' => true,
                        'type' => ControlsManager::TEXT,
                        'placeholder' => __('Enter your title'),
                        'default' => __('This is heading element'),
                    ],
                    [
                        'name' => 'description',
                        'label' => __('Description'),
                        'type' => ControlsManager::WYSIWYG,
                    ],
                    [
                        'name' => 'url',
                        'label' => __('Link'),
                        'type' => ControlsManager::TEXT,
                        'label_block' => true,
                        'placeholder' => __('http://your-link.com'),
                    ],
                    [
                        'name' => 'active',
                        'label' => __('Enabled'),
                        'type' => ControlsManager::SWITCHER,
                        'default' => '1',
                        'return_value' => '1',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $editSettings = Plugin::instance()->editor->isEditMode() ? $this->getData('editSettings') : null;
        $activeItemIndex = isset($editSettings['activeItemIndex']) ? $editSettings['activeItemIndex'] : 0;
        $slides = [];

        foreach ($settings['slides'] as $i => &$slide) {
            if (!empty($slide['active']) && !$activeItemIndex || $i + 1 == $activeItemIndex) {
                $slides[] = [
                    'image_url' => Helper::getMediaLink($slide['image']['url']),
                    'title' => $slide['title'],
                    'legend' => !empty($slide['image']['alt']) ? $slide['image']['alt'] : '',
                    'description' => $slide['description'],
                    'url' => $slide['url'],
                ];
            }
        }

        $context = \Context::getContext();
        $context->smarty->assign([
            'homeslider' => [
                'speed' => $settings['speed'],
                'pause' => $settings['pause'] ? 'true' : 'false',
                'wrap' => $settings['wrap'] ? 'true' : 'false',
                'slides' => $slides,
            ],
        ]);

        $tpl = 'modules/ps_imageslider/views/templates/hook/slider.tpl';

        if (file_exists(_PS_THEME_DIR_ . $tpl)) {
            $tpl_path = _PS_THEME_DIR_ . $tpl;
        } elseif (($parent = $context->shop->theme->get('parent')) && file_exists(_PS_ALL_THEMES_DIR_ . "$parent/$tpl")) {
            $tpl_path = _PS_ALL_THEMES_DIR_ . "$parent/$tpl";
        } else {
            $tpl_path = _PS_ROOT_DIR_ . "/$tpl";
        }

        echo $context->smarty->fetch($tpl_path);
    }

    public function renderPlainContent()
    {
    }
}
