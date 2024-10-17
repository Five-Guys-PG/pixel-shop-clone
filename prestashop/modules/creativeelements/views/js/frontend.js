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
/******/ 	return __webpack_require__(__webpack_require__.s = 636);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */,
/* 1 */,
/* 2 */,
/* 3 */,
/* 4 */,
/* 5 */,
/* 6 */,
/* 7 */,
/* 8 */,
/* 9 */,
/* 10 */,
/* 11 */,
/* 12 */,
/* 13 */,
/* 14 */,
/* 15 */,
/* 16 */,
/* 17 */,
/* 18 */,
/* 19 */,
/* 20 */,
/* 21 */,
/* 22 */,
/* 23 */,
/* 24 */,
/* 25 */,
/* 26 */,
/* 27 */,
/* 28 */,
/* 29 */,
/* 30 */,
/* 31 */,
/* 32 */,
/* 33 */,
/* 34 */,
/* 35 */,
/* 36 */,
/* 37 */,
/* 38 */,
/* 39 */,
/* 40 */,
/* 41 */,
/* 42 */,
/* 43 */,
/* 44 */,
/* 45 */,
/* 46 */,
/* 47 */,
/* 48 */,
/* 49 */,
/* 50 */,
/* 51 */,
/* 52 */,
/* 53 */,
/* 54 */,
/* 55 */,
/* 56 */,
/* 57 */,
/* 58 */,
/* 59 */,
/* 60 */,
/* 61 */,
/* 62 */,
/* 63 */,
/* 64 */,
/* 65 */,
/* 66 */,
/* 67 */,
/* 68 */,
/* 69 */,
/* 70 */,
/* 71 */,
/* 72 */,
/* 73 */,
/* 74 */,
/* 75 */,
/* 76 */,
/* 77 */,
/* 78 */,
/* 79 */,
/* 80 */,
/* 81 */,
/* 82 */,
/* 83 */,
/* 84 */,
/* 85 */,
/* 86 */,
/* 87 */,
/* 88 */,
/* 89 */,
/* 90 */,
/* 91 */,
/* 92 */,
/* 93 */,
/* 94 */,
/* 95 */,
/* 96 */,
/* 97 */,
/* 98 */,
/* 99 */,
/* 100 */,
/* 101 */,
/* 102 */,
/* 103 */,
/* 104 */,
/* 105 */,
/* 106 */,
/* 107 */,
/* 108 */,
/* 109 */,
/* 110 */,
/* 111 */,
/* 112 */,
/* 113 */,
/* 114 */,
/* 115 */,
/* 116 */,
/* 117 */,
/* 118 */,
/* 119 */,
/* 120 */,
/* 121 */,
/* 122 */,
/* 123 */,
/* 124 */,
/* 125 */,
/* 126 */,
/* 127 */,
/* 128 */,
/* 129 */,
/* 130 */,
/* 131 */,
/* 132 */,
/* 133 */,
/* 134 */,
/* 135 */,
/* 136 */,
/* 137 */,
/* 138 */,
/* 139 */,
/* 140 */,
/* 141 */,
/* 142 */,
/* 143 */,
/* 144 */,
/* 145 */,
/* 146 */,
/* 147 */,
/* 148 */,
/* 149 */,
/* 150 */,
/* 151 */,
/* 152 */,
/* 153 */,
/* 154 */,
/* 155 */,
/* 156 */,
/* 157 */,
/* 158 */,
/* 159 */,
/* 160 */,
/* 161 */,
/* 162 */,
/* 163 */,
/* 164 */,
/* 165 */,
/* 166 */,
/* 167 */,
/* 168 */,
/* 169 */,
/* 170 */,
/* 171 */,
/* 172 */,
/* 173 */,
/* 174 */,
/* 175 */,
/* 176 */,
/* 177 */,
/* 178 */,
/* 179 */,
/* 180 */,
/* 181 */,
/* 182 */,
/* 183 */,
/* 184 */,
/* 185 */,
/* 186 */,
/* 187 */,
/* 188 */,
/* 189 */,
/* 190 */,
/* 191 */,
/* 192 */,
/* 193 */,
/* 194 */,
/* 195 */,
/* 196 */,
/* 197 */,
/* 198 */,
/* 199 */,
/* 200 */,
/* 201 */,
/* 202 */,
/* 203 */,
/* 204 */,
/* 205 */,
/* 206 */,
/* 207 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var userAgent = navigator.userAgent;

exports.default = {
	webkit: -1 !== userAgent.indexOf('AppleWebKit'),
	firefox: -1 !== userAgent.indexOf('Firefox'),
	ie: /Trident|MSIE/.test(userAgent),
	edge: -1 !== userAgent.indexOf('Edge'),
	mac: -1 !== userAgent.indexOf('Macintosh')
};

/***/ }),
/* 208 */,
/* 209 */,
/* 210 */,
/* 211 */,
/* 212 */,
/* 213 */,
/* 214 */,
/* 215 */,
/* 216 */,
/* 217 */,
/* 218 */,
/* 219 */,
/* 220 */,
/* 221 */,
/* 222 */,
/* 223 */,
/* 224 */,
/* 225 */,
/* 226 */,
/* 227 */,
/* 228 */,
/* 229 */,
/* 230 */,
/* 231 */,
/* 232 */,
/* 233 */,
/* 234 */,
/* 235 */,
/* 236 */,
/* 237 */,
/* 238 */,
/* 239 */,
/* 240 */,
/* 241 */,
/* 242 */,
/* 243 */,
/* 244 */,
/* 245 */,
/* 246 */,
/* 247 */,
/* 248 */,
/* 249 */,
/* 250 */,
/* 251 */,
/* 252 */,
/* 253 */,
/* 254 */,
/* 255 */,
/* 256 */,
/* 257 */,
/* 258 */,
/* 259 */,
/* 260 */,
/* 261 */,
/* 262 */,
/* 263 */,
/* 264 */,
/* 265 */,
/* 266 */,
/* 267 */,
/* 268 */,
/* 269 */,
/* 270 */,
/* 271 */,
/* 272 */,
/* 273 */,
/* 274 */,
/* 275 */,
/* 276 */,
/* 277 */,
/* 278 */,
/* 279 */,
/* 280 */,
/* 281 */,
/* 282 */,
/* 283 */,
/* 284 */,
/* 285 */,
/* 286 */,
/* 287 */,
/* 288 */,
/* 289 */,
/* 290 */,
/* 291 */,
/* 292 */,
/* 293 */,
/* 294 */,
/* 295 */,
/* 296 */,
/* 297 */,
/* 298 */,
/* 299 */,
/* 300 */,
/* 301 */,
/* 302 */,
/* 303 */,
/* 304 */,
/* 305 */,
/* 306 */,
/* 307 */,
/* 308 */,
/* 309 */,
/* 310 */,
/* 311 */,
/* 312 */,
/* 313 */,
/* 314 */,
/* 315 */,
/* 316 */,
/* 317 */,
/* 318 */,
/* 319 */,
/* 320 */,
/* 321 */,
/* 322 */,
/* 323 */,
/* 324 */,
/* 325 */,
/* 326 */,
/* 327 */,
/* 328 */,
/* 329 */,
/* 330 */,
/* 331 */,
/* 332 */,
/* 333 */,
/* 334 */,
/* 335 */,
/* 336 */,
/* 337 */,
/* 338 */,
/* 339 */,
/* 340 */,
/* 341 */
/***/ (function(module, exports, __webpack_require__) {

/**
 * Handles managing all events for whatever you plug it into. Priorities for hooks are based on lowest to highest in
 * that, lowest priority hooks are fired first.
 */

const EventManager = function() {
	const slice = Array.prototype.slice;
	let MethodsAvailable;

	/**
	 * Contains the hooks that get registered with this EventManager. The array for storage utilizes a "flat"
	 * object literal such that looking up the hook utilizes the native object literal hash.
	 */
	const STORAGE = {
		actions: {},
		filters: {}
	};

	/**
	 * Removes the specified hook by resetting the value of it.
	 *
	 * @param type Type of hook, either 'actions' or 'filters'
	 * @param hook The hook (namespace.identifier) to remove
	 *
	 * @private
	 */
	function _removeHook(type, hook, callback, context) {
		if (!STORAGE[type][hook]) {
			return;
		}

		if (!callback) {
			STORAGE[type][hook] = [];
		} else {
			const handlers = STORAGE[type][hook];
			if (!context) {
				for (let i = handlers.length; i--;) {
					if (handlers[i].callback === callback) {
						handlers.splice(i, 1);
					}
				}
			} else {
				for (let i = handlers.length; i--;) {
					const handler = handlers[i];
					if (handler.callback === callback && handler.context === context) {
						handlers.splice(i, 1);
					}
				}
			}
		}
	}

	/**
	 * Use an insert sort for keeping our hooks organized based on priority. This function is ridiculously faster
	 * than bubble sort, etc: http://jsperf.com/javascript-sort
	 *
	 * @param hooks The custom array containing all of the appropriate hooks to perform an insert sort on.
	 * @private
	 */
	function _hookInsertSort(hooks) {
		for (let i = 1, len = hooks.length; i < len; i++) {
			const tmpHook = hooks[i];
			let j = i;
			while ((j > 0) && (hooks[j - 1].priority > tmpHook.priority)) {
				hooks[j] = hooks[j - 1];
				--j;
			}
			hooks[j] = tmpHook;
		}

		return hooks;
	}

	/**
	 * Adds the hook to the appropriate storage container
	 *
	 * @param type 'actions' or 'filters'
	 * @param hook The hook (namespace.identifier) to add to our event manager
	 * @param callback The function that will be called when the hook is executed.
	 * @param priority The priority of this hook. Must be an integer.
	 * @param [context] A value to be used for this
	 * @private
	 */
	function _addHook(type, hook, callback, priority, context) {
		const hookObject = {
			callback: callback,
			priority: priority,
			context: context
		};

		// Utilize 'prop itself' : http://jsperf.com/hasownproperty-vs-in-vs-undefined/19
		let hooks = STORAGE[type][hook];

		if (hooks) {
			// TEMP FIX BUG
			const hasSameCallback = hooks.some(item => item.callback === callback);

			if (hasSameCallback) {
				return;
			}
			// END TEMP FIX BUG

			hooks.push(hookObject);
			hooks = _hookInsertSort(hooks);
		} else {
			hooks = [hookObject];
		}

		STORAGE[type][hook] = hooks;
	}

	/**
	 * Runs the specified hook. If it is an action, the value is not modified but if it is a filter, it is.
	 *
	 * @param type 'actions' or 'filters'
	 * @param hook The hook ( namespace.identifier ) to be ran.
	 * @param args Arguments to pass to the action/filter. If it's a filter, args is actually a single parameter.
	 * @private
	 */
	function _runHook(type, hook, args) {
		const handlers = STORAGE[type][hook];

		if (!handlers) {
			return 'filters' === type ? args[0] : false;
		}

		const len = handlers.length;

		if ('filters' === type) {
			for (let i = 0; i < len; i++) {
				args[0] = handlers[i].callback.apply(handlers[i].context, args);
			}
		} else {
			for (let i = 0; i < len; i++) {
				handlers[i].callback.apply(handlers[i].context, args);
			}
		}

		return 'filters' === type ? args[0] : true;
	}

	/**
	 * Adds an action to the event manager.
	 *
	 * @param action Must contain namespace.identifier
	 * @param callback Must be a valid callback function before this action is added
	 * @param [priority=10] Used to control when the function is executed in relation to other callbacks bound to the same hook
	 * @param [context] Supply a value to be used for this
	 */
	function addAction(action, callback, priority, context) {
		if ('string' === typeof action && 'function' === typeof callback) {
			priority = parseInt(priority || 10, 10);
			_addHook('actions', action, callback, priority, context);
		}

		return MethodsAvailable;
	}

	/**
	 * Performs an action if it exists. You can pass as many arguments as you want to this function; the only rule is
	 * that the first argument must always be the action.
	 */
	function doAction(/* action, arg1, arg2, ... */) {
		const args = slice.call(arguments);
		const action = args.shift();

		if ('string' === typeof action) {
			_runHook('actions', action, args);
		}

		return MethodsAvailable;
	}

	/**
	 * Removes the specified action if it contains a namespace.identifier & exists.
	 *
	 * @param action The action to remove
	 * @param [callback] Callback function to remove
	 */
	function removeAction(action, callback) {
		if ('string' === typeof action) {
			_removeHook('actions', action, callback);
		}

		return MethodsAvailable;
	}

	/**
	 * Adds a filter to the event manager.
	 *
	 * @param filter Must contain namespace.identifier
	 * @param callback Must be a valid callback function before this action is added
	 * @param [priority=10] Used to control when the function is executed in relation to other callbacks bound to the same hook
	 * @param [context] Supply a value to be used for this
	 */
	function addFilter(filter, callback, priority, context) {
		if ('string' === typeof filter && 'function' === typeof callback) {
			priority = parseInt(priority || 10, 10);
			_addHook('filters', filter, callback, priority, context);
		}

		return MethodsAvailable;
	}

	/**
	 * Performs a filter if it exists. You should only ever pass 1 argument to be filtered. The only rule is that
	 * the first argument must always be the filter.
	 */
	function applyFilters(/* filter, filtered arg, arg2, ... */) {
		const args = slice.call(arguments);
		const filter = args.shift();

		if ('string' === typeof filter) {
			return _runHook('filters', filter, args);
		}

		return MethodsAvailable;
	}

	/**
	 * Removes the specified filter if it contains a namespace.identifier & exists.
	 *
	 * @param filter The action to remove
	 * @param [callback] Callback function to remove
	 */
	function removeFilter(filter, callback) {
		if ('string' === typeof filter) {
			_removeHook('filters', filter, callback);
		}

		return MethodsAvailable;
	}

	/**
	 * Maintain a reference to the object scope so our public methods never get confusing.
	 */
	MethodsAvailable = {
		removeFilter,
		applyFilters,
		addFilter,
		removeAction,
		doAction,
		addAction
	};

	// return all of the publicly available methods
	return MethodsAvailable;
};

module.exports = EventManager;

/***/ }),
/* 342 */,
/* 343 */,
/* 344 */,
/* 345 */,
/* 346 */,
/* 347 */,
/* 348 */,
/* 349 */,
/* 350 */,
/* 351 */,
/* 352 */,
/* 353 */,
/* 354 */,
/* 355 */,
/* 356 */,
/* 357 */,
/* 358 */,
/* 359 */,
/* 360 */,
/* 361 */,
/* 362 */,
/* 363 */,
/* 364 */,
/* 365 */,
/* 366 */,
/* 367 */,
/* 368 */,
/* 369 */,
/* 370 */,
/* 371 */,
/* 372 */,
/* 373 */,
/* 374 */,
/* 375 */,
/* 376 */,
/* 377 */,
/* 378 */,
/* 379 */,
/* 380 */,
/* 381 */,
/* 382 */,
/* 383 */,
/* 384 */,
/* 385 */,
/* 386 */,
/* 387 */,
/* 388 */,
/* 389 */,
/* 390 */,
/* 391 */,
/* 392 */,
/* 393 */,
/* 394 */,
/* 395 */,
/* 396 */,
/* 397 */,
/* 398 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class Scroll {
	/**
	 * @param {object} obj
	 * @param {number} obj.sensitivity - Value between 0-100 - Will determine the intersection trigger points on the element
	 * @param {function} obj.callback - Will be triggered on each intersection point between the element and the viewport top/bottom
	 * @param {string} obj.offset - Offset between the element intersection points and the viewport, written like in CSS: '-50% 0 -25%'
	 * @param {HTMLElement} obj.root - The element that the events will be relative to, if 'null' will be relative to the viewport
	 */
	static scrollObserver(obj) {
		let lastScrollY = 0;

		// Generating threshholds points along the animation height
		// More threshholds points = more trigger points of the callback
		const buildThreshholds = (sensitivityPercentage = 0) => {
			const threshholds = [];

			if (0 < sensitivityPercentage && sensitivityPercentage <= 100) {
				const increment = 100 / sensitivityPercentage;

				for (let i = 0; i <= 100; i += increment) {
					threshholds.push(i / 100);
				}
			} else {
				threshholds.push(0);
			}

			return threshholds;
		};

		const options = {
			root: obj.root || null,
			rootMargin: obj.offset || '0px',
			threshold: buildThreshholds(obj.sensitivity)
		};

		function handleIntersect(entries, observer) {
			const currentScrollY = entries[0].boundingClientRect.y,
						isInViewport = entries[0].isIntersecting,
						intersectionScrollDirection = currentScrollY < lastScrollY ? 'down' : 'up',
						scrollPercentage = Math.abs(parseFloat((entries[0].intersectionRatio * 100).toFixed(2)));

			obj.callback({
				sensitivity: obj.sensitivity,
				isInViewport: isInViewport,
				scrollPercentage: scrollPercentage,
				intersectionScrollDirection: intersectionScrollDirection
			});

			lastScrollY = currentScrollY;
		}

		return new IntersectionObserver(handleIntersect, options);
	}

	/**
	 * @param {jQuery Element} $element
	 * @param {object} offsetObj
	 * @param {number} offsetObj.start - Offset start value in percentages
	 * @param {number} offsetObj.end - Offset end value in percentages
	 */
	static getElementViewportPercentage($element, offsetObj = {}) {
		const elementOffset = $element[0].getBoundingClientRect(),
					offsetStart = offsetObj.start || 0,
					offsetEnd = offsetObj.end || 0,
					windowStartOffset = window.innerHeight * offsetStart / 100,
					windowEndOffset = window.innerHeight * offsetEnd / 100,
					y1 = elementOffset.top - window.innerHeight,
					y2 = elementOffset.top + windowStartOffset + $element.height(),
					startPosition = 0 - y1 + windowStartOffset,
					endPosition = y2 - y1 + windowEndOffset,
					percent = Math.max(0, Math.min(startPosition / endPosition, 1));

		return parseFloat((percent * 100).toFixed(2));
	}

	/**
	 * @param {object} offsetObj
	 * @param {number} offsetObj.start - Offset start value in percentages
	 * @param {number} offsetObj.end - Offset end value in percentages
	 * @param {number} limitPageHeight - Will limit the page height calculation
	 */
	static getPageScrollPercentage(offsetObj = {}, limitPageHeight) {
		const offsetStart = offsetObj.start || 0,
					offsetEnd = offsetObj.end || 0,
					initialPageHeight = limitPageHeight || document.documentElement.scrollHeight - document.documentElement.clientHeight,
					heightOffset = initialPageHeight * offsetStart / 100,
					pageRange = initialPageHeight + heightOffset + initialPageHeight * offsetEnd / 100,
					scrollPos = document.documentElement.scrollTop + document.body.scrollTop + heightOffset;

		return scrollPos / pageRange * 100;
	}
}

exports.default = Scroll;

