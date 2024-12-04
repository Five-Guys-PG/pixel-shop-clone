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

class ModulesXThemeXWidgetsXSiteLogo extends WidgetImage
{
    public function getName()
    {
        return 'theme-site-logo';
    }

    public function getTitle()
    {
        return __('Shop Logo');
    }

    public function getIcon()
    {
        return 'eicon-site-logo';
    }

    public function getCategories()
    {
        return ['theme-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'logo', 'branding'];
    }

    protected function isDynamicContent()
    {
        return true;
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->updateControl('image', [
            'label' => '',
            'dynamic' => [
                'active' => true,
                'default' => Plugin::$instance->dynamic_tags->tagDataToTagText(null, 'site-logo'),
            ],
            'default' => [
                'url' => 'img/' . \Configuration::get('PS_LOGO'),
                'alt' => \Configuration::get('PS_SHOP_NAME'),
            ],
        ]);

        $this->updateControl('link_to', ['default' => 'custom']);

        $this->updateControl('link', [
            'dynamic' => [
                'active' => true,
                'default' => Plugin::$instance->dynamic_tags->tagDataToTagText(null, 'site-url'),
            ],
            'default' => ['url' => __PS_BASE_URI__],
        ]);

        $this->removeControl('caption');

        $this->startInjection([
            'of' => 'height',
        ]);

        $this->addControl(
            'shrink',
            [
                'label' => __('Sticked'),
                'type' => ControlsManager::POPOVER_TOGGLE,
                'render_type' => 'ui',
                'condition' => [
                    'height[size]!' => '',
                ],
            ]
        );

        $this->startPopover();

        $this->addResponsiveControl(
            'shrink_height',
            [
                'label' => __('Height'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '.elementor-sticky--active:not(#e) .elementor-element-{{ID}} .elementor-image img' => 'height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'shrink!' => '',
                ],
            ]
        );

        $this->addControl(
            'shrink_duration',
            [
                'label' => __('Transition Duration') . ' (s)',
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image img' => 'transition: height {{SIZE}}s',
                ],
                'condition' => [
                    'shrink!' => '',
                ],
            ]
        );

        $this->endPopover();

        $this->endInjection();

        $this->updateControl('text_color', ['scheme' => '']);

        $this->updateControl('caption_typography_font_family', ['scheme' => '']);
        $this->updateControl('caption_typography_font_weight', ['scheme' => '']);
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-image';
    }
}
