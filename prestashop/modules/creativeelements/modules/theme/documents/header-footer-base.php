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

use CE\CoreXBaseXPageBase as PageBase;
use CE\ModulesXCatalogXControlsXSelectCategory as SelectCategory;
use CE\ModulesXCatalogXControlsXSelectManufacturer as SelectManufacturer;
use CE\ModulesXThemeXDocumentsXThemeDocument as ThemeDocument;

abstract class ModulesXThemeXDocumentsXHeaderFooterBase extends ThemeDocument
{
    public function getCssWrapperSelector()
    {
        return '#' . $this->getName();
    }

    public function getElementUniqueSelector(ElementBase $element)
    {
        return '#' . $this->getName() . ' .elementor-element' . $element->getUniqueSelector();
    }

    protected static function getEditorPanelCategories()
    {
        // Move to top as active.
        return [
            'theme-elements' => [
                'title' => __('Site'),
                'active' => true,
            ],
        ] + parent::getEditorPanelCategories();
    }

    protected function getPermalinkUrl(\Link $link, $id_lang, $id_shop, array $args, $relative = true)
    {
        $settings = $this->getData('settings');
        $preview = isset($settings['preview']) ? $settings['preview'] : 'index';

        switch ($preview) {
            case 'index':
                return parent::getPermalinkUrl($link, $id_lang, $id_shop, $args, $relative);
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
            case 'product':
                empty($settings['id_product']) || $product = new \Product($settings['id_product'], false, $id_lang);
                empty($product->id) && $product = new \Product(Helper::getLastUpdatedProductId($id_shop) ?: null, false, $id_lang);

                if (!$product->active) {
                    isset($args['id_employee']) || $args['id_employee'] = \Context::getContext()->employee->id;
                    $args['adtoken'] = \Tools::getAdminTokenLite('AdminProducts');
                }
                $url = $link->getProductLink($product, null, null, null, $id_lang, $id_shop, $product->cache_default_attribute ?: 0, false, $relative);
                $url = explode('#', $url)[0];
                break;
            case 'cms':
                $settings['id_cms'] && $cms = new \CMS($settings['id_cms'], $id_lang);
                empty($cms->id) && $cms = new \CMS(1, $id_lang);

                if (!$cms->active) {
                    isset($args['id_employee']) || $args['id_employee'] = \Context::getContext()->employee->id;
                    $args['adtoken'] = \Tools::getAdminTokenLite('AdminCmsContent');
                }
                $url = $link->getCMSLink($cms, null, null, $id_lang, $id_shop, $relative);
                break;
            default:
                $url = $link->getPageLink($preview, true, $id_lang, null, false, $id_shop, $relative);
                break;
        }

        return add_query_arg($args, $url);
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

        $pages = [
            'index' => __('Home'),
            'listing' => [
                'label' => __('Listing'),
                'options' => [
                    'category' => __('Category'),
                    'manufacturer' => __('Brand'),
                    // 'supplier' => __('Supplier'),
                    'search' => __('Search'),
                    'prices-drop' => __('Prices Drop'),
                    'new-products' => __('New Products'),
                    'best-sales' => __('Best Sellers'),
                ],
            ],
            'product' => __('Product'),
            'cart' => __('Shopping Cart'),
            'contact' => __('Contact Page'),
            'cms' => __('CMS'),
            'pagenotfound' => __('404 Page'),
        ];
        if (!\Configuration::get('PS_DISPLAY_BEST_SELLERS') || \Configuration::get('PS_CATALOG_MODE')) {
            unset($pages['listing']['options']['best-sales']);
        }
        if (!$display_suppliers = \Configuration::get('PS_DISPLAY_SUPPLIERS')) {
            unset($pages['listing']['options']['supplier']);
        }
        if (!$display_manufacturers = version_compare(_PS_VERSION_, '1.7.7', '<') ? $display_suppliers : \Configuration::get('PS_DISPLAY_MANUFACTURERS')) {
            unset($pages['listing']['options']['manufacturer']);
        }
        $this->addControl(
            'preview',
            [
                'label' => __('Preview'),
                'type' => ControlsManager::SELECT,
                'groups' => &$pages,
                'default' => 'index',
            ]
        );

        $this->addControl(
            'id_category',
            [
                'label' => __('Search & Select'),
                'label_block' => true,
                'type' => SelectCategory::CONTROL_TYPE,
                'select2options' => [
                    'allowClear' => false,
                ],
                'default' => \Context::getContext()->shop->id_category,
                'export' => false,
                'condition' => [
                    'preview' => 'category',
                ],
            ]
        );

        $display_manufacturers && $this->addControl(
            'id_manufacturer',
            [
                'label' => __('Search & Select'),
                'label_block' => true,
                'type' => SelectManufacturer::CONTROL_TYPE,
                'select2options' => [
                    'placeholder' => __('Select...'),
                ],
                'export' => false,
                'condition' => [
                    'preview' => 'manufacturer',
                ],
            ]
        );

        $this->addControl(
            'search',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Search') . '...',
                'condition' => [
                    'preview' => 'search',
                ],
            ]
        );

        $this->addControl(
            'id_product',
            [
                'label' => __('Search & Select'),
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'select2options' => [
                    'placeholder' => __('Type Product Name / Ref'),
                    'ajax' => [
                        'get' => 'Products',
                        'url' => Helper::getAjaxProductsListLink(),
                    ],
                ],
                'export' => false,
                'condition' => [
                    'preview' => 'product',
                ],
            ]
        );

        $this->addControl(
            'id_cms',
            [
                'label' => __('Search & Select'),
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'select2options' => [
                    'placeholder' => __('Type Here'),
                    'ajax' => [
                        'get' => 'Cms',
                    ],
                ],
                'export' => false,
                'condition' => [
                    'preview' => 'cms',
                ],
            ]
        );

        $this->addControl(
            'apply_preview',
            [
                'type' => ControlsManager::BUTTON,
                'text' => __('Apply & Preview'),
                'event' => 'ceThemeBuilder:ApplyPreview',
            ]
        );

        $this->endControlsSection();

        PageBase::registerStyleControls($this);

        $this->updateControl(
            'section_page_style',
            [
                'label' => __('Style'),
            ]
        );

        if ($this->getName() === 'footer') {
            $this->updateControl('padding', [
                'default' => [
                    'isLinked' => false,
                ],
            ]);
        }

        $this->startInjection([
            'of' => 'padding',
        ]);

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'box_shadow',
                'fields_options' => $this->getName() === 'header' ? [
                    'box_shadow_type' => [
                        'default' => 'yes',
                    ],
                    'box_shadow' => [
                        'default' => [
                            'blur' => 0,
                        ],
                    ],
                ] : [],
            ]
        );

        $this->endInjection();
    }
}
