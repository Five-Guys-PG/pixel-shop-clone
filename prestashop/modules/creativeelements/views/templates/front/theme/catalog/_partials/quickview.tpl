{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
{if isset($CE_PRODUCT_QUICK_VIEW_ID)}
	<form id="add-to-cart-or-refresh" action="{$urls.pages.cart}" method="post" style="display:none">
		<input type="hidden" name="token" value="{$static_token}">
		<input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
		<input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id">
		<input type="hidden" name="qty" value="{$product.quantity_wanted}" id="quantity_wanted">
		<input type="submit" class="ce-add-to-cart" data-button-action="add-to-cart">
	</form>
	{call_user_func('CE\\apply_filters', 'the_content', '')|cefilter}
{elseif file_exists("{$smarty.const._PS_THEME_DIR_}templates/catalog/_partials/quickview.tpl")}
	{include '[1]catalog/_partials/quickview.tpl'}
{elseif $smarty.const._PARENT_THEME_NAME_}
	{include 'parent:catalog/_partials/quickview.tpl'}
{/if}