{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}

{extends file="helpers/options/options.tpl"}

{block name="input" append}
	{if 'multiselect' === $field.type}
		<div class="col-lg-9">
		{if $field.list}
			{$selected = json_decode(Configuration::get($key))}
			{if !$selected}{$selected = []}{/if}
			<select multiple class="form-control fixed-width-xxl {if isset($field.class)}{$field.class}{/if}" name="{$key}[]"{if isset($field.js)} onchange="{$field.js}"{/if} id="{$key}" {if isset($field.size)} size="{$field.size}"{/if}>
				{foreach $field.list as $option}
					<option value="{$option[$field.identifier]}"{if in_array($option[$field.identifier], $selected)} selected="selected"{/if}>{$option.name}</option>
				{/foreach}
			</select>
		{elseif isset($input.empty_message)}
			{$input.empty_message}
		{/if}
		</div>
	{/if}
{/block}