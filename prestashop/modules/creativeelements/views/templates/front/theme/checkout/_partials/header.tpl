{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
{if isset($CE_HEADER)}
	{$CE_HEADER|cefilter}
{elseif file_exists("{$smarty.const._PS_THEME_DIR_}templates/checkout/_partials/header.tpl")}
	{include file='[1]checkout/_partials/header.tpl'}
{elseif $smarty.const._PARENT_THEME_NAME_}
	{include file='parent:checkout/_partials/header.tpl'}
{/if}