/***/ }),
/* 399 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var Scroll = __webpack_require__(398).default;

const MotionFXBase = elementorModules.ViewModule.extend({
	__construct: function(options) {
		this.onInsideViewport = this.onInsideViewport.bind(this);
		this.motionFX = options.motionFX;

		if (!this.intersectionObservers) {
			this.setElementInViewportObserver();
		}
	},

	onInsideViewport: function() {
		this.run();
		this.animationFrameRequest = requestAnimationFrame(this.onInsideViewport);
	},

	setElementInViewportObserver: function() {
		this.intersectionObserver = Scroll.scrollObserver({
			callback: (event) => {
				if (event.isInViewport) {
					this.onInsideViewport();
				} else {
					this.removeAnimationFrameRequest();
				}
			}
		});
		this.intersectionObserver.observe(this.motionFX.elements.$parent[0]);
	},

	runCallback: function() {
		const callback = this.getSettings('callback');
		callback.apply(this, arguments);
	},

	removeIntersectionObserver: function() {
		if (this.intersectionObserver) {
			this.intersectionObserver.unobserve(this.motionFX.elements.$parent[0]);
		}
	},

	removeAnimationFrameRequest: function() {
		if (this.animationFrameRequest) {
			cancelAnimationFrame(this.animationFrameRequest);
		}
	},

	destroy: function() {
		this.removeAnimationFrameRequest();
		this.removeIntersectionObserver();
	},

	onInit: function() {
		elementorModules.ViewModule.prototype.onInit.apply(this, arguments);
	}
});

exports.default = MotionFXBase;

/***/ }),
/* 400 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var MotionFx = __webpack_require__(401).default;

class MotionEffectsHandler extends elementorModules.frontend.handlers.Base {
	__construct(...args) {
		super.__construct(...args);
		this.toggle = ceFrontend.debounce(this.toggle, 200);
	}

	bindEvents() {
		ceFrontend.elements.$window.on('resize', this.toggle);
	}

	unbindEvents() {
		ceFrontend.elements.$window.off('resize', this.toggle);
	}

	initEffects() {
		this.effects = {
			translateY: {
				interaction: 'scroll',
				actions: ['translateY']
			},
			translateX: {
				interaction: 'scroll',
				actions: ['translateX']
			},
			rotateZ: {
				interaction: 'scroll',
				actions: ['rotateZ']
			},
			scale: {
				interaction: 'scroll',
				actions: ['scale']
			},
			opacity: {
				interaction: 'scroll',
				actions: ['opacity']
			},
			blur: {
				interaction: 'scroll',
				actions: ['blur']
			},
			mouseTrack: {
				interaction: 'mouseMove',
				actions: ['translateXY']
			},
			tilt: {
				interaction: 'mouseMove',
				actions: ['tilt']
			}
		};
	}

	prepareOptions(name) {
		const elementSettings = this.getElementSettings(),
					type = 'motion_fx' === name ? 'element' : 'background',
					interactions = {};

		$.each(elementSettings, (key, value) => {
			const keyRegex = new RegExp('^' + name + '_(.+?)_effect'),
						keyMatches = key.match(keyRegex);

			if (!keyMatches || !value) {
				return;
			}

			const options = {},
						effectName = keyMatches[1];

			$.each(elementSettings, (subKey, subValue) => {
				const subKeyRegex = new RegExp(name + '_' + effectName + '_(.+)'),
							subKeyMatches = subKey.match(subKeyRegex);

				if (!subKeyMatches) {
					return;
				}

				const subFieldName = subKeyMatches[1];

				if ('effect' === subFieldName) {
					return;
				}

				if ('object' === typeof subValue) {
					subValue = Object.keys(subValue.sizes).length ? subValue.sizes : subValue.size;
				}

				options[subKeyMatches[1]] = subValue;
			});

			const effect = this.effects[effectName],
						interactionName = effect.interaction;

			if (!interactions[interactionName]) {
				interactions[interactionName] = {};
			}

			effect.actions.forEach(action => interactions[interactionName][action] = options);
		});

		let $element = this.$element,
				$dimensionsElement;

		const elementType = this.getElementType();

		if ('element' === type && 'section' !== elementType) {
			$dimensionsElement = $element;
			let childElementSelector;

			if ('column' === elementType) {
				childElementSelector = '.elementor-column-wrap';
			} else {
				childElementSelector = '.elementor-widget-container';
			}

			$element = $element.find('> ' + childElementSelector);
		}

		const options = {
			type: type,
			interactions: interactions,
			$element: $element,
			$dimensionsElement: $dimensionsElement,
			refreshDimensions: this.isEdit,
			range: elementSettings[name + '_range'],
			classes: {
				element: 'elementor-motion-effects-element',
				parent: 'elementor-motion-effects-parent',
				backgroundType: 'elementor-motion-effects-element-type-background',
				container: 'elementor-motion-effects-container',
				layer: 'elementor-motion-effects-layer',
				perspective: 'elementor-motion-effects-perspective'
			}
		};

		if (!options.range && 'fixed' === this.getCurrentDeviceSetting('_position')) {
			options.range = 'page';
		}

		if ('fixed' === this.getCurrentDeviceSetting('_position')) {
			options.isFixedPosition = true;
		}

		if ('background' === type && 'column' === this.getElementType()) {
			options.addBackgroundLayerTo = ' > .elementor-element-populated';
		}

		return options;
	}

	activate(name) {
		const options = this.prepareOptions(name);

		if ($.isEmptyObject(options.interactions)) {
			return;
		}

		this[name] = new MotionFx(options);
	}

	deactivate(name) {
		if (this[name]) {
			this[name].destroy();
			delete this[name];
		}
	}

	toggle() {
		const currentDeviceMode = ceFrontend.getCurrentDeviceMode(),
					elementSettings = this.getElementSettings();

		['motion_fx', 'background_motion_fx'].forEach(name => {
			const devices = elementSettings[name + '_devices'],
						isCurrentModeActive = !devices || -1 !== devices.indexOf(currentDeviceMode);

			if (isCurrentModeActive && (elementSettings[name + '_motion_fx_scrolling'] || elementSettings[name + '_motion_fx_mouse'])) {
				if (this[name]) {
					this.refreshInstance(name);
				} else {
					this.activate(name);
				}
			} else {
				this.deactivate(name);
			}
		});
	}

	refreshInstance(instanceName) {
		const instance = this[instanceName];

		if (!instance) {
			return;
		}

		const preparedOptions = this.prepareOptions(instanceName);
		instance.setSettings(preparedOptions);
		instance.refresh();
	}

	onInit() {
		super.onInit();
		this.initEffects();
		this.toggle();
	}

	onElementChange(propertyName) {
		if (/motion_fx_((scrolling)|(mouse)|(devices))$/.test(propertyName)) {
			this.toggle();
			return;
		}

		const propertyMatches = propertyName.match('.*?motion_fx');

		if (propertyMatches) {
			const instanceName = propertyMatches[0];
			this.refreshInstance(instanceName);

			if (!this[instanceName]) {
				this.activate(instanceName);
			}
		}

		if (/^_position/.test(propertyName)) {
			['motion_fx', 'background_motion_fx'].forEach(instanceName => {
				this.refreshInstance(instanceName);
			});
		}
	}

	onDestroy() {
		super.onDestroy();
		['motion_fx', 'background_motion_fx'].forEach(name => {
			this.deactivate(name);
		});
	}
}

exports.default = ($scope) => {
	ceFrontend.elementsHandler.addHandler(MotionEffectsHandler, {$element: $scope});
};

/***/ }),
/* 401 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var Scroll = __webpack_require__(402).default;

var MouseMove = __webpack_require__(403).default;

var Actions = __webpack_require__(404).default;

class MotionFX extends elementorModules.ViewModule {
	getDefaultSettings() {
		return {
			type: 'element',
			$element: null,
			$dimensionsElement: null,
			addBackgroundLayerTo: null,
			interactions: {},
			refreshDimensions: false,
			range: 'viewport',
			classes: {
				element: 'motion-fx-element',
				parent: 'motion-fx-parent',
				backgroundType: 'motion-fx-element-type-background',
				container: 'motion-fx-container',
				layer: 'motion-fx-layer',
				perspective: 'motion-fx-perspective'
			}
		};
	}

	bindEvents() {
		this.onWindowResize = this.onWindowResize.bind(this);
		ceFrontend.elements.$window.on('resize', this.onWindowResize);
	}

	unbindEvents() {
		ceFrontend.elements.$window.off('resize', this.onWindowResize);
	}

	addBackgroundLayer() {
		const settings = this.getSettings();
		this.elements.$motionFXContainer = $('<div>', {class: settings.classes.container});
		this.elements.$motionFXLayer = $('<div>', {class: settings.classes.layer});
		this.updateBackgroundLayerSize();
		this.elements.$motionFXContainer.prepend(this.elements.$motionFXLayer);
		const $addBackgroundLayerTo = settings.addBackgroundLayerTo ? this.$element.find(settings.addBackgroundLayerTo) : this.$element;
		$addBackgroundLayerTo.prepend(this.elements.$motionFXContainer);
	}

	removeBackgroundLayer() {
		this.elements.$motionFXContainer.remove();
	}

	updateBackgroundLayerSize() {
		const settings = this.getSettings(),
					speed = {
						x: 0,
						y: 0
					},
					mouseInteraction = settings.interactions.mouseMove,
					scrollInteraction = settings.interactions.scroll;

		if (mouseInteraction && mouseInteraction.translateXY) {
			speed.x = mouseInteraction.translateXY.speed * 10;
			speed.y = mouseInteraction.translateXY.speed * 10;
		}

		if (scrollInteraction) {
			if (scrollInteraction.translateX) {
				speed.x = scrollInteraction.translateX.speed * 10;
			}

			if (scrollInteraction.translateY) {
				speed.y = scrollInteraction.translateY.speed * 10;
			}
		}

		this.elements.$motionFXLayer.css({
			width: 100 + speed.x + '%',
			height: 100 + speed.y + '%'
		});
	}

	defineDimensions() {
		const $dimensionsElement = this.getSettings('$dimensionsElement') || this.$element,
					elementOffset = $dimensionsElement.offset();
		const dimensions = {
			elementHeight: $dimensionsElement.outerHeight(),
			elementWidth: $dimensionsElement.outerWidth(),
			elementTop: elementOffset.top,
			elementLeft: elementOffset.left
		};
		dimensions.elementRange = dimensions.elementHeight + innerHeight;
		this.setSettings('dimensions', dimensions);

		if ('background' === this.getSettings('type')) {
			this.defineBackgroundLayerDimensions();
		}
	}

	defineBackgroundLayerDimensions() {
		const dimensions = this.getSettings('dimensions');
		dimensions.layerHeight = this.elements.$motionFXLayer.height();
		dimensions.layerWidth = this.elements.$motionFXLayer.width();
		dimensions.movableX = dimensions.layerWidth - dimensions.elementWidth;
		dimensions.movableY = dimensions.layerHeight - dimensions.elementHeight;
		this.setSettings('dimensions', dimensions);
	}

	initInteractionsTypes() {
		this.interactionsTypes = {
			scroll: Scroll,
			mouseMove: MouseMove
		};
	}

	prepareSpecialActions() {
		const settings = this.getSettings(),
					hasTiltEffect = !!(settings.interactions.mouseMove && settings.interactions.mouseMove.tilt);
		this.elements.$parent.toggleClass(settings.classes.perspective, hasTiltEffect);
	}

	cleanSpecialActions() {
		const settings = this.getSettings();
		this.elements.$parent.removeClass(settings.classes.perspective);
	}

	runInteractions() {
		const settings = this.getSettings();
		this.prepareSpecialActions();
		$.each(settings.interactions, (interactionName, actions) => {
			this.interactions[interactionName] = new this.interactionsTypes[interactionName]({
				motionFX: this,
				callback: (...args) => {
					$.each(actions, (actionName, actionData) => {
						return this.actions.runAction(actionName, actionData, ...args);
					});
				}
			});

			this.interactions[interactionName].run();
		});
	}

	destroyInteractions() {
		this.cleanSpecialActions();
		$.each(this.interactions, (interactionName, interaction) => interaction.destroy());
		this.interactions = {};
	}

	refresh() {
		this.actions.setSettings(this.getSettings());

		if ('background' === this.getSettings('type')) {
			this.updateBackgroundLayerSize();
			this.defineBackgroundLayerDimensions();
		}

		this.actions.refresh();
		this.destroyInteractions();
		this.runInteractions();
	}

	destroy() {
		this.destroyInteractions();
		this.actions.refresh();
		const settings = this.getSettings();
		this.$element.removeClass(settings.classes.element);
		this.elements.$parent.removeClass(settings.classes.parent);

		if ('background' === settings.type) {
			this.$element.removeClass(settings.classes.backgroundType);
			this.removeBackgroundLayer();
		}
	}

	onInit() {
		super.onInit();
		const settings = this.getSettings();
		this.$element = settings.$element;
		this.elements.$parent = this.$element.parent();
		this.$element.addClass(settings.classes.element);
		this.elements.$parent = this.$element.parent();
		this.elements.$parent.addClass(settings.classes.parent);

		if ('background' === settings.type) {
			this.$element.addClass(settings.classes.backgroundType);
			this.addBackgroundLayer();
		}

		this.defineDimensions();
		settings.$targetElement = 'element' === settings.type ? this.$element : this.elements.$motionFXLayer;
		this.interactions = {};
		this.actions = new Actions(settings);
		this.initInteractionsTypes();
		this.runInteractions();
	}

	onWindowResize() {
		this.defineDimensions();
	}
}

exports.default = MotionFX;

/***/ }),
/* 402 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var MotionFXBase = __webpack_require__(399).default;

var Scroll = __webpack_require__(398).default;

class ScrollHandler extends MotionFXBase {
	run() {
		if (pageYOffset === this.windowScrollTop) {
			return false;
		}

		this.onScrollMovement();
		this.windowScrollTop = pageYOffset;
	}

	onScrollMovement() {
		this.updateMotionFxDimensions();
		this.updateAnimation();
	}

	updateMotionFxDimensions() {
		const motionFXSettings = this.motionFX.getSettings();

		if (motionFXSettings.refreshDimensions) {
			this.motionFX.defineDimensions();
		}
	}

	updateAnimation() {
		let passedRangePercents;

		if ('page' === this.motionFX.getSettings('range')) {
			passedRangePercents = Scroll.getPageScrollPercentage();
		} else if (this.motionFX.getSettings('isFixedPosition')) {
			passedRangePercents = Scroll.getPageScrollPercentage({}, window.innerHeight);
		} else {
			passedRangePercents = Scroll.getElementViewportPercentage(this.motionFX.elements.$parent);
		}

		this.runCallback(passedRangePercents);
	}
}

exports.default = ScrollHandler;

/***/ }),
/* 403 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var MotionFXBase = __webpack_require__(399).default;

class MouseMoveInteraction extends MotionFXBase {
	bindEvents() {
		if (!MouseMoveInteraction.mouseTracked) {
			ceFrontend.elements.$window.on('mousemove', MouseMoveInteraction.updateMousePosition);
			MouseMoveInteraction.mouseTracked = true;
		}
	}

	run() {
		const mousePosition = MouseMoveInteraction.mousePosition,
					oldMousePosition = this.oldMousePosition;

		if (oldMousePosition.x === mousePosition.x && oldMousePosition.y === mousePosition.y) {
			return;
		}

		this.oldMousePosition = {
			x: mousePosition.x,
			y: mousePosition.y
		};

		const passedPercentsX = 100 / innerWidth * mousePosition.x;
		const passedPercentsY = 100 / innerHeight * mousePosition.y;

		this.runCallback(passedPercentsX, passedPercentsY);
	}

	onInit() {
		this.oldMousePosition = {};
		super.onInit();
	}

	static mousePosition = {};

	static updateMousePosition = (event) => {
		MouseMoveInteraction.mousePosition = {
			x: event.clientX,
			y: event.clientY
		};
	};
}

exports.default = MouseMoveInteraction;

/***/ }),
/* 404 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class MotionFXActions extends elementorModules.Module {
	constructor() {
		super(...arguments);
		this.onInit();
	}

	getMovePointFromPassedPercents(movableRange, passedPercents) {
		const movePoint = passedPercents / movableRange * 100;
		return +movePoint.toFixed(2);
	}

	getEffectValueFromMovePoint(range, movePoint) {
		return range * movePoint / 100;
	}

	getStep(passedPercents, options) {
		if ('element' === this.getSettings('type')) {
			return this.getElementStep(passedPercents, options);
		}

		return this.getBackgroundStep(passedPercents, options);
	}

	getElementStep(passedPercents, options) {
		return -(passedPercents - 50) * options.speed;
	}

	getBackgroundStep(passedPercents, options) {
		const movableRange = this.getSettings('dimensions.movable' + options.axis.toUpperCase());
		return -this.getEffectValueFromMovePoint(movableRange, passedPercents);
	}

	getDirectionMovePoint(passedPercents, direction, range) {
		let movePoint;

		if (passedPercents < range.start) {
			if ('out-in' === direction) {
				movePoint = 0;
			} else if ('in-out' === direction) {
				movePoint = 100;
			} else {
				movePoint = this.getMovePointFromPassedPercents(range.start, passedPercents);

				if ('in-out-in' === direction) {
					movePoint = 100 - movePoint;
				}
			}
		} else if (passedPercents < range.end) {
			if ('in-out-in' === direction) {
				movePoint = 0;
			} else if ('out-in-out' === direction) {
				movePoint = 100;
			} else {
				movePoint = this.getMovePointFromPassedPercents(range.end - range.start, passedPercents - range.start);

				if ('in-out' === direction) {
					movePoint = 100 - movePoint;
				}
			}
		} else if ('in-out' === direction) {
			movePoint = 0;
		} else if ('out-in' === direction) {
			movePoint = 100;
		} else {
			movePoint = this.getMovePointFromPassedPercents(100 - range.end, 100 - passedPercents);

			if ('in-out-in' === direction) {
				movePoint = 100 - movePoint;
			}
		}

		return movePoint;
	}

	translateX(actionData, passedPercents) {
		actionData.axis = 'x';
		actionData.unit = 'px';
		this.transform('translateX', passedPercents, actionData);
	}

	translateY(actionData, passedPercents) {
		actionData.axis = 'y';
		actionData.unit = 'px';
		this.transform('translateY', passedPercents, actionData);
	}

	translateXY(actionData, passedPercentsX, passedPercentsY) {
		this.translateX(actionData, passedPercentsX);
		this.translateY(actionData, passedPercentsY);
	}

	tilt(actionData, passedPercentsX, passedPercentsY) {
		const options = {
			speed: actionData.speed / 10,
			direction: actionData.direction
		};
		this.rotateX(options, passedPercentsY);
		this.rotateY(options, 100 - passedPercentsX);
	}

	rotateX(actionData, passedPercents) {
		actionData.axis = 'x';
		actionData.unit = 'deg';
		this.transform('rotateX', passedPercents, actionData);
	}

	rotateY(actionData, passedPercents) {
		actionData.axis = 'y';
		actionData.unit = 'deg';
		this.transform('rotateY', passedPercents, actionData);
	}

	rotateZ(actionData, passedPercents) {
		actionData.unit = 'deg';
		this.transform('rotateZ', passedPercents, actionData);
	}

	scale(actionData, passedPercents) {
		const movePoint = this.getDirectionMovePoint(passedPercents, actionData.direction, actionData.range);
		this.updateRulePart('transform', 'scale', 1 + actionData.speed * movePoint / 1000);
	}

	transform(action, passedPercents, actionData) {
		if (actionData.direction) {
			passedPercents = 100 - passedPercents;
		}

		this.updateRulePart('transform', action, this.getStep(passedPercents, actionData) + actionData.unit);
	}

	opacity(actionData, passedPercents) {
		const movePoint = this.getDirectionMovePoint(passedPercents, actionData.direction, actionData.range),
					level = actionData.level / 10,
					opacity = 1 - level + this.getEffectValueFromMovePoint(level, movePoint);
		this.$element.css({
			opacity: opacity,
			'will-change': 'opacity'
		});
	}

	blur(actionData, passedPercents) {
		const movePoint = this.getDirectionMovePoint(passedPercents, actionData.direction, actionData.range),
					blur = actionData.level - this.getEffectValueFromMovePoint(actionData.level, movePoint);
		this.updateRulePart('filter', 'blur', blur + 'px');
	}

	updateRulePart(ruleName, key, value) {
		if (!this.rulesVariables[ruleName]) {
			this.rulesVariables[ruleName] = {};
		}

		if (!this.rulesVariables[ruleName][key]) {
			this.rulesVariables[ruleName][key] = true;
			this.updateRule(ruleName);
		}

		const cssVarKey = '--' + key;
		this.$element[0].style.setProperty(cssVarKey, value);
	}

	updateRule(ruleName) {
		let value = '';
		$.each(this.rulesVariables[ruleName], function (variableKey) {
			value += variableKey + '(var(--' + variableKey + '))';
		});
		this.$element.css(ruleName, value);
	}

	runAction(actionName, actionData, passedPercents, ...args) {
		if (actionData.affectedRange) {
			if (actionData.affectedRange.start > passedPercents) {
				passedPercents = actionData.affectedRange.start;
			}

			if (actionData.affectedRange.end < passedPercents) {
				passedPercents = actionData.affectedRange.end;
			}
		}

		this[actionName](actionData, passedPercents, ...args);
	}

	refresh() {
		this.rulesVariables = {};
		this.$element.css({
			transform: '',
			filter: '',
			opacity: '',
			'will-change': ''
		});
	}

	onInit() {
		this.$element = this.getSettings('$targetElement');
		this.refresh();
	}
}

exports.default = MotionFXActions;

/***/ }),
/* 405 */
/***/ (function(module, exports, __webpack_require__) {

var StickyHandler = elementorModules.frontend.handlers.Base.extend({
	bindEvents: function () {
		ceFrontend.addListenerOnce(this.getUniqueHandlerID() + 'sticky', 'resize', this.run);
	},
	unbindEvents: function () {
		ceFrontend.removeListeners(this.getUniqueHandlerID() + 'sticky', 'resize', this.run);
	},
	isActive: function () {
		return undefined !== this.$element.data('sticky');
	},
	activate: function () {
		var elementSettings = this.getElementSettings(),
				stickyOptions = {
			to: elementSettings.sticky,
			offset: elementSettings.sticky_offset,
			$offset: $(elementSettings.sticky_dynamic_offset),
			effectsOffset: elementSettings.sticky_effects_offset,
			autoHide: elementSettings.sticky_auto_hide,
			autoHideOffset: elementSettings.sticky_auto_hide_offset || {size: 0},
			autoHideDuration: elementSettings.sticky_auto_hide_duration || {size: 0},
			classes: {
				sticky: 'elementor-sticky',
				stickyActive: 'elementor-sticky--active elementor-section--handles-inside',
				stickyEffects: 'elementor-sticky--effects',
				stickyHide: 'ce-sticky--hide',
				spacer: 'elementor-sticky__spacer'
			}
		};

		if (elementSettings.sticky_parent) {
			stickyOptions.parent = '.elementor-widget-wrap';
		}

		this.$element.sticky(stickyOptions);
	},
	deactivate: function () {
		if (!this.isActive()) {
			return;
		}
		this.$element.sticky('destroy');
	},
	run: function (refresh) {
		if (!this.getElementSettings('sticky')) {
			this.deactivate();
			return;
		}

		var currentDeviceMode = ceFrontend.getCurrentDeviceMode(),
				activeDevices = this.getElementSettings('sticky_on');

		if (~activeDevices.indexOf(currentDeviceMode)) {
			if (true === refresh) {
				this.reactivate();
			} else if (!this.isActive()) {
				this.activate();
			}
		} else {
			this.deactivate();
		}
	},
	reactivate: function () {
		this.deactivate();
		this.activate();
	},
	onElementChange: function (settingKey) {
		if (~['sticky', 'sticky_on'].indexOf(settingKey)) {
			this.run(true);
		}

		if (~['sticky_offset', 'sticky_effects_offset', 'sticky_parent', 'sticky_auto_hide', 'sticky_auto_hide_offset', 'sticky_auto_hide_duration'].indexOf(settingKey)) {
			this.reactivate();
		}
	},
	onInit: function () {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
		this.run();
	},
	onDestroy: function () {
		elementorModules.frontend.handlers.Base.prototype.onDestroy.apply(this, arguments);
		this.deactivate();
	}
});

module.exports = $scope => {
	new StickyHandler({$element: $scope});
};

/***/ }),
/* 406 */,
/* 407 */,
/* 408 */,
/* 409 */,
/* 410 */,
/* 411 */,
/* 412 */,
/* 413 */,
/* 414 */,
/* 415 */,
/* 416 */,
/* 417 */,
/* 418 */,
/* 419 */,
/* 420 */,
/* 421 */,
/* 422 */,
/* 423 */,
/* 424 */,
/* 425 */,
/* 426 */,
/* 427 */,
/* 428 */,
/* 429 */,
/* 430 */,
/* 431 */,
/* 432 */,
/* 433 */,
/* 434 */,
/* 435 */,
/* 436 */,
/* 437 */,
/* 438 */,
/* 439 */,
/* 440 */,
/* 441 */,
/* 442 */,
/* 443 */,
/* 444 */,
/* 445 */,
/* 446 */,
/* 447 */,
/* 448 */,
/* 449 */,
/* 450 */,
/* 451 */,
/* 452 */,
/* 453 */,
/* 454 */,
/* 455 */,
/* 456 */,
/* 457 */,
/* 458 */,
/* 459 */,
/* 460 */,
/* 461 */,
/* 462 */,
/* 463 */,
/* 464 */,
/* 465 */,
/* 466 */,
/* 467 */,
/* 468 */,
/* 469 */,
/* 470 */,
/* 471 */,
/* 472 */,
/* 473 */,
/* 474 */,
/* 475 */,
/* 476 */,
/* 477 */,
/* 478 */,
/* 479 */,
/* 480 */,
/* 481 */,
/* 482 */,
/* 483 */,
/* 484 */,
/* 485 */,
/* 486 */,
/* 487 */,
/* 488 */,
/* 489 */,
/* 490 */,
/* 491 */,
/* 492 */,
/* 493 */,
/* 494 */,
/* 495 */,
/* 496 */,
/* 497 */,
/* 498 */,
/* 499 */,
/* 500 */,
/* 501 */,
/* 502 */,
/* 503 */,
/* 504 */,
/* 505 */,
/* 506 */,
/* 507 */,
/* 508 */,
/* 509 */,
/* 510 */,
/* 511 */,
/* 512 */,
/* 513 */,
/* 514 */,
/* 515 */,
/* 516 */,
/* 517 */,
/* 518 */,
/* 519 */,
/* 520 */,
/* 521 */,
/* 522 */,
/* 523 */,
/* 524 */,
/* 525 */,
/* 526 */,
/* 527 */,
/* 528 */,
/* 529 */,
/* 530 */,
/* 531 */,
/* 532 */,
/* 533 */,
/* 534 */,
/* 535 */,
/* 536 */,
/* 537 */,
/* 538 */,
/* 539 */,
/* 540 */,
/* 541 */,
/* 542 */,
/* 543 */,
/* 544 */,
/* 545 */,
/* 546 */,
/* 547 */,
/* 548 */,
/* 549 */,
/* 550 */,
/* 551 */,
/* 552 */,
/* 553 */,
/* 554 */,
/* 555 */,
/* 556 */,
/* 557 */,
/* 558 */,
/* 559 */,
/* 560 */,
/* 561 */,
/* 562 */,
/* 563 */,
/* 564 */,
/* 565 */,
/* 566 */,
/* 567 */,
/* 568 */,
/* 569 */,
/* 570 */,
/* 571 */,
/* 572 */,
/* 573 */,
/* 574 */,
/* 575 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class BaseTabs extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				tabTitle: '.elementor-tab-title',
				tabContent: '.elementor-tab-content'
			},
			classes: {
				active: 'elementor-active'
			},
			showTabFn: 'show',
			hideTabFn: 'hide',
			toggleSelf: true,
			hidePrevious: true,
			autoExpand: true
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings('selectors');
		return {
			$tabTitles: this.findElement(selectors.tabTitle),
			$tabContents: this.findElement(selectors.tabContent)
		};
	}

	activateDefaultTab() {
		const settings = this.getSettings();

		if (!settings.autoExpand || (settings.autoExpand === 'editor' && !this.isEdit)) {
			return;
		}

		const defaultActiveTab = this.getEditSettings('activeItemIndex') || 1;
		const originalToggleMethods = {
			showTabFn: settings.showTabFn,
			hideTabFn: settings.hideTabFn
		};

		// Toggle tabs without animation to avoid jumping
		this.setSettings({
			showTabFn: 'show',
			hideTabFn: 'hide'
		});
		this.changeActiveTab(defaultActiveTab);

		// Return back original toggle effects
		this.setSettings(originalToggleMethods);
	}

	deactivateActiveTab(tabIndex) {
		const settings = this.getSettings(),
					activeClass = settings.classes.active,
					activeFilter = tabIndex ? `[data-tab="${tabIndex}"]` : `.${activeClass}`,
					$activeTitle = this.elements.$tabTitles.filter(activeFilter),
					$activeContent = this.elements.$tabContents.filter(activeFilter);

		$activeTitle.add($activeContent).removeClass(activeClass);
		$activeContent[settings.hideTabFn]();
	}

	activateTab(tabIndex) {
		const settings = this.getSettings(),
					activeClass = settings.classes.active,
					$requestedTitle = this.elements.$tabTitles.filter(`[data-tab="${tabIndex}"]`),
					$requestedContent = this.elements.$tabContents.filter(`[data-tab="${tabIndex}"]`);

		$requestedTitle.add($requestedContent).addClass(activeClass);
		$requestedContent[settings.showTabFn]();
	}

	isActiveTab(tabIndex) {
		return this.elements.$tabTitles.filter(`[data-tab="${tabIndex}"]`).hasClass(this.getSettings('classes.active'));
	}

	bindEvents() {
		this.elements.$tabTitles.on({
			keydown: (event) => {
				if (event.key === 'Enter') {
					event.preventDefault();
					this.changeActiveTab(event.currentTarget.getAttribute('data-tab'));
				}
			},
			click: (event) => {
				event.preventDefault();
				this.changeActiveTab(event.currentTarget.getAttribute('data-tab'));
			}
		});
	}

	onInit(...args) {
		super.onInit(...args);
		this.activateDefaultTab();
	}

	onEditSettingsChange(propertyName) {
		if (propertyName === 'activeItemIndex') {
			this.activateDefaultTab();
		}
	}

	changeActiveTab(tabIndex) {
		const isActiveTab = this.isActiveTab(tabIndex),
					settings = this.getSettings();

		if ((settings.toggleSelf || !isActiveTab) && settings.hidePrevious) {
			this.deactivateActiveTab();
		}

		if (!settings.hidePrevious && isActiveTab) {
			this.deactivateActiveTab(tabIndex);
		}

		if (!isActiveTab) {
			this.activateTab(tabIndex);
		}
	}
}

exports.default = BaseTabs;

/***/ }),
/* 576 */,
/* 577 */,
/* 578 */,
/* 579 */,
/* 580 */,
/* 581 */,
/* 582 */,
/* 583 */,
/* 584 */,
/* 585 */,
/* 586 */,
/* 587 */,
/* 588 */,
/* 589 */,
/* 590 */,
/* 591 */,
/* 592 */,
/* 593 */,
/* 594 */,
/* 595 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class BaseLoader extends elementorModules.ViewModule {
	getDefaultSettings() {
		return {
			isInserted: false,
			selectors: {
				firstScript: 'script:first'
			}
		};
	}

	getDefaultElements() {
		return {
			$firstScript: $(this.getSettings('selectors.firstScript'))
		};
	}

	insertAPI() {
		this.elements.$firstScript.before($('<script>', {
			src: this.getApiURL()
		}));
		this.setSettings('isInserted', true);
	}

	getVideoIDFromURL(url) {
		const videoIDParts = url.match(this.getURLRegex());
		return videoIDParts && videoIDParts[1];
	}

	onApiReady(callback) {
		if (!this.getSettings('isInserted')) {
			this.insertAPI();
		}
		if (this.isApiLoaded()) {
			callback(this.getApiObject());
		} else {
			// If not ready check again by timeout..
			setTimeout(() => {
				this.onApiReady(callback);
			}, 350);
		}
	}
}

exports.default = BaseLoader;

/***/ }),
/* 596 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class BackgroundSlideshow extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			classes: {
				swiperContainer: 'elementor-background-slideshow swiper',
				swiperWrapper: 'swiper-wrapper',
				swiperSlide: 'elementor-background-slideshow__slide swiper-slide',
				swiperSlideInner: 'elementor-background-slideshow__slide__image',
				kenBurns: 'elementor-ken-burns',
				kenBurnsActive: 'elementor-ken-burns--active',
				kenBurnsIn: 'elementor-ken-burns--in',
				kenBurnsOut: 'elementor-ken-burns--out'
			}
		};
	}

	getDefaultElements() {
		const classes = this.getSettings('classes');
		const elements = {
			$slider: this.$element.find('.' + classes.swiperContainer)
		};
		elements.$mainSwiperSlides = elements.$slider.find('.' + classes.swiperSlide);
		return elements;
	}

	getSwiperOptions() {
		const elementSettings = this.getElementSettings();

		const swiperOptions = {
			grabCursor: false,
			slidesPerView: 1,
			slidesPerGroup: 1,
			loop: 'yes' === elementSettings.background_slideshow_loop,
			speed: elementSettings.background_slideshow_transition_duration,
			autoplay: {
				delay: elementSettings.background_slideshow_slide_duration,
				stopOnLastSlide: !elementSettings.background_slideshow_loop
			},
			handleElementorBreakpoints: true,
			on: {
				slideChange: () => {
					this.handleKenBurns();
				}
			}
		};

		if ('yes' === elementSettings.background_slideshow_loop) {
			swiperOptions.loopedSlides = this.getSlidesCount();
		}

		switch (elementSettings.background_slideshow_slide_transition) {
			case 'fade':
				swiperOptions.effect = 'fade';
				swiperOptions.fadeEffect = {
					crossFade: true
				};
				break;

			case 'slide_down':
				swiperOptions.autoplay.reverseDirection = true;
				// falls through
			case 'slide_up':
				swiperOptions.direction = 'vertical';
				break;

			default:
				swiperOptions.effect = elementSettings.background_slideshow_slide_transition;
		}

		if ('yes' === elementSettings.background_slideshow_lazyload) {
			swiperOptions.lazy = {
				loadPrevNext: true,
				loadPrevNextAmount: 1
			};
		}

		return swiperOptions;
	}

	getInitialSlide() {
		const editSettings = this.getEditSettings();
		return editSettings.activeItemIndex ? editSettings.activeItemIndex - 1 : 0;
	}

	handleKenBurns() {
		const elementSettings = this.getElementSettings();

		if (!elementSettings.background_slideshow_ken_burns) {
			return;
		}

		const settings = this.getSettings();

		if (this.$activeImageBg) {
			this.$activeImageBg.removeClass(settings.classes.kenBurnsActive);
		}

		this.activeItemIndex = this.swiper ? this.swiper.activeIndex : this.getInitialSlide();

		if (this.swiper) {
			this.$activeImageBg = $(this.swiper.slides[this.activeItemIndex]).children('.' + settings.classes.swiperSlideInner);
		} else {
			this.$activeImageBg = $(this.elements.$mainSwiperSlides[0]).children('.' + settings.classes.swiperSlideInner);
		}

		this.$activeImageBg.addClass(settings.classes.kenBurnsActive);
	}

	getSlidesCount() {
		return this.elements.$slides.length;
	}

	buildSwiperElements() {
		const classes = this.getSettings('classes'),
					elementSettings = this.getElementSettings(),
					direction = 'slide_left' === elementSettings.background_slideshow_slide_transition ? 'ltr' : 'rtl',
					$container = $('<div>', {class: classes.swiperContainer, dir: direction}),
					$wrapper = $('<div>', {class: classes.swiperWrapper});

		let slideInnerClass = classes.swiperSlideInner;

		if (elementSettings.background_slideshow_ken_burns) {
			slideInnerClass += ' ' + classes.kenBurns;
			const kenBurnsDirection = 'in' === elementSettings.background_slideshow_ken_burns_zoom_direction ? 'kenBurnsIn' : 'kenBurnsOut';
			slideInnerClass += ' ' + classes[kenBurnsDirection];
		}

		this.elements.$slides = $();
		elementSettings.background_slideshow_gallery.forEach(slide => {
			const url = slide.image.url && -1 < slide.image.url.indexOf('://') ? slide.image.url : prestashop.urls.base_url + slide.image.url,
						$slide = $('<div>', {class: classes.swiperSlide}),
						$slidebg = $('<img>', {class: slideInnerClass, src: url, alt: slide.image.alt || ''});
			slide.image.title && $slidebg.attr('title', slide.image.title);

			if (!slide.image.loading) {
				$slidebg.attr('loading', 'lazy');
				$slidebg.after('<div class="swiper-lazy-preloader"></div>');
			}
			$slide.append($slidebg);
			$wrapper.append($slide);
			this.elements.$slides = this.elements.$slides.add($slide);
		});
		$container.append($wrapper);
		this.$element.prepend($container);
		this.elements.$backgroundSlideShowContainer = $container;
	}

	initSlider() {
		const slidesCount = this.getSlidesCount();
		if (1 > slidesCount) {
			return;
		}
		this.$element.css('background-image', '');
		const swiperOptions = this.getSwiperOptions();
		if (1 === slidesCount) {
			swiperOptions.loop = false;
		}
		this.swiper = new ceFrontend.utils.swiper(this.elements.$backgroundSlideShowContainer, swiperOptions);
		this.elements.$backgroundSlideShowContainer.data('swiper', this.swiper);
		this.handleKenBurns();
	}

	activate() {
		this.buildSwiperElements();
		this.initSlider();
	}

	deactivate() {
		if (this.swiper) {
			this.swiper.destroy();
			this.elements.$backgroundSlideShowContainer.remove();
		}
	}

	run() {
		if ('slideshow' === this.getElementSettings('background_background')) {
			this.activate();
		} else {
			this.deactivate();
		}
	}

	onInit() {
		super.onInit();

		if (this.getElementSettings('background_slideshow_gallery')) {
			this.run();
		}
	}

	onDestroy() {
		super.onDestroy();
		this.deactivate();
	}

	onElementChange(propertyName) {
		if ('background_background' === propertyName) {
			this.run();
		}
	}
}

exports.default = BackgroundSlideshow;

/***/ }),
/* 597 */,
/* 598 */,
/* 599 */,
/* 600 */,
/* 601 */,
/* 602 */,
/* 603 */,
/* 604 */,
/* 605 */,
/* 606 */,
/* 607 */,
/* 608 */,
/* 609 */,
/* 610 */,
/* 611 */,
/* 612 */,
/* 613 */,
/* 614 */,
/* 615 */,
/* 616 */,
/* 617 */,
/* 618 */,
/* 619 */,
/* 620 */,
/* 621 */,
/* 622 */,
/* 623 */,
/* 624 */,
/* 625 */,
/* 626 */,
/* 627 */,
/* 628 */,
/* 629 */,
/* 630 */,
/* 631 */,
/* 632 */,
/* 633 */,
/* 634 */,
/* 635 */
/***/ (function(module, exports, __webpack_require__) {

var NumberFormatter = {
	CURRENCY_SYMBOL_PLACEHOLDER: '¤',
	DECIMAL_SEPARATOR_PLACEHOLDER: '.',
	GROUP_SEPARATOR_PLACEHOLDER: ',',
	PERCENT_SYMBOL_PLACEHOLDER: '%',
	MINUS_SIGN_PLACEHOLDER: '-',
	PLUS_SIGN_PLACEHOLDER: '+',

	s11n: null,

	format: function(number, specification) {
		this.s11n = specification;
		var pattern = this.s11n[number < 0 ? 'negativePattern' : 'positivePattern'],
				num = Math.abs(number).toFixed(this.s11n.maxFractionDigits),
				digits = num.toString().split('.'),
				majorDigits = this.splitMajorGroups(digits[0]),
				minorDigits = this.adjustMinorDigitsZeroes(digits[1] || ''),
				formattedNumber = majorDigits;
		if (minorDigits) {
			formattedNumber += this.DECIMAL_SEPARATOR_PLACEHOLDER + minorDigits;
		}
		formattedNumber = this.addPlaceholders(formattedNumber, pattern);
		formattedNumber = this.replaceSymbols(formattedNumber);

		return this.performSpecificReplacements(formattedNumber);
	},
	splitMajorGroups: function(digit) {
		if (!this.s11n.groupingUsed) {
			return digit;
		}
		var majorDigits = digit.split('').reverse(),
				groups = [];
		groups.push(majorDigits.splice(0, this.s11n.primaryGroupSize));
		while (majorDigits.length) {
			groups.push(majorDigits.splice(0, this.s11n.secondaryGroupSize));
		}
		groups = groups.reverse().map(group => group.reverse().join(''));

		return groups.join(this.GROUP_SEPARATOR_PLACEHOLDER);
	},
	adjustMinorDigitsZeroes: function(digit) {
		if (digit.length > this.s11n.maxFractionDigits) {
			digit = digit.replace(/0+$/, '');
		}
		if (digit.length < this.s11n.minFractionDigits) {
			digit = digit.padEnd(this.s11n.minFractionDigits, '0');
		}

		return digit;
	},
	addPlaceholders: function(formattedNumber, pattern) {
		return pattern.replace(/#?(,#+)*0(\.[0#]+)*/, formattedNumber);
	},
	replaceSymbols: function(number) {
		var symbols = this.s11n.symbol || this.s11n.numberSymbols,
				map = {};
		map[this.DECIMAL_SEPARATOR_PLACEHOLDER] = symbols[0];
		map[this.GROUP_SEPARATOR_PLACEHOLDER] = symbols[1];
		map[this.PERCENT_SYMBOL_PLACEHOLDER] = symbols[3];
		map[this.MINUS_SIGN_PLACEHOLDER] = symbols[4];
		map[this.PLUS_SIGN_PLACEHOLDER] = symbols[5];

		return this.strtr(number, map);
	},
	strtr: function(str, pairs) {
		var substrs = Object.keys(pairs).map(function escapeRE(string) {
			return string && /[\\^$.*+?()[\]{}|]/.test(string) ? string.replace(/[\\^$.*+?()[\]{}|]/g, '\\$&') : string;
		});

		return str.split(RegExp(`(${substrs.join('|')})`)).map(part => pairs[part] || part).join('');
	},
	performSpecificReplacements: function(formattedNumber) {
		return this.s11n.currencySymbol
			? formattedNumber.split(this.CURRENCY_SYMBOL_PLACEHOLDER).join(this.s11n.currencySymbol)
			: formattedNumber
		;
	}
};

exports.default = NumberFormatter;

/***/ }),
/* 636 */
/***/ (function(module, exports, __webpack_require__) {

var DocumentsManager = __webpack_require__(637).default;

// var Storage = __webpack_require__(569).default;

var Environment = __webpack_require__(207).default;

var NumberFormatter = __webpack_require__(635).default;

var YoutubeLoader = __webpack_require__(638).default;

var VimeoLoader = __webpack_require__(639).default;

var UrlActions = __webpack_require__(640).default;

var Swiper = __webpack_require__(641).default;

/* global ceFrontendConfig */

var EventManager = __webpack_require__(341),
		ElementsHandler = __webpack_require__(642),
		AnchorsModule = __webpack_require__(659),
		LightboxModule = __webpack_require__(660);

class Frontend extends elementorModules.ViewModule {
	constructor() {
		super(...arguments);
		this.config = ceFrontendConfig;
	}

	getDefaultSettings() {
		return {
			selectors: {
				elementor: '.elementor',
				adminBar: '#wpadminbar'
			},
			classes: {
				ie: 'elementor-msie'
			}
		};
	}

	getDefaultElements() {
		const defaultElements = {
			window: window,
			$window: $(window),
			$document: $(document),
			$head: $(document.head),
			$body: $(document.body),
			$deviceMode: $('<span>', {
				id: 'elementor-device-mode',
				class: 'elementor-screen-only'
			})
		};
		defaultElements.$body.append(defaultElements.$deviceMode);
		return defaultElements;
	}

	bindEvents() {
		this.elements.$window.on('resize', () => this.setDeviceModeData());
	}

	/**
	 * @deprecated 2.4.0 Use just `this.elements` instead
	 */
	getElements(elementName) {
		return this.getItems(this.elements, elementName);
	}

	/**
	 * @deprecated 2.4.0 This method was never in use
	 */
	getPageSettings(settingName) {
		const settingsObject = this.isEditMode() ? elementor.settings.page.model.attributes : this.config.settings.page;
		return this.getItems(settingsObject, settingName);
	}

	getGeneralSettings(settingName) {
		const settingsObject = this.isEditMode() ? elementor.settings.general.model.attributes : this.config.settings.general;
		return this.getItems(settingsObject, settingName);
	}

	getCurrentDeviceMode() {
		return getComputedStyle(this.elements.$deviceMode[0], ':after').content.replace(/"/g, '');
	}

	getDeviceSetting(deviceMode, settings, settingKey) {
		const devices = ['desktop', 'tablet', 'mobile'];
		let deviceIndex = devices.indexOf(deviceMode);

		while (deviceIndex > 0) {
			const currentDevice = devices[deviceIndex],
						fullSettingKey = settingKey + '_' + currentDevice,
						deviceValue = settings[fullSettingKey];

			if (deviceValue) {
				return deviceValue;
			}

			deviceIndex--;
		}

		return settings[settingKey];
	}

	getCurrentDeviceSetting(settings, settingKey) {
		return this.getDeviceSetting(ceFrontend.getCurrentDeviceMode(), settings, settingKey);
	}

	isEditMode() {
		return this.config.environmentMode.edit;
	}

	isWPPreviewMode() {
		return this.config.environmentMode.wpPreview;
	}

	initDialogsManager() {
		let dialogsManager;

		this.getDialogsManager = () => {
			if (!dialogsManager) {
				dialogsManager = new DialogsManager.Instance();
			}

			return dialogsManager;
		};
	}

	initOnReadyComponents() {
		this.utils = {
			numberFormatter: NumberFormatter,
			youtube: new YoutubeLoader(),
			vimeo: new VimeoLoader(),
			anchors: new AnchorsModule(),
			lightbox: new LightboxModule(),
			urlActions: new UrlActions(),
			swiper: Swiper
		};

		this.elementsHandler = new ElementsHandler($);

		if (this.isEditMode()) {
			elementor.once('document:loaded', () => this.onDocumentLoaded());
		} else {
			this.onDocumentLoaded();
		}
	}

	// initOnReadyElements() {
	//   this.elements.$wpAdminBar = this.elements.$document.find(this.getSettings('selectors.adminBar'));
	// }

	addIeCompatibility() {
		const el = document.createElement('div'),
					supportsGrid = 'string' === typeof el.style.grid;

		if (!Environment.ie && supportsGrid) {
			return;
		}

		this.elements.$body.addClass(this.getSettings('classes.ie'));
		const msieCss = '<link rel="stylesheet" id="elementor-frontend-css-msie" href="' + this.config.urls.assets + 'css/frontend-msie.min.css?' + this.config.version + '" type="text/css">';
		this.elements.$body.append(msieCss);
	}

	setDeviceModeData() {
		this.elements.$body.attr('data-elementor-device-mode', this.getCurrentDeviceMode());
	}

	addListenerOnce(listenerID, event, callback, to) {
		if (!to) {
			to = this.elements.$window;
		}

		if (!this.isEditMode()) {
			to.on(event, callback);
			return;
		}

		this.removeListeners(listenerID, event, to);

		if (to instanceof $) {
			const eventNS = event + '.' + listenerID;
			to.on(eventNS, callback);
		} else {
			to.on(event, callback, listenerID);
		}
	}

	removeListeners(listenerID, event, callback, from) {
		if (!from) {
			from = this.elements.$window;
		}

		if (from instanceof $) {
			const eventNS = event + '.' + listenerID;
			from.off(eventNS, callback);
		} else {
			from.off(event, callback, listenerID);
		}
	}

	// Based on underscore function
	debounce(func, wait) {
		let timeout;
		return function() {
			const context = this,
						args = arguments;

			const later = () => {
				timeout = null;
				func.apply(context, args);
			};

			const callNow = !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);

			if (callNow) {
				func.apply(context, args);
			}
		};
	}

	muteMigrationTraces() {
		$.migrateMute = true;
		$.migrateTrace = false;
	}

	init() {
		this.hooks = new EventManager();
		// this.storage = new Storage();
		this.addIeCompatibility();
		this.setDeviceModeData();
		this.initDialogsManager();

		if (this.isEditMode()) {
			this.muteMigrationTraces();
		}

		this.modules = {
			// TODO: BC Since 2.9.0
			linkActions: {
				addAction: (...args) => ceFrontend.utils.urlActions.addAction(...args)
			}
		};

		// Keep this line before `initOnReadyComponents` call
		this.elements.$window.trigger('ce/frontend/init');
		// this.initOnReadyElements();
		this.initOnReadyComponents();
	}

	onDocumentLoaded() {
		this.documentsManager = new DocumentsManager();
		this.trigger('components:init');
	}

	get Module() {
		if (this.isEditMode()) {
			parent.elementorCommon.helpers.hardDeprecated('ceFrontend.Module', '2.5.0', 'elementorModules.frontend.handlers.Base');
		}

		return elementorModules.frontend.handlers.Base;
	}
}

window.ceFrontend = new Frontend();

if (!ceFrontend.isEditMode()) {
	$(() => ceFrontend.init());
}

/***/ }),
/* 637 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class DocumentsManager extends elementorModules.ViewModule {
	constructor() {
		super(...arguments);
		this.documents = {};
		this.initDocumentClasses();
		this.attachDocumentsClasses();
	}

	getDefaultSettings() {
		return {
			selectors: {
				document: '.elementor'
			}
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings('selectors');
		return {
			$documents: $(selectors.document)
		};
	}

	initDocumentClasses() {
		this.documentClasses = {
			base: elementorModules.frontend.Document
		};
		ceFrontend.hooks.doAction('elementor/frontend/documents-manager/init-classes', this);
	}

	addDocumentClass(documentType, documentClass) {
		this.documentClasses[documentType] = documentClass;
	}

	attachDocumentsClasses() {
		this.elements.$documents.each((index, document) => this.attachDocumentClass($(document)));
	}

	attachDocumentClass($document) {
		const documentData = $document.data(),
					documentID = documentData.elementorId,
					documentType = documentData.elementorType,
					DocumentClass = this.documentClasses[documentType] || this.documentClasses.base;

		this.documents[documentID] = new DocumentClass({
			$element: $document,
			id: documentID
		});
	}
}

exports.default = DocumentsManager;

/***/ }),
/* 638 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var BaseLoader = __webpack_require__(595).default;

class YoutubeLoader extends BaseLoader {
	getApiURL() {
		return 'https://www.youtube.com/iframe_api';
	}

	getURLRegex() {
		return /^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?vi?=|(?:embed|v|vi|user)\/))([^?&"'>]+)/;
	}

	isApiLoaded() {
		return window.YT && YT.loaded;
	}

	getApiObject() {
		return YT;
	}
}

exports.default = YoutubeLoader;

/***/ }),
/* 639 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var BaseLoader = __webpack_require__(595).default;

class VimeoLoader extends BaseLoader {
	getApiURL() {
		return 'https://player.vimeo.com/api/player.js';
	}

	getURLRegex() {
		return /^(?:https?:\/\/)?(?:www|player\.)?(?:vimeo\.com\/)?(?:video\/)?(\d+)([^?&#"'>]?)/;
	}

	isApiLoaded() {
		return window.Vimeo;
	}

	getApiObject() {
		return Vimeo;
	}
}

exports.default = VimeoLoader;

/***/ }),
/* 640 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class UrlActions extends elementorModules.ViewModule {
	getDefaultSettings() {
		return {
			selectors: {
				links: 'a[href^="#ce-action"]'
			}
		};
	}

	bindEvents() {
		ceFrontend.elements.$document.on('click', this.getSettings('selectors.links'), this.runLinkAction.bind(this));
	}

	initActions() {
		function _nearestElement(from, selector, container) {
			const r = from.getBoundingClientRect(),
						p = {
							x: r.left + r.width / 2,
							y: r.top + r.height / 2,
						},
						closest = {
							d: Infinity,
							elem: null,
						};
			$(container || document.body).find(selector).each(function () {
				const r = this.getBoundingClientRect(),
							d = Math.hypot(Math.max(r.left - p.x, 0, p.x - r.right), Math.max(r.top - p.y, 0, p.y - r.bottom));
				if (d < closest.d) {
					closest.d = d;
					closest.elem = this;
				}
			});
			return closest.elem;
		}

		this.actions = {
			toggle: function (settings, event) {
				if (settings.selector && settings['class']) {
					return $(settings.selector).toggleClass(settings['class']);
				}
				if ('filters' === settings.target) {
					const $container = $(_nearestElement(event && event.currentTarget || document.activeElement, '.ce-filters__container'));

					$container.toggleClass('ce-filters--shown');

					return $container.hasClass('ce-filters--shown') && $container.find(':focusable').eq(0).focus();
				}
				setTimeout(function() {
					$({
						search: '.elementor-search__toggle:first',
						cart: '.elementor-cart__toggle > a:first',
					}[settings.target]).triggerHandler('click');
				});
			},
			lightbox: function (settings) {
				if (settings.id) {
					ceFrontend.utils.lightbox.openSlideshow(settings.id, settings.url);
				} else {
					ceFrontend.utils.lightbox.showModal(settings);
				}
			},
			closeLightbox: function () {
				const modal = ceFrontend.utils.lightbox.getModal();
				if (modal.isVisible()) {
					modal.hide();
				}
			},
			carousel: function (settings, event) {
				const carousel = _nearestElement(event && event.currentTarget || document.activeElement, '.swiper', settings.selector),
							swiper = carousel && carousel.swiper;

				if (!swiper) {
					return;
				}

				switch (settings.action) {
					case 'next':
						swiper.slideNext();
						break;
					case 'prev':
						swiper.slidePrev();
						break;
					case 'goto':
						swiper.slideTo(settings.goto - 1);
						break;
					case 'play':
						swiper.autoplay.running || swiper.autoplay.start();
						swiper.autoplay.paused && swiper.run();
						break;
					case 'pause':
						swiper.autoplay.pause();
						break;
				}
			},
			quickview: function (settings, event) {
				const dataset = settings.id_product ? {
					idProduct: settings.id_product,
					idProductAttribute: settings.id_product_attribute || 0
				} : $(event && event.currentTarget || document.activeElement).closest('[data-id-product]').data() || {
					idProduct: $('#product input[id=product_page_product_id]:first').val()
				};
				if (dataset.idProduct) {
					ceFrontend.utils.urlActions.actions.closeLightbox();

					prestashop.emit('clickQuickView', {dataset: dataset});
				}
			},
			addToCart: function (settings, event) {
				if (ceFrontend.isEditMode()) {
					return;
				}
				const data = $(event && event.currentTarget || document.activeElement).closest('[data-id-product]').data(),
					idProduct = settings.id_product || data && data.idProduct;
				if (idProduct) {
					$.post(prestashop.urls.pages.cart, {
						add: 1,
						action: 'update',
						id_product: idProduct,
						qty: settings.qty || 1,
						token: prestashop.static_token
					}, null, 'json').then(function (resp) {
						prestashop.emit('updateCart', {
							reason: {
								idProduct: resp.id_product || idProduct,
								idProductAttribute: resp.id_product_attribute,
								idCustomization: resp.id_customization,
								linkAction: 'add-to-cart',
								cart: resp.cart
							},
							resp: resp
						});
					}).fail(function (resp) {
						prestashop.emit('handleError', {eventType: 'addProductToCart', resp: resp});
					});
				} else {
					$('form[id="add-to-cart-or-refresh"] [data-button-action="add-to-cart"]:first').click();
				}
				ceFrontend.utils.urlActions.actions.closeLightbox();
			},
			buyNow: function (settings) {
				if (ceFrontend.isEditMode()) {
					return;
				}
				const events = prestashop._events.updateCart;
				delete prestashop._events.updateCart;

				prestashop.once('updateCart', function (data) {
					if (data.resp && data.resp.success) {
						location.href = prestashop.urls.pages[settings.redirect || 'order'];
					} else {
						prestashop._events.updateCart = events;
						prestashop.emit('updateCart', data);
					}
				});
				ceFrontend.utils.urlActions.actions.addToCart(settings);
			}
		};
	}

	addAction(name, callback) {
		this.actions[name] = callback;
	}

	runAction(url, ...restArgs) {
		const actionMatch = url.match(/#ce-action=(\w+)(.*)/);

		if (!actionMatch) {
			return;
		}

		const action = this.actions[actionMatch[1]];

		if (!action) {
			return;
		}

		const settings = Object.fromEntries(new URLSearchParams(actionMatch[2]));

		action(settings, ...restArgs);
	}

	runLinkAction(event) {
		event.preventDefault();
		this.runAction($(event.currentTarget).attr('href'), event);
	}

	runHashAction() {
		if (location.hash) {
			this.runAction(location.hash);
		}
	}

	createActionHash(action, settings) {
		// We need to encode the hash tag (#) here, in order to support share links for a variety of providers
		return '#ce-action=' + action + (settings && Object.keys(settings).length ? '&' + new URLSearchParams(settings).toString() : '');
	}

	onInit() {
		super.onInit();
		this.initActions();
		ceFrontend.on('components:init', this.runHashAction.bind(this));
	}
}

exports.default = UrlActions;

/***/ }),
/* 641 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

const originalSwiper = window.Swiper;

class Swiper {
	constructor(container, config) {
		this.config = config;
		if (this.config.breakpoints && this.config.handleElementorBreakpoints) {
			this.adjustConfig();
		}
		return new originalSwiper(container[0], this.config);
	}

	// Backwards compatibility for Elementor Pro <2.9.0 (old Swiper version - <5.0.0)
	// In Swiper 5.0.0 and up, breakpoints changed from acting as max-width to acting as min-width
	adjustConfig() {
		const elementorBreakpoints = ceFrontend.config.breakpoints;
		const elementorBreakpointValues = Object.values(elementorBreakpoints);

		Object.keys(this.config.breakpoints).forEach(configBPKey => {
			const configBPKeyInt = parseInt(configBPKey);
			let breakpointToUpdate;

			// The `configBPKeyInt + 1` is a BC Fix for Elementor Pro Carousels from 2.8.0-2.8.3 used with Elementor >= 2.9.0
			if (configBPKeyInt === elementorBreakpoints.md || configBPKeyInt + 1 === elementorBreakpoints.md) {
				// This handles the mobile breakpoint. Elementor's default sm breakpoint is never actually used,
				// so the mobile breakpoint (md) needs to be handled separately and set to the 0 breakpoint (xs)
				breakpointToUpdate = elementorBreakpoints.xs;
			} else {
				// Find the index of the current config breakpoint in the Elementor Breakpoints array
				const currentBPIndexInElementorBPs = elementorBreakpointValues.findIndex(elementorBP =>
					// BC Fix for Elementor Pro Carousels from 2.8.0-2.8.3 used with Elementor >= 2.9.0
					configBPKeyInt === elementorBP || configBPKeyInt + 1 === elementorBP
				);
				// For all other Swiper config breakpoints, move them one breakpoint down on the breakpoint list,
				// according to the array of Elementor's global breakpoints
				breakpointToUpdate = elementorBreakpointValues[currentBPIndexInElementorBPs - 1];
			}

			this.config.breakpoints[breakpointToUpdate] = this.config.breakpoints[configBPKey];

			// Then reset the settings in the original breakpoint key to the default values
			this.config.breakpoints[configBPKey] = {
				slidesPerView: this.config.slidesPerView,
				slidesPerGroup: this.config.slidesPerGroup ? this.config.slidesPerGroup : 1,
				spaceBetween: this.config.spaceBetween,
				autoplay: this.config.autoplay,
				loop: this.config.loop
			};
		});
	}
}

exports.default = Swiper;

/***/ }),
/* 642 */
/***/ (function(module, exports, __webpack_require__) {

var _accordion = __webpack_require__(643);

var _alert = __webpack_require__(644);

var _counter = __webpack_require__(645);

var _progress = __webpack_require__(646);

var _tabs = __webpack_require__(647);

var _toggle = __webpack_require__(648);

var _video = __webpack_require__(649);

var _imageCarousel = __webpack_require__(650);

var _textEditor = __webpack_require__(651);

var _section = __webpack_require__(652);

var _column = __webpack_require__(657);

var _global = __webpack_require__(658);

var _motionEffects = __webpack_require__(400);

var _countDown = __webpack_require__(662);

var _navMenu = __webpack_require__(664);

var _ajaxForm = __webpack_require__(666);

module.exports = function ($) {
	// element-type.skin-type
	const handlers = {
		// Elements
		section: _section.default,
		column: _column.default,
		// Widgets
		'accordion.default': _accordion.default,
		'alert.default': _alert.default,
		'counter.default': _counter.default,
		'progress.default': _progress.default,
		'tabs.default': _tabs.default,
		'toggle.default': _toggle.default,
		'video.default': _video.default,
		'image-carousel.default': _imageCarousel.default,
		'product-carousel.default': _imageCarousel.default,
		'testimonial-carousel.default': _imageCarousel.default,
		'trustedshops-reviews.default': _imageCarousel.default,
		'text-editor.default': _textEditor.default,
		'ajax-search.default': __webpack_require__(665),
		'animated-headline.default': __webpack_require__(663),
		'countdown.default': _countDown,
		'product-sale-countdown.default': _countDown,
		'nav-menu.default': _navMenu,
		'category-tree.default': _navMenu,
		'language-selector.default': _navMenu,
		'currency-selector.default': _navMenu,
		'sign-in.default': _navMenu,
		'contact-form.default': _ajaxForm,
		'email-subscription.default': _ajaxForm,
		'shopping-cart.default': __webpack_require__(667),
		'product-images.default': __webpack_require__(669),
		'listing-filters.default': __webpack_require__(670),
		'listing-pagination.default': __webpack_require__(671),
	};
	const handlersInstances = {};

	const addGlobalHandlers = () => {
		ceFrontend.hooks.addAction('frontend/element_ready/global', _global.default);
		// Motion Effects
		ceFrontend.hooks.addAction('frontend/element_ready/global', _motionEffects.default);
	};

	const addElementsHandlers = () => {
		$.each(handlers, (elementName, funcCallback) => {
			ceFrontend.hooks.addAction('frontend/element_ready/' + elementName, funcCallback);
		});
		// Sticky
		ceFrontend.hooks.addAction('frontend/element_ready/section', __webpack_require__(405));
		ceFrontend.hooks.addAction('frontend/element_ready/widget', __webpack_require__(405));
	};

	this.initHandlers = function () {
		addGlobalHandlers();
		addElementsHandlers();
	};

	this.addHandler = (HandlerClass, options) => {
		const elementID = options.$element.data('model-cid');
		let handlerID; // If element is in edit mode

		if (elementID) {
			handlerID = HandlerClass.prototype.getConstructorID();

			if (!handlersInstances[elementID]) {
				handlersInstances[elementID] = {};
			}

			const oldHandler = handlersInstances[elementID][handlerID];

			if (oldHandler) {
				oldHandler.onDestroy();
			}
		}

		const newHandler = new HandlerClass(options);

		if (elementID) {
			handlersInstances[elementID][handlerID] = newHandler;
		}
	};

	this.getHandlers = (handlerName) => {
		if (handlerName) {
			return handlers[handlerName];
		}

		return handlers;
	};

	this.runReadyTrigger = (scope) => {
		// Initializing the `$scope` as frontend jQuery instance
		const $scope = $(scope);
		const elementType = $scope.attr('data-element_type');

		if (!elementType) {
			return;
		}

		ceFrontend.hooks.doAction('frontend/element_ready/global', $scope, $);
		ceFrontend.hooks.doAction('frontend/element_ready/' + elementType, $scope, $);

		if ('widget' === elementType) {
			ceFrontend.hooks.doAction('frontend/element_ready/' + $scope.attr('data-widget_type'), $scope, $);
		}
	};

	this.initHandlers();
};

/***/ }),
/* 643 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var _baseTabs = __webpack_require__(575);

exports.default = ($scope) => {
	ceFrontend.elementsHandler.addHandler(_baseTabs.default, {
		$element: $scope,
		showTabFn: 'slideDown',
		hideTabFn: 'slideUp'
	});
};

/***/ }),
/* 644 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class Alert extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				dismissButton: '.elementor-alert-dismiss'
			}
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings('selectors');
		return {
			$dismissButton: this.$element.find(selectors.dismissButton)
		};
	}

	bindEvents() {
		this.elements.$dismissButton.on('click', this.onDismissButtonClick.bind(this));
	}

	onDismissButtonClick() {
		this.$element.fadeOut();
	}
}

exports.default = ($scope) => {
	ceFrontend.elementsHandler.addHandler(Alert, {
		$element: $scope
	});
};

/***/ }),
/* 645 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var Scroll = __webpack_require__(398).default;

class Counter extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				counterNumber: '.elementor-counter-number'
			}
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings('selectors');
		return {
			$counterNumber: this.$element.find(selectors.counterNumber)
		};
	}

	onInit() {
		super.onInit();

		this.intersectionObserver = Scroll.scrollObserver({
			callback: event => {
				if (event.isInViewport) {
					this.intersectionObserver.unobserve(this.elements.$counterNumber[0]);
					const data = this.elements.$counterNumber.data(),
								decimalDigits = data.toValue.toString().match(/\.(.*)/);
					if (decimalDigits) {
						data.rounding = decimalDigits[1].length;
					}
					this.elements.$counterNumber.numerator(data);
				}
			}
		});
		this.intersectionObserver.observe(this.elements.$counterNumber[0]);
	}
}

exports.default = ($scope) => {
	ceFrontend.elementsHandler.addHandler(Counter, {
		$element: $scope
	});
};

/***/ }),
/* 646 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class Progress extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				progressNumber: '.elementor-progress-bar'
			}
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings('selectors');
		return {
			$progressNumber: this.$element.find(selectors.progressNumber)
		};
	}

	onInit() {
		super.onInit();

		this.intersectionObserver = new IntersectionObserver(entries => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					this.intersectionObserver.unobserve(this.elements.$progressNumber[0]);
					const $progressbar = this.elements.$progressNumber;
					$progressbar.css('width', $progressbar.data('max') + '%');
				}
			});
		}, {
			root: null,
			threshold: 0,
			rootMargin: '0px'
		});
		this.intersectionObserver.observe(this.elements.$progressNumber[0]);
	}
}

exports.default = ($scope) => {
	ceFrontend.elementsHandler.addHandler(Progress, {
		$element: $scope
	});
};

/***/ }),
/* 647 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var _baseTabs = __webpack_require__(575);

var _default = function _default($scope) {
	ceFrontend.elementsHandler.addHandler(_baseTabs.default, {
		$element: $scope,
		toggleSelf: false
	});
};

exports.default = ($scope) => {
	ceFrontend.elementsHandler.addHandler(_baseTabs.default, {
		$element: $scope,
		toggleSelf: false
	});
};

/***/ }),
/* 648 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var _baseTabs = __webpack_require__(575);

exports.default = ($scope) => {
	ceFrontend.elementsHandler.addHandler(_baseTabs.default, {
		$element: $scope,
		showTabFn: 'slideDown',
		hideTabFn: 'slideUp',
		hidePrevious: false,
		autoExpand: 'editor'
	});
};

/***/ }),
/* 649 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class VideoModule extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				imageOverlay: '.elementor-custom-embed-image-overlay',
				video: '.elementor-video',
				videoIframe: '.elementor-video-iframe'
			}
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings('selectors');
		return {
			$imageOverlay: this.$element.find(selectors.imageOverlay),
			$video: this.$element.find(selectors.video),
			$videoIframe: this.$element.find(selectors.videoIframe)
		};
	}

	getLightBox() {
		return ceFrontend.utils.lightbox;
	}

	handleVideo() {
		if (!this.getElementSettings('lightbox')) {
			this.elements.$imageOverlay.remove();
			this.playVideo();
		}
	}

	playVideo() {
		if (this.elements.$video.length) {
			this.elements.$video[0].play();
			return;
		}
		const $videoIframe = this.elements.$videoIframe;
		const lazyLoad = $videoIframe.data('lazy-load');
		if (lazyLoad) {
			$videoIframe.attr('src', lazyLoad);
		}
		let newSourceUrl = $videoIframe[0].src.replace('&autoplay=0', '');
		$videoIframe[0].src = `${newSourceUrl}&autoplay=1`;
		if ($videoIframe[0].src.includes('vimeo.com')) {
			const videoSrc = $videoIframe[0].src;
			const timeMatch = /#t=[^&]*/.exec(videoSrc); // Param '#t=' must be last in the URL
			$videoIframe[0].src = videoSrc.slice(0, timeMatch.index) + videoSrc.slice(timeMatch.index + timeMatch[0].length) + timeMatch[0];
		}
	}

	animateVideo() {
		this.getLightBox().setEntranceAnimation(this.getCurrentDeviceSetting('lightbox_content_animation'));
	}

	handleAspectRatio() {
		this.getLightBox().setVideoAspectRatio(this.getElementSettings('aspect_ratio'));
	}

	bindEvents() {
		this.elements.$imageOverlay.on('click', this.handleVideo.bind(this));
	}

	onElementChange(propertyName) {
		if (propertyName.startsWith('lightbox_content_animation')) {
			this.animateVideo();
			return;
		}
		const isLightBoxEnabled = this.getElementSettings('lightbox');
		if (propertyName === 'lightbox' && !isLightBoxEnabled) {
			this.getLightBox().getModal().hide();
			return;
		}
		if (propertyName === 'aspect_ratio' && isLightBoxEnabled) {
			this.handleAspectRatio();
		}
	}
}

