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

trait CarouselTrait
{
    public function getScriptDepends()
    {
        return ['swiper'];
    }

    protected function registerCarouselSection(array $args = [])
    {
        $default_slides_count = isset($args['default_slides_count']) ? $args['default_slides_count'] : 3;
        $variable_width = isset($args['variable_width']) ? ['variable_width' => ''] : [];

        $this->startControlsSection(
            'section_additional_options',
            [
                'label' => __('Carousel'),
            ]
        );

        $this->addControl(
            'default_slides_count',
            [
                'type' => ControlsManager::HIDDEN,
                'default' => (int) $default_slides_count,
                'frontend_available' => true,
            ]
        );

        $options = range(1, 10);
        $options = array_combine($options, $options);

        $this->addResponsiveControl(
            'slides_to_show',
            [
                'label' => __('Slides to Show'),
                'type' => ControlsManager::SELECT2,
                'select2options' => [
                    'tags' => true,
                    'placeholder' => __('Default'),
                ],
                'options' => $options,
                'selectors' => [
                    '{{WRAPPER}} .swiper:not(.swiper-initialized) .swiper-wrapper' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
                'render_type' => 'template',
                'classes' => 'select2-numeric',
                'frontend_available' => true,
                'condition' => $variable_width,
            ]
        );

        $this->addResponsiveControl(
            'slides_to_scroll',
            [
                'label' => __('Slides to Scroll'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                ] + $options,
                'description' => __('Set how many slides are scrolled per swipe.'),
                'frontend_available' => true,
                'condition' => [
                    'slides_to_show!' => '1',
                    'center_mode' => '',
                ] + $variable_width,
                'device_args' => [
                    ControlsStack::RESPONSIVE_TABLET => [
                        'condition' => [
                            'slides_to_show_tablet!' => '1',
                            'center_mode_tablet' => '',
                        ] + $variable_width,
                    ],
                    ControlsStack::RESPONSIVE_MOBILE => [
                        'condition' => [
                            'slides_to_show_mobile!' => ['', '1'],
                            'center_mode_mobile' => '',
                        ] + $variable_width,
                    ],
                ],
            ]
        );

        $this->addResponsiveControl(
            'center_mode',
            [
                'label' => __('Center Mode'),
                'type' => ControlsManager::SWITCHER,
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'navigation',
            [
                'label' => __('Navigation'),
                'type' => ControlsManager::SELECT,
                'default' => 'both',
                'options' => [
                    'both' => __('Arrows and Dots'),
                    'arrows' => __('Arrows'),
                    'dots' => __('Dots'),
                    'none' => __('None'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'navigation_previous_icon',
            [
                'label' => __('Previous Arrow Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['none'],
                'recommended' => [
                    'ce-icons' => [
                        'caret-left',
                        'angle-left',
                        'chevron-left',
                        'arrow-left',
                        'long-arrow-left',
                    ],
                    'fa-solid' => [
                        'caret-left',
                        'chevron-left',
                        'angle-left',
                        'angles-left',
                        'arrow-left',
                        'left-long',
                        'circle-arrow-left',
                        'circle-chevron-left',
                        'square-caret-left',
                    ],
                    'fa-regular' => [
                        'square-caret-left',
                        'circle-left',
                        'hand-point-left',
                    ],
                ],
                'default' => [
                    'value' => 'ceicon-chevron-left',
                    'library' => 'ce-icons',
                ],
                'condition' => [
                    'navigation' => ['both', 'arrows'],
                ],
            ]
        );

        $this->addControl(
            'navigation_next_icon',
            [
                'label' => __('Next Arrow Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['none'],
                'recommended' => [
                    'ce-icons' => [
                        'caret-right',
                        'angle-right',
                        'chevron-right',
                        'arrow-right',
                        'long-arrow-right',
                    ],
                    'fa-solid' => [
                        'caret-right',
                        'chevron-right',
                        'angle-right',
                        'angles-right',
                        'arrow-right',
                        'right-long',
                        'circle-arrow-right',
                        'circle-chevron-right',
                        'square-caret-right',
                    ],
                    'fa-regular' => [
                        'square-caret-right',
                        'circle-right',
                        'hand-point-right',
                    ],
                ],
                'default' => [
                    'value' => 'ceicon-chevron-right',
                    'library' => 'ce-icons',
                ],
                'condition' => [
                    'navigation' => ['both', 'arrows'],
                ],
            ]
        );

        $this->addControl(
            'additional_options',
            [
                'label' => __('Additional Options'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'autoplay',
            [
                'label' => __('Autoplay'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'pause_on_hover',
            [
                'label' => __('Pause on Hover'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'autoplay' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'pause_on_interaction',
            [
                'label' => __('Pause on Interaction'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'autoplay' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'autoplay_speed',
            [
                'label' => __('Autoplay Speed'),
                'type' => ControlsManager::NUMBER,
                'default' => 5000,
                'condition' => [
                    'autoplay' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->addResponsiveControl(
            'infinite',
            [
                'label' => __('Infinite Loop'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'tablet_default' => 'yes',
                'mobile_default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'effect',
            [
                'label' => __('Effect'),
                'type' => ControlsManager::SELECT,
                'default' => 'slide',
                'options' => [
                    'cards' => __('Cards'),
                    'coverflow' => __('Coverflow'),
                    'cube' => __('Cube'),
                    'fade' => __('Fade'),
                    'flip' => __('Flip'),
                    'slide' => __('Slide'),
                ],
                'condition' => [
                    'slides_to_show' => '1',
                    'center_mode' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'speed',
            [
                'label' => __('Animation Speed') . ' (ms)',
                'type' => ControlsManager::NUMBER,
                'default' => 500,
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'direction',
            [
                'label' => __('Direction'),
                'type' => ControlsManager::SELECT,
                'default' => 'ltr',
                'options' => [
                    'ltr' => __('Left'),
                    'rtl' => __('Right'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->endControlsSection();
    }

    protected function registerNavigationStyleSection()
    {
        $this->startControlsSection(
            'section_style_navigation',
            [
                'label' => __('Navigation'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'navigation' => ['arrows', 'dots', 'both'],
                ],
            ]
        );

        $this->addControl(
            'heading_style_arrows',
            [
                'label' => __('Arrows'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->addControl(
            'arrows_position',
            [
                'label' => __('Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'inside',
                'options' => [
                    'inside' => __('Inside'),
                    'outside' => __('Outside'),
                ],
                'prefix_class' => 'elementor-arrows-position-',
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->addResponsiveControl(
            'arrows_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->addControl(
            'arrows_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-swiper-button' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->addControl(
            'heading_style_dots',
            [
                'label' => __('Pagination'),
                'type' => ControlsManager::HEADING,
                'condition' => [
                    'navigation' => ['dots', 'both'],
                ],
            ]
        );

        $this->addControl(
            'dots_position',
            [
                'label' => __('Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'outside',
                'options' => [
                    'outside' => __('Outside'),
                    'inside' => __('Inside'),
                ],
                'prefix_class' => 'elementor-pagination-position-',
                'condition' => [
                    'navigation' => ['dots', 'both'],
                ],
            ]
        );

        $this->addResponsiveControl(
            'dots_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'navigation' => ['dots', 'both'],
                ],
            ]
        );

        $this->addControl(
            'dots_inactive_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    // The opacity property will override the default inactive dot color which is opacity 0.2.
                    '{{WRAPPER}} .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)' => 'background: {{VALUE}}; opacity: 1',
                ],
                'condition' => [
                    'navigation' => ['dots', 'both'],
                ],
            ]
        );

        $this->addControl(
            'dots_color',
            [
                'label' => __('Active Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'navigation' => ['dots', 'both'],
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function renderCarousel(array &$settings, array &$slides)
    {
        if (!$slides) {
            return;
        }
        $this->addRenderAttribute('carousel', 'class', 'swiper-wrapper');
        ?>
        <div class="elementor-carousel-wrapper swiper" dir="<?php echo esc_attr($settings['direction']); ?>">
            <div <?php $this->printRenderAttributeString('carousel'); ?>><?php echo implode('', $slides); ?></div>
        <?php if (count($slides) > 1) { ?>
            <?php if ('arrows' === $settings['navigation'] || 'both' === $settings['navigation']) { ?>
                <div class="elementor-swiper-button elementor-swiper-button-prev" role="button" tabindex="0">
                    <?php IconsManager::renderIcon($settings['navigation_previous_icon'], ['aria-hidden' => 'true']); ?>
                    <span class="elementor-screen-only"><?php _e('Previous'); ?></span>
                </div>
                <div class="elementor-swiper-button elementor-swiper-button-next" role="button" tabindex="0">
                    <?php IconsManager::renderIcon($settings['navigation_next_icon'], ['aria-hidden' => 'true']); ?>
                    <span class="elementor-screen-only"><?php _e('Next'); ?></span>
                </div>
            <?php } ?>
            <?php if ('dots' === $settings['navigation'] || 'both' === $settings['navigation']) { ?>
                <div class="swiper-pagination"></div>
            <?php } ?>
        <?php } ?>
        </div>
        <?php
    }
}
