{**
 * Creative Elements - live PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
{if isset($CE_PRODUCT_MINIATURE_UID) && get_class($CE_PRODUCT_MINIATURE_UID) === 'CE\\UId' && (
	file_exists("{$smarty.const._CE_TEMPLATES_}front/theme/catalog/_partials/miniatures/product-$CE_PRODUCT_MINIATURE_UID.tpl") ||
	CE\Plugin::$instance->documents->get($CE_PRODUCT_MINIATURE_UID)->saveTpl()
)}
	{if !isset($productClasses)}
		{if !isset($layout)}
			{$layout = Context::getContext()->controller->getLayout()}
		{/if}
		{if preg_match('/(left|right|both)-column/', $layout)}
			{$productClasses = 'col-xs-6 col-xl-4'}
		{else}
			{$productClasses = 'col-xs-6 col-xl-3'}
		{/if}
	{/if}
	{include "catalog/_partials/miniatures/product-$CE_PRODUCT_MINIATURE_UID.tpl"}
{elseif file_exists("{$smarty.const._PS_THEME_DIR_}templates/catalog/_partials/miniatures/product.tpl")}
	{include '[1]catalog/_partials/miniatures/product.tpl'}
{elseif $smarty.const._PARENT_THEME_NAME_}
	{include 'parent:catalog/_partials/miniatures/product.tpl'}
{/if}