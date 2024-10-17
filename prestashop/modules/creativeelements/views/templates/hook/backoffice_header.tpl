{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
<style>
i.mi-ce {
	font-size: 14px !important;
}
i.icon-AdminParentCEContent, i.mi-ce {
	position: relative;
	height: 1em;
	width: 1.2857em;
}
i.icon-AdminParentCEContent:before, i.mi-ce:before,
i.icon-AdminParentCEContent:after, i.mi-ce:after {
	content: '';
	position: absolute;
	margin: 0;
	left: .2143em;
	top: 0;
	width: .9286em;
	height: .6428em;
	border-width: .2143em 0;
	border-style: solid;
	border-color: currentColor;
	box-sizing: content-box;
}
i.icon-AdminParentCEContent:after, i.mi-ce:after {
	top: .4286em;
	width: .6428em;
	height: 0;
	border-width: .2143em 0 0;
}
#maintab-AdminParentCreativeElements, #subtab-AdminParentCreativeElements {
	display: none;
}
</style>
{if !empty($edit_width_ce)}
<script type="text/html" id="tmpl-btn-back-to-ps">
    <a href="{$edit_width_ce|escape:'html':'UTF-8'}&amp;action=backToPsEditor" class="btn btn-default btn-back-to-ps"><i class="material-icons">navigate_before</i> {ce__('Back to PrestaShop Editor')}</a>
</script>
<script type="text/html" id="tmpl-btn-edit-with-ce">
    <a href="{$edit_width_ce|escape:'html':'UTF-8'}" class="btn pointer btn-edit-with-ce"><i class="material-icons mi-ce"></i> {ce__('Edit with Creative Elements')}</a>
</script>
{/if}