{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
{if file_exists("{$smarty.const._PS_THEME_DIR_}templates/cms/category.tpl")}
	{$ce_layout='[1]cms/category.tpl'}
{elseif $smarty.const._PARENT_THEME_NAME_}
	{$ce_layout='parent:cms/category.tpl'}
{/if}

{extends $ce_layout}

{block name='page_content'}
	{capture assign='ce_page_content'}{$smarty.block.parent}{/capture}
{if strpos($ce_page_content, 'elementor-id') === false}
	{$cms_category.description|cefilter}
{/if}
	{$ce_page_content|cefilter}
{/block}