exports.default = ($scope) => {
	ceFrontend.elementsHandler.addHandler(VideoModule, {
		$element: $scope
	});
};

/***/ }),
/* 650 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class ImageCarouselHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				carousel: '.elementor-carousel-wrapper',
				slideContent: '.swiper-slide'
			}
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings('selectors');
		const elements = {
			$carousel: this.$element.find(selectors.carousel)
		};
		elements.$swiperSlides = elements.$carousel.find(selectors.slideContent);
		return elements;
	}

	getSlidesCount() {
		return this.elements.$swiperSlides.length;
	}

	getSwiperSettings() {
		const elementSettings = this.getElementSettings(),
					variableWidth = elementSettings.variable_width === 'yes',
					slidesToShow = +elementSettings.slides_to_show || elementSettings.default_slides_count,
					isSingleSlide = slidesToShow === 1,
					defaultLGDevicesSlidesCount = isSingleSlide ? 1 : elementSettings.default_slides_count - 1 || 1,
					elementorBreakpoints = ceFrontend.config.breakpoints,
					spaceBetween = elementSettings[this.spaceBetweenKey] && elementSettings[this.spaceBetweenKey] || {},
					em = parseInt(this.elements.$swiperSlides.css('fontSize'));
		const autoplay = {
			delay: elementSettings.autoplay_speed,
			disableOnInteraction: elementSettings.pause_on_interaction === 'yes'
		};
		const swiperOptions = {
			grabCursor: true,
			touchThreshold: 100,
			slidesPerView: variableWidth ? 'auto' : slidesToShow,
			spaceBetween: (spaceBetween.size || 0) * (spaceBetween.unit === 'em' ? em : 1),
			centeredSlides: elementSettings.center_mode === 'yes',
			autoplay: $.extend({enabled: !this.isEdit && elementSettings.autoplay === 'yes'}, autoplay),
			loop: elementSettings.infinite === 'yes' && (variableWidth || this.slidesCount > slidesToShow),
			speed: elementSettings.speed,
			handleElementorBreakpoints: true,
			breakpoints: {}
		};
		swiperOptions.breakpoints[elementorBreakpoints.lg] = {
			slidesPerView: variableWidth ? 'auto' : +elementSettings.slides_to_show_tablet || defaultLGDevicesSlidesCount,
			slidesPerGroup: +elementSettings.slides_to_scroll_tablet || 1,
			centeredSlides: elementSettings.center_mode_tablet === 'yes',
			autoplay: $.extend({enabled: !this.isEdit && elementSettings.autoplay_tablet === 'yes'}, autoplay),
			loop: elementSettings.infinite_tablet === 'yes' && (variableWidth || this.slidesCount > (+elementSettings.slides_to_show_tablet || defaultLGDevicesSlidesCount))
		};
		swiperOptions.breakpoints[elementorBreakpoints.md] = {
			slidesPerView: variableWidth ? 'auto' : +elementSettings.slides_to_show_mobile || 1,
			slidesPerGroup: +elementSettings.slides_to_scroll_mobile || 1,
			centeredSlides: elementSettings.center_mode_mobile === 'yes',
			autoplay: $.extend({enabled: !this.isEdit && elementSettings.autoplay_mobile === 'yes'}, autoplay),
			loop: elementSettings.infinite_mobile === 'yes' && (variableWidth || this.slidesCount > (+elementSettings.slides_to_show_mobile || 1))
		};

		if (isSingleSlide) {
			swiperOptions.effect = elementSettings.effect;

			if (elementSettings.effect === 'fade') {
				swiperOptions.fadeEffect = {crossFade: true};
			}
		} else {
			swiperOptions.slidesPerGroup = +elementSettings.slides_to_scroll || 1;
		}

		if (elementSettings[this.spaceBetweenKey + '_tablet'] && typeof elementSettings[this.spaceBetweenKey + '_tablet'].size === 'number') {
			const spaceBetweenTablet = elementSettings[this.spaceBetweenKey + '_tablet'];
			swiperOptions.breakpoints[elementorBreakpoints.lg].spaceBetween = spaceBetweenTablet.size * (spaceBetweenTablet.unit === 'em' ? em : 1);
		}
		if (elementSettings[this.spaceBetweenKey + '_mobile'] && typeof elementSettings[this.spaceBetweenKey + '_mobile'].size === 'number') {
			const spaceBetweenMobile = elementSettings[this.spaceBetweenKey + '_mobile'];
			swiperOptions.breakpoints[elementorBreakpoints.md].spaceBetween = spaceBetweenMobile.size * (spaceBetweenMobile.unit === 'em' ? em : 1);
		}

		const showArrows = elementSettings.navigation === 'arrows' || elementSettings.navigation === 'both',
					showDots = elementSettings.navigation === 'dots' || elementSettings.navigation === 'both';

		if (showArrows) {
			swiperOptions.navigation = {
				prevEl: '.elementor-swiper-button-prev',
				nextEl: '.elementor-swiper-button-next'
			};
		}

		if (showDots) {
			swiperOptions.pagination = {
				el: '.swiper-pagination',
				type: 'bullets',
				clickable: true
			};
		}

		return swiperOptions;
	}

	updateSpaceBetween() {
		const params = this.swiper.params,
					spaceBetween = ceFrontend.getCurrentDeviceSetting(this.getElementSettings(), this.spaceBetweenKey) || {};

		params.spaceBetween = (spaceBetween.size || 0) * (spaceBetween.unit === 'em' ? parseInt(this.elements.$swiperSlides.css('fontSize')) : 1);

		this.swiper.update();
	}

	onInit(...args) {
		super.onInit(...args);

		const elementSettings = this.getElementSettings();

		if (!this.elements.$swiperSlides.length) {
			return;
		}
		this.slidesCount = this.elements.$swiperSlides.length;

		const widget = this.$element.data('widget_type').split('.')[0];

		this.spaceBetweenKey = {
			'image-carousel': 'image_spacing_custom',
			'product-carousel': 'product_spacing_custom',
			'trustedshops-reviews': 'reviews_spacing',
		}[widget] || 'space_between';

		this.swiper = new ceFrontend.utils.swiper(this.elements.$carousel, this.getSwiperSettings());
		this.elements.$carousel.data('swiper', this.swiper);

		if (elementSettings.pause_on_hover === 'yes') {
			this.elements.$carousel.on({
				mouseenter: () => {
					this.swiper.autoplay.stop();
				},
				mouseleave: () => {
					this.swiper.autoplay.start();
				}
			});
		}
	}

	onElementChange(propertyName) {
		if (propertyName.indexOf(this.spaceBetweenKey) === 0) {
			this.updateSpaceBetween();
		} else if (~['arrows_position', 'image_height'].indexOf(propertyName)) {
			this.swiper.update();
		}
	}

	onEditSettingsChange(propertyName) {
		if (propertyName === 'activeItemIndex' && this.swiper) {
			this.swiper.slideToLoop(this.getEditSettings('activeItemIndex') - 1);
		}
	}
}

exports.default = ($scope) => {
	ceFrontend.elementsHandler.addHandler(ImageCarouselHandler, {
		$element: $scope
	});
};

/***/ }),
/* 651 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class TextEditor extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				paragraph: 'p:first'
			},
			classes: {
				dropCap: 'elementor-drop-cap',
				dropCapLetter: 'elementor-drop-cap-letter'
			}
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings('selectors'),
					classes = this.getSettings('classes'),
					$dropCap = $('<span>', {class: classes.dropCap}),
					$dropCapLetter = $('<span>', {class: classes.dropCapLetter});
		$dropCap.append($dropCapLetter);
		return {
			$paragraph: this.$element.find(selectors.paragraph),
			$dropCap: $dropCap,
			$dropCapLetter: $dropCapLetter
		};
	}

	wrapDropCap() {
		const isDropCapEnabled = this.getElementSettings('drop_cap');

		if (!isDropCapEnabled) {
			// If there is an old drop cap inside the paragraph
			if (this.dropCapLetter) {
				this.elements.$dropCap.remove();
				this.elements.$paragraph.prepend(this.dropCapLetter);
				this.dropCapLetter = '';
			}
			return;
		}

		const $paragraph = this.elements.$paragraph;

		if (!$paragraph.length) {
			return;
		}

		const paragraphContent = $paragraph.html().replace(/&nbsp;/g, ' '),
					firstLetterMatch = paragraphContent.match(/^ *([^ ] ?)/);

		if (!firstLetterMatch) {
			return;
		}

		const firstLetter = firstLetterMatch[1],
					trimmedFirstLetter = firstLetter.trim();

		// Don't apply drop cap when the content starting with an HTML tag
		if ('<' === trimmedFirstLetter) {
			return;
		}

		this.dropCapLetter = firstLetter;
		this.elements.$dropCapLetter.text(trimmedFirstLetter);
		const restoredParagraphContent = paragraphContent.slice(firstLetter.length).replace(/^ */, function(match) {
			return new Array(match.length + 1).join('&nbsp;');
		});
		$paragraph.html(restoredParagraphContent).prepend(this.elements.$dropCap);
	}

	onInit(...args) {
		super.onInit(...args);
		this.wrapDropCap();
	}

	onElementChange(propertyName) {
		if ('drop_cap' === propertyName) {
			this.wrapDropCap();
		}
	}
}

exports.default = ($scope) => {
	ceFrontend.elementsHandler.addHandler(TextEditor, {
		$element: $scope
	});
};

/***/ }),
/* 652 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var BackgroundSlideshow = __webpack_require__(596).default;

var BackgroundVideo = __webpack_require__(653).default;

var HandlesPosition = __webpack_require__(654).default;

var StretchedSection = __webpack_require__(655).default;

var Shapes = __webpack_require__(656).default;

const addSectionHandlers = ($scope) => {
	if (ceFrontend.isEditMode() || $scope.hasClass('elementor-section-stretched')) {
		ceFrontend.elementsHandler.addHandler(StretchedSection, {
			$element: $scope
		});
	}

	if (ceFrontend.isEditMode()) {
		ceFrontend.elementsHandler.addHandler(Shapes, {
			$element: $scope
		});
		ceFrontend.elementsHandler.addHandler(HandlesPosition, {
			$element: $scope
		});
	}

	ceFrontend.elementsHandler.addHandler(BackgroundVideo, {
		$element: $scope
	});
	ceFrontend.elementsHandler.addHandler(BackgroundSlideshow, {
		$element: $scope
	});
};

exports.default = addSectionHandlers;

/***/ }),
/* 653 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class BackgroundVideo extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				backgroundVideoContainer: '.elementor-background-video-container',
				backgroundVideoEmbed: '.elementor-background-video-embed',
				backgroundVideoHosted: '.elementor-background-video-hosted'
			}
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings('selectors');
		const elements = {
			$backgroundVideoContainer: this.$element.find(selectors.backgroundVideoContainer)
		};
		elements.$backgroundVideoEmbed = elements.$backgroundVideoContainer.children(selectors.backgroundVideoEmbed);
		elements.$backgroundVideoHosted = elements.$backgroundVideoContainer.children(selectors.backgroundVideoHosted);
		return elements;
	}

	calcVideosSize($video) {
		let aspectRatioSetting = '16:9';

		if ('vimeo' === this.videoType) {
			aspectRatioSetting = $video[0].width + ':' + $video[0].height;
		}

		const containerWidth = this.elements.$backgroundVideoContainer.outerWidth();
		const containerHeight = this.elements.$backgroundVideoContainer.outerHeight();
		const aspectRatioArray = aspectRatioSetting.split(':');
		const aspectRatio = aspectRatioArray[0] / aspectRatioArray[1];
		const ratioWidth = containerWidth / aspectRatio;
		const ratioHeight = containerHeight * aspectRatio;
		const isWidthFixed = containerWidth / containerHeight > aspectRatio;
		return {
			width: isWidthFixed ? containerWidth : ratioHeight,
			height: isWidthFixed ? ratioWidth : containerHeight
		};
	}

	changeVideoSize() {
		if (!('hosted' === this.videoType) && !this.player) {
			return;
		}

		let $video;

		if ('youtube' === this.videoType) {
			$video = $(this.player.getIframe());
		} else if ('vimeo' === this.videoType) {
			$video = $(this.player.element);
		} else if ('hosted' === this.videoType) {
			$video = this.elements.$backgroundVideoHosted;
		}

		if (!$video) {
			return;
		}

		const size = this.calcVideosSize($video);
		$video.width(size.width).height(size.height);
	}

	startVideoLoop(firstTime) {
		// If the section has been removed
		if (!this.player.getIframe().contentWindow) {
			return;
		}

		const elementSettings = this.getElementSettings();
		const startPoint = elementSettings.background_video_start || 0;
		const endPoint = elementSettings.background_video_end;

		if (elementSettings.background_play_once && !firstTime) {
			this.player.stopVideo();
			return;
		}

		this.player.seekTo(startPoint);

		if (endPoint) {
			const durationToEnd = endPoint - startPoint + 1;
			setTimeout(() => {
				this.startVideoLoop(false);
			}, durationToEnd * 1000);
		}
	}

	prepareVimeoVideo(Vimeo, videoId) {
		const elementSettings = this.getElementSettings();
		const startTime = elementSettings.background_video_start ? elementSettings.background_video_start : 0;
		const videoSize = this.elements.$backgroundVideoContainer.outerWidth();
		const vimeoOptions = {
			id: videoId,
			width: videoSize.width,
			autoplay: true,
			loop: !elementSettings.background_play_once,
			transparent: false,
			background: true,
			muted: true
		};
		this.player = new Vimeo.Player(this.elements.$backgroundVideoContainer, vimeoOptions);

		// Handle user-defined start/end times
		this.handleVimeoStartEndTimes(elementSettings);

		this.player.ready().then(() => {
			$(this.player.element).addClass('elementor-background-video-embed');
			this.changeVideoSize();
		});
	}

	handleVimeoStartEndTimes(elementSettings) {
		// If a start time is defined, set the start time
		if (elementSettings.background_video_start) {
			this.player.on('play', (data) => {
				if (0 === data.seconds) {
					this.player.setCurrentTime(elementSettings.background_video_start);
				}
			});
		}

		this.player.on('timeupdate', (data) => {
			// If an end time is defined, handle ending the video
			if (elementSettings.background_video_end && elementSettings.background_video_end < data.seconds) {
				if (elementSettings.background_play_once) {
					// Stop at user-defined end time if not loop
					this.player.pause();
				} else {
					// Go to start time if loop
					this.player.setCurrentTime(elementSettings.background_video_start);
				}
			}

			// If start time is defined but an end time is not, go to user-defined start time at video end.
			// Vimeo JS API has an 'ended' event, but it never fires when infinite loop is defined, so we
			// get the video duration (returns a promise) then use duration-0.5s as end time
			this.player.getDuration().then((duration) => {
				if (elementSettings.background_video_start && !elementSettings.background_video_end && data.seconds > duration - 0.5) {
					this.player.setCurrentTime(elementSettings.background_video_start);
				}
			});
		});
	}

	prepareYTVideo(YT, videoID) {
		const $backgroundVideoContainer = this.elements.$backgroundVideoContainer;
		const elementSettings = this.getElementSettings();
		let startStateCode = YT.PlayerState.PLAYING;

		// Since version 67, Chrome doesn't fire the `PLAYING` state at start time
		if (window.chrome) {
			startStateCode = YT.PlayerState.UNSTARTED;
		}

		$backgroundVideoContainer.addClass('elementor-loading elementor-invisible');
		this.player = new YT.Player(this.elements.$backgroundVideoEmbed[0], {
			videoId: videoID,
			events: {
				onReady: () => {
					this.player.mute();
					this.changeVideoSize();
					this.startVideoLoop(true);
					this.player.playVideo();
				},
				onStateChange: (event) => {
					switch (event.data) {
						case startStateCode:
							$backgroundVideoContainer.removeClass('elementor-invisible elementor-loading');
							break;
						case YT.PlayerState.ENDED:
							this.player.seekTo(elementSettings.background_video_start || 0);
							if (elementSettings.background_play_once) {
								this.player.destroy();
							}
					}
				}
			},
			playerVars: {
				controls: 0,
				rel: 0,
				playsinline: 1
			}
		});
	}

	activate() {
		let videoLink = this.getElementSettings('background_video_link');
		const playOnce = this.getElementSettings('background_play_once');

		if (-1 !== videoLink.indexOf('vimeo.com')) {
			this.videoType = 'vimeo';
			this.apiProvider = ceFrontend.utils.vimeo;
		} else if (videoLink.match(/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com)/)) {
			this.videoType = 'youtube';
			this.apiProvider = ceFrontend.utils.youtube;
		}

		if (this.apiProvider) {
			let videoID = this.apiProvider.getVideoIDFromURL(videoLink);
			this.apiProvider.onApiReady((apiObject) => {
				if ('youtube' === this.videoType) {
					this.prepareYTVideo(apiObject, videoID);
				}
				if ('vimeo' === this.videoType) {
					this.prepareVimeoVideo(apiObject, videoID);
				}
			});
		} else {
			this.videoType = 'hosted';
			const startTime = this.getElementSettings('background_video_start');
			const endTime = this.getElementSettings('background_video_end');

			if (startTime || endTime) {
				videoLink += '#t=' + (startTime || 0) + (endTime ? ',' + endTime : '');
			}

			this.elements.$backgroundVideoHosted.attr('src', videoLink).one('canplay', this.changeVideoSize.bind(this));

			if (playOnce) {
				this.elements.$backgroundVideoHosted.on('ended', () => {
					this.elements.$backgroundVideoHosted.hide();
				});
			}
		}

		ceFrontend.elements.$window.on('resize', this.changeVideoSize);
	}

	deactivate() {
		if (('youtube' === this.videoType && this.player.getIframe()) || 'vimeo' === this.videoType) {
			this.player.destroy();
		} else {
			this.elements.$backgroundVideoHosted.removeAttr('src').off('ended');
		}

		ceFrontend.elements.$window.off('resize', this.changeVideoSize);
	}

	run() {
		const elementSettings = this.getElementSettings();

		if (!elementSettings.background_play_on_mobile && 'mobile' === ceFrontend.getCurrentDeviceMode()) {
			return;
		}

		if ('video' === elementSettings.background_background && elementSettings.background_video_link) {
			this.activate();
		} else {
			this.deactivate();
		}
	}

	onInit(...args) {
		super.onInit(...args);
		this.changeVideoSize = this.changeVideoSize.bind(this);
		this.run();
	}

	onElementChange(propertyName) {
		if ('background_background' === propertyName) {
			this.run();
		}
	}
}

exports.default = BackgroundVideo;

/***/ }),
/* 654 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class HandlesPosition extends elementorModules.frontend.handlers.Base {
	isFirstSection() {
		return this.$element.is('.elementor-edit-mode .elementor-top-section:first');
	}

	isOverflowHidden() {
		return 'hidden' === this.$element.css('overflow');
	}

	getOffset() {
		if ('body' === elementor.config.document.container) {
			return this.$element.offset().top;
		}

		const $container = $(elementor.config.document.container);
		return this.$element.offset().top - $container.offset().top;
	}

	setHandlesPosition() {
		const document = elementor.documents.getCurrent();

		if (!document || !document.container.isEditable()) {
			return;
		}

		const isOverflowHidden = this.isOverflowHidden();

		if (!isOverflowHidden && !this.isFirstSection()) {
			return;
		}

		const offset = isOverflowHidden ? 0 : this.getOffset(),
					$handlesElement = this.$element.find('> .elementor-element-overlay > .elementor-editor-section-settings'),
					insideHandleClass = 'elementor-section--handles-inside';

		if (offset < 25 || this.$element.closest('[data-elementor-type^="product-"]').length) {
			this.$element.addClass(insideHandleClass);

			if (offset < -5) {
				$handlesElement.css('top', -offset);
			} else {
				$handlesElement.css('top', '');
			}
		} else {
			this.$element.removeClass(insideHandleClass);
		}
	}

	onInit() {
		this.setHandlesPosition();
		this.$element.on('mouseenter', this.setHandlesPosition.bind(this));
	}
}

exports.default = HandlesPosition;

/***/ }),
/* 655 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class StretchedSection extends elementorModules.frontend.handlers.Base {
	bindEvents() {
		const handlerID = this.getUniqueHandlerID();
		ceFrontend.addListenerOnce(handlerID, 'resize', this.stretch);
		ceFrontend.addListenerOnce(handlerID, 'sticky:stick', this.stretch, this.$element);
		ceFrontend.addListenerOnce(handlerID, 'sticky:unstick', this.stretch, this.$element);
	}

	unbindEvents() {
		ceFrontend.removeListeners(this.getUniqueHandlerID(), 'resize', this.stretch);
	}

	initStretch() {
		this.stretch = this.stretch.bind(this);
		this.stretchElement = new elementorModules.frontend.tools.StretchElement({
			element: this.$element,
			selectors: {
				container: this.getStretchContainer()
			}
		});
	}

	getStretchContainer() {
		return ceFrontend.getGeneralSettings('elementor_stretched_section_container') || document.body;
	}

	stretch() {
		if (!this.getElementSettings('stretch_section')) {
			return;
		}

		this.stretchElement.stretch();
	}

	onInit(...args) {
		this.initStretch();
		super.onInit(...args);
		this.stretch();
	}

	onElementChange(propertyName) {
		if ('stretch_section' === propertyName) {
			if (this.getElementSettings('stretch_section')) {
				this.stretch();
			} else {
				this.stretchElement.reset();
			}
		}
	}

	onGeneralSettingsChange(changed) {
		if ('elementor_stretched_section_container' in changed) {
			this.stretchElement.setSettings('selectors.container', this.getStretchContainer());
			this.stretch();
		}
	}
}

exports.default = StretchedSection;

/***/ }),
/* 656 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

class Shapes extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '> .elementor-shape-%s'
			},
			svgURL: ceFrontend.config.urls.assets + 'img/shapes/'
		};
	}

	getDefaultElements() {
		const elements = {},
					selectors = this.getSettings('selectors');
		elements.$topContainer = this.$element.find(selectors.container.replace('%s', 'top'));
		elements.$bottomContainer = this.$element.find(selectors.container.replace('%s', 'bottom'));
		return elements;
	}

	getSvgURL(shapeType, fileName) {
		let svgURL = this.getSettings('svgURL') + fileName + '.svg';

		if (elementor.config.additional_shapes && shapeType in elementor.config.additional_shapes) {
			svgURL = elementor.config.additional_shapes[shapeType];

			if (-1 < fileName.indexOf('-negative')) {
				svgURL = svgURL.replace('.svg', '-negative.svg');
			}
		}

		return svgURL;
	}

	buildSVG(side) {
		const baseSettingKey = 'shape_divider_' + side,
					shapeType = this.getElementSettings(baseSettingKey),
					$svgContainer = this.elements['$' + side + 'Container'];
		$svgContainer.attr('data-shape', shapeType);

		if (!shapeType) {
			$svgContainer.empty(); // Shape-divider set to 'none'
			return;
		}

		let fileName = shapeType;

		if (this.getElementSettings(baseSettingKey + '_negative')) {
			fileName += '-negative';
		}

		const svgURL = this.getSvgURL(shapeType, fileName);
		$.get(svgURL, (data) => {
			$svgContainer.empty().append(data.childNodes[0]);
		});
		this.setNegative(side);
	}

	setNegative(side) {
		this.elements['$' + side + 'Container'].attr('data-negative', !!this.getElementSettings('shape_divider_' + side + '_negative'));
	}

	onInit(...args) {
		super.onInit(...args);

		['top', 'bottom'].forEach((side) => {
			if (this.getElementSettings('shape_divider_' + side)) {
				this.buildSVG(side);
			}
		});
	}

	onElementChange(propertyName) {
		const shapeChange = propertyName.match(/^shape_divider_(top|bottom)$/);

		if (shapeChange) {
			this.buildSVG(shapeChange[1]);
			return;
		}

		const negativeChange = propertyName.match(/^shape_divider_(top|bottom)_negative$/);

		if (negativeChange) {
			this.buildSVG(negativeChange[1]);
			this.setNegative(negativeChange[1]);
		}
	}
}

exports.default = Shapes;

/***/ }),
/* 657 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var BackgroundSlideshow = __webpack_require__(596).default;

exports.default = ($scope) => {
	ceFrontend.elementsHandler.addHandler(BackgroundSlideshow, {
		$element: $scope
	});
};

/***/ }),
/* 658 */
/***/ (function(module, exports, __webpack_require__) {

exports.__esModule = true;

var Scroll = __webpack_require__(398).default;

class GlobalHandler extends elementorModules.frontend.handlers.Base {
	getWidgetType() {
		return 'global';
	}

	animate() {
		const $element = this.$element,
			animation = this.getAnimation();

		if ('none' === animation) {
			$element.removeClass('elementor-invisible');
			return;
		}

		const elementSettings = this.getElementSettings(),
			animationDelay = elementSettings._animation_delay || elementSettings.animation_delay || 0;

		$element.addClass('elementor-invisible').removeClass(animation);

		if (this.currentAnimation) {
			$element.removeClass(this.currentAnimation);
		}

		this.currentAnimation = animation;
		setTimeout(() => {
			$element.removeClass('elementor-invisible').addClass('animated ' + animation);
		}, animationDelay);
	}

	getAnimation() {
		return this.getCurrentDeviceSetting('animation') || this.getCurrentDeviceSetting('_animation');
	}

	onInit(...args) {
		super.onInit(...args);

		if (this.getAnimation()) {
			const observer = Scroll.scrollObserver({
				callback: (event) => {
					if (event.isInViewport) {
						this.animate();
						observer.unobserve(this.$element[0]);
					}
				}
			});
			observer.observe(this.$element[0]);
		}
	}

	onElementChange(propertyName) {
		if (/^_?animation/.test(propertyName)) {
			this.animate();
		}
	}
}

exports.default = ($scope) => {
	ceFrontend.elementsHandler.addHandler(GlobalHandler, {
		$element: $scope
	});
};

/***/ }),
/* 659 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = elementorModules.ViewModule.extend({
	getDefaultSettings: function () {
		return {
			scrollDuration: 500,
			selectors: {
				links: 'a[href*="#"]',
				targets: '.elementor-element, .elementor-menu-anchor',
				scrollable: 'html, body'
			}
		};
	},
	getDefaultElements: function () {
		var selectors = this.getSettings('selectors');
		return {
			$scrollable: $(selectors.scrollable)
		};
	},
	bindEvents: function () {
		ceFrontend.elements.$document.on('click', this.getSettings('selectors.links'), this.handleAnchorLinks);
	},
	handleAnchorLinks: function (event) {
		var clickedLink = event.currentTarget,
				isSamePathname = location.pathname === clickedLink.pathname,
				isSameHostname = location.hostname === clickedLink.hostname,
				$anchor;

		if (!isSameHostname || !isSamePathname || clickedLink.hash.length < 2) {
			return;
		}

		try {
			$anchor = $(clickedLink.hash).filter(this.getSettings('selectors.targets'));
		} catch (e) {
			return;
		}

		if (!$anchor.length) {
			return;
		}

		var scrollTop = $anchor.offset().top,
				// $wpAdminBar = ceFrontend.elements.$wpAdminBar,
				$activeStickies = $('.elementor-section.elementor-sticky--active:visible'),
				maxStickyHeight = 0;

		// if ($wpAdminBar.length > 0) {
		// 	scrollTop -= $wpAdminBar.height();
		// }

		if ($activeStickies.length > 0) {
			maxStickyHeight = Math.max.apply(null, $activeStickies.map(function () {
				return $(this).outerHeight();
			}).get());
			scrollTop -= maxStickyHeight;
		}

		event.preventDefault();
		scrollTop = ceFrontend.hooks.applyFilters('frontend/handlers/menu_anchor/scroll_top_distance', scrollTop);
		this.elements.$scrollable.animate({
			scrollTop: scrollTop
		}, this.getSettings('scrollDuration'), 'linear');
	},
	onInit: function () {
		elementorModules.ViewModule.prototype.onInit.apply(this, arguments);
		this.bindEvents();
	}
});

/***/ }),
/* 660 */
/***/ (function(module, exports, __webpack_require__) {

var Screenfull = __webpack_require__(661);

module.exports = elementorModules.ViewModule.extend({
	oldAspectRatio: null,
	oldAnimation: null,
	swiper: null,
	player: null,
	getDefaultSettings: function () {
		return {
			classes: {
				aspectRatio: 'elementor-aspect-ratio-%s',
				item: 'elementor-lightbox-item',
				image: 'elementor-lightbox-image',
				videoContainer: 'elementor-video-container',
				videoWrapper: 'elementor-fit-aspect-ratio',
				playButton: 'elementor-custom-embed-play',
				playButtonIcon: 'fa',
				playing: 'elementor-playing',
				hidden: 'elementor-hidden',
				invisible: 'elementor-invisible',
				preventClose: 'elementor-lightbox-prevent-close',
				slideshow: {
					container: 'swiper',
					slidesWrapper: 'swiper-wrapper',
					prevButton: 'elementor-swiper-button elementor-swiper-button-prev',
					nextButton: 'elementor-swiper-button elementor-swiper-button-next',
					prevButtonIcon: 'ceicon-chevron-left',
					nextButtonIcon: 'ceicon-chevron-right',
					slide: 'swiper-slide',
					header: 'elementor-slideshow__header',
					footer: 'elementor-slideshow__footer',
					title: 'elementor-slideshow__title',
					description: 'elementor-slideshow__description',
					counter: 'elementor-slideshow__counter',
					iconExpand: 'ceicon-frame-expand',
					iconShrink: 'ceicon-frame-minimize',
					iconZoomIn: 'ceicon-zoom-in-bold',
					iconZoomOut: 'ceicon-zoom-out-bold',
					hideUiVisibility: 'elementor-slideshow--ui-hidden',
					fullscreenMode: 'elementor-slideshow--fullscreen-mode',
					zoomMode: 'elementor-slideshow--zoom-mode'
				}
			},
			selectors: {
				links: 'a, [data-elementor-lightbox]',
				slideshow: {
					activeSlide: '.swiper-slide-active',
					prevSlide: '.swiper-slide-prev',
					nextSlide: '.swiper-slide-next'
				}
			},
			modalOptions: {
				id: 'elementor-lightbox',
				entranceAnimation: 'zoomIn',
				videoAspectRatio: 169,
				position: {
					enable: false
				}
			}
		};
	},
	getModal: function () {
		if (!module.exports.modal) {
			this.initModal();
		}

		return module.exports.modal;
	},
	initModal: function () {
		var modal = module.exports.modal = ceFrontend.getDialogsManager().createWidget('lightbox', {
			className: 'elementor-lightbox',
			closeButton: true,
			closeButtonClass: 'ceicon-close',
			selectors: {
				preventClose: '.' + this.getSettings('classes.preventClose')
			},
			hide: {
				onClick: true
			}
		});
		modal.on('hide', function () {
			modal.setMessage('');
		});
	},
	showModal: function (options) {
		var self = this,
				defaultOptions = self.getDefaultSettings().modalOptions;
		self.id = options.id;
		self.setSettings('modalOptions', $.extend(defaultOptions, options.modalOptions));
		var modal = self.getModal();
		modal.setID(self.getSettings('modalOptions.id'));

		modal.onShow = function () {
			DialogsManager.getWidgetType('lightbox').prototype.onShow.apply(modal, arguments);
			self.setEntranceAnimation();
		};

		modal.onHide = function () {
			DialogsManager.getWidgetType('lightbox').prototype.onHide.apply(modal, arguments);
			modal.getElements('message').removeClass('animated');

			if (Screenfull.isFullscreen) {
				self.deactivateFullscreen();
			}
		};

		switch (options.type) {
			case 'video':
				self.setVideoContent(options);
				break;

			case 'image':
				var slides = [{
					image: options.url,
					index: 0,
					title: options.title,
					description: options.description
				}];
				options.slideshow = {
					slides: slides,
					swiper: {
						loop: false,
						pagination: false
					}
				};

			case 'slideshow':
				self.setSlideshowContent(options.slideshow);
				break;

			case 'embed':
				var width = options.width || '90%';
				var height = options.height || '90%';
				self.setHTMLContent(`<div class="swiper-lazy-preloader"></div><iframe src="${options.url}" frameBorder="0" class="ce-lightbox-embed" style="width:${width}; height:${height};"></iframe>`);
				break;

			default:
				self.setHTMLContent(options.html);
		}

		modal.show();
	},
	setHTMLContent: function (html) {
		this.getModal().setMessage(html);
	},
	setVideoContent: function (options) {
		var classes = this.getSettings('classes'),
				$videoContainer = $('<div>', {
			class: "".concat(classes.videoContainer, " ").concat(classes.preventClose)
		}),
				$videoWrapper = $('<div>', {
			class: classes.videoWrapper
		}),
				modal = this.getModal();
		var $videoElement;

		if ('hosted' === options.videoType) {
			var videoParams = $.extend({
				src: options.url,
				autoplay: ''
			}, options.videoParams);
			$videoElement = $('<video>', videoParams);
		} else {
			var videoURL = options.url.replace('&autoplay=0', '') + '&autoplay=1';
			$videoElement = $('<iframe>', {
				src: videoURL,
				allowfullscreen: 1
			});
		}

		$videoContainer.append($videoWrapper);
		$videoWrapper.append($videoElement);
		modal.setMessage($videoContainer);
		this.setVideoAspectRatio();
		var onHideMethod = modal.onHide;

		modal.onHide = function () {
			onHideMethod();
			modal.getElements('message').removeClass('elementor-fit-aspect-ratio');
		};
	},
	getSlideshowHeader: function () {
		var showCounter = 'yes' === ceFrontend.getGeneralSettings('elementor_lightbox_enable_counter'),
				showFullscreen = 'yes' === ceFrontend.getGeneralSettings('elementor_lightbox_enable_fullscreen'),
				showZoom = 'yes' === ceFrontend.getGeneralSettings('elementor_lightbox_enable_zoom'),
				classes = this.getSettings('classes'),
				slideshowClasses = classes.slideshow,
				elements = this.elements;

		if (!(showCounter || showFullscreen || showZoom)) {
			return;
		}

		elements.$header = $('<header>', {
			class: slideshowClasses.header + ' ' + classes.preventClose
		});

		if (showCounter) {
			elements.$counter = $('<span>', {
				class: slideshowClasses.counter
			});
			elements.$header.append(elements.$counter);
		}

		if (showFullscreen) {
			elements.$iconExpand = $('<i>', {
				class: slideshowClasses.iconExpand
			}).append($('<span>'), $('<span>'));
			elements.$iconExpand.on('click', this.toggleFullscreen);
			elements.$header.append(elements.$iconExpand);
		}

		if (showZoom) {
			elements.$iconZoom = $('<i>', {
				class: slideshowClasses.iconZoomIn
			});
			elements.$iconZoom.on('click', this.toggleZoomMode);
			elements.$header.append(elements.$iconZoom);
		}

		return elements.$header;
	},
	toggleFullscreen: function () {
		if (Screenfull.isFullscreen) {
			this.deactivateFullscreen();
		} else if (Screenfull.isEnabled) {
			this.activateFullscreen();
		}
	},
	toggleZoomMode: function () {
		if (1 !== this.swiper.zoom.scale) {
			this.deactivateZoom();
		} else {
			this.activateZoom();
		}
	},
	activateFullscreen: function () {
		var classes = this.getSettings('classes');

		Screenfull.request(this.elements.$container.parents('.dialog-widget')[0]);

		this.elements.$iconExpand.removeClass(classes.slideshow.iconExpand).addClass(classes.slideshow.iconShrink);
		this.elements.$container.addClass(classes.slideshow.fullscreenMode);
	},
	deactivateFullscreen: function () {
		var classes = this.getSettings('classes');

		Screenfull.exit();

		this.elements.$iconExpand.removeClass(classes.slideshow.iconShrink).addClass(classes.slideshow.iconExpand);
		this.elements.$container.removeClass(classes.slideshow.fullscreenMode);
	},
	activateZoom: function () {
		var swiper = this.swiper,
				elements = this.elements,
				classes = this.getSettings('classes');
		swiper.zoom.in();
		swiper.allowSlideNext = false;
		swiper.allowSlidePrev = false;
		swiper.allowTouchMove = false;
		elements.$container.addClass(classes.slideshow.zoomMode);
		elements.$iconZoom.removeClass(classes.slideshow.iconZoomIn).addClass(classes.slideshow.iconZoomOut);
	},
	deactivateZoom: function () {
		var swiper = this.swiper,
				elements = this.elements,
				classes = this.getSettings('classes');
		swiper.zoom.out();
		swiper.allowSlideNext = true;
		swiper.allowSlidePrev = true;
		swiper.allowTouchMove = true;
		elements.$container.removeClass(classes.slideshow.zoomMode);
		elements.$iconZoom.removeClass(classes.slideshow.iconZoomOut).addClass(classes.slideshow.iconZoomIn);
	},
	getSlideshowFooter: function () {
		var classes = this.getSettings('classes'),
				$footer = $('<footer>', {class: classes.slideshow.footer + ' ' + classes.preventClose}),
				$title = $('<div>', {class: classes.slideshow.title}),
				$description = $('<div>', {class: classes.slideshow.description});
		$footer.append($title, $description);
		return $footer;
	},
	setSlideshowContent: function (options) {
		var _this2 = this;

		var isSingleSlide = 1 === options.slides.length,
				hasTitle = '' !== ceFrontend.getGeneralSettings('elementor_lightbox_title_src'),
				hasDescription = '' !== ceFrontend.getGeneralSettings('elementor_lightbox_description_src'),
				showFooter = hasTitle || hasDescription,
				classes = this.getSettings('classes'),
				slideshowClasses = classes.slideshow,
				$container = $('<div>', {
			class: slideshowClasses.container
		}),
				$slidesWrapper = $('<div>', {
			class: slideshowClasses.slidesWrapper
		});
		var $prevButton, $nextButton;
		options.slides.forEach(function (slide) {
			var slideClass = slideshowClasses.slide + ' ' + classes.item;

			if (slide.video) {
				slideClass += ' ' + classes.video;
			}

			var $slide = $('<div>', {
				class: slideClass
			});

			if (slide.video) {
				$slide.attr('data-elementor-slideshow-video', slide.video);
				var $playIcon = $('<div>', {class: classes.playButton}).html($('<i>', {class: classes.playButtonIcon}));
				$slide.append($playIcon);
			} else {
				var $zoomContainer = $('<div>', {class: 'swiper-zoom-container'}),
						$slideImage = $('<img>', {
					class: classes.image + ' ' + classes.preventClose,
					src: slide.image,
					'data-title': slide.title,
					'data-description': slide.description
				});
				$zoomContainer.append($slideImage);
				$slide.append($zoomContainer);
			}

			$slidesWrapper.append($slide);
		});
		this.elements.$container = $container;
		this.elements.$header = this.getSlideshowHeader();
		$container.prepend(this.elements.$header).append($slidesWrapper);

		if (!isSingleSlide) {
			$prevButton = $('<div>', {
				class: slideshowClasses.prevButton + ' ' + classes.preventClose
			}).html($('<i>', {
				class: slideshowClasses.prevButtonIcon
			}));
			$nextButton = $('<div>', {
				class: slideshowClasses.nextButton + ' ' + classes.preventClose
			}).html($('<i>', {
				class: slideshowClasses.nextButtonIcon
			}));
			$container.append($prevButton, $nextButton);
		}

		if (showFooter) {
			this.elements.$footer = this.getSlideshowFooter();
			$container.append(this.elements.$footer);
		}

		this.setSettings('hideUiTimeout', '');
		$container.on('click mousemove keypress', function () {
			clearTimeout(_this2.getSettings('hideUiTimeout'));
			$container.removeClass(slideshowClasses.hideUiVisibility);

			_this2.setSettings('hideUiTimeout', setTimeout(function () {
				$container.addClass(slideshowClasses.hideUiVisibility);
			}, 3500));
		});
		var modal = this.getModal();
		modal.setMessage($container);
		var onShowMethod = modal.onShow;

		modal.onShow = function () {
			onShowMethod();
			var swiperOptions = {
				pagination: {
					el: '.' + slideshowClasses.counter,
					type: 'fraction'
				},
				on: {
					slideChangeTransitionEnd: _this2.onSlideChange
				},
				zoom: true,
				spaceBetween: 100,
				grabCursor: true,
				runCallbacksOnInit: false,
				loop: true,
				keyboard: true,
				handleElementorBreakpoints: true
			};

			if (!isSingleSlide) {
				swiperOptions.navigation = {
					prevEl: $prevButton[0],
					nextEl: $nextButton[0]
				};
			}

			if (options.swiper) {
				$.extend(swiperOptions, options.swiper);
			}

			_this2.swiper = new ceFrontend.utils.swiper($container, swiperOptions); // Expose the swiper instance in the frontend

			$container.data('swiper', _this2.swiper);

			_this2.setVideoAspectRatio();

			_this2.playSlideVideo();

			if (showFooter) {
				_this2.updateFooterText();
			}
		};
	},
	setVideoAspectRatio: function (aspectRatio) {
		aspectRatio = aspectRatio || this.getSettings('modalOptions.videoAspectRatio');
		var $widgetContent = this.getModal().getElements('widgetContent'),
				oldAspectRatio = this.oldAspectRatio,
				aspectRatioClass = this.getSettings('classes.aspectRatio');
		this.oldAspectRatio = aspectRatio;

		if (oldAspectRatio) {
			$widgetContent.removeClass(aspectRatioClass.replace('%s', oldAspectRatio));
		}

		if (aspectRatio) {
			$widgetContent.addClass(aspectRatioClass.replace('%s', aspectRatio));
		}
	},
	getSlide: function (slideState) {
		return $(this.swiper.slides).filter(this.getSettings('selectors.slideshow.' + slideState + 'Slide'));
	},
	updateFooterText: function () {
		if (!this.elements.$footer) {
			return;
		}

		var classes = this.getSettings('classes'),
				$activeSlide = this.getSlide('active'),
				$image = $activeSlide.find('.elementor-lightbox-image'),
				titleText = $image.data('title'),
				descriptionText = $image.data('description'),
				$title = this.elements.$footer.find('.' + classes.slideshow.title),
				$description = this.elements.$footer.find('.' + classes.slideshow.description);
		$title.text(titleText || '');
		$description.text(descriptionText || '');
	},
	playSlideVideo: function () {
		var _this3 = this;

		var $activeSlide = this.getSlide('active'),
				videoURL = $activeSlide.data('elementor-slideshow-video');

		if (!videoURL) {
			return;
		}

		var classes = this.getSettings('classes'),
				$videoContainer = $('<div>', {class: classes.videoContainer + ' ' + classes.invisible}),
				$videoWrapper = $('<div>', {class: classes.videoWrapper}),
				$playIcon = $activeSlide.children('.' + classes.playButton);
		var videoType, apiProvider;
		$videoContainer.append($videoWrapper);
		$activeSlide.append($videoContainer);

		if (-1 !== videoURL.indexOf('vimeo.com')) {
			videoType = 'vimeo';
			apiProvider = ceFrontend.utils.vimeo;
		} else if (videoURL.match(/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com)/)) {
			videoType = 'youtube';
			apiProvider = ceFrontend.utils.youtube;
		}

		var videoID = apiProvider.getVideoIDFromURL(videoURL);
		apiProvider.onApiReady(function (apiObject) {
			if ('youtube' === videoType) {
				_this3.prepareYTVideo(apiObject, videoID, $videoContainer, $videoWrapper, $playIcon);
			} else if ('vimeo' === videoType) {
				_this3.prepareVimeoVideo(apiObject, videoID, $videoContainer, $videoWrapper, $playIcon);
			}
		});
		$playIcon.addClass(classes.playing).removeClass(classes.hidden);
	},
	prepareYTVideo: function (YT, videoID, $videoContainer, $videoWrapper, $playIcon) {
		var _this4 = this;

		var classes = this.getSettings('classes'),
				$videoPlaceholderElement = $('<div>');
		var startStateCode = YT.PlayerState.PLAYING;
		$videoWrapper.append($videoPlaceholderElement); // Since version 67, Chrome doesn't fire the `PLAYING` state at start time

		if (window.chrome) {
			startStateCode = YT.PlayerState.UNSTARTED;
		}

		$videoContainer.addClass('elementor-loading' + ' ' + classes.invisible);
		this.player = new YT.Player($videoPlaceholderElement[0], {
			videoId: videoID,
			events: {
				onReady: function () {
					$playIcon.addClass(classes.hidden);
					$videoContainer.removeClass(classes.invisible);

					_this4.player.playVideo();
				},
				onStateChange: function (event) {
					if (event.data === startStateCode) {
						$videoContainer.removeClass('elementor-loading' + ' ' + classes.invisible);
					}
				}
			},
			playerVars: {
				controls: 0,
				rel: 0
			}
		});
	},
	prepareVimeoVideo: function (Vimeo, videoId, $videoContainer, $videoWrapper, $playIcon) {
		var classes = this.getSettings('classes'),
				vimeoOptions = {
			id: videoId,
			autoplay: true,
			transparent: false,
			playsinline: false
		};
		this.player = new Vimeo.Player($videoWrapper, vimeoOptions);
		this.player.ready().then(function () {
			$playIcon.addClass(classes.hidden);
			$videoContainer.removeClass(classes.invisible);
		});
	},
	setEntranceAnimation: function (animation) {
		animation = animation || ceFrontend.getCurrentDeviceSetting(this.getSettings('modalOptions'), 'entranceAnimation');
		var $widgetMessage = this.getModal().getElements('message');

		if (this.oldAnimation) {
			$widgetMessage.removeClass(this.oldAnimation);
		}

		this.oldAnimation = animation;

		if (animation) {
			$widgetMessage.addClass('animated ' + animation);
		}
	},
	isLightboxLink: function (element) {
		if ('A' === element.tagName.toUpperCase() && (element.hasAttribute('download') || !/^[^?]+\.(png|jpe?g|gif|svg|webp|avif)(\?.*)?$/i.test(element.href))) {
			return false;
		}

		var generalOpenInLightbox = parseInt(ceFrontend.getGeneralSettings('elementor_global_image_lightbox')),
				currentLinkOpenInLightbox = element.dataset.elementorOpenLightbox;
		return 'yes' === currentLinkOpenInLightbox || generalOpenInLightbox && 'no' !== currentLinkOpenInLightbox;
	},
	getLightBoxImageAttribute: function (link, type) {
		var src = ceFrontend.getGeneralSettings('elementor_lightbox_' + type + '_src');

		switch (src) {
			case 'title':
			case 'alt':
				return $(link).find('img').prop(src) || '';
			case 'caption':
				return $(link).closest('figure').find('figcaption').text() || (
					$(link).closest('[class*="widget-product"]').length ? $(link).find('img').prop('alt') : ''
				);
			case 'description':
				return $(link).closest('figure').find('[aria-description]').attr('aria-description');
		}
		return '';
	},
	openSlideshow: function (slideshowID, initialSlideURL) {
		var $allSlideshowLinks = $(this.getSettings('selectors.links')).filter(function (index, element) {
			var $element = $(element);
			return slideshowID === element.dataset.elementorLightboxSlideshow && !$element.parent('.swiper-slide-duplicate, figcaption').length;
		});
		var self = this,
				slides = [],
				initialSlideIndex = 0;
		$allSlideshowLinks.each(function () {
			var slideVideo = this.dataset.elementorLightboxVideo;
			var slideIndex = this.dataset.elementorLightboxIndex;

			if (undefined === slideIndex) {
				slideIndex = $allSlideshowLinks.index(this);
			}

			if (initialSlideURL === this.href || slideVideo && initialSlideURL === slideVideo) {
				initialSlideIndex = slideIndex;
			}

			var slideData = {
				image: this.href,
				index: slideIndex,
				title: self.getLightBoxImageAttribute(this, 'title'),
				description: self.getLightBoxImageAttribute(this, 'description')
			};

			if (slideVideo) {
				slideData.video = slideVideo;
			}

			slides.push(slideData);
		});
		slides.sort(function (a, b) {
			return a.index - b.index;
		});
		this.showModal({
			type: 'slideshow',
			id: slideshowID,
			modalOptions: {
				id: 'elementor-lightbox-slideshow-' + slideshowID
			},
			slideshow: {
				slides: slides,
				swiper: {
					initialSlide: +initialSlideIndex
				}
			}
		});
	},
	openLink: function (event) {
		var element = event.currentTarget,
				$target = $(event.target),
				editMode = ceFrontend.isEditMode(),
				isClickInsideElementor = !!$target.closest('.elementor-edit-area').length;

		if (!this.isLightboxLink(element)) {
			if (editMode && isClickInsideElementor) {
				event.preventDefault();
			}

			return;
		}

		event.preventDefault();

		if (editMode && !elementor.getPreferences('lightbox_in_editor')) {
			return;
		}

		var lightboxData = {};

		if (element.dataset.elementorLightbox) {
			lightboxData = JSON.parse(element.dataset.elementorLightbox);
		}

		if (lightboxData.type && 'slideshow' !== lightboxData.type) {
			this.showModal(lightboxData);
			return;
		}

		if (!element.dataset.elementorLightboxSlideshow) {
			var slideshowID = 'single-img';
			this.showModal({
				type: 'image',
				id: slideshowID,
				url: element.href,
				title: element.dataset.elementorLightboxTitle,
				description: element.dataset.elementorLightboxDescription,
				modalOptions: {
					id: 'elementor-lightbox-slideshow-' + slideshowID
				}
			});
			return;
		}

		this.openSlideshow(element.dataset.elementorLightboxSlideshow, element.href);
	},
	bindEvents: function () {
		ceFrontend.elements.$document.on('click', this.getSettings('selectors.links'), this.openLink);
	},
	onSlideChange: function () {
		this.getSlide('prev').add(this.getSlide('next')).add(this.getSlide('active')).find('.' + this.getSettings('classes.videoWrapper')).remove();
		this.playSlideVideo();
		this.updateFooterText();
	}
});

/***/ }),
/* 661 */
/***/ (function(module, exports, __webpack_require__) {

const fn = (function () {
	let val;
	const fnMap = [
		['requestFullscreen', 'exitFullscreen', 'fullscreenElement', 'fullscreenEnabled', 'fullscreenchange', 'fullscreenerror'],
		['webkitRequestFullscreen', 'webkitExitFullscreen', 'webkitFullscreenElement', 'webkitFullscreenEnabled', 'webkitfullscreenchange', 'webkitfullscreenerror'],
		['webkitRequestFullScreen', 'webkitCancelFullScreen', 'webkitCurrentFullScreenElement', 'webkitCancelFullScreen', 'webkitfullscreenchange', 'webkitfullscreenerror'],
		['mozRequestFullScreen', 'mozCancelFullScreen', 'mozFullScreenElement', 'mozFullScreenEnabled', 'mozfullscreenchange', 'mozfullscreenerror'],
		['msRequestFullscreen', 'msExitFullscreen', 'msFullscreenElement', 'msFullscreenEnabled', 'MSFullscreenChange', 'MSFullscreenError']
	];
	const l = fnMap.length;
	const ret = {};

	for (let i = 0; i < l; i++) {
		val = fnMap[i];
		if (val && val[1] in document) {
			for (let j = 0; j < val.length; j++) {
				ret[fnMap[0][j]] = val[j];
			}
			return ret;
		}
	}

	return false;
})();

const eventNameMap = {
	change: fn.fullscreenchange,
	error: fn.fullscreenerror
};

const screenfull = {
	request(element) {
		return new Promise((resolve, reject) => {
			const onFullScreenEntered = function () {
				this.off('change', onFullScreenEntered);
				resolve();
			}.bind(this);

			this.on('change', onFullScreenEntered);
			element = element || document.documentElement;

			Promise.resolve(element[fn.requestFullscreen]()).catch(reject);
		});
	},
	exit() {
		return new Promise((resolve, reject) => {
			if (!this.isFullscreen) {
				resolve();
				return;
			}

			const onFullScreenExit = function () {
				this.off('change', onFullScreenExit);
				resolve();
			}.bind(this);

			this.on('change', onFullScreenExit);

			Promise.resolve(document[fn.exitFullscreen]()).catch(reject);
		});
	},
	toggle(element) {
		return this.isFullscreen ? this.exit() : this.request(element);
	},
	onchange(callback) {
		this.on('change', callback);
	},
	onerror(callback) {
		this.on('error', callback);
	},
	on(event, callback) {
		const eventName = eventNameMap[event];
		if (eventName) {
			document.addEventListener(eventName, callback, false);
		}
	},
	off(event, callback) {
		const eventName = eventNameMap[event];
		if (eventName) {
			document.removeEventListener(eventName, callback, false);
		}
	},
	raw: fn
};

if (!fn) {
	module.exports = { isEnabled: false };
	return;
}

Object.defineProperties(screenfull, {
	isFullscreen: {
		get: function () {
			return Boolean(document[fn.fullscreenElement]);
		}
	},
	element: {
		enumerable: true,
		get: function () {
			return document[fn.fullscreenElement];
		}
	},
	isEnabled: {
		enumerable: true,
		get: function () {
			return Boolean(document[fn.fullscreenEnabled]);
		}
	}
});

module.exports = screenfull;

/***/ }),
/* 662 */
/***/ (function(module, exports) {

var CountDownHandler = elementorModules.frontend.handlers.Base.extend({
	cache: null,
	cacheElements: function () {
		var $countDown = this.$element.find('.elementor-countdown-wrapper');
		this.cache = {
			$countDown: $countDown,
			timeInterval: null,
			elements: {
				$countdown: $countDown.find('.elementor-countdown-wrapper'),
				$daysSpan: $countDown.find('.elementor-countdown-days'),
				$hoursSpan: $countDown.find('.elementor-countdown-hours'),
				$minutesSpan: $countDown.find('.elementor-countdown-minutes'),
				$secondsSpan: $countDown.find('.elementor-countdown-seconds'),
				$expireMessage: $countDown.parent().find('.elementor-countdown-expire--message')
			},
			data: {
				id: this.$element.data('id'),
				endTime: new Date($countDown.data('date') * 1000),
				actions: $countDown.data('expire-actions'),
				evergreenInterval: $countDown.data('evergreen-interval')
			}
		};
	},
	onInit: function () {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
		this.cacheElements();

		if (0 < this.cache.data.evergreenInterval) {
			this.cache.data.endTime = this.getEvergreenDate();
		}

		this.initializeClock();
	},
	updateClock: function () {
		var self = this,
				timeRemaining = this.getTimeRemaining(this.cache.data.endTime);
		$.each(timeRemaining.parts, function (timePart) {
			var $element = self.cache.elements['$' + timePart + 'Span'];
			var partValue = this.toString();

			if (1 === partValue.length) {
				partValue = 0 + partValue;
			}

			if ($element.length) {
				$element.text(partValue);
			}
		});

		if (timeRemaining.total <= 0) {
			clearInterval(this.cache.timeInterval);
			this.runActions();
		}
	},
	initializeClock: function () {
		var self = this;
		this.updateClock();
		this.cache.timeInterval = setInterval(function () {
			self.updateClock();
		}, 1000);
	},
	runActions: function () {
		var self = this; // Trigger general event for 3rd patry actions

		self.$element.trigger('countdown_expire', self.$element);

		if (!this.cache.data.actions) {
			return;
		}

		this.cache.data.actions.forEach(function (action) {
			switch (action.type) {
				case 'hide':
					self.cache.$countDown.hide();
					break;

				case 'redirect':
					if (action.redirect_url) {
						window.location.href = action.redirect_url;
					}

					break;

				case 'message':
					self.cache.elements.$expireMessage.show();
					break;
			}
		});
	},
	getTimeRemaining: function (endTime) {
		var timeRemaining = endTime - new Date();
		var seconds = Math.floor(timeRemaining / 1000 % 60),
				minutes = Math.floor(timeRemaining / 1000 / 60 % 60),
				hours = Math.floor(timeRemaining / (1000 * 60 * 60) % 24),
				days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));

		if (days < 0 || hours < 0 || minutes < 0) {
			seconds = minutes = hours = days = 0;
		}

		return {
			total: timeRemaining,
			parts: {
				days: days,
				hours: hours,
				minutes: minutes,
				seconds: seconds
			}
		};
	},
	getEvergreenDate: function () {
		var self = this,
				id = this.cache.data.id,
				interval = this.cache.data.evergreenInterval,
				dueDateKey = id + '-evergreen_due_date',
				intervalKey = id + '-evergreen_interval',
				localData = {
			dueDate: localStorage.getItem(dueDateKey),
			interval: localStorage.getItem(intervalKey)
		},
				initEvergreen = function initEvergreen() {
			var evergreenDueDate = new Date();
			self.cache.data.endTime = evergreenDueDate.setSeconds(evergreenDueDate.getSeconds() + interval);
			localStorage.setItem(dueDateKey, self.cache.data.endTime);
			localStorage.setItem(intervalKey, interval);
			return self.cache.data.endTime;
		};

		if (null === localData.dueDate && null === localData.interval) {
			return initEvergreen();
		}

		if (null !== localData.dueDate && interval !== parseInt(localData.interval, 10)) {
			return initEvergreen();
		}

		if (localData.dueDate > 0 && parseInt(localData.interval, 10) === interval) {
			return localData.dueDate;
		}
	}
});

