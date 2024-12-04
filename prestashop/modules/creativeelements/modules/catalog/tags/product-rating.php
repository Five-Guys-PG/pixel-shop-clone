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

use CE\CoreXDynamicTagsXTag as Tag;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXCatalogXTagsXProductRating extends Tag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-rating';
    }

    public function getTitle()
    {
        return __('Product Rating');
    }

    public function getGroup()
    {
        return Module::CATALOG_GROUP;
    }

    public function getCategories()
    {
        return [Module::TEXT_CATEGORY];
    }

    public function getPanelTemplateSettingKey()
    {
        return 'type';
    }

    protected function _registerControls()
    {
        $this->addControl(
            'type',
            [
                'label' => __('Field'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'average_grade' => __('Average Grade'),
                    'nb_comments' => __('Comments Number'),
                ],
                'default' => 'average_grade',
            ]
        );
    }

    public function render()
    {
        $context = \Context::getContext();
        $product = $context->smarty->tpl_vars['product']->value;

        switch ($this->getSettings('type')) {
            case 'average_grade':
                if (isset($product['productComments'])) {
                    echo $product['productComments']['averageRating'];
                } elseif ($pcr = $context->controller->getContainer()->get('product_comment_repository', 2)) {
                    echo $pcr->getAverageGrade($product['id'], \Configuration::get('PRODUCT_COMMENTS_MODERATE'));
                }
                break;
            case 'nb_comments':
                if (isset($product['productComments'])) {
                    echo $product['productComments']['nbComments'];
                } elseif ($pcr = $context->controller->getContainer()->get('product_comment_repository', 2)) {
                    echo $pcr->getCommentsNumber($product['id'], \Configuration::get('PRODUCT_COMMENTS_MODERATE'));
                }
                break;
        }
    }

    protected function renderSmarty()
    {
        echo '{$pcr = Context::getContext()->controller->getContainer()->get(product_comment_repository, 2)}';

        if ($this->getSettings('type') === 'average_grade') {
            echo '{if $pcr}{$pcr->getAverageGrade($product.id, Configuration::get(PRODUCT_COMMENTS_MODERATE))}{/if}';
        } else {
            echo '{if $pcr}{$pcr->getCommentsNumber($product.id, Configuration::get(PRODUCT_COMMENTS_MODERATE))}{/if}';
        }
    }
}
