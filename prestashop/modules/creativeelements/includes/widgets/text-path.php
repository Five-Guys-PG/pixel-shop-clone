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
 * Elementor WordArt widget.
 *
 * Elementor widget that displays text along SVG path.
 */
class WidgetTextPath extends WidgetBase
{
    const DEFAULT_PATH_FILL = '#E8178A';

    const SVG_PATHS = [
        'arc' => [
            'svg' => ['width' => 250.5, 'height' => 125.25, 'viewBox' => '0 0 250.5 125.25'],
            'path' => ['d' => 'M.25,125.25a125,125,0,0,1,250,0'],
        ],
        'circle' => [
            'svg' => ['width' => 250.5, 'height' => 250.5, 'viewBox' => '0 0 250.5 250.5'],
            'path' => ['d' => 'M.25,125.25a125,125,0,1,1,125,125,125,125,0,0,1-125-125'],
        ],
        'line' => [
            'svg' => ['width' => 250, 'height' => 22, 'viewBox' => '0 0 250 22'],
            'path' => ['d' => 'M 0 27 l 250 -22'],
        ],
        'oval' => [
            'svg' => ['width' => 250.5, 'height' => 125.75, 'viewBox' => '0 0 250.5 125.75'],
            'path' => ['d' => 'M.25,62.875C.25,28.2882,56.2144.25,125.25.25s125,28.0382,125,62.625-55.9644,62.625-125,62.625S.25,97.4619.25,62.875'],
        ],
        'spiral' => [
            'svg' => ['width' => 250.435, 'height' => 239.445, 'viewBox' => '0 0 250.435 239.445'],
            'path' => ['d' => 'M.185,49.022a149.349,149.349,0,0,1,210.982-9.824,119.48,119.48,0,0,1,7.861,168.79A95.583,95.583,0,0,1,84,214.27a76.467,76.467,0,0,1-5.031-108.023'],
        ],
        'wave' => [
            'svg' => ['width' => 250, 'height' => 42.5, 'viewBox' => '0 0 250 42.5'],
            'path' => ['d' => 'M0,42.25C62.5,42.25,62.5.25,125,.25s62.5,42,125,42'],
        ],
    ];

    /**
     * Get widget name.
     *
     * Retrieve Text Path widget name.
     *
     * @return string Widget name
     */
    public function getName()
    {
        return 'text-path';
    }

    /**
     * Get widget title.
     *
     * Retrieve Text Path widget title.
     *
     * @return string Widget title
     */
    public function getTitle()
    {
        return __('Text Path');
    }