module.exports = function ($scope) {
	new CountDownHandler({$element: $scope});
};

/***/ }),
/* 663 */
/***/ (function(module, exports) {

var AnimatedHeadlineHandler = elementorModules.frontend.handlers.Base.extend({
	svgPaths: {
		circle: ['M325,18C228.7-8.3,118.5,8.3,78,21C22.4,38.4,4.6,54.6,5.6,77.6c1.4,32.4,52.2,54,142.6,63.7 c66.2,7.1,212.2,7.5,273.5-8.3c64.4-16.6,104.3-57.6,33.8-98.2C386.7-4.9,179.4-1.4,126.3,20.7'],
		underline_zigzag: ['M9.3,127.3c49.3-3,150.7-7.6,199.7-7.4c121.9,0.4,189.9,0.4,282.3,7.2C380.1,129.6,181.2,130.6,70,139 c82.6-2.9,254.2-1,335.9,1.3c-56,1.4-137.2-0.3-197.1,9'],
		x: ['M497.4,23.9C301.6,40,155.9,80.6,4,144.4', 'M14.1,27.6c204.5,20.3,393.8,74,467.3,111.7'],
		strikethrough: ['M3,75h493.5'],
		curly: ['M3,146.1c17.1-8.8,33.5-17.8,51.4-17.8c15.6,0,17.1,18.1,30.2,18.1c22.9,0,36-18.6,53.9-18.6 c17.1,0,21.3,18.5,37.5,18.5c21.3,0,31.8-18.6,49-18.6c22.1,0,18.8,18.8,36.8,18.8c18.8,0,37.5-18.6,49-18.6c20.4,0,17.1,19,36.8,19 c22.9,0,36.8-20.6,54.7-18.6c17.7,1.4,7.1,19.5,33.5,18.8c17.1,0,47.2-6.5,61.1-15.6'],
		diagonal: ['M13.5,15.5c131,13.7,289.3,55.5,475,125.5'],
		double: ['M8.4,143.1c14.2-8,97.6-8.8,200.6-9.2c122.3-0.4,287.5,7.2,287.5,7.2', 'M8,19.4c72.3-5.3,162-7.8,216-7.8c54,0,136.2,0,267,7.8'],
		double_underline: ['M5,125.4c30.5-3.8,137.9-7.6,177.3-7.6c117.2,0,252.2,4.7,312.7,7.6', 'M26.9,143.8c55.1-6.1,126-6.3,162.2-6.1c46.5,0.2,203.9,3.2,268.9,6.4'],
		underline: ['M7.7,145.6C109,125,299.9,116.2,401,121.3c42.1,2.2,87.6,11.8,87.3,25.7']
	},
	getDefaultSettings: function () {
		var settings = {
			animationDelay: 2500,
			//letters effect
			lettersDelay: 50,
			//typing effect
			typeLettersDelay: 150,
			selectionDuration: 500,
			//clip effect
			revealDuration: 600,
			revealAnimationDelay: 1500
		};
		settings.typeAnimationDelay = settings.selectionDuration + 800;
		settings.selectors = {
			headline: '.elementor-headline',
			dynamicWrapper: '.elementor-headline-dynamic-wrapper'
		};
		settings.classes = {
			dynamicText: 'elementor-headline-dynamic-text',
			dynamicLetter: 'elementor-headline-dynamic-letter',
			textActive: 'elementor-headline-text-active',
			textInactive: 'elementor-headline-text-inactive',
			letters: 'elementor-headline-letters',
			animationIn: 'elementor-headline-animation-in',
			typeSelected: 'elementor-headline-typing-selected'
		};
		return settings;
	},
	getDefaultElements: function () {
		var selectors = this.getSettings('selectors');
		return {
			$headline: this.$element.find(selectors.headline),
			$dynamicWrapper: this.$element.find(selectors.dynamicWrapper)
		};
	},
	getNextWord: function ($word) {
		return $word.is(':last-child') ? $word.parent().children().eq(0) : $word.next();
	},
	switchWord: function ($oldWord, $newWord) {
		$oldWord.removeClass('elementor-headline-text-active').addClass('elementor-headline-text-inactive');
		$newWord.removeClass('elementor-headline-text-inactive').addClass('elementor-headline-text-active');
		this.setDynamicWrapperWidth($newWord);
	},
	singleLetters: function () {
		var classes = this.getSettings('classes');
		this.elements.$dynamicText.each(function () {
			var $word = $(this),
					letters = $word.text().split(''),
					isActive = $word.hasClass(classes.textActive);
			$word.empty();
			letters.forEach(function (letter) {
				var $letter = $('<span>', {class: classes.dynamicLetter}).text(letter);

				if (isActive) {
					$letter.addClass(classes.animationIn);
				}

				$word.append($letter);
			});
			$word.css('opacity', 1);
		});
	},
	showLetter: function ($letter, $word, bool, duration) {
		var self = this,
				classes = this.getSettings('classes');
		$letter.addClass(classes.animationIn);

		if (!$letter.is(':last-child')) {
			setTimeout(function () {
				self.showLetter($letter.next(), $word, bool, duration);
			}, duration);
		} else if (!bool) {
			setTimeout(function () {
				self.hideWord($word);
			}, self.getSettings('animationDelay'));
		}
	},
	hideLetter: function ($letter, $word, bool, duration) {
		var self = this,
				settings = this.getSettings();
		$letter.removeClass(settings.classes.animationIn);

		if (!$letter.is(':last-child')) {
			setTimeout(function () {
				self.hideLetter($letter.next(), $word, bool, duration);
			}, duration);
		} else if (bool) {
			setTimeout(function () {
				self.hideWord(self.getNextWord($word));
			}, self.getSettings('animationDelay'));
		}
	},
	showWord: function ($word, $duration) {
		var self = this,
				settings = self.getSettings(),
				animationType = self.getElementSettings('animation_type');

		if ('typing' === animationType) {
			self.showLetter($word.find('.' + settings.classes.dynamicLetter).eq(0), $word, false, $duration);
			$word.addClass(settings.classes.textActive).removeClass(settings.classes.textInactive);
		} else if ('clip' === animationType) {
			self.elements.$dynamicWrapper.animate({
				width: $word.width() + 10
			}, settings.revealDuration, function () {
				setTimeout(function () {
					self.hideWord($word);
				}, settings.revealAnimationDelay);
			});
		}
	},
	hideWord: function ($word) {
		var self = this,
				settings = self.getSettings(),
				classes = settings.classes,
				letterSelector = '.' + classes.dynamicLetter,
				animationType = self.getElementSettings('animation_type'),
				nextWord = self.getNextWord($word);

		if ('typing' === animationType) {
			self.elements.$dynamicWrapper.addClass(classes.typeSelected);
			setTimeout(function () {
				self.elements.$dynamicWrapper.removeClass(classes.typeSelected);
				$word.addClass(settings.classes.textInactive).removeClass(classes.textActive).children(letterSelector).removeClass(classes.animationIn);
			}, settings.selectionDuration);
			setTimeout(function () {
				self.showWord(nextWord, settings.typeLettersDelay);
			}, settings.typeAnimationDelay);
		} else if (self.elements.$headline.hasClass(classes.letters)) {
			var bool = $word.children(letterSelector).length >= nextWord.children(letterSelector).length;
			self.hideLetter($word.find(letterSelector).eq(0), $word, bool, settings.lettersDelay);
			self.showLetter(nextWord.find(letterSelector).eq(0), nextWord, bool, settings.lettersDelay);
			self.setDynamicWrapperWidth(nextWord);
		} else if ('clip' === animationType) {
			self.elements.$dynamicWrapper.animate({
				width: '2px'
			}, settings.revealDuration, function () {
				self.switchWord($word, nextWord);
				self.showWord(nextWord);
			});
		} else {
			self.switchWord($word, nextWord);
			setTimeout(function () {
				self.hideWord(nextWord);
			}, settings.animationDelay);
		}
	},
	setDynamicWrapperWidth: function ($newWord) {
		var animationType = this.getElementSettings('animation_type');

		if ('clip' !== animationType && 'typing' !== animationType) {
			this.elements.$dynamicWrapper.css('width', $newWord.width());
		}
	},
	animateHeadline: function () {
		var self = this,
				animationType = self.getElementSettings('animation_type'),
				$dynamicWrapper = self.elements.$dynamicWrapper;

		if ('clip' === animationType) {
			$dynamicWrapper.width($dynamicWrapper.width() + 10);
		} else if ('typing' !== animationType) {
			//assign to .elementor-headline-dynamic-wrapper the width of its longest word
			var width = 0;
			self.elements.$dynamicText.each(function () {
				var wordWidth = $(this).width();

				if (wordWidth > width) {
					width = wordWidth;
				}
			});
			$dynamicWrapper.css('width', width);
		} //trigger animation


		setTimeout(function () {
			self.hideWord(self.elements.$dynamicText.eq(0));
		}, self.getSettings('animationDelay'));
	},
	getSvgPaths: function (pathName) {
		var pathsInfo = this.svgPaths[pathName],
				$paths = $();
		pathsInfo.forEach(function (pathInfo) {
			$paths = $paths.add($('<path>', {d: pathInfo}));
		});
		return $paths;
	},
	fillWords: function () {
		var elementSettings = this.getElementSettings(),
				classes = this.getSettings('classes'),
				$dynamicWrapper = this.elements.$dynamicWrapper;

		if ('rotate' === elementSettings.headline_style) {
			var rotatingText = (elementSettings.rotating_text || '').split('\n');
			rotatingText.forEach(function (word, index) {
				var $dynamicText = $('<span>', {class: classes.dynamicText}).html(word.replace(/ /g, '&nbsp;'));

				if (!index) {
					$dynamicText.addClass(classes.textActive);
				}

				$dynamicWrapper.append($dynamicText);
			});
		} else {
			var $dynamicText = $('<span>', {class: classes.dynamicText + ' ' + classes.textActive}).text(elementSettings.highlighted_text),
					$svg = $('<svg>', {xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 500 150', preserveAspectRatio: 'none'}).html(this.getSvgPaths(elementSettings.marker));
			$dynamicWrapper.append($dynamicText, $svg[0].outerHTML);
		}

		this.elements.$dynamicText = $dynamicWrapper.children('.' + classes.dynamicText);
	},
	rotateHeadline: function () {
		var settings = this.getSettings(); //insert <span> for each letter of a changing word

		if (this.elements.$headline.hasClass(settings.classes.letters)) {
			this.singleLetters();
		} //initialise headline animation


		this.animateHeadline();
	},
	initHeadline: function () {
		if ('rotate' === this.getElementSettings('headline_style')) {
			this.rotateHeadline();
		}
	},
	onInit: function () {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
		this.fillWords();
		this.initHeadline();
	}
});

module.exports = function ($scope) {
	new AnimatedHeadlineHandler({$element: $scope});
};

/***/ }),
/* 664 */
/***/ (function(module, exports) {

if ($.fn.smartmenus) {
	// Override the default detection
	$.SmartMenus.prototype.isCSSOn = function () {
		return true;
	};
}

var MenuHandler = elementorModules.frontend.handlers.Base.extend({
	stretchElement: null,
	getDefaultSettings: function () {
		return {
			selectors: {
				menu: '.elementor-nav',
				anchorLink: '.elementor-nav--main .elementor-item-anchor',
				mainMenu: '.elementor-nav__container.elementor-nav--main',
				dropdownMenu: '.elementor-nav__container.elementor-nav--dropdown',
				menuToggle: '.elementor-menu-toggle'
			}
		};
	},
	getDefaultElements: function () {
		var selectors = this.getSettings('selectors'),
				elements = {};
		elements.$menu = this.$element.find(selectors.menu);
		elements.$anchorLink = this.$element.find(selectors.anchorLink);
		elements.$mainMenu = this.$element.find(selectors.mainMenu);
		elements.$dropdownMenu = this.$element.find(selectors.dropdownMenu);
		elements.$dropdownMenuFinalItems = elements.$dropdownMenu.find('.menu-item:not(.menu-item-has-children) > a');
		elements.$menuToggle = this.$element.find(selectors.menuToggle);
		return elements;
	},
	bindEvents: function () {
		if (!this.elements.$menu.length) {
			return;
		}

		this.elements.$menuToggle.on('click', this.toggleMenu.bind(this));

		if (this.getElementSettings('full_width')) {
			this.elements.$dropdownMenuFinalItems.on('click', this.toggleMenu.bind(this, false));
		}

		ceFrontend.addListenerOnce(this.$element.data('model-cid'), 'resize', this.stretchMenu);
	},
	initStretchElement: function () {
		this.stretchElement = new elementorModules.frontend.tools.StretchElement({
			element: this.elements.$dropdownMenu
		});
	},
	toggleMenu: function (show) {
		var isDropdownVisible = this.elements.$menuToggle.hasClass('elementor-active');

		if ('boolean' !== typeof show) {
			show = !isDropdownVisible;
		}

		this.elements.$menuToggle.attr('aria-expanded', show);
		this.elements.$dropdownMenu.attr('aria-hidden', !show);
		this.elements.$menuToggle.toggleClass('elementor-active', show);

		if (show && this.getElementSettings('full_width')) {
			this.stretchElement.stretch();
		}
	},
	followMenuAnchors: function () {
		var self = this;
		self.elements.$anchorLink.each(function () {
			if (location.pathname === this.pathname && '' !== this.hash) {
				self.followMenuAnchor($(this));
			}
		});
	},
	followMenuAnchor: function ($element) {
		const anchorSelector = $element[0].hash,
			$targetElement = $element.hasClass('elementor-item-anchor') ? $element : $element.closest('.elementor-item-anchor');
		let rootMargin = '300px 0px -50% 0px',
			$anchor;

		try {
			// `decodeURIComponent` for UTF8 characters in the hash.
			$anchor = $(decodeURIComponent(anchorSelector));
		} catch (e) {
			return;
		}

		if (!$anchor.length) {
			return;
		}

		if (!$anchor.hasClass('elementor-menu-anchor')) {
			rootMargin = this.calculateRootMargin($anchor);
		}
		const threshold = this.buildThreshold($anchor);

		new IntersectionObserver(entries => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					$targetElement.addClass('elementor-item-active');
					// $element.attr('aria-current', 'location');
				} else {
					$targetElement.removeClass('elementor-item-active');
					// $element.attr('aria-current', '');
				}
			});
		}, {
			root: null,
			rootMargin,
			threshold
		}).observe($anchor[0]);
	},
	calculateRootMargin: function ($anchor) {
		const viewportHeight = $(window).height();
		const anchorHeight = $anchor.outerHeight();
		let rootMargin;
		if (anchorHeight > viewportHeight) {
			rootMargin = 0;
		} else {
			const boxViewport = viewportHeight - anchorHeight;
			rootMargin = boxViewport / 2;
		}
		return rootMargin + 'px';
	},
	buildThreshold: function ($anchor) {
		const viewportHeight = $(window).height();
		const anchorHeight = $anchor.outerHeight();
		let threshold = 0.5;
		if (anchorHeight > viewportHeight) {
			const halfViewport = viewportHeight / 2;
			threshold = halfViewport / anchorHeight;
		}
		return threshold;
	},
	stretchMenu: function () {
		if (this.getElementSettings('full_width')) {
			this.stretchElement.stretch();
			this.elements.$dropdownMenu.css('top', this.elements.$menuToggle.outerHeight());
		} else {
			this.stretchElement.reset();
		}
	},
	onInit: function () {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

		if (!this.elements.$menu.length) {
			return;
		}
		var align = this.getElementSettings('align_submenu'),
				noMouseOver = 'click' === this.getElementSettings('show_submenu_on');

		this.elements.$menu.smartmenus({
			subIndicators: false,
			subMenusMaxWidth: '1000px',
			noMouseOver: noMouseOver,
			rightToLeftSubMenus: align ? 'right' === align : ceFrontend.config.is_rtl
		});

		if (noMouseOver) {
			this.elements.$mainMenu.filter('.elementor-langs, .elementor-currencies, .elementor-sign-in').children()
				.on('click', 'a.elementor-item.has-submenu.highlighted', function (event) {
					// Close submenu on 2nd click
					this.elements.$menu.smartmenus('menuHide', $(event.currentTarget).next());
					event.currentTarget.blur();
					event.preventDefault();
				}.bind(this))
			;
		}

		if ('accordion' === this.getElementSettings('animation_dropdown')) {
			// Trick for accordion mobile menu
			var $accordion = this.elements.$dropdownMenu.children().on('click.ce', 'a.has-submenu', function () {
				var $ul = $(this.parentNode).siblings().children('a.highlighted').next();

				$ul.length && $accordion.smartmenus('menuHide', $ul);
			});
		}

		this.initStretchElement();
		this.stretchMenu();

		if (!ceFrontend.isEditMode()) {
			this.followMenuAnchors();
		}
	},
	onElementChange: function (propertyName) {
		if ('full_width' === propertyName) {
			this.stretchMenu();
		}
	}
});

