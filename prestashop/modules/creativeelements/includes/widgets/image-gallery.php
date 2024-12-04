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

use CE\CoreXResponsiveXResponsive as Responsive;

/**
 * Elementor image gallery widget.
 *
 * Elementor widget that displays a set of images in an aligned grid.
 *
 * @since 1.0.0
 */
class WidgetImageGallery extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve image gallery widget name.
     *
     * @since 1.0.0
     *
     * @return string Widget name
     */
    public function getName()
    {
        return 'image-gallery';
    }

    /**
     * Get widget title.
     *
     * Retrieve image gallery widget title.
     *
     * @since 1.0.0
     *
     * @return string Widget title
     */
    public function getTitle()
    {
        return __('Image Gallery');
    }

    /**
     * Get widget icon.
     *
     * Retrieve image gallery widget icon.
     *
     * @since 1.0.0
     *
     * @return string Widget icon
     */
    public function getIcon()
    {
        return 'eicon-gallery-masonry';
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
        return ['images', 'photos', 'visual', 'gallery', 'grid', 'masonry'];
    }

    protected function isDynamicContent()
    {
        return false;
    }

    /**
     * Register image gallery widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_gallery',
            [
                'label' => __('Image Gallery'),
            ]
        );

        $this->addControl(
            'links',
            [
                'type' => ControlsManager::RAW_HTML,
                'raw' => '
                    <style>
                    .elementor-control-links.elementor-hidden-control ~ .elementor-control-gallery .elementor-control-link {
                        display: none; }
                    </style>',
                'classes' => 'elementor-hidden',
                'condition' => [
                    'gallery_link' => 'custom',
                ],
            ]
        );

        $this->addControl(
            'spans',
            [
                'type' => ControlsManager::RAW_HTML,
                'raw' => '
                    <style>
                    .elementor-control-spans.elementor-hidden-control ~ .elementor-control-gallery .elementor-control-cols,
                    .elementor-control-spans.elementor-hidden-control ~ .elementor-control-gallery .elementor-control-rows {
                        display: none; }
                    </style>',
                'classes' => 'elementor-hidden',
                'condition' => [
                    'layout!' => 'masonry',
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
                'label' => __('Caption') . ' & ' . __('Description'),
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Enter your image caption'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->addControl(
            'description',
            [
                'show_label' => false,
                'type' => ControlsManager::TEXTAREA,
                'rows' => 2,
                'placeholder' => __('Enter your description'),
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
                'placeholder' => __('http://your-link.com'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->addResponsiveControl(
            'cols',
            [
                'label' => __('Column Span'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'max' => 12,
                'placeholder' => 1,
            ]
        );

        $repeater->addResponsiveControl(
            'rows',
            [
                'label' => __('Row Span'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'max' => 12,
                'placeholder' => 1,
            ]
        );

        $this->addControl(
            'gallery',
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
            'gallery_rand',
            [
                'label' => __('Order By'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'rand' => __('Random'),
                ],
            ]
        );

        $this->addControl(
            'layout',
            [
                'label' => __('Layout'),
                'type' => ControlsManager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => __('Grid'),
                    'masonry' => __('Masonry'),
                ],
                'prefix_class' => 'ce-image-gallery--layout-',
                'separator' => 'before',
            ]
        );

        $gallery_columns = range(1, 10);
        $gallery_columns = array_combine($gallery_columns, $gallery_columns);

        $this->addResponsiveControl(
            'gallery_columns',
            [
                'label' => __('Columns'),
                'type' => ControlsManager::SELECT,
                'options' => &$gallery_columns,
                'desktop_default' => 4,
                'tablet_default' => 3,
                'mobile_default' => 2,
                'selectors' => [
                    '{{WRAPPER}}.ce-image-gallery--layout-grid .ce-image-gallery' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                    '{{WRAPPER}}.ce-image-gallery--layout-masonry .ce-image-gallery' => 'columns: {{VALUE}}',
                ],
                'condition' => [
                    'overflow_scrolling!' => 'yes',
                ],
            ]
        );

        $breakpoints = Responsive::getBreakpoints();

        $this->addControl(
            'overflow_scrolling',
            [
                'label' => __('Overflow Scrolling'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Disabled'),
                    'yes' => __('Enabled'),
                    'tablet' => __('Tablet') . " (< {$breakpoints['lg']}px)",
                    'mobile' => __('Mobile') . " (< {$breakpoints['md']}px)",
                ],
                'prefix_class' => 'ce-image-gallery--overflow-scrolling-',
                'render_type' => 'template',
            ]
        );

        $this->addResponsiveControl(
            'column_width',
            [
                'label' => __('Column Width'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-image-gallery .ce-gallery-item' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'overflow_scrolling' => 'yes',
                ],
                'device_args' => [
                    ControlsStack::RESPONSIVE_TABLET => [
                        'condition' => [
                            'overflow_scrolling' => ['yes', 'tablet'],
                        ],
                    ],
                    ControlsStack::RESPONSIVE_MOBILE => [
                        'condition' => [
                            'overflow_scrolling!' => '',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'overlay',
            [
                'label' => __('Overlay'),
                'type' => ControlsManager::SWITCHER,
                'separator' => 'before',
                'label_on' => __('On'),
                'label_off' => __('Off'),
            ]
        );

        $this->addControl(
            'gallery_display_caption',
            [
                'label' => __('Caption'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'none' => __('Hide'),
                    '' => __('Show'),
                    'description' => __('Show with Description'),
                ],
                'selectors_dictionary' => [
                    'description' => 'flex',
                ],
                'prefix_class' => 'ce-image-gallery--caption-',
                'selectors' => [
                    '{{WRAPPER}} figcaption' => 'display: {{VALUE}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->addControl(
            'gallery_link',
            [
                'label' => __('Link'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'none' => __('None'),
                    'file' => __('Media File'),
                    'custom' => __('Custom URL'),
                ],
                'default' => 'file',
                'separator' => 'before',
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
                    'gallery_link' => 'file',
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
                    'gallery_link' => 'file',
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
            'section_gallery_images',
            [
                'label' => __('Images'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'image_spacing_custom',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}.ce-image-gallery--layout-grid .ce-image-gallery' => 'grid-gap: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.ce-image-gallery--layout-masonry figure' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.ce-image-gallery--layout-masonry .ce-image-gallery' => 'column-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'image_aspect_ratio',
            [
                'label' => __('Aspect Ratio'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    '1/1' => '1:1',
                    '3/2' => '3:2',
                    '4/3' => '4:3',
                    '9/16' => '9:16',
                    '16/9' => '16:9',
                    '21/9' => '21:9',
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ce-gallery-icon, {{WRAPPER}} .ce-gallery-icon img' => 'aspect-ratio: {{VALUE}}',
                ],
                'frontend_available' => true,
                'condition' => [
                    'layout' => 'grid',
                ],
            ]
        );

        $this->addResponsiveControl(
            'image_height',
            [
                'label' => __('Height'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 800,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} img' => 'width: 100%; height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ce-image-gallery' => 'grid-auto-rows: minmax({{SIZE}}{{UNIT}}, 1fr)',
                ],
                'condition' => [
                    'layout' => 'grid',
                    'image_aspect_ratio' => '',
                ],
            ]
        );

        // Add class 'ce-image-gallery--height-auto' to widget when image height is on default
        $this->addControl(
            'image_height_auto',
            [
                'type' => ControlsManager::HIDDEN,
                'default' => 'auto',
                'prefix_class' => 'ce-image-gallery--height-',
                'condition' => [
                    'image_aspect_ratio' => '',
                    'image_height[size]' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'object-fit',
            [
                'label' => __('Object Fit'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'none' => __('None'),
                    'fill' => _x('Fill', 'Background Control'),
                    'cover' => _x('Cover', 'Background Control'),
                    'contain' => _x('Contain', 'Background Control'),
                    'scale-down' => _x('Scale Down', 'Background Control'),
                ],
                'default' => 'cover',
                'selectors' => [
                    '{{WRAPPER}} img' => 'object-fit: {{VALUE}};',
                ],
                'condition' => [
                    'image_aspect_ratio' => '',
                    'image_height[size]!' => '',
                ],
                'device_args' => $height_conditions = [
                    ControlsStack::RESPONSIVE_TABLET => [
                        'condition' => null,
                        'conditions' => [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'image_height[size]',
                                    'operator' => '>',
                                    'value' => 0,
                                ],
                                [
                                    'name' => 'image_height_tablet[size]',
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
                                    'name' => 'image_height[size]',
                                    'operator' => '>',
                                    'value' => 0,
                                ],
                                [
                                    'name' => 'image_height_tablet[size]',
                                    'operator' => '>',
                                    'value' => 0,
                                ],
                                [
                                    'name' => 'image_height_mobile[size]',
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
                    '{{WRAPPER}} img' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'image_aspect_ratio' => '',
                    'image_height[size]!' => '',
                ],
                'device_args' => $height_conditions,
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'image_border',
                'exclude' => ['color'],
                'selector' => '{{WRAPPER}} figure .ce-gallery-icon',
            ]
        );

        $this->addControl(
            'image_border_none',
            [
                'type' => ControlsManager::HIDDEN,
                'default' => 'none',
                'prefix_class' => 'ce-image-gallery--border-',
                'condition' => [
                    'image_border_border!' => '',
                ],
            ]
        );

        $this->startControlsTabs('image_tabs');

        $this->startControlsTab(
            'normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'image_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} figure .ce-gallery-icon' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'image_border_border!' => '',
                ],
            ]
        );

        $this->addControl(
            'image_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} figure .ce-gallery-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} figure img' => 'border-radius: 0{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .ce-gallery-icon',
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'image_filters',
                'selector' => '{{WRAPPER}} .ce-gallery-icon img',
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
            'image_border_color_hover',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} figure .ce-gallery-icon:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'image_border_border!' => '',
                ],
            ]
        );

        $this->addControl(
            'image_border_radius_hover',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} figure .ce-gallery-icon:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} figure .ce-gallery-icon:hover img' => 'border-radius: 0{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'image_box_shadow_hover',
                'selector' => '{{WRAPPER}} .ce-gallery-icon:hover',
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'image_filters_hover',
                'selector' => '{{WRAPPER}} .ce-gallery-icon:hover img',
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'image_hover_animation',
            [
                'label' => __('Hover Animation'),
                'type' => ControlsManager::SELECT,
                'groups' => [
                    [
                        'label' => __('None'),
                        'options' => [
                            '' => __('None'),
                        ],
                    ],
                    [
                        'label' => __('Cover'),
                        'options' => [
                            'zoom-in' => 'Zoom In',
                            'zoom-out' => 'Zoom Out',
                            'move-left' => 'Move Left',
                            'move-right' => 'Move Right',
                            'move-up' => 'Move Up',
                            'move-down' => 'Move Down',
                        ],
                    ],
                    [
                        'label' => __('Classic'),
                        'options' => ControlHoverAnimation::getAnimations(),
                    ],
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'image_effect_duration',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3000,
                    ],
                ],
                'default' => [
                    'size' => 800,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-gallery-icon, {{WRAPPER}} .ce-gallery-icon img' => 'transition-duration: {{SIZE}}ms; transition-property: all;',
                ],
                'render_type' => 'template',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_overlay',
            [
                'label' => __('Overlay'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'overlay!' => '',
                ],
            ]
        );

        $this->startControlsTabs('overlay_tabs');

        $this->startControlsTab(
            'overlay_tab_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'overlay_background',
                'label' => __('Background'),
                'types' => ['gradient'],
                'selector' => '{{WRAPPER}} .ce-gallery-overlay',
            ]
        );
        $this->updateControl('overlay_background_color', ['condition' => null]);

        $this->endControlsTab();

        $this->startControlsTab(
            'overlay_tab_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'overlay_background_hover',
                'types' => ['gradient'],
                'selector' => '{{WRAPPER}} .ce-gallery-icon:hover .ce-gallery-overlay',
            ]
        );
        $this->updateControl('overlay_background_hover_color', ['condition' => null]);

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'overlay_blend_mode',
            [
                'label' => __('Blend Mode'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Normal'),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'color-burn' => 'Color Burn',
                    'hue' => 'Hue',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'exclusion' => 'Exclusion',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-gallery-overlay' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'overlay_margin',
            [
                'label' => __('Margin'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ce-gallery-overlay' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'overlay_animation',
            [
                'label' => __('Hover Animation'),
                'type' => ControlsManager::SELECT,
                'groups' => [
                    [
                        'label' => __('None'),
                        'options' => [
                            '' => __('None'),
                        ],
                    ],
                    [
                        'label' => __('Entrance'),
                        'options' => [
                            'enter-from-right' => 'Slide In Right',
                            'enter-from-left' => 'Slide In Left',
                            'enter-from-top' => 'Slide In Up',
                            'enter-from-bottom' => 'Slide In Down',
                            'enter-zoom-in' => 'Zoom In',
                            'enter-zoom-out' => 'Zoom Out',
                            'fade-in' => 'Fade In',
                        ],
                    ],
                    [
                        'label' => __('Exit'),
                        'options' => [
                            'exit-to-right' => 'Slide Out Right',
                            'exit-to-left' => 'Slide Out Left',
                            'exit-to-top' => 'Slide Out Up',
                            'exit-to-bottom' => 'Slide Out Down',
                            'exit-zoom-in' => 'Zoom In',
                            'exit-zoom-out' => 'Zoom Out',
                            'fade-out' => 'Fade Out',
                        ],
                    ],
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'overlay_effect_duration',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'render_type' => 'template',
                'default' => [
                    'size' => 800,
                ],
                'range' => [
                    'px' => [
                        'max' => 3000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-gallery-overlay' => 'transition-duration: {{SIZE}}ms',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_caption',
            [
                'label' => __('Caption'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'gallery_display_caption!' => 'none',
                ],
            ]
        );

        $this->addControl(
            'caption_position',
            [
                'label' => __('Position'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'outside' => __('Outside'),
                    'inside' => __('Inside'),
                ],
                'default' => 'outside',
                'prefix_class' => 'ce-image-gallery--caption-',
            ]
        );

        $this->addResponsiveControl(
            'caption_space',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .ce-gallery-item' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'caption_position' => 'outside',
                ],
            ]
        );

        $this->addControl(
            'caption_vertical_align',
            [
                'label' => __('Vertical Align'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'top' => [
                        'title' => __('Top'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => __('Middle'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __('Bottom'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} figure' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'caption_position' => 'inside',
                ],
            ]
        );

        $this->addControl(
            'caption_horizontal_align',
            [
                'label' => __('Horizontal Align'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'stretch' => [
                        'title' => __('Stretch'),
                        'icon' => 'eicon-h-align-stretch',
                    ],
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
                'condition' => [
                    'caption_position' => 'inside',
                ],
                'prefix_class' => 'ce-image-gallery--align-',
                'selectors' => [
                    '{{WRAPPER}} figure' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} figcaption' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'align',
            [
                'label' => __('Text Align'),
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
                    '{{WRAPPER}} figcaption' => 'text-align: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'caption_position',
                            'value' => 'outside',
                        ],
                        [
                            'name' => 'caption_horizontal_align',
                            'value' => 'stretch',
                        ],
                    ],
                ],
            ]
        );

        $this->startControlsTabs(
            'caption_tabs',
            [
                'condition' => [
                    'gallery_display_caption' => 'description',
                ],
            ]
        );

        $this->startControlsTab(
            'caption_tab_title',
            [
                'label' => __('Title'),
            ]
        );

        $this->addControl(
            'text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} figcaption, {{WRAPPER}} figcaption a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'gallery_display_caption' => ['', 'description'],
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} figcaption, {{WRAPPER}} figcaption a',
                'condition' => [
                    'gallery_display_caption' => ['', 'description'],
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} figcaption, {{WRAPPER}} figcaption a',
                'condition' => [
                    'gallery_display_caption' => ['', 'description'],
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'caption_tab_description',
            [
                'label' => __('Description'),
            ]
        );

        $this->addControl(
            'description_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} figcaption:after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} figcaption:after',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'description_text_shadow',
                'selector' => '{{WRAPPER}} figcaption:after',
            ]
        );

        $this->addResponsiveControl(
            'description_line_clamp',
            [
                'label' => __('Max Lines'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'selectors' => [
                    '{{WRAPPER}} figcaption:after' => '-webkit-line-clamp: {{VALUE}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'description_space',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} figcaption' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'separator_caption',
            [
                'type' => ControlsManager::DIVIDER,
                'condition' => [
                    'gallery_display_caption' => 'description',
                ],
            ]
        );

        $this->addControl(
            'caption_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} figcaption' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBackdropFilter::getType(),
            [
                'name' => 'backdrop_filters',
                'selector' => '{{WRAPPER}} figcaption',
                'condition' => [
                    'caption_position' => 'inside',
                ],
            ]
        );

        $this->addControl(
            'caption_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} figcaption' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'caption_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} figcaption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'caption_margin',
            [
                'label' => __('Margin'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} figcaption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'caption_horizontal_align!' => 'stretch',
                ],
            ]
        );

        $this->addControl(
            'caption_animation',
            [
                'label' => __('Hover Animation'),
                'type' => ControlsManager::SELECT,
                'groups' => [
                    [
                        'label' => __('None'),
                        'options' => [
                            '' => __('None'),
                        ],
                    ],
                    [
                        'label' => 'Entrance',
                        'options' => [
                            'fade-from-right' => 'Fade In Right',
                            'fade-from-left' => 'Fade In Left',
                            'fade-from-top' => 'Fade In Up',
                            'fade-from-bottom' => 'Fade In Down',
                            'enter-zoom-in' => 'Zoom In',
                            'enter-zoom-out' => 'Zoom Out',
                            'fade-in' => 'Fade In',
                        ],
                    ],
                    [
                        'label' => 'Reaction',
                        'options' => [
                            'grow' => 'Grow',
                            'shrink' => 'Shrink',
                            'move-right' => 'Move Right',
                            'move-left' => 'Move Left',
                            'move-up' => 'Move Up',
                            'move-down' => 'Move Down',
                        ],
                    ],
                    [
                        'label' => 'Exit',
                        'options' => [
                            'fade-to-right' => 'Fade Out Right',
                            'fade-to-left' => 'Fade Out Left',
                            'fade-to-top' => 'Fade Out Up',
                            'fade-to-bottom' => 'Fade Out Down',
                            'exit-zoom-in' => 'Zoom In',
                            'exit-zoom-out' => 'Zoom Out',
                            'fade-out' => 'Fade Out',
                        ],
                    ],
                ],
                'separator' => 'before',
                'condition' => [
                    'caption_position' => 'inside',
                ],
            ]
        );

        $this->addControl(
            'caption_animation_offset',
            [
                'label' => __('Offset'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}}' => '--ce-caption-animation-offset: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'caption_animation' => [
                        'fade-from-right',
                        'fade-from-left',
                        'fade-from-top',
                        'fade-from-bottom',
                        'fade-to-right',
                        'fade-to-left',
                        'fade-to-top',
                        'fade-to-bottom',
                    ],
                    'caption_position' => 'inside',
                ],
            ]
        );

        $this->addControl(
            'caption_effect_duration',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'render_type' => 'template',
                'default' => [
                    'size' => 800,
                ],
                'range' => [
                    'px' => [
                        'max' => 3000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} figcaption' => 'transition-duration: {{SIZE}}ms',
                ],
                'condition' => [
                    'caption_position' => 'inside',
                ],
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render image gallery widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        if (!$gallery = $settings['gallery']) {
            return;
        }
        $image_hover_animation = $settings['image_hover_animation'];
        $overlay_animation = $settings['overlay_animation'];
        $caption_animation = $settings['caption_animation'];
        $image_animated_class = '';

        if (strpos($image_hover_animation, 'move') === 0 || strpos($image_hover_animation, 'zoom') === 0) {
            $image_animated_class = ' elementor-bg-transform-' . $image_hover_animation;
        } elseif ($image_hover_animation) {
            $image_animated_class = ' elementor-animation-' . $image_hover_animation;
        }
        $animated_class = !empty($overlay_animation) || !empty($caption_animation) ? ' elementor-animated-content' : '';

        empty($settings['gallery_rand']) || shuffle($gallery);

        'file' === $settings['gallery_link'] && $this->addRenderAttribute('link', [
            'data-elementor-open-lightbox' => $settings['open_lightbox'],
            'data-elementor-lightbox-slideshow' => !empty($settings['lightbox_group']) ? $settings['lightbox_group'] : $this->getId(),
        ]);

        if (Plugin::$instance->editor->isEditMode()) {
            $this->addRenderAttribute('link', 'class', 'elementor-clickable');
        } ?>
        <div class="ce-image-gallery">
        <?php foreach ($gallery as $i => &$item) { ?>
            <?php if (!empty($item['image']['url'])) {
                $style = [];
                empty($item['cols']) || $style[] = '--ce-col-span: ' . $item['cols'];
                empty($item['rows']) || $style[] = '--ce-row-span: ' . $item['rows'];
                empty($item['cols_tablet']) || $style[] = '--ce-col-span-tablet: ' . $item['cols_tablet'];
                empty($item['rows_tablet']) || $style[] = '--ce-row-span-tablet: ' . $item['rows_tablet'];
                empty($item['cols_mobile']) || $style[] = '--ce-col-span-mobile: ' . $item['cols_mobile'];
                empty($item['rows_mobile']) || $style[] = '--ce-row-span-mobile: ' . $item['rows_mobile'];
                ($link = $this->getLinkUrl($item, $settings['gallery_link'])) && $this->addLinkAttributes($link_i = "link_$i", $link);
                $caption = $link && '' !== $item['caption'] ? "<a {$this->getRenderAttributeString('link')} {$this->getRenderAttributeString($link_i)}>{$item['caption']}</a>" : $item['caption'];
                ?>
                <figure class="ce-gallery-item<?php echo $animated_class; ?>"<?php $style && print ' style="' . implode('; ', $style) . '"'; ?>>
                    <div class="ce-gallery-icon<?php echo $image_animated_class; ?>">
                        <?php $link && print "<a {$this->getRenderAttributeString('link')} {$this->getRenderAttributeString($link_i)}>"; ?>
                        <?php echo GroupControlImageSize::getAttachmentImageHtml($item, 'image', 'elementor-bg'); ?>
                        <?php $link && print '</a>'; ?>
                    <?php if (!empty($settings['overlay'])) { ?>
                        <div class="ce-gallery-overlay elementor-animated-item--<?php echo $overlay_animation; ?>"></div>
                    <?php } ?>
                    </div>
                    <figcaption class="ce-gallery-caption elementor-animated-item--<?php echo $caption_animation; ?>"
                        <?php $item['description'] && print 'aria-description="' . esc_attr($item['description']) . '"'; ?>><?php echo $caption; ?></figcaption>
                </figure>
            <?php } ?>
        <?php } ?>
        </div>
        <?php
    }

    /**
     * Render image gallery widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 2.9.0
     */
    protected function contentTemplate()
    {
        ?>
        <#
        function getLinkUrl(attachment, linkTo) {
            if ('none' === linkTo) {
                return false;
            }
            if ('custom' === linkTo) {
                if (!attachment || !attachment.link || !attachment.link.url) {
                    return false;
                }
                return attachment.link;
            }
            return !attachment || !attachment.image || !attachment.image.url ? false : {
                url: elementor.helpers.getMediaLink(attachment.image.url),
            };
        }
        var gallery = !settings.gallery_rand ? settings.gallery : settings.gallery && settings.gallery.sort(function shuffle() {
                return Math.random() > 0.5 ? 1 : -1;
            }),
            imageAnimatedClass = (!settings.image_hover_animation.indexOf('move') || !settings.image_hover_animation.indexOf('zoom')
                ? 'elementor-bg-transform-'
                : 'elementor-animation-'
            ) + settings.image_hover_animation,
            animatedClass = settings.overlay_animation || settings.caption_animation ? 'elementor-animated-content' : '',
            link;

        'file' === settings.gallery_link && view.addRenderAttribute('link', {
            'data-elementor-open-lightbox': settings.open_lightbox,
            'data-elementor-lightbox-slideshow': settings.lightbox_group || view.getID(),
        });
        view.addRenderAttribute('link', 'class', 'elementor-clickable');
        #>
        <div class="ce-image-gallery">
        <# gallery.length && gallery.forEach(function (item) { #>
            <# if (item.image && item.image.url) {
                var style = [];
                item.cols > 1 && style.push('--ce-col-span: ' + item.cols);
                item.rows > 1 && style.push('--ce-row-span: ' + item.rows);
                item.cols_tablet > 1 && style.push('--ce-col-span-tablet: ' + item.cols_tablet);
                item.rows_tablet > 1 && style.push('--ce-row-span-tablet: ' + item.rows_tablet);
                item.cols_mobile > 1 && style.push('--ce-col-span-mobile: ' + item.cols_mobile);
                item.rows_mobile > 1 && style.push('--ce-row-span-mobile: ' + item.rows_mobile);
                #>
                <figure class="ce-gallery-item {{ animatedClass }}" {{{ style.length ? 'style="' + style.join('; ') + '"' : '' }}}>
                    <div class="ce-gallery-icon {{ imageAnimatedClass }}">
                    <# if (link = getLinkUrl(item, settings.gallery_link)) { #>
                        <a {{{ view.getRenderAttributeString('link') }}} href="{{ link.url }}">
                    <# } #>
                        <img class="elementor-bg" src="{{ elementor.helpers.getMediaLink(item.image.url) }}" loading="lazy">
                    <# if (link) { #>
                        </a>
                    <# } #>
                    <# if (settings.overlay) { #>
                        <div class="ce-gallery-overlay elementor-animated-item--{{ settings.overlay_animation }}"></div>
                    <# } #>
                    </div>
                    <figcaption class="ce-gallery-caption elementor-animated-item--{{ settings.caption_animation }}"
                        <# if (item.description) { #>aria-description="{{ item.description }}"<# } #>><#
                        if (link) {
                            #><a {{{ view.getRenderAttributeString('link') }}} href="{{ link.url }}"><#
                        }
                        print(item.caption);
                        if (link) {
                            #></a><#
                        }
                    #></figcaption>
                </figure>
            <# } #>
        <# }); #>
        </div>
        <?php
    }

    /**
     * Retrieve image carousel link URL.
     *
     * @since 1.0.0
     *
     * @param array $attachment
     * @param object $instance
     *
     * @return array|string|false An array/string containing the attachment URL, or false if no link
     */
    private function getLinkUrl($attachment, $link_to)
    {
        if ('none' === $link_to) {
            return false;
        }

        if ('custom' === $link_to) {
            if (empty($attachment['link']['url'])) {
                return false;
            }

            return $attachment['link'];
        }

        return empty($attachment['image']['url']) ? false : [
            'url' => Helper::getMediaLink($attachment['image']['url']),
        ];
    }
}
