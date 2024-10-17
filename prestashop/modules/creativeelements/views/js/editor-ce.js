/*!
 * Creative Elements - live Theme & Page Builder
 * Copyright 2019-2024 WebshopWorks.com
 */

$(window).on('elementor:init', function onElementorInit() {
	if ('product-miniature' === elementor.config.document.type) {
		var pageModel = elementor.settings.page.model;

		$('#elementor-preview-responsive-wrapper').css({
			height: '100%',
			margin: '0 auto',
			padding: 0,
			transitionDuration: '0s',
		});
		elementor.on('preview:loaded', function () {
			var minWidth = pageModel.getControl('preview_width').min,
				controlView;
			$(elementor.$previewContents[0].body).css({
				width: pageModel.get('preview_width'),
				minWidth: minWidth,
			}).resizable({
				handles: 'e, w',
				start: function () {
					var pageView = elementor.getPanelView().getCurrentPageView(),
						device = ceFrontend.getCurrentDeviceMode();
					controlView = 'preview_settings' !== pageView.activeSection ? null : pageView.getControlViewByName(
						'desktop' === device ? 'preview_width' : 'preview_width_' + device
					);
					elementor.$previewContents[0].documentElement.style.cursor = 'ew-resize';
				},
				resize: function (e, ui) {
					var device = ceFrontend.getCurrentDeviceMode(),
						width = 2 * (ui.size.width - ui.originalSize.width) + ui.originalSize.width;
					if (width < minWidth) {
						width = minWidth;
					}
					this.style.width = width + 'px';
					this.style.left = '';
					this.style.right = '';

					pageModel.set('desktop' === device ? 'preview_width' : 'preview_width_' + device, width);

					controlView && controlView.render();
				},
				stop: function () {
					elementor.$previewContents[0].documentElement.style.cursor = '';
					elementor.$previewContents.children().on('click.ce-resize', function (e) {
						e.stopPropagation();
					});
					setTimeout(function () {
						elementor.$previewContents.children().off('click.ce-resize');
					});
				},
			});
		});
		elementor.settings.page.model.on('change', function onChangePreviewWidth() {
            var device = ceFrontend.getCurrentDeviceMode(),
                preview_width = 'desktop' === device ? 'preview_width' : 'preview_width_' + device;
			if (preview_width in this.changed) {
				elementor.$previewContents[0].body.style.width = this.changed[preview_width] + 'px';
			}
		});
		elementor.channels.deviceMode.on('change', function onChangeDeviceMode() {
			var width = ceFrontend.getCurrentDeviceSetting(pageModel.attributes, 'preview_width');

			elementor.$previewContents[0].body.style.width = width + 'px';
		});
	}

	elementor.channels.editor.on('section:activated', function onSectionActivated(sectionName, editor) {
		var editedElement = editor.getOption('editedElementView'),
			widgetType = editedElement.model.get('widgetType');

		if ('flip-box' === widgetType) {
			// init flip box back
			var isSideB = ['section_b', 'section_style_b', 'section_style_button'].indexOf(sectionName) > -1,
				$backLayer = editedElement.$el.find('.elementor-flip-box-back');

			editedElement.$el.toggleClass('elementor-flip-box--flipped', isSideB);

			if (isSideB) {
				$backLayer.css('transition', 'none');
			} else {
				setTimeout(function () {
					$backLayer.css('transition', '');
				}, 10);
			}
		} else if ('ajax-search' === widgetType) {
			// init search results
			editedElement.$el.find('.elementor-search__products').css({
				display: ['section_results_style', 'section_products_style'].indexOf(sectionName) < 0 ? 'none' : ''
			});
		}
	});

	var tabNumber = '<span class="ce-tab-num"></span>';
	// Refresh Tabbed Section
	elementor.hooks.addAction('panel/open_editor/column', function (panel, model, column) {
		if (!column._parent.model.get('settings').get('tabs')) return;

		panel.getHeaderView().setTitle(elementor.translate('edit_element', [elementor.translate('tab')]));

		var index = column.$el.index(),
			$items = column.$el.parent().prev().find('a');

		ceFrontend.elements.window.jQuery($items[index]).click();

		elementor.$previewContents[0].activeElement && elementor.$previewContents[0].activeElement.blur();
	});
	// Add/Sort Tab
	elementor.channels.data.on('element:after:add drag:after:update', function (model) {
		model = model.attributes || model;

		if ('column' !== model.elType) return;

		var $column = elementor.$previewElementorEl.find('[data-id=' + model.id + ']'),
			$litems = $column.parent().prev().find('li'),
			$clone = $litems.eq(0).clone(),
			index = $column.index();

		index ? $clone.insertAfter($litems[index - 1]) : $clone.insertBefore($litems[0]);

		var $a = $clone.find('a').html(
			elementor.helpers.renderIcon( Object.values(elementor.sections.currentView.children._views)[0], model.settings.tab_icon, {}, "i" ) +
			(model.settings._title || tabNumber)
		).removeClass('elementor-item-active');

		setTimeout(function() {
			$a.click();
		});
	});
	// Remove Tab
	elementor.channels.data.on('element:before:remove', function (model) {
		model = model.attributes || model;

		if ('column' !== model.elType) return;

		var $column = elementor.$previewElementorEl.find('[data-id=' + model.id + ']');
			$items = $column.parent().prev().find('a');

		$items.eq($column.index()).parent().remove();
		$items.eq(0).click();
	});
	// Change Tab Title
	elementor.channels.editor.on('change:column:_title change:column:tab_icon', function (control, column) {
		var index = column.$el.index(),
			colSettings = control.options.elementSettingsModel;

		column.$el.parent().prev().find('a').eq(index).html(
			elementor.helpers.renderIcon( column, colSettings.get("tab_icon"), {}, "i" ) +
			(colSettings.get('_title') || tabNumber)
		);
	});
	// Enter Tab Title from Navigator
	elementor.channels.editor.on('enter:column:_title', function (model) {
		var $column = elementor.$previewElementorEl.find('.elementor-element-editable'),
			$items = $column.parent().prev().find('a'),
			index = $column.index();

		$items.eq(index).html(
			elementor.helpers.renderIcon( Object.values(elementor.sections.currentView.children._views)[0], model.get("tab_icon"), {}, "i" ) +
			(model.get('_title') || tabNumber)
		);
		$('.elementor-control-_title input[data-setting="_title"]').val(model.get('_title'));
	});
});