    /**
     * Get widget icon.
     *
     * Retrieve Text Path widget icon.
     *
     * @return string Widget icon
     */
    public function getIcon()
    {
        return 'eicon-wordart';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @return array Widget keywords
     */
    public function getKeywords()
    {
        return ['text path', 'word path', 'text on path', 'wordart', 'word art'];
    }

    /**
     * Register content controls under content tab.
     */
    protected function registerContentTab()
    {
        $this->startControlsSection(
            'section_content_text_path',
            [
                'label' => __('Text Path'),
                'tab' => ControlsManager::TAB_CONTENT,
            ]
        );

        $this->addControl(
            'text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'label_block' => true,
                'default' => __('Add Your Curvy Text Here'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->addControl(
            'path',
            [
                'label' => __('Path Type'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'wave' => __('Wave'),
                    'arc' => __('Arc'),
                    'circle' => __('Circle'),
                    'line' => __('Line'),
                    'oval' => __('Oval'),
                    'spiral' => __('Spiral'),
                    'custom' => __('Custom'),
                ],
                'default' => 'wave',
            ]
        );

        $this->addControl(
            'custom_path_width',
            [
                'label' => __('Width'),
                'type' => ControlsManager::NUMBER,
                'min' => 0,
                'default' => 250,
                'condition' => [
                    'path' => 'custom',
                ],
            ]
        );

        $this->addControl(
            'custom_path_height',
            [
                'label' => __('Height'),
                'type' => ControlsManager::NUMBER,
                'min' => 0,
                'default' => 42.5,
                'condition' => [
                    'path' => 'custom',
                ],
            ]
        );

        $this->addControl(
            'custom_path_data',
            [
                'label' => __('Path'),
                'type' => ControlsManager::TEXTAREA,
                'default' => "M 0,42.25\nC 62.5,42.25 62.5,0.25 125,0.25\ns 62.5,42 125,42",
                'description' => __('Want to create custom text paths?') . sprintf(
                    ' <a href="https://w3.org/TR/SVG/paths.html#PathData" target="_blank">%s</a>',
                    __('Learn More')
                ),
                'condition' => [
                    'path' => 'custom',
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
                'placeholder' => __('Paste URL or type'),
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
                    '{{WRAPPER}}' => '--alignment: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'text_path_direction',
            [
                'label' => __('Text Direction'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'rtl' => __('RTL'),
                    'ltr' => __('LTR'),
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--direction: {{VALUE}}',
                ],
                'render_type' => 'template',
            ]
        );

        $this->addControl(
            'show_path',
            [
                'label' => __('Show Path'),
                'type' => ControlsManager::SWITCHER,
                'return_value' => self::DEFAULT_PATH_FILL,
                'selectors' => [
                    '{{WRAPPER}}' => '--path-stroke: {{VALUE}}; --path-fill: transparent;',
                ],
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Register style controls under style tab.
     */
    protected function registerStyleTab()
    {
        /*
         * Text Path styling section.
         */
        $this->startControlsSection(
            'section_style_text_path',
            [
                'label' => __('Text Path'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    'px' => [
                        'max' => 800,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'rotation',
            [
                'label' => __('Rotate'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['deg'],
                'default' => [
                    'unit' => 'deg',
                ],
                'tablet_default' => [
                    'unit' => 'deg',
                ],
                'mobile_default' => [
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--rotate: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'text_heading',
            [
                'label' => __('Text'),
                'type' => ControlsManager::HEADING,
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'text_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'exclude' => ['line_height'],
                'fields_options' => [
                    'font_size' => [
                        'size_units' => ['px'],
                    ],
                    // Text decoration isn't an inherited property, so it's required to explicitly
                    // target the specific `textPath` element.
                    'text_decoration' => [
                        'selectors' => [
                            '{{WRAPPER}} textPath' => 'text-decoration: {{VALUE}}',
                        ],
                    ],
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTextStroke::getType(),
            [
                'name' => 'text_stroke',
                'selector' => '{{WRAPPER}} textPath',
            ]
        );

        $this->addResponsiveControl(
            'word_spacing',
            [
                'label' => __('Word Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -20,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--word-spacing: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'start_point',
            [
                'label' => __('Starting Point'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['%'],
                'default' => [
                    'unit' => '%',
                ],
            ]
        );

        $this->startControlsTabs('text_style');

        /*
         * Normal tab.
         */
        $this->startControlsTab(
            'text_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'text_color_normal',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--text-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        /*
         * Hover tab.
         */
        $this->startControlsTab(
            'text_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'text_color_hover',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--text-color-hover: {{VALUE}}',
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

        $this->addControl(
            'hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['s'],
                'range' => [
                    's' => [
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 's',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--transition: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        /*
         * Path styling section.
         */
        $this->startControlsSection(
            'section_style_path',
            [
                'label' => __('Path'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'show_path!' => '',
                ],
            ]
        );

        $this->startControlsTabs('path_style');

        /*
         * Normal tab.
         */
        $this->startControlsTab(
            'path_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'path_fill_normal',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--path-fill: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'stroke_heading_normal',
            [
                'label' => __('Stroke'),
                'type' => ControlsManager::HEADING,
            ]
        );

        $this->addControl(
            'stroke_color_normal',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'default' => self::DEFAULT_PATH_FILL,
                'selectors' => [
                    '{{WRAPPER}}' => '--stroke-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'stroke_width_normal',
            [
                'label' => __('Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--stroke-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsTab();

        /*
         * Hover tab.
         */
        $this->startControlsTab(
            'path_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'path_fill_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--path-fill-hover: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'stroke_heading_hover',
            [
                'label' => __('Stroke'),
                'type' => ControlsManager::HEADING,
            ]
        );

        $this->addControl(
            'stroke_color_hover',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--stroke-color-hover: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'stroke_width_hover',
            [
                'label' => __('Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--stroke-width-hover: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'stroke_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['s'],
                'range' => [
                    's' => [
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 's',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--stroke-transition: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();
    }

    /**
     * Register Text Path widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     */
    protected function _registerControls()
    {
        $this->registerContentTab();
        $this->registerStyleTab();
    }

    /**
     * Render Text Path widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $path_id = 'e-path-' . $this->getId();
        $is_rtl = $settings['text_path_direction'] ? 'rtl' === $settings['text_path_direction'] : is_rtl();
        $start_offset = $is_rtl ? 100 - (int) $settings['start_point']['size'] : (int) $settings['start_point']['size'];
        $text = esc_html($settings['text']);

        $this->addRenderAttribute('svg', 'class', 'ce-text-path');
        $this->addRenderAttribute('path', 'id', $path_id);
        $this->addRenderAttribute('custom' !== $settings['path'] ? self::SVG_PATHS[$settings['path']] : [
            'svg' => [
                'width' => $settings['custom_path_width'],
                'height' => $settings['custom_path_height'],
                'viewBox' => "0 0 {$settings['custom_path_width']} {$settings['custom_path_height']}",
            ],
            'path' => ['d' => $settings['custom_path_data']],
        ]);

        if ($has_link = !empty($settings['link']['url'])) {
            $this->addRenderAttribute('link', 'href', $settings['link']['url']);

            empty($settings['link']['is_external']) || $this->addRenderAttribute('link', 'target', '_blank');
            empty($settings['link']['nofollow']) || $this->addRenderAttribute('link', 'rel', 'nofollow');
        }
        if ($settings['hover_animation']) {
            $this->addRenderAttribute('svg', 'class', 'elementor-animation-' . $settings['hover_animation']);
        } ?>
        <svg <?php $this->printRenderAttributeString('svg'); ?>>
            <path <?php $this->printRenderAttributeString('path'); ?>/>
            <text>
                <textPath href="#<?php echo $path_id; ?>" startOffset="<?php echo $start_offset; ?>%">
                    <?php echo $has_link ? "<a {$this->getRenderAttributeString('link')}>$text</a>" : $text; ?>
                </textPath>
            </text>
        </svg>
        <?php
    }

    /**
     * Render Text Path widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     */
    protected function contentTemplate()
    {
        ?>
        <#
        var SVG_PATHS = <?php echo json_encode(self::SVG_PATHS); ?>,
            pathId = 'e-path-' + view.getID(),
            isRtl = settings.text_path_direction ? 'rtl' === settings.text_path_direction : <?php echo (int) is_rtl(); ?>,
            startOffset = isRtl ? 100 - settings.start_point.size : settings.start_point.size;

        view.addRenderAttribute('svg', 'class', 'ce-text-path');
        view.addRenderAttribute('path', 'id', pathId);
        view.addRenderAttribute('custom' !== settings.path ? SVG_PATHS[settings.path] : {
            svg: {
                width: settings.custom_path_width,
                height: settings.custom_path_height,
                viewBox: '0 0 ' + settings.custom_path_width + ' ' + settings.custom_path_height
            },
            path: {d: settings.custom_path_data}
        });

        if (settings.hover_animation) {
            view.addRenderAttribute('svg', 'class', 'elementor-animation-' + settings.hover_animation);
        }
        #>
        <svg {{{ view.getRenderAttributeString('svg') }}}>
            <path {{{ view.getRenderAttributeString('path') }}}/>
            <text>
                <textPath href="#{{ pathId }}" startOffset="{{ startOffset || 0 }}%">
                <# if (settings.link.url) { #>
                    <a href="{{ settings.link.url }}">{{ settings.text }}</a>
                <# } else print(_.escape(settings.text)) #>
                </textPath>
            </text>
        </svg>
        <?php
    }
}
