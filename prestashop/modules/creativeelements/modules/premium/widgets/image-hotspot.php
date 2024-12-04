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

class ModulesXPremiumXWidgetsXImageHotspot extends WidgetBase
{
    public function getName()
    {
        return 'image-hotspot';
    }

    public function getTitle()
    {
        return __('Image Hotspot');
    }

    public function getIcon()
    {
        return 'eicon-image-hotspot';
    }

    public function getCategories()
    {
        return ['premium'];
    }

    public function getKeywords()
    {
        return ['image', 'photo', 'hotspot'];
    }

    protected function isDynamicContent()
    {
        return false;
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_image',
            [
                'label' => __('Image'),
            ]
        );

        $this->addControl(
            'image',
            [
                'label' => __('Choose Image'),
                'type' => ControlsManager::MEDIA,
                'seo' => true,
                'default' => [
                    'url' => Utils::getPlaceholderImageSrc(),
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_hotspots',
            [
                'label' => __('Hotspots'),
            ]
        );

        $this->addControl(
            'hotspots',
            [
                'type' => ControlsManager::REPEATER,
                'default' => [
                    [
                        '_id' => Utils::generateRandomString(),
                        'title' => __('Hotspot #1'),
                        'description' => '<p>' . __('Lorem ipsum dolor sit amet, consectetur adipiscing elit.') . '</p>',
                        'x' => [
                            'size' => 25,
                            'unit' => '%',
                        ],
                        'y' => [
                            'size' => 50,
                            'unit' => '%',
                        ],
                        'link' => [
                            'url' => '',
                        ],
                    ],
                ],
                'fields' => [
                    [
                        'name' => 'title',
                        'label' => __('Title & Description'),
                        'type' => ControlsManager::TEXT,
                        'default' => __('Title'),
                        'label_block' => true,
                    ],
                    [
                        'name' => 'description',
                        'type' => ControlsManager::WYSIWYG,
                        'default' => '<p>' . __('Description') . '</p>',
                        'show_label' => false,
                    ],
                    [
                        'name' => 'x',
                        'label' => _x('X Position', 'Background Control'),
                        'type' => ControlsManager::SLIDER,
                        'size_units' => ['%'],
                        'default' => [
                            'size' => 50,
                            'unit' => '%',
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .elementor-image-hotspot-wrapper{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
                        ],
                    ],
                    [
                        'name' => 'y',
                        'label' => _x('Y Position', 'Background Control'),
                        'type' => ControlsManager::SLIDER,
                        'size_units' => ['%'],
                        'default' => [
                            'size' => 50,
                            'unit' => '%',
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .elementor-image-hotspot-wrapper{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
                        ],
                    ],
                    [
                        'name' => 'link',
                        'label' => __('Link'),
                        'type' => ControlsManager::URL,
                        'default' => ['url' => ''],
                        'placeholder' => 'http://your-link.com',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->addControl(
            'selected_icon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICONS,
                'fa4compatibility' => 'icon',
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
                'default' => 'framed',
                'prefix_class' => 'elementor-view-',
            ]
        );

        $this->addControl(
            'title_display',
            [
                'label' => __('Title Display'),
                'type' => ControlsManager::CHOOSE,
                'options' => WidgetHeading::getDisplaySizes(),
                'style_transfer' => true,
            ]
        );

        $this->addControl(
            'title_size',
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
                'default' => 'h4',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_image',
            [
                'label' => __('Image'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'image_size',
            [
                'label' => __('Size') . ' (%)',
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-hotspot' => 'max-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementor-image-hotspot > img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'image_border',
                'label' => __('Image Border'),
                'selector' => '{{WRAPPER}} .elementor-image-hotspot > img',
            ]
        );

        $this->addControl(
            'image_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-hotspot > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-image-hotspot > img',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_icon',
            [
                'label' => __('Icon'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
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
                'default' => [
                    'size' => 22,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_padding',
            [
                'label' => __('Icon Padding'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 0.4,
                    'unit' => 'em',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'view!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'icon_rotate',
            [
                'label' => __('Icon Rotate'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon:before' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->addControl(
            'icon_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'view!' => 'default',
                ],
            ]
        );

        $this->startControlsTabs('icon_tabs');

        $this->startControlsTab(
            'icon_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'icon_primary_color',
            [
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'icon_secondary_color',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'default' => '#ffffff',
                'condition' => [
                    'view!' => 'default',
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'icon_hover',
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

        $this->addControl(
            'icon_animation',
            [
                'label' => __('Animation'),
                'type' => ControlsManager::HOVER_ANIMATION,
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_box',
            [
                'label' => __('Box'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'box_width',
            [
                'label' => __('Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-hotspot-content' => 'width: {{SIZE}}px',
                ],
            ]
        );

        $this->addResponsiveControl(
            'box_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-hotspot-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'box_background',
                'types' => ['classic'],
                'selector' => '{{WRAPPER}} .elementor-image-hotspot-content',
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .elementor-image-hotspot-content',
            ]
        );

        $this->addControl(
            'box_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-hotspot-content' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->addControl(
            'box_shadow_type',
            [
                'label' => _x('Box Shadow', 'Box Shadow Control'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'outset' => __('Custom'),
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'box_shadow',
            [
                'type' => ControlsManager::BOX_SHADOW,
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-hotspot-content' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
                ],
                'condition' => [
                    'box_shadow_type!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_content',
            [
                'label' => __('Content'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'text_align',
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
                    '{{WRAPPER}} .elementor-image-hotspot-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'heading_title',
            [
                'label' => __('Title'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'title_bottom_space',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-hotspot-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-hotspot-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-image-hotspot-title',
            ]
        );

        $this->addControl(
            'heading_description',
            [
                'label' => __('Description'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'description_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-hotspot .elementor-tab-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'description_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-image-hotspot .elementor-tab-content',
            ]
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        if (empty($settings['image']['url'])) {
            return;
        }
        $icon = IconsManager::getBcIcon($settings, 'icon', ['aria-hidden' => 'true']);
        $displayClass = $settings['title_display'] ? " ce-display-{$settings['title_display']}" : '';
        ?>
        <div class="elementor-image-hotspot">
            <?php echo GroupControlImageSize::getAttachmentImageHtml($settings); ?>
        <?php foreach ($settings['hotspots'] as $i => $item) {
            $icon_tag = 'div';
            $this->addRenderAttribute("icon-$i", 'class', 'elementor-icon');
            $settings['icon_animation'] && $this->addRenderAttribute("icon-$i", 'class', 'elementor-animation-' . $settings['icon_animation']);

            if (!empty($item['link']['url'])) {
                $icon_tag = 'a';

                $this->addLinkAttributes("icon-$i", $item['link']);
            } ?>
            <div class="elementor-image-hotspot-wrapper elementor-repeater-item-<?php echo $item['_id']; ?>">
                <<?php echo $icon_tag; ?> <?php $this->printRenderAttributeString("icon-$i"); ?>><?php echo $icon; ?></<?php echo $icon_tag; ?>>
                <div class="elementor-image-hotspot-content">
                <?php if ('' !== $item['title']) { ?>
                    <<?php echo $settings['title_size']; ?> class="elementor-image-hotspot-title<?php echo $displayClass; ?>"><?php echo $item['title']; ?></<?php echo $settings['title_size']; ?>>
                <?php } ?>
                <?php if ('' !== $item['description']) { ?>
                    <div class="elementor-tab-content"><?php echo $item['description']; ?></div>
                <?php } ?>
                </div>
            </div>
        <?php } ?>
        </div>
        <?php
    }

    protected function contentTemplate()
    {
        ?>
        <#
        if (!settings.image.url) {
            return;
        }
        var icon = elementor.helpers.getBcIcon(view, settings, 'icon', {'aria-hidden': true}),
            displayClass = settings.title_display ? 'ce-display-' + settings.title_display : '';
        #>
        <div class="elementor-image-hotspot">
            <img class="elementor-image" src="{{ elementor.helpers.getMediaLink( settings.image.url ) }}">
        <#  _.each( settings.hotspots, function( item ) { #>
            <div class="elementor-image-hotspot-wrapper elementor-repeater-item-{{{item._id}}}">
                <# var iconTag = item.link.url ? 'a' : 'div'; #>
                <{{{ iconTag }}} class="elementor-icon elementor-animation-{{ settings.icon_animation }}"
                    <# if (item.link.url) { #>href="{{ item.link.url }}"<# } #>>
                    {{{ icon }}}
                </{{{ iconTag }}}>
                <div class="elementor-image-hotspot-content">
                <# if (item.title) { #>
                    <{{{settings.title_size}}} class="elementor-image-hotspot-title {{ displayClass }}">
                        {{{item.title}}}
                    </{{{settings.title_size}}}>
                <# } #>
                <# if (item.description) { #>
                    <div class="elementor-tab-content">{{{item.description}}}</div>
                <# } #>
                </div>
            </div>
        <# }) #>
        </div>
        <?php
    }
}