$(function () {elementor.on('preview:loaded', function () {
	// Compatibility fix for document.write
	var doc = elementor.$previewContents[0];
	doc.write = function write(str) {
		var script = doc.currentScript;
		setTimeout(() => {
			if (!script.parentNode) {
				script = [].slice.apply(doc.scripts).find(el => el.src && el.src === script.src || el.innerHTML && el.innerHTML === script.innerHTML);
			}
			script.insertAdjacentHTML('afterend', str);
		});
	};
	// init widgets
	ceFrontend.hooks.addAction('frontend/element_ready/widget', function ($widget, $) {
		// remote render fix
		if ($widget.find('.ce-remote-render').length) {
			var render = elementor.helpers.renderWidgets,
				widget = elementor.helpers.getModelById('' + $widget.data('id')),
				data = widget.toJSON();

			render.actions['render_' + data.id] = {
				action: 'render_' + data.id,
				data: data
			};
			clearTimeout(render.timeout);
			render.timeout = setTimeout(function() {
				render.xhr = $.post(elementor.config.document.urls.preview, {
					render: 'widget',
					editor_post_id: elementor.config.document.id,
					actions: JSON.stringify(render.actions),
				}, null, 'text').always(function (arg, status) {
					var data = JSON.parse('success' === status ? arg : arg.responseText) || {};
					for (var action in data) {
						elementor.helpers.getModelById(action.split('_')[1]).onRemoteGetHtml(data);
					}
				});
				render.actions = {};
			}, render.delay);
		}
	});
	// Play entrance animations for tabs in editor
	elementor.$previewElementorEl.on('mouseup.ce', '.elementor-nav-tabs a', function () {
		if (~this.className.indexOf('elementor-item-active')) {
			return;
		}
		var index = $(this.parentNode).index(),
			$col = $(this).closest('.elementor-container').find('> .elementor-row > .elementor-column').eq(index),
			$animated = $col.find('.animated').addBack('.animated');

		$animated.each(function () {
			var id = $(this).data('id'),
				settings = elementor.helpers.getModelById(id).get('settings').attributes;
			$(this).removeClass(settings.animation || settings._animation);
		});
		$animated.length && setTimeout(function() {
			$animated.each(function () {
				var id = $(this).data('id'),
					settings = elementor.helpers.getModelById(id).get('settings').attributes;
				$(this).addClass(settings.animation || settings._animation);
			});
		});
	});
})});

