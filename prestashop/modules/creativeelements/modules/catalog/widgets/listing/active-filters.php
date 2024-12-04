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

class ModulesXCatalogXWidgetsXListingXActiveFilters extends WidgetBase
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'listing-active-filters';
    }

    public function getTitle()
    {
        return __('Active Filters');
    }

    public function getIcon()
    {
        return 'eicon-check-circle';
    }

    public function getCategories()
    {
        return ['listing-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'category', 'products', 'listing', 'active', 'filters'];
    }

    protected function getActiveFilters()
    {
        $activeFilters = [];

        if ($facets = Helper::getFacets()) {
            foreach ($facets as &$facet) {
                foreach ($facet['filters'] as &$filter) {
                    $filter['active'] && $activeFilters[] = $filter;
                }
            }
        }

        return $activeFilters;
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_active_filters',
            [
                'label' => __('Active Filters'),
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
            'button_heading',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Buttons'),
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'show_label',
            [
                'label' => __('Label'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
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
                    'info' => __('Info'),
                    'success' => __('Success'),
                    'warning' => __('Warning'),
                    'danger' => __('Danger'),
                ],
                'default' => 'info',
                'style_transfer' => true,
                'prefix_class' => 'elementor-button-',
            ]
        );

        $this->addControl(
            'button_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::CHOOSE,
                'toggle' => false,
                'options' => WidgetButton::getButtonSizes(),
                'default' => 'xs',
                'style_transfer' => true,
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
                    '{{WRAPPER}} .ce-active-filters' => 'justify-content: {{VALUE}}',
                ],
                'style_transfer' => true,
            ]
        );

        $this->addControl(
            'remove_icon',
            [
                'label' => __('Remove Item Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['none'],
                'default' => [
                    'value' => 'ceicon-times',
                    'library' => 'ce-icons',
                ],
                'recommended' => [
                    'fa-regular' => [
                        'circle-xmark',
                        'eraser',
                        'trash-can',
                    ],
                    'fa-solid' => [
                        'eraser',
                        'trash-can',
                        'trash',
                        'rotate-left',
                        'circle-xmark',
                        'xmark',
                    ],
                    'ce-icons' => [
                        'times',
                        'close',
                        'delete-left',
                    ],
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
            ]
        );

        $this->addControl(
            'show_clear',
            [
                'label' => __('Clear Button'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_clear',
            [
                'label' => __('Clear Button'),
                'condition' => [
                    'show_clear!' => '',
                ],
            ]
        );

        $this->addControl(
            'clear_position',
            [
                'label' => __('Position'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'before' => __('Before'),
                    'after' => __('After'),
                ],
                'default' => 'after',
                'selectors_dictionary' => [
                    'before' => -1,
                    'after' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-active-filters__clear' => 'order: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'clear_text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __('Default'),
            ]
        );

        $this->addControl(
            'clear_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'ceicon-close',
                    'library' => 'ce-icons',
                ],
                'recommended' => [
                    'fa-regular' => [
                        'circle-xmark',
                        'eraser',
                        'trash-can',
                    ],
                    'fa-solid' => [
                        'eraser',
                        'trash-can',
                        'trash',
                        'rotate-left',
                        'circle-xmark',
                        'xmark',
                    ],
                    'ce-icons' => [
                        'times',
                        'close',
                        'delete-left',
                    ],
                ],
            ]
        );

        $this->addControl(
            'clear_icon_align',
            [
                'label' => __('Icon Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Before'),
                    'right' => __('After'),
                ],
                'condition' => [
                    'clear_icon[value]!' => '',
                ],
            ]
        );

        $this->addControl(
            'clear_icon_indent',
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
                    'toggle_icon[value]!' => '',
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
            'section_button_style',
            [
                'label' => __('Buttons'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'overflow_scrolling',
            [
                'label' => __('Overflow Scrolling'),
                'type' => ControlsManager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .ce-active-filters' => 'flex-wrap: nowrap; overflow-x: auto; scrollbar-width: thin;',
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
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-active-filters' => 'gap: {{SIZE}}{{UNIT}};',
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

        $this->startControlsSection(
            'section_clear_style',
            [
                'label' => __('Clear Button'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'show_clear!' => '',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'clear_typography',
                'selector' => '{{WRAPPER}} .elementor-button.ce-active-filters__clear',
            ]
        );

        $this->startControlsTabs('tabs_clear');

        $this->startControlsTab(
            'tab_clear_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'clear_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button.ce-active-filters__clear:not(#e)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'clear_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button.ce-active-filters__clear' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'clear_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button.ce-active-filters__clear' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tabs_clear_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'clear_text_color_hover',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button.ce-active-filters__clear:not(#e):hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'clear_bg_color_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button.ce-active-filters__clear:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'clear_border_color_hover',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button.ce-active-filters__clear:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'clear_border_width',
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
                    '{{WRAPPER}} .elementor-button.ce-active-filters__clear' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'clear_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button.ce-active-filters__clear' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'clear_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button.ce-active-filters__clear' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-heading elementor-widget-button';
    }

    protected function render()
    {
        $context = \Context::getContext();
        $listing = $context->smarty->tpl_vars['listing']->value;

        if (!$listing['products'] && empty(${'_GET'}['q'])) {
            return;
        }
        if (!$activeFilters = $this->getActiveFilters()) {
            return print '<!-- listing-active-filters -->';
        }
        $t = $context->getTranslator();
        $settings = $this->getSettingsForDisplay();
        $show_label = (bool) $settings['show_label'];
        ob_start();
        IconsManager::renderIcon($settings['remove_icon']);
        $remove_icon = ob_get_clean();
        $button_size = esc_attr($settings['button_size']);
        $icon_align = esc_attr($settings['icon_align']);

        if ('' !== $settings['title_text']) {
            $this->addRenderAttribute('title_text', 'class', 'elementor-heading-title');
            $this->addInlineEditingAttributes('title_text');
            $settings['title_display'] && $this->addRenderAttribute('title_text', 'class', 'ce-display-' . $settings['title_display']);

            echo "<{$settings['title_tag']} {$this->getRenderAttributeString('title_text')}>{$settings['title_text']}</{$settings['title_tag']}>";
        } ?>
        <div class="ce-active-filters">
        <?php foreach ($activeFilters as &$filter) { ?>
            <a href="<?php echo esc_attr($filter['nextEncodedFacetsURL']); ?>" class="js-search-link elementor-button elementor-size-<?php echo $button_size; ?>">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-icon elementor-align-icon-<?php echo $icon_align; ?>"><?php echo $remove_icon; ?></span>
                    <span class="elementor-button-text"><?php echo $show_label ? "{$t->trans('%1$s:', [$filter['facetLabel']], 'Shop.Theme.Catalog')} {$filter['label']}" : $filter['label']; ?></span>
                </span>
            </a>
        <?php } ?>
        <?php if ($settings['show_clear']) { ?>
            <a href="<?php echo esc_attr(Helper::getClearAllLink()); ?>" class="js-search-link ce-active-filters__clear elementor-button elementor-size-<?php echo $button_size; ?>">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-icon elementor-align-icon-<?php echo esc_attr($settings['clear_icon_align']); ?>"><?php IconsManager::renderIcon($settings['clear_icon']); ?></span>
                    <span class="elementor-button-text"><?php echo $settings['clear_text'] ?: $t->trans('Clear all', [], 'Shop.Theme.Actions'); ?></span>
                </span>
            </a>
        <?php } ?>
        </div>
        <?php
    }

    protected function contentTemplate()
    {
    }

    public function renderPlainContent()
    {
    }
}
