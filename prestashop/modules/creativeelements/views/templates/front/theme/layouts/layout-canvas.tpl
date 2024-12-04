{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
<!doctype html>
<html lang="{$language.iso_code}">
<head>
	{block name='head'}
		{include file='_partials/head.tpl'}
	{/block}
</head>
<body id="{$page.page_name}" class="{$page.body_classes|classnames}">
	<main>
		{block name='notifications'}
			{include file='_partials/notifications.tpl'}
		{/block}
		{$ce_desc.description|cefilter}
	</main>
	{block name='javascript_bottom'}
		{include file="_partials/javascript.tpl" javascript=$javascript.bottom}
	{/block}
</body>
</html>