module.exports = function ($scope) {
	new MenuHandler({$element: $scope});
};

/***/ }),
/* 665 */
/***/ (function(module, exports) {

var AjaxSearchHandler = elementorModules.frontend.handlers.Base.extend({
	getDefaultSettings: function () {
		return {
			selectors: {
				wrapper: '.elementor-search',
				container: '.elementor-search__container',
				icon: '.elementor-search__icon',
				input: '.elementor-search__input',
				clear: '.elementor-search__clear',
				toggle: '.elementor-search__toggle',
				submit: '.elementor-search__submit',
				closeButton: '.dialog-close-button'
			},
			classes: {
				isFocus: 'elementor-search--focus',
				isTopbar: 'elementor-search--topbar',
				lightbox: 'elementor-lightbox'
			}
		};
	},
	getDefaultElements: function () {
		var selectors = this.getSettings('selectors'),
			elements = {};

		elements.$wrapper = this.$element.find(selectors.wrapper);
		elements.$container = this.$element.find(selectors.container);
		elements.$input = this.$element.find(selectors.input);
		elements.$clear = this.$element.find(selectors.clear);
		elements.$icon = this.$element.find(selectors.icon);
		elements.$toggle = this.$element.find(selectors.toggle);
		elements.$submit = this.$element.find(selectors.submit);
		elements.$closeButton = this.$element.find(selectors.closeButton);

		return elements;
	},
	bindEvents: function () {
		var self = this,
			$container = self.elements.$container,
			$closeButton = self.elements.$closeButton,
			$input = self.elements.$input,
			$clear = self.elements.$clear,
			$wrapper = self.elements.$wrapper,
			$icon = self.elements.$icon,
			skin = this.getElementSettings('skin'),
			classes = this.getSettings('classes');

		'search' !== prestashop.page.page_name
			&& $input.one('focus', $.proxy(this, 'loadAutocomplete'));

		$clear.on('click', function () {
			$input.val('').triggerHandler('keydown');
			$clear.css({
				visibility: '',
				pointerEvents: '',
			});
		});

		$input.on('input', function () {
			var empty = !$input.val();

			$clear.css({
				visibility: empty ? '' : 'visible',
				pointerEvents: empty ? '' : 'all',
			});
		});

		if ('topbar' === skin) {
			// Activate topbar mode on click
			self.elements.$toggle.on('click', function () {
				$container.toggleClass(classes.isTopbar).toggleClass(classes.lightbox);
				$input.focus();
			});

			$closeButton.on('click', function () {
				$container.removeClass(classes.isTopbar).removeClass(classes.lightbox);
			});
			// Deactivate topbar mode on click or on esc.
			ceFrontend.elements.$document.keyup(function (event) {
				var ESC_KEY = 27;

				if (ESC_KEY === event.keyCode) {
					if ($container.hasClass(classes.isTopbar)) {
						$container.click();
					}
				}
			}).on('click', function (event) {
				if ($container.hasClass(classes.isTopbar) && !$(event.target).closest($wrapper).length) {
					$container.removeClass(classes.isTopbar).removeClass(classes.lightbox);
				}
			});
		} else {
			// Apply focus style on wrapper element when input is focused
			$input.on({
				focus: function () {
					$wrapper.addClass(classes.isFocus);
				},
				blur: function () {
					$wrapper.removeClass(classes.isFocus);
				}
			});
		}

		if ('minimal' === skin) {
			// Apply focus style on wrapper element when icon is clicked in minimal skin
			$icon.on('click', function () {
				$wrapper.addClass(classes.isFocus);
				$input.focus();
			});
		}
	},
	loadAutocomplete: function () {
		var baseDir = window.baseDir || prestashop.urls.base_url,
				include = $.ui && $.ui.autocomplete ? '' : 'jquery-ui';

		if (include) {
			$('<link rel="stylesheet">').attr({
				href: baseDir + 'js/jquery/ui/themes/base/minified/' + include + '.min.css'
			}).appendTo(document.head);

			$('<link rel="stylesheet">').attr({
				href: baseDir + 'js/jquery/ui/themes/base/minified/jquery.ui.theme.min.css'
			}).appendTo(document.head);

			$.ajax({
				url: baseDir + 'js/jquery/ui/' + include + '.min.js',
				cache: true,
				dataType: 'script',
				success: $.proxy(this, 'initAutocomplete')
			});
		} else {
			this.initAutocomplete();
		}
	},
	initAutocomplete: function () {
		$.fn.ceAjaxSearch || $.widget('ww.ceAjaxSearch', $.ui.autocomplete, {
			_create: function () {
				this._super();
				this.menu.element.addClass('elementor-search__products');
				this.element.on('focus' + this.eventNamespace, $.proxy(this, '_openOnFocus'))
				$(document).on('click' + this.eventNamespace, $.proxy(this, '_closeOnDocumentClick'));

				// Don't close on blur
				this._off(this.element, 'blur');
				// Trick for disable auto-scrolling on hover
				this.menu.element.outerHeight = function() {
					if (window.event && 'mouseover' === event.type) {
						return Infinity;
					}
					return $.fn.outerHeight.apply(this, arguments);
				};
			},
			_openOnFocus: function (event) {
				this.menu.element.show();
				this._resizeMenu();
				this.menu.element.position(
					$.extend({of: this.element}, this.options.position)
				);
			},
			_closeOnDocumentClick: function (event) {
				$(event.target).closest(this.options.appendTo).length || this._close();
			},
			search: function (value, event) {
				value = value != null ? value : this._value();
				this._super(value, event);

				if (value.length < this.options.minLength) {
					// Clear previous results
					this.menu.element.empty();
				}
			},
			_renderItem: function (ul, prod) {
				var es = this.options.elementSettings,
					cover = prod.cover && prod.cover.small.url || prestashop.urls.img_prod_url + prestashop.language.iso_code + '-default-small_default.jpg';

				return $('<li class="elementor-search__product">').html(
					'<a class="elementor-search__product-link" href="' + encodeURI(prod.url) + '">' +
						(es.show_image ? '<img class="elementor-search__product-image" src="' + encodeURI(cover) + '" alt="' + prod.name.replace(/"/g, '&quot;') + '">' : '') +
						'<div class="elementor-search__product-details">' +
							'<div class="elementor-search__product-name">' + prod.name + '</div>' +
							(es.show_category ? '<div class="elementor-search__product-category">' + prod.category_name + '</div>' : '') +
							(es.show_description ? '<div class="elementor-search__product-description">' + (prod.description_short || '').replace(/<\/?\w+.*?>/g, '') + '</div>' : '') +
							(es.show_price ? '<div class="elementor-search__product-price">' + (prod.has_discount ? '<del>' + prod.regular_price + '</del> ' : '') + prod.price + '</div>' : '') +
						'</div>' +
					'</a>'
				).appendTo(ul);
			},
			_resizeMenu: function () {
				this._super();
				this.options.position.my = 'left top+' + this.menu.element.css('margin-top');

				setTimeout(function () {
					this.menu.element.css({
						maxHeight: 'calc(100vh - ' + (this.menu.element.offset().top - $(window).scrollTop())  + 'px)',
						overflowY: 'auto',
						WebkitOverflowScrolling: 'touch',
					});
				}.bind(this), 1);
			},
		});
		var action = this.elements.$wrapper.prop('action'),
			searchName = this.elements.$input.prop('name');

		this.elements.$input.ceAjaxSearch({
			appendTo: 'topbar' === this.getElementSettings('skin')
				? this.elements.$container
				: this.elements.$wrapper,
			minLength: this.elements.$input.attr('minlength'),
			elementSettings: this.getElementSettings(),

			source: function (query, response) {
				var data = {
					ajax: true,
					resultsPerPage: this.options.elementSettings.list_limit || 10,
				};
				data[searchName] = query.term;

				$.post(action, data, null, 'json')
					.then(function (resp) {
						response(resp.products);
					})
					.fail(response)
				;
			},

			select: function (event, ui) {
				if (location.href !== ui.item.url && !ceFrontend.isEditMode()) {
					location.href = ui.item.url;
				};
			},
		});
	}
});

module.exports = function ($scope) {
	new AjaxSearchHandler({$element: $scope});
};

/***/ }),
/* 666 */
/***/ (function(module, exports) {

var AjaxFormHandler = (function(c){return(c.constructor.prototype=c).constructor})({
	constructor: function AjaxFormHandler( $element ) {
		this.$element = $element;

		this.settings = {
			selectors: {
				form: 'form',
				submitButton: '[type="submit"]'
			}
		};

		this.elements = {};
		this.elements.$form = this.$element.find( this.settings.selectors.form );
		this.elements.$submitButton = this.elements.$form.find( this.settings.selectors.submitButton );

		this.bindEvents();
	},
	bindEvents: function() {
		this.elements.$form.on( 'submit', $.proxy( this, 'handleSubmit' ) );
	},
	beforeSend: function() {
		var $form = this.elements.$form;

		$form
			.animate( { opacity: '0.45' }, 500 )
			.addClass( 'elementor-form-waiting' )
		;
		$form.find( '.elementor-message' ).remove();
		$form.find( '.elementor-error' ).removeClass( 'elementor-error' );

		$form
			.find( 'div.elementor-field-group' )
			.removeClass( 'error' )
			.find( 'span.elementor-form-help-inline' )
			.remove()
			.end()
			.find( ':input' ).attr( 'aria-invalid', 'false' )
		;
		this.elements.$submitButton
			.attr( 'disabled', 'disabled' )
			.find( '> span' )
			.prepend( '<span class="elementor-form-spinner ceicon-loading ceicon-animation-spin"></span>' )
		;
	},
	getFormData: function() {
		var formData = new FormData( this.elements.$form[0] );
		formData.append(
			this.elements.$submitButton[0].name,
			this.elements.$submitButton[0].value
		);
		return formData;
	},
	onSuccess: function( response, status ) {
		var $form = this.elements.$form,
			insertMethod = $form.data( 'msg' ) === 'before' ? 'prepend' : 'append';

		this.elements.$submitButton
			.removeAttr( 'disabled' )
			.find( '.elementor-form-spinner' )
			.remove()
		;
		$form
			.animate( { opacity: '1' }, 100 )
			.removeClass( 'elementor-form-waiting' )
		;

		if ( response.success ) {
			var message = $form.data( 'success' ) || response.success;

			$form.trigger( 'submit_success', response );
			$form.trigger( 'form_destruct', response );
			$form.trigger( 'reset' );

			$form[insertMethod]( '<div class="elementor-message elementor-message-success" role="alert">' + message + '</div>' );
		} else {
			var message = $form.data( 'error' ) || response.errors && response.errors.join( '<br>' ) || 'Unknown error';

			$form[insertMethod]( '<div class="elementor-message elementor-message-danger" role="alert">' + message + '</div>' );
		}
	},
	onError: function( xhr, desc ) {
		var $form = this.elements.$form;

		$form.append( '<div class="elementor-message elementor-message-danger" role="alert">' + desc + '</div>' );

		this.elements.$submitButton
			.html( this.elements.$submitButton.text() )
			.removeAttr( 'disabled' )
		;
		$form
			.animate( {
				opacity: '1'
			}, 100 )
			.removeClass( 'elementor-form-waiting' )
		;
		$form.trigger( 'error' );
	},
	handleSubmit: function( event ) {
		var $form = this.elements.$form;

		event.preventDefault();

		if ( $form.hasClass( 'elementor-form-waiting' ) ) {
			return false;
		}

		this.beforeSend();

		$.ajax( {
			url: $form.attr('action'),
			type: 'POST',
			dataType: 'json',
			data: this.getFormData(),
			processData: false,
			contentType: false,
			success: $.proxy( this, 'onSuccess' ),
			error: $.proxy( this, 'onError' )
		} );
	}
});

module.exports = function( $scope, $ ) {
	new AjaxFormHandler( $scope );
};

/***/ }),
/* 667 */
/***/ (function(module, exports) {

var ShoppingCartHandler = elementorModules.frontend.handlers.Base.extend({

	getDefaultSettings: function () {
		return {
			selectors: {
				container: '.elementor-cart__container',
				toggle: '.elementor-cart__toggle .elementor-button',
				closeButton: '.elementor-cart__close-button'
			},
			classes: {
				isShown: 'elementor-cart--shown',
				lightbox: 'elementor-lightbox',
				isHidden: 'elementor-cart-hidden'
			}
		};
	},

	getDefaultElements: function () {
		var selectors = this.getSettings('selectors'),
			elements = {};

		elements.$container = this.$element.find(selectors.container);
		elements.$toggle = this.$element.find(selectors.toggle);
		elements.$closeButton = this.$element.find(selectors.closeButton);

		return elements;
	},

	bindEvents: function () {
		var self = this,
			$container = self.elements.$container,
			$closeButton = self.elements.$closeButton,
			classes = this.getSettings('classes');

		// Activate sidebar mode on click
		self.elements.$toggle.on('click', function (event) {
			if (!self.elements.$toggle.hasClass(classes.isHidden)) {
				event.preventDefault();
				$container.toggleClass(classes.isShown);
			}
		});

		// Deactivate sidebar mode on click or on esc.
		$container.on('click', function (event) {
			if ($container.hasClass(classes.isShown) && $container[0] === event.target) {
				$container.removeClass(classes.isShown);
			}
		});

		$closeButton.on('click', function () {
			$container.removeClass(classes.isShown);
		});

		ceFrontend.elements.$document.keyup(function (event) {
			var ESC_KEY = 27;

			if (ESC_KEY === event.keyCode) {
				if ($container.hasClass(classes.isShown)) {
					$container.click();
				}
			}
		});

		$container.on('click', '.elementor-cart__product-remove a', function (event) {
			var dataset = $(this).data();
				dataset.linkAction = 'delete-from-cart';

			$(this).closest('.elementor-cart__product').addClass('ce-disabled');

			event.preventDefault();

			$.ajax({
				url: this.href,
				method: 'POST',
				dataType: 'json',
				data: {
					ajax: 1,
					action: 'update',
				},
			}).then(function (resp) {
				prestashop.emit('updateCart', {
					reason: dataset,
					resp: resp,
				});
			}).fail(function (resp) {
				prestashop.emit('handleError', {
					eventType: 'updateProductInCart',
					resp: resp,
					cartAction: dataset.linkAction,
				});
			});
		});

		prestashop.on('updateCart', function(data) {
			if (!data || !data.resp || !data.resp.cart) {
				return;
			}
			var cart = data.resp.cart,
				gift = $container.find('.elementor-cart__products').data('gift'),
				$products = $();

			// Show ps_shoppingcart modal on update
			if (self.getElementSettings('action_show_modal') && 'add-to-cart' === data.reason.linkAction && !data.resp.hasError) {
				// Fix for multiple shopping carts
				if (!ShoppingCartHandler.xhr || ShoppingCartHandler.xhr.readyState > 1) {
					ShoppingCartHandler.xhr && ShoppingCartHandler.xhr.abort()
					ShoppingCartHandler.xhr = $.post(
						self.getElementSettings('modal_url'),
						{
							ajax: true,
							action: 'addToCartModal',
							id_product: data.reason.idProduct,
							id_product_attribute: data.reason.idProductAttribute,
							id_customization: data.reason.idCustomization,
						},
						function (resp) {
							$('#blockcart-modal').remove();
							$(document.body).append(resp.modal).children('#blockcart-modal').modal();
						},
						'json'
					);
				}
			}

			// Update toggle
			self.elements.$toggle.find('.elementor-button-text')
				.html(cart.subtotals.products.value)
			;
			self.elements.$toggle.find('.elementor-button-icon')
				.attr('data-counter', cart.products_count)
				.data('counter', cart.products_count)
			;

			var remove_item_icon = self.getElementSettings('remove_item_icon') || {};

			// Update products
			cart.products.forEach(function (product) {
				var $prod = $(
						'<div class="elementor-cart__product">' +
							'<div class="elementor-cart__product-image"></div>' +
							'<div class="elementor-cart__product-name">' +
								'<div class="elementor-cart__product-attrs"></div>' +
							'</div>' +
							'<div class="elementor-cart__product-price"></div>' +
							(remove_item_icon.value ? '<i class="elementor-cart__product-remove ' + remove_item_icon.value + '"></i>' : '') +
						'</div>'
					),
					$attrs = $prod.find('.elementor-cart__product-attrs'),
					cover = product.cover || prestashop.urls.no_picture_image;

				if (product.embedded_attributes && product.embedded_attributes.id_image) {
					// PS 1.7.8 fix - product.cover contains wrong image
					var i, id_cover = product.embedded_attributes.id_image.split('-').pop();
					for (i = 0; i < product.images.length; i++) {
						if (id_cover == product.images[i].id_image) {
							cover = product.images[i];
							break;
						}
					}
				}

				$('<img>').appendTo($prod.find('.elementor-cart__product-image')).attr({
					src: cover.bySize.cart_default && cover.bySize.cart_default.url || cover.small.url,
					alt: cover.legend,
				});
				$('<a>').prependTo($prod.find('.elementor-cart__product-name'))
					.attr('href', product['url'])
					.html(product['name'])
				;
				// Add product attributes
				for (var label in product['attributes']) {
					$('<div class="elementor-cart__product-attr">').html(
						'<span class="elementor-cart__product-attr-label">' + label + ':</span> ' +
						'<span class="elementor-cart__product-attr-value">' + product['attributes'][label] + '</span>'
					).appendTo($attrs);
				}
				// Add product customizations
				product.customizations && product.customizations.forEach(function (customization) {
					customization.fields.forEach(function (field) {
						$('<div class="elementor-cart__product-attr">').html(
							'<span class="elementor-cart__product-attr-label">' + field['label'] + ':</span> ' +
							'<span class="elementor-cart__product-attr-value">' +
								('image' === field['type'] ? $('<img>').attr('src', field['image']['small']['url'])[0].outerHTML : field['text']) +
							'</span>'
						).appendTo($attrs);
					});
				});
				$prod.find('.elementor-cart__product-price').html(
					'<span class="elementor-cart__product-quantity">' + product['quantity'] + '</span> &times; ' + (product['is_gift'] ? gift : product['price']) + ' '
				).append(product['has_discount'] ? $('<del>').html(product['regular_price']) : []);

				$('<a>').appendTo($prod.find('.elementor-cart__product-remove')).attr({
					href: product['remove_from_cart_url'],
					rel: 'nofollow',
					'data-id-product': product['id_product'],
					'data-id-product-attribute': product['id_product_attribute'],
					'data-id-customization': product['id_customization'],
				}).data({
					'idProduct': product['id_product'],
					'idProductAttribute': product['id_product_attribute'],
					'idCustomization': product['id_customization'],
				});
				$products.push($prod[0]);
			});
			// Update cart
			$container.find('.elementor-cart__products')
				.empty()
				.append($products)
			;
			$container.find('.elementor-cart__empty-message')
				.toggleClass('elementor-hidden', !!cart.products_count)
			;
			$container.find('.elementor-cart__summary').html(
				'<div class="elementor-cart__summary-label">' + cart.summary_string + '</div>' +
				'<div class="elementor-cart__summary-value">' + cart.subtotals.products.value + '</div>' + (cart.subtotals.discounts ?
				'<div class="elementor-cart__summary-label">' + cart.subtotals.discounts.label + '</div>' +
				'<div class="elementor-cart__summary-value">-' + cart.subtotals.discounts.value + '</div>' : '') +
				'<span class="elementor-cart__summary-label">' + cart.subtotals.shipping.label + '</span>' +
				'<span class="elementor-cart__summary-value">' + cart.subtotals.shipping.value + '</span>' +
				'<strong class="elementor-cart__summary-label">' + cart.totals.total.label + '</strong>' +
				'<strong class="elementor-cart__summary-value">' + cart.totals.total.value + '</strong>'
			);
			$container.find('.elementor-alert-warning')
				.toggleClass('elementor-hidden', !cart.minimalPurchaseRequired)
				.html('<span class="elementor-alert-description">' + cart.minimalPurchaseRequired + '</span>');
			;
			$container.find('.elementor-button--checkout')
				.toggleClass('ce-disabled', cart.minimalPurchaseRequired || !cart.products_count)
			;

			// Open shopping cart after updated
			if (self.getElementSettings('action_open_cart') && 'add-to-cart' === data.reason.linkAction && !data.resp.hasError) {
				self.elements.$container.hasClass(classes.isShown) || self.elements.$toggle.triggerHandler('click');
			}
		});
	}
});

module.exports = function ($scope) {
	new ShoppingCartHandler({$element: $scope});
};

/***/ }),
/* 668 */
/***/ (function(module, exports) {

module.exports = elementorModules.frontend.handlers.Base.extend({
	getDefaultSettings: function () {
		return {
			selectors: {
				mainSwiper: '.elementor-main-swiper',
				swiperSlide: '.swiper-slide'
			},
			slidesPerView: {
				desktop: 3,
				tablet: 2,
				mobile: 1
			}
		};
	},
	getDefaultElements: function () {
		var selectors = this.getSettings('selectors');
		var elements = {
			$mainSwiper: this.$element.find(selectors.mainSwiper)
		};
		elements.$mainSwiperSlides = elements.$mainSwiper.find(selectors.swiperSlide);
		return elements;
	},
	getSlidesCount: function () {
		return this.elements.$mainSwiperSlides.length;
	},
	getInitialSlide: function () {
		var editSettings = this.getEditSettings();
		return editSettings.activeItemIndex ? editSettings.activeItemIndex - 1 : 0;
	},
	getEffect: function () {
		return this.getElementSettings('effect');
	},
	getDeviceSlidesPerView: function (device) {
		var slidesPerViewKey = 'slides_per_view' + ('desktop' === device ? '' : '_' + device);
		return +this.getElementSettings(slidesPerViewKey) || this.getSettings('slidesPerView')[device];
	},
	getSlidesPerView: function (device) {
		if ('slide' === this.getEffect()) {
			return this.getDeviceSlidesPerView(device);
		}

		return 1;
	},
	getDesktopSlidesPerView: function () {
		return this.getSlidesPerView('desktop');
	},
	getTabletSlidesPerView: function () {
		return this.getSlidesPerView('tablet');
	},
	getMobileSlidesPerView: function () {
		return this.getSlidesPerView('mobile');
	},
	getDeviceSlidesToScroll: function (device) {
		var slidesToScrollKey = 'slides_to_scroll' + ('desktop' === device ? '' : '_' + device);
		return Math.min(this.getSlidesCount(), +this.getElementSettings(slidesToScrollKey) || 1);
	},
	getSlidesToScroll: function (device) {
		if ('slide' === this.getEffect()) {
			return this.getDeviceSlidesToScroll(device);
		}

		return 1;
	},
	getDesktopSlidesToScroll: function () {
		return this.getSlidesToScroll('desktop');
	},
	getTabletSlidesToScroll: function () {
		return this.getSlidesToScroll('tablet');
	},
	getMobileSlidesToScroll: function () {
		return this.getSlidesToScroll('mobile');
	},
	getSpaceBetween: function (device) {
		var propertyName = 'space_between';

		if (device && 'desktop' !== device) {
			propertyName += '_' + device;
		}

		return (this.getElementSettings(propertyName) || {}).size || 0;
	},
	getSwiperOptions: function () {
		var elementSettings = this.getElementSettings(); // TODO: Temp migration for old saved values since 2.2.0

		if ('progress' === elementSettings.pagination) {
			elementSettings.pagination = 'progressbar';
		}

		var swiperOptions = {
			grabCursor: true,
			touchThreshold: 100,
			initialSlide: this.getInitialSlide(),
			centeredSlides: elementSettings.centered_slides,
			slidesPerView: this.getDesktopSlidesPerView(),
			slidesPerGroup: this.getDesktopSlidesToScroll(),
			spaceBetween: this.getSpaceBetween(),
			loop: 'yes' === elementSettings.loop,
			speed: elementSettings.speed,
			effect: this.getEffect(),
			preventClicksPropagation: false,
			slideToClickedSlide: true,
			handleElementorBreakpoints: true,
			autoplay: {
				enabled: !this.isEdit && elementSettings.autoplay,
				delay: elementSettings.autoplay_speed,
				disableOnInteraction: !!elementSettings.pause_on_interaction
			}
		};

		if (elementSettings.show_arrows) {
			swiperOptions.navigation = {
				prevEl: '.elementor-swiper-button-prev',
				nextEl: '.elementor-swiper-button-next'
			};
		}

		if (elementSettings.pagination) {
			swiperOptions.pagination = {
				el: '.swiper-pagination',
				type: elementSettings.pagination,
				clickable: true
			};
		}

		if ('cube' !== this.getEffect()) {
			var breakpointsSettings = {},
					breakpoints = ceFrontend.config.breakpoints;
			breakpointsSettings[breakpoints.lg] = {
				slidesPerView: this.getTabletSlidesPerView(),
				slidesPerGroup: this.getTabletSlidesToScroll(),
				spaceBetween: this.getSpaceBetween('tablet')
			};
			breakpointsSettings[breakpoints.md] = {
				slidesPerView: this.getMobileSlidesPerView(),
				slidesPerGroup: this.getMobileSlidesToScroll(),
				spaceBetween: this.getSpaceBetween('mobile')
			};
			swiperOptions.breakpoints = breakpointsSettings;
		}

		return swiperOptions;
	},
	updateSpaceBetween: function (swiper, propertyName) {
		var deviceMatch = propertyName.match('space_between_(.*)'),
				device = deviceMatch ? deviceMatch[1] : 'desktop',
				newSpaceBetween = this[propertyName.indexOf('thumb') === 0 ? 'getThumbSpaceBetween' : 'getSpaceBetween'](device),
				breakpoints = ceFrontend.config.breakpoints;

		if ('desktop' !== device) {
			var breakpointDictionary = {
				tablet: breakpoints.lg,
				mobile: breakpoints.md
			};
			swiper.params.breakpoints[breakpointDictionary[device]].spaceBetween = newSpaceBetween;
		} else {
			swiper.originalParams.spaceBetween = newSpaceBetween;
		}

		swiper.params.spaceBetween = newSpaceBetween;
		swiper.update();
	},
	onInit: function () {
		var _this = this;

		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
		var elementSettings = this.getElementSettings();
		this.swipers = {};

		if (1 >= this.getSlidesCount()) {
			return;
		}

		this.swipers.main = new ceFrontend.utils.swiper(this.elements.$mainSwiper, this.getSwiperOptions()); // Expose the swiper instance in the frontend

		this.elements.$mainSwiper.data('swiper', this.swipers.main);

		if (elementSettings.pause_on_hover) {
			this.elements.$mainSwiper.on({
				mouseenter: function () {
					_this.swipers.main.autoplay.stop();
				},
				mouseleave: function () {
					_this.swipers.main.autoplay.start();
				}
			});
		}
	},
	onElementChange: function (propertyName) {
		if (1 >= this.getSlidesCount()) {
			return;
		}

		if (0 === propertyName.indexOf('width')) {
			this.swipers.main.update();
		}

		if (0 === propertyName.indexOf('space_between')) {
			this.updateSpaceBetween(this.swipers.main, propertyName);
		}
	},
	onEditSettingsChange: function (propertyName) {
		if (1 >= this.getSlidesCount()) {
			return;
		}

		if ('activeItemIndex' === propertyName) {
			this.swipers.main.slideToLoop(this.getEditSettings('activeItemIndex') - 1);
		}
	}
});

/***/ }),
/* 669 */
/***/ (function(module, exports, __webpack_require__) {

var ProductImagesHandle, Base = __webpack_require__(668);

ProductImagesHandle = Base.extend({
	slideshowSpecialElementSettings: [
		'slides_per_view',
		'slides_per_view_tablet',
		'slides_per_view_mobile'
	],
	isSlideshow: function () {
		return 'slideshow' === this.getElementSettings('skin');
	},
	getDefaultSettings: function () {
		var defaultSettings = Base.prototype.getDefaultSettings.apply(this, arguments);

		if (this.isSlideshow()) {
			defaultSettings.selectors.thumbsSwiper = '.elementor-thumbnails-swiper';

			defaultSettings.slidesPerView = {
				desktop: this.$element.hasClass('elementor-position-bottom') ? 5 : 4,
				tablet: 4,
				mobile: 3
			};
		}

		return defaultSettings;
	},
	getElementSettings: function (setting) {
		if (-1 !== this.slideshowSpecialElementSettings.indexOf(setting) && this.isSlideshow()) {
			setting = 'slideshow_' + setting;
		}

		return Base.prototype.getElementSettings.call(this, setting);
	},
	getDefaultElements: function () {
		var selectors = this.getSettings('selectors'),
			defaultElements = Base.prototype.getDefaultElements.apply(this, arguments);

		if (this.isSlideshow()) {
			defaultElements.$thumbsSwiper = this.$element.find(selectors.thumbsSwiper);
		}

		return defaultElements;
	},
	getSlidesPerView: function (device) {
		if (this.isSlideshow()) {
			return 1;
		}

		if ('coverflow' === this.getEffect()) {
			return this.getDeviceSlidesPerView(device);
		}

		return Base.prototype.getSlidesPerView.apply(this, arguments);
	},
	getThumbSpaceBetween: function (device) {
		var propertyName = 'thumb_space_between';

		if (device && 'desktop' !== device) {
			propertyName += '_' + device;
		}

		return this.getElementSettings(propertyName).size || 0;
	},
	getSwiperOptions: function () {
		var options = Base.prototype.getSwiperOptions.apply(this, arguments);

		if (this.isSlideshow()) {
			options.loopedSlides = this.getSlidesCount();

			delete options.pagination;
			delete options.breakpoints;
		}

		return options;
	},
	onInit: function () {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

		this.swipers = {};

		var slidesCount = this.getSlidesCount();

		if (!slidesCount) {
			return;
		}

		var mainSliderOptions = this.getSwiperOptions();

		if (this.isSlideshow()) {
			var elementSettings = this.getElementSettings(),
				breakpointsSettings = {},
				breakpoints = ceFrontend.config.breakpoints,
				slidesPerView = this.getSettings('slidesPerView');

			breakpointsSettings[breakpoints.lg] = {
				slidesPerView: +elementSettings.slides_per_view_tablet || slidesPerView.tablet,
			};
			breakpointsSettings[breakpoints.md] = {
				slidesPerView: +elementSettings.slides_per_view_mobile || slidesPerView.mobile,
			};

			if (elementSettings['thumb_space_between_tablet'] && 'number' === typeof elementSettings['thumb_space_between_tablet'].size) {
				breakpointsSettings[breakpoints.lg].spaceBetween = elementSettings['thumb_space_between_tablet'].size;
			}
			if (elementSettings['thumb_space_between_mobile'] && 'number' === typeof elementSettings['thumb_space_between_mobile'].size) {
				breakpointsSettings[breakpoints.md].spaceBetween = elementSettings['thumb_space_between_mobile'].size;
			}

			var thumbsSliderOptions = {
				autoplay: {enabled: false},
				slidesPerView: +elementSettings.slides_per_view || slidesPerView.desktop,
				initialSlide: this.getInitialSlide(),
				spaceBetween: this.getThumbSpaceBetween(),
				threshold: 2,
				watchSlidesProgress: true,
				breakpoints: breakpointsSettings,
				handleElementorBreakpoints: true,
				direction: 'bottom' === elementSettings.position ? 'horizontal' : 'vertical',
				scrollbar: {
					el: '.swiper-scrollbar',
					hide: true,
					draggable: true,
				},
				mousewheel: {
					enabled: true,
					forceToAxis: true,
				},
			};

			var thumbs = this.swipers.thumbs = new ceFrontend.utils.swiper(this.elements.$thumbsSwiper, thumbsSliderOptions);

			mainSliderOptions.thumbs = {
				swiper: this.swipers.thumbs,
			};
			mainSliderOptions.on = {
				sliderFirstMove: function () {
					this.zoom.out();
				},
			};

			this.elements.$mainSwiper
				.on('mouseenter.ceZoom', '.swiper-zoom-container', this.onZoomIn.bind(this))
				.on('mouseleave.ceZoom', '.swiper-zoom-container', this.onZoomOut.bind(this))
			;
		} else if ('coverflow' === this.getEffect()) {
			mainSliderOptions.centeredSlides = true;
		}

		this.swipers.main = new ceFrontend.utils.swiper(this.elements.$mainSwiper, mainSliderOptions);
	},
	onZoomIn: function () {
		var data = this.swipers.main.touchEventsData;

		if (data.isMoved || Date.now() - data.touchStartTime < 200) {
			return;
		}

		this.swipers.main.zoom.in();

		this.elements.$mainSwiper.on('mousemove.ceZoom', '.swiper-zoom-container img', function (e) {
			var r = this.parentNode.getBoundingClientRect(),
				x = (100 * (e.clientX - r.left) / r.width).toFixed(3),
				y = (100 * (e.clientY - r.top) / r.height).toFixed(3);

			this.style.transformOrigin = x + '% ' + y + '%';
			this.style.transitionDuration = '0s';
		});
	},
	onZoomOut: function () {
		this.swipers.main.zoom.out();

		this.elements.$mainSwiper.off('mousemove.ceZoom')
	},
	onElementChange: function (propertyName) {
		if (1 >= this.getSlidesCount()) {
			return;
		}

		if (!this.isSlideshow()) {
			return Base.prototype.onElementChange.apply(this, arguments);
		}

		if (0 === propertyName.indexOf('thumb_space_between')) {
			return this.updateSpaceBetween(this.swipers.thumbs, propertyName);
		}

		if (/^width|^slideshow_(width|height)/.test(propertyName)) {
			this.swipers.main.update();
			this.swipers.thumbs.update();
		}
	}
});

module.exports = function ($scope) {
	new ProductImagesHandle({$element: $scope});
};

/***/ }),
/* 670 */
/***/ (function(module, exports, __webpack_require__) {

var ListingFiltersHandler = elementorModules.frontend.handlers.Base.extend({

	getDefaultSettings: function () {
		return {
			selectors: {
				container: '.ce-filters__container',
				closeButton: '.dialog-lightbox-close-button',
			},
			classes: {
				isShown: 'ce-filters--shown',
				lightbox: 'elementor-lightbox',
			}
		};
	},

	getDefaultElements: function () {
		var selectors = this.getSettings('selectors'),
			elements = {};

		elements.$container = this.$element.find(selectors.container);
		elements.$closeButton = this.$element.find(selectors.closeButton);

		return elements;
	},

	bindEvents: function () {
		var self = this,
			$container = self.elements.$container,
			$closeButton = self.elements.$closeButton,
			classes = this.getSettings('classes');

		// Deactivate sidebar mode on click or on esc.
		$container.on('click', function (event) {
			if ($container.hasClass(classes.isShown) && $container[0] === event.target) {
				$container.removeClass(classes.isShown);
			}
		});

		$closeButton.on('click', function () {
			$container.removeClass(classes.isShown);
		});

		ceFrontend.elements.$document.keyup(function (event) {
			if (27 === event.keyCode) {
				$container.removeClass(classes.isShown);
			}
		});

		$container.on('keyup', '[tabindex]', function (event) {
			if (13 === event.keyCode || 32 === event.keyCode) {
				this.click();
			}
		}).on('keydown', function (event) {
			32 === event.keyCode && event.preventDefault();
		});

		$container.on('click', '.ce-filters__tab', function onClickFiltersTab() {
			$(this).next().slideToggle({
				duration: 200,
				start: function flexFix(anim) {
					this.style.display = 'flex';
				}
			});
			$(this).toggleClass('elementor-active');
		});

		$container.on('click', '.elementor-field-option', function onChangeInput() {
			this.children[0].checked ^= 1;
		});

		$container.on('change', 'select', function onChangeSelect() {
			prestashop.emit('updateFacets', this.children[this.selectedIndex].dataset.url);
		});

		$container.on('change', '.ce-dual-range', function onChangeDualRange() {
			var data = $(this).data(),
					filter = [data.label.replace(/\s+/g, '+'), data.unit, this.children[1].value, this.children[2].value].join('-'),
					url = data.url,
					q = url.match(/[?&]q=[^&]+/);
			if (q) {
				url = url.replace(q[0], q[0] + '/' + filter);
			} else {
				url += (~url.indexOf('?') ? '&' : '?') + 'q=' + filter;
			}
			prestashop.emit('updateFacets', url);
		}).on('input', '.ce-dual-range__min', function onInputDualRangeMin() {
			if (+this.value > +this.nextElementSibling.value) {
				this.value = this.nextElementSibling.value;
			}
			var data = $(this.parentNode).data(),
				formattedNumber = ceFrontend.utils.numberFormatter.format(this.value, data.specifications);
			this.parentNode.children[0].style.left = (this.value - this.min) / (this.max - this.min) * 100 + '%';
			this.style.zIndex = 1;
			this.parentNode.nextElementSibling.children[0].innerHTML = data.specifications.currencySymbol ? formattedNumber : formattedNumber + ' ' + data.unit;
		}).on('input', '.ce-dual-range__max', function onInputDualRangeMax() {
			if (+this.value < +this.previousElementSibling.value) {
				this.value = this.previousElementSibling.value;
			}
			var data = $(this.parentNode).data(),
				formattedNumber = ceFrontend.utils.numberFormatter.format(this.value, data.specifications);
			this.parentNode.children[0].style.right = (this.max - this.value) / (this.max - this.min) * 100 + '%';
			this.previousElementSibling.style.zIndex = '';
			this.parentNode.nextElementSibling.children[1].innerHTML = data.specifications.currencySymbol ? formattedNumber : formattedNumber + ' ' + data.unit;
		});

		prestashop.once('updateFacets', function onceUpdateFacets() {
			$(document).one('ajaxSend', function onUpdateFacetsAjaxSend(event, xhr) {
				xhr.setRequestHeader(
					'X-CE-Filters-' + self.$element.data('id'),
					JSON.stringify({
						sidebar_shown: $container.hasClass(classes.isShown),
						tabs_active: self.getElementSettings('remember_state')
							? $container.find('.elementor-active[data-type]').get().map(el => el.dataset.type)
							: [],
					})
				);
			});
		});
		prestashop._events.updateFacets.length && prestashop._events.updateFacets.unshift(prestashop._events.updateFacets.pop());
	},
});

module.exports = function ($scope) {
	new ListingFiltersHandler({$element: $scope});
};

/***/ }),
/* 671 */
/***/ (function(module, exports, __webpack_require__) {

var Scroll = __webpack_require__(398).default;

var ListingPaginationHandler = elementorModules.frontend.handlers.Base.extend({

	getDefaultSettings: function () {
		return {
			selectors: {
				loadMore: '.ce-load-more',
				autoLoad: '.ce-auto-load',
			},
			classes: {
				hideFacetedOverlay: 'ce-faceted-overlay--hide',
			}
		};
	},

	getDefaultElements: function () {
		var selectors = this.getSettings('selectors'),
			elements = {};

		elements.$loadMore = this.$element.find(selectors.loadMore);
		elements.$autoLoad = this.$element.find(selectors.autoLoad);

		return elements;
	},

	bindEvents: function () {
		if (ceFrontend.isEditMode()) {
			return;
		}
		var self = this;

		self.elements.$loadMore.on('click', function onClickLoadMore(event) {
			event.preventDefault();

			$(this).addClass('elementor-active');

			self.updateFacets(this.href, 'loadmore');
		});

		self.elements.$autoLoad[0] && setTimeout(() => {
			self.intersectionObserver = Scroll.scrollObserver({
				callback: event => {
					if (event.isInViewport) {
						self.intersectionObserver.unobserve(self.elements.$autoLoad[0]);
						self.updateFacets(self.elements.$autoLoad[0].href, 'autoload');
					}
				}
			});
			self.intersectionObserver.observe(self.elements.$autoLoad[0]);

			prestashop.once('updateFacets', function () {
				self.intersectionObserver.unobserve(self.elements.$autoLoad[0]);
			});
		});
	},

	updateFacets: function (url, loadType) {
		var $productList = $('#js-product-list');
		if ($productList.length) {
			$productList.attr('id', 'ce-product-list');
			$(document.body).addClass(this.getSettings('classes').hideFacetedOverlay);

			loadType && $(document).one('ajaxSend', function onUpdateFacetsAjaxSend(event, xhr) {
				xhr.setRequestHeader('X-CE-Pagination', loadType);
			});
			prestashop.emit('updateFacets', url);
		}
	},
});

module.exports = function ($scope) {
	new ListingPaginationHandler({$element: $scope});
};

/***/ })

/******/ ]);

