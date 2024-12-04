/*!
 * Creative Elements - live Theme & Page Builder
 * Copyright 2019-2024 WebshopWorks.com
 */

jQuery(function ($) {
	if ('#license' === location.hash) {
		history.replaceState({}, document.title, location.pathname + location.search);

		$('#modal_license').modal();
	}

	var $regenerate = $('#page-header-desc-configuration-regenerate-css'),
		$replace = $(document.replace_url);

	$regenerate
		.attr({
			title: '<p style="margin:0 -10px; width:182px;">' + $regenerate.attr('onclick').substr(2) + '</p>',
		})
		.tooltip({
			html: true,
			placement: 'bottom',
		})
		.on('click.ce', function onClickRegenerateCss() {
			if ($regenerate.find('.icon-spin').length) {
				return;
			}
			$regenerate.find('i').attr('class', 'process-icon-reload icon-rotate-right icon-spin');

			$.post(
				location.href,
				{
					ajax: true,
					action: 'regenerate_css',
				},
				function onSuccessRegenerateCss(resp) {
					$regenerate.find('i').attr('class', 'process-icon-ok');
				},
				'json'
			);
		})
		.removeAttr('onclick')
	;
	$replace.on('submit.ce', function onSubmitReplaceUrl(event) {
		event.preventDefault();

		if ($replace.find('.icon-spin').length) {
			return;
		}
		$replace.find('i').attr('class', 'icon-circle-o-notch icon-spin');

		$.post(
			location.href,
			$(this).serialize(),
			function onSuccessReplaceUrl(resp) {
				if (resp.success) {
					document.replace_url.reset();
				}
				$replace.find('i').attr('class', 'icon-refresh');

				$replace.find('.alert').attr({
					'class': 'alert alert-' + (resp.success ? 'success' : 'danger')
				}).html(resp.data);
			},
			'json'
		);
	});

	document.forms.configuration_form.subTab.value = $('#head_tabs .current').attr('id').replace('subtab-', '');

	$('#head_tabs').on('click', 'a', function onClickTab(event) {
		event.preventDefault();

		var tab = this.id.replace('subtab-', ''),
			url = location.pathname + location.search.replace(/&subTab=\w+/, '');

		$('#head_tabs a.current').removeClass('current');
		$(this).addClass('current');
		$('#configuration_fieldset_' + tab).removeClass('hidden').siblings().addClass('hidden');

		document.forms.configuration_form.subTab.value = tab;
		history.replaceState({}, document.title, url + '&subTab=' + tab);
	});

	$('input[name=elementor_stretched_section_container]').attr({
		placeholder: 'body',
	});
	$('input[name=elementor_viewport_lg]').attr({
		type: 'number',
		min: 769,
		max: 1439,
	});
	$('input[name=elementor_viewport_md]').attr({
		type: 'number',
		min: 481,
		max: 1024,
	});
	$('input[name=elementor_assets_priority]').attr({
		type: 'number',
		min: 50,
		max: 999,
	});
});
