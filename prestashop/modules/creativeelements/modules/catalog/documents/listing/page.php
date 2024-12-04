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

use CE\ModulesXCatalogXControlsXSelectCategory as SelectCategory;
use CE\ModulesXCatalogXControlsXSelectManufacturer as SelectManufacturer;
use CE\ModulesXCatalogXWidgetsXListingXPageTitle as PageTitle;
use CE\ModulesXThemeXDocumentsXThemePageDocument as ThemePageDocument;

class ModulesXCatalogXDocumentsXListingXPage extends ThemePageDocument
{
    const RENDER_MAP = [
        'ce_product_list_header' => 'rendered_products_header',
        'ce_product_list_top' => 'rendered_products_top',
        'ce_product_list' => 'rendered_products',
        'ce_product_list_bottom' => 'rendered_products_bottom',
        'ce_product_list_footer' => 'rendered_products_footer',
    ];

    protected $rendered_blocks = [
        'rendered_products_header' => ' ',
        'rendered_products_top' => ' ',
        'rendered_products' => ' ',
        'rendered_products_bottom' => ' ',
        'rendered_products_footer' => ' ',
        'rendered_widgets' => [],
    ];

    public function getName()
    {
        return 'listing-page';
    }

    public static function getTitle()
    {
        return __('Listing Page');
    }

    protected static function getEditorPanelCategories()
    {
        return [
            'listing-elements' => [
                'title' => __('Listing'),
            ],
        ] + parent::getEditorPanelCategories();
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['category'] = 'listing';

        return $config;
    }

    public static function getTagClasses()
    {
        return [
            'ListingInfo',
            'ListingImage',
            'ListingClearUrl',
        ];
    }

    public static function getWidgetClasses()
    {
        return [
            'ListingXImage',
            'ListingXFilters',
            'ListingXActiveFilters',
            'ListingXDescription',
            'ListingXSortOrder',
            'ListingXProducts',
            'ListingXPagination',
            'ListingXInfo',
            'ListingXBlock',
            'ListingXTemplate',
        ];
    }

    protected function getPermalinkUrl(\Link $link, $id_lang, $id_shop, array $args, $relative = true)
    {
        $settings = $this->getData('settings');
        $preview = isset($settings['preview']) ? $settings['preview'] : 'category';

        switch ($preview) {
            case 'category':
                $id_category = !empty($settings['id_category'])
                    ? $settings['id_category']
                    : \Configuration::get('PS_HOME_CATEGORY', null, null, $id_shop ?: null);
                $url = $link->getCategoryLink($id_category, null, $id_lang, null, $id_shop, $relative);
                break;
            case 'manufacturer':
                $id_manufacturer = !empty($settings['id_manufacturer'])
                    ? $settings['id_manufacturer']
                    : Helper::getLastUpdatedManufacturerId();
                $url = $link->getManufacturerLink($id_manufacturer, null, $id_lang, $id_shop, $relative);
                break;
            case 'search':
                $url = $link->getPageLink($preview, true, $id_lang, empty($settings['search']) ? null : [
                    's' => $settings['search'],
                ], false, $id_shop, $relative);
                break;
            default:
                $url = $link->getPageLink($preview, true, $id_lang, null, false, $id_shop, $relative);
                break;
        }

        return add_query_arg($args, $url);
    }

    public function getRenderedBlocks()
    {
        $elements_data = $this->getElementsData();

        $this->renderBlocks($elements_data);

        return $this->rendered_blocks;
    }

