{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}

{capture ce_alert}<div class="alert alert-%s">%s</div>{/capture}

{capture ce_undefined_position}
	{ce__('Undefined Position!')}
	<a href="http://docs.webshopworks.com/creative-elements/79-troubleshooting/337-undefined-position" class="ce-read-more">{ce__('Learn More')}</a>
{/capture}

{capture ce_fancybox_close}<script>top.$.fancybox.close()</script>{/capture}

{capture ce_action_link}<a href="%s" target="%s"><i class="icon-%s"></i> %s</a>{/capture}

{capture ce_modal_license_status}{ce__('License Status:')} <span class="text-success">{ce__('Active')}</span>{/capture}

{capture ce_modal_license}
	<form name="activate" action="%s" method="post">
		<div class="modal-body">{'Please activate your license to get unlimited access to the template library.'}</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary"><i class="icon-file-text"></i>&nbsp; {ce__('Activate')}</button>
		</div>
	</form>
{/capture}

{capture ce_modal_license_switch}
	<form name="activate" action="%s" method="post">
		<div class="modal-body">{ce__('Your website is activated.')}</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary" data-dismiss="modal">{ce__('OK')}</button>
			<button type="submit" class="btn"><i class="icon-file-text"></i>&nbsp; {ce__('Switch License')}</button>
		</div>
	</form>
{/capture}

{capture ce_modal_replace_url}
	<form name="replace_url" class="form-horizontal">
		<div class="modal-body">
			<input type="hidden" name="ajax" value="1">
			<input type="hidden" name="action" value="replace_url">
			<div class="alert alert-warning">%s</div>
			<div class="form-group">
				<div class="col-sm-6">
					<input type="url" placeholder="%s" class="form-control" name="from" required>
				</div>
				<div class="col-sm-6">
					<input type="url" placeholder="%s" class="form-control" name="to" required>
				</div>
			</div>
			<div class="help-block">%s</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary"><i class="icon-refresh"></i>&nbsp; %s</button>
		</div>
	</form>
{/capture}

{function ce_preview_breadcrumb links=[]}
	{$last = array_pop($links)}
	{foreach $links as $link}
		<a>{$link.title|cleanHtml}</a><span class="navigation-pipe">&gt;</span>
	{/foreach}
	{$last.title|cleanHtml}
{/function}

{capture ce_inline_script}
	<script data-cfasync="false">
	%s
	</script>
{/capture}

