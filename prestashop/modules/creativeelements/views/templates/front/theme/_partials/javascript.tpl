{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
{if file_exists("{$smarty.const._PS_THEME_DIR_}templates/_partials/javascript.tpl")}
	{include '[1]_partials/javascript.tpl'}
{elseif $smarty.const._PARENT_THEME_NAME_}
	{include 'parent:_partials/javascript.tpl'}
{/if}
<!--CE-JS-->