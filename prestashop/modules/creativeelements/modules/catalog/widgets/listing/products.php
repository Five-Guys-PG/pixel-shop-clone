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

use CE\ModulesXCatalogXWidgetsXProductXBase as ProductBase;

class ModulesXCatalogXWidgetsXListingXProducts extends WidgetBase
{
    const REMOTE_RENDER = true;

    private static $id;

    public function getName()
    {
        return 'listing-products';
    }

    public function getTitle()
    {
        return __('Products');
    }

    public function getIcon()
    {
        return 'eicon-products';
    }

    public function getCategories()
    {
        return ['listing-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'category', 'products', 'listing'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_products',
            [
                'label' => __('Products'),
            ]
        );

        $this->addControl(
            'skin',
            [
                'label' => __('Miniature'),
                'type' => ControlsManager::SELECT,
                'options' => ProductBase::getSkinOptions(),
                'default' => 'product',
                'save_default' => true,
            ]
        );

        $this->addResponsiveControl(
            'columns',
            [
                'label' => __('Columns'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'default' => 4,
                'tablet_default' => 3,
                'mobile_default' => 1,
                'selectors' => [
                    '{{WRAPPER}} .ce-products' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
                ],
            ]
        );

        is_admin() && $this->addControl(
            'configure',
            [
                'label' => __('Additional Options'),
                'type' => ControlsManager::BUTTON,
                'text' => '<i class="eicon-external-link-square"></i>' . __('Configure'),
                'link' => [
                    'url' => \Context::getContext()->link->getAdminLink('AdminPPreferences') . '#pagination_products_per_page',
                    'is_external' => true,
                ],
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_products',
            [
                'label' => __('Products'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'column_gap',
            [
                'label' => __('Columns Gap'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ce-products' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'row_gap',
            [
                'label' => __('Rows Gap'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ce-products' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    public function onImport($widget)
    {
        // Check Skin
        if ('product' !== $widget['settings']['skin'] && !ProductBase::getSkinTemplate($widget['settings']['skin'])) {
            $widget['settings']['skin'] = 'product';
        }

        return $widget;
    }

    protected function render()
    {
        static $id_container;
        null === $id_container && $this->addRenderAttribute('_container', 'id', $id_container = 'js-product-list');

        $context = \Context::getContext();
        $vars = &$context->smarty->tpl_vars;
        $listing = $vars['listing']->value;

        if (!$listing['products']) {
            if (!$context->controller instanceof \CategoryController || !$vars['subcategories']->value) {
                if ($id_no_results = \Configuration::get('CE_LISTING_NO_RESULTS')) {
                    $uid = UId::$_ID;
                    UId::$_ID = new UId($id_no_results, UId::THEME, $context->language->id, $context->shop->id);
                    echo apply_filters('the_content', '');
                    UId::$_ID = $uid;
                } else {
                    \CESmarty::write(_CE_TEMPLATES_ . 'front/theme/catalog/listing/block.tpl', 'ce_page_content');
                }
            }
            return print '<!-- listing-products -->';
        }

        if (!$tpl = ProductBase::getSkinTemplate($this->getSettings('skin'))) {
            return print '<!-- Missing Miniature -->';
        }

        echo '<div class="ce-products ce-product-grid products">';
        foreach ($listing['products'] as &$product) {
            echo $context->smarty->fetch($tpl, null, null, [
                'product' => $product,
                'productClasses' => '',
            ]);
        }
        echo '</div>';
    }

    public function renderPlainContent()
    {
    }
}
