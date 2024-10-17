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

use CE\ModulesXCatalogXWidgetsXProductXAddToCart as ProductAddToCart;

class ModulesXCatalogXWidgetsXProductXMiniatureXAddToCart extends ProductAddToCart
{
    public function getName()
    {
        return 'product-miniature-add-to-cart';
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->updateControl('button_type', [
            'options' => [
                'add-to-cart' => __('Add to Cart'),
                'buy-now' => __('Buy Now'),
            ],
        ]);

        $this->updateControl('selected_icon', [
            'exclude_inline_options' => ['svg'],
        ]);

        $this->startControlsSection(
            'section_combinations',
            [
                'label' => __('Product with Combinations'),
            ]
        );

        $this->addControl(
            'combinations_action',
            [
                'label' => __('Action'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'quickview' => __('Quick View'),
                    'full-details' => __('View Full Details'),
                ],
            ]
        );

        $this->addControl(
            'combinations_text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'placeholder' => 'Default',
                'condition' => [
                    'combinations_action!' => '',
                ],
            ]
        );

        $this->addControl(
            'selected_combinations_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['svg'],
                'fa4compatibility' => 'combinations_icon',
                'recommended' => [
                    'fa-solid' => [
                        'sliders',
                        'eye',
                    ],
                    'fa-regular' => [
                        'eye',
                    ],
                ],
                'default' => [
                    'value' => 'fas fa-eye',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'combinations_action!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_combinations_style',
            [
                'label' => __('Product with Combinations'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'combinations_action!' => '',
                ],
            ]
        );

        $this->startControlsTabs('tabs_combinations_style');

        $this->startControlsTab(
            'tab_combinations_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'combinations_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button-combinations:not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'combinations_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button-combinations' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'combinations_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button-combinations' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_combinations_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'combinations_color_hover',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button-combinations:not(#e):hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'combinations_background_color_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button-combinations:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'combinations_border_color_hover',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button-combinations:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();
    }

    protected function render()
    {
        $vars = &$GLOBALS['smarty']->tpl_vars;
        $product = $vars['product']->value;

        if (!$product['available_for_order'] || $vars['configuration']->value['is_catalog']) {
            return;
        }
        $settings = $this->getSettings();
        $action = \Tools::toCamelCase($settings['button_type']);

        $this->addRenderAttribute('button', 'href', "#ce-action=$action");

        if ($settings['combinations_action'] && $product['attributes'] && $product['quantity_all_versions']) {
            $this->inlineEditing = false;
            $this->addRenderAttribute('button', 'class', 'elementor-button-combinations');

            $href = 'quickview' === $settings['combinations_action'] ? '#ce-action=quickview' : $product['url'];
            $this->setRenderAttribute('button', 'href', $href);

            $combinations_icon = isset($settings['combinations_icon']) && !isset($settings['__fa4_migrated']['selected_combinations_icon'])
                ? $settings['combinations_icon']
                : $settings['selected_combinations_icon']['value'];
            $this->setRenderAttribute('icon', 'class', $combinations_icon);

            $this->setSettings('selected_icon', $settings['selected_combinations_icon']);
            $this->setSettings(
                'text',
                $settings['combinations_text'] ?: __('quickview' === $settings['combinations_action'] ? 'Quick View' : 'View Full Details')
            );
        } else {
            $icon = isset($settings['icon']) && !isset($settings['__fa4_migrated']['selected_icon'])
                ? $settings['icon']
                : $settings['selected_icon']['value'];
            $this->setRenderAttribute('icon', 'class', $icon);

            if (!$settings['text']) {
                $this->inlineEditing = false;

                $this->setSettings(
                    'text',
                    'add-to-cart' === $settings['button_type'] ? __('Add to Cart') : __('Buy Now')
                );
            }
        }
        WidgetButton::render();
    }

    protected function renderSmarty()
    {
        $settings = $this->getSettings();
        $action = \Tools::toCamelCase($settings['button_type']);
        $icon = isset($settings['icon']) && !isset($settings['__fa4_migrated']['selected_icon'])
            ? $settings['icon']
            : $settings['selected_icon']['value'];
        echo '{if $product.available_for_order && !$configuration.is_catalog}';

        if ($settings['combinations_action']) {
            $combinations_icon = isset($settings['combinations_icon']) && !isset($settings['__fa4_migrated']['selected_combinations_icon'])
                ? $settings['combinations_icon']
                : $settings['selected_combinations_icon']['value'];

            $this->addRenderAttribute('button', [
                'class' => '{if $product.attributes and $product.quantity_all_versions}elementor-button-combinations{/if}',
                '{if $product.attributes && $product.quantity_all_versions}' .
                    'href="' . ('quickview' === $settings['combinations_action'] ? '#ce-action=quickview' : '{$product.url}') . '"' .
                '{elseif $product.add_to_cart_url}' .
                    'href="#ce-action=' . $action . '{if $product.minimal_quantity > 1}&amp;qty={$product.minimal_quantity}{/if}"' .
                '{/if}' => null,
            ]);
            $this->addRenderAttribute('icon', 'class', [
                '{if $product.attributes}' . $combinations_icon . '{else}' . $icon . '{/if}',
            ]);
            $this->setSettings(
                'text',
                '{if $product.attributes && $product.quantity_all_versions}' .
                    ($settings['combinations_text'] ?: __('quickview' === $settings['combinations_action'] ? 'Quick View' : 'View Full Details')) .
                '{else}' .
                    ($settings['text'] ?: __('add-to-cart' === $settings['button_type'] ? 'Add to Cart' : 'Buy Now')) .
                '{/if}'
            );
        } else {
            $this->addRenderAttribute('button', [
                '{if $product.add_to_cart_url}href="#ce-action=' . $action . '{if $product.minimal_quantity > 1}&amp;qty={$product.minimal_quantity}{/if}"{/if}' => null,
            ]);
            $this->addRenderAttribute('icon', [
                'class' => $icon,
            ]);
            '' === $settings['text'] && $this->setSettings(
                'text',
                'add-to-cart' === $settings['button_type'] ? __('Add to Cart') : __('Buy Now')
            );
        }
        WidgetButton::render();
        echo '{/if}';
    }

    protected function renderIcon(array &$settings)
    {
        ?>
        <span class="elementor-button-icon elementor-align-icon-<?php echo esc_attr($this->getSettings('icon_align')); ?>">
            <i <?php $this->printRenderAttributeString('icon'); ?> aria-hidden="true"></i>
        </span>
        <?php
    }
}
