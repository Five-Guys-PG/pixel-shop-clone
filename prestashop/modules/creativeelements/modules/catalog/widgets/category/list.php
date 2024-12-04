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

use CE\ModulesXCatalogXControlsXSelectCategory as SelectCategory;

class ModulesXCatalogXWidgetsXCategoryXList extends WidgetBase
{
    const REMOTE_RENDER = true;

    private $properties;

    public function getName()
    {
        return 'category-list';
    }

    public function getTitle()
    {
        return isset($this->properties['title']) ? $this->properties['title'] : __('Category List');
    }

    public function getIcon()
    {
        return isset($this->properties['icon']) ? $this->properties['icon'] : 'eicon-bullet-list';
    }

    public function getCategories()
    {
        return isset($this->properties['categories']) ? $this->properties['categories'] : ['catalog'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'category', 'listing', 'buttons', 'subcategories'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_category_list',
            [
                'label' => __('Category List'),
            ]
        );

        $this->addControl(
            'skin',
            [
                'label' => __('Skin'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'icon-list' => __('Classic'),
                    'button' => __('Button'),
                ],
                'default' => 'icon-list',
                'prefix_class' => 'elementor-widget-',
                'style_transfer' => true,
                'render_type' => 'template',
            ]
        );

        $this->addControl(
            'title_text',
            [
                'label' => __('Title'),
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Enter your title'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->addControl(
            'title_display',
            [
                'label' => __('Display'),
                'type' => ControlsManager::CHOOSE,
                'options' => WidgetHeading::getDisplaySizes(),
                'style_transfer' => true,
                'condition' => [
                    'title_text!' => '',
                ],
            ]
        );

        $this->addControl(
            'title_tag',
            [
                'label' => __('HTML Tag'),
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
                'condition' => [
                    'title_text!' => '',
                ],
            ]
        );

        $this->addControl(
            'id_category',
            [
                'label' => __('Root Category'),
                'label_block' => true,
                'type' => SelectCategory::CONTROL_TYPE,
                'select2options' => [
                    'allowClear' => false,
                ],
                'extend' => [
                    '0' => __('Current Category') . ' / ' . __('Default'),
                ],
                'default' => 0,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'view',
            [
                'label' => __('Layout'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'traditional' => [
                        'title' => __('Default'),
                        'icon' => 'eicon-editor-list-ul',
                    ],
                    'inline' => [
                        'title' => __('Inline'),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                ],
                'default' => 'traditional',
                'render_type' => 'template',
                'classes' => 'elementor-control-start-end',
                'style_transfer' => true,
                'prefix_class' => 'elementor-icon-list--layout-',
                'condition' => [
                    'skin' => 'icon-list',
                ],
            ]
        );

        $this->addControl(
            'list_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'recommended' => [
                    'ce-icons' => [
                        'minus',
                    ],
                    'fa-solid' => [
                        'folder',
                        'folder-closed',
                        'folder-open',
                    ],
                    'fa-regular' => [
                        'folder',
                        'folder-closed',
                        'folder-open',
                    ],
                ],
                'condition' => [
                    'skin' => 'icon-list',
                ],
            ]
        );

        $this->addResponsiveControl(
            'icon_align',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'elementor%s-align-',
                'condition' => [
                    'skin' => 'icon-list',
                ],
            ]
        );

        $columns = range(1, 10);
        $columns = array_combine($columns, $columns);
        $columns[''] = __('Default');

        $this->addResponsiveControl(
            'columns',
            [
                'label' => __('Columns'),
                'type' => ControlsManager::SELECT,
                'options' => &$columns,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-items' => 'columns: {{VALUE}};',
                ],
                'condition' => [
                    'skin' => 'icon-list',
                    'view!' => 'inline',
                ],
            ]
        );