$(function onReady() {
	// init page custom CSS
	var addPageCustomCss = function() {
		var customCSS = elementor.settings.page.model.get('custom_css');

		if (customCSS) {
			customCSS = customCSS.replace(/selector/g, elementor.config.initial_document.settings.cssWrapperSelector);

			elementor.settings.page.getControlsCSS().elements.$stylesheetElement.append(customCSS);
		}
	};
	elementor.on('preview:loaded', addPageCustomCss);
	elementor.settings.page.model.on('change', function () {
		if ('custom_css' in this.changed) {
			addPageCustomCss();
		}
	});

	// init element custom CSS
	elementor.hooks.addFilter('editor/style/styleText', function addCustomCss(css, view) {
		var model = view.getEditModel(),
			customCSS = model.get('settings').get('custom_css');

		if (customCSS) {
			css += customCSS.replace(/selector/g, '.elementor-element.elementor-element-' + view.model.id);
		}

		return css;
	});

	// Theme Builder
	elementor.channels.editor.on('ceThemeBuilder:ApplyPreview', function saveAndReload() {
		elementor.saver.saveAutoSave({
			onSuccess: function onSuccess() {
				elementor.saver.setFlagEditorChange(false);
				location.reload();
			}
		});
	});

	// init CMS & Products Cache
	elementor.cacheCms = {};
	elementor.cacheProducts = {};
	elementor.getProductName = function (id) {
		return this.cacheProducts[id] ? this.cacheProducts[id].name : '';
	};
	elementor.getProductImage = function (id) {
		return this.cacheProducts[id] ? this.cacheProducts[id].image : '';
	};

	// init File Manager
	elementor.fileManager = elementor.dialogsManager.createWidget('lightbox', {
		id: 'ce-file-manager-modal',
		closeButton: true,
		headerMessage: window.tinyMCE ? tinyMCE.i18n.translate('File manager') : 'File manager',

		onReady: function () {
			this.TYPE_IMAGE = 1;
			this.TYPE_VIDEO = 3;
			this.FIELD_IMAGE_ALL = 0xCE;
			this.FIELD_IMAGE_SVG = 0xCE + 1;
			this.FIELD_VIDEO_ALL = 0xCE + 2;

			var $message = this.getElements('message').html(
				'<iframe id="ce-file-manager" width="100%" height="750"></iframe>'
			);
			this.iframe = $message.children()[0];

			this.open = function (fieldId, type, field_id = 0) {
				this.fieldId = fieldId;

				if (this.iframe.contentWindow) {
					if (this.iframe.src.indexOf(`type=${type}&field_id=${field_id}`) < 0) {
						this.iframe.src = baseAdminDir + 'filemanager/dialog.php?' + new URLSearchParams({
							type: type,
							field_id: field_id,
							fldr: localStorage.ceImgFldr || ''
						});
					} else {
						this.initFrame();
						this.getElements('widget').appendTo = function() {
							return this;
						};
					}
					this.show();
				} else {
					$message.prepend(
						$('#tmpl-elementor-template-library-loading').html()
					);
					this.iframe.src = baseAdminDir + 'filemanager/dialog.php?' + new URLSearchParams({
						type: type,
						field_id: field_id,
						fldr: localStorage.ceImgFldr || ''
					});
					this.show(0);
				}
			};
			this.initFrame = function () {
				var $doc = $(this.iframe).contents();

				localStorage.ceImgFldr = $doc.find('#fldr_value').val();

				$doc.find('a.link').attr('data-field_id', this.fieldId);

				this.iframe.contentWindow.close_window = this.hide.bind(this);

				$doc.find('input[type=file]').attr({
					accept: '.' + this.iframe.contentWindow.allowed_ext.join(', .')
				});

				// WEBP
				$doc.find('li[data-name$=".webp"], li[data-name$=".WEBP"]').each(function () {
					$(this).find('.img-container img').attr({
						src: $(this).find('a.preview').data('url'),
					}).css({
						height: '100%',
						objectFit: 'cover',
					});
					$(this).find('.filetype').css('background', 'rgba(0,0,0,0.2)');
					$(this).find('.cover').remove();

					var $form = $(this).find('.download-form').attr('onsubmit', 'event.preventDefault()');
					$form.find('a[onclick*=submit]').attr({
						href: $form.find('.preview').data('url'),
						download: $form[0].elements.name.value,
					});
					$form.find('.rename-file, .delete-file').attr('data-path', '');
				});
			};
			this.iframe.onload = this.initFrame.bind(this);
		},

		onHide: function () {
			var $input = $('#' + this.fieldId),
				value = $input.val();

			value && $input.val(value.replace(location.origin, '')).trigger('input');
		},
	});

	// helper for get model by id
	elementor.helpers.getModelById = function(id, models) {
		models = models || elementor.elements.models;

		for (var i = models.length; i--;) {
			if (models[i].id === id) {
				return models[i];
			}
			if (models[i].attributes.elements.models.length) {
				var model = this.getModelById(id, models[i].attributes.elements.models);

				if (model) {
					return model;
				}
			}
		}
	};

	elementor.helpers.renderWidgets = {
		delay: 100,
		timeout: null,
		actions: {},
	};

	elementor.helpers.getParentSectionModel = function(id, sections) {
		sections = sections || elementor.elements.models;

		for (var i = sections.length; i--;) {
			if ('section' !== sections[i].attributes.elType) {
				continue;
			}
			var sectionModel = sections[i].attributes.settings;

			if (sections[i].attributes.elements.models.length) {
				var columns = sections[i].attributes.elements.models;

				for (var j = columns.length; j--;) {
					if (columns[j].id === id) {
						return sectionModel;
					}
					if (columns[j].attributes.settings.cid === id) {
						return sectionModel;
					}
					if (columns[j].attributes.elements.models.length) {
						var result = this.getParentSectionModel(id, columns[j].attributes.elements.models);
						if (result) {
							return result;
						}
					}
				}
			}
		}
	};

	// fix: add home_url to relative media path
	elementor.helpers.getMediaLink = function(url) {
		if (url && url.indexOf('://') < 0) {
			url = elementor.config.home_url + url;
		}
		return url;
	};
	/** @deprecated 2.9.14.4 Use `elementor.helpers.getMediaLink` instead */
	elementor.imagesManager = {getImageUrl: image => elementor.helpers.getMediaLink(image.url)};

	elementor.once('document:loaded', function onceDocumentLoaded() {
		var doc = elementor.config.document;
		if ('kit' === doc.type) {
			elementor.$previewElementorEl.on('click', 'img', function onClickKitImage() {
				$('.elementor-control-section_images:not(.elementor-open)').click();
			});
			elementor.$previewElementorEl.on('click', 'p,h1,h2,h3,h4,h5,h6', function onClickKitHeading() {
				$('.elementor-control-section_typography:not(.elementor-open)').click();

				'H1' === this.tagName && $('.elementor-control-tab_heading_h1').click() ||
				'H2' === this.tagName && $('.elementor-control-tab_heading_h2').click() ||
				'H3' === this.tagName && $('.elementor-control-tab_heading_h3').click() ||
				'H4' === this.tagName && $('.elementor-control-tab_heading_h4').click() ||
				'H5' === this.tagName && $('.elementor-control-tab_heading_h5').click() ||
				'H6' === this.tagName && $('.elementor-control-tab_heading_h6').click();
			});
			elementor.$previewElementorEl.on('click', '[class*="ce-display-"]', function onClickKitDisplay() {
				$('.elementor-control-section_typography:not(.elementor-open)').click();

				$(this).hasClass('ce-display-xxl') && $('.elementor-control-tab_display_xxl').click() ||
				$(this).hasClass('ce-display-xl') && $('.elementor-control-tab_display_xl').click() ||
				$(this).hasClass('ce-display-large') && $('.elementor-control-tab_display_large').click() ||
				$(this).hasClass('ce-display-medium') && $('.elementor-control-tab_display_medium').click() ||
				$(this).hasClass('ce-display-small') && $('.elementor-control-tab_display_small').click();
			});
			elementor.$previewElementorEl.on('click', '.elementor-button', function onClickKitButton() {
				$('.elementor-control-section_buttons:not(.elementor-open)').click();

				$(this).hasClass('elementor-size-xs') && $('.elementor-control-type-tab[class*=button_size_xs]').click() ||
				$(this).hasClass('elementor-size-xl') && $('.elementor-control-type-tab[class*=button_size_xl]').click() ||
				$(this).hasClass('elementor-size-lg') && $('.elementor-control-type-tab[class*=button_size_lg]').click() ||
				$(this).hasClass('elementor-size-md') && $('.elementor-control-type-tab[class*=button_size_md]').click() ||
				$('.elementor-control-type-tab[class*=button_size_sm]').click();

				if ($(this).closest('.elementor-button-primary').length) {
					return $('.elementor-control-button_type select').val('primary').change();
				}
				if ($(this).closest('.elementor-button-secondary').length) {
					return $('.elementor-control-button_type select').val('secondary').change();
				}
				$('.elementor-control-button_type select').val('').change();
			});
			elementor.$previewElementorEl.on('click', '.btn, .btn-primary, btn-secondary', function onClickKitBtn() {
				$('.elementor-control-section_buttons:not(.elementor-open)').click();

				if ($(this).hasClass('btn-primary')) {
					return $('.elementor-control-button_type select').val('primary_btn').change();
				}
				if ($(this).hasClass('btn-secondary')) {
					return $('.elementor-control-button_type select').val('secondary_btn').change();
				}
				$('.elementor-control-button_type select').val('btn').change();
			});
			elementor.$previewElementorEl.on('click', e => {
				e.preventDefault();
				e.stopPropagation();
			});
			setTimeout(function initKit() {
				elementor.$previewContents.children().addClass('elementor-editor-preview');
				elementor.$previewElementorEl.removeClass('elementor-edit-area elementor-edit-area-active');
				elementor.$previewElementorEl.find('.elementor-element, .ui-sortable').each(function disableEvents() {
					var _data = $._data(this);
					_data._events = _data.events;
					delete _data.events;
				}).filter('.elementor-widget').removeClass('elementor-element-edit-mode');
			}, 1);
			// Hide preview button
			$('#elementor-panel-footer-saver-preview').css('display', 'none');
		}
		var idType = doc.id.substr(-6, 2);
		if ((1 == idType || 17 == idType) && doc.elements && !doc.elements.length) {
			// Auto open Library for Theme Builder
			doc.remoteLibrary && setTimeout(function () {
				$e.run('library/open');
			});
		}
		// Fix for importing template from Theme Style
		var kitSwitchBack = false;
		$e.hooks.registerUIBefore({
			getCommand: () => 'document/elements/import',
			getId: () => 'ce-kit-switch-before',
 			getConditions: () => 'kit' === elementor.config.initial_document.type,
			getContainerType: () => {},
			getType: () => 'ui',
			run: function () {
				return $e.modules.hookUI.Before.prototype.run.apply(this, arguments);
			},
			apply: function (args) {
				if (document.body.classList.contains('elementor-editor-kit')) {
					kitSwitchBack = true;
					// Do not change template layout
					delete args.data.page_settings.template;
					// Switch to edit mode
					$('#elementor-panel-header-kit-close').click();
				}
				if (args.data.page_settings && args.data.page_settings.custom_colors) {
					// Update picked colors
					var count = Object.keys(elementor.schemes.getScheme('color-picker').items).length;
					while (count--) {
						elementor.schemes.removeSchemeItem('color-picker', 0);
					}
					Object.values(args.data.page_settings.custom_colors).forEach((item) => {
						elementor.schemes.addSchemeItem('color-picker', {value: item.color});
					});
					elementor.schemes.saveScheme('color-picker');
				}
			}
		});
		$e.hooks.registerUIAfter({
			getCommand: () => 'document/elements/import',
			getId: () => 'ce-kit-switch-after',
 			getConditions: () => kitSwitchBack,
			getContainerType: () => {},
			getType: () => 'ui',
			run: function() {
				return $e.modules.hookUI.After.prototype.run.apply(this, arguments);
			},
			apply: function() {
				kitSwitchBack = false;
				// Switch to Menu > Theme Style
				$e.route('panel/menu');

				$('.elementor-panel-menu-item-theme-style').click();
			}
		});
	});

	elementor.on('preview:loaded', function onPreviewLoaded() {
		// fix for View Page in force edit mode
		var href = elementor.$preview[0].contentWindow.location.href;

		if (~href.indexOf('&force=1&')) {
			elementor.config.post_permalink = href.replace(/&force=1&.*/, '');
		}

		// scroll to content area
		var offset = elementor.$previewElementorEl.offset() || {top: 0};
		if (offset.top > $(window).height() * 0.66) {
			elementor.$previewContents.find('html, body').animate({
				scrollTop: offset.top - 30
			}, 400);
		}

		// fix for multiple Global colors / fonts
		elementor.$previewContents.find('#elementor-global-css, link[href*="css/ce/global-"]').remove();

		// init Edit with CE buttons
		elementor.$previewContents.find('.ce-edit-btn').on('click.ce', function() {
			location.href = this.href;
		});

		// init Read More link
		elementor.$previewContents.find('.ce-read-more').on('click.ce', function() {
			window.open(this.href);
		});

		// fix for redirecting preview
		elementor.$previewContents.find('a[href]').on('click.ce', function(e) {
			e.preventDefault();
		});
	});

	elementor.once('preview:loaded', function oncePreviewLoaded() {
		// init language switcher
		var $context = $('#ce-context'),
			$langs = $('#ce-langs'),
			$languages = $langs.children().remove(),
			built = $langs.data('built'),
			lang = $langs.data('lang');

		elementor.shopContext = $context.length
			? $context.val()
			: 's-' + parseInt(elementor.config.document.id.slice(-2))
		;
		if ('s' !== elementor.shopContext[0]) {
			var showToast = elementor.notifications.showToast.bind(elementor.notifications, {
					message: elementor.translate('multistore_notification'),
					buttons: [{
						name: 'view_languages',
						text: $context.find(':selected').html().split('★')[0],
						callback: function callback() {
							$('#elementor-panel-footer-lang').click();
						}
					}]
				}),
				toast = elementor.notifications.getToast();
			if (toast.isVisible()) {
				toast.on('hide', function onHideToast() {
					toast.off('hide', onHideToast);
					setTimeout(showToast, 350);
				});
			} else {
				showToast();
			}
		}
		elementor.helpers.filterLangs = function() {
			var ctx = $context.length ? $context.val() : elementor.shopContext,
				id_group = 'g' === ctx[0] ? parseInt(ctx.substr(2)) : 0,
				id_shop = 's' === ctx[0] ? parseInt(ctx.substr(2)) : 0,
				dirty = elementor.shopContext != ctx;

			$langs.empty();

			var id_shops = id_group ? $context.find(':selected').nextUntil('[value^=g]').map(function() {
				return parseInt(this.value.substr(2));
			}).get() : [id_shop];

			$languages.each(function() {
				if (!ctx || $(this).data('shops').filter(function(id) { return ~id_shops.indexOf(id) }).length) {
					var $lang = $(this).clone().appendTo($langs),
						id_lang = $lang.data('lang'),
						active = !dirty && lang == id_lang;

					var uid = elementor.config.document.id.replace(/\d\d(\d\d)$/, function(m, shop) {
						return ('0' + id_lang).slice(-2) + ('0' + id_shop).slice(-2);
					});
					$lang.attr('data-uid', uid).data('uid', uid);

					active && $lang.addClass('active');

					if (active || !id_shop || !built[id_shop] || !built[id_shop][id_lang]) {
						$lang.find('.elementor-button').remove();
					}
				}
			});
		};
		elementor.helpers.filterLangs();
		$context.on('click.ce-ctx', function onClickContext(e) {
			// prevent closing languages
			e.stopPropagation();
		}).on('change.ce-ctx', elementor.helpers.filterLangs);

		$langs.on('click.ce-lang', '.ce-lang', function onChangeLanguage() {
			var uid = $(this).data('uid'),
				href = location.href.replace(/uid=\d+/, 'uid=' + uid);

			if ($context.length && $context.val() != elementor.shopContext) {
				document.context.action = href;
				document.context.submit();
			} else if (uid != elementor.config.document.id) {
				location = href;
			}
		}).on('click.ce-lang-get', '.elementor-button', function onGetLanguageContent(e) {
			e.stopImmediatePropagation();
			var $icon = $('i', this);

			if ($icon.hasClass('eicon-animation-spin')) {
				return;
			}
			$icon.attr('class', 'eicon-spinner eicon-animation-spin');

			elementorCommon.ajax.addRequest('get_language_content', {
				data: {
					uid: $(this).closest('[data-uid]').data('uid')
				},
				success: function(data) {
					$icon.attr('class', 'eicon-file-download');

					$('#elementor-panel-footer-lang').removeClass('elementor-open');

					elementor.getRegion('sections').currentView.addChildModel(data);

					$e.components.get('document/save').footerSaver.activateSaveButtons(elementor.documents.getCurrent(), true);
				},
				error: function(data) {
					elementor.templates.showErrorDialog(data);
				}
			});
		});
	});

	// handle permission errors for AJAX requests
	$(document).ajaxSuccess(function onAjaxSuccess(e, xhr, conf, res) {
		if (res.data && res.data.responses && res.data.responses.apply_scheme && res.data.responses.apply_scheme.success) {
			// refresh custom colors
			elementor.$previewElementorEl.find('.elementor-widget:has(img[src*="background:"])').each(function() {
				var widget = elementor.helpers.getModelById('' + $(this).data('id'));
				widget && widget.renderRemoteServer && widget.renderRemoteServer();
			});
		}
		if (false === res.success && res.data && res.data.permission) {
			NProgress.done();
			$('.elementor-button-state').removeClass('elementor-button-state');

			try {
				elementor.templates.showTemplates();
			} catch (ex) {}

			elementor.templates.getErrorDialog()
				.setMessage('<center>' + res.data.permission + '</center>')
				.show()
			;
		}
	});
});