window.prestashop && prestashop.on('updateProductList', function onUpdateProductList(data) {
	data.rendered_widgets && Object.keys(data.rendered_widgets).forEach(id => {
		var $widgetContainer = $('.elementor-element-' + id + ' > .elementor-widget-container');
		if ($widgetContainer[0]) {
			var widget = $widgetContainer[0].parentNode;

			$widgetContainer.replaceWith(data.rendered_widgets[id]);
			ceFrontend.elementsHandler.runReadyTrigger(widget);
		}
	});
	var $productList = $('#ce-product-list'),
			listingData = $('.elementor[data-elementor-type^=listing]').data('elementorSettings');
	if ($productList.length || listingData) {
		var scrollTo = window.scrollTo;
		window.scrollTo = $.noop;

		var products = $productList.children()[0];
		products && $(data.rendered_products).find('>:first>*').appendTo(products);

		setTimeout(() => {
			window.scrollTo = scrollTo;

			if ($productList.length) {
				$productList.attr('id', 'js-product-list');
			} else if ('none' !== listingData.scroll_up) {
				var y = 'top' === listingData.scroll_up ? 0 : $('#' + listingData.scroll_id).offset().top + ceFrontend.getCurrentDeviceSetting(listingData, 'scroll_offset');
				window.scrollY > y && window.scrollTo(0, y);
			}
		});
	}
	$(document.body).removeClass('ce-faceted-overlay--hide');
	$('.ce-load-more.elementor-active').removeClass('elementor-active');
});

