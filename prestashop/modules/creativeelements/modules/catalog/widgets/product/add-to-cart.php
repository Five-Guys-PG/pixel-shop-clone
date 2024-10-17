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

class ModulesXCatalogXWidgetsXProductXAddToCart extends WidgetButton
{
    const REMOTE_RENDER = true;

    protected $inlineEditing = true;

    public function getName()
    {
        return 'product-add-to-cart';
    }

    public function getTitle()
    {
        return __('Add to Cart');
    }

    public function getIcon()
    {
        return 'eicon-product-add-to-cart';
    }

    public function getCategories()
    {
        return ['product-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'product', 'button', 'add to cart', 'buy now'];
    }

    protected function isDynamicContent()
    {
        return true;
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->updateControl(
            'button_type',
            [
                'label' => __('Action'),
                'options' => [
                    'add-to-cart' => __('Add to Cart'),
                    'buy-now' => __('Buy Now'),
                    'full-details' => __('View Full Details'),
                ],
                'default' => 'add-to-cart',
                'render_type' => 'template',
                'prefix_class' => null,
                'style_transfer' => false,
            ]
        );

        $this->addControl(
            'type',
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
            ],
            [
                'position' => [
                    'of' => 'button_type',
                ],
            ]
        );

        $this->updateControl(
            'link',
            [
                'type' => ControlsManager::HIDDEN,
                'default' => '',
            ]
        );

        $this->updateControl(
            'text',
            [
                'default' => '',
                'placeholder' => __('Default'),
            ]
        );

        $this->updateControl(
            'selected_icon',
            [
                'default' => [
                    'value' => 'ceicon-basket-solid',
                    'library' => 'ce-icons',
                ],
                'recommended' => [
                    'ce-icons' => [
                        'cart-light',
                        'cart-medium',
                        'cart-solid',
                        'trolley-light',
                        'trolley-medium',
                        'trolley-solid',
                        'trolley-bold',
                        'basket-light',
                        'basket-medium',
                        'basket-solid',
                        'bag-light',
                        'bag-medium',
                        'bag-solid',
                        'bag-rounded-o',
                        'bag-rounded',
                        'bag-trapeze-o',
                        'bag-trapeze',
                    ],
                    'fa-solid' => [
                        'bag-shopping',
                        'basket-shopping',
                        'cart-shopping',
                        'cart-plus',
                    ],
                ],
            ]
        );

        $this->removeControl('button_css_id');

        $this->updateControl('background_color', ['scheme' => '']);

        $this->updateControl('typography_font_family', ['scheme' => '']);
        $this->updateControl('typography_font_weight', ['scheme' => '']);

        $this->startControlsSection(
            'section_disabled_style',
            [
                'label' => __('Disabled'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'button_type!' => 'full-details',
                ],
            ]
        );

        $this->addControl(
            'disabled_cursor',
            [
                'label' => __('Cursor'),
                'label_block' => false,
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'default' => [
                        'icon' => 'fas fa-arrow-pointer',
                    ],
                    'not-allowed' => [
                        'icon' => 'eicon-ban',
                    ],
                ],
                'toggle' => false,
                'default' => 'not-allowed',
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button' => 'cursor: pointer;',
                    '{{WRAPPER}} a.elementor-button:not([href])' => 'cursor: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'disabled_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button:not([href]):not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'disabled_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'default' => 'rgba(129,138,145,0.35)',
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button:not([href])' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'disabled_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button:not([href])' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-button';
    }

    protected function addInlineEditingAttributes($key, $toolbar = 'basic')
    {
        $this->inlineEditing && parent::addInlineEditingAttributes($key, $toolbar);
    }

    protected function render()
    {
        $vars = &$GLOBALS['smarty']->tpl_vars;
        $product = $vars['product']->value;
        $button_type = $this->getSettings('button_type');

        if ('full-details' === $button_type) {
            $this->addRenderAttribute('button', 'href', $product['url']);
        } elseif (!$product['available_for_order'] || $vars['configuration']->value['is_catalog']) {
            return;
        } elseif ($product['add_to_cart_url']) {
            $this->addRenderAttribute('button', 'href', '#ce-action=' . \Tools::toCamelCase($button_type));
        }
        // BC: Clear link
        $this->setSettings('link', []);

        if (!$this->getSettings('text')) {
            $this->inlineEditing = false;
            $this->setSettings(
                'text',
                'add-to-cart' === $button_type ? __('Add to Cart') : (
                    'buy-now' === $button_type ? __('Buy Now') : __('View Full Details')
                )
            );
        }
        parent::render();
    }

    public function renderPlainContent()
    {
    }

    protected function contentTemplate()
    {
    }
}