// Handle expired session
$(document).on('heartbeat-error', function onHeartbeatError(e, xhr) {
	if (xhr.getResponseHeader('login')) {
		$.fancybox.isOpen || $.fancybox({
			type: 'iframe',
			href: baseAdminDir + 'index.php?controller=AdminLogin&redirect=AdminCEEditor',
			autoSize: false,
			width: 600,
			height: 800,
			padding: 0,
			closeBtn: false,
			helpers: {
				overlay: {closeClick: false}
			}
		});
	}
});

// init layerslider widget
$('#elementor-panel').on('change.ls', '.ls-selector select', function onChangeSlider() {
	var $ = elementor.$previewContents[0].defaultView.jQuery;

	$('.elementor-element-' + elementor.panel.currentView.content.currentView.model.id)
		.addClass('elementor-widget-empty')
		.append('<i class="elementor-widget-empty-icon eicon-insert-image">')
		.find('.ls-container').layerSlider('destroy').remove()
	;
}).on('click.ls-new', '.elementor-control-ls-new button', function addSlider(e) {
	var title = prompt(ls.NameYourSlider);

	null === title || $.post(ls.url, {
		'ls-add-new-slider': 1,
		'title': title
	}, function onSuccessNewSlider(data) {
		var id = (data.match(/name="slider_id" value="(\d+)"/) || []).pop();
		if (id) {
			var option = '#' + id + ' - ' + title;
			elementor.config.widgets['ps-widget-LayerSlider'].controls.slider.options[id] = option;
			$('.ls-selector select')
				.append('<option value="' + id + '">' + option + '</option>')
				.val(id)
				.change()
			;
			$('.elementor-control-ls-edit button').trigger('click.ls-edit');
		}
	});
}).on('click.ls-edit', '.elementor-control-ls-edit button', function editSlider(e) {
	var lsUpdate,
		lsId = $('.ls-selector select').val();

	$.fancybox({
		width: '100%',
		height: '100%',
		padding: 0,
		href: ls.url + '&action=edit&id=' + lsId,
		type: 'iframe',
		afterLoad: function onAfterLoadSlider() {
			var win = $('.fancybox-iframe').contents()[0].defaultView;

			win.jQuery(win.document).ajaxSuccess(function(e, xhr, args, res) {
				if (args.data && args.data.indexOf('action=ls_save_slider') === 0 && '{"status":"ok"}' === res) {
					lsUpdate = true;
				}
			});
			$(win.document.head).append('<style>\
				#header, #nav-sidebar, .add-new-h2, .ls-save-shortcode { display: none; }\
				#main { padding-top: 0; }\
				#main #content { margin-left: 0; }\
			</style>');
		},
		beforeClose: function onBeforeCloseSlider() {
			var win = $('.fancybox-iframe').contents()[0].defaultView,
				close = win.LS_editorIsDirty ? confirm(ls.ChangesYouMadeMayNotBeSaved) : true;

			if (close && win.LS_editorIsDirty) {
				win.LS_editorIsDirty = false;
			}
			return close;
		},
		afterClose: function onAfterCloseSlider() {
			lsUpdate && $('.ls-selector select')
				.val(0).change()
				.val(lsId).change()
			;
		}
	});
});