    protected function renderBlocks(&$elements_data)
    {
        $include = ['listing-pagination', 'listing-info'];
        $exclude = ['listing-page-title', 'listing-image', 'listing-description'];

        foreach ($elements_data as &$element_data) {
            if (isset($element_data['widgetType']) && strpos($widget_type = $element_data['widgetType'], 'listing') === 0) {
                if ('listing-block' === $widget_type && !empty($element_data['settings']['block']) && !empty(static::RENDER_MAP[$block = $element_data['settings']['block']])) {
                    $this->rendered_blocks[static::RENDER_MAP[$block]] = '';
                } elseif ('listing-products' === $widget_type && ' ' === $this->rendered_blocks['rendered_products']) {
                    $this->rendered_blocks['rendered_products'] = $this->renderElement($element_data);
                } elseif (isset($_SERVER['HTTP_X_CE_PAGINATION']) ? in_array($widget_type, $include) : !in_array($widget_type, $exclude)) {
                    $this->rendered_blocks['rendered_widgets'][$element_data['id']] = $this->renderElement($element_data);
                }
            } elseif (isset($element_data['settings']['__dynamic__'])) {
                foreach ($element_data['settings']['__dynamic__'] as &$tag) {
                    if (strpos($tag, '"shortcode"') || strpos($tag, '"listing-info"')) {
                        $this->rendered_blocks['rendered_widgets'][$element_data['id']] = $this->renderElement($element_data);
                        continue 2;
                    }
                }
            }
            $element_data['elements'] && $this->renderBlocks($element_data['elements']);
        }
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->startControlsSection(
            'preview_settings',
            [
                'label' => __('Preview Settings'),
                'tab' => ControlsManager::TAB_SETTINGS,
            ]
        );

        $this->registerPreviewSettings();

        $this->addControl(
            'apply_preview',
            [
                'type' => ControlsManager::BUTTON,
                'text' => __('Apply & Preview'),
                'event' => 'ceThemeBuilder:ApplyPreview',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_ajax_refresh',
            [
                'label' => 'AJAX Refresh',
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->addControl(
            'scroll_up',
            [
                'label' => __('Scroll Up'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'none' => __('Disable'),
                    'top' => __('Top'),
                    'custom' => __('Custom'),
                ],
                'default' => 'top',
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'scroll_id',
            [
                'label' => __('CSS ID'),
                'type' => ControlsManager::TEXT,
                'classes' => 'elementor-control-direction-ltr',
                'title' => __('Add your custom id WITHOUT the Pound key. e.g: my-id'),
                'frontend_available' => true,
                'condition' => [
                    'scroll_up' => 'custom',
                ],
            ]
        );

        $this->addResponsiveControl(
            'scroll_offset',
            [
                'label' => __('Offset') . ' (px)',
                'type' => ControlsManager::NUMBER,
                'default' => 0,
                'frontend_available' => true,
                'condition' => [
                    'scroll_up' => 'custom',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function registerPreviewSettings()
    {
        $preview_options = [
            'category' => __('Category'),
            'manufacturer' => __('Brand'),
            'search' => __('Search'),
            'prices-drop' => __('Prices Drop'),
            'new-products' => __('New Products'),
            'best-sales' => __('Best Sellers'),
        ];
        if (!\Configuration::get(version_compare(_PS_VERSION_, '1.7.7', '<') ? 'PS_DISPLAY_SUPPLIERS' : 'PS_DISPLAY_MANUFACTURERS')) {
            unset($preview_options['manufacturer']);
        }
        if (!\Configuration::get('PS_DISPLAY_BEST_SELLERS') || \Configuration::get('PS_CATALOG_MODE')) {
            unset($preview_options['best-sales']);
        }
        $this->addControl(
            'preview',
            [
                'label' => __('Preview'),
                'type' => ControlsManager::SELECT,
                'options' => &$preview_options,
                'default' => 'category',
            ]
        );

        $this->addControl(
            'id_category',
            [
                'show_label' => false,
                'label_block' => true,
                'type' => SelectCategory::CONTROL_TYPE,
                'select2options' => [
                    'allowClear' => false,
                ],
                'extend' => [
                    '0' => __('Default'),
                ],
                'default' => 0,
                'export' => false,
                'condition' => [
                    'preview' => 'category',
                ],
            ]
        );

        $this->addControl(
            'id_manufacturer',
            [
                'show_label' => false,
                'label_block' => true,
                'type' => isset($preview_options['manufacturer'])
                    ? SelectManufacturer::CONTROL_TYPE
                    : ControlsManager::HIDDEN,
                'select2options' => [
                    'allowClear' => false,
                ],
                'extend' => [
                    '0' => __('Default'),
                ],
                'default' => 0,
                'export' => false,
                'condition' => [
                    'preview' => 'manufacturer',
                ],
            ]
        );

        $this->addControl(
            'search',
            [
                'show_label' => false,
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Search') . '...',
                'condition' => [
                    'preview' => 'search',
                ],
            ]
        );
    }

    public static function registerTags($dynamic_tags)
    {
        foreach (static::getTagClasses() as $tag_class) {
            $class_name = 'CE\ModulesXCatalogXTagsX' . $tag_class;

            $dynamic_tags->registerTag($class_name);
        }
    }

    public static function registerWidgets($widgets_manager)
    {
        $widgets_manager->registerWidgetType(new PageTitle([], null, [
            'title' => __('Listing Name'),
            'icon' => 'eicon-product-title',
            'categories' => ['listing-elements' => 1],
            'keywords' => ['shop', 'store', 'heading', 'title', 'name', 'listing', 'page', 'title'],
            'dynamic_tag_name' => 'page-title',
        ]));

        foreach (static::getWidgetClasses() as $widget_class) {
            $class_name = 'CE\ModulesXCatalogXWidgetsX' . $widget_class;
            $widgets_manager->registerWidgetType(new $class_name());
        }
    }

    public function __construct(array $data = [])
    {
        if ($data && is_admin()) {
            $template = get_post_meta($data['post_id'], '_wp_page_template', true);
            $template || update_post_meta($data['post_id'], '_wp_page_template', 'layout-full-width');

            $data['settings']['template'] = $template ?: 'layout-full-width';
        }

        parent::__construct($data);

        did_action('elementor/dynamic_tags/register_tags')
            ? static::registerTags(Plugin::$instance->dynamic_tags)
            : add_action('elementor/dynamic_tags/register_tags', [static::class, 'registerTags']);
        did_action('elementor/widgets/widgets_registered')
            ? static::registerWidgets(Plugin::$instance->widgets_manager)
            : add_action('elementor/widgets/widgets_registered', [static::class, 'registerWidgets']);
    }
}
