{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
{if isset($CE_PAGE_CONTACT)}
	{$ce_layout=$layout}
{elseif file_exists("{$smarty.const._PS_THEME_DIR_}templates/contact.tpl")}
	{$ce_layout='[1]contact.tpl'}
{elseif $smarty.const._PARENT_THEME_NAME_}
	{$ce_layout='parent:contact.tpl'}
{/if}

{extends $ce_layout}

{if isset($CE_PAGE_CONTACT)}
	{block name='content'}<section id="content">{$CE_PAGE_CONTACT|cefilter}</section>{/block}
{/if}