{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
{if isset($CE_PAGE_INDEX)}
	{$ce_layout=$layout}
{elseif file_exists("{$smarty.const._PS_THEME_DIR_}templates/index.tpl")}
	{$ce_layout='[1]index.tpl'}
{elseif $smarty.const._PARENT_THEME_NAME_}
	{$ce_layout='parent:index.tpl'}
{/if}

{extends $ce_layout}

{if isset($CE_PAGE_INDEX)}
	{block name=_q_c_('alysum' === $smarty.const._THEME_NAME_ || 'alysum' === $smarty.const._PARENT_THEME_NAME_, 'main', 'content')}
	<section id="content">{$CE_PAGE_INDEX|cefilter}</section>
	{/block}
{/if}