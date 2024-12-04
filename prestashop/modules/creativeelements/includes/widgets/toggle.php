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

/**
 * Elementor toggle widget.
 *
 * Elementor widget that displays a collapsible display of content in an toggle
 * style, allowing the user to open multiple items.
 *
 * @since 1.0.0
 */
class WidgetToggle extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve toggle widget name.
     *
     * @since 1.0.0
     *
     * @return string Widget name
     */
    public function getName()
    {
        return 'toggle';
    }

    /**
     * Get widget title.
     *
     * Retrieve toggle widget title.
     *
     * @since 1.0.0
     *
     * @return string Widget title
     */
    public function getTitle()
    {
        return __('Toggle');
    }

    /**
     * Get widget icon.
     *
     * Retrieve toggle widget icon.
     *
     * @since 1.0.0
     *
     * @return string Widget icon
     */
    public function getIcon()
    {
        return 'eicon-toggle';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     *
     * @return array Widget keywords
     */
    public function getKeywords()
    {
        return ['tabs', 'accordion', 'toggle'];
    }

    protected function isDynamicContent()
    {
        return false;
    }

    /**
     * Register toggle widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_toggle',
            [
                'label' => __('Toggle'),
            ]
        );

        $repeater = new Repeater();

        $repeater->addControl(
            'tab_title',
            [
                'label' => __('Title & Description'),
                'type' => ControlsManager::TEXT,
                'default' => __('Toggle Title'),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->addControl(
            'tab_content',
            [
                'label' => __('Content'),
                'type' => ControlsManager::WYSIWYG,
                'default' => __('Toggle Content'),
                'show_label' => false,
            ]
        );

        $this->addControl(
            'tabs',
            [
                'label' => __('Toggle Items'),
                'type' => ControlsManager::REPEATER,
                'fields' => $repeater->getControls(),
                'default' => [
                    [
                        'tab_title' => __('Toggle #1'),
                        'tab_content' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'),
                    ],
                    [
                        'tab_title' => __('Toggle #2'),
                        'tab_content' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'),
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->addControl(
            'view',
            [
                'label' => __('View'),
                'type' => ControlsManager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->addControl(
            'selected_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'icon',
                'recommended' => [
                    'fa-solid' => [
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
                    'value' => 'fas fa-caret' . (is_rtl() ? '-left' : '-right'),
                    'library' => 'fa-solid',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'selected_active_icon',
            [
                'label' => __('Active Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'icon_active',
                'recommended' => [
                    'fa-solid' => [
                        'chevron-up',
                        'angle-up',
                        'angles-up',
                        'caret-up',
                        'square-caret-up',
                    ],
                    'fa-regular' => [
                        'square-caret-up',
                    ],
                ],
                'default' => [
                    'value' => 'fas fa-caret-up',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->addControl(
            'title_display',
            [
                'label' => __('Title Display'),
                'type' => ControlsManager::CHOOSE,
                'options' => WidgetHeading::getDisplaySizes(),
                'style_transfer' => true,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'title_html_tag',
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
                ],
                'default' => 'div',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_toggle_style',
            [
                'label' => __('Toggle'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-tab-title' => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-tab-content' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-tab-content' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-tab-title' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'space_between',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .elementor-toggle-item',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_toggle_style_title',
            [
                'label' => __('Title'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'title_background',
            [
                'label' => __('Background'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-tab-title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // The title selector specificity is to override Theme Style
        $this->addControl(
            'title_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-toggle-title:not(#e), {{WRAPPER}} .elementor-toggle-icon' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
            ]
        );

        $this->addControl(
            'tab_active_color',
            [
                'label' => __('Active Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-active a.elementor-toggle-title:not(#e), {{WRAPPER}} .elementor-active .elementor-toggle-icon' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} a.elementor-toggle-title',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
            ]
        );

        $this->addGroupControl(
            GroupControlTextStroke::getType(),
            [
                'name' => 'text_stroke',
                'selector' => '{{WRAPPER}} a.elementor-toggle-title',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'title_shadow',
                'selector' => '{{WRAPPER}} a.elementor-toggle-title',
            ]
        );

        $this->addResponsiveControl(
            'title_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_toggle_style_icon',
            [
                'label' => __('Icon'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->addControl(
            'icon_align',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Start'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __('End'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => is_rtl() ? 'right' : 'left',
                'toggle' => false,
            ]
        );

        $this->addControl(
            'icon_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title .elementor-toggle-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title .elementor-toggle-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'icon_active_color',
            [
                'label' => __('Active Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title.elementor-active .elementor-toggle-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title.elementor-active .elementor-toggle-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'icon_space',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle-icon.elementor-toggle-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-toggle-icon.elementor-toggle-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_toggle_style_content',
            [
                'label' => __('Content'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'content_background_color',
            [
                'label' => __('Background'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-tab-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'content_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-tab-content' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .elementor-tab-content',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'content_shadow',
                'selector' => '{{WRAPPER}} .elementor-tab-content',
            ]
        );

        $this->addResponsiveControl(
            'content_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render toggle widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $id_int = substr($this->getIdInt(), 0, 3);
        $display_class = $settings['title_display'] ? " ce-display-{$settings['title_display']}" : '';
        ?>
        <div class="elementor-toggle" role="tablist">
        <?php foreach ($settings['tabs'] as $index => &$item) {
            if (!$tab_content = trim(isset($item['__dynamic__']['tab_content']) ? $item['tab_content'] : $this->parseTextEditor($item['tab_content']))) {
                continue;
            }
            $tab_count = $index + 1;
            $tab_title_key = $this->getRepeaterSettingKey('tab_title', 'tabs', $index);
            $tab_content_key = $this->getRepeaterSettingKey('tab_content', 'tabs', $index);

            $this->addRenderAttribute($tab_title_key, [
                'id' => 'elementor-tab-title-' . $id_int . $tab_count,
                'class' => 'elementor-tab-title',
                'data-tab' => $tab_count,
                'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
            ]);
            $this->addRenderAttribute($tab_content_key, [
                'id' => 'elementor-tab-content-' . $id_int . $tab_count,
                'class' => ['elementor-tab-content', 'elementor-clearfix'],
                'data-tab' => $tab_count,
                'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count,
            ]);
            $this->addInlineEditingAttributes($tab_content_key, 'advanced'); ?>
            <div class="elementor-toggle-item">
                <<?php echo esc_html($settings['title_html_tag']); ?> <?php $this->printRenderAttributeString($tab_title_key); ?> role="tab">
                <?php if ($icon = IconsManager::getBcIcon($settings, 'icon', ['class' => 'elementor-toggle-icon-closed'])) { ?>
                    <span class="elementor-toggle-icon elementor-toggle-icon-<?php echo $settings['icon_align']; ?>" aria-hidden="true">
                        <?php echo $icon; ?>
                        <?php echo IconsManager::getBcIcon($settings, 'icon_active', ['selected' => 'selected_active_icon', 'class' => 'elementor-toggle-icon-opened']); ?>
                    </span>
                <?php } ?>
                    <a href="javascript:;" class="elementor-toggle-title<?php echo $display_class; ?>"><?php echo $item['tab_title']; ?></a>
                </<?php echo esc_html($settings['title_html_tag']); ?>>
                <div <?php $this->printRenderAttributeString($tab_content_key); ?> role="tabpanel"><?php echo $tab_content; ?></div>
            </div>
        <?php } ?>
        </div>
        <?php
    }

    /**
     * Render toggle widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 2.9.0
     */
    protected function contentTemplate()
    {
        ?>
        <div class="elementor-toggle" role="tablist">
        <#
        if ( ! settings.tabs ) {
            return;
        }
        var tabindex = view.getIDInt().toString().substr( 0, 3 ),
            icon;

        _.each( settings.tabs, function( item, index ) {
            var tabCount = index + 1,
                tabTitleKey = view.getRepeaterSettingKey( 'tab_title', 'tabs', index ),
                tabContentKey = view.getRepeaterSettingKey( 'tab_content', 'tabs', index );

            view.addRenderAttribute( tabTitleKey, {
                'id': 'elementor-tab-title-' + tabindex + tabCount,
                'class': 'elementor-tab-title',
                'data-tab': tabCount,
                'aria-controls': 'elementor-tab-content-' + tabindex + tabCount
            } );
            view.addRenderAttribute( tabContentKey, {
                'id': 'elementor-tab-content-' + tabindex + tabCount,
                'class': [ 'elementor-tab-content', 'elementor-clearfix' ],
                'data-tab': tabCount,
                'aria-labelledby': 'elementor-tab-title-' + tabindex + tabCount
            } );
            view.addInlineEditingAttributes( tabContentKey, 'advanced' );
            #>
            <div class="elementor-toggle-item">
                <{{{ settings.title_html_tag }}} {{{ view.getRenderAttributeString( tabTitleKey ) }}} role="tab">
                <# if ( icon = elementor.helpers.getBcIcon(view, settings, 'icon', {'class': 'elementor-toggle-icon-closed'}) ) { #>
                    <span class="elementor-toggle-icon elementor-toggle-icon-{{ settings.icon_align }}" aria-hidden="true">
                        {{{ icon }}}
                        {{{ elementor.helpers.getBcIcon(view, settings, 'icon_active', {selected: 'selected_active_icon', 'class': 'elementor-toggle-icon-opened'}) }}}
                    </span>
                <# } #>
                    <a href="javascript:;" class="elementor-toggle-title ce-display-{{ settings.title_display }}">{{{ item.tab_title }}}</a>
                </{{{ settings.title_html_tag }}}>
                <div {{{ view.getRenderAttributeString( tabContentKey ) }}} role="tabpanel">{{{ item.tab_content }}}</div>
            </div>
            <#
        } ); #>
        </div>
        <?php
    }
}
