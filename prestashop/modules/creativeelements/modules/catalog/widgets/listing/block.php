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

class ModulesXCatalogXWidgetsXListingXBlock extends WidgetBase
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'listing-block';
    }

    public function getTitle()
    {
        return __('Listing Block');
    }

    public function getIcon()
    {
        return 'eicon-product-meta';
    }

    public function getCategories()
    {
        return ['listing-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'listing', 'category', 'block'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_listing_block',
            [
                'label' => __('Listing Block'),
            ]
        );

        $groups = [
            '' => __('Select...'),
            'listing' => [
                'label' => __('Listing'),
                'options' => [
                    'ce_product_list_header' => __('Header'),
                    'ce_product_list_top' => __('Top'),
                    'ce_product_list_active_filters' => __('Active Filters'),
                    'ce_product_list' => __('Products'),
                    'ce_product_list_bottom' => __('Bottom'),
                    'ce_product_list_footer' => __('Footer'),
                ],
            ],
        ];
        if (is_admin() && Plugin::$instance->documents->getCurrent()->getTemplateType() === 'listing-category') {
            $groups['category'] = [
                'label' => __('Category'),
                'options' => [
                    'ce_subcategory_list' => __('Subcategories'),
                ],
            ];
        }
        $this->addControl(
            'block',
            [
                'label_block' => true,
                'type' => ControlsManager::SELECT,
                'groups' => &$groups,
            ]
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $block = $this->getSettings('block');
        $products = &$GLOBALS['smarty']->tpl_vars['listing']->value['products'];

        if (!$block || !$products && 'ce_product_list_header' !== $block && 'ce_product_list' !== $block) {
            return;
        }
        'ce_product_list' === $block && $this->addRenderAttribute('_container', 'id', 'products');

        $products || 'ce_product_list' === $block && $block = 'ce_page_content';

        if (!$html = \CESmarty::get(_CE_TEMPLATES_ . 'front/theme/catalog/listing/block.tpl', $block)) {
            return is_preview() ? print '<div class="elementor-alert elementor-alert-danger">Selected block is missing from your theme!</div>' : null;
        }
        echo trim($html);
    }

    public function renderPlainContent()
    {
    }
}