window.prestashop && prestashop.on('updateCart', function onUpdateCart(data) {
	if (data.resp && data.resp.hasError) {
		return window.WishlistEventBus && WishlistEventBus.$emit('showToast', {
			detail: {
				type: 'error',
				message: data.resp.errors.join(' '),
			},
		}) || alert(data.resp.errors.join("\n"));
	}
	if (data.resp && data.resp.success) {
		var input = $('#add-to-cart-or-refresh > [name=id_product][value=' + data.resp.id_product + ']')[0];

		input && ceFrontend.refreshProduct(input.form);
	}
});

ceFrontend.refreshProduct = function (form, quickview) {
	if (!$(form).nextAll('.elementor').length) {
		return;
	}
	var productUrl = window.prestashop && prestashop.urls.pages.product || elementor.config.document.urls.permalink,
			formData = new FormData(form);
	quickview && formData.set('quickview', quickview);
	formData.set('quantity_wanted', formData.get('qty'));
	formData.delete('qty');
	formData.set('ajax', 1);

	this.refreshProduct.xhr && this.refreshProduct.xhr.readyState !== 4 && this.refreshProduct.xhr.abort();

	this.refreshProduct.xhr = $.ajax(productUrl + '&action=refresh&id_product=' + formData.get('id_product'), {
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		dataType: 'json',
		success: function (data) {
			var $content = $(data.product_content);

			// Refresh Widgets
			$content.find('.elementor-widget:not(.ce-skip-refresh) > .elementor-widget-container').each(function () {
				var id = $(this.parentNode).data('id'),
						widgetContainer = $('.elementor-element-' + id + ' > .elementor-widget-container')[0];
				if (widgetContainer) {
					$(widgetContainer).replaceWith(this);
					ceFrontend.elementsHandler.runReadyTrigger(this.parentNode);
				}
			});

			// Refresh Quantity
			$(form.elements.qty).attr('min', data.product_minimal_quantity).each(function () {
				if (this.value < data.product_minimal_quantity) {
					this.value = data.product_minimal_quantity;
				}
			});

			+data.is_quick_view || prestashop.emit('updatedProduct', data);
		}
	});
};

$('html').on('click.ce', '.elementor-nav-tabs a', function onClickNavTab(e) {
	e.preventDefault();

	if (~this.className.indexOf('elementor-item-active')) {
		return;
	}
	var index = $(this.parentNode).index(),
		$col = $(this).closest('.elementor-container').find('> .elementor-row > .elementor-column').eq(index),
		editMode = ceFrontend.isEditMode();

	$(this).closest('.elementor-nav').find('.elementor-item-active').removeClass('elementor-item-active');
	$(this).addClass('elementor-item-active');
	$col.addClass('elementor-active').siblings().removeClass('elementor-active');

	$col.find('.animated').addBack('.animated').each(function () {
		var $this = $(this),
			settings = editMode ? elementor.helpers.getModelById($this.data('id')).get('settings').attributes : $this.data('settings') || {},
			animation = $this.hasClass('elementor-widget') ? '_animation' : 'animation';

		$this.addClass('elementor-invisible').removeClass(ceFrontend.getCurrentDeviceSetting(settings, animation));

		setTimeout(function () {
			$this.removeClass([
				settings[animation + '_mobile'] || '',
				settings[animation + '_tablet'] || '',
				settings[animation] || ''
			].join(' '));
		});
		setTimeout(function () {
			$this.removeClass('elementor-invisible').addClass(ceFrontend.getCurrentDeviceSetting(settings, animation));
		}, settings[animation + '_delay'] || 0)
	});
}).on('keydown.ce', '.elementor-field[name=qty]', function onEnterDownQuantity(e) {
	if (13 === e.keyCode) {
		if (parseInt(this.value, 10) >= parseInt(this.min, 10)) {
			return;
		}
		e.preventDefault();
	}
}).on('keyup.ce', '.elementor-field[name=qty]', function onEnterUpQuantity(e) {
	if (13 === e.keyCode) {
		if (parseInt(this.value, 10) >= parseInt(this.min, 10)) {
			this.blur();

			ceFrontend.utils.urlActions.actions.closeLightbox();
		}
	}
}).on('click.ce', '.ce-add-to-wishlist', function onClickAddToWishlist(e) {
	e.preventDefault();

	if (!window.WishlistEventBus) {
		return alert('Please install & enable the Wishlist module!');
	}
	if (!prestashop.customer.is_logged) {
		return WishlistEventBus.$emit('showLogin');
	}
	var $this = $(this);

	if (!$this.hasClass('elementor-active')) {
		WishlistEventBus.$emit('showAddToWishList', {
			detail: {
				forceOpen: true,
				productId: $this.data('productId'),
				productAttributeId: $this.data('productAttributeId'),
			},
		});
	} else {
		var product = productsAlreadyTagged.find(function (tagged) {
			return tagged.id_product == $this.data('productId')
				&& tagged.id_product_attribute == $this.data('productAttributeId');
		});
		product && $.post(this.href, {
			action: 'deleteProductFromWishlist',
			params: {
				idWishList: product.id_wishlist,
				id_product: product.id_product,
				id_product_attribute: product.id_product_attribute,
			},
		}, function onSuccessDeleteProductFromWishlist(response) {
			$('.ce-add-to-wishlist' +
				'[data-product-id=' + product.id_product + ']' +
				'[data-product-attribute-id=' + product.id_product_attribute + ']'
			).removeClass('elementor-active').find('i').attr('class', 'ceicon-heart-o');

			productsAlreadyTagged = productsAlreadyTagged.filter(function (tagged) {
				return tagged.id_product != product.id_product
					&& tagged.id_product_attribute != product.id_product_attribute;
			});
			WishlistEventBus.$emit('showToast', {
				detail: {
					type: response.success ? 'success' : 'error',
					message: response.message,
				},
			});
		}, 'json');
	}
}).on('click.ce-comments', 'a[href="#product-comments-list-header"]', function (e) {
	var $comments = $('#product-comments-list-header'),
		$section = $comments.closest('.elementor-section-tabbed');
	if ($section.length) {
		var column = $section.find('> .elementor-container > .elementor-row > .elementor-column').toArray().find(function (el) {
			return $(el).find($comments).length;
		});
		$section.find('> .elementor-container > .elementor-nav-tabs a').eq($(column).index()).click();
	}
	$('html, body').animate({
		scrollTop: $($section[0] || $comments[0]).offset().top
	}, 500, 'swing', $(this).hasClass('elementor-button--post-comment') ? function() {
		$('.post-product-comment').click();
	} : void 0);
	e.preventDefault();
}).on('change.ce', '[form="add-to-cart-or-refresh"]', function (e) {
	// Refresh Product on change
	ceFrontend.refreshProduct(this.form, $(this).closest('#ce-product-quick-view').length);
}).on('input.ce', '[form="add-to-cart-or-refresh"][name=qty]', function () {
	// Update Product on input
	clearTimeout(ceFrontend.refreshProduct.timeout);

	if ('' !== this.value) ceFrontend.refreshProduct.timeout = setTimeout(function () {
		ceFrontend.refreshProduct(this.form, $(this).closest('#ce-product-quick-view').length);
	}.bind(this), 200);
});

$(function ceReady() {
	// Fix for category description
	$('#js-product-list-header').attr('id', 'product-list-header');

	// Init product quick view
	if (ceFrontend.config.productQuickView) {
		delete prestashop._events.clickQuickView;

		prestashop.on('clickQuickView', function onClickQuickView(e) {
			onClickQuickView.xhr && onClickQuickView.xhr.abort();

			onClickQuickView.xhr = $.post(prestashop.urls.pages.product, {
				ajax: 1,
				action: 'quickview',
				id_product: e.dataset.idProduct,
				id_product_attribute: e.dataset.idProductAttribute,
				id_ce_theme: ceFrontend.config.productQuickView
			}, null, 'json');

			onClickQuickView.xhr.then(function onSuccessQuickView(resp) {
				var lightbox = ceFrontend.utils.lightbox,
					$html = $(resp.quickview_html),
					settings = $html.filter('.elementor').data('elementorSettings'),
					modal = lightbox.getModal(),
					elem = modal.getElements();

				elem.message.removeClass([
					'zoomIn',
					settings.entrance_animation || '',
					settings.entrance_animation_tablet || '',
					settings.entrance_animation_mobile || ''
				].join(' ')).addClass(
					ceFrontend.getCurrentDeviceSetting(settings, 'entrance_animation')
				);

				$('[form="add-to-cart-or-refresh"]').attr('form', 'ce-add-to-cart-or-refresh');
				$('#add-to-cart-or-refresh').attr('id', 'ce-add-to-cart-or-refresh');

				lightbox.showModal({
					modalOptions: {id: 'ce-product-quick-view'},
					html: $html
				});

				modal.off('hide').on('hide', function onHideQuickView() {
					$('[form="ce-add-to-cart-or-refresh"]').attr('form', 'add-to-cart-or-refresh');
					$('#ce-add-to-cart-or-refresh').attr('id', 'add-to-cart-or-refresh');

					setTimeout(function () {
						elem.closeButton.prependTo(elem.widgetContent);
						elem.message.removeClass([
							settings.entrance_animation || '',
							settings.entrance_animation_tablet || '',
							settings.entrance_animation_mobile || ''
						].join(' '));

						modal.setMessage('');
					}, 400);
				});

				elem.message.addClass('elementor-lightbox-prevent-close')
					.prepend('outside' === settings.close_button_position ? null : elem.closeButton)
					.find('.elementor-widget').each(function () {
						ceFrontend.elementsHandler.runReadyTrigger(this);
					})
				;
			}).fail(function (resp) {
				prestashop.emit('handleError', {eventType: 'clickQuickView', resp: resp});
			});
		});
		$(document.body).on('click.ce', '[data-link-action=quickview]', function overrideQuickView(e) {
			e.preventDefault();
			e.stopPropagation();

			ceFrontend.utils.urlActions.actions.quickview({
				id_product: $(this).closest('[data-id-product]').data('idProduct')
			});
		});
	}
	// handle Added to Wishlist
	window.WishlistEventBus && WishlistEventBus.$on('addedToWishlist', function onAddedToWishlist(e) {
		var product = productsAlreadyTagged[productsAlreadyTagged.length - 1];
		$('.ce-add-to-wishlist' +
			'[data-product-id=' + product.id_product + ']' +
			'[data-product-attribute-id=' + product.id_product_attribute + ']'
		).addClass('elementor-active').find('i').attr('class', 'ceicon-heart');
	});
});