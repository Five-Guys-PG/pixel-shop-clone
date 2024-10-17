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

use CE\ModulesXThemeXDocumentsXThemePageDocument as ThemePageDocument;

class ModulesXCatalogXDocumentsXProduct extends ThemePageDocument
{
    public function getName()
    {
        return 'product';
    }

    public static function getTitle()
    {
        return __('Product Page');
    }

    protected static function getEditorPanelCategories()
    {
        return [
            'product-elements' => [
                'title' => __('Product'),
            ],
        ] + parent::getEditorPanelCategories();
    }

    public static function getTagClasses()
    {
        return [
            'ProductName',
            'ProductUrl',
            'ProductPrice',
            'ProductRating',
            'ProductMeta',
            // 'ProductStock',
            // 'ProductTerms',
            'ProductDescriptionShort',
            'ProductAvailabilityDateTime',
            'CategoryName',
            'CategoryUrl',
            'ManufacturerName',
            'ManufacturerUrl',
        ];
    }

    public static function getWidgetClasses()
    {
        return [
            'ProductXName',
            'ProductXImages',
            'ProductXBadges',
            'ProductXImage',
            'ProductXPrice',
            'ProductXRating',
            'ProductXMeta',
            'ProductXVariants',
            'ProductXDescriptionShort',
            'ProductXCustomization',
            'ProductXStock',
            'ProductXQuantity',
            'ProductXAddToCart',
            'ProductXDescription',
            'ProductXAddToWishlist',
            'ProductXFeatures',
            'ProductXAttachments',
            'ProductXSaleCountdown',
            'ManufacturerXImage',
            'ProductXShare',
            'ProductXBlock',
        ];
    }

    protected function getPermalinkUrl(\Link $link, $id_lang, $id_shop, array $args, $relative = true)
    {
        $settings = $this->getData('settings');

        empty($settings['preview_id'])
            || $product = new \Product($settings['preview_id'], false, $id_lang);
        empty($product->id)
            && $product = new \Product(Helper::getLastUpdatedProductId($id_shop) ?: null, false, $id_lang);
        if (!$product->active) {
            isset($args['id_employee'])
                || $args['id_employee'] = \Context::getContext()->employee->id;
            $args['adtoken'] = \Tools::getAdminTokenLite('AdminProducts');
        }
        $url = $link->getProductLink($product, null, null, null, $id_lang, $id_shop, $product->cache_default_attribute ?: 0, false, $relative, false, [], false);

        return add_query_arg($args, explode('#', $url)[0]);
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

        $this->addControl(
            'preview_id',
            [
                'type' => ControlsManager::SELECT2,
                'label' => __('Product'),
                'label_block' => true,
                'select2options' => [
                    'placeholder' => __('Loading') . '...',
                    'allowClear' => false,
                    'ajax' => [
                        'get' => 'Products',
                        'url' => Helper::getAjaxProductsListLink(),
                    ],
                ],
                'default' => is_admin() ? (Helper::getLastUpdatedProductId(\Context::getContext()->shop->id) ?: 1) : '',
                'export' => false,
            ]
        );

        $this->addControl(
            'apply_preview',
            [
                'type' => ControlsManager::BUTTON,
                'text' => __('Apply & Preview'),
                'separator' => 'none',
                'event' => 'ceThemeBuilder:ApplyPreview',
            ]
        );

        $this->endControlsSection();
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
        foreach (static::getWidgetClasses() as $widget_class) {
            $class_name = 'CE\ModulesXCatalogXWidgetsX' . $widget_class;

            $widgets_manager->registerWidgetType(new $class_name());
        }
    }

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        did_action('elementor/dynamic_tags/register_tags')
            ? static::registerTags(Plugin::$instance->dynamic_tags)
            : add_action('elementor/dynamic_tags/register_tags', [static::class, 'registerTags']);
        did_action('elementor/widgets/widgets_registered')
            ? static::registerWidgets(Plugin::$instance->widgets_manager)
            : add_action('elementor/widgets/widgets_registered', [static::class, 'registerWidgets']);
    }
}
