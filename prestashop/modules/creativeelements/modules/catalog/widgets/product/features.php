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

class ModulesXCatalogXWidgetsXProductXFeatures extends WidgetHeading
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-features';
    }

    public function getTitle()
    {
        return __('Product Features');
    }

    public function getIcon()
    {
        return 'eicon-product-info';
    }

    public function getCategories()
    {
        return ['product-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'product', 'features', 'information'];
    }

    protected function isDynamicContent()
    {
        return true;
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->updateControl('section_title', [
            'label' => __('Product Features'),
        ]);

        $this->updateControl('title', [
            'type' => ControlsManager::TEXT,
            'label_block' => true,
            'default' => '',
        ]);

        $this->removeControl('link');

        $this->updateControl('header_size', [
            'default' => 'h3',
        ]);

        is_admin() && $this->addControl(
            'configure',
            [
                'label' => __('Product Features'),
                'type' => ControlsManager::BUTTON,
                'text' => '<i class="eicon-external-link-square"></i>' . __('Configure'),
                'link' => [
                    'url' => \Context::getContext()->link->getAdminLink('AdminFeatures'),
                    'is_external' => true,
                ],
                'separator' => 'before',
            ],
            [
                'position' => [
                    'of' => 'align',
                ],
            ]
        );

        $this->removeControl('blend_mode');

        $this->updateControl('section_title_style', [
            'condition' => ['title!' => ''],
        ], [
            'recursive' => true,
        ]);

        $this->updateControl('title_color', ['scheme' => '']);

        $this->updateControl('typography_font_family', ['scheme' => '']);
        $this->updateControl('typography_font_weight', ['scheme' => '']);

        $this->removeControl('blend_mode');

        $this->addResponsiveControl(
            'spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'em' => [
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ],
            [
                'position' => [
                    'type' => 'section',
                    'of' => 'section_title_style',
                ],
            ]
        );

        $this->startControlsSection(
            'section_features_style',
            [
                'label' => __('Product Features'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} table.ce-product-features',
            ]
        );

        $this->startControlsTabs('tabs_style_rows');

        $this->startControlsTab(
            'tab_row_odd',
            [
                'label' => __('Odd'),
            ]
        );

        $this->addControl(
            'odd_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-features tr:nth-child(odd)' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_row_even',
            [
                'label' => __('Even'),
            ]
        );

        $this->addControl(
            'even_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-features tr:nth-child(even)' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->startControlsTabs('tabs_style_columns');

        $this->startControlsTab(
            'tab_column_label',
            [
                'label' => __('Label'),
            ]
        );

        $this->addResponsiveControl(
            'label_align',
            [
                'label' => __('Alignment'),
                'label_block' => false,
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
                    '{{WRAPPER}} .ce-product-features__label' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .ce-product-features__label',
            ]
        );

        $this->addControl(
            'label_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-features__label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'label_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-features__label' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'label_width',
            [
                'label' => __('Width'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 33,
                    'unit' => '%',
                ],
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-features__label' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'label_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-features__label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'label_border',
                'selector' => '{{WRAPPER}} .ce-product-features__label',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_column_value',
            [
                'label' => __('Value'),
            ]
        );

        $this->addResponsiveControl(
            'value_align',
            [
                'label' => __('Alignment'),
                'label_block' => false,
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
                    '{{WRAPPER}} .ce-product-features__value' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'value_typography',
                'selector' => '{{WRAPPER}} .ce-product-features__value',
            ]
        );

        $this->addControl(
            'value_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-features__value' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'value_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-features__value' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'value_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-features__value' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'value_border',
                'selector' => '{{WRAPPER}} .ce-product-features__value',
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-heading';
    }

    protected function render()
    {
        $product = $GLOBALS['smarty']->tpl_vars['product']->value;

        if (!$product['grouped_features']) {
            return;
        }
        $settings = $this->getSettingsForDisplay();

        if ('' !== $settings['title']) {
            $this->addRenderAttribute('title', 'class', 'elementor-heading-title');
            $settings['size'] && $this->addRenderAttribute('title', 'class', 'ce-display-' . $settings['size']);

            echo "<{$settings['header_size']} {$this->getRenderAttributeString('title')}>{$settings['title']}</{$settings['header_size']}>";
        } ?>
        <table class="ce-product-features">
        <?php foreach ($product['grouped_features'] as $feature) { ?>
            <tr class="ce-product-features__row">
                <th class="ce-product-features__label"><?php echo esc_html($feature['name']); ?></th>
                <td class="ce-product-features__value"><?php echo esc_html($feature['value']); ?></td>
            </tr>
        <?php } ?>
        </table>
        <?php
    }

    public function renderSmarty()
    {
        $settings = $this->getSettingsForDisplay();

        echo '{if $product.grouped_features}';

        if ('' !== $settings['title']) {
            $this->addRenderAttribute('title', 'class', 'elementor-heading-title');
            $settings['size'] && $this->addRenderAttribute('title', 'class', 'ce-display-' . $settings['size']);

            echo "<{$settings['header_size']} {$this->getRenderAttributeString('title')}>{$settings['title']}</{$settings['header_size']}>";
        } ?>
        <table class="ce-product-features">
        {foreach $product['grouped_features'] as $feature}
            <tr class="ce-product-features__row">
                <th class="ce-product-features__label">{$feature.name}</th>
                <td class="ce-product-features__value">{$feature.value}</td>
            </tr>
        {/foreach}
        </table>
        <?php
        echo '{/if}';
    }

    public function renderPlainContent()
    {
    }

    protected function contentTemplate()
    {
    }
}
