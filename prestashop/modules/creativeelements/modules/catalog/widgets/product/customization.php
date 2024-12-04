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

class ModulesXCatalogXWidgetsXProductXCustomization extends WidgetBase
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-customization';
    }

    public function getTitle()
    {
        return __('Product Customization');
    }

    public function getIcon()
    {
        return 'eicon-custom';
    }

    public function getCategories()
    {
        return ['product-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'product', 'customization'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_customization',
            [
                'label' => __('Product Customization'),
            ]
        );

        $this->addControl(
            'title_text',
            [
                'label' => __('Title & Description'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __('Default'),
                'label_block' => true,
            ]
        );

        $this->addControl(
            'description_text',
            [
                'show_label' => false,
                'type' => ControlsManager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __('Default'),
                'rows' => 5,
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
            'title_tag',
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
            'input_heading',
            [
                'label' => __('Form Fields'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'input_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'xs' => __('Extra Small'),
                    'sm' => __('Small'),
                    'md' => __('Medium'),
                    'lg' => __('Large'),
                    'xl' => __('Extra Large'),
                ],
                'default' => 'sm',
            ]
        );

        $this->addControl(
            'mark_required',
            [
                'label' => __('Required Mark'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_button_content',
            [
                'label' => __('Button'),
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
            ]
        );

        $this->addControl(
            'button_text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Default'),
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
            ]
        );

        $this->addResponsiveControl(
            'button_align',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'end' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'stretch' => [
                        'title' => __('Justified'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'start',
                'prefix_class' => 'elementor%s-button-align-',
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
                        'floppy-disk',
                    ],
                    'fa-regular' => [
                        'floppy-disk',
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
                'condition' => [
                    'selected_icon[value]!' => '',
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
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_customization',
            [
                'label' => __('Product Customization'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'heading_title',
            [
                'label' => __('Title'),
                'type' => ControlsManager::HEADING,
            ]
        );

        $this->addResponsiveControl(
            'title_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
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
                'name' => 'text_stroke',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'title_shadow',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
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

        $this->addResponsiveControl(
            'description_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-text-editor' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'description_align',
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
                    '{{WRAPPER}} .elementor-text-editor' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'description_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-text-editor' => 'color: {{VALUE}};',
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
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .elementor-text-editor',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'description_shadow',
                'selector' => '{{WRAPPER}} .elementor-text-editor',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_form',
            [
                'label' => __('Form'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'heading_style_label',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Label'),
            ]
        );

        $this->addControl(
            'label_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group > label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'label_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'mark_color',
            [
                'label' => __('Mark Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-mark-required .elementor-field-label:after' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'mark_required!' => '',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .elementor-field-group label',
            ]
        );

        $this->addControl(
            'row_gap',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-form-fields-wrapper' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_field_style',
            [
                'label' => __('Field'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'field_typography',
                'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field',
            ]
        );

        $this->addControl(
            'field_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'field_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field-textual' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'field_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field-textual' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'field_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field-textual' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'field_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field-textual' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_button_style',
            [
                'label' => __('Button'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->startControlsTabs('tabs_button_style');

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
                    '{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
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
            'button_hover_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_background_hover_color',
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
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
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

        $this->endControlsSection();
    }

    protected function shouldPrintEmpty()
    {
        return true;
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-heading elementor-widget-text-editor elementor-widget-contact-form';
    }

    protected function render()
    {
        $context = \Context::getContext();
        $vars = &$context->smarty->tpl_vars;
        $product = $vars['product']->value;

        if ($vars['configuration']->value['is_catalog'] || !$product['customizations']['fields']) {
            return;
        }
        $t = $context->getTranslator();
        $settings = $this->getSettings();

        if ('' !== $title = trim($settings['title_text'] ?: $t->trans('Product customization', [], 'Shop.Theme.Catalog'))) {
            $this->addRenderAttribute('title_text', 'class', 'elementor-heading-title');
            $settings['title_display'] && $this->addRenderAttribute('title_text', 'class', 'ce-display-' . $settings['title_display']);
            $settings['title_text'] && $this->addInlineEditingAttributes('title_text', 'none');

            echo "<{$settings['title_tag']} {$this->getRenderAttributeString('title_text')}>$title</{$settings['title_tag']}>";
        }
        if ($description = trim($settings['description_text'] ?: $t->trans("Don't forget to save your customization to be able to add to cart", [], 'Shop.Forms.Help'))) {
            $this->addRenderAttribute('description_text', 'class', 'elementor-text-editor');
            $settings['description_text'] && $this->addInlineEditingAttributes('description_text');

            echo "<div {$this->getRenderAttributeString('description_text')}>$description</div>";
        }
        $mark_class = $settings['mark_required'] ? ' elementor-mark-required' : '';
        $input_classes = 'elementor-field elementor-field-textual elementor-size-' . esc_attr($settings['input_size']);
        $this->addRenderAttribute('button', 'class', 'elementor-button elementor-size-' . $settings['button_size']);
        ?>
        <form class="ce-product-customization" method="post" action="<?php echo esc_attr($product['url']); ?>" enctype="multipart/form-data">
            <div class="elementor-form-fields-wrapper">
            <?php foreach ($product['customizations']['fields'] as $field) { ?>
                <div class="elementor-field-group elementor-column elementor-col-100 elementor-field-type-<?php echo esc_attr($field['type']) . ($field['required'] ? $mark_class : ''); ?>">
                    <label class="elementor-field-label" for="field-<?php echo esc_attr($field['input_name']); ?>"><?php echo $field['label']; ?></label>
                <?php if ('text' === $field['type']) { ?>
                    <textarea name="<?php echo esc_attr($field['input_name']); ?>" id="field-<?php echo esc_attr($field['input_name']); ?>" class="<?php echo $input_classes; ?>" rows="1" maxlength="250"
                        <?php $field['required'] && print 'required'; ?>><?php echo $field['text']; ?></textarea>
                <?php } elseif ('image' === $field['type']) { ?>
                    <label class="<?php echo $input_classes; ?>" style="display: flex; align-items: center; text-align: start;">
                        <input type="file" name="<?php echo esc_attr($field['input_name']); ?>" id="field-<?php echo esc_attr($field['input_name']); ?>" accept="image/png, image/jpeg, image/gif"
                            <?php $field['required'] && print 'required'; ?>>
                    <?php if ($field['is_customized']) { ?>
                        <a href="<?php echo esc_attr(Plugin::$instance->frontend->createActionHash('lightbox', ['type' => 'image', 'url' => $field['image']['large']['url']])); ?>">
                            <img src="<?php echo esc_attr($field['image']['small']['url']); ?>" alt="" loading="lazy">
                        </a>
                        <a href="<?php echo esc_attr($field['remove_image_url']); ?>" rel="nofollow"><?php echo $t->trans('Remove Image', [], 'Shop.Theme.Actions'); ?></a>
                    <?php } ?>
                    </label>
                <?php } ?>
                </div>
            <?php } ?>
                <div class="elementor-field-group elementor-column elementor-col-100 elementor-field-type-submit">
                    <button type="submit" name="submitCustomizedData" <?php $this->printRenderAttributeString('button'); ?>>
                        <span class="elementor-button-content-wrapper">
                        <?php if ($settings['selected_icon']['value']) { ?>
                            <span class="elementor-align-icon-<?php echo esc_attr($this->getSettings('icon_align')); ?>"><?php IconsManager::renderIcon($settings['selected_icon']); ?></span>
                        <?php } ?>
                            <span class="elementor-button-text"><?php echo $settings['button_text'] ?: $t->trans('Save Customization', [], 'Shop.Theme.Actions'); ?></span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
        <?php
    }
}
