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

class ModulesXPremiumXWidgetsXFlipBox extends WidgetBase
{
    public function getName()
    {
        return 'flip-box';
    }

    public function getTitle()
    {
        return __('Flip Box');
    }

    public function getIcon()
    {
        return 'eicon-flip-box';
    }

    public function getCategories()
    {
        return ['premium'];
    }

    public function getKeywords()
    {
        return ['flip', 'box', 'cta', 'banner'];
    }

    protected function isDynamicContent()
    {
        return false;
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_a',
            [
                'label' => __('Front'),
            ]
        );

        $this->addControl(
            'graphic_element',
            [
                'label' => __('Graphic Element'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'none' => [
                        'title' => __('None'),
                        'icon' => 'eicon-ban',
                    ],
                    'image' => [
                        'title' => __('Image'),
                        'icon' => 'eicon-image-bold',
                    ],
                    'icon' => [
                        'title' => __('Icon'),
                        'icon' => 'eicon-star',
                    ],
                ],
                'default' => 'icon',
            ]
        );

        $this->addControl(
            'image',
            [
                'label' => __('Choose Image'),
                'type' => ControlsManager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'seo' => true,
                'default' => [
                    'url' => Utils::getPlaceholderImageSrc(),
                ],
                'condition' => [
                    'graphic_element' => 'image',
                ],
            ]
        );

