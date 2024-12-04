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

class ModulesXPremiumXWidgetsXTestimonialCarousel extends WidgetBase
{
    use CarouselTrait;

    public function getName()
    {
        return 'testimonial-carousel';
    }

    public function getTitle()
    {
        return __('Testimonial Carousel');
    }

    public function getIcon()
    {
        return 'eicon-testimonial-carousel';
    }

    public function getCategories()
    {
        return ['premium'];
    }

    public function getKeywords()
    {
        return ['testimonial', 'blockquote', 'carousel', 'slider'];
    }

    protected function isDynamicContent()
    {
        return false;
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_testimonials',
            [
                'label' => __('Testimonials'),
            ]
        );

        $sample = [
            'content' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'),
            'image' => [
                'url' => Utils::getPlaceholderImageSrc(),
            ],
            'name' => 'John Doe',
            'title' => 'Designer',
        ];

        $this->addControl(
            'slides',
            [
                'type' => ControlsManager::REPEATER,
                'default' => [$sample, $sample, $sample],
                'fields' => [
                    [
                        'name' => 'content',
                        'label' => __('Content'),
                        'type' => ControlsManager::TEXTAREA,
                        'rows' => 5,
                    ],
                    [
                        'name' => 'image',
                        'label' => __('Image'),
                        'type' => ControlsManager::MEDIA,
                        'seo' => 'true',
                        'default' => [
                            'url' => Utils::getPlaceholderImageSrc(),
                        ],
                    ],
                    [
                        'name' => 'name',
                        'label' => __('Name'),
                        'type' => ControlsManager::TEXT,
                        'default' => 'John Doe',
                    ],
                    [
                        'name' => 'title',
                        'label' => __('Job'),
                        'type' => ControlsManager::TEXT,
                        'default' => 'Designer',
                    ],
                    [
                        'name' => 'link',
                        'label' => __('Link'),
                        'type' => ControlsManager::URL,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'placeholder' => __('https://your-link.com'),
                    ],
                ],
                'title_field' => '<# if (image.url) { #>' .
                    '<img src="{{ elementor.helpers.getMediaLink(image.url) }}" class="ce-repeater-thumb"><# } #>' .
                    '{{{ name || title || image.title || image.alt || image.url && image.url.split("/").pop() }}}',
            ]
        );

        $this->addControl(
            'layout',
            [
                'label' => __('Layout'),
                'type' => ControlsManager::SELECT,
                'default' => 'image_inline',
                'options' => [
                    'image_inline' => __('Image Inline'),
                    'image_stacked' => __('Image Stacked'),
                    'image_above' => __('Image Above'),
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'alignment',
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
                'separator' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->registerCarouselSection([
            'default_slides_count' => 1,
        ]);

        $this->startControlsSection(
            'section_style_testimonials',
            [
                'label' => __('Testimonials'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'space_between',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .swiper:not(.swiper-initialized) .swiper-wrapper' => 'grid-column-gap: {{SIZE}}px;',
                ],
                'frontend_available' => true,
                'render_type' => 'none',
            ]
        );

        $this->addResponsiveControl(
            'slide_min_height',
            [
                'label' => __('Min Height'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'slide_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'slide_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}}  .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'slide_border_size',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}}  .swiper-slide' => 'border-style: solid; border-width: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->addControl(
            'slide_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'slide_border_size[top]!' => '',
                ],
            ]
        );

        $this->addControl(
            'slide_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'separator' => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'border-radius: {{SIZE}}{{UNIT}};',
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

        $this->startControlsTabs('tabs_style_content');

        $this->startControlsTab(
            'tab_style_content',
            [
                'label' => __('Content'),
            ]
        );

        $this->addResponsiveControl(
            'content_gap',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'content_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .elementor-testimonial-content',
            ]
        );

