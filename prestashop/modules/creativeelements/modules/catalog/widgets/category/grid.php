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

class ModulesXCatalogXWidgetsXCategoryXGrid extends WidgetImageGallery
{
    private $properties;

    public function getName()
    {
        return 'category-grid';
    }

    public function getTitle()
    {
        return isset($this->properties['title']) ? $this->properties['title'] : __('Category Grid');
    }

    public function getIcon()
    {
        return 'eicon-gallery-grid';
    }

    public function getCategories()
    {
        return isset($this->properties['categories']) ? $this->properties['categories'] : ['catalog'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'grid', 'listing', 'category', 'images'];
    }

    protected function isDynamicContent()
    {
        return true;
    }

    protected function _registerControls()
    {
        // Tmp fix: Start tabs with Content tab
        \Closure::bind(function ($stack_id) {
            $this->stacks[$stack_id]['tabs']['content'] = __('Content');
        }, Plugin::$instance->controls_manager, 'CE\ControlsManager')->__invoke($this->getUniqueName());

        $this->startControlsSection(
            'section_title_style',
            [
                'label' => __('Title'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'title_text!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'title_align',
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
                    '{{WRAPPER}} .elementor-heading-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->addGroupControl(
            GroupControlTextStroke::getType(),
            [
                'name' => 'title_text_stroke',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'title_text_shadow',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->addResponsiveControl(
            'title_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        parent::_registerControls();

        $this->updateControl('section_gallery', [
            'label' => $this->getTitle(),
        ]);

        $this->updateControl('links', [
            'raw' => '
                <style>
                .elementor-control-show_caption.elementor-control-type-switcher { display: none; }
                </style>',
            'condition' => null,
        ]);

        $this->startInjection([
            'of' => 'links',
        ]);

        $this->addControl(
            'title_text',
            [
                'label' => __('Title'),
                'classes' => 'elementor-control-type-heading',
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Enter your title'),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->addControl(
            'title_display',
            [
                'label' => __('Display'),
                'type' => ControlsManager::CHOOSE,
                'options' => WidgetHeading::getDisplaySizes(),
                'style_transfer' => true,
                'condition' => [
                    'title_text!' => '',
                ],
            ]
        );

        $this->addControl(
            'title_tag',
            [
                'label' => __('HTML Tag'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h3',
                'condition' => [
                    'title_text!' => '',
                ],
            ]
        );

        $this->endInjection();

        $this->updateControl('gallery', [
            'label' => __('Grid'),
            'classes' => 'elementor-control-type-heading',
            'dynamic' => [
                'active' => true,
                'default' => Plugin::$instance->dynamic_tags->tagDataToTagText(null, 'category-images'),
            ],
            'default' => [],
            'separator' => 'before',
        ]);

        $this->updateResponsiveControl('gallery_columns', [
            'desktop_default' => 6,
            'tablet_default' => 4,
        ]);

        $this->updateControl('layout', [
            'type' => ControlsManager::HIDDEN,
        ]);

        $this->updateControl('gallery_link', [
            'type' => ControlsManager::HIDDEN,
            'default' => 'custom',
        ]);

        $this->removeControl('gallery_rand');

        $this->updateControl('image_spacing_custom', [
            'default' => [
                'size' => 10,
            ],
        ]);

        $this->updateControl('object-fit', [
            'default' => 'contain',
        ]);

        $this->updateControl('caption_space', [
            'default' => [
                'size' => 10,
            ],
        ]);
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-heading elementor-widget-image-gallery';
    }

    public function render()
    {
        $settings = $this->getSettingsForDisplay();

        if ('' !== $settings['title_text'] && $settings['gallery']) {
            $this->addRenderAttribute('title_text', 'class', 'elementor-heading-title');
            $settings['title_display'] && $this->addRenderAttribute('title_text', 'class', 'ce-display-' . $settings['title_display']);
            $this->addInlineEditingAttributes('title_text', 'none');

            echo "<{$settings['title_tag']} {$this->getRenderAttributeString('title_text')}>{$settings['title_text']}</{$settings['title_tag']}>";
        }

        parent::render();
    }

    protected function contentTemplate()
    {
        ?>
        <# if (settings.title_text) {
            var title_tag = settings.title_tag;

            view.addRenderAttribute( 'title_text', 'class', ['elementor-heading-title', 'ce-display-' + settings.title_display] );

            view.addInlineEditingAttributes( 'title_text' );
            #>
            <{{ title_tag }} {{{ view.getRenderAttributeString( 'title_text' ) }}}>{{{ settings.title_text }}}</{{ title_tag }}>
        <# } #>
        <?php
        parent::contentTemplate();
    }

    public function renderPlainContent()
    {
    }

    public function __construct(array $data = [], $args = null, array $properties = [])
    {
        $this->properties = $properties;

        parent::__construct($data, $args);
    }
}
