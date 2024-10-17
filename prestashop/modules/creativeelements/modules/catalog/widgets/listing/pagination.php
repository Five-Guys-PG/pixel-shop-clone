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

class ModulesXCatalogXWidgetsXListingXPagination extends WidgetBase
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'listing-pagination';
    }

    public function getTitle()
    {
        return __('Pagination');
    }

    public function getIcon()
    {
        return 'eicon-post-navigation';
    }

    public function getCategories()
    {
        return ['listing-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'category', 'products', 'listing', 'pagination', 'load', 'more', 'infinite', 'scroll'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_pagination',
            [
                'label' => __('Pagination'),
            ]
        );

        $this->addControl(
            'class_button',
            [
                'type' => ControlsManager::HIDDEN,
                'prefix_class' => 'elementor-widget-',
                'default' => 'button',
                'condition' => [
                    'skin' => 'button',
                ],
            ]
        );

        $this->addControl(
            'class_icon_list',
            [
                'type' => ControlsManager::HIDDEN,
                'prefix_class' => 'elementor-widget-',
                'default' => 'icon-list',
                'condition' => [
                    'type' => 'default',
                    'skin' => 'classic',
                ],
            ]
        );

        $this->addControl(
            'type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'default' => __('Default'),
                    'loadmore' => __('Load More'),
                    'autoload' => __('Infinite Scroll'),
                ],
                'default' => 'default',
                'prefix_class' => 'ce-pagination--type-',
                'style_transfer' => true,
                'frontend_available' => true,
                'render_type' => 'template',
            ]
        );

        $this->addControl(
            'skin',
            [
                'label' => __('Skin'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'classic' => __('Classic'),
                    'button' => __('Button'),
                ],
                'default' => 'classic',
                'prefix_class' => 'ce-pagination--skin-',
                'style_transfer' => true,
                'render_type' => 'template',
            ]
        );

        $this->addControl(
            'navigation',
            [
                'label' => __('Navigation'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'both' => __('Arrows') . ' & ' . __('Pages'),
                    'arrows' => __('Arrows'),
                    'pages' => __('Pages'),
                ],
                'default' => 'both',
                'condition' => [
                    'type' => 'default',
                ],
            ]
        );

        $this->addResponsiveControl(
            'align',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
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
                    '{{WRAPPER}} .elementor-row' => 'justify-content: {{VALUE}}',
                    '{{WRAPPER}} .elementor-icon-box-wrapper' => 'text-align: {{VALUE}};',
                ],
                'style_transfer' => true,
                'condition' => [
                    'skin' => 'classic',
                    'navigation!' => 'arrows',
                ],
            ]
        );

        $this->addControl(
            'button_heading',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Button'),
                'separator' => 'before',
                'condition' => [
                    'skin' => 'button',
                ],
            ]
        );

        $this->addControl(
            'button_type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'primary' => __('Primary'),
                    'secondary' => __('Secondary'),
                ],
                'default' => 'secondary',
                'prefix_class' => 'elementor-button-',
                'style_transfer' => true,
                'condition' => [
                    'skin' => 'button',
                ],
            ]
        );

        $this->addControl(
            'text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Default'),
                'condition' => [
                    'type!' => 'default',
                ],
            ]
        );

        $this->addResponsiveControl(
            'button_align',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
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
                    'justify' => [
                        'title' => __('Justified'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-row' => 'justify-content: {{VALUE}}',
                ],
                'prefix_class' => 'elementor%s-align-',
                'condition' => [
                    'skin' => 'button',
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
                    'skin' => 'button',
                ],
            ]
        );

        $this->addControl(
            'more_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'classes' => 'elementor-control-type-heading',
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'recommended' => [
                    'ce-icons' => [
                        'plus',
                        'sort-down',
                    ],
                    'fa-solid' => [
                        'plus',
                        'chevron-down',
                        'angle-down',
                        'angles-down',
                        'caret-down',
                        'square-caret-down',
                    ],
                    'fa-regular' => [
                        'square-caret-down',
                    ],
                ],
                'default' => [
                    'value' => 'fas fa-angles-down',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'type' => 'loadmore',
                ],
            ]
        );

        $this->addControl(
            'heading_icon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::HEADING,
                'condition' => [
                    'type' => 'autoload',
                ],
            ]
        );

        $this->addControl(
            'load_icon',
            [
                'label' => __('Loading'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'recommended' => [
                    'ce-icons' => [
                        'loading',
                    ],
                    'fa-solid' => [
                        'spinner',
                        'gear',
                        'clock',
                        'hourglass',
                        'hourglass-half',
                    ],
                    'fa-regular' => [
                        'clock',
                        'hourglass',
                        'hourglass-half',
                    ],
                ],
                'default' => [
                    'value' => 'ceicon-loading',
                    'library' => 'ce-icons',
                ],
                'frontend_available' => true,
                'condition' => [
                    'type!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'icon_align',
            [
                'label' => __('Icon Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Before'),
                    'right' => __('After'),
                ],
                'condition' => [
                    'type!' => 'default',
                    'skin' => 'button',
                    'load_icon[value]!' => '',
                ],
            ]
        );

        $this->addControl(
            'icon_indent',
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
                    'type!' => 'default',
                    'skin' => 'button',
                    'load_icon[value]!' => '',
                ],
            ]
        );

        $this->addControl(
            'view',
            [
                'label' => __('View'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'default' => __('Default'),
                    'stacked' => __('Stacked'),
                    'framed' => __('Framed'),
                ],
                'default' => 'default',
                'prefix_class' => 'elementor-widget-icon-box elementor-shape-circle elementor-view-',
                'condition' => [
                    'type!' => 'default',
                    'skin' => 'classic',
                ],
            ]
        );

        $this->addResponsiveControl(
            'position',
            [
                'label' => __('Icon Position'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => __('Top'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'top',
                'mobile_default' => 'top',
                'prefix_class' => 'elementor%s-position-',
                'condition' => [
                    'type!' => 'default',
                    'skin' => 'classic',
                    'load_icon[value]!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_arrows',
            [
                'label' => __('Arrows'),
                'condition' => [
                    'type' => 'default',
                    'navigation!' => 'pages',
                ],
            ]
        );

        $this->addControl(
            'prev_text',
            [
                'label' => __('Previous'),
                'classes' => 'elementor-control-type-heading',
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Label'),
            ]
        );

        $this->addControl(
            'prev_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'recommended' => [
                    'ce-icons' => [
                        'caret-left',
                        'angle-left',
                        'chevron-left',
                        'arrow-left',
                        'long-arrow-left',
                    ],
                    'fa-solid' => [
                        'caret-left',
                        'square-caret-left',
                        'arrow-left',
                        'circle-arrow-left',
                        'circle-left',
                        'left-long',
                        'arrow-left-long',
                        'chevron-left',
                        'circle-chevron-left',
                        'angle-left',
                        'angles-left',
                    ],
                    'fa-regular' => [
                        'square-caret-left',
                        'circle-left',
                        'hand-point-left',
                    ],
                ],
                'default' => [
                    'value' => 'ceicon-chevron-left',
                    'library' => 'ce-icons',
                ],
            ]
        );

        $this->addControl(
            'prev_icon_align',
            [
                'label' => __('Icon Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Before'),
                    'right' => __('After'),
                ],
                'selectors_dictionary' => [
                    'left' => 'row-reverse',
                    'right' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}}.ce-pagination--skin-classic .ce-pagination__arrow:first-child' => 'flex-direction: {{VALUE}}',
                ],
                'condition' => [
                    'prev_text!' => '',
                    'prev_icon[value]!' => '',
                ],
            ]
        );

        $this->addControl(
            'next_text',
            [
                'label' => __('Next'),
                'classes' => 'elementor-control-type-heading',
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Label'),
            ]
        );

        $this->addControl(
            'next_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'recommended' => [
                    'ce-icons' => [
                        'caret-right',
                        'angle-right',
                        'chevron-right',
                        'arrow-right',
                        'long-arrow-right',
                    ],
                    'fa-solid' => [
                        'caret-right',
                        'square-caret-right',
                        'arrow-right',
                        'circle-arrow-right',
                        'circle-right',
                        'right-long',
                        'arrow-right-long',
                        'chevron-right',
                        'circle-chevron-right',
                        'angle-right',
                        'angles-right',
                    ],
                    'fa-regular' => [
                        'square-caret-right',
                        'circle-right',
                        'hand-point-right',
                    ],
                ],
                'default' => [
                    'value' => 'ceicon-chevron-right',
                    'library' => 'ce-icons',
                ],
            ]
        );

        $this->addControl(
            'next_icon_align',
            [
                'label' => __('Icon Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'right',
                'options' => [
                    'left' => __('Before'),
                    'right' => __('After'),
                ],
                'selectors_dictionary' => [
                    'left' => 'row-reverse',
                    'right' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}}.ce-pagination--skin-classic .ce-pagination__arrow:last-child' => 'flex-direction: {{VALUE}}',
                ],
                'condition' => [
                    'next_text!' => '',
                    'next_icon[value]!' => '',
                ],
            ]
        );

        $this->addControl(
            'arrow_icon_indent',
            [
                'label' => __('Icon Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.ce-pagination--skin-classic .ce-pagination__arrow, {{WRAPPER}} .elementor-button-content-wrapper' => 'gap: {{SIZE}}{{UNIT}}',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'prev_text',
                            'operator' => '!==',
                            'value' => '',
                        ],
                        [
                            'name' => 'next_text',
                            'operator' => '!==',
                            'value' => '',
                        ],
                    ],
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_list_style',
            [
                'label' => __('List'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'type' => 'default',
                    'skin' => 'classic',
                    'navigation!' => 'arrows',
                ],
            ]
        );

        $this->addResponsiveControl(
            'list_spacing',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-pagination' => 'margin: 0 calc(-{{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .ce-pagination .elementor-icon-list-item' => 'margin: 0 calc({{SIZE}}{{UNIT}}/2)',
                    'body.lang-rtl {{WRAPPER}} .elementor-icon-list-item:after' => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
                    'body:not(.lang-rtl) {{WRAPPER}} .elementor-icon-list-item:after' => 'right: calc(-{{SIZE}}{{UNIT}}/2)',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} a.elementor-icon-list-item',
            ]
        );

        $this->addControl(
            'list_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a:not([href]) .elementor-icon-list-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'list_link_color',
            [
                'label' => __('Link Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a[href] .elementor-icon-list-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'list_hover_color',
            [
                'label' => __('Link Hover Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a[href]:hover .elementor-icon-list-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'divider',
            [
                'label' => __('Divider'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Off'),
                'label_on' => __('On'),
                'selectors' => [
                    '{{WRAPPER}} .elementor-row .elementor-icon-list-item:not(:last-child):after' => 'content: ""',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'divider_style',
            [
                'label' => __('Style'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'solid' => __('Solid'),
                    'double' => __('Double'),
                    'dotted' => __('Dotted'),
                    'dashed' => __('Dashed'),
                ],
                'default' => 'solid',
                'condition' => [
                    'divider!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'border-left-style: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'divider_weight',
            [
                'label' => __('Weight'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'condition' => [
                    'divider!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'divider_height',
            [
                'label' => __('Height'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['%', 'px'],
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                    ],
                ],
                'condition' => [
                    'divider!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'divider_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'default' => '#ddd',
                'condition' => [
                    'divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_icon',
            [
                'label' => __('Icon'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'type!' => 'default',
                    'skin' => 'classic',
                    'load_icon[value]!' => '',
                ],
            ]
        );

        $this->startControlsTabs(
            'icon_colors',
            [
                'condition' => [
                    'type' => 'loadmore',
                ],
            ]
        );

        $this->startControlsTab(
            'icon_colors_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'primary_color',
            [
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'type' => ['loadmore', 'autoload'],
                ],
            ]
        );

        $this->addControl(
            'secondary_color',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'condition' => [
                    'view!' => 'default',
                    'type' => ['loadmore', 'autoload'],
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'icon_colors_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'hover_primary_color',
            [
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover, {{WRAPPER}}.elementor-view-default .elementor-icon:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'hover_secondary_color',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'condition' => [
                    'view!' => 'default',
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'divider_icon',
            [
                'type' => ControlsManager::DIVIDER,
                'condition' => [
                    'type' => 'loadmore',
                ],
            ]
        );

        $this->addResponsiveControl(
            'icon_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'condition' => [
                    'view!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'icon_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'view' => 'framed',
                ],
            ]
        );

        $this->addControl(
            'icon_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'view!' => 'default',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_title',
            [
                'label' => __('Text'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'type!' => 'default',
                    'skin' => 'classic',
                    'text!' => ' ',
                ],
            ]
        );

        $this->addResponsiveControl(
            'title_gap',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-icon-box-title',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
            ]
        );

        $this->addGroupControl(
            GroupControlTextStroke::getType(),
            [
                'name' => 'text_stroke',
                'selector' => '{{WRAPPER}} .elementor-icon-box-title',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'title_shadow',
                'selector' => '{{WRAPPER}} .elementor-icon-box-title',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_button_style',
            [
                'label' => __('Button'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'skin' => 'button',
                    'navigation!' => 'arrows',
                ],
            ]
        );

        $this->addResponsiveControl(
            'button_spacing',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-pagination, {{WRAPPER}} .elementor-row' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'type' => 'default',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'button_text_shadow',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->startControlsTabs(
            'button_tabs',
            [
                'condition' => [
                    'type!' => 'autoload',
                ],
            ]
        );

        $this->startControlsTab(
            'button_normal',
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
                    '{{WRAPPER}} a.elementor-button:not(#e)' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'type!' => '',
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
                'condition' => [
                    'type!' => '',
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
                'condition' => [
                    'type!' => '',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'button_hover',
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
                    '{{WRAPPER}} a.elementor-button:not(#e):hover' => 'color: {{VALUE}};',
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

        $this->startControlsTab(
            'button_active',
            [
                'label' => __('Active'),
            ]
        );

        $this->addControl(
            'button_active_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button.elementor-active:not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_active_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button.elementor-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_active_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button.elementor-active' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'separator_button_tabs',
            [
                'type' => ControlsManager::DIVIDER,
                'condition' => [
                    'type!' => 'autoload',
                ],
            ]
        );

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

        $this->startControlsSection(
            'section_arrows_style',
            [
                'label' => __('Arrows'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'type' => 'default',
                    'navigation!' => 'pages',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'arrow_typography',
                'selector' => '{{WRAPPER}} .ce-pagination__arrow',
            ]
        );

        $this->startControlsTabs('arrow_tabs');

        $this->startControlsTab(
            'arrow_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'arrow_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.ce-pagination__arrow:not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'arrow_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-pagination__arrow' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'skin' => 'button',
                ],
            ]
        );

        $this->addControl(
            'arrow_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-pagination__arrow' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'skin' => 'button',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'arrow_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'arrow_hover_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.ce-pagination__arrow:not(#e):hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'arrow_hover_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-pagination__arrow:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'skin' => 'button',
                ],
            ]
        );

        $this->addControl(
            'arrow_hover_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-pagination__arrow:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'skin' => 'button',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'arrow_disabled',
            [
                'label' => __('Disabled'),
            ]
        );

        $this->addControl(
            'arrow_disabled_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.ce-pagination__arrow.ce-disabled:not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'arrow_disabled_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-pagination__arrow.ce-disabled' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'skin' => 'button',
                ],
            ]
        );

        $this->addControl(
            'arrow_disabled_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-pagination__arrow.ce-disabled' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'skin' => 'button',
                ],
            ]
        );

        $this->addControl(
            'arrow_disabled_opacity',
            [
                'label' => __('Opacity'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-pagination__arrow.ce-disabled' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'arrow_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-pagination__arrow' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'skin' => 'button',
                ],
            ]
        );

        $this->addControl(
            'arrow_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .ce-pagination__arrow' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'skin' => 'button',
                ],
            ]
        );

        $this->addResponsiveControl(
            'arrow_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .ce-pagination__arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'skin' => 'button',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $listing = $GLOBALS['smarty']->tpl_vars['listing']->value;
        $pagination = &$listing['pagination'];

        if (!$listing['products'] && empty(${'_GET'}['q'])) {
            return;
        }
        if (!$pagination['should_be_displayed']) {
            return print '<!-- listing-pagination -->';
        }
        if ('default' !== $type = $this->getSettings('type')) {
            return $pagination['current_page'] === $pagination['pages_count']
                ? print '<!-- listing-pagination  -->'
                : $this->{"render$type"}(array_pop($pagination['pages'])['url']);
        }
        $settings = $this->getSettingsForDisplay();
        $show_arrows = 'pages' !== $settings['navigation'];
        $show_pages = 'arrows' !== $settings['navigation'];
        $prev = $pagination['current_page'] === 1 ? [
            'clickable' => false,
            'url' => '#',
        ] : array_shift($pagination['pages']);
        $next = $pagination['current_page'] === $pagination['pages_count'] ? [
            'clickable' => false,
            'url' => '#',
        ] : array_pop($pagination['pages']);
        ?>
        <nav class="ce-pagination" role="navigation">
        <?php if ('classic' === $settings['skin']) { ?>
            <?php if ($show_arrows) { ?>
                <a href="<?php echo $prev['url']; ?>" rel="prev" class="js-search-link elementor-icon-list-item<?php $prev['clickable'] || print ' ce-disabled'; ?> ce-pagination__arrow">
                    <span class="elementor-icon-list-text"><?php echo $settings['prev_text']; ?></span>
                <?php if ($settings['prev_icon']['value']) { ?>
                    <span class="elementor-icon-list-icon"><?php IconsManager::renderIcon($settings['prev_icon']); ?></span>
                <?php } ?>
                </a>
            <?php } ?>
            <?php if ($show_pages) { ?>
                <div class="elementor-row elementor-icon-list-items elementor-inline-items">
                <?php foreach ($pagination['pages'] as &$page) { ?>
                    <a<?php $page['clickable'] && print ' href="' . esc_attr($page['url']) . '" rel="nofollow"'; ?> class="js-search-link elementor-icon-list-item<?php $page['current'] && print ' elementor-active'; ?>">
                        <span class="elementor-icon-list-text"><?php echo $page['page'] ?: '…'; ?></span>
                    </a>
                <?php } ?>
                </div>
            <?php } ?>
            <?php if ($show_arrows) { ?>
                <a href="<?php echo $next['url']; ?>" rel="next" class="js-search-link elementor-icon-list-item<?php $next['clickable'] || print ' ce-disabled'; ?> ce-pagination__arrow">
                    <span class="elementor-icon-list-text"><?php echo $settings['next_text']; ?></span>
                <?php if ($settings['next_icon']['value']) { ?>
                    <span class="elementor-icon-list-icon"><?php IconsManager::renderIcon($settings['next_icon']); ?></span>
                <?php } ?>
                </a>
            <?php } ?>
        <?php } elseif ('button' === $settings['skin']) {
            $button_classes = 'js-search-link elementor-button elementor-size-' . esc_attr($settings['button_size']);
            ?>
            <?php if ($show_arrows) { ?>
                <a href="<?php echo $prev['url']; ?>" rel="prev" class="ce-pagination__arrow <?php echo $button_classes . ($prev['clickable'] ? '' : ' ce-disabled'); ?>">
                    <span class="elementor-button-content-wrapper">
                    <?php if ($settings['prev_icon']['value']) { ?>
                        <span class="elementor-button-icon elementor-align-icon-<?php echo $settings['prev_icon_align']; ?>"><?php IconsManager::renderIcon($settings['prev_icon']); ?></span>
                    <?php } ?>
                        <span class="elementor-button-text"><?php echo $settings['prev_text']; ?></span>
                    </span>
                </a>
            <?php } ?>
            <?php if ($show_pages) { ?>
                <div class="elementor-row">
                <?php foreach ($pagination['pages'] as &$page) { ?>
                    <a href="<?php echo $page['url']; ?>" rel="nofollow" class="<?php echo $button_classes . ($page['current'] ? ' elementor-active' : ($page['page'] ? '' : ' ce-disabled')); ?>">
                        <span class="elementor-button-content-wrapper">
                            <span class="elementor-button-text"><?php echo $page['page'] ?: '…'; ?></span>
                        </span>
                    </a>
                <?php } ?>
                </div>
            <?php } ?>
            <?php if ($show_arrows) { ?>
                <a href="<?php echo $next['url']; ?>" rel="next" class="ce-pagination__arrow <?php echo $button_classes . ($next['clickable'] ? '' : ' ce-disabled'); ?>">
                    <span class="elementor-button-content-wrapper">
                    <?php if ($settings['next_icon']['value']) { ?>
                        <span class="elementor-button-icon elementor-align-icon-<?php echo $settings['next_icon_align']; ?>"><?php IconsManager::renderIcon($settings['next_icon']); ?></span>
                    <?php } ?>
                        <span class="elementor-button-text"><?php echo $settings['next_text']; ?></span>
                    </span>
                </a>
            <?php } ?>
        <?php } ?>
        </nav>
        <?php
    }

    protected function renderLoadMore($url)
    {
        $settings = $this->getSettingsForDisplay();

        if ('classic' === $settings['skin']) { ?>
            <div class="elementor-icon-box-wrapper">
            <?php if (!empty($settings['more_icon']['value'])) { ?>
                <a href="<?php echo esc_attr($url); ?>" rel="next" class="elementor-icon-box-icon ce-load-more">
                    <span class="elementor-icon">
                        <?php IconsManager::renderIcon($settings['more_icon'], ['aria-hidden' => 'true']); ?>
                        <?php IconsManager::renderIcon($settings['load_icon'], ['class' => 'ce-spin']); ?>
                    </span>
                </a>
            <?php } ?>
            <?php if (!$settings['text'] || trim($settings['text'])) { ?>
                <div class="elementor-icon-box-content">
                    <span class="elementor-icon-box-title ce-load-more"><?php echo $settings['text'] ?: __('Load More'); ?></span>
                </div>
            <?php } ?>
            </div>
            <?php
        } elseif ('button' === $settings['skin']) {
            $this->addRenderAttribute('button', 'href', $url);
            $this->addRenderAttribute('button', 'class', 'ce-load-more elementor-button elementor-size-' . $settings['button_size']);
            ?>
            <a <?php $this->printRenderAttributeString('button'); ?> rel="next" role="button">
                <span class="elementor-button-content-wrapper">
                <?php if ($settings['more_icon']['value']) { ?>
                    <span class="elementor-button-icon elementor-align-icon-<?php echo $settings['icon_align']; ?>">
                        <?php IconsManager::renderIcon($settings['more_icon'], ['aria-hidden' => 'true']); ?>
                        <?php IconsManager::renderIcon($settings['load_icon'], ['class' => 'ce-spin']); ?>
                    </span>
                <?php } ?>
                <?php if (trim($settings['text']) || !$settings['text']) { ?>
                    <span class="elementor-button-text"><?php echo $settings['text'] ?: __('Load More'); ?></span>
                <?php } ?>
                </span>
            </a>
            <?php
        }
    }

    protected function renderAutoLoad($url)
    {
        $settings = $this->getSettingsForDisplay();

        if ('classic' === $settings['skin']) { ?>
            <div class="elementor-icon-box-wrapper">
                <a href="<?php echo esc_attr($url); ?>" rel="next" class="elementor-icon-box-icon ce-auto-load">
                <?php if (!empty($settings['load_icon']['value'])) { ?>
                    <span class="elementor-icon"><?php IconsManager::renderIcon($settings['load_icon'], ['class' => 'ce-spin']); ?></span>
                <?php } ?>
                </a>
            <?php if (!$settings['text'] || trim($settings['text'])) { ?>
                <div class="elementor-icon-box-content">
                    <span class="elementor-icon-box-title"><?php echo $settings['text'] ?: __('Loading'); ?></span>
                </div>
            <?php } ?>
            </div>
            <?php
        } elseif ('button' === $settings['skin']) {
            $this->addRenderAttribute('button', 'href', $url);
            $this->addRenderAttribute('button', 'class', 'ce-auto-load elementor-button elementor-size-' . $settings['button_size']);
            ?>
            <a <?php $this->printRenderAttributeString('button'); ?> rel="next" role="button">
                <span class="elementor-button-content-wrapper">
                <?php if ($settings['load_icon']['value']) { ?>
                    <span class="elementor-button-icon elementor-align-icon-<?php echo $settings['icon_align']; ?>">
                        <?php IconsManager::renderIcon($settings['load_icon'], ['class' => 'ce-spin']); ?>
                    </span>
                <?php } ?>
                <?php if (trim($settings['text']) || !$settings['text']) { ?>
                    <span class="elementor-button-text"><?php echo $settings['text'] ?: __('Loading'); ?></span>
                <?php } ?>
                </span>
            </a>
            <?php
        }
    }

    public function renderPlainContent()
    {
    }
}