{function ce_add_custom_font}
	<div class="elementor-metabox-content">
		<div class="elementor-field font_face elementor-field-repeater">
			<script type="text/template" id="tmpl-elementor-add-font">
				<div class="repeater-block block-visible">
					<span class="repeater-title ce-hidden" data-default="{ce__('Settings')}" data-selector=".font_weight">{ce__('Settings')}</span>
					<span class="elementor-repeater-tool-btn close-repeater-row" title="{ce__('Close')}">
						<i aria-hidden="true" class="icon-times"></i> {ce__('Close')}
					</span>
					<span class="elementor-repeater-tool-btn toggle-repeater-row" title="{ce__('Edit')}">
						<i aria-hidden="true" class="icon-edit"></i> {ce__('Edit')}
					</span>
					<span class="elementor-repeater-tool-btn remove-repeater-row" data-confirm="{ce__('Are you sure?')}" title="{ce__('Delete')}">
						<i aria-hidden="true" class="icon-trash"></i> {ce__('Delete')}
					</span>
					<div class="repeater-content form-table">
						<div class="repeater-content-top">
							<div class="elementor-field font_face elementor-field-select">
								<p class="elementor-field-label">
									<label for="font_face[__counter__][font_weight]">{ce__('Weight')}:</label>
								</p>
								<select class="font_weight" id="font_face[__counter__][font_weight]" name="font_face[__counter__][font_weight]">
									<option value="normal">{ce__('Normal')}</option>
									<option value="bold">{ce__('Bold')}</option>
									<option value="100">100</option>
									<option value="200">200</option>
									<option value="300">300</option>
									<option value="400">400</option>
									<option value="500">500</option>
									<option value="600">600</option>
									<option value="700">700</option>
									<option value="800">800</option>
									<option value="900">900</option>
								</select>
							</div>
							<div class="elementor-field font_face elementor-field-select">
								<p class="elementor-field-label">
									<label for="font_face[__counter__][font_style]">{ce__('Style')}:</label>
								</p>
								<select class="font_style" id="font_face[__counter__][font_style]" name="font_face[__counter__][font_style]">
									<option value="normal">{ce__('Normal')}</option>
									<option value="italic">{ce__('Italic')}</option>
									<option value="oblique">{ce__('Oblique')}</option>
								</select>
							</div>
							<div class="inline-preview">Creative Elements Module is Making The Web Beautiful!</div>
							<div class="elementor-field font_face elementor-field-toolbar">
								<span class="elementor-repeater-tool-btn close-repeater-row" title="{ce__('Close')}">
									<i aria-hidden="true" class="icon-times"></i> {ce__('Close')}
								</span>
								<span class="elementor-repeater-tool-btn toggle-repeater-row" title="{ce__('Edit')}">
									<i aria-hidden="true" class="icon-edit"></i> {ce__('Edit')}
								</span>
								<span class="elementor-repeater-tool-btn remove-repeater-row" data-confirm="Are you sure?" title="{ce__('Delete')}">
									<i aria-hidden="true" class="icon-trash"></i> {ce__('Delete')}
								</span>
							</div>
						</div>
						<div class="repeater-content-bottom">
							<div class="elementor-field font_face elementor-field-file">
								<p class="elementor-field-label">
									<label for="font_face[__counter__][woff2]file">{sprintf(ce__('%s File'), 'WOFF2')}</label>
								</p>
								<input class="hidden" name="font_face[__counter__][woff2][file]" type="file" accept=".woff2,font/woff2">
								<input class="elementor-field-input" name="font_face[__counter__][woff2][url]" placeholder="{ce__('The Web Open Font Format 2, Used by Super Modern Browsers')}" type="text">
								<input class="elementor-button elementor-upload-btn" data-ext="woff2" data-preview_anchor="none" data-remove_text="{ce__('Delete')}" data-upload_text="{ce__('Upload')}" id="font_face[__counter__][woff2]" name="font_face[__counter__][woff2]" type="button">
							</div>
							<div class="elementor-field font_face elementor-field-file">
								<p class="elementor-field-label">
									<label for="font_face[__counter__][woff]file">{sprintf(ce__('%s File'), 'WOFF')}</label>
								</p>
								<input class="hidden" name="font_face[__counter__][woff][file]" type="file" accept=".woff,font/woff">
								<input class="elementor-field-input" name="font_face[__counter__][woff][url]" placeholder="{ce__('The Web Open Font Format, Used by Modern Browsers')}" type="text">
								<input class="elementor-button elementor-upload-btn" data-ext="woff" data-preview_anchor="none" data-remove_text="{ce__('Delete')}" data-upload_text="{ce__('Upload')}" id="font_face[__counter__][woff]" name="font_face[__counter__][woff]" type="button">
							</div>
							<div class="elementor-field font_face elementor-field-file">
								<p class="elementor-field-label">
									<label for="font_face[__counter__][ttf]file">{sprintf(ce__('%s File'), 'TTF')}</label>
								</p>
								<input class="hidden" name="font_face[__counter__][ttf][file]" type="file" accept=".ttf,font/ttf">
								<input class="elementor-field-input" name="font_face[__counter__][ttf][url]" placeholder="{ce__('TrueType Fonts, Used for better supporting Safari, Android, iOS')}" type="text">
								<input class="elementor-button elementor-upload-btn" data-ext="ttf" data-preview_anchor="none" data-remove_text="{ce__('Delete')}" data-upload_text="{ce__('Upload')}" id="font_face[__counter__][ttf]" name="font_face[__counter__][ttf]" type="button">
							</div>
							<div class="elementor-field font_face elementor-field-file">
								<p class="elementor-field-label">
									<label for="font_face[__counter__][otf]file">{sprintf(ce__('%s File'), 'OTF')}</label>
								</p>
								<input class="hidden" name="font_face[__counter__][otf][file]" type="file" accept=".otf,font/otf">
								<input class="elementor-field-input" name="font_face[__counter__][otf][url]" placeholder="{ce__('OpenType Fonts, Used for better supporting Safari, Android, iOS')}" type="text">
								<input class="elementor-button elementor-upload-btn" data-ext="otf" data-preview_anchor="none" data-remove_text="{ce__('Delete')}" data-upload_text="{ce__('Upload')}" id="font_face[__counter__][otf]" name="font_face[__counter__][otf]" type="button">
							</div>
						</div>
					</div>
				</div>
			</script>
			<input type="button" class="elementor-button add-repeater-row" value="{ce__('Add Font Variation')}" data-template-id="tmpl-elementor-add-font">
		</div>
	</div>
{/function}

{function ce_add_custom_icon}
	<script type="text/template" id="elementor-custom-icons-template-footer">
		{* <div class="elementor-icon-set-footer">{literal}Created on: {{day}}/{{mm}}/{{year}}, {{hour}}:{{minute}}{/literal}</div> *}
	</script>

	<script type="text/template" id="elementor-custom-icons-template-header">
		<div class="elementor-icon-set-header">
			<div><span class="elementor-icon-set-header-meta">{ce__('Type')}: </span><span class="elementor-icon-set-header-meta-value">{literal}{{custom_icon_type}}{/literal}</span></div>
			<div><span class="elementor-icon-set-header-meta">{ce__('CSS Prefix')}: </span><span class="elementor-icon-set-header-meta-value">{literal}{{prefix}}{/literal}</span></div>
			<div><span class="elementor-icon-set-header-meta">{ce__('Icons Count')}: </span><span class="elementor-icon-set-header-meta-value">{literal}{{count}}{/literal}</span></div>
			{* <div class="elementor-icon-set-header-meta-remove"><div class="remove"><i class="icon-trash"></i> {ce__('Remove')}</div></div> *}
		</div>
	</script>

	<script type="text/template" id="elementor-custom-icons-template-duplicate-prefix">
		<div class="elementor-icon-set-duplicate-prefix">
			{ce__('The Icon Set prefix already exists in your site. In order to avoid conflicts we recommend to use a unique prefix per Icon Set.')}
		</div>
	</script>
	<div id="elementor-custom-icons-metabox">
		<div class="elementor-custom-icons-metabox"></div>
		<div class="elementor-dropzone-field zip_upload">
			<div class="box__input">
				<div class="elementor--dropzone--upload__icon">
					<i class="icon-upload"></i>
				</div>
				<input type="file" name="zip_upload" id="zip_upload" accept="zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed" class="box__file">
				<br>
				<h4><span class="box__dragndrop">{ce__('Drag & Drop to Upload')}</span></h4>
				<h5>{ce__('Your Fontello, IcoMoon or Fontastic .zip file')}</h5>
				<br>
				<div class="elementor-button elementor--dropzone--upload__browse">
					<span>{ce__('Click here to browse')}</span>
				</div>
			</div>
			<div class="box__uploading">Uploading…</div>
			<div class="box__success">Done!</div>
			<div class="box__error">Error!</div>
		</div>
	</div>
{/function}
