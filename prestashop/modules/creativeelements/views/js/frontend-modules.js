/*!
 * Creative Elements - live Theme & Page Builder
 * Copyright 2019-2024 WebshopWorks.com & Elementor.com
 */

/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

var Modules = __webpack_require__(1).default;

var Document = __webpack_require__(4).default;

var StretchElement = __webpack_require__(5);

var Base = __webpack_require__(6);

Modules.frontend = {
	Document,
	tools: {
		StretchElement,
	},
	handlers: {
		Base
	}
};

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var Module = __webpack_require__(2);

var ViewModule = __webpack_require__(3);

// var _argsObject = _interopRequireDefault(__webpack_require__(210));

// var _masonry = _interopRequireDefault(__webpack_require__(577));

// var _forceMethodImplementation = _interopRequireDefault(__webpack_require__(578));

window.elementorModules = {
	Module,
	ViewModule,
	// ArgsObject: _argsObject.default,
	// ForceMethodImplementation: _forceMethodImplementation.default,
	utils: {
		// Masonry: _masonry.default
	}
};

exports.default = elementorModules;

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

var Module = function Module() {
	var instanceParams = arguments,
			self = this,
			events = {};
	var settings;

	var ensureClosureMethods = function ensureClosureMethods() {
		$.each(self, function (methodName) {
			var oldMethod = self[methodName];

			if ('function' !== typeof oldMethod) {
				return;
			}

			self[methodName] = function () {
				return oldMethod.apply(self, arguments);
			};
		});
	};

	var initSettings = function initSettings() {
		settings = self.getDefaultSettings();
		var instanceSettings = instanceParams[0];

		if (instanceSettings) {
			$.extend(true, settings, instanceSettings);
		}
	};

	var init = function init() {
		self.__construct.apply(self, instanceParams);

		ensureClosureMethods();
		initSettings();
		self.trigger('init');
	};

	this.getItems = function (items, itemKey) {
		if (itemKey) {
			var keyStack = itemKey.split('.'),
					currentKey = keyStack.splice(0, 1);

			if (!keyStack.length) {
				return items[currentKey];
			}

			if (!items[currentKey]) {
				return;
			}

			return this.getItems(items[currentKey], keyStack.join('.'));
		}

		return items;
	};

	this.getSettings = function (setting) {
		return this.getItems(settings, setting);
	};

	this.setSettings = function (settingKey, value, settingsContainer) {
		if (!settingsContainer) {
			settingsContainer = settings;
		}

		if ('object' === typeof settingKey) {
			$.extend(settingsContainer, settingKey);
			return self;
		}

		var keyStack = settingKey.split('.'),
				currentKey = keyStack.splice(0, 1);

		if (!keyStack.length) {
			settingsContainer[currentKey] = value;
			return self;
		}

		if (!settingsContainer[currentKey]) {
			settingsContainer[currentKey] = {};
		}

		return self.setSettings(keyStack.join('.'), value, settingsContainer[currentKey]);
	};

	this.getErrorMessage = function (type, functionName) {
		var message;

		switch (type) {
			case 'forceMethodImplementation':
				message = "The method '".concat(functionName, "' must to be implemented in the inheritor child.");
				break;

			default:
				message = 'An error occurs';
		}

		return message;
	}; // TODO: This function should be deleted ?.


	this.forceMethodImplementation = function (functionName) {
		throw new Error(this.getErrorMessage('forceMethodImplementation', functionName));
	};

	this.on = function (eventName, callback) {
		if ('object' === typeof eventName) {
			$.each(eventName, function (singleEventName) {
				self.on(singleEventName, this);
			});
			return self;
		}

		var eventNames = eventName.split(' ');
		eventNames.forEach(function (singleEventName) {
			if (!events[singleEventName]) {
				events[singleEventName] = [];
			}

			events[singleEventName].push(callback);
		});
		return self;
	};

	this.off = function (eventName, callback) {
		if (!events[eventName]) {
			return self;
		}

		if (!callback) {
			delete events[eventName];
			return self;
		}

		var callbackIndex = events[eventName].indexOf(callback);

		if (-1 !== callbackIndex) {
			delete events[eventName][callbackIndex]; // Reset array index (for next off on same event).

			events[eventName] = events[eventName].filter(function (val) {
				return val;
			});
		}

		return self;
	};

	this.trigger = function (eventName) {
		var methodName = 'on' + eventName[0].toUpperCase() + eventName.slice(1),
				params = Array.prototype.slice.call(arguments, 1);

		if (self[methodName]) {
			self[methodName].apply(self, params);
		}

		var callbacks = events[eventName];

		if (!callbacks) {
			return self;
		}

		$.each(callbacks, function (index, callback) {
			callback.apply(self, params);
		});
		return self;
	};

	init();
};

