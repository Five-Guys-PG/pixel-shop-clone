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

class ModulesXCatalogXWidgetsXProductXAttachments extends WidgetBase
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-attachments';
    }

    public function getTitle()
    {
        return __('Product Attachments');
    }

    public function getIcon()
    {
        return 'eicon-product-pages';
    }

    public function getCategories()
    {
        return ['product-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'attachment', 'download', 'document', 'product'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_product_attachments',
            [
                'label' => __('Product Attachments'),
            ]
        );

        $this->addControl(
            'title_text',
            [
                'label' => __('Title'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __('Enter your title'),
                'label_block' => true,
            ]
        );

        $this->addControl(
            'selected_icon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICONS,
                'default' => [
                    'value' => 'fas fa-download',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'file-pdf',
                        'download',
                        'file-arrow-down',
                        'cloud-arrow-down',
                        'paperclip',
                        'link',
                    ],
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
                'prefix_class' => 'elementor-view-',
            ]
        );

        $this->addControl(
            'shape',
            [
                'label' => __('Shape'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'circle' => __('Circle'),
                    'square' => __('Square'),
                ],
                'default' => 'circle',
                'condition' => [
                    'view!' => 'default',
                    'selected_icon[value]!' => '',
                ],
                'prefix_class' => 'elementor-shape-',
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
                'default' => 'h3',
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
            'heading_align',
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
            'heading_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}}.elementor-widget-heading .elementor-heading-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'heading_text_shadow',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->addResponsiveControl(
            'heading_spacing',
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
            'section_style_icon',
            [
                'label' => __('Icon'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->startControlsTabs('icon_colors');

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
            ]
        );

        $this->addControl(
            'secondary_color',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
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

        $this->addControl(
            'hover_animation',
            [
                'label' => __('Hover Animation'),
                'type' => ControlsManager::HOVER_ANIMATION,
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addResponsiveControl(
            'icon_space',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.elementor-position-top .elementor-icon-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.elementor-tablet-position-top .elementor-icon-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.elementor-mobile-position-top .elementor-icon-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                'default' => [
                    'size' => 20,
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
            'border_width',
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
            'border_radius',
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
                    '{{WRAPPER}} .elementor-icon-box-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'content_vertical_alignment',
            [
                'label' => __('Vertical Alignment'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'top' => __('Top'),
                    'middle' => __('Middle'),
                    'bottom' => __('Bottom'),
                ],
                'default' => 'top',
                'prefix_class' => 'elementor-vertical-align-',
            ]
        );

        $this->addResponsiveControl(
            'space_between',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-attachment:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'heading_label',
            [
                'label' => __('Label'),
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
                    '{{WRAPPER}} .elementor-icon-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .elementor-icon-box-description',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'description_shadow',
                'selector' => '{{WRAPPER}} .elementor-icon-box-description',
            ]
        );

        $this->endControlsSection();
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-heading elementor-widget-icon-box' .
            ' elementor-position-' . (is_rtl() ? 'right' : 'left');
    }

    protected function render()
    {
        $product = $GLOBALS['smarty']->tpl_vars['product']->value;

        if (!$product['attachments']) {
            return;
        }
        $settings = $this->getSettingsForDisplay();

        $this->addRenderAttribute('icon', 'class', [
            'elementor-icon',
            'elementor-animation-' . $settings['hover_animation'],
        ]);
        $icon = IconsManager::getBcIcon($settings, 'icon', ['aria-hidden' => 'true']);

        if ('' !== $settings['title_text']) {
            $this->addRenderAttribute('title_text', 'class', 'elementor-heading-title');
            $settings['title_display'] && $this->addRenderAttribute('title_text', 'class', 'ce-display-' . $settings['title_display']);
            $this->addInlineEditingAttributes('title_text', 'none');
            ?>
            <<?php echo $settings['title_size']; ?> <?php $this->printRenderAttributeString('title_text'); ?>>
                <?php echo $settings['title_text']; ?>
            </<?php echo $settings['title_size']; ?>>
            <?php
        }
        foreach ($product['attachments'] as $attachment) {
            $url = \Link::getUrlSmarty([
                'entity' => 'attachment',
                'params' => ['id_attachment' => $attachment['id_attachment']],
            ]); ?>
            <div class="ce-attachment elementor-icon-box-wrapper">
            <?php if ($icon) { ?>
                <div class="elementor-icon-box-icon">
                    <a <?php $this->printRenderAttributeString('icon'); ?> href="<?php echo esc_attr($url); ?>"><?php echo $icon; ?></a>
                </div>
            <?php } ?>
                <div class="elementor-icon-box-content">
                    <a class="elementor-icon-box-title" href="<?php echo esc_attr($url); ?>"><?php echo $attachment['name']; ?></a>
                    <p class="elementor-icon-box-description"><?php echo $attachment['description']; ?></p>
                </div>
            </div>
            <?php
        }
    }

    public function renderPlainContent()
    {
    }

    protected function contentTemplate()
    {
    }
}
