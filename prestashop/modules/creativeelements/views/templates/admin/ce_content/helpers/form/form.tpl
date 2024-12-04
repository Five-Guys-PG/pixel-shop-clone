{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}

{extends file="helpers/form/form.tpl"}

{block name="input_row"}
	{$smarty.block.parent}
	{if 'hook' === $input.name}
		<script>
		var $hook = $('#hook').attr('type', 'hidden');
		var $select = $('<select name="hook">').html(`
		{foreach $ce_hooks as $ce_hook}
			<option value="{$ce_hook}">{$ce_hook}</option>
		{/foreach}
		`).insertAfter($hook);

		if (!$select.find('[value="'+$hook.val()+'"]').length) {
			$('<option>', {
				value: $hook.val(),
				html: $hook.val()
			}).appendTo($select);
		}

		$select.select2({
			tags: true,
			createTag: function(params) {
				return {
					id: params.term,
					text: params.term,
					newOption: true
				};
			},
			templateResult: function(data) {
				var $result = $('<span>').text(data.text);

				if (data.newOption) {
					$result.append(" <i>(custom)</i>");
				}
				return $result;
			}
		}).val($hook.val())
			.trigger('change.select2')
		;
		</script>
	{/if}
{/block}
