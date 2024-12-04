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

use CE\CoreXResponsiveXResponsive as Responsive;

class ModulesXCatalogXWidgetsXListingXFilters extends WidgetBase
{
    const REMOTE_RENDER = true;

    protected $context;

    protected $translator;

    public function getName()
    {
        return 'listing-filters';
    }

    public function getTitle()
    {
        return __('Filters');
    }

    public function getIcon()
    {
        return 'eicon-checkbox';
    }

    public function getCategories()
    {
        return ['listing-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'category', 'products', 'listing', 'filters'];
    }

    protected function getFacetType(&$facet)
    {
        return isset($facet['properties']['id_attribute_group']) ? "attribute_group:{$facet['properties']['id_attribute_group']}" : (
            isset($facet['properties']['id_feature']) ? "feature:{$facet['properties']['id_feature']}" : $facet['type']
        );
    }

    public static function isColorLight($color)
    {
        $r = hexdec($color[1] . $color[2]);
        $g = hexdec($color[3] . $color[4]);
        $b = hexdec($color[5] . $color[5]);

        return 0.8 <= (max($r, $g, $b) + min($r, $g, $b)) / 510;
    }

    protected function getFacetTypeOptions()
    {
        $options = [
            'availability' => $this->translator->trans('Availability', [], 'Modules.Facetedsearch.Shop'),
            'manufacturer' => $this->translator->trans('Brand', [], 'Modules.Facetedsearch.Shop'),
            'category' => $this->translator->trans('Categories', [], 'Modules.Facetedsearch.Shop'),
            'condition' => $this->translator->trans('Condition', [], 'Modules.Facetedsearch.Shop'),
            'price' => $this->translator->trans('Price', [], 'Modules.Facetedsearch.Shop'),
            'extras' => $this->translator->trans('Selections', [], 'Modules.Facetedsearch.Shop'),
            'weight' => $this->translator->trans('Weight', [], 'Modules.Facetedsearch.Shop'),
        ];
        $db = \Db::getInstance();
        $id_lang = $this->context->language->id;

        if (\Combination::isFeatureActive() && $rows = $db->executeS(
            'SELECT ag.`id_attribute_group` AS id, agl.`name` FROM ' . _DB_PREFIX_ . 'attribute_group ag ' .
            \Shop::addSqlAssociation('attribute_group', 'ag') .
            'LEFT JOIN ' . _DB_PREFIX_ . 'attribute_group_lang agl ON ag.`id_attribute_group` = agl.`id_attribute_group` AND `id_lang` = ' . (int) $id_lang
        )) {
            $type = $this->translator->trans('Attribute', [], 'Admin.Global');

            foreach ($rows as &$row) {
                $options["attribute_group:{$row['id']}"] = "{$row['name']} ($type #{$row['id']})";
            }
        }
        if ($rows = $db->executeS(
            'SELECT f.`id_feature` AS id, fl.`name` FROM ' . _DB_PREFIX_ . 'feature f ' .
            \Shop::addSqlAssociation('feature', 'f') .
            'LEFT JOIN ' . _DB_PREFIX_ . 'feature_lang fl ON f.`id_feature` = fl.`id_feature` AND fl.`id_lang` = ' . (int) $id_lang
        )) {
            $type = $this->translator->trans('Feature', [], 'Admin.Global');

            foreach ($rows as &$row) {
                $options["feature:{$row['id']}"] = "{$row['name']} ($type #{$row['id']})";
            }
        }
        asort($options);

        return $options;
    }

    protected function getActiveFilterUrl(array &$filters)
    {
        foreach ($filters as &$filter) {
            if ($filter['active']) {
                return $filter['nextEncodedFacetsURL'];
            }
        }
    }

    protected function getNumberSpecification()
    {
        $spec = !isset($this->context->currentLocale) || !method_exists($this->context->currentLocale, 'getNumberSpecification') ? [
            'positivePattern' => $pattern = \Closure::bind(function () {
                return $this->repository->locales[$this->getCulture()]['numbers']['decimalFormats-numberSystem-latn']['standard'];
            }, $cldr = \Tools::getCldr($this->context), $cldr)->__invoke(),
            'negativePattern' => "-$pattern",
            'maxFractionDigits' => 3,
            'minFractionDigits' => 0,
            'groupingUsed' => true,
            'primaryGroupSize' => 3,
            'secondaryGroupSize' => 3,
        ] : $this->context->currentLocale->getNumberSpecification()->toArray();

        empty($spec['numberSymbols']) && $spec['numberSymbols'] = ['.', ',', ';', '%', '-', '+'];

        return $spec;
    }

    protected function _registerControls()
    {
        $is_admin = is_admin();

        $this->startControlsSection(
            'section_filters',
            [
                'label' => __('Filters'),
            ]
        );

        $is_admin && !\Module::isEnabled('ps_facetedsearch') && $this->addControl(
            'notice',
            [
                'raw' => sprintf(__('%s module (%s) must be installed!'), __('Faceted Search'), 'ps_facetedsearch'),
                'type' => ControlsManager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
            ]
        );

        $breakpoints = Responsive::getBreakpoints();

        $this->addControl(
            'sidebar',
            [
                'label' => __('Sidebar'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Disabled'),
                    'yes' => __('Enabled'),
                    'tablet' => __('Tablet') . " (< {$breakpoints['lg']}px)",
                    'mobile' => __('Mobile') . " (< {$breakpoints['md']}px)",
                ],
                'default' => 'tablet',
                'prefix_class' => 'ce-filters--sidebar-',
                'render_type' => 'template',
            ]
        );

        $this->addControl(
            'show_toggle',
            [
                'label' => __('Toggle Button'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'default' => 'yes',
                'condition' => [
                    'sidebar!' => '',
                ],
            ]
        );

        $this->addControl(
            'show_clear',
            [
                'label' => __('Clear Button'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'default' => 'yes',
            ]
        );

        $this->addControl(
            'heading_heading',
            [
                'label' => __('Heading'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'heading',
            [
                'show_label' => false,
                'type' => ControlsManager::TEXT,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __('Enter your title'),
            ]
        );

        $this->addControl(
            'heading_display',
            [
                'label' => __('Display'),
                'type' => ControlsManager::CHOOSE,
                'options' => WidgetHeading::getDisplaySizes(),
                'style_transfer' => true,
                'condition' => [
                    'heading!' => '',
                ],
            ]
        );

        $this->addControl(
            'heading_tag',
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
                    'heading!' => '',
                ],
            ]
        );

        $this->addControl(
            'heading_tab',
            [
                'label' => __('Tab'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'tab_active',
            [
                'label' => __('Active'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    0 => __('None'),
                    1 => '1',
                    2 => '1 - 2',
                    3 => '1 - 3',
                    4 => '1 - 4',
                    5 => '1 - 5',
                    6 => '1 - 6',
                    7 => '1 - 7',
                    8 => '1 - 8',
                    9 => '1 - 9',
                    10 => '1 - 10',
                    999 => __('All'),
                    'custom' => __('Custom'),
                ],
                'default' => 0,
            ]
        );

        $this->addControl(
            'tabs_active',
            [
                'show_label' => false,
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'multiple' => true,
                'options' => $is_admin ? self::getFacetTypeOptions() : [],
                'condition' => [
                    'tab_active' => 'custom',
                ],
            ]
        );

        $this->addControl(
            'remember_state',
            [
                'label' => __('Remember State'),
                'type' => ControlsManager::SWITCHER,
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'title_display',
            [
                'label' => __('Title Display'),
                'type' => ControlsManager::CHOOSE,
                'options' => WidgetHeading::getDisplaySizes(),
                'style_transfer' => true,
            ]
        );

        $this->addControl(
            'title_tag',
            [
                'label' => __('Title HTML Tag'),
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
                'default' => 'h6',
            ]
        );

        $this->addControl(
            'tab_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'recommended' => [
                    'ce-icons' => [
                        'caret-left',
                        'caret-right',
                        'angle-left',
                        'angle-right',
                        'chevron-left',
                        'chevron-right',
                        'arrow-left',
                        'arrow-right',
                        'sort-down',
                    ],
                    'fa-solid' => [
                        'caret-left',
                        'caret-right',
                        'caret-down',
                        'chevron-left',
                        'chevron-right',
                        'chevron-down',
                        'angle-left',
                        'angle-right',
                        'angle-down',
                        'angles-left',
                        'angles-right',
                        'angles-down',
                        'arrow-left',
                        'arrow-right',
                        'arrow-down',
                        'circle-arrow-left',
                        'circle-arrow-right',
                        'circle-arrow-down',
                        'circle-chevron-left',
                        'circle-chevron-right',
                        'circle-chevron-down',
                        'square-caret-left',
                        'square-caret-right',
                        'square-caret-down',
                    ],
                    'fa-regular' => [
                        'square-caret-left',
                        'square-caret-right',
                        'square-caret-down',
                        'circle-left',
                        'circle-right',
                        'circle-down',
                        'hand-point-left',
                        'hand-point-right',
                        'hand-point-down',
                    ],
                ],
                'default' => [
                    'value' => 'ceicon-angle-right',
                    'library' => 'ce-icons',
                ],
            ]
        );

        $this->addControl(
            'tab_icon_align',
            [
                'label' => __('Icon Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'right',
                'options' => [
                    'left' => __('Before'),
                    'right' => __('After'),
                ],
                'selectors_dictionary' => [
                    'left' => -1,
                    'right' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__tab-icon' => 'order: {{VALUE}}',
                ],
                'condition' => [
                    'tab_icon[value]!' => '',
                ],
            ]
        );

        $this->addControl(
            'tab_icon_indent',
            [
                'label' => __('Icon Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__tab' => 'gap: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ce-filters__title' => 'flex-grow: min(0, {{SIZE}})',
                ],
                'condition' => [
                    'tab_icon[value]!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_toggle',
            [
                'label' => __('Toggle Button'),
                'condition' => [
                    'sidebar!' => '',
                    'show_toggle!' => '',
                ],
            ]
        );

        $this->addControl(
            'toggle_type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'primary' => __('Primary'),
                    'secondary' => __('Secondary'),
                ],
                'default' => 'secondary',
                'style_transfer' => true,
            ]
        );

        $this->addControl(
            'toggle_text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __('Default'),
            ]
        );

        $this->addResponsiveControl(
            'toggle_align',
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
                'selectors_dictionary' => [
                    'left' => 'left; flex-direction: row',
                    'right' => 'right; flex-direction: row',
                    'center' => 'center; flex-direction: row',
                    'justify' => 'stretch; flex-direction: column',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__toggle' => 'justify-content: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'toggle_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::CHOOSE,
                'toggle' => false,
                'options' => WidgetButton::getButtonSizes(),
                'default' => 'sm',
                'style_transfer' => true,
            ]
        );

        $this->addControl(
            'toggle_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'recommended' => [
                    'fa-solid' => [
                        'sliders',
                        'list-check',
                        'filter',
                        'gear',
                        'gears',
                        'square-check',
                        'circle-check',
                    ],
                    'ce-icons' => [
                        'check',
                    ],
                    'fa-regular' => [
                        'square-check',
                        'circle-check',
                    ],
                ],
                'default' => [
                    'value' => 'fas fa-sliders',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->addControl(
            'toggle_icon_align',
            [
                'label' => __('Icon Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Before'),
                    'right' => __('After'),
                ],
                'condition' => [
                    'toggle_icon[value]!' => '',
                ],
            ]
        );

        $this->addControl(
            'toggle_icon_indent',
            [
                'label' => __('Icon Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__toggle .elementor-button-content-wrapper' => 'gap: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ce-filters__toggle .elementor-button-text' => 'flex-grow: min(0, {{SIZE}})',
                ],
                'condition' => [
                    'toggle_icon[value]!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_clear',
            [
                'label' => __('Clear Button'),
                'condition' => [
                    'show_clear!' => '',
                ],
            ]
        );

        $this->addControl(
            'clear_type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'primary' => __('Primary'),
                    'secondary' => __('Secondary'),
                ],
                'style_transfer' => true,
            ]
        );

        $this->addControl(
            'clear_text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __('Default'),
            ]
        );

        $this->addResponsiveControl(
            'clear_align',
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
                'selectors_dictionary' => [
                    'left' => 'left; flex-direction: row',
                    'right' => 'right; flex-direction: row',
                    'center' => 'center; flex-direction: row',
                    'justify' => 'stretch; flex-direction: column',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__clear' => 'justify-content: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'clear_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::CHOOSE,
                'toggle' => false,
                'options' => WidgetButton::getButtonSizes(),
                'default' => 'sm',
                'style_transfer' => true,
            ]
        );

        $this->addControl(
            'clear_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'recommended' => [
                    'fa-regular' => [
                        'circle-xmark',
                        'eraser',
                        'trash-can',
                    ],
                    'fa-solid' => [
                        'eraser',
                        'trash-can',
                        'trash',
                        'rotate-left',
                        'circle-xmark',
                        'xmark',
                    ],
                    'ce-icons' => [
                        'times',
                        'close',
                        'delete-left',
                    ],
                ],
                'default' => [
                    'value' => 'ceicon-close',
                    'library' => 'ce-icons',
                ],
            ]
        );

        $this->addControl(
            'clear_icon_align',
            [
                'label' => __('Icon Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Before'),
                    'right' => __('After'),
                ],
                'condition' => [
                    'clear_icon[value]!' => '',
                ],
            ]
        );

        $this->addControl(
            'clear_icon_indent',
            [
                'label' => __('Icon Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__clear .elementor-button-content-wrapper' => 'gap: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ce-filters__clear .elementor-button-text' => 'flex-grow: min(0, {{SIZE}})',
                ],
                'condition' => [
                    'toggle_icon[value]!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        if ($is_admin) {
            $this->startControlsSection(
                'section_additional_options',
                [
                    'label' => __('Additional Options'),
                ]
            );

            $this->addControl(
                'configure_module',
                [
                    'label' => $this->translator->trans('Faceted search', [], 'Modules.Facetedsearch.Admin'),
                    'type' => ControlsManager::BUTTON,
                    'text' => '<i class="eicon-external-link-square"></i>' . __('Configure'),
                    'link' => [
                        'url' => $this->context->link->getAdminLink('AdminModules', true, [], ['configure' => 'ps_facetedsearch']),
                        'is_external' => true,
                    ],
                ]
            );

            $this->endControlsSection();
        }

        $this->startControlsSection(
            'section_toggle_style',
            [
                'label' => __('Toggle Button'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'sidebar!' => '',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'toggle_typography',
                'selector' => '{{WRAPPER}} .ce-filters__toggle .elementor-button',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'toggle_text_shadow',
                'selector' => '{{WRAPPER}} .ce-filters__toggle .elementor-button',
            ]
        );

        $this->startControlsTabs('tabs_toggle');

        $this->startControlsTab(
            'tab_toggle_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'toggle_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__toggle a.elementor-button:not(#e)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'toggle_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__toggle .elementor-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'toggle_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__toggle .elementor-button' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tabs_toggle_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'toggle_text_color_hover',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__toggle a.elementor-button:not(#e):hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'toggle_bg_color_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__toggle .elementor-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'toggle_border_color_hover',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__toggle .elementor-button:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'toggle_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 20,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__toggle .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'toggle_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__toggle .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'toggle_box_shadow',
                'selector' => '{{WRAPPER}} .ce-filters__toggle .elementor-button',
            ]
        );

        $this->addResponsiveControl(
            'toggle_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__toggle .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_sidebar_style',
            [
                'label' => __('Sidebar'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'sidebar!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'sidebar_width',
            [
                'label' => __('Width'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 600,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-filters--shown .ce-filters' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'sidebar_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters--shown .ce-filters' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    '{{WRAPPER}} .dialog-lightbox-close-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'sidebar_background',
                'selector' => '{{WRAPPER}} .ce-filters--shown .ce-filters',
            ]
        );

        $this->addControl(
            'lightbox_color',
            [
                'label' => __('Overlay Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-lightbox.ce-filters--shown' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'heading_close',
            [
                'label' => __('Close Button'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'lightbox_ui_color',
            [
                'label' => __('UI Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dialog-lightbox-close-button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'lightbox_ui_color_hover',
            [
                'label' => __('UI Hover Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dialog-lightbox-close-button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_heading_style',
            [
                'label' => __('Heading'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'heading!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'heading_align',
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
            'heading_color',
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
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->addGroupControl(
            GroupControlTextStroke::getType(),
            [
                'name' => 'heading_text_stroke',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'heading_text_shadow',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->addResponsiveControl(
            'heading_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'margin-bottom: calc({{SIZE}}{{UNIT}} - {{gap.SIZE}}{{gap.UNIT}});',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_clear_style',
            [
                'label' => __('Clear Button'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'show_clear!' => '',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'clear_typography',
                'selector' => '{{WRAPPER}} .ce-filters__clear .elementor-button',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'clear_text_shadow',
                'selector' => '{{WRAPPER}} .ce-filters__clear .elementor-button',
            ]
        );

        $this->startControlsTabs('tabs_clear');

        $this->startControlsTab(
            'tab_clear_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'clear_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__clear a.elementor-button:not(#e)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'clear_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__clear .elementor-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'clear_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__clear .elementor-button' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tabs_clear_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'clear_text_color_hover',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__clear a.elementor-button:not(#e):hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'clear_bg_color_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__clear .elementor-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'clear_border_color_hover',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__clear .elementor-button:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'clear_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 20,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__clear .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'clear_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__clear .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'clear_box_shadow',
                'selector' => '{{WRAPPER}} .ce-filters__clear .elementor-button',
            ]
        );

        $this->addResponsiveControl(
            'clear_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__clear .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'clear_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__clear' => 'margin-bottom: calc({{SIZE}}{{UNIT}} - {{gap.SIZE}}{{gap.UNIT}});',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_filters_style',
            [
                'label' => __('Filters'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'gap',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group' => 'padding: calc({{SIZE}}{{UNIT}} / 2) 0;',
                ],
            ]
        );

        $this->addControl(
            'divider',
            [
                'label' => __('Divider'),
                'type' => ControlsManager::POPOVER_TOGGLE,
                'render_type' => 'ui',
            ]
        );

        $this->startPopover();

        $this->addControl(
            'divider_style',
            [
                'label' => __('Style'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'solid' => __('Solid'),
                    'double' => __('Double'),
                    'dotted' => __('Dotted'),
                    'dashed' => __('Dashed'),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group:not(:last-child)' => 'border-bottom: 1px {{VALUE}};',
                ],
                'condition' => [
                    'divider!' => '',
                ],
            ]
        );

        $this->addControl(
            'divider_weight',
            [
                'label' => __('Weight'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'divider!' => '',
                ],
            ]
        );

        $this->addControl(
            'divider_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
                ],
                'condition' => [
                    'divider!' => '',
                ],
            ]
        );

        $this->endPopover();

        $this->addControl(
            'heading_tab_title',
            [
                'label' => __('Tab Title'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__tab' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .ce-filters__title' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'title_tag!' => 'span',
                ],
            ]
        );

        $this->addControl(
            'title_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__tab' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'title_color_active',
            [
                'label' => __('Active Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-active .ce-filters__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .ce-filters__title',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'title_text_shadow',
                'selector' => '{{WRAPPER}} .ce-filters__title',
            ]
        );

        $this->addControl(
            'title_count',
            [
                'label' => __('Items Indicator'),
                'type' => ControlsManager::POPOVER_TOGGLE,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__title:not([data-count="0"]):after' => 'content: "(" attr(data-count) ")";',
                ],
            ]
        );

        $this->startPopover();

        $this->addControl(
            'title_count_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__title:after' => '-webkit-padding-start: {{SIZE}}{{UNIT}}; padding-inline-start: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                    'size' => 0.2,
                    'unit' => 'em',
                ],
                'condition' => [
                    'title_count!' => '',
                ],
            ]
        );

        $this->addControl(
            'title_count_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__title:after' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'title_count!' => '',
                ],
            ]
        );

        $this->addControl(
            'title_count_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__title:after' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'title_count!' => '',
                ],
            ]
        );

        $this->endPopover();

        $this->addControl(
            'tab_icon_color',
            [
                'label' => __('Icon Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__tab-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'tab_icon[value]!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'tab_icon_size',
            [
                'label' => __('Icon Size'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__tab-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tab_icon[value]!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'title_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .ce-filters__tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'heading_tab_content',
            [
                'label' => __('Tab Content'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'content_max_height',
            [
                'label' => __('Max Height'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 800,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-subgroup' => 'max-height: {{SIZE}}{{UNIT}}; overflow: auto;',
                ],
            ]
        );

        $this->addResponsiveControl(
            'content_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-subgroup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_label_style',
            [
                'label' => __('Label'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'label_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-option' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'label_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .elementor-field-label',
            ]
        );

        $this->addControl(
            'magnitude',
            [
                'label' => __('Items Indicator'),
                'type' => ControlsManager::POPOVER_TOGGLE,
                'default' => \Configuration::get('PS_LAYERED_SHOW_QTIES') ? 'yes' : '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-label[data-magnitude]:after' => 'content: "(" attr(data-magnitude) ")";',
                ],
            ]
        );

        $this->startPopover();

        $this->addControl(
            'magnitude_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-label:after' => '-webkit-padding-start: {{SIZE}}{{UNIT}}; padding-inline-start: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                    'size' => 0.2,
                    'unit' => 'em',
                ],
                'condition' => [
                    'magnitude!' => '',
                ],
            ]
        );

        $this->addControl(
            'magnitude_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-label:after' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'magnitude!' => '',
                ],
            ]
        );

        $this->addControl(
            'magnitude_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-label:after' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'magnitude!' => '',
                ],
            ]
        );

        $this->endPopover();

        $this->addResponsiveControl(
            'label_gap',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-subgroup' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_checkbox_style',
            [
                'label' => __('Checkbox'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'checkbox_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-checkbox' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->startControlsTabs('tabs_checkbox');

        $this->startControlsTab(
            'tab_checkbox_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'checkbox_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-checkbox' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'checkbox_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-checkbox' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_checkbox_active',
            [
                'label' => __('Active'),
            ]
        );

        $this->addControl(
            'checkbox_color_active',
            [
                'label' => __('Icon Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input:checked ~ .ce-checkbox' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'checkbox_bg_color_active',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input:checked ~ .ce-checkbox' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'checkbox_border_color_active',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input:checked ~ .ce-checkbox' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'checkbox_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-checkbox' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'checkbox_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-checkbox' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_radio_style',
            [
                'label' => __('Radio Buttons'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'radio_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-radio' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->startControlsTabs('tabs_radio');

        $this->startControlsTab(
            'tab_radio_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'radio_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-radio' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'radio_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-radio' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_radio_active',
            [
                'label' => __('Active'),
            ]
        );

        $this->addControl(
            'radio_color_active',
            [
                'label' => __('Icon Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input:checked ~ .ce-radio' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'radio_bg_color_active',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input:checked ~ .ce-radio' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'radio_border_color_active',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input:checked ~ .ce-radio' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'radio_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-radio' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_pattern_style',
            [
                'label' => __('Color Palette'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'pattern_layout',
            [
                'label' => __('Layout'),
                'type' => ControlsManager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'stacked' => [
                        'title' => __('Stacked'),
                        'icon' => 'eicon-editor-list-ul',
                    ],
                    'inline' => [
                        'title' => __('Inline'),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                ],
                'default' => 'stacked',
                'prefix_class' => 'ce-filters--color-layout-',
            ]
        );

        $this->addResponsiveControl(
            'pattern_gap',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-type-color .elementor-field-subgroup' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'pattern_layout' => 'inline',
                ],
            ]
        );

        $this->addControl(
            'pattern_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-option i[style]' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'pattern_icon_size',
            [
                'label' => __('Icon Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-type-color .ce-checkbox::before' => 'transform: scale({{SIZE}})',
                ],
            ]
        );

        $this->startControlsTabs('tabs_pattern');

        $this->startControlsTab(
            'tab_pattern_default',
            [
                'label' => __('Default'),
            ]
        );

        $this->addControl(
            'pattern_icon_color',
            [
                'label' => __('Icon Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-option input:checked ~ i[style]' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'pattern_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-field-option i[style]',
            ]
        );

        $this->addControl(
            'pattern_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-option i[style]' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'pattern_border_color_active',
            [
                'label' => __('Active Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-option input:checked ~ i[style]' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_pattern_active',
            [
                'label' => __('Light'),
            ]
        );

        $this->addControl(
            'pattern_icon_color_light',
            [
                'label' => __('Icon Color'),
                'type' => ControlsManager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-option input:checked ~ i.ce-color--light' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'pattern_box_shadow_light',
                'selector' => '{{WRAPPER}} .elementor-field-option i.ce-color--light[style]',
            ]
        );

        $this->addControl(
            'pattern_border_color_light',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-option i.ce-color--light' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'pattern_border_color_light_active',
            [
                'label' => __('Active Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-option input:checked ~ i.ce-color--light' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'pattern_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-option i[style]' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'pattern_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-option i[style]' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'pattern_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-option i[style]' => 'background-clip: content-box; padding: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_select_style',
            [
                'label' => __('Drop-down List'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'select_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SELECT,
                'default' => 'sm',
                'options' => [
                    'xs' => __('Extra Small'),
                    'sm' => __('Small'),
                    'md' => __('Medium'),
                    'lg' => __('Large'),
                    'xl' => __('Extra Large'),
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'select_typography',
                'selector' => '{{WRAPPER}} select.elementor-field',
            ]
        );

        $this->addControl(
            'select_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} select.elementor-field' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'select_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} select.elementor-field' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'select_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} select.elementor-field' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'select_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} select.elementor-field' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'select_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} select.elementor-field' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'select_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} select.elementor-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_range_style',
            [
                'label' => __('Slider'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('tabs_range');

        $this->startControlsTab(
            'tab_range',
            [
                'label' => __('Range'),
            ]
        );

        $this->addControl(
            'range_height',
            [
                'label' => __('Height'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-dual-range' => '--ce-range-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'range_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-dual-range' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'range_active_color',
            [
                'label' => __('Active Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-dual-range' => '--ce-range-active-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'range_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-dual-range' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_range_thumb',
            [
                'label' => __('Handle'),
            ]
        );

        $this->addControl(
            'range_thumb_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-dual-range' => '--ce-range-thumb-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'range_thumb_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-dual-range' => '--ce-range-thumb-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'range_thumb_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-dual-range' => '--ce-range-thumb-border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'range_thumb_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-dual-range' => '--ce-range-thumb-border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'range_thumb_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-dual-range' => '--ce-range-thumb-border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'range_thumb_box_shadow_type',
            [
                'label' => _x('Box Shadow', 'Box Shadow Control'),
                'type' => ControlsManager::POPOVER_TOGGLE,
                'render_type' => 'ui',
            ]
        );

        $this->startPopover();

        $this->addControl(
            'range_thumb_box_shadow',
            [
                'type' => ControlsManager::BOX_SHADOW,
                'selectors' => [
                    '{{WRAPPER}} input::-webkit-slider-thumb' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
                    '{{WRAPPER}} input::-moz-range-thumb' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
                ],
                'condition' => [
                    'range_thumb_box_shadow_type!' => '',
                ],
            ]
        );

        $this->endPopover();

        $this->addControl(
            'range_thumb_cursor',
            [
                'label' => __('Cursor'),
                'type' => ControlsManager::CHOOSE,
                'toggle' => false,
                'options' => [
                    '' => [
                        'title' => 'Drag',
                        'icon' => 'fas fa-hand',
                    ],
                    'pointer' => [
                        'title' => 'Pointer',
                        'icon' => 'fas fa-hand-pointer',
                    ],
                    'ew-resize' => [
                        'title' => 'Resize',
                        'icon' => 'fas fa-arrows-left-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-dual-range' => '--ce-range-thumb-cursor: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-heading';
    }

    protected function render()
    {
        if (!$facets = Helper::getFacets()) {
            return;
        }
        $settings = $this->getSettingsForDisplay();
        $title_class = 'ce-filters__tab' . ($settings['title_display'] ? ' ce-display-' . $settings['title_display'] : '');
        $tabs_active = $settings['tabs_active'] ?: [];
        $tab_active = (int) $settings['tab_active'];
        $tab_index = 0;
        $heading_tag = '' !== $settings['heading'] ? $settings['heading_tag'] : 'h3';

        $this->addRenderAttribute('container', 'class', ['ce-filters__container', 'elementor-lightbox']);
        $this->addRenderAttribute('heading', 'class', 'elementor-heading-title');
        empty($settings['heading_display']) || $this->addRenderAttribute('heading', 'class', 'ce-display-' . $settings['heading_display']);
        $this->addInlineEditingAttributes('heading');

        if (!empty($_SERVER[$state_key = strtoupper("HTTP_X_CE_Filters_{$this->getId()}")]) && $state = json_decode($_SERVER[$state_key], true)) {
            empty($state['tabs_active']) || $tabs_active = array_merge($state['tabs_active'], $tabs_active);
            empty($state['sidebar_shown']) || $this->addRenderAttribute('container', 'class', 'ce-filters--shown');
        } ?>
        <div <?php $this->printRenderAttributeString('container'); ?>>
            <div class="ce-filters ce-scrollbar--auto" role="tablist">
                <i class="dialog-close-button dialog-lightbox-close-button ceicon-close" role="button" tabindex="0" aria-label="<?php esc_attr_e('Close'); ?>"></i>
                <?php echo "<$heading_tag {$this->getRenderAttributeString('heading')}>{$settings['heading']}</$heading_tag>"; ?>
            <?php if ($settings['show_clear'] && !empty(${'_GET'}['q']) && $clear_all_link = Helper::getClearAllLink()) { ?>
                <div class="ce-filters__clear<?php $settings['clear_type'] && print " elementor-button-{$settings['clear_type']}"; ?>">
                    <a href="<?php echo esc_attr($clear_all_link); ?>" class="js-search-link elementor-button elementor-size-<?php echo esc_attr($settings['clear_size']); ?>" role="button">
                        <span class="elementor-button-content-wrapper">
                        <?php if (!empty($settings['clear_icon']['value'])) { ?>
                            <span class="elementor-button-icon elementor-align-icon-<?php echo esc_attr($settings['clear_icon_align']); ?>"><?php IconsManager::renderIcon($settings['clear_icon']); ?></span>
                        <?php } ?>
                            <span class="elementor-button-text"><?php echo $settings['clear_text'] ?: $this->translator->trans('Clear all', [], 'Shop.Theme.Actions'); ?></span>
                        </span>
                    </a>
                </div>
            <?php } ?>
            <?php foreach ($facets as &$facet) {
                if (!$facet['displayed']) {
                    continue;
                }
                $active_count = count(array_filter(array_column($facet['filters'], 'active')));
                $facet_type = $this->getFacetType($facet);
                $is_active = ++$tab_index <= $tab_active || $active_count || $tabs_active && in_array($facet_type, $tabs_active);
                $is_color = isset($facet['filters'][0]['properties']['color']) || isset($facet['filters'][0]['properties']['texture']);
                ?>
                <div class="elementor-field-group elementor-field-type-<?php echo esc_attr($facet['widgetType']) . ($is_color ? ' elementor-field-type-color' : ''); ?>">
                    <<?php echo $settings['title_tag']; ?> class="<?php echo $title_class . ($is_active ? ' elementor-active' : ''); ?>" data-type="<?php echo $facet_type; ?>" role="tab" tabindex="0">
                        <span class="ce-filters__title" data-count="<?php echo $active_count; ?>"><?php echo $facet['label']; ?></span>
                    <?php if (!empty($settings['tab_icon']['value'])) { ?>
                        <span class="ce-filters__tab-icon" aria-hidden="true"><?php IconsManager::renderIcon($settings['tab_icon']); ?></span>
                    <?php } ?>
                    </<?php echo $settings['title_tag']; ?>>
                    <div class="elementor-field-subgroup ce-scrollbar--auto" role="tabpanel">
                        <?php method_exists($this, $method = "render{$facet['widgetType']}") ? $this->$method($facet) : $this->renderOptions($facet); ?>
                    </div>
                </div>
            <?php } ?>
            </div>
        </div>
        <?php
        if (!$settings['sidebar'] || !$settings['show_toggle']) {
            return;
        } ?>
        <div class="ce-filters__toggle<?php $settings['toggle_type'] && print " elementor-button-{$settings['toggle_type']}"; ?>">
            <a href="#ce-action=toggle&target=filters" class="elementor-button elementor-size-<?php echo $settings['toggle_size']; ?>" role="button">
                <span class="elementor-button-content-wrapper">
                <?php if (!empty($settings['toggle_icon']['value'])) { ?>
                    <span class="elementor-button-icon elementor-align-icon-<?php echo esc_attr($settings['toggle_icon_align']); ?>"><?php IconsManager::renderIcon($settings['toggle_icon']); ?></span>
                <?php } ?>
                    <span class="elementor-button-text"><?php echo $settings['toggle_text'] ?: __('Filters'); ?></span>
                </span>
            </a>
        </div>
        <?php
    }

    protected function renderDropdown(array &$facet)
    {
        $show_magnitude = (bool) $this->getSettings('magnitude');
        ?>
        <div class="elementor-select-wrapper">
            <select class="elementor-field elementor-field-textual elementor-size-<?php echo esc_attr($this->getSettings('select_size')); ?>">
                <option value="" data-url="<?php echo esc_attr($this->getActiveFilterUrl($facet['filters'])); ?>"><?php echo $this->translator->trans('(no filter)', [], 'Shop.Theme.Global'); ?></option>
            <?php foreach ($facet['filters'] as &$filter) { ?>
                <option value="<?php echo (int) $filter['value']; ?>" data-url="<?php echo esc_attr($filter['nextEncodedFacetsURL']); ?>"<?php $filter['active'] && print ' selected'; ?>>
                    <?php echo $show_magnitude ? "{$filter['label']} ({$filter['magnitude']})" : $filter['label']; ?>
                </option>
            <?php } ?>
            </select>
        </div>
        <?php
    }

    protected function renderSlider(array &$facet)
    {
        $filter = &$facet['filters'][0];
        $filter_label = explode(' - ', $filter['label']);
        $min = (float) $facet['properties']['min'];
        $max = (float) $facet['properties']['max'];

        if (isset($facet['properties']['specifications'])) {
            $specs = $facet['properties']['specifications'];
            $step = 1 / 10 ** $specs['minFractionDigits'];
            unset($specs['numberSymbols'], $specs['currencyCode']);
        } else {
            $specs = $this->getNumberSpecification();
            $step = 0;
            $diff = $max - $min;

            for ($e = 0.1; !$step; $e *= 10) {
                $diff > $e ? ($specs['maxFractionDigits'] = max(0, $specs['maxFractionDigits'] - 1)) : $step = $e / 100;
            }
            $d = (int) ($min / $step);
            $min > $d * $step && $min = $d * $step;
            $d = ceil($max / $step);
            $max < $d * $step && $max = $d * $step;
        }
        $min_value = $filter['value'] ? (float) $filter['value'][0] : $min;
        $max_value = $filter['value'] ? (float) $filter['value'][1] : $max;
        ?>
        <div class="ce-dual-range" data-label="<?php echo esc_attr($facet['label']); ?>" data-unit="<?php echo esc_attr($facet['properties']['unit']); ?>"
            data-url="<?php echo esc_attr($filter['nextEncodedFacetsURL']); ?>" data-specifications="<?php echo esc_attr(json_encode($specs)); ?>">
            <div class="ce-dual-range__selected"
                style="left: <?php echo max(0, ($min_value - $min) / ($max - $min) * 100); ?>%; right: <?php echo max(0, ($max - $max_value) / ($max - $min) * 100); ?>%;"></div>
            <input type="range" class="ce-dual-range__min" min="<?php echo $min; ?>" max="<?php echo $max; ?>" step="<?php echo $step; ?>" value="<?php echo $min_value; ?>">
            <input type="range" class="ce-dual-range__max" min="<?php echo $min; ?>" max="<?php echo $max; ?>" step="<?php echo $step; ?>" value="<?php echo $max_value; ?>">
        </div>
        <span class="elementor-field-label"><span><?php echo $filter_label[0]; ?></span> - <span><?php echo $filter_label[1]; ?></span></span>
        <?php
    }

    protected function renderOptions(array &$facet)
    {
        foreach ($facet['filters'] as &$filter) {
            if (!$filter['displayed']) {
                continue;
            }
            $type = $facet['multipleSelectionAllowed'] ? 'checkbox' : 'radio';
            $this->setRenderAttribute('icon', 'class', 'checkbox' === $type || $filter['properties'] ? 'ce-checkbox ceicon ceicon-check' : 'ce-radio');
            $is_color = isset($filter['properties']['color']);
            $is_color && self::isColorLight($filter['properties']['color']) && $this->addRenderAttribute('icon', 'class', 'ce-color--light');
            ?>
            <a href="<?php echo esc_attr($filter['nextEncodedFacetsURL']); ?>" rel="nofollow" class="elementor-field-option js-search-link">
                <input type="<?php echo $type; ?>"<?php $filter['active'] && print ' checked'; ?>>
                <i <?php $this->printRenderAttributeString('icon'); ?>
                <?php if ($is_color) { ?>
                    title="<?php echo $filter['label']; ?>" style="background-color: <?php echo esc_attr($filter['properties']['color']); ?>"
                <?php } elseif (isset($filter['properties']['texture'])) { ?>
                    title="<?php echo $filter['label']; ?>" style="background-image: url(<?php echo esc_attr($filter['properties']['texture']); ?>)"
                <?php } ?>role="<?php echo $type; ?>" aria-checked="<?php echo $filter['active'] ? 'true' : 'false'; ?>"></i>
                <span class="elementor-field-label" data-magnitude="<?php echo $filter['magnitude']; ?>"><?php echo $filter['label']; ?></span>
            </a>
            <?php
        }
    }

    protected function contentTemplate()
    {
    }

    public function renderPlainContent()
    {
    }

    public function __construct($data = [], $args = [])
    {
        $this->context = \Context::getContext();
        $this->translator = $this->context->getTranslator();

        parent::__construct($data, $args);
    }
}
