{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
{if file_exists("{$smarty.const._PS_THEME_DIR_}templates/_partials/helpers.tpl")}
	{include '[1]_partials/helpers.tpl'}
{elseif $smarty.const._PARENT_THEME_NAME_ && file_exists("{$smarty.const._PS_PARENT_THEME_DIR_}templates/_partials/helpers.tpl")}
	{include 'parent:_partials/helpers.tpl'}
{/if}
<!doctype html>
<html lang="{$language.iso_code}">
<head>
	{block name='head'}
		{include file='_partials/head.tpl'}
	{/block}
</head>
<body id="{$page.page_name}" class="{$page.body_classes|classnames}">
	{block name='hook_after_body_opening_tag'}
		{hook h='displayAfterBodyOpeningTag'}
	{/block}
	<main>
		{block name='product_activation'}
			{include file='catalog/_partials/product-activation.tpl'}
		{/block}
		<header id="header">
			{block name='header'}
				{include file='_partials/header.tpl'}
			{/block}
		</header>
		{block name='notifications'}
			{include file='_partials/notifications.tpl'}
		{/block}
		{block name="content"}{/block}
		<footer id="footer">
			{block name="footer"}
				{include file="_partials/footer.tpl"}
			{/block}
		</footer>
	</main>
	{block name='javascript_bottom'}
		{include file="_partials/javascript.tpl" javascript=$javascript.bottom}
	{/block}
	{block name='hook_before_body_closing_tag'}
		{hook h='displayBeforeBodyClosingTag'}
	{/block}
</body>
</html>