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

use CE\ModulesXCatalogXDocumentsXProduct as ProductDocument;

class ModulesXCatalogXDocumentsXProductQuickView extends ProductDocument
{
    public function getName()
    {
        return 'product-quick-view';
    }

    public static function getTitle()
    {
        return __('Quick View');
    }

    public function getCssWrapperSelector()
    {
        return '#ce-product-quick-view';
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['category'] = 'quick view';
        $config['autoImportSettings'] = true;

        return $config;
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'popup_layout',
            [
                'label' => __('Layout'),
                'tab' => ControlsManager::TAB_SETTINGS,
            ]
        );

        $this->addResponsiveControl(
            'width',
            [
                'label' => __('Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1600,
                    ],
                    'vw' => [
                        'min' => 10,
                    ],
                ],
                'size_units' => ['px', 'vw'],
                'default' => [
                    'size' => 1200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .dialog-widget-content' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'height_type',
            [
                'label' => __('Height'),
                'type' => ControlsManager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __('Fit To Content'),
                    'fit_to_screen' => __('Fit To Screen'),
                    'custom' => __('Custom'),
                ],
                'selectors_dictionary' => [
                    'fit_to_screen' => '100vh',
                    'custom' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dialog-widget-content' => 'height: {{VALUE}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'height',
            [
                'label' => __('Custom Height'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1600,
                    ],
                    'vh' => [
                        'min' => 10,
                    ],
                ],
                'size_units' => ['px', 'vh'],
                'condition' => [
                    'height_type' => 'custom',
                ],
                'default' => [
                    'size' => 700,
                ],
                'selectors' => [
                    '{{WRAPPER}} .dialog-widget-content' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'content_position',
            [
                'label' => __('Content Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => __('Top'),
                    'center' => __('Center'),
                    'bottom' => __('Bottom'),
                ],
                'condition' => [
                    'height_type!' => 'auto',
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'position_heading',
            [
                'label' => __('Position'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'horizontal_position',
            [
                'label' => __('Horizontal'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'toggle' => false,
                'default' => 'center',
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
                'selectors' => [
                    '{{WRAPPER}}' => 'justify-content: {{VALUE}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'vertical_position',
            [
                'label' => __('Vertical'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'toggle' => false,
                'default' => 'center',
                'options' => [
                    'top' => [
                        'title' => __('Top'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __('Bottom'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'align-items: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'close_button',
            [
                'label' => __('Close Button'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .dialog-close-button' => 'display: block',
                ],
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'entrance_animation',
            [
                'label' => __('Entrance Animation'),
                'type' => ControlsManager::ANIMATION,
                'label_block' => true,
                'frontend_available' => true,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'entrance_animation_duration',
            [
                'label' => __('Animation Duration') . ' (sec)',
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .dialog-message' => 'animation-duration: {{SIZE}}s',
                ],
                'condition' => [
                    'entrance_animation!' => 'none',
                ],
            ]
        );

        $this->endControlsSection();

        parent::_registerControls();

        $this->startControlsSection(
            'section_page_style',
            [
                'label' => __('Popup'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background',
                'selector' => '{{WRAPPER}} .dialog-message',
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .dialog-message',
            ]
        );

        $this->addResponsiveControl(
            'border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dialog-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .dialog-message',
                'fields_options' => [
                    'box_shadow_type' => [
                        'default' => 'yes',
                    ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 2,
                            'vertical' => 8,
                            'blur' => 23,
                            'spread' => 3,
                            'color' => 'rgba(0,0,0,0.2)',
                        ],
                    ],
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_overlay',
            [
                'label' => __('Overlay'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'overlay_background',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => 'rgba(0,0,0,.8)',
                    ],
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_close_button',
            [
                'label' => __('Close Button'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'close_button!' => '',
                ],
            ]
        );

        $this->addControl(
            'close_button_position',
            [
                'label' => __('Position'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'inside' => __('Inside'),
                    'outside' => __('Outside'),
                ],
                'default' => 'outside',
                'frontend_available' => true,
            ]
        );

        $this->addResponsiveControl(
            'close_button_vertical',
            [
                'label' => __('Vertical Position'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'step' => 0.1,
                    ],
                    'px' => [
                        'max' => 500,
                        'min' => -500,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dialog-close-button' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'close_button_horizontal',
            [
                'label' => __('Horizontal Position'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'step' => 0.1,
                    ],
                    'px' => [
                        'max' => 500,
                        'min' => -500,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dialog-close-button' => is_rtl() ? 'left: {{SIZE}}{{UNIT}}' : 'right: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->startControlsTabs('close_button_style_tabs');

        $this->startControlsTab(
            'tab_x_button_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'close_button_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dialog-close-button i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'close_button_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dialog-close-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_x_button_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'close_button_hover_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dialog-close-button:hover i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'close_button_hover_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dialog-close-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addResponsiveControl(
            'icon_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .dialog-close-button' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_advanced',
            [
                'label' => __('Advanced'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->addResponsiveControl(
            'margin',
            [
                'label' => __('Margin'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dialog-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; ' .
                        'max-height: calc(100vh - {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}})',
                ],
            ]
        );

        $this->addResponsiveControl(
            'padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dialog-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'z_index',
            [
                'label' => __('Z-Index'),
                'type' => ControlsManager::NUMBER,
                'min' => 0,
                'selectors' => [
                    '{{WRAPPER}}' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();

        Plugin::$instance->controls_manager->addCustomCssControls($this);
    }

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        if (\CreativeElements::getPreviewUId(false) == (string) $this->getMainId()) {
            add_action('wp_footer', [__CLASS__, 'printPreviewFooter']);

            add_filter('template_include', function () {
                return _CE_TEMPLATES_ . 'front/theme/layouts/layout-canvas.tpl';
            }, 12);
        }
    }

    public static function printPreviewFooter()
    {
        ?>
        <script>
        // Init Lightbox
        $('html').attr({
            id: 'ce-product-quick-view',
        }).addClass('dialog-widget dialog-lightbox-widget dialog-type-lightbox elementor-lightbox')
        $('body').addClass('dialog-widget-content dialog-lightbox-widget-content');
        $('main').addClass('dialog-message dialog-lightbox-message')
        $('<div class="dialog-close-button dialog-lightbox-close-button"><i class="ceicon-close">')
            .appendTo('.dialog-message');

        // Init Form
        var $form = $('<form>').attr({
            action: prestashop.urls.pages.product,
            method: 'post',
            id: 'add-to-cart-or-refresh',
            style: 'display: none',
        }).prependTo('main');
        $('<input>').attr({
            type: 'hidden',
            name: 'token',
            value: prestashop.static_token,
        }).appendTo($form);
        $('<input>').attr({
            type: 'hidden',
            id: 'product_page_product_id',
            name: 'id_product',
            value: ceFrontend.config.post.id.match(/(\d+)\d{6}/)[1],
        }).appendTo($form);
        $('<input>').attr({
            type: 'hidden',
            id: 'quantity_wanted',
            name: 'qty',
            value: 1,
        }).appendTo($form);
        $('<input>').attr({
            type: 'submit',
            'class': 'ce-add-to-cart',
            'data-button-action': 'add-to-cart',
        }).appendTo($form);

        // Entrance Animation Preview
        if (top.elementor && top.elementor.settings) {
            var previewModalSettings,
                pageModel = top.elementor.settings.page.model
                prev = {
                    animation: {
                        desktop: null,
                        tablet: null,
                        mobile: null
                    },
                    duration: null,
                    closeButtonPosition: null
                };
            pageModel.on('change', previewModalSettings = function () {
                var device = ceFrontend.getCurrentDeviceMode(),
                    control = device === 'desktop' ? 'entrance_animation' : 'entrance_animation_' + device;
                    animation = pageModel.get(control),
                    duration = pageModel.get('entrance_animation_duration'),
                    closeButtonPosition = pageModel.get('close_button_position');

                if (animation && (animation !== prev.animation[device] || duration !== prev.duration)) {
                    $('.dialog-message').removeClass([
                        prev.animation.desktop,
                        prev.animation.tablet,
                        prev.animation.mobile,
                        'animated'
                    ].join(' '));

                    setTimeout(function () {
                        $('.dialog-message').addClass(animation + ' animated');
                    });

                    prev.animation[device] = animation;
                    prev.duration = duration;
                }
                if (closeButtonPosition !== prev.closeButtonPosition) {
                    $('.dialog-close-button').prependTo(
                        'outside' === closeButtonPosition ? 'body' : '.dialog-message'
                    );
                }
            });
            top.elementor.on('preview:loaded', previewModalSettings);
        }
        </script>
        <?php
    }
}
