{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
{$layout="{$smarty.const._CE_TEMPLATES_}front/theme/layouts/layout-empty.tpl"}

{extends file="catalog/listing/{$page.page_name}.tpl"}

{block product_list_header}{capture ce_product_list_header}{$smarty.block.parent}{/capture}{/block}

{block subcategory_list append}{capture ce_subcategory_list}{$smarty.block.parent}{/capture}{/block}

{block product_list_top append}{capture ce_product_list_top}{$smarty.block.parent}{/capture}{/block}

{block product_list_active_filters}{capture ce_product_list_active_filters}{$smarty.block.parent}{/capture}{/block}

{block product_list}{capture ce_product_list}{$smarty.block.parent}{/capture}{/block}

{block product_list_bottom}{capture ce_product_list_bottom}{$smarty.block.parent}{/capture}{/block}

{block product_list_footer}{capture ce_product_list_footer}{$smarty.block.parent}{/capture}{/block}

{block page_content}{capture ce_page_content}{$smarty.block.parent}{/capture}{/block}
