{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}

{extends file='page.tpl'}

{block name='page_content_container'}
	{if isset($ce_content)}
		{$ce_content.content|cefilter}
	{else}
		{$ce_template.content|cefilter}
	{/if}
{/block}
