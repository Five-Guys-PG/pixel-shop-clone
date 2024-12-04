/*!
 * Creative Elements - live Theme & Page Builder
 * Copyright 2019-2024 WebshopWorks.com
 */

window.ceAdmin && document.addEventListener('DOMContentLoaded', () => {
	// Cancel button fix
	$('.btn[id$=_form_cancel_btn]')
		.removeAttr('onclick')
		.attr('href', location.href.replace(/&id\w*=\d+|&(add|update)\w+(=[^&]*)?/g, ''))
	;

	// Fix for shortcode
	$('.ce-shortcode input').on('click.ce', function(e) {
		this.select();
	}).parent()
		.removeAttr('onclick')
		.removeClass('pointer')
	;

	// Fix for after ajax save new ybc_blog post update links
	history.pushState = (function(parent) {
		return function(data, title, url) {
			var id = url.match(/&id_post=(\d+)/);

			id && $('.btn-edit-with-ce').each(function() {
				this.href = this.href.replace('&id_page=0', '&id_page=' + id[1]);
			});
			return parent.apply(this, arguments);
		};
	})(history.pushState);

	// HiBlog compatibility
	~location.href.indexOf('configure=hiblog') && $(document).on('ajaxSuccess', function onAjaxSuccess(e, xhr, args) {
		var idPost = $('#id_post').val();

		idPost && (args.data.get && args.data.get('action') === 'savePost' || ~args.data.indexOf('action=displayPostForm')) && $.post(
			$('a[href*=AdminCEContent]').prop('href'),
			{
				action: 'hideEditor',
				ajax: true,
				id: idPost,
				idType: 16,
			},
			function onSuccessHideEditor(data) {
				ceAdmin.hideEditor = data;
				ceAdmin.uid = idPost + ceAdmin.uid.slice(-6);
				ceAdmin.$contents = ceAdmin.getContents('textarea[name^=blog_description_]');

				$('.wrapper-edit-with-ce').prevAll('.mce-tinymce').hide(0);
			},
			'json'
		);
	});

	// Quick Edit
	var column = /^1\.7\.[0-7]\./.test(_PS_VERSION_) ? 'td:nth-of-type(2)' : 'td[class*="-id_"]',
		selectors = [
			'table.ce_content ' + column,
			'table.ce_theme ' + column,
			'table.ce_template ' + column,
			'table.product td:nth-of-type(2)',
			'table.category td:nth-of-type(2)',
			'table#product_grid_table td[class*="-id_"]',
			'table#category_grid_table td[class*="-id_"]',
			'table:is(.cms, #cms_page_grid_table) ' + column
		];

	if (ceAdmin.editManufacturers) {
		selectors.push('table.manufacturer td:nth-of-type(2)');
		selectors.push('table#manufacturer_grid_table td[class*="-id_"]');
	}
	if (ceAdmin.editSuppliers) {
		selectors.push('table.supplier td:nth-of-type(2)');
		selectors.push('table#supplier_grid_table td[class*="-id_"]');
	}
	$(selectors.join()).get().forEach(function (td) {
		var template = $('#form-ce_template').length,
			id = parseInt( $(td).text() ),
			idShop = template ? '00' : ceAdmin.uid.substr(-2),
			idLang = template ? '00' : ('0' + ceAdmin.languages[0].id_lang).substr(-2),
			idType = template ? '01' : ceAdmin.uid.substr(-6, 2),
			$btnGroup = $(td.parentNode).find('.btn-group-action');

		if ($.fn.pstooltip) {
			$('<div class="btn-group ce-edit" data-toggle="pstooltip" data-original-title="' + ceAdmin.i18n.edit + '" data-placement="left">').html(
				ceAdmin.languages.length > 1 && !template
				?
				'<a class="btn dropdown-toggle dropdown-item" data-toggle="dropdown">' +
					'<i class="material-icons mi-ce"></i>' +
				'</a>' +
				'<div class="dropdown-menu dropdown-menu-right">' +
					ceAdmin.languages.map(function (lang) {
						return '<a href="' + ceAdmin.editorUrl + id + idType + ('0' + lang.id_lang).substr(-2) + idShop + '" class="btn dropdown-item">' + lang.name + '</a>';
					}).join('') +
				'</div>'
				:
				'<a class="btn dropdown-item" href="' + ceAdmin.editorUrl + id + idType + idLang + idShop + '">' +
					'<i class="material-icons mi-ce"></i>' +
				'</a>'
			).prependTo($btnGroup).pstooltip();
		} else {
			$('<div class="btn-group pull-right ce-edit" title="' + ceAdmin.i18n.edit + '">').html(
				ceAdmin.languages.length > 1 && !template
				?
				'<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">' +
					'<i class="icon-AdminParentCEContent"></i>' +
				'</button>' +
				'<ul class="dropdown-menu">' +
					ceAdmin.languages.map(function (lang) {
						return '<li><a href="' + ceAdmin.editorUrl + id + idType + ('0' + lang.id_lang).substr(-2) + idShop + '">' + lang.name + '</a></li>';
					}).join('') +
				'</ul>'
				:
				'<a class="btn btn-default" href="' + ceAdmin.editorUrl + id + idType + idLang + idShop + '">' +
					'<i class="icon-AdminParentCEContent"></i>' +
				'</a>'
			).prependTo($btnGroup);
		}
	});
	// Minor CSS fix
	$('.btn-group-action a.product-edit.tooltip-link').addClass('dropdown-item');

	// Import Template
	var $import = $('.ce-import-panel').removeClass('hide')
		.parent().slideUp(0).insertBefore('#form-ce_template')
	;
	$('.ce-import-panel #file').attr({
		accept: '.json,.zip',
		required: true,
	});

	// Auto open Theme Settings tab
	document.body.classList.contains('admincethemes') && $(() => {
		var index = $('#head_tabs .current').parent().index() - 2;
		if (index > 0) {
			$('#ce_theme_form .nav-tabs a').eq(index).click();
		}
	});

	// Handler functions
	ceAdmin.onClickImport = function() {
		$import.hasClass('visible')
			? $import.removeClass('visible').slideUp(300)
			: $import.addClass('visible').slideDown(300)
		;
	};
	ceAdmin.onClickBtnBack = function(e) {
		ceAdmin.checkChanges = true;
	};
	ceAdmin.onClickBtnWrapper = function(e) {
		this.children[0].click();
	};
	ceAdmin.onClickBtnEdit = function(e) {
		e.stopPropagation();
		ceAdmin.checkChanges = true;

		if ('0' === ceAdmin.uid[0]) {
			if (document.body.classList.contains('adminmaintenance')) {
				return this.href += '&action=addMaintenance';
			}
			ceAdmin.checkChanges = e.preventDefault();
			return alert(ceAdmin.i18n.save);
		}
	};

	// Button templates
	var tmplBtnBack = $('#tmpl-btn-back-to-ps').html(),
		tmplBtnEdit = $('#tmpl-btn-edit-with-ce').html();

	if (ceAdmin.footerProduct) {
		const tag = $('.product-page-v2').length ? 'h3' : 'h2';
		var $tf = $('<div class="translationsFields tab-content">').wrap('<div class="translations tabbable">');
		$tf.parent()
			.insertAfter('#related-product, :has(>#product_description_related_products)')
			.wrap('<div class="form-group">')
			.before(`<${tag} class="ce-product-hook">displayFooterProduct</${tag}>`)
		;

		$('textarea[id*=description_short_]').each(function(i, el) {
			var idLang = el.id.split('_').pop(),
				lang = el.parentNode.className.match(/translation-label-(\w+)/),
				$btn = $(tmplBtnEdit).click(ceAdmin.onClickBtnEdit);

			if ('0' === ceAdmin.footerProduct[0]) {
				$btn[0].href += '&action=addFooterProduct&uid=' + (1*ceAdmin.uid + 100*idLang);
			} else {
				$btn[0].href += '&uid=' + (1*ceAdmin.footerProduct + 100*idLang) + '&footerProduct=' + ceAdmin.uid.slice(0, -6);
			}
			$('<div class="translation-field tab-pane">')
				.addClass(lang ? 'translation-label-'+lang[1] : '')
				.addClass(el.parentNode.classList.contains('active') ? 'active' : '')
				.addClass(el.parentNode.classList.contains('visible') ? 'visible' : '')
				.append($btn)
				.appendTo($tf)
			;
		});
	}

	ceAdmin.getContents = function(selector) {
		if (!ceAdmin.editSuppliers && $('form[name=supplier]').length ||
			!ceAdmin.editManufacturers && $('form[name=manufacturer]').length
		) {
			return;
		}
		return $(selector).each(function(i, el) {
			var idLang = parseInt(el[el.id ? 'id' : 'name'].split('_').pop()) || 0,
				$btn = $(tmplBtnEdit).insertBefore(el).click(ceAdmin.onClickBtnEdit);

			$btn[0].href += '&uid=' + (1*ceAdmin.uid + 100*idLang);

			if (~ceAdmin.hideEditor.indexOf(idLang)) {
				$(tmplBtnBack).insertBefore($btn).click(ceAdmin.onClickBtnBack)[0].href += '&uid=' + (1*ceAdmin.uid + 100*idLang);
				$btn.wrap('<div class="wrapper-edit-with-ce">').parent().click(ceAdmin.onClickBtnWrapper);
				$(el).hide().next('.maxLength').hide();
			} else {
				$btn.after('<br>');
			}
		});
	};

	ceAdmin.$contents = ceAdmin.getContents([
		'body:not(.adminproducts) textarea[name^=content_]:not([name*=short])',
		'body:not(.adminproducts) textarea[name*="[content]"]',
		'body:not(.adminpsblogblogs) textarea[name^=description_]:not([name*=short])',
		'textarea[name*="[description]"]:not([name*=short])',
		'textarea[name^=post_content_]',
		'textarea[name=content]',
		'.adminmaintenance textarea'
	].join());

	ceAdmin.form = ceAdmin.$contents[0] && ceAdmin.$contents[0].form;
	ceAdmin.formChanged = false;

	$(function() {
		// run after jQuery's document ready
		$(ceAdmin.form).one('change', ':input', function() {
			ceAdmin.formChanged = true;
		});
	});
	$(window).on('beforeunload', function() {
		if (ceAdmin.checkChanges && ceAdmin.formChanged) {
			delete ceAdmin.checkChanges;
			return "Changes you made may not be saved!";
		}
	});
});

if (/[&\?]perm=/.test(location.search)) {
	history.replaceState({}, document.title, location.href.replace(/[&\?]perm=\d+/, ''));
}