        $this->addGroupControl(
            GroupControlTextStroke::getType(),
            [
                'name' => 'text_stroke',
                'selector' => '{{WRAPPER}} .elementor-testimonial-content',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_style_name',
            [
                'label' => __('Name'),
            ]
        );

        $this->addControl(
            'name_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .elementor-testimonial-name',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_style_title',
            [
                'label' => __('Job'),
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-job' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-testimonial-job',
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_image',
            [
                'label' => __('Image'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'image_gap',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout!' => 'image_inline',
                ],
            ]
        );

        $this->addResponsiveControl(
            'image_size',
            [
                'label' => __('Image Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'image_border',
            [
                'label' => __('Border'),
                'type' => ControlsManager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'border-style: solid;',
                ],
            ]
        );

        $this->addControl(
            'image_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'image_border!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'image_border_size',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 20,
                    ],
                ],
                'separator' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_border!' => '',
                ],
            ]
        );

        $this->addControl(
            'image_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->registerNavigationStyleSection();
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-testimonial';
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $layout_class = 'elementor-testimonial-image-position-' . ('image_inline' === $settings['layout'] ? 'aside' : 'top');
        $slides = [];

        foreach ($settings['slides'] as &$slide) {
            $has_link = !empty($slide['link']['url']);

            if ($has_link) {
                $this->setRenderAttribute('link', [
                    'href' => $slide['link']['url'],
                    'target' => $slide['link']['is_external'] ? '_blank' : null,
                    'rel' => !empty($slide['link']['nofollow']) ? 'nofollow' : null,
                ]);
            }
            ob_start(); ?>
            <div class="swiper-slide">
                <div class="elementor-testimonial-wrapper">
                <?php if ('image_above' === $settings['layout'] && !empty($slide['image']['url'])) { ?>
                    <div class="elementor-testimonial-meta <?php echo $layout_class; ?>">
                        <div class="elementor-testimonial-meta-inner">
                            <div class="elementor-testimonial-image">
                            <?php if ($has_link) { ?>
                                <a <?php $this->printRenderAttributeString('link'); ?>>
                                    <?php echo GroupControlImageSize::getAttachmentImageHtml($slide, 'image'); ?>
                                    <?php empty($slide['image']['loading']) && print '<div class="swiper-lazy-preloader"></div>'; ?>
                                </a>
                            <?php } else { ?>
                                <?php echo GroupControlImageSize::getAttachmentImageHtml($slide, 'image'); ?>
                                <?php empty($slide['image']['loading']) && print '<div class="swiper-lazy-preloader"></div>'; ?>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if ('' !== $slide['content']) { ?>
                    <div class="elementor-testimonial-content"><?php echo $slide['content']; ?></div>
                <?php } ?>
                    <div class="elementor-testimonial-meta <?php echo $layout_class; ?>">
                        <div class="elementor-testimonial-meta-inner">
                        <?php if ('image_above' !== $settings['layout'] && !empty($slide['image']['url'])) { ?>
                            <div class="elementor-testimonial-image">
                            <?php if ($has_link) { ?>
                                <a <?php $this->printRenderAttributeString('link'); ?>>
                                    <?php echo GroupControlImageSize::getAttachmentImageHtml($slide, 'image'); ?>
                                    <?php empty($slide['image']['loading']) && print '<div class="swiper-lazy-preloader"></div>'; ?>
                                </a>
                            <?php } else { ?>
                                <?php echo GroupControlImageSize::getAttachmentImageHtml($slide, 'image'); ?>
                                <?php empty($slide['image']['loading']) && print '<div class="swiper-lazy-preloader"></div>'; ?>
                            <?php } ?>
                            </div>
                        <?php } ?>
                            <div class="elementor-testimonial-details">
                            <?php if ('' !== $slide['name']) { ?>
                                <div class="elementor-testimonial-name">
                                <?php if ($has_link) { ?>
                                    <a <?php $this->printRenderAttributeString('link'); ?>><?php echo $slide['name']; ?></a>
                                <?php } else { ?>
                                    <?php echo $slide['name']; ?>
                                <?php } ?>
                                </div>
                            <?php } ?>
                            <?php if ('' !== $slide['title']) { ?>
                                <div class="elementor-testimonial-job">
                                <?php if ($has_link) { ?>
                                    <a <?php $this->printRenderAttributeString('link'); ?>><?php echo $slide['title']; ?></a>
                                <?php } else { ?>
                                    <?php echo $slide['title']; ?>
                                <?php } ?>
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $slides[] = ob_get_clean();
        }

        $this->renderCarousel($settings, $slides);
    }
}
