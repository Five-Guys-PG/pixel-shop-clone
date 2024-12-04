{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
{if $stylesheets}
	{foreach $stylesheets.external as $stylesheet}
	<link rel="stylesheet" href="{$stylesheet.uri}" media="{$stylesheet.media}">
	{/foreach}

	{foreach $stylesheets.inline as $id => $stylesheet}
	<style id="{$id}">
	{$stylesheet.content|cefilter}
	</style>
	{/foreach}
{/if}

{foreach $javascript.external as $js}
	<script src="{$js.uri}" {$js.attribute}></script>
{/foreach}

{foreach $javascript.inline as $js}
	<script>
	{$js.content|cefilter}
	</script>
{/foreach}

{if $js_custom_vars}
	<script>
	{foreach $js_custom_vars as $var_key => $var_val}
		var {$var_key} = {json_encode($var_val)|cefilter};
	{/foreach}
	</script>
{/if}