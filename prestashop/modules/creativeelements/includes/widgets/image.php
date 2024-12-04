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
 * Elementor image widget.
 *
 * Elementor widget that displays an image into the page.
 *
 * @since 1.0.0
 */
class WidgetImage extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve image widget name.
     *
     * @since 1.0.0
     *
     * @return string Widget name
     */
    public function getName()
    {
        return 'image';
    }

    /**
     * Get widget title.
     *
     * Retrieve image widget title.
     *
     * @since 1.0.0
     *
     * @return string Widget title
     */
    public function getTitle()
    {
        return __('Image');
    }

    /**
     * Get widget icon.
     *
     * Retrieve image widget icon.
     *
     * @since 1.0.0
     *
     * @return string Widget icon
     */
    public function getIcon()
    {
        return 'eicon-image';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the image widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since 2.0.0
     *
     * @return array Widget categories
     */
    public function getCategories()
    {
        return ['basic'];
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
        return ['image', 'picture', 'photo', 'visual'];
    }

    protected function isDynamicContent()
    {
        return false;
    }

    /**
     * Register image widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_image',
            [
                'label' => __('Image'),
            ]
        );

        $this->addControl(
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

        $this->addControl(
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

        $this->addResponsiveControl(
            'align',
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
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
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
            'link',
            [
                'label' => __('Link'),
                'type' => ControlsManager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __('https://your-link.com'),
                'condition' => [
                    'link_to' => 'custom',
                ],
                'show_label' => false,
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
            'view',
            [
                'label' => __('View'),
                'type' => ControlsManager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_image',
            [
                'label' => __('Image'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'width',
            [
                'label' => __('Width'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 1,
                    ],
                    'vw' => [
                        'min' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'space',
            [
                'label' => __('Max Width'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => ['%', 'px', 'vw'],
                'range' => [
                    '%' => [
                        'min' => 1,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'height',
            [
                'label' => __('Height'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'object-fit',
            [
                'label' => __('Object Fit'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'fill' => _x('Fill', 'Background Control'),
                    'cover' => _x('Cover', 'Background Control'),
                    'contain' => _x('Contain', 'Background Control'),
                    'scale-down' => _x('Scale Down', 'Background Control'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image img' => 'object-fit: {{VALUE}};',
                ],
                'condition' => [
                    'height[size]!' => '',
                ],
                'device_args' => $height_conditions = [
                    ControlsStack::RESPONSIVE_TABLET => [
                        'condition' => null,
                        'conditions' => [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'height[size]',
                                    'operator' => '>',
                                    'value' => 0,
                                ],
                                [
                                    'name' => 'height_tablet[size]',
                                    'operator' => '>',
                                    'value' => 0,
                                ],
                            ],
                        ],
                    ],
                    ControlsStack::RESPONSIVE_MOBILE => [
                        'condition' => null,
                        'conditions' => [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'height[size]',
                                    'operator' => '>',
                                    'value' => 0,
                                ],
                                [
                                    'name' => 'height_tablet[size]',
                                    'operator' => '>',
                                    'value' => 0,
                                ],
                                [
                                    'name' => 'height_mobile[size]',
                                    'operator' => '>',
                                    'value' => 0,
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->addResponsiveControl(
            'object_position',
            [
                'label' => __('Position'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'top left' => _x('Top Left', 'Background Control'),
                    'top center' => _x('Top Center', 'Background Control'),
                    'top right' => _x('Top Right', 'Background Control'),
                    'center left' => _x('Center Left', 'Background Control'),
                    'center center' => _x('Center Center', 'Background Control'),
                    'center right' => _x('Center Right', 'Background Control'),
                    'bottom left' => _x('Bottom Left', 'Background Control'),
                    'bottom center' => _x('Bottom Center', 'Background Control'),
                    'bottom right' => _x('Bottom Right', 'Background Control'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image img' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'height[size]!' => '',
                ],
                'device_args' => $height_conditions,
            ]
        );

        $this->addControl(
            'separator_panel_style',
            [
                'type' => ControlsManager::DIVIDER,
            ]
        );

        $this->startControlsTabs('image_effects');

        $this->startControlsTab(
            'normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'opacity',
            [
                'label' => __('Opacity'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .elementor-image img',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'opacity_hover',
            [
                'label' => __('Opacity'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image:hover img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'css_filters_hover',
                'selector' => '{{WRAPPER}} .elementor-image:hover img',
            ]
        );

        $this->addControl(
            'background_hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image img' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );

        $this->addControl(
            'hover_animation',
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
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .elementor-image img',
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'image_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'image_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .elementor-image img',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_caption',
            [
                'label' => __('Caption'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'caption!' => '',
                ],
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
                'selectors' => [
                    '{{WRAPPER}} .widget-image-caption' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget-image-caption' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
            ]
        );

        $this->addControl(
            'caption_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget-image-caption' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'caption_typography',
                'selector' => '{{WRAPPER}} .widget-image-caption',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'caption_text_shadow',
                'selector' => '{{WRAPPER}} .widget-image-caption',
            ]
        );

        $this->addResponsiveControl(
            'caption_space',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .widget-image-caption' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render image widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        if (empty($settings['image']['url'])) {
            return;
        }
        // Fix: Check isset for extended widgets
        $has_caption = isset($settings['caption']) && '' !== $settings['caption'];
        $link = $this->getLinkUrl($settings);

        if ($link) {
            $this->addLinkAttributes('link', $link);

            if (Plugin::$instance->editor->isEditMode()) {
                $this->addRenderAttribute('link', 'class', 'elementor-clickable');
            }

            if ('custom' !== $settings['link_to']) {
                $this->addLightboxDataAttributes('link', null, $settings['open_lightbox'], !empty($settings['lightbox_group']) ? $settings['lightbox_group'] : null);
            }
        } ?>
        <div class="elementor-image">
        <?php if ($has_caption) { ?>
            <figure class="ce-caption">
        <?php } ?>
        <?php if ($link) { ?>
            <a <?php $this->printRenderAttributeString('link'); ?>>
        <?php } ?>
            <?php echo GroupControlImageSize::getAttachmentImageHtml($settings); ?>
        <?php if ($link) { ?>
            </a>
        <?php } ?>
        <?php if ($has_caption) { ?>
            <figcaption class="widget-image-caption ce-caption-text"><?php echo $settings['caption']; ?></figcaption>
        <?php } ?>
        <?php if ($has_caption) { ?>
            </figure>
        <?php } ?>
        </div>
        <?php
    }

    /**
     * Render image widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 2.9.0
     */
    protected function contentTemplate()
    {
        ?>
        <# if ( settings.image.url ) {
            var image_url = elementor.helpers.getMediaLink( settings.image.url );

            if ( ! image_url ) {
                return;
            }

            var link_url;

            if ( 'custom' === settings.link_to ) {
                link_url = settings.link.url;
            } else if ( 'file' === settings.link_to ) {
                link_url = image_url;
            }

            #><div class="elementor-image{{ settings.shape ? ' elementor-image-shape-' + settings.shape : '' }}"><#
            var lightbox = 'data-elementor-open-lightbox',
                imgClass = '';

            if ( '' !== settings.hover_animation ) {
                imgClass = 'elementor-animation-' + settings.hover_animation;
            }

            if ( settings.caption ) {
                #><figure class="ce-caption"><#
            }

            if ( link_url ) {
                #><a class="elementor-clickable" {{ lightbox }}="{{ settings.open_lightbox }}" href="{{ link_url }}"><#
            }
            #><img src="{{ image_url }}" class="{{ imgClass }}"><#

            if ( link_url ) {
                #></a><#
            }

            if ( settings.caption ) {
                #><figcaption class="widget-image-caption ce-caption-text">{{{ settings.caption }}}</figcaption><#
            }

            if ( settings.caption ) {
                #></figure><#
            }

            #></div><#
        } #>
        <?php
    }

    /**
     * Retrieve image widget link URL.
     *
     * @since 1.0.0
     *
     * @param array $settings
     *
     * @return array|string|false An array/string containing the link URL, or false if no link
     */
    private function getLinkUrl($settings)
    {
        if ('none' === $settings['link_to']) {
            return false;
        }

        if ('custom' === $settings['link_to']) {
            if (empty($settings['link']['url'])) {
                return false;
            }

            return $settings['link'];
        }

        return [
            'url' => Helper::getMediaLink($settings['image']['url']),
        ];
    }
}
