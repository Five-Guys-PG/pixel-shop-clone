{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
{if isset($CE_PRODUCT)}
	{$ce_layout=$layout}
{elseif file_exists("{$smarty.const._PS_THEME_DIR_}templates/catalog/product.tpl")}
	{$ce_layout='[1]catalog/product.tpl'}
{elseif $smarty.const._PARENT_THEME_NAME_}
	{$ce_layout='parent:catalog/product.tpl'}
{/if}

{extends $ce_layout}

{if isset($CE_PRODUCT)}
	{block name='head' append}
	{include file='catalog/_partials/microdata/product-jsonld.tpl'}

	<meta property="og:type" content="product">
	{if version_compare($smarty.const._PS_VERSION_, '1.7.8', '<')}
		<meta property="og:title" content="{$page.meta.title}">
		<meta property="og:description" content="{$page.meta.description}">
		<meta property="og:url" content="{$urls.current_url}">
		<meta property="og:site_name" content="{$shop.name}">
	{/if}
	{if $product.cover}
		<meta property="og:image" content="{$product.cover.large.url}">
	{/if}
	{if $product.show_price}
		<meta property="product:pretax_price:amount" content="{$product.price_tax_exc}">
		<meta property="product:pretax_price:currency" content="{$currency.iso_code}">
		<meta property="product:price:amount" content="{$product.price_amount}">
		<meta property="product:price:currency" content="{$currency.iso_code}">
	{/if}
	{if !empty($product.weight)}
		<meta property="product:weight:value" content="{$product.weight}">
		<meta property="product:weight:units" content="{$product.weight_unit}">
	{/if}
	{/block}

	{block name='content'}
	<section id="content" style="max-width: none">
		<form id="add-to-cart-or-refresh" action="{$urls.pages.cart}" method="post" style="display:none">
			<input type="hidden" name="token" value="{$static_token}">
			<input type="hidden" name="id_product" value="{$product.id|intval}" id="product_page_product_id">
			<input type="hidden" name="id_customization" value="{$product.id_customization|intval}" id="product_customization_id">
			<input type="hidden" name="qty" value="{$product.quantity_wanted|intval}" id="quantity_wanted"
				{if $product.show_quantities}data-stock="{$product.quantity}" data-allow-oosp="{$product.allow_oosp}"{/if}>
			<input type="submit" class="ce-add-to-cart" data-button-action="add-to-cart">
		</form>
		{$CE_PRODUCT|cefilter}
	</section>
	{/block}
{/if}