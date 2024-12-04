{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
{$layout="{$smarty.const._CE_TEMPLATES_}front/theme/layouts/layout-empty.tpl"}

{extends file='catalog/product.tpl'}

{block page_content}{capture ce_page_content}{$smarty.block.parent}{/capture}{/block}

{block product_flags append}{capture ce_product_flags}{$smarty.block.parent}{/capture}{/block}

{block product_cover_thumbnails append}
	{capture ce_product_cover_thumbnails}{$smarty.block.parent}{/capture}
	{include file='catalog/_partials/product-images-modal.tpl'}
{/block}

{block product_prices}{capture ce_product_prices}{$smarty.block.parent}{/capture}{/block}

{block product_customization}{capture ce_product_customization}{$smarty.block.parent}{/capture}{/block}

{block product_pack}{capture ce_product_pack}{$smarty.block.parent}{/capture}{/block}

{block product_discounts}{capture ce_product_discounts}{$smarty.block.parent}{/capture}{/block}

{block product_availability}{capture ce_product_availability}{$smarty.block.parent}{/capture}{/block}

{block product_minimal_quantity}{capture ce_product_minimal_quantity}{$smarty.block.parent}{/capture}{/block}

{block product_quantity}{capture ce_product_actions}{hook h='displayProductActions' product=$product}{/capture}{/block}

{block product_additional_info}{capture ce_product_additional_info}{$smarty.block.parent}{/capture}{/block}

{block hook_display_reassurance}{capture ce_hook_display_reassurance}{$smarty.block.parent}{/capture}{/block}

{block product_tabs}{capture ce_product_tabs}{$smarty.block.parent}{/capture}{/block}

{block product_reference append}{capture ce_product_reference}{$smarty.block.parent}{/capture}{/block}

{block product_quantities append}{capture ce_product_quantities}{$smarty.block.parent}{/capture}{/block}

{block product_availability_date append}{capture ce_product_availability_date}{$smarty.block.parent}{/capture}{/block}

{block product_out_of_stock append}{capture ce_product_out_of_stock}{$smarty.block.parent}{/capture}{/block}

{block product_features append}{capture ce_product_features}{$smarty.block.parent}{/capture}{/block}

{block product_specific_references append}{capture ce_product_specific_references}{$smarty.block.parent}{/capture}{/block}

{block product_condition append}{capture ce_product_condition}{$smarty.block.parent}{/capture}{/block}

{block product_accessories}{capture ce_product_accessories}{$smarty.block.parent}{/capture}{/block}

{block product_footer}{capture ce_product_footer}{$smarty.block.parent}{/capture}{/block}