        $this->addControl(
            'selected_icon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'graphic_element' => 'icon',
                ],
            ]
        );

        $this->addControl(
            'icon_view',
            [
                'label' => __('View'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'default' => __('Default'),
                    'stacked' => __('Stacked'),
                    'framed' => __('Framed'),
                ],
                'default' => 'default',
                'condition' => [
                    'graphic_element' => 'icon',
                ],
            ]
        );

        $this->addControl(
            'icon_shape',
            [
                'label' => __('Shape'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'circle' => __('Circle'),
                    'square' => __('Square'),
                ],
                'default' => 'circle',
                'condition' => [
                    'icon_view!' => 'default',
                    'graphic_element' => 'icon',
                ],
            ]
        );

        $this->addControl(
            'title_text_a',
            [
                'label' => __('Title & Description'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('This is the heading'),
                'placeholder' => __('Enter your title'),
                'label_block' => true,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'description_text_a',
            [
                'show_label' => false,
                'type' => ControlsManager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Lorem ipsum dolor sit amet consectetur adipiscing elit dolor'),
                'placeholder' => __('Enter your description'),
            ]
        );

        $this->addControl(
            'title_display_a',
            [
                'label' => __('Title Display'),
                'type' => ControlsManager::CHOOSE,
                'options' => WidgetHeading::getDisplaySizes(),
                'style_transfer' => true,
            ]
        );

        $this->addControl(
            'title_size_a',
            [
                'label' => __('Title HTML Tag'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h3',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_b',
            [
                'label' => __('Back'),
            ]
        );

        $this->addControl(
            'graphic_element_b',
            [
                'label' => __('Graphic Element'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'none' => [
                        'title' => __('None'),
                        'icon' => 'eicon-ban',
                    ],
                    'image' => [
                        'title' => __('Image'),
                        'icon' => 'eicon-image-bold',
                    ],
                    'icon' => [
                        'title' => __('Icon'),
                        'icon' => 'eicon-star',
                    ],
                ],
            ]
        );

        $this->addControl(
            'image_b',
            [
                'label' => __('Choose Image'),
                'type' => ControlsManager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'seo' => true,
                'default' => [
                    'url' => Utils::getPlaceholderImageSrc(),
                ],
                'condition' => [
                    'graphic_element_b' => 'image',
                ],
            ]
        );

        $this->addControl(
            'selected_icon_b',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICONS,
                'fa4compatibility' => 'icon_b',
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'graphic_element_b' => 'icon',
                ],
            ]
        );

        $this->addControl(
            'icon_view_b',
            [
                'label' => __('View'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'default' => __('Default'),
                    'stacked' => __('Stacked'),
                    'framed' => __('Framed'),
                ],
                'default' => 'default',
                'condition' => [
                    'graphic_element_b' => 'icon',
                ],
            ]
        );

        $this->addControl(
            'icon_shape_b',
            [
                'label' => __('Shape'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'circle' => __('Circle'),
                    'square' => __('Square'),
                ],
                'default' => 'circle',
                'condition' => [
                    'graphic_element_b' => 'icon',
                    'icon_view_b!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'title_text_b',
            [
                'label' => __('Title & Description'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => __('This is the heading'),
                'placeholder' => __('Enter your title'),
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'description_text_b',
            [
                'show_label' => false,
                'type' => ControlsManager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Lorem ipsum dolor sit amet consectetur adipiscing elit dolor'),
                'placeholder' => __('Enter your description'),
            ]
        );

        $this->addControl(
            'title_display_b',
            [
                'label' => __('Title Display'),
                'type' => ControlsManager::CHOOSE,
                'options' => WidgetHeading::getDisplaySizes(),
                'style_transfer' => true,
            ]
        );

        $this->addControl(
            'title_size_b',
            [
                'label' => __('Title HTML Tag'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h3',
            ]
        );

        $this->addControl(
            'separator_button',
            [
                'type' => ControlsManager::DIVIDER,
            ]
        );

        $this->addControl(
            'button_heading',
            [
                'label' => __('Button'),
                'type' => ControlsManager::HEADING,
                'condition' => [
                    'button!' => '',
                ],
            ]
        );

        $this->addControl(
            'button_type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'default' => __('Widget Default'),
                    '' => __('Default'),
                    'primary' => __('Primary'),
                    'secondary' => __('Secondary'),
                ],
                'default' => 'default',
                'prefix_class' => 'elementor-button-',
                'style_transfer' => true,
                'condition' => [
                    'button!' => '',
                ],
            ]
        );

        $this->addControl(
            'button',
            [
                'label' => __('Button Text'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Click Here'),
            ]
        );

        $this->addControl(
            'link',
            [
                'label' => __('Link'),
                'type' => ControlsManager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __('https://your-link.com'),
            ]
        );

        $this->addControl(
            'link_click',
            [
                'label' => __('Apply Link On'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'box' => __('Whole Box'),
                    'button' => __('Button Only'),
                ],
                'default' => 'button',
                'condition' => [
                    'button!' => '',
                    'link[url]!' => '',
                ],
            ]
        );

        $this->addControl(
            'button_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::CHOOSE,
                'toggle' => false,
                'options' => WidgetButton::getButtonSizes(),
                'default' => 'sm',
                'style_transfer' => true,
                'condition' => [
                    'button!' => '',
                ],
            ]
        );

        $this->addControl(
            'selected_button_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'button_icon',
                'condition' => [
                    'button!' => '',
                ],
            ]
        );

        $this->addControl(
            'button_icon_align',
            [
                'label' => __('Icon Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Before'),
                    'right' => __('After'),
                ],
                'condition' => [
                    'button!' => '',
                    'selected_button_icon[value]!' => '',
                ],
            ]
        );

        $this->addControl(
            'button_icon_indent',
            [
                'label' => __('Icon Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button-content-wrapper' => 'gap: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .elementor-button-text' => 'flex-grow: min(0, {{SIZE}})',
                ],
                'condition' => [
                    'button!' => '',
                    'selected_button_icon[value]!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_flip_box',
            [
                'label' => __('Flip Box'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'height',
            [
                'label' => __('Height'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                    ],
                ],
                'size_units' => ['px', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-side, {{WRAPPER}} .elementor-flip-box-overlay' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'heading_hover_animation',
            [
                'label' => __('Hover Animation'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'flip_effect',
            [
                'label' => __('Flip Effect'),
                'type' => ControlsManager::SELECT,
                'default' => 'flip',
                'options' => [
                    'flip' => 'Flip',
                    'slide' => 'Slide',
                    'push' => 'Push',
                    'zoom-in' => 'Zoom In',
                    'zoom-out' => 'Zoom Out',
                    'fade' => 'Fade',
                ],
                'prefix_class' => 'elementor-flip-box--effect-',
            ]
        );

        $this->addControl(
            'flip_direction',
            [
                'label' => __('Flip Direction'),
                'type' => ControlsManager::SELECT,
                'default' => 'up',
                'options' => [
                    'left' => __('Left'),
                    'right' => __('Right'),
                    'up' => __('Up'),
                    'down' => __('Down'),
                ],
                'prefix_class' => 'elementor-flip-box--direction-',
                'condition' => [
                    'flip_effect!' => [
                        'fade',
                        'zoom-in',
                        'zoom-out',
                    ],
                ],
            ]
        );

        $this->addControl(
            'flip_3d',
            [
                'label' => __('3D Depth'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('On'),
                'label_off' => __('Off'),
                'return_value' => 'elementor-flip-box--3d',
                'prefix_class' => '',
                'condition' => [
                    'flip_effect' => 'flip',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_a',
            [
                'label' => __('Front'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('tabs_style_a');

        $this->startControlsTab(
            'tab_box_a',
            [
                'label' => __('Box'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background_a',
                'types' => ['none', 'classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementor-flip-box-front',
            ]
        );

        $this->addControl(
            'background_overlay_a',
            [
                'label' => __('Background Overlay'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-overlay' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'background_a_background' => 'classic',
                    'background_a_image[url]!' => '',
                ],
            ]
        );

        $this->addControl(
            'alignment_a',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'vertical_position_a',
            [
                'label' => __('Vertical Position'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => __('Top'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => __('Middle'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __('Bottom'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'prefix_class' => 'elementor-flip-box-front--valign-',
            ]
        );

        $this->addResponsiveControl(
            'padding_a',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'border_a',
                'label' => __('Border Style'),
                'separator' => 'default',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-overlay',
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'shadow_a',
                'separator' => 'default',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_icon_a',
            [
                'label' => __('Icon'),
                'condition' => [
                    'graphic_element' => 'icon',
                ],
            ]
        );

        $this->addControl(
            'icon_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_primary_color',
            [
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-view-stacked .elementor-icon svg' => 'stroke: {{VALUE}}',
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-view-framed .elementor-icon, ' .
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'icon_secondary_color',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-view-framed .elementor-icon svg' => 'stroke: {{VALUE}};',
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_view!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'icon_size',
            [
                'label' => __('Icon Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_padding',
            [
                'label' => __('Icon Padding'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon_view!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'icon_rotate',
            [
                'label' => __('Icon Rotate'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->addControl(
            'icon_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'icon_view!' => 'default',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_image_a',
            [
                'label' => __('Image'),
                'condition' => [
                    'graphic_element' => 'image',
                ],
            ]
        );

        $this->addControl(
            'image_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'image_width',
            [
                'label' => __('Size') . ' (%)',
                'type' => ControlsManager::SLIDER,
                'size_units' => ['%'],
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image img' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'image_opacity',
            [
                'label' => __('Opacity'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image img',
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'image_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_content_a',
            [
                'label' => __('Content'),
            ]
        );

        $this->addControl(
            'heading_style_title_a',
            [
                'label' => __('Title'),
                'type' => ControlsManager::HEADING,
            ]
        );

        $this->addControl(
            'title_spacing_a',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'title_color_a',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography_a',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-title',
            ]
        );

        $this->addGroupControl(
            GroupControlTextStroke::getType(),
            [
                'name' => 'text_stroke',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-title',
            ]
        );

        $this->addControl(
            'heading_style_description_a',
            [
                'label' => __('Description'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'description_color_a',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'description_typography_a',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-description',
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_b',
            [
                'label' => __('Back'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('tabs_style_b');

        $this->startControlsTab(
            'tab_box_b',
            [
                'label' => __('Box'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background_b',
                'types' => ['none', 'classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementor-flip-box-back',
            ]
        );

        $this->addControl(
            'background_overlay_b',
            [
                'label' => __('Background Overlay'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-overlay' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'background_b_background' => 'classic',
                    'background_b_image[url]!' => '',
                ],
            ]
        );

        $this->addControl(
            'alignment_b',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'vertical_position_b',
            [
                'label' => __('Vertical Position'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => __('Top'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => __('Middle'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __('Bottom'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'prefix_class' => 'elementor-flip-box-back--valign-',
            ]
        );

        $this->addResponsiveControl(
            'padding_b',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'border_b',
                'label' => __('Border Style'),
                'separator' => 'default',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-overlay',
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'shadow_b',
                'separator' => 'default',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_image_b',
            [
                'label' => __('Image'),
                'condition' => [
                    'graphic_element_b' => 'image',
                ],
            ]
        );

        $this->addControl(
            'image_spacing_b',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'image_width_b',
            [
                'label' => __('Size') . ' (%)',
                'type' => ControlsManager::SLIDER,
                'size_units' => ['%'],
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image img' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'image_opacity_b',
            [
                'label' => __('Opacity'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'image_border_b',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image img',
            ]
        );

        $this->addControl(
            'image_border_radius_b',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_icon_b',
            [
                'label' => __('Icon'),
                'condition' => [
                    'graphic_element_b' => 'icon',
                ],
            ]
        );

        $this->addControl(
            'icon_spacing_b',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_primary_color_b',
            [
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-stacked .elementor-icon svg' => 'stroke: {{VALUE}}',
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-framed .elementor-icon, ' .
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'icon_secondary_color_b',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-framed .elementor-icon svg' => 'stroke: {{VALUE}};',
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_view!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'icon_size_b',
            [
                'label' => __('Icon Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_padding_b',
            [
                'label' => __('Icon Padding'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon_view!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'icon_rotate_b',
            [
                'label' => __('Icon Rotate'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->addControl(
            'icon_border_radius_b',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'icon_view_b!' => 'default',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_content_b',
            [
                'label' => __('Content'),
            ]
        );

        $this->addControl(
            'heading_style_title_b',
            [
                'label' => __('Title'),
                'type' => ControlsManager::HEADING,
            ]
        );

        $this->addControl(
            'title_spacing_b',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'title_color_b',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography_b',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-title',
            ]
        );

        $this->addGroupControl(
            GroupControlTextStroke::getType(),
            [
                'name' => 'text_stroke_b',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-title',
            ]
        );

        $this->addControl(
            'heading_description_style_b',
            [
                'label' => __('Description'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'description_spacing_b',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-button' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'button!' => '',
                ],
            ]
        );

        $this->addControl(
            'description_color_b',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'description_typography_b',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-description',
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_button',
            [
                'label' => __('Button'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'button!' => '',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .elementor-button',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'button_text_shadow',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->startControlsTabs('tabs_button_colors');

        $this->startControlsTab(
            'tab_button_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'button_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:not(#e), {{WRAPPER}} a.elementor-button:not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_button_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'button_hover_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:not(#e):hover, {{WRAPPER}} a.elementor-button:not(#e):hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_hover_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_hover_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'button_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 20,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'button_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->addResponsiveControl(
            'button_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $wrapper_tag = 'div';
        $button_tag = 'span';
        $display_a = $settings['title_display_a'] ? " ce-display-{$settings['title_display_a']}" : '';
        $display_b = $settings['title_display_b'] ? " ce-display-{$settings['title_display_b']}" : '';

        $this->addRenderAttribute('flipbox-back', 'class', 'elementor-flip-box-back elementor-flip-box-side');

        $this->addRenderAttribute('button', 'class', 'elementor-button elementor-size-' . $settings['button_size']);
        // BC fix
        $this->addRenderAttribute('button_icon', 'class', 'elementor-button-icon elementor-align-icon-' . $this->getSettings('button_icon_align'));

        if (!empty($settings['link']['url'])) {
            if ('box' === $settings['link_click'] || '' === $settings['button']) {
                $wrapper_tag = 'a';

                $this->addLinkAttributes('flipbox-back', $settings['link']);
            } else {
                $button_tag = 'a';

                $this->addRenderAttribute('button', [
                    'class' => 'elementor-button-link',
                    'role' => 'button',
                ]);
                $this->addLinkAttributes('button', $settings['link']);
            }
        }
        if ('icon' === $settings['graphic_element']) {
            $icon = IconsManager::getBcIcon($settings, 'icon', ['aria-hidden' => 'true']);

            $this->addRenderAttribute('icon-wrapper-front', 'class', [
                'elementor-icon-wrapper',
                'elementor-view-' . $settings['icon_view'],
            ]);
            if ('default' !== $settings['icon_view']) {
                $this->addRenderAttribute('icon-wrapper-front', 'class', 'elementor-shape-' . $settings['icon_shape']);
            }
        }
        if ('icon' === $settings['graphic_element_b']) {
            $icon_b = IconsManager::getBcIcon($settings, 'icon_b', ['aria-hidden' => 'true']);

            $this->addRenderAttribute('icon-wrapper-back', 'class', [
                'elementor-icon-wrapper',
                'elementor-view-' . $settings['icon_view_b'],
            ]);
            if ('default' !== $settings['icon_view_b']) {
                $this->addRenderAttribute('icon-wrapper-back', 'class', 'elementor-shape-' . $settings['icon_shape_b']);
            }
        } ?>
        <div class="elementor-flip-box">
            <div class="elementor-flip-box-front elementor-flip-box-side">
                <div class="elementor-flip-box-overlay">
                    <div class="elementor-flip-box-content">
                    <?php if ('icon' === $settings['graphic_element'] && $icon) { ?>
                        <div <?php $this->printRenderAttributeString('icon-wrapper-front'); ?>>
                            <div class="elementor-icon"><?php echo $icon; ?></div>
                        </div>
                    <?php } elseif ('image' === $settings['graphic_element']) { ?>
                        <div class="elementor-flip-box-image">
                            <?php echo GroupControlImageSize::getAttachmentImageHtml($settings); ?>
                        </div>
                    <?php } ?>
                    <?php if ('' !== $settings['title_text_a']) { ?>
                        <<?php echo $settings['title_size_a']; ?> class="elementor-flip-box-title<?php echo esc_attr($display_a); ?>">
                            <?php echo $settings['title_text_a']; ?>
                        </<?php echo $settings['title_size_a']; ?>>
                    <?php } ?>
                    <?php if ('' !== $settings['description_text_a']) { ?>
                        <div class="elementor-flip-box-description"><?php echo $settings['description_text_a']; ?></div>
                    <?php } ?>
                    </div>
                </div>
            </div>
            <<?php echo $wrapper_tag; ?> <?php $this->printRenderAttributeString('flipbox-back'); ?>>
                <div class="elementor-flip-box-overlay">
                    <div class="elementor-flip-box-content">
                    <?php if ('none' !== $settings['graphic_element_b']) { ?>
                        <?php if ('image' === $settings['graphic_element_b']) { ?>
                        <div class="elementor-flip-box-image">
                            <?php echo GroupControlImageSize::getAttachmentImageHtml($settings, 'image_b'); ?>
                        </div>
                        <?php } elseif ('icon' === $settings['graphic_element_b'] && $icon_b) { ?>
                            <div <?php $this->printRenderAttributeString('icon-wrapper-back'); ?>>
                                <div class="elementor-icon"><?php echo $icon_b; ?></div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <?php if ('' !== $settings['title_text_b']) { ?>
                        <<?php echo $settings['title_size_b']; ?> class="elementor-flip-box-title<?php echo esc_attr($display_b); ?>">
                            <?php echo $settings['title_text_b']; ?>
                        </<?php echo $settings['title_size_b']; ?>>
                    <?php } ?>
                    <?php if ('' !== $settings['description_text_b']) { ?>
                        <div class="elementor-flip-box-description"><?php echo $settings['description_text_b']; ?></div>
                    <?php } ?>
                    <?php if ('' !== $settings['button']) { ?>
                        <<?php echo $button_tag; ?> <?php $this->printRenderAttributeString('button'); ?>>
                            <span class="elementor-button-content-wrapper">
                            <?php if ($button_icon = IconsManager::getBcIcon($settings, 'button_icon')) { ?>
                                <span <?php $this->printRenderAttributeString('button_icon'); ?>><?php echo $button_icon; ?></span>
                            <?php } ?>
                                <span class="elementor-button-text"><?php echo $settings['button']; ?></span>
                            </span>
                        </<?php echo $button_tag; ?>>
                    <?php } ?>
                    </div>
                </div>
            </<?php echo $wrapper_tag; ?>>
        </div>
        <?php
    }

    protected function contentTemplate()
    {
        ?>
        <#
        if ( 'icon' === settings.graphic_element ) {
            var icon = elementor.helpers.getBcIcon(view, settings, 'icon', {'aria-hidden': true}),
                iconWrapperClasses = 'elementor-icon-wrapper';
            iconWrapperClasses += ' elementor-view-' + settings.icon_view;

            if ( 'default' !== settings.icon_view ) {
                iconWrapperClasses += ' elementor-shape-' + settings.icon_shape;
            }
        }
        if ( 'icon' === settings.graphic_element_b ) {
            var iconB = elementor.helpers.getBcIcon(view, settings, 'icon_b', {'aria-hidden': true}),
                iconWrapperClassesBack = 'elementor-icon-wrapper';
            iconWrapperClassesBack += ' elementor-view-' + settings.icon_view_b;

            if ( 'default' !== settings.icon_view_b ) {
                iconWrapperClassesBack += ' elementor-shape-' + settings.icon_shape_b;
            }
        }
        var titleTagFront = settings.title_size_a,
            titleTagBack = settings.title_size_b,
            wrapperTag = 'div',
            buttonTag = 'span',
            buttonIcon;

        view.addRenderAttribute('flipbox-back', 'class', 'elementor-flip-box-back elementor-flip-box-side');
        view.addRenderAttribute('button', 'class', [
            'elementor-button',
            'elementor-size-' + settings.button_size
        ]);
        view.addRenderAttribute('button_icon', 'class', [
            'elementor-button-icon',
            'elementor-align-icon-' + settings.button_icon_align
        ]);
        if (settings.link && settings.link.url) {
            if ( 'box' === settings.link_click || !settings.button ) {
                wrapperTag = 'a';
                view.addRenderAttribute( 'flipbox-back', 'href', settings.link.url );
            } else {
                buttonTag = 'a';
                view.addRenderAttribute( 'button', 'href', settings.link.url );
            }
        }
        #>
        <div class="elementor-flip-box">
            <div class="elementor-flip-box-front elementor-flip-box-side">
                <div class="elementor-flip-box-overlay">
                    <div class="elementor-flip-box-content">
                    <# if ( 'icon' === settings.graphic_element && icon ) { #>
                        <div class="{{ iconWrapperClasses }}">
                             <div class="elementor-icon">{{{ icon }}}</div>
                        </div>
                    <# } else if ( 'image' === settings.graphic_element && settings.image.url ) { #>
                        <div class="elementor-flip-box-image">
                            <img src="{{ elementor.helpers.getMediaLink( settings.image.url ) }}">
                        </div>
                    <# } #>
                    <# if ( settings.title_text_a ) { #>
                        <{{ titleTagFront }} class="elementor-flip-box-title ce-display-{{ settings.title_display_a }}">
                            {{{ settings.title_text_a }}}
                        </{{ titleTagFront }}>
                    <# } #>
                    <# if ( settings.description_text_a ) { #>
                        <div class="elementor-flip-box-description">{{{ settings.description_text_a }}}</div>
                    <# } #>
                    </div>
                </div>
            </div>
            <{{ wrapperTag }} {{{ view.getRenderAttributeString('flipbox-back') }}}>
                <div class="elementor-flip-box-overlay">
                    <div class="elementor-flip-box-content">
                    <# if ('icon' === settings.graphic_element_b && iconB) { #>
                        <div class="{{ iconWrapperClassesBack }}">
                            <div class="elementor-icon">{{{ iconB }}}</div>
                        </div>
                    <# } else if ( 'image' === settings.graphic_element_b && settings.image_b.url ) { #>
                        <div class="elementor-flip-box-image">
                            <img src="{{ elementor.helpers.getMediaLink( settings.image_b.url ) }}">
                        </div>
                    <# } #>
                    <# if ( settings.title_text_b ) { #>
                        <{{ titleTagBack }} class="elementor-flip-box-title ce-display-{{ settings.title_display_b }}">
                            {{{ settings.title_text_b }}}
                        </{{ titleTagBack }}>
                    <# } #>
                    <# if ( settings.description_text_b ) { #>
                        <div class="elementor-flip-box-description">{{{ settings.description_text_b }}}</div>
                    <# } #>
                    <# if ( settings.button ) { #>
                        <{{ buttonTag }} {{{ view.getRenderAttributeString('button') }}}>
                            <span class="elementor-button-content-wrapper">
                            <# if ( buttonIcon = elementor.helpers.getBcIcon(view, settings, 'button_icon') ) { #>
                                <span {{{ view.getRenderAttributeString('button_icon') }}}>{{{ buttonIcon }}}</span>
                            <# } #>
                                <span class="elementor-button-text">{{{ settings.button }}}</span>
                            </span>
                        </{{ buttonTag }}>
                    <# } #>
                    </div>
                </div>
            </{{ wrapperTag }}>
        </div>
        <?php
    }
}
