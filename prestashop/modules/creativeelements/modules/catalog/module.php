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

use CE\CoreXBaseXModule as BaseModule;
use CE\ModulesXCatalogXControlsXSelectCategory as SelectCategory;
use CE\ModulesXCatalogXControlsXSelectManufacturer as SelectManufacturer;
use CE\ModulesXCatalogXControlsXSelectSupplier as SelectSupplier;
use CE\ModulesXCatalogXDocumentsXProduct as ProductDocument;

class ModulesXCatalogXModule extends BaseModule
{
    public function getName()
    {
        return 'catalog';
    }

    public function registerControls($controls_manager)
    {
        $controls_manager->registerControl(SelectCategory::CONTROL_TYPE, new SelectCategory());
        $controls_manager->registerControl(SelectManufacturer::CONTROL_TYPE, new SelectManufacturer());
        $controls_manager->registerControl(SelectSupplier::CONTROL_TYPE, new SelectSupplier());
    }

    public function registerDocuments($documents)
    {
        $class_prefix = 'CE\ModulesXCatalogXDocumentsX';

        $documents->registerDocumentType('listing-page', $class_prefix . 'ListingXPage');
        $documents->registerDocumentType('listing-category', $class_prefix . 'ListingXCategory');
        $documents->registerDocumentType('listing-no-results', $class_prefix . 'ListingXNoResults');
        $documents->registerDocumentType('product', $class_prefix . 'Product');
        $documents->registerDocumentType('product-quick-view', $class_prefix . 'ProductQuickView');
        $documents->registerDocumentType('product-miniature', $class_prefix . 'ProductMiniature');
        \Configuration::get(version_compare(_PS_VERSION_, '1.7.7', '<') ? 'PS_DISPLAY_SUPPLIERS' : 'PS_DISPLAY_MANUFACTURERS')
            && $documents->registerDocumentType('listing-manufacturer', $class_prefix . 'ListingXManufacturer');
    }

    public function registerTags($dynamic_tags)
    {
        $class_prefix = 'CE\ModulesXCatalogXTagsX';

        foreach ([
            'ProductAddToCart',
            'ProductBuyNow',
            'ProductQuickView',
            'ProductImage',
            'ProductImages',
            'CategoryImage',
            'CategoryImages',
            'ManufacturerImage',
            'ManufacturerImages',
            'CartRuleDateTime',
            'SpecificPriceRuleDateTime',
        ] as $tag_class) {
            $dynamic_tags->registerTag($class_prefix . $tag_class);
        }
    }

    public function registerWidgets($widgets_manager)
    {
        $class_prefix = 'CE\ModulesXCatalogXWidgetsX';

        foreach ([
            'ProductXCarousel',
            'ProductXGrid',
            'ProductXBox',
            'CategoryXGrid',
            'CategoryXList',
            'CategoryXTree',
        ] as $widget_class) {
            $widget_class = $class_prefix . $widget_class;

            $widgets_manager->registerWidgetType(new $widget_class());
        }
    }

    private function refreshProduct($id_ce_theme)
    {
        $context = \Context::getContext();
        $id_product = (int) \Tools::getValue('id_product');
        $groups = \Tools::getValue('group');
        $ipa = $groups ? (int) \Product::getIdProductAttributeByIdAttributes($id_product, $groups, true) : null;
        $product = new \Product($id_product, false, $context->language->id, $context->shop->id);
        $product_url = $context->link->getProductLink($product, null, null, null, null, null, $ipa, false, false, true);
        unset(${'_GET'}['ajax'], ${'_POST'}['ajax']);
        $args = ${'_GET'};
        unset(
            $args['controller'],
            $args['action'],
            $args['id_product'],
            $args['id_product_attribute'],
            $args['rewrite'],
            $args['isolang'],
            $args['id_lang']
        );
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

        wp_send_json([
            'product_content' => Plugin::$instance->frontend->getBuilderContent(
                new UId($id_ce_theme, UId::THEME, $context->language->id, $context->shop->id)
            ),
            'product_url' => $args
                ? str_replace('#', (strpos('?', $product_url) === false ? '?' : '&') . http_build_query($args) . '#', $product_url)
                : $product_url,
            'product_minimal_quantity' => (int) \Closure::bind(function () use ($ipa) {
                return $this->getProductMinimalQuantity(['id_product_attribute' => $ipa]);
            }, $context->controller, $context->controller)->__invoke(),
            'id_product_attribute' => $ipa,
            'product_title' => $context->smarty->tpl_vars['page']->value['meta']['title'],
            'is_quick_view' => \Tools::getValue('quickview'),
        ]);
    }

    public function init()
    {
        $context = \Context::getContext();
        $controller = $context->controller;
        // Init Quick View
        if ($id_pqv = (int) \Configuration::get('CE_PRODUCT_QUICK_VIEW')) {
            Plugin::$instance->frontend->hasElementorInPage(true);

            if ($controller instanceof \ProductController && \Tools::getValue('action') === 'quickview') {
                \CreativeElements::skipOverrideLayoutTemplate();

                $context->smarty->assign('CE_PRODUCT_QUICK_VIEW_ID', $id_pqv);

                UId::$_ID = new UId($id_pqv, UId::THEME, $context->language->id, $context->shop->id);
            }
        }
        // Init Product Refresh
        if ($controller instanceof \ProductController
            && \Tools::getValue('action') === 'refresh'
            && $id_ce_theme = \Configuration::get(empty($_REQUEST['quickview']) ? 'CE_PRODUCT' : 'CE_PRODUCT_QUICK_VIEW')
        ) {
            add_action('elementor/widgets/widgets_registered', function ($manager) {
                // Skip these widgets on refresh
                $manager->unregisterWidgetType('product-name');
                $manager->unregisterWidgetType('product-description');
                $manager->unregisterWidgetType('product-description-short');
                $manager->unregisterWidgetType('product-quantity');
                $manager->unregisterWidgetType('product-box');
                $manager->unregisterWidgetType('product-grid');
                $manager->unregisterWidgetType('product-carousel');
            }, 11);

            $this->refreshProduct($id_ce_theme);
        }
        // Init Product Listing AJAX request
        if ($controller instanceof \ProductListingFrontController
            && $controller->ajax
            && !\Tools::getIsset('action')
            && $id_ce_theme = substr(\CreativeElements::getPreviewUId(false), 0, -6) ?: \Configuration::get('CE_LISTING_' . strtoupper(str_replace('-', '_', $controller->php_self)))
        ) {
            \Closure::bind(function () use ($context, $id_ce_theme) {
                // the search provider will need a context (language, shop...) to do its job
                $psc = $this->getProductSearchContext();
                // the controller generates the query...
                $query = $this->getProductSearchQuery();
                // if no module wants to do the query, then the core feature is used
                $provider = $this->getProductSearchProviderFromModules($query) ?: $this->getDefaultProductSearchProvider();
                // we need to set a few parameters from back-end preferences
                if (0 >= ($resultsPerPage = (int) \Tools::getValue('resultsPerPage')) || $resultsPerPage > 36) {
                    $resultsPerPage = \Configuration::get('PS_PRODUCTS_PER_PAGE');
                }
                $query->setResultsPerPage($resultsPerPage)->setPage(max((int) \Tools::getValue('page'), 1));
                // set the sort order if provided in the URL
                if ($encodedSortOrder = \Tools::getValue('order')) {
                    $query->setSortOrder(\PrestaShop\PrestaShop\Core\Product\Search\SortOrder::newFromString($encodedSortOrder));
                }
                // set the parameters containing the encoded facets from the URL
                $query->setEncodedFacets(\Tools::getValue('q'));
                // We're ready to run the actual query!
                $result = $provider->runQuery($psc, $query);
                // sort order is useful for template, add it if undefined - it should be the same one as for the query anyway
                if (!$result->getCurrentSortOrder()) {
                    $result->setCurrentSortOrder($query->getSortOrder());
                }
                // prepare the products
                $products = $this->prepareMultipleProductsForTemplate($result->getProducts());
                // render the facets
                if ($provider instanceof \FacetsRendererInterface) {
                    // with the provider
                    $rendered_facets = $provider->renderFacets($psc, $result);
                    $rendered_active_filters = $provider->renderActiveFilters($psc, $result);
                } else {
                    // with the core
                    $rendered_facets = $this->renderFacets($result);
                    $rendered_active_filters = $this->renderActiveFilters($result);
                }
                $pagination = $this->getTemplateVarPagination($query, $result);
                // prepare the sort orders
                $sort_orders = $this->getTemplateVarSortOrders($result->getAvailableSortOrders(), $query->getSortOrder()->toString());
                $sort_selected = false;

                if (!empty($sort_orders)) {
                    foreach ($sort_orders as &$order) {
                        if (isset($order['current']) && true === $order['current']) {
                            $sort_selected = $order['label'];
                            break;
                        }
                    }
                }
                $searchVariables = [
                    'result' => &$result,
                    'label' => $this->getListingLabel(),
                    'products' => &$products,
                    'sort_orders' => &$sort_orders,
                    'sort_selected' => $sort_selected,
                    'pagination' => &$pagination,
                    'rendered_facets' => &$rendered_facets,
                    'rendered_active_filters' => &$rendered_active_filters,
                    'js_enabled' => $this->ajax,
                    'current_url' => isset($_SERVER['HTTP_X_CE_PAGINATION'], $pagination['pages'][1])
                        ? $pagination['pages'][1]['url']
                        : $this->updateQueryString(['q' => $result->getEncodedFacets()]),
                ];
                \Hook::exec('filterProductSearch', ['searchVariables' => &$searchVariables]);
                \Hook::exec('actionProductSearchAfter', $searchVariables);

                $context->smarty->assign('listing', $searchVariables);
                $document = Plugin::$instance->documents->get(new UId($id_ce_theme, UId::THEME, $context->language->id, $context->shop->id));
                $rendered_blocks = $document->getRenderedBlocks();

                $searchVariables['rendered_products_top'] = $rendered_blocks['rendered_products_top'] ?: $this->render('catalog/_partials/products-top');
                $searchVariables['rendered_products'] = $rendered_blocks['rendered_products'] ?: $this->render('catalog/_partials/products');
                $searchVariables['rendered_products_bottom'] = $rendered_blocks['rendered_products_bottom'] ?: $this->render('catalog/_partials/products-bottom');

                if ($this instanceof \CategoryController) {
                    $searchVariables['rendered_products_header'] = $rendered_blocks['rendered_products_header'] ?: $this->render('catalog/_partials/category-header');
                    (int) _PS_VERSION_ < 8
                        || $searchVariables['rendered_products_footer'] = $rendered_blocks['rendered_products_footer'] ?: $this->render('catalog/_partials/category-footer');
                }
                $searchVariables['rendered_widgets'] = &$rendered_blocks['rendered_widgets'];

                wp_send_json($searchVariables);
            }, $controller, 'ProductListingFrontControllerCore')->__invoke();
        }
    }

    public function __construct()
    {
        add_action('template_redirect', [$this, 'init'], 1);
        add_action('elementor/controls/controls_registered', [$this, 'registerControls']);
        add_action('elementor/documents/register', [$this, 'registerDocuments']);
        add_action('elementor/dynamic_tags/register_tags', [$this, 'registerTags']);
        add_action('elementor/widgets/widgets_registered', [$this, 'registerWidgets']);
        add_action('ce/css-file/global/before_render', function () {
            // Init widgets if needed
            $widgets_manager = Plugin::$instance->widgets_manager;
            $widgets_manager->getWidgetTypes();

            ProductDocument::registerWidgets($widgets_manager);
        });
    }
}
