{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "ItemList",
	"itemListElement": [
	{foreach $listing.products as $item name=products}
		{
			"@type": "ListItem",
			"position": {$smarty.foreach.products.iteration|intval},
			"name": "{$item.name}",
			"url": "{$item.url}"
		}{if !$smarty.foreach.products.last},{/if}
	{/foreach}
	]
}
</script>