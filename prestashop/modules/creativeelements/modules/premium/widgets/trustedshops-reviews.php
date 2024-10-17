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

class ModulesXPremiumXWidgetsXTrustedshopsReviews extends WidgetBase
{
    use CarouselTrait;

    public function getName()
    {
        return 'trustedshops-reviews';
    }

    public function getTitle()
    {
        return __('TrustedShops Reviews');
    }

    public function getIcon()
    {
        return 'eicon-carousel';
    }

    public function getCategories()
    {
        return ['premium'];
    }

    public function getKeywords()
    {
        return ['trustedshops', 'reviews', 'testimonial', 'carousel', 'slider'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_product_carousel',
            [
                'label' => __('TrustedShops Reviews'),
            ]
        );

        $this->addControl(
            'ts_id',
            [
                'label' => __('TrustedShops Id'),
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'description' => __('You received your personal TS ID when you registered with Trusted Shops.'),
            ]
        );

        $show_from = __('Show from %d Stars');

        $this->addControl(
            'min_rating',
            [
                'label' => __('Filter Reviews'),
                'type' => ControlsManager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => __('Show All'),
                    '2' => sprintf($show_from, 2),
                    '3' => sprintf($show_from, 3),
                    '4' => sprintf($show_from, 4),
                    '5' => sprintf($show_from, 5),
                ],
            ]
        );

        $this->endControlsSection();

        $this->registerCarouselSection([
            'default_slides_count' => 4,
        ]);

        $this->startControlsSection(
            'section_style_reviews',
            [
                'label' => __('Reviews'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'reviews_min_height',
            [
                'label' => __('Min Height'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                    ],
                ],
                'size_units' => ['px', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'reviews_vertical_alignment',
            [
                'label' => __('Vertical Alignment'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'top' => __('Top'),
                    'middle' => __('Middle'),
                    'bottom' => __('Bottom'),
                ],
                'default' => 'middle',
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-wrapper' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'reviews_spacing',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper:not(.swiper-initialized) .swiper-wrapper' => 'grid-column-gap: {{SIZE}}px;',
                ],
                'frontend_available' => true,
                'render_type' => 'none',
                'condition' => [
                    'slides_to_show!' => '1',
                ],
            ]
        );

        $this->addControl(
            'reviews_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'reviews_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-trustedshops-reviews-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-trustedshops-reviews-comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'reviews_border',
                'selector' => '{{WRAPPER}} .elementor-image-carousel .swiper-slide',
            ]
        );

        $this->addControl(
            'reviews_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-carousel .swiper-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'heading_style_header',
            [
                'label' => __('Heading'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'header_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-trustedshops-reviews-header' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'header_gap',
            [
                'label' => __('Gap'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-trustedshops-reviews-header' => 'padding-bottom: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .elementor-trustedshops-reviews-comment' => 'padding-top: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->addControl(
            'header_separator',
            [
                'label' => __('Separator'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'return_value' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .elementor-trustedshops-reviews-header' => 'border-bottom-style: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'header_separator_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-trustedshops-reviews-header' => 'border-bottom-color: {{VALUE}};',
                ],
                'condition' => [
                    'header_separator!' => '',
                ],
            ]
        );

        $this->addControl(
            'header_separator_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-trustedshops-reviews-header' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'header_separator!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_text',
            [
                'label' => __('Text'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'heading_style_date',
            [
                'label' => __('Date'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'date_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-trustedshops-reviews-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'date_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .elementor-trustedshops-reviews-date',
            ]
        );

        $this->addControl(
            'heading_style_comment',
            [
                'label' => __('Comment'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'comment_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-trustedshops-reviews-comment' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'comment_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-trustedshops-reviews-comment',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_rating',
            [
                'label' => __('Rating'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'rating_icon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Font Awesome'),
                    'unicode' => __('Unicode'),
                ],
            ]
        );

        $this->addControl(
            'rating_unmarked_style',
            [
                'label' => __('Unmarked Style'),
                'label_block' => false,
                'type' => ControlsManager::CHOOSE,
                'default' => 'star',
                'options' => [
                    'star' => [
                        'title' => __('Solid'),
                        'icon' => 'ceicon-star',
                    ],
                    'star-o' => [
                        'title' => __('Outline'),
                        'icon' => 'ceicon-star-o',
                    ],
                ],
            ]
        );

        $this->addControl(
            'rating_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-trustedshops-reviews-stars' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'rating_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-trustedshops-reviews-stars' => 'letter-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'rating_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'default' => '#f0ad4e',
                'selectors' => [
                    '{{WRAPPER}} .elementor-trustedshops-reviews-stars' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'rating_unmarked_color',
            [
                'label' => __('Unmarked Color'),
                'type' => ControlsManager::COLOR,
                'default' => '#ccd6df',
                'selectors' => [
                    '{{WRAPPER}} .elementor-trustedshops-reviews-stars .elementor-unmarked-star' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->registerNavigationStyleSection();
    }

    protected function getReviews($tsId)
    {
        if (strlen($tsId) != 33) {
            return false;
        }

        $reviews = get_transient('ts_' . $tsId);

        if (false === $reviews) {
            $result = wp_remote_get("http://api.trustedshops.com/rest/public/v2/shops/$tsId/reviews.json");

            if (empty($result)) {
                return false;
            }

            $result = json_decode($result, true);

            if (empty($result['response']['data']['shop']['reviews'])) {
                return false;
            }

            $reviews = &$result['response']['data']['shop']['reviews'];

            set_transient('ts_' . $tsId, $reviews, 24 * 3600);
        }

        return $reviews;
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $reviews = $this->getReviews($settings['ts_id']);

        if (empty($reviews)) {
            return;
        }

        $date_format = \Context::getContext()->language->date_format_lite;
        $star = '<i class="ceicon-star"></i>';
        $unstar = '<i class="ceicon-' . $settings['rating_unmarked_style'] . ' elementor-unmarked-star"></i>';
        $slides = [];

        foreach ($reviews as &$review) {
            $rating = round($review['mark']);

            if ($rating >= (int) $settings['min_rating']) {
                ob_start(); ?>
                <div class="swiper-slide">
                    <div class="elementor-trustedshops-review">
                        <div class="elementor-trustedshops-reviews-header">
                            <div class="elementor-trustedshops-reviews-date">
                                <?php echo date($date_format, strtotime($review['changeDate'])); ?>
                            </div>
                            <div class="elementor-trustedshops-reviews-stars">
                                <?php echo str_repeat($star, $rating) . str_repeat($unstar, 5 - $rating); ?>
                            </div>
                        </div>
                        <div class="elementor-trustedshops-reviews-comment"><?php echo $review['comment']; ?></div>
                    </div>
                </div>
                <?php
                $slides[] = ob_get_clean();
            }
        }

        $this->addRenderAttribute('carousel', 'class', 'elementor-trustedshops-reviews');

        $settings['rating_icon'] && $this->addRenderAttribute('carousel', 'class', 'elementor-icon-unicode');

        $this->renderCarousel($settings, $slides);
    }
}
