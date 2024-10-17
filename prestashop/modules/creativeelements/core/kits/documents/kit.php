<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2022 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

use CE\CoreXBaseXDocument as Document;
use CE\CoreXBaseXPageBase as PageBase;

class CoreXKitsXDocumentsXKit extends PageBase
{
    private $custom_colors_disabled;
    private $typography_schemes_disabled;

    public function __construct(array $data = [])
    {
        Document::__construct($data);

        $this->custom_colors_disabled = \Configuration::get('elementor_disable_color_schemes');
        $this->typography_schemes_disabled = \Configuration::get('elementor_disable_typography_schemes');
    }

    public static function isInitialDocument()
    {
        return \Tools::getValue('editor_post_id') === \Tools::getValue('initial_document_id', 0);
    }

    public function isEditableByCurrentUser()
    {
        return parent::isEditableByCurrentUser() && (
            !is_admin() || \Profile::getProfileAccess(
                \Context::getContext()->employee->id_profile,
                \Tab::getIdFromClassName('AdminCEThemes')
            )['edit'] === '1'
        );
    }

    public static function getProperties()
    {
        $properties = parent::getProperties();
        $uid = \Tools::getValue('uid');

        $properties['has_elements'] = $uid && UId::TEMPLATE === UId::parse($uid)->id_type || self::isInitialDocument() || Helper::isAdminImport();
        // $properties['show_in_finder'] = false;
        // $properties['show_on_admin_bar'] = false;
        // $properties['edit_capability'] = 'edit_theme_options';
        $properties['support_kit'] = true;

        return $properties;
    }

    public function getName()
    {
        return 'kit';
    }

    public static function getTitle()
    {
        return __('Theme Style');
    }

    public function getPermalink()
    {
        return \Context::getContext()->link->getModuleLink('creativeelements', 'preview', [], null, null, null, true);
    }

    public static function getEditorPanelConfig()
    {
        $config = parent::getEditorPanelConfig();
        $config['default_route'] = 'panel/global/style';

        return $config;
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['autoImportSettings'] = true;
        $config['default_route'] = 'templates/blocks';
        $config['category'] = 'style guide';

        return $config;
    }

    public function getExitToDashboardUrl()
    {
        $uid = uidval($this->getMainId());
        $url = \Context::getContext()->link->getAdminLink('AdminCEThemes', true, [], [
            'id_ce_template' => $uid->id,
            'updatece_template' => 1,
        ]);

        return $url;
    }

    public function getCssWrapperSelector()
    {
        return 'body.ce-kit-' . substr($this->getMainId(), 0, -6);
    }

    /**
     * @since 2.0.0
     */
    protected function _registerControls()
    {
        $this->registerDocumentControls();

        $this->addTypographySection();
        $this->addButtonsSection();
        $this->addIconsSection();
        $this->addImagesSection();
        $this->addFormFieldsSection();
        $this->addBodySection();
        $this->addLayoutSection();
        $this->addBreadcrumbSection();
        $this->addPageTitleSection();
        $this->addLightboxSection();

        Plugin::$instance->controls_manager->addCustomCssControls($this, ControlsManager::TAB_STYLE);
    }

    // protected function getPostStatuses()

    private function addSchemesNotice()
    {
        // Get the current section config (array - section id and tab) to use for creating a unique control ID and name
        $current_section = $this->getCurrentSection();

        if (!$this->custom_colors_disabled || !$this->typography_schemes_disabled) {
            $this->addControl(
                $current_section['section'] . '_schemes_notice',
                [
                    'name' => $current_section['section'] . '_schemes_notice',
                    'type' => ControlsManager::RAW_HTML,
                    'raw' => sprintf(
                        __('In order for Theme Style to affect all relevant elements, please disable Default Colors ' .
                            'and Fonts from the <a href="%s" target="_blank">Settings Page</a>.'),
                        \Context::getContext()->link->getAdminLink('AdminCESettings')
                    ),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                    'render_type' => 'ui',
                ]
            );
        }
    }

