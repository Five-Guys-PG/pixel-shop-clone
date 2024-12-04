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

class ModulesXCatalogXWidgetsXProductXRating extends WidgetStarRating
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-rating';
    }

    public function getTitle()
    {
        return __('Product Rating');
    }

    public function getIcon()
    {
        return 'eicon-product-rating';
    }

    public function getCategories()
    {
        return ['product-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'rating', 'review', 'comments', 'stars', 'product'];
    }

    protected function isDynamicContent()
    {
        return true;
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->updateControl('rating_scale', ['type' => ControlsManager::HIDDEN]);

        $this->updateControl('rating', ['type' => ControlsManager::HIDDEN]);

        $this->updateControl('star_style', ['separator' => '']);

        $this->updateControl('title', ['separator' => '']);

        $this->updateControl('title_color', ['scheme' => '']);

        $this->updateControl('title_typography_font_family', ['scheme' => '']);
        $this->updateControl('title_typography_font_weight', ['scheme' => '']);

        $this->startInjection([
            'of' => 'title',
        ]);

        $this->addControl(
            'hide_empty',
            [
                'label' => __('Hide Empty'),
                'type' => ControlsManager::SWITCHER,
            ]
        );

        $this->addControl(
            'show_average_grade',
            [
                'label' => __('Average Grade'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
            ]
        );

        $this->addControl(
            'show_comments_number',
            [
                'label' => __('Comments Number'),
                'classes' => 'elementor-control-type-heading',
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'selected_comments_number_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'comments_number_icon',
                'recommended' => [
                    'fa-solid' => [
                        'comment',
                        'comments',
                        'comment-dots',
                        'message',
                    ],
                    'fa-regular' => [
                        'comment',
                        'comments',
                        'comment-dots',
                        'message',
                    ],
                ],
                'default' => [
                    'value' => 'fas fa-comment-dots',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_comments_number!' => '',
                ],
            ]
        );

        $this->addControl(
            'comments_number_before',
            [
                'label' => __('Before'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Default'),
                'condition' => [
                    'show_comments_number!' => '',
                ],
            ]
        );

        $this->addControl(
            'comments_number_after',
            [
                'label' => __('After'),
                'type' => ControlsManager::TEXT,
                'condition' => [
                    'show_comments_number!' => '',
                ],
            ]
        );

        if ($this->getName() === 'product-rating') {
            $this->addControl(
                'show_post_comment',
                [
                    'label' => __('Post Comment'),
                    'classes' => 'elementor-control-type-heading',
                    'type' => ControlsManager::SWITCHER,
                    'label_on' => __('Show'),
                    'label_off' => __('Hide'),
                    'separator' => 'before',
                ]
            );

            $this->addControl(
                'selected_post_comment_icon',
                [
                    'label' => __('Icon'),
                    'label_block' => false,
                    'type' => ControlsManager::ICONS,
                    'skin' => 'inline',
                    'fa4compatibility' => 'post_comment_icon',
                    'recommended' => [
                        'fa-solid' => [
                            'marker',
                            'pencil',
                            'pen',
                            'square-pen',
                            'pen-clip',
                            'pen-nib',
                            'pen-fancy',
                            'pen-to-square',
                        ],
                        'fa-regular' => [
                            'pen-to-square',
                        ],
                    ],
                    'default' => [
                        'value' => 'fas fa-pencil',
                        'library' => 'fa-solid',
                    ],
                    'condition' => [
                        'show_post_comment!' => '',
                    ],
                ]
            );

            $this->addControl(
                'post_comment',
                [
                    'label' => __('Text'),
                    'type' => ControlsManager::TEXT,
                    'placeholder' => __('Default'),
                    'condition' => [
                        'show_post_comment!' => '',
                    ],
                ]
            );
        }

        $this->endInjection();

        $this->updateControl(
            'align',
            [
                'label_block' => false,
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
                'separator' => 'before',
            ]
        );

        $this->startControlsSection(
            'section_average_grade_style',
            [
                'label' => __('Average Grade'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'show_average_grade!' => '',
                ],
            ]
        );

        $this->addControl(
            'average_grade_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-rating__average-grade' => is_rtl() ? 'margin-right: {{SIZE}}{{UNIT}};' : 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'average_grade_typography',
                'selector' => '{{WRAPPER}} .ce-product-rating__average-grade',
            ]
        );

        $this->addControl(
            'average_grade_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-rating__average-grade' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_comments_number_style',
            [
                'label' => __('Comments Number'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'show_comments_number!' => '',
                ],
            ]
        );

        $this->addControl(
            'comments_number_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item' => is_rtl() ? 'margin-right: {{SIZE}}{{UNIT}};' : 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'comments_number_typography',
                'selector' => '{{WRAPPER}} .elementor-icon-list-item',
            ]
        );

        $this->addControl(
            'comments_number_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-icon-list-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'comments_number_color_hover',
            [
                'label' => __('Hover'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-icon-list-item:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'comments_number_indent',
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
                'condition' => [
                    'comments_number_icon!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        if ($this->getName() === 'product-rating') {
            $this->startControlsSection(
                'section_post_comment_style',
                [
                    'label' => __('Post Comment'),
                    'tab' => ControlsManager::TAB_STYLE,
                    'condition' => [
                        'show_post_comment!' => '',
                    ],
                ]
            );

            $this->addControl(
                'post_comment_layout',
                [
                    'label' => __('Layout'),
                    'type' => ControlsManager::SELECT,
                    'default' => 'inline',
                    'options' => [
                        'inline' => __('Inline'),
                        'stacked' => __('Stacked'),
                    ],
                    'prefix_class' => 'ce-product-rating--layout-',
                ]
            );

            $this->addControl(
                'post_comment_spacing',
                [
                    'label' => __('Spacing'),
                    'type' => ControlsManager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'default' => [
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.ce-product-rating--layout-inline .elementor-button' => is_rtl() ? 'margin-right: {{SIZE}}{{UNIT}};' : 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}.ce-product-rating--layout-stacked .elementor-button' => 'margin-top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->addGroupControl(
                GroupControlTypography::getType(),
                [
                    'name' => 'post_comment_typography',
                    'selector' => '{{WRAPPER}} a.elementor-button',
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
                'post_comment_color',
                [
                    'label' => __('Text Color'),
                    'type' => ControlsManager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button:not(#e)' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->addControl(
                'post_comment_bg_color',
                [
                    'label' => __('Background Color'),
                    'type' => ControlsManager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button' => 'background-color: {{VALUE}};',
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
                'post_comment_color_hover',
                [
                    'label' => __('Text Color'),
                    'type' => ControlsManager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button:not(#e):hover, {{WRAPPER}} a.elementor-button:not(#e):focus' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->addControl(
                'post_comment_bg_color_hover',
                [
                    'label' => __('Background Color'),
                    'type' => ControlsManager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} a.elementor-button:focus' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->addControl(
                'post_comment_border_color_hover',
                [
                    'label' => __('Border Color'),
                    'type' => ControlsManager::COLOR,
                    'condition' => [
                        'border_border!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} a.elementor-button:focus' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->addControl(
                'post_comment_hover_animation',
                [
                    'label' => __('Hover Animation'),
                    'type' => ControlsManager::HOVER_ANIMATION,
                ]
            );

            $this->endControlsTab();

            $this->endControlsTabs();

            $this->addGroupControl(
                GroupControlBorder::getType(),
                [
                    'name' => 'post_comment_border',
                    'selector' => '{{WRAPPER}} .elementor-button',
                    'separator' => 'before',
                ]
            );

            $this->addControl(
                'post_comment_border_radius',
                [
                    'label' => __('Border Radius'),
                    'type' => ControlsManager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->addGroupControl(
                GroupControlBoxShadow::getType(),
                [
                    'name' => 'post_comment_box_shadow',
                    'selector' => '{{WRAPPER}} .elementor-button',
                ]
            );

            $this->addResponsiveControl(
                'post_comment_padding',
                [
                    'label' => __('Padding'),
                    'type' => ControlsManager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->endControlsSection();
        }
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-star-rating';
    }

    protected function render()
    {
        $productcomments = \Module::getInstanceByName('productcomments');

        if (empty($productcomments->active) || $productcomments->author !== 'PrestaShop') {
            return printf(
                '<div class="alert alert-warning" role="alert">%s</div>',
                __('Please install and enable the official "productcomments" module!')
            );
        }
        $context = \Context::getContext();
        $product = $context->smarty->tpl_vars['product']->value;
        $vars = $productcomments->getWidgetVariables('displayCE', [
            'id_product' => (int) $product['id_product'],
        ]);
        $nb_comments = (int) $vars['nb_comments'];

        if (!$nb_comments && $this->getSettings('hide_empty')) {
            return;
        }
        $average_grade = \Tools::ps_round($vars['average_grade'], 1);
        $has_link = $this->getName() === 'product-rating' && \Tools::getValue('action') !== 'quickview';
        $this->setSettings('rating', $average_grade);
        $settings = $this->getSettingsForDisplay();
        $t = $context->getTranslator(); ?>
        <div class="ce-product-rating">
            <?php parent::render(); ?>
        <?php if ($average_grade && $settings['show_average_grade']) { ?>
            <span class="ce-product-rating__average-grade"><?php echo $average_grade; ?></span>
        <?php } ?>
        <?php if ($nb_comments && $settings['show_comments_number']) { ?>
            <a class="elementor-icon-list-item"<?php $has_link && print ' href="#product-comments-list-header"'; ?>>
            <?php if ($cn_icon = IconsManager::getBcIcon($settings, 'comments_number_icon', ['aria-hidden' => 'true'])) { ?>
                <span class="elementor-icon-list-icon"><?php echo $cn_icon; ?></span>
            <?php } ?>
                <span class="elementor-icon-list-text">
                    <?php echo $settings['comments_number_before'] ?: "{$t->trans('Read user reviews', [], 'Modules.Productcomments.Shop')}: "; ?>
                    <?php echo $nb_comments . $settings['comments_number_after']; ?>
                </span>
            </a>
        <?php } ?>
        </div>
        <?php if ($vars['post_allowed'] && $settings['show_post_comment']) { ?>
            <a class="elementor-button elementor-button--post-comment elementor-size-sm" href="#product-comments-list-header">
            <?php if ($pc_icon = IconsManager::getBcIcon($settings, 'post_comment_icon', ['aria-hidden' => 'true'])) { ?>
                <span class="elementor-button-icon elementor-align-icon-left"><?php echo $pc_icon; ?></span>
            <?php } ?>
                <span class="elementor-button-text">
                    <?php echo $settings['post_comment'] ?: $t->trans('Write your review', [], 'Modules.Productcomments.Shop'); ?>
                </span>
            </a>
        <?php } ?>
        <?php
    }

    public function renderPlainContent()
    {
    }

    protected function contentTemplate()
    {
    }
}