        $this->addResponsiveControl(
            'column_gap',
            [
                'label' => __('Columns Gap'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    '%' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'em' => [
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-items' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'skin' => 'icon-list',
                    'view!' => 'inline',
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
                'prefix_class' => 'elementor-button-',
                'style_transfer' => true,
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
                'prefix_class' => 'elementor%s-align-',
                'selectors' => [
                    '{{WRAPPER}} .ce-category-list' => 'justify-content: {{VALUE}}',
                ],
                'style_transfer' => true,
                'condition' => [
                    'skin' => 'button',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_title_style',
            [
                'label' => __('Title'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'title_text!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'title_align',
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
                    '{{WRAPPER}} .elementor-heading-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->addGroupControl(
            GroupControlTextStroke::getType(),
            [
                'name' => 'title_text_stroke',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'title_text_shadow',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->addResponsiveControl(
            'title_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_icon_list',
            [
                'label' => __('List'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'skin' => 'icon-list',
                ],
            ]
        );

        $this->addResponsiveControl(
            'space_between',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item' => 'margin: 0 calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .elementor-icon-list-items.elementor-inline-items' => 'margin: 0 calc(-{{SIZE}}{{UNIT}}/2)',
                    'body.lang-rtl {{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after' => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
                    'body:not(.lang-rtl) {{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after' => 'right: calc(-{{SIZE}}{{UNIT}}/2)',
                ],
            ]
        );

        $this->addControl(
            'heading_link',
            [
                'label' => __('Link'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'text_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'text_color_hover',
            [
                'label' => __('Hover'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'icon_typography',
                'selector' => '{{WRAPPER}} .elementor-icon-list-item',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .elementor-icon-list-item',
            ]
        );

        $this->addControl(
            'text_indent',
            [
                'label' => __('Text Indent'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-text' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'divider',
            [
                'label' => __('Divider'),
                'type' => ControlsManager::POPOVER_TOGGLE,
                'label_off' => __('Off'),
                'label_on' => __('On'),
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'content: ""',
                ],
                'separator' => 'before',
            ]
        );

        $this->startPopover();

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
                    'divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child):after' => 'border-top-style: {{VALUE}}',
                    '{{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:not(:last-child):after' => 'border-left-style: {{VALUE}}',
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
                    'divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .elementor-inline-items .elementor-icon-list-item:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'divider_width',
            [
                'label' => __('Width'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'condition' => [
                    'divider' => 'yes',
                    'view!' => 'inline',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
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
                    'divider' => 'yes',
                    'view' => 'inline',
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

        $this->endPopover();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_icon_style',
            [
                'label' => __('Icon'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'skin' => 'icon-list',
                    'list_icon[value]!' => '',
                ],
            ]
        );

        $this->addControl(
            'icon_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} i.elementor-icon-list-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} svg.elementor-icon-list-icon' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'icon_color_hover',
            [
                'label' => __('Hover'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:hover i.elementor-icon-list-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-icon-list-item:hover svg.elementor-icon-list-icon' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'icon_vertical_align',
            [
                'label' => __('Vertical Align'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Start'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => __('End'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item a' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'icon_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 14,
                ],
                'range' => [
                    'px' => [
                        'min' => 6,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} i.elementor-icon-list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} svg.elementor-icon-list-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_button_style',
            [
                'label' => __('Buttons'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'skin' => 'button',
                ],
            ]
        );

        $this->addControl(
            'overflow_scrolling',
            [
                'label' => __('Overflow Scrolling'),
                'type' => ControlsManager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .ce-category-list' => 'flex-wrap: nowrap; overflow-x: auto; scrollbar-width: thin;',
                    '{{WRAPPER}} .elementor-button-text' => 'white-space: pre;',
                ],
            ]
        );

        $this->addResponsiveControl(
            'button_spacing',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-category-list' => 'gap: {{SIZE}}{{UNIT}};',
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

        $this->startControlsTabs('button_tabs');

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
                    '{{WRAPPER}} a.elementor-button:not(#e):hover, {{WRAPPER}} a.elementor-button:not(#e):active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_hover_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_hover_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:active' => 'border-color: {{VALUE}};',
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
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
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

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-heading';
    }

    protected function render()
    {
        $context = \Context::getContext();
        $settings = $this->getSettingsForDisplay();

        if (!$settings['id_category'] && isset($context->smarty->tpl_vars['subcategories'])) {
            $subcategories = $context->smarty->tpl_vars['subcategories']->value;
        } else {
            $id_category = $settings['id_category'] ?: (
                $context->controller instanceof \ProductController ? $context->controller->getProduct()->id_category_default : $context->shop->id_category
            );
            foreach ($subcategories = \Category::getChildren($id_category, $context->language->id, true, $context->shop->id) as $i => $cat) {
                $subcategories[$i]['url'] = $context->link->getCategoryLink($cat['id_category'], $cat['link_rewrite'], $context->language->id, null, $context->shop->id);
            }
        }

        if ('' !== $settings['title_text']) {
            $this->addRenderAttribute('title_text', 'class', 'elementor-heading-title');
            $this->addInlineEditingAttributes('title_text');
            $settings['title_display'] && $this->addRenderAttribute('title_text', 'class', 'ce-display-' . $settings['title_display']);

            echo "<{$settings['title_tag']} {$this->getRenderAttributeString('title_text')}>{$settings['title_text']}</{$settings['title_tag']}>";
        }
        if ('icon-list' === $settings['skin']) {
            $this->addRenderAttribute('icon_list', 'class', 'elementor-icon-list-items');
            $this->addRenderAttribute('list_item', 'class', 'elementor-icon-list-item');

            if ('inline' === $settings['view']) {
                $this->addRenderAttribute('icon_list', 'class', 'elementor-inline-items');
                $this->addRenderAttribute('list_item', 'class', 'elementor-inline-item');
            }
            $icon = !empty($settings['list_icon']['value'])
                && ob_start()
                && IconsManager::renderIcon($settings['list_icon'], ['class' => 'elementor-icon-list-icon'])
                ? ob_get_clean() : '';
            echo "<ul {$this->getRenderAttributeString('icon_list')}>";
            foreach ($subcategories as &$subcategory) { ?>
                <li class="elementor-icon-list-item">
                    <a href="<?php echo esc_attr($subcategory['url']); ?>">
                        <?php echo $icon; ?>
                        <span class="elementor-icon-list-text"><?php echo $subcategory['name']; ?></span>
                    </a>
                </li><?php
            }
            echo '</ul>';
        } elseif ('button' === $settings['skin']) {
            $button_size = esc_attr($settings['button_size']);
            echo '<div class="ce-category-list">';
            foreach ($subcategories as &$subcategory) { ?>
                <a href="<?php echo esc_attr($subcategory['url']); ?>" class="elementor-button elementor-size-<?php echo $button_size; ?>">
                    <span class="elementor-button-content-wrapper">
                        <span class="elementor-button-text"><?php echo $subcategory['name']; ?></span>
                    </span>
                </a><?php
            }
            echo '</div>';
        }
    }

    protected function contentTemplate()
    {
    }

    public function renderPlainContent()
    {
    }

    public function __construct(array $data = [], $args = null, array $properties = [])
    {
        $this->properties = $properties;

        parent::__construct($data, $args);
    }
}