    private function addTypographySection()
    {
        $this->startControlsSection(
            'section_typography',
            [
                'label' => __('Typography'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addSchemesNotice();

        $this->addControl(
            'body_heading',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Body'),
            ]
        );

        $this->addControl(
            'body_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    '{{WRAPPER}}' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'body_typography',
            ]
        );

        $this->addResponsiveControl(
            'paragraph_spacing',
            [
                'label' => __('Paragraph Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} p' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'range' => [
                    'em' => [
                        'max' => 20,
                    ],
                ],
                'size_units' => ['px', 'em', 'vh'],
            ]
        );

        // Link Selectors
        $link_selector = 'a, .elementor a';
        $link_hover_selector = 'a:hover, .elementor a:hover';

        $this->addControl(
            'link_heading',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Link'),
                'separator' => 'before',
            ]
        );

        $this->startControlsTabs('tabs_link_style');

        $this->startControlsTab(
            'tab_link_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'link_normal_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $link_selector => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'link_normal_typography',
                'selector' => $link_selector,
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_link_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'link_hover_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $link_hover_selector => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'link_hover_typography',
                'selector' => $link_hover_selector,
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        // Headings.
        $this->addControl(
            'heading_heading',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Heading'),
                'separator' => 'before',
            ]
        );

        $this->startControlsTabs('tabs_heading_style');

        for ($i = 1; $i <= 6; ++$i) {
            $this->startControlsTab(
                "tab_heading_h$i",
                [
                    'label' => "H$i",
                ]
            );

            $this->addControl(
                "h{$i}_color",
                [
                    'label' => __('Color'),
                    'type' => ControlsManager::COLOR,
                    'dynamic' => [],
                    'selectors' => [
                        "{{WRAPPER}} h$i" => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->addGroupControl(
                GroupControlTypography::getType(),
                [
                    'name' => "h{$i}_typography",
                    'selector' => "{{WRAPPER}} h$i",
                ]
            );

            $this->endControlsTab();
        }

        $this->endControlsTabs();

        // Display for Headings.
        $this->addControl(
            'heading_display',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Display'),
                'separator' => 'before',
            ]
        );

        $this->startControlsTabs('tabs_display_style');

        foreach ([
            'small' => 'S',
            'medium' => 'M',
            'large' => 'L',
            'xl' => 'XL',
            'xxl' => 'XXL',
        ] as $size => $label) {
            $this->startControlsTab(
                "tab_display_$size",
                [
                    'label' => $label,
                ]
            );

            $this->addControl(
                "display_{$size}_color",
                [
                    'label' => __('Color'),
                    'type' => ControlsManager::COLOR,
                    'dynamic' => [],
                    'selectors' => [
                        "{{WRAPPER}} .ce-display-$size" => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->addGroupControl(
                GroupControlTypography::getType(),
                [
                    'name' => "display_{$size}_typography",
                    'selector' => "{{WRAPPER}} .ce-display-$size",
                ]
            );

            $this->endControlsTab();
        }

        $this->endControlsTabs();

        $this->endControlsSection();
    }

    private function addButtonsSection()
    {
        $this->startControlsSection(
            'section_buttons',
            [
                'label' => __('Buttons'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $buttons = [
            '' => __('Default'),
            'primary' => __('Primary'),
            'secondary' => __('Secondary'),
        ];
        $btns = [
            'btn' => __('Theme') . ' ' . __('Default'),
            'primary_btn' => __('Theme') . ' ' . __('Primary'),
            'secondary_btn' => __('Theme') . ' ' . __('Secondary'),
        ];
        $this->addControl(
            'button_type',
            [
                'type' => ControlsManager::SELECT,
                'groups' => [
                    'buttons' => [
                        'label' => 'Creative Elements',
                        'options' => &$buttons,
                    ],
                    'btns' => [
                        'label' => \Tools::ucFirst(_THEME_NAME_) . ' ' . __('Theme'),
                        'options' => &$btns,
                    ],
                ],
                'classes' => 'ce-btn-skin',
            ]
        );

        $this->addSchemesNotice();

        // CE Buttons
        foreach ($buttons as $type => $heading) {
            $button = $type ? $type . '_button' : 'button';
            $button_selector = ($type ? ".elementor-button-$type " : '') . '.elementor-button';
            $button_color_selector = preg_replace('/\.elementor-button(?!-)/', 'a$0:not(#e)', $button_selector);
            $button_hover_selector = "$button_selector:hover, $button_selector:focus";

            $this->addControl(
                "{$button}_heading",
                [
                    'type' => ControlsManager::HEADING,
                    'label' => "$heading " . __('Button'),
                    'condition' => [
                        'button_type' => $type,
                    ],
                ]
            );

            $this->addGroupControl(
                GroupControlTypography::getType(),
                [
                    'name' => "{$button}_typography",
                    'selector' => $button_selector,
                    'exclude' => ['font_size'],
                ]
            );

            $this->addGroupControl(
                GroupControlTextShadow::getType(),
                [
                    'name' => "{$button}_text_shadow",
                    'selector' => $button_selector,
                ]
            );

            $this->startControlsTabs("{$button}_tabs_style");

            $this->startControlsTab(
                "{$button}_tab_normal",
                [
                    'label' => __('Normal'),
                ]
            );

            $this->addControl(
                "{$button}_text_color",
                [
                    'label' => __('Text Color'),
                    'type' => ControlsManager::COLOR,
                    'dynamic' => [],
                    'selectors' => [
                        "$button_selector, $button_color_selector" => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->addControl(
                "{$button}_background_color",
                [
                    'label' => __('Background Color'),
                    'type' => ControlsManager::COLOR,
                    'dynamic' => [],
                    'selectors' => [
                        $button_selector => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->addGroupControl(
                GroupControlBoxShadow::getType(),
                [
                    'name' => "{$button}_box_shadow",
                    'selector' => $button_selector,
                ]
            );

            $this->addGroupControl(
                GroupControlBorder::getType(),
                [
                    'name' => "{$button}_border",
                    'selector' => $button_selector,
                    'fields_options' => [
                        'color' => [
                            'dynamic' => [],
                        ],
                    ],
                ]
            );

            $this->endControlsTab();

            $this->startControlsTab(
                "{$button}_tab_hover",
                [
                    'label' => __('Hover'),
                ]
            );

            $this->addControl(
                "{$button}_hover_text_color",
                [
                    'label' => __('Text Color'),
                    'type' => ControlsManager::COLOR,
                    'dynamic' => [],
                    'selectors' => [
                        "$button_hover_selector, $button_color_selector:hover, $button_color_selector:focus" => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->addControl(
                "{$button}_hover_background_color",
                [
                    'label' => __('Background Color'),
                    'type' => ControlsManager::COLOR,
                    'dynamic' => [],
                    'selectors' => [
                        $button_hover_selector => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->addGroupControl(
                GroupControlBoxShadow::getType(),
                [
                    'name' => "{$button}_hover_box_shadow",
                    'selector' => $button_hover_selector,
                ]
            );

            $this->addGroupControl(
                GroupControlBorder::getType(),
                [
                    'name' => "{$button}_hover_border",
                    'selector' => $button_hover_selector,
                    'fields_options' => [
                        'color' => [
                            'dynamic' => [],
                        ],
                    ],
                ]
            );

            $this->endControlsTab();

            $this->endControlsTabs();

            $this->addControl(
                "{$button}_size_heading",
                [
                    'label' => __('Size'),
                    'type' => ControlsManager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->startControlsTabs("{$button}_sizes_style");

            foreach ([
                'sm' => 'S',
                'md' => 'M',
                'lg' => 'L',
                'xl' => 'XL',
                'xs' => 'XS',
            ] as $size => $label) {
                $key = 'sm' === $size ? '' : "_$size";
                $button_size_selector = "$button_selector.elementor-size-$size";

                $this->startControlsTab(
                    "{$button}_size_$size",
                    [
                        'label' => $label,
                    ]
                );

                $this->addResponsiveControl(
                    "{$button}{$key}_typography_font_size",
                    [
                        'label' => _x('Font Size', 'Typography Control'),
                        'type' => ControlsManager::SLIDER,
                        'size_units' => ['px', 'em', 'rem', 'vw'],
                        'range' => [
                            'px' => [
                                'min' => 1,
                                'max' => 200,
                            ],
                            'vw' => [
                                'min' => 0.1,
                                'max' => 10,
                                'step' => 0.1,
                            ],
                        ],
                        'selectors' => [
                            $button_size_selector => 'font-size: {{SIZE}}{{UNIT}}',
                        ],
                    ]
                );

                $this->addResponsiveControl(
                    "{$button}{$key}_padding",
                    [
                        'label' => __('Padding'),
                        'type' => ControlsManager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors' => [
                            $button_size_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

                $this->addControl(
                    "{$button}{$key}_border_radius",
                    [
                        'label' => __('Border Radius'),
                        'type' => ControlsManager::DIMENSIONS,
                        'size_units' => ['px', '%'],
                        'selectors' => [
                            $button_size_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

                $this->endControlsTab();
            }

            $this->endControlsTabs();
        }

        // Theme Buttons
        foreach ($btns as $btn => $heading) {
            $btn_selector = 'btn' === $btn
                ? '.btn:not(.btn-primary, .btn-secondary)'
                : '.btn-' . str_replace('_btn', '', $btn);
            $btn_color_selector = "a$btn_selector";
            $btn_hover_selector = "$btn_selector:hover, $btn_selector:hover:active, $btn_selector:focus";

            $this->addControl(
                "{$btn}_heading",
                [
                    'type' => ControlsManager::HEADING,
                    'label' => "$heading " . __('Button'),
                    'condition' => [
                        'button_type' => $btn,
                    ],
                ]
            );

            $this->addGroupControl(
                GroupControlTypography::getType(),
                [
                    'name' => "{$btn}_typography",
                    'selector' => $btn_selector,
                ]
            );

            $this->addGroupControl(
                GroupControlTextShadow::getType(),
                [
                    'name' => "{$btn}_text_shadow",
                    'selector' => $btn_selector,
                ]
            );

            $this->startControlsTabs("{$btn}_tabs_style");

            $this->startControlsTab(
                "{$btn}_tab_normal",
                [
                    'label' => __('Normal'),
                ]
            );

            $this->addControl(
                "{$btn}_text_color",
                [
                    'label' => __('Text Color'),
                    'type' => ControlsManager::COLOR,
                    'dynamic' => [],
                    'selectors' => [
                        "$btn_selector, $btn_color_selector" => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->addControl(
                "{$btn}_background_color",
                [
                    'label' => __('Background Color'),
                    'type' => ControlsManager::COLOR,
                    'dynamic' => [],
                    'selectors' => [
                        $btn_selector => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->addGroupControl(
                GroupControlBoxShadow::getType(),
                [
                    'name' => "{$btn}_box_shadow",
                    'selector' => $btn_selector,
                ]
            );

            $this->addGroupControl(
                GroupControlBorder::getType(),
                [
                    'name' => "{$btn}_border",
                    'selector' => $btn_selector,
                    'fields_options' => [
                        'color' => [
                            'dynamic' => [],
                        ],
                    ],
                ]
            );

            $this->endControlsTab();

            $this->startControlsTab(
                "{$btn}_tab_hover",
                [
                    'label' => __('Hover'),
                ]
            );

            $this->addControl(
                "{$btn}_hover_text_color",
                [
                    'label' => __('Text Color'),
                    'type' => ControlsManager::COLOR,
                    'dynamic' => [],
                    'selectors' => [
                        "$btn_hover_selector, $btn_color_selector:hover, $button_color_selector:focus" => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->addControl(
                "{$btn}_hover_background_color",
                [
                    'label' => __('Background Color'),
                    'type' => ControlsManager::COLOR,
                    'dynamic' => [],
                    'selectors' => [
                        $btn_hover_selector => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->addGroupControl(
                GroupControlBoxShadow::getType(),
                [
                    'name' => "{$btn}_hover_box_shadow",
                    'selector' => $btn_hover_selector,
                ]
            );

            $this->addGroupControl(
                GroupControlBorder::getType(),
                [
                    'name' => "{$btn}_hover_border",
                    'selector' => $btn_hover_selector,
                    'fields_options' => [
                        'color' => [
                            'dynamic' => [],
                        ],
                    ],
                ]
            );

            $this->endControlsTab();

            $this->endControlsTabs();

            $this->addResponsiveControl(
                "{$btn}_padding",
                [
                    'label' => __('Padding'),
                    'type' => ControlsManager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        $btn_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->addControl(
                "{$btn}_border_radius",
                [
                    'label' => __('Border Radius'),
                    'type' => ControlsManager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        $btn_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }

        $this->endControlsSection();
    }

    private function addIconsSection()
    {
        $this->startControlsSection(
            'section_icons',
            [
                'label' => __('Icons'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('icon_colors');

        $this->startControlsTab(
            'icon_colors_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'icon_primary_color',
            [
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
                    '.elementor-view-framed .elementor-icon, .elementor-view-default .elementor-icon' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
            ]
        );

        $this->addControl(
            'icon_secondary_color',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'icon_colors_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'icon_hover_primary_color',
            [
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '.elementor-view-stacked .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '.elementor-view-framed .elementor-icon:hover, .elementor-view-default .elementor-icon:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'icon_hover_secondary_color',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '.elementor-view-framed .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '.elementor-view-stacked .elementor-icon:hover' => 'color: {{VALUE}};',
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
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '.elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'icon_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'em' => [
                        'max' => 5,
                    ],
                ],
                'selectors' => [
                    '.elementor-view-framed .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                    '.elementor-view-stacked .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '.elementor-view-framed .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '.elementor-view-framed.elementor-shape-square .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '.elementor-view-stacked.elementor-shape-square .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    private function addImagesSection()
    {
        // Image Selectors
        $image_selector = '{{WRAPPER}} img';
        $image_hover_selector = '{{WRAPPER}} img:hover';

        $this->startControlsSection(
            'section_images',
            [
                'label' => __('Images'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addSchemesNotice();

        $this->startControlsTabs('tabs_image_style');

        $this->startControlsTab(
            'tab_image_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'image_border',
                'selector' => $image_selector,
                'fields_options' => [
                    'color' => [
                        'dynamic' => [],
                    ],
                ],
            ]
        );

        $this->addResponsiveControl(
            'image_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    $image_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'image_opacity',
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
                    $image_selector => 'opacity: {{SIZE}};',
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
                'selector' => $image_selector,
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'image_css_filters',
                'selector' => '{{WRAPPER}} img',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_image_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'image_hover_border',
                'selector' => '{{WRAPPER}} img:hover',
                'fields_options' => [
                    'color' => [
                        'dynamic' => [],
                    ],
                ],
            ]
        );

        $this->addResponsiveControl(
            'image_hover_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    $image_hover_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'image_hover_opacity',
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
                    $image_hover_selector => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'image_hover_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => $image_hover_selector,
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'image_hover_css_filters',
                'selector' => $image_hover_selector,
            ]
        );

        $this->addControl(
            'image_hover_transition',
            [
                'label' => __('Transition Duration') . ' (s)',
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    $image_selector => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();
    }

    private function addFormFieldsSection()
    {
        // Use an array for better readability.
        $label_selector = '{{WRAPPER}} label';
        $input_selector = '{{WRAPPER}} .form-control, {{WRAPPER}} .elementor-field-textual';
        $input_focus_selector = '{{WRAPPER}} .form-control:focus, {{WRAPPER}} .elementor-field-textual:focus';

        $this->startControlsSection(
            'section_form_fields',
            [
                'label' => __('Form Fields'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addSchemesNotice();

        $this->addControl(
            'form_label_heading',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Label'),
            ]
        );

        $this->addControl(
            'form_label_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $label_selector => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'form_label_typography',
                'selector' => $label_selector,
            ]
        );

        $this->addControl(
            'form_field_heading',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Field'),
                'separator' => 'before',
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'form_field_typography',
                'selector' => $input_selector,
            ]
        );

        $this->startControlsTabs('tabs_form_field_style');

        $this->startControlsTab(
            'tab_form_field_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addFormFieldStateTabControls('form_field', $input_selector);

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_form_field_focus',
            [
                'label' => __('Focus'),
            ]
        );

        $this->addFormFieldStateTabControls('form_field_focus', $input_focus_selector);

        $this->addControl(
            'form_field_focus_transition_duration',
            [
                'label' => __('Transition Duration') . ' (ms)',
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    $input_selector => 'transition: {{SIZE}}ms',
                ],
                'range' => [
                    'px' => [
                        'max' => 3000,
                    ],
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addResponsiveControl(
            'form_field_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    $input_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    'select.form-control:not([size]):not([multiple])' => 'height: auto;',
                ],
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();
    }

    private function addBodySection()
    {
        $this->startControlsSection(
            'section_background',
            [
                'label' => __('Background'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addSchemesNotice();

        $this->startControlsTabs('background_tabs_style');

        foreach ([
            'body' => '{{WRAPPER}}',
            'wrapper' => '{{WRAPPER}} #wrapper',
            'content' => '{{WRAPPER}} #content',
        ] as $tab => $wrapper) {
            $this->startControlsTab(
                "tab_background_$tab",
                [
                    'label' => __(ucfirst($tab)),
                ]
            );

            $this->addGroupControl(
                GroupControlBackground::getType(),
                [
                    'name' => "{$tab}_background",
                    'types' => ['classic', 'gradient'],
                    'selector' => $wrapper,
                    'fields_options' => [
                        // 'background' => [
                        //     'frontend_available' => true,
                        // ],
                        'color' => [
                            'default' => 'wrapper' === $tab ? '#00000000' : '',
                            'dynamic' => [],
                        ],
                        'color_b' => [
                            'dynamic' => [],
                        ],
                    ],
                ]
            );

            $this->endControlsTab();
        }

        $this->endControlsTabs();

        $this->endControlsSection();
    }

    private function addLayoutSection()
    {
        $this->startControlsSection(
            'section_layout',
            [
                'label' => __('Layout'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'override_container',
            [
                'label' => __('Override Theme Container'),
                'type' => ControlsManager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .container' => 'width: {{container_width.SIZE}}{{container_width.UNIT}}; max-width: 100%;',
                    '(tablet){{WRAPPER}} .container' => 'width: {{container_width_tablet.SIZE}}{{container_width_tablet.UNIT}}',
                    '(mobile){{WRAPPER}} .container' => 'width: {{container_width_mobile.SIZE}}{{container_width_mobile.UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'container_width',
            [
                'label' => __('Content Width'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 300,
                        'max' => 1500,
                        'step' => 10,
                    ],
                    'vw' => [
                        'min' => 1,
                    ],
                ],
                'tablet_default' => [
                    'unit' => 'vw',
                ],
                'mobile_default' => [
                    'unit' => 'vw',
                ],
                'placeholder' => '1140',
                'device_args' => [
                    ControlsStack::RESPONSIVE_TABLET => [
                        'placeholder' => '',
                    ],
                    ControlsStack::RESPONSIVE_MOBILE => [
                        'placeholder' => '',
                    ],
                ],
                'description' => __('Sets the default width of the content area (Default: 1140)'),
                'selectors' => [
                    '.elementor-section.elementor-section-boxed > .elementor-container' => 'max-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'space_between_widgets',
            [
                'label' => __('Widgets Space') . ' (px)',
                'type' => ControlsManager::SLIDER,
                'placeholder' => '20',
                'range' => [
                    'px' => [
                        'max' => 40,
                    ],
                ],
                'description' => __('Sets the default space between widgets (Default: 20)'),
                'selectors' => [
                    '.elementor-widget:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsSection();
    }

    private function addBreadcrumbSection()
    {
        $this->startControlsSection(
            'section_breadcrumb',
            [
                'label' => __('Breadcrumb'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'breadcrumb_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb li:not(#e), {{WRAPPER}} .ce-breadcrumb__item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'breadcrumb_link_color',
            [
                'label' => __('Link Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb li a:not(#e), {{WRAPPER}} .ce-breadcrumb__item a:not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'breadcrumb_link_color_hover',
            [
                'label' => __('Link Hover Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb li a:not(#e):hover, {{WRAPPER}} .ce-breadcrumb__item a:not(#e):hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'breadcrumb_typography',
                'selector' => '{{WRAPPER}} .breadcrumb li:not(#e), {{WRAPPER}} .ce-breadcrumb__item',
            ]
        );

        $this->addResponsiveControl(
            'breadcrumb_align',
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
                    '{{WRAPPER}} .breadcrumb:not(#e), {{WRAPPER}} .elementor-row' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    private function addPageTitleSection()
    {
        $this->startControlsSection(
            'section_page_title',
            [
                'label' => __('Page Title'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $title_selector = implode(', ', array_map(
            function ($selector) {
                return "{{WRAPPER}} $selector, {{WRAPPER}} $selector *";
            },
            preg_split('/\s*,\s*/', \Configuration::get('elementor_page_title_selector'))
        )) . ', {{WRAPPER}} .ce-page-title *';

        $this->addControl(
            'title_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $title_selector => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography',
                'selector' => $title_selector,
            ]
        );

        $this->addResponsiveControl(
            'title_align',
            [
                'label' => __('Text Align'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
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
                    $title_selector => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    private function addLightboxSection()
    {
        $this->startControlsSection(
            'section_lightbox',
            [
                'label' => __('Lightbox'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'lightbox_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    '.elementor-lightbox' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'lightbox_ui_color',
            [
                'label' => __('UI Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    '.elementor-lightbox' => '--lightbox-ui-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'lightbox_ui_color_hover',
            [
                'label' => __('UI Hover Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    '.elementor-lightbox' => '--lightbox-ui-color-hover: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'lightbox_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    '.elementor-lightbox' => '--lightbox-text-color: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'lightbox_box_shadow',
                'selector' => '.elementor-lightbox .elementor-lightbox-image',
            ]
        );

        $this->addResponsiveControl(
            'lightbox_icons_size',
            [
                'label' => __('Toolbar Icons Size'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => \Configuration::get('lightbox_icons_size') ?: '',
                ],
                'selectors' => [
                    '.elementor-lightbox' => '--lightbox-header-icons-size: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'lightbox_slider_icons_size',
            [
                'label' => __('Navigation Icons Size'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => \Configuration::get('lightbox_slider_icons_size') ?: '',
                ],
                'selectors' => [
                    '.elementor-lightbox' => '--lightbox-navigation-icons-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsSection();
    }

    private function addFormFieldStateTabControls($prefix, $selector)
    {
        $this->addControl(
            $prefix . '_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $selector => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            $prefix . '_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $selector => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => $prefix . '_box_shadow',
                'selector' => $selector,
            ]
        );

        if ('form_field_focus' === $prefix) {
            $this->addControl(
                $prefix . '_outline',
                [
                    'label' => __('Outline'),
                    'type' => ControlsManager::POPOVER_TOGGLE,
                    'render_type' => 'ui',
                ]
            );

            $this->startPopover();

            $this->addControl(
                $prefix . '_outline_type',
                [
                    'label' => __('Type'),
                    'type' => ControlsManager::SELECT,
                    'options' => [
                        'solid' => _x('Solid', 'Border Control'),
                        'double' => _x('Double', 'Border Control'),
                        'dotted' => _x('Dotted', 'Border Control'),
                        'dashed' => _x('Dashed', 'Border Control'),
                        'groove' => _x('Groove', 'Border Control'),
                    ],
                    'default' => 'solid',
                    'selectors' => [
                        $selector => 'outline-style: {{VALUE}};',
                    ],
                    'condition' => [
                        $prefix . '_outline!' => '',
                    ],
                ]
            );

            $this->addControl(
                $prefix . '_outline_color',
                [
                    'label' => __('Color'),
                    'type' => ControlsManager::COLOR,
                    'dynamic' => [],
                    'selectors' => [
                        $selector => 'outline-color: {{VALUE}};',
                    ],
                    'condition' => [
                        $prefix . '_outline!' => '',
                    ],
                ]
            );

            $this->addResponsiveControl(
                $prefix . 'outline_width',
                [
                    'label' => __('Width') . ' (px)',
                    'type' => ControlsManager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 20,
                        ],
                    ],
                    'selectors' => [
                        $selector => 'outline-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        $prefix . '_outline!' => '',
                    ],
                ]
            );

            $this->endPopover();
        }

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => $prefix . '_border',
                'selector' => $selector,
                'fields_options' => [
                    'color' => [
                        'dynamic' => [],
                    ],
                ],
            ]
        );

        $this->addControl(
            $prefix . '_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
    }

    protected function saveElements($elements)
    {
        if (self::isInitialDocument() || Helper::isAdminImport()) {
            parent::saveElements($elements);
        }
    }

    protected function saveSettings($settings)
    {
        $old_settings = $this->getMeta(static::PAGE_META_KEY);

        if (!empty($old_settings['custom_colors'])) {
            $settings['custom_colors'] = $old_settings['custom_colors'];
        }
        unset($settings['button_type']);

        parent::saveSettings($settings);
    }
}