Module.prototype.__construct = function () {};

Module.prototype.getDefaultSettings = function () {
	return {};
};

Module.prototype.getConstructorID = function () {
	return this.constructor.name;
};

Module.extend = function (properties) {
	var parent = this;

	var child = function child() {
		return parent.apply(this, arguments);
	};

	$.extend(child, parent);
	child.prototype = Object.create($.extend({}, parent.prototype, properties));
	child.prototype.constructor = child;
	child.__super__ = parent.prototype;
	return child;
};

module.exports = Module;

/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

var Module = __webpack_require__(2);

var ViewModule = Module.extend({
	elements: null,
	getDefaultElements: function () {
		return {};
	},
	bindEvents: function () {},
	onInit: function () {
		this.initElements();
		this.bindEvents();
	},
	initElements: function () {
		this.elements = this.getDefaultElements();
	}
});

module.exports = ViewModule;

/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class ElementorDocument extends elementorModules.ViewModule {
	getDefaultSettings() {
		return {
			selectors: {
				elements: '.elementor-element',
				nestedDocumentElements: '.elementor .elementor-element'
			},
			classes: {
				editMode: 'elementor-edit-mode'
			}
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings('selectors');
		return {
			$elements: this.$element.find(selectors.elements).not(this.$element.find(selectors.nestedDocumentElements))
		};
	}

	getDocumentSettings(setting) {
		let elementSettings;

		if (this.isEdit) {
			elementSettings = {};
			const settings = elementor.settings.page.model;
			$.each(settings.getActiveControls(), (controlKey) => {
				elementSettings[controlKey] = settings.attributes[controlKey];
			});
		} else {
			elementSettings = this.$element.data('elementor-settings') || {};
		}

		return this.getItems(elementSettings, setting);
	}

	runElementsHandlers() {
		this.elements.$elements.each((index, element) => ceFrontend.elementsHandler.runReadyTrigger(element));
	}

	onInit() {
		this.$element = this.getSettings('$element');
		super.onInit();
		this.isEdit = this.$element.hasClass(this.getSettings('classes.editMode'));

		if (this.isEdit) {
			elementor.on('document:loaded', () => {
				elementor.settings.page.model.on('change', this.onSettingsChange.bind(this));
			});
		} else {
			this.runElementsHandlers();
		}
	}

	onSettingsChange() {}
}

exports.default = ElementorDocument;

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = elementorModules.ViewModule.extend({
	getDefaultSettings: function () {
		return {
			element: null,
			direction: ceFrontend.config.is_rtl ? 'right' : 'left',
			selectors: {
				container: window
			}
		};
	},
	getDefaultElements: function () {
		return {
			$element: $(this.getSettings('element'))
		};
	},
	stretch: function () {
		// CE fix for nav menu
		if (!this.elements.$element.length) {
			return;
		}

		var containerSelector = this.getSettings('selectors.container'),
				$container;

		try {
			$container = $(containerSelector);
		} catch (e) {}

		if (!$container || !$container.length) {
			$container = $(this.getDefaultSettings().selectors.container);
		}

		this.reset();
		var $element = this.elements.$element,
				containerWidth = $container.outerWidth(),
				elementOffset = $element.offset().left,
				isFixed = 'fixed' === $element.css('position'),
				correctOffset = isFixed ? 0 : elementOffset;

		if (window !== $container[0]) {
			var containerOffset = $container.offset().left;

			if (isFixed) {
				correctOffset = containerOffset;
			}

			if (elementOffset > containerOffset) {
				correctOffset = elementOffset - containerOffset;
			}
		}

		if (!isFixed) {
			if (ceFrontend.config.is_rtl) {
				correctOffset = containerWidth - ($element.outerWidth() + correctOffset);
			}

			correctOffset = -correctOffset;
		}

		var css = {};
		css.width = containerWidth + 'px';
		css[this.getSettings('direction')] = correctOffset + 'px';
		$element.css(css);
	},
	reset: function () {
		var css = {};
		css.width = '';
		css[this.getSettings('direction')] = '';
		this.elements.$element.css(css);
	}
});

/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = elementorModules.ViewModule.extend({
	$element: null,
	editorListeners: null,
	onElementChange: null,
	onEditSettingsChange: null,
	onGeneralSettingsChange: null,
	onPageSettingsChange: null,
	isEdit: null,
	__construct: function (settings) {
		this.$element = settings.$element;
		this.isEdit = this.$element.hasClass('elementor-element-edit-mode');

		if (this.isEdit) {
			this.addEditorListeners();
		}
	},
	findElement: function (selector) {
		var $mainElement = this.$element;
		return $mainElement.find(selector).filter(function () {
			return $(this).closest('.elementor-element').is($mainElement);
		});
	},
	getUniqueHandlerID: function (cid, $element) {
		if (!cid) {
			cid = this.getModelCID();
		}

		if (!$element) {
			$element = this.$element;
		}

		return cid + $element.attr('data-element_type') + this.getConstructorID();
	},
	initEditorListeners: function () {
		var self = this;
		self.editorListeners = [{
			event: 'element:destroy',
			to: elementor.channels.data,
			callback: function (removedModel) {
				if (removedModel.cid !== self.getModelCID()) {
					return;
				}

				self.onDestroy();
			}
		}];

		if (self.onElementChange) {
			var elementType = self.getWidgetType() || self.getElementType();
			var eventName = 'change';

			if ('global' !== elementType) {
				eventName += ':' + elementType;
			}

			self.editorListeners.push({
				event: eventName,
				to: elementor.channels.editor,
				callback: function (controlView, elementView) {
					var elementViewHandlerID = self.getUniqueHandlerID(elementView.model.cid, elementView.$el);

					if (elementViewHandlerID !== self.getUniqueHandlerID()) {
						return;
					}

					self.onElementChange(controlView.model.get('name'), controlView, elementView);
				}
			});
		}

		if (self.onEditSettingsChange) {
			self.editorListeners.push({
				event: 'change:editSettings',
				to: elementor.channels.editor,
				callback: function (changedModel, view) {
					if (view.model.cid !== self.getModelCID()) {
						return;
					}

					self.onEditSettingsChange(Object.keys(changedModel.changed)[0]);
				}
			});
		}

		['page', 'general'].forEach(function (settingsType) {
			var listenerMethodName = 'on' + settingsType[0].toUpperCase() + settingsType.slice(1) + 'SettingsChange';

			if (self[listenerMethodName]) {
				self.editorListeners.push({
					event: 'change',
					to: elementor.settings[settingsType].model,
					callback: function (model) {
						self[listenerMethodName](model.changed);
					}
				});
			}
		});
	},
	getEditorListeners: function () {
		if (!this.editorListeners) {
			this.initEditorListeners();
		}

		return this.editorListeners;
	},
	addEditorListeners: function () {
		var uniqueHandlerID = this.getUniqueHandlerID();
		this.getEditorListeners().forEach(function (listener) {
			ceFrontend.addListenerOnce(uniqueHandlerID, listener.event, listener.callback, listener.to);
		});
	},
	removeEditorListeners: function () {
		var uniqueHandlerID = this.getUniqueHandlerID();
		this.getEditorListeners().forEach(function (listener) {
			ceFrontend.removeListeners(uniqueHandlerID, listener.event, null, listener.to);
		});
	},
	getElementType: function () {
		return this.$element.data('element_type');
	},
	getWidgetType: function () {
		var widgetType = this.$element.data('widget_type');

		if (!widgetType) {
			return;
		}

		return widgetType.split('.')[0];
	},
	getID: function () {
		return this.$element.data('id');
	},
	getModelCID: function () {
		return this.$element.data('model-cid');
	},
	getElementSettings: function (setting) {
		var elementSettings = {};
		var modelCID = this.getModelCID();

		if (this.isEdit && modelCID) {
			var settings = ceFrontend.config.elements.data[modelCID],
					attributes = settings.attributes;
			var type = attributes.widgetType || attributes.elType;

			if (attributes.isInner) {
				type = 'inner-' + type;
			}

			var settingsKeys = ceFrontend.config.elements.keys[type];

			if (!settingsKeys) {
				settingsKeys = ceFrontend.config.elements.keys[type] = [];
				$.each(settings.controls, function (name, control) {
					if (control.frontend_available) {
						settingsKeys.push(name);
					}
				});
			}

			$.each(settings.getActiveControls(), function (controlKey) {
				if (-1 !== settingsKeys.indexOf(controlKey)) {
					var value = attributes[controlKey];

					if (value && value.toJSON) {
						value = value.toJSON();
					}

					elementSettings[controlKey] = value;
				}
			});
		} else {
			elementSettings = this.$element.data('settings') || {};
		}

		return this.getItems(elementSettings, setting);
	},
	getEditSettings: function (setting) {
		var attributes = {};

		if (this.isEdit) {
			attributes = ceFrontend.config.elements.editSettings[this.getModelCID()].attributes;
		}

		return this.getItems(attributes, setting);
	},
	getCurrentDeviceSetting: function (settingKey) {
		return ceFrontend.getCurrentDeviceSetting(this.getElementSettings(), settingKey);
	},
	onDestroy: function () {
		if (this.isEdit) {
			this.removeEditorListeners();
		}

		if (this.unbindEvents) {
			this.unbindEvents();
		}
	}
});

/***/ })

/******/ ]);
