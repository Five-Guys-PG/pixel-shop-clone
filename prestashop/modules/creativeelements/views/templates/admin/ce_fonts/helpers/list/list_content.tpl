{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}

{extends file="helpers/list/list_content.tpl"}

{block name="td_content"}
	{if 'preview' === $key}
		<style>{str_replace('{{BASE}}', $smarty.const.__PS_BASE_URI__, $tr.$key)}</style>
		<span style="font-family: '{$tr.$key->family|escape:'html':'UTF-8'}'; font-size: 16px;">
			Creative Elements Module Is Making The Web Beautiful!
		</span>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
