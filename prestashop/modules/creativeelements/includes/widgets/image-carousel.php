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

/**
 * Elementor image carousel widget.
 *
 * Elementor widget that displays a set of images in a rotating carousel or
 * slider.
 *
 * @since 1.0.0
 */
class WidgetImageCarousel extends WidgetBase
{
    use CarouselTrait;

    /**
     * Get widget name.
     *
     * Retrieve image carousel widget name.
     *
     * @since 1.0.0
     *
     * @return string Widget name
     */
    public function getName()
    {
        return 'image-carousel';
    }

    /**
     * Get widget title.
     *
     * Retrieve image carousel widget title.
     *
     * @since 1.0.0
     *
     * @return string Widget title
     */
    public function getTitle()
    {
        return __('Image Carousel');
    }

    /**
     * Get widget icon.
     *
     * Retrieve image carousel widget icon.
     *
     * @since 1.0.0
     *
     * @return string Widget icon
     */
    public function getIcon()
    {
        return 'eicon-slider-push';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     *
     * @return array Widget keywords
     */
    public function getKeywords()
    {
        return ['image', 'photo', 'visual', 'carousel', 'slider'];
    }

    protected function isDynamicContent()
    {
        return false;
    }

    /**
     * Register image carousel widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_image_carousel',
            [
                'label' => __('Image Carousel'),
            ]
        );

        $this->addControl(
            'links',
            [
                'type' => ControlsManager::RAW_HTML,
                'raw' => '
                    <style>
                    .elementor-control-links.elementor-hidden-control ~
                    .elementor-control-carousel .elementor-control-link,
                    .elementor-control-links { display: none; }
                    </style>',
                'condition' => [
                    'link_to' => 'custom',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->addControl(
            'image',
            [
                'label' => __('Choose Image'),
                'type' => ControlsManager::MEDIA,
                'seo' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::getPlaceholderImageSrc(),
                ],
            ]
        );

        $repeater->addControl(
            'caption',
            [
                'label' => __('Caption'),
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Enter your image caption'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->addControl(
            'link',
            [
                'label' => __('Link'),
                'type' => ControlsManager::URL,
                'label_block' => true,
                'placeholder' => __('http://your-link.com'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->addControl(
            'carousel',
            [
                'label' => __('Images'),
                'type' => ControlsManager::REPEATER,
                'fields' => $repeater->getControls(),
                'default' => [
                    [
                        'image' => [
                            'url' => Utils::getPlaceholderImageSrc(),
                        ],
                    ],
                    [
                        'image' => [
                            'url' => Utils::getPlaceholderImageSrc(),
                        ],
                    ],
                ],
                'dynamic' => [
                    'active' => true,
                    'categories' => ['gallery'],
                ],
                'title_field' => '<# if (image.url) { #>' .
                    '<img src="{{ elementor.helpers.getMediaLink(image.url) }}" class="ce-repeater-thumb"><# } #>' .
                    '{{{ caption || image.title || image.alt || image.url && image.url.split("/").pop() }}}',
            ]
        );

        $this->addControl(
            'link_to',
            [
                'label' => __('Link'),
                'type' => ControlsManager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None'),
                    'file' => __('Media File'),
                    'custom' => __('Custom URL'),
                ],
            ]
        );

        $this->addControl(
            'open_lightbox',
            [
                'label' => __('Lightbox'),
                'type' => ControlsManager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default'),
                    'yes' => __('Yes'),
                    'no' => __('No'),
                ],
                'condition' => [
                    'link_to' => 'file',
                ],
            ]
        );

        $this->addControl(
            'lightbox_group',
            [
                'label' => __('Lightbox Group'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Default'),
                'condition' => [
                    'link_to' => 'file',
                    'open_lightbox' => 'yes',
                ],
            ]
        );

        $this->addControl(
            'variable_width',
            [
                'label' => __('Variable Width'),
                'type' => ControlsManager::SWITCHER,
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'image_stretch',
            [
                'label' => __('Image Stretch'),
                'type' => ControlsManager::SWITCHER,
            ]
        );

        $this->addResponsiveControl(
            'image_height',
            [
                'label' => __('Height'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide-image' => 'height: {{SIZE}}{{UNIT}}',
                ],
                'frontend_available' => true,
                'condition' => [
                    'image_stretch!' => '',
                ],
            ]
        );

        $this->addControl(
            'view',
            [
                'label' => __('View'),
                'type' => ControlsManager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->endControlsSection();

        $this->registerCarouselSection([
            'lazyload' => true,
            'variable_width' => true,
        ]);

        $this->startControlsSection(
            'section_style_image',
            [
                'label' => __('Image'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'image_spacing',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::POPOVER_TOGGLE,
                'return_value' => 'custom',
                'condition' => [
                    'slides_to_show!' => '1',
                ],
            ]
        );

        $this->startPopover();

        $this->addResponsiveControl(
            'image_spacing_custom',
            [
                'label' => __('Gap'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper:not(.swiper-initialized) .swiper-wrapper' => 'grid-column-gap: {{SIZE}}px;',
                ],
                'condition' => [
                    'slides_to_show!' => '1',
                    'image_spacing' => 'custom',
                ],
                'frontend_available' => true,
                'render_type' => 'none',
                'separator' => 'after',
            ]
        );

        $this->endPopover();

        $this->addResponsiveControl(
            'gallery_vertical_align',
            [
                'label' => __('Vertical Align'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Start'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => __('End'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'condition' => [
                    'slides_to_show!' => '1',
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-wrapper' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .elementor-image-carousel .swiper-slide-image',
            ]
        );

        $this->addControl(
            'image_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-carousel .swiper-slide-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_caption',
            [
                'label' => __('Caption'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'caption_align',
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-carousel-caption' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'caption_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-carousel-caption' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'caption_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .elementor-image-carousel-caption',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'caption_shadow',
                'selector' => '{{WRAPPER}} .elementor-image-carousel-caption',
            ]
        );

        $this->addResponsiveControl(
            'caption_space',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} figcaption' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->registerNavigationStyleSection();
    }

    public function onImport($widget)
    {
        // Compatibility fix with WP image-carousel
        if (isset($widget['settings']['carousel'][0]['url'])) {
            $carousel = [];
            $import_images = Plugin::$instance->templates_manager->getImportImagesInstance();

            foreach ($widget['settings']['carousel'] as &$img) {
                $image = $import_images->import($img);

                $carousel[] = [
                    '_imported' => true,
                    '_id' => Utils::generateRandomString(),
                    'image' => $image ?: [
                        'id' => 0,
                        'url' => Utils::getPlaceholderImageSrc(),
                    ],
                ];
            }

            $widget['settings']['carousel'] = $carousel;
        }

        return $widget;
    }

    /**
     * Render image carousel widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        if (!$settings['carousel']) {
            return;
        }

        $group = !empty($settings['lightbox_group']) ? $settings['lightbox_group'] : $this->getId();
        $edit_mode = Plugin::$instance->editor->isEditMode();
        $slides = [];

        foreach ($settings['carousel'] as $index => &$item) {
            if (empty($item['image']['url'])) {
                continue;
            }
            $image_html = GroupControlImageSize::getAttachmentImageHtml($item, 'image', 'swiper-slide-image');
            $link_tag = '';
            $link = $this->getLinkUrl($item, $settings['link_to']);

            if ($link) {
                $link_key = 'link_' . $index;

                $this->addLightboxDataAttributes($link_key, $item['image']['id'], $settings['open_lightbox'], $group);

                if ($edit_mode) {
                    $this->addRenderAttribute($link_key, 'class', 'elementor-clickable');
                }

                $this->addLinkAttributes($link_key, $link);

                $link_tag = '<a ' . $this->getRenderAttributeString($link_key) . '>';
            }
            $slide_html = '<div class="swiper-slide">' . $link_tag .
                '<figure class="swiper-slide-inner">' . $image_html;

            empty($item['image']['loading']) && $slide_html .= '<div class="swiper-lazy-preloader"></div>';

            $item['caption'] && $slide_html .= '<figcaption class="elementor-image-carousel-caption">' . $item['caption'] . '</figcaption>';

            $slide_html .= '</figure>';

            if ($link) {
                $slide_html .= '</a>';
            }
            $slide_html .= '</div>';

            $slides[] = $slide_html;
        }

        if (!$slides) {
            return;
        }

        $this->addRenderAttribute('carousel', 'class', 'elementor-image-carousel');

        $settings['variable_width'] && $this->addRenderAttribute('carousel', 'class', 'swiper-variable-width');
        $settings['image_stretch'] && $this->addRenderAttribute('carousel', 'class', 'swiper-image-stretch');

        $this->renderCarousel($settings, $slides);
    }

    /**
     * Retrieve image carousel link URL.
     *
     * @since 1.0.0
     *
     * @param array $item
     * @param object $instance
     *
     * @return array|string|false An array/string containing the attachment URL, or false if no link
     */
    private function getLinkUrl($item, $link_to)
    {
        if ('none' === $link_to) {
            return false;
        }

        if ('custom' === $link_to) {
            if (empty($item['link']['url'])) {
                return false;
            }

            return $item['link'];
        }

        return empty($item['image']['url']) ? false : [
            'url' => Helper::getMediaLink($item['image']['url']),
        ];
    }
}
