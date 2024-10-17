/*
 * By Elementor & WebshopWorks Team
 */
( function( $ ) {
	var Sticky = function( element, userSettings ) {
		var $element,
			isSticky = false,
			isFollowingParent = false,
			isReachedEffectsPoint = false,
			prevPageYOffset = pageYOffset,
			resizeObserver,
			elements = {},
			settings;

		var defaultSettings = {
			to: 'top',
			offset: 0,
			effectsOffset: 0,
			parent: false,
			classes: {
				sticky: 'sticky',
				stickyActive: 'sticky-active',
				stickyEffects: 'sticky-effects',
				spacer: 'sticky-spacer',
			},
		};

		var initElements = function() {
			$element = $( element ).addClass( settings.classes.sticky );

			elements.$window = $( window );

			if ( settings.parent ) {
				if ( 'parent' === settings.parent ) {
					elements.$parent = $element.parent();
				} else {
					elements.$parent = $element.closest( settings.parent );
				}
			}
		};

		var initSettings = function() {
			settings = jQuery.extend( true, defaultSettings, userSettings );
		};

		var bindEvents = function() {
			elements.$window.on( {
				// resize: onWindowResize,
				scroll: onWindowScroll,
			} );
			resizeObserver = new ResizeObserver(onWindowResize);
			resizeObserver.observe(jQuery('body > main')[0] || document.body);
		};

		var unbindEvents = function() {
			elements.$window
				// .off( 'resize', onWindowResize )
				.off( 'scroll', onWindowScroll );
			resizeObserver.disconnect();
		};

		var init = function() {
			initSettings();

			initElements();

			bindEvents();

			checkPosition();
		};

		var backupCSS = function( $elementBackupCSS, backupState, properties ) {
			var css = {},
				elementStyle = $elementBackupCSS[ 0 ].style;

			properties.forEach( function( property ) {
				css[ property ] = undefined !== elementStyle[ property ] ? elementStyle[ property ] : '';
			} );

			$elementBackupCSS.data( 'css-backup-' + backupState, css );
		};

		var getCSSBackup = function( $elementCSSBackup, backupState ) {
			return $elementCSSBackup.data( 'css-backup-' + backupState );
		};

		var addSpacer = function() {
			elements.$spacer = $element.clone()
				.addClass( settings.classes.spacer )
				.css( {
					visibility: 'hidden',
					transition: 'none',
					animation: 'none',
				} );

			$element.after( elements.$spacer );
		};

		var removeSpacer = function() {
			elements.$spacer.remove();
		};

		var getOffset = function() {
			var offset = settings.offset;

			settings.$offset.length && settings.$offset.each(function() {
				offset += this.offsetHeight;
			});

			return offset;
		};

		var stickElement = function() {
			backupCSS( $element, 'unsticky', [ 'position', 'width', 'margin-top', 'margin-bottom', 'top', 'bottom' ] );

			var css = {
				position: 'fixed',
				width: getElementOuterSize( $element, 'width' ),
				marginTop: 0,
				marginBottom: 0,
			};

			css[ settings.to ] = getOffset();

			css[ 'top' === settings.to ? 'bottom' : 'top' ] = '';

			$element
				.css( css )
				.addClass( settings.classes.stickyActive );
		};

		var unstickElement = function() {
			$element
				.css( getCSSBackup( $element, 'unsticky' ) )
				.removeClass( settings.classes.stickyActive );
		};

		var followParent = function() {
			backupCSS( elements.$parent, 'childNotFollowing', [ 'position' ] );

			elements.$parent.css( 'position', 'relative' );

			backupCSS( $element, 'notFollowing', [ 'position', 'top', 'bottom' ] );

			var css = {
				position: 'absolute',
			};

			css[ settings.to ] = '';

			css[ 'top' === settings.to ? 'bottom' : 'top' ] = 0;

			$element.css( css );

			isFollowingParent = true;
		};

		var unfollowParent = function() {
			elements.$parent.css( getCSSBackup( elements.$parent, 'childNotFollowing' ) );

			$element.css( getCSSBackup( $element, 'notFollowing' ) );

			isFollowingParent = false;
		};

		var getElementOuterSize = function( $elementOuterSize, dimension, includeMargins ) {
			var computedStyle = getComputedStyle( $elementOuterSize[ 0 ] ),
				elementSize = parseFloat( computedStyle[ dimension ] ),
				sides = 'height' === dimension ? [ 'top', 'bottom' ] : [ 'left', 'right' ],
				propertiesToAdd = [];

			if ( 'border-box' !== computedStyle.boxSizing ) {
				propertiesToAdd.push( 'border', 'padding' );
			}

			if ( includeMargins ) {
				propertiesToAdd.push( 'margin' );
			}

			propertiesToAdd.forEach( function( property ) {
				sides.forEach( function( side ) {
					elementSize += parseFloat( computedStyle[ property + '-' + side ] );
				} );
			} );

			return elementSize;
		};

		var getElementViewportOffset = function( $elementViewportOffset ) {
			var windowScrollTop = elements.$window.scrollTop(),
				elementHeight = getElementOuterSize( $elementViewportOffset, 'height' ),
				viewportHeight = innerHeight,
				elementOffsetFromTop = $elementViewportOffset.offset().top,
				distanceFromTop = elementOffsetFromTop - windowScrollTop,
				topFromBottom = distanceFromTop - viewportHeight;

			return {
				top: {
					fromTop: distanceFromTop,
					fromBottom: topFromBottom,
				},
				bottom: {
					fromTop: distanceFromTop + elementHeight,
					fromBottom: topFromBottom + elementHeight,
				},
			};
		};

		var stick = function() {
			addSpacer();

			stickElement();

			isSticky = true;

			// fix: restore checked property
			$element.find('input[checked]').prop('checked', true);

			$element.trigger( 'sticky:stick' );
		};

		var unstick = function() {
			unstickElement();

			removeSpacer();

			isSticky = false;

			$element.trigger( 'sticky:unstick' );
		};

		var checkParent = function() {
			var elementOffset = getElementViewportOffset( $element ),
				isTop = 'top' === settings.to;

			if ( isFollowingParent ) {
				var offset = getOffset(),
					isNeedUnfollowing = isTop ? elementOffset.top.fromTop > offset : elementOffset.bottom.fromBottom < -offset;

				if ( isNeedUnfollowing ) {
					unfollowParent();
				}
			} else if (elements.$parent[ 0 ]) {
				var parentOffset = getElementViewportOffset( elements.$parent ),
					parentStyle = getComputedStyle( elements.$parent[ 0 ] ),
					borderWidthToDecrease = parseFloat( parentStyle[ isTop ? 'borderBottomWidth' : 'borderTopWidth' ] ),
					parentViewportDistance = isTop ? parentOffset.bottom.fromTop - borderWidthToDecrease : parentOffset.top.fromBottom + borderWidthToDecrease,
					isNeedFollowing = isTop ? parentViewportDistance <= elementOffset.bottom.fromTop : parentViewportDistance >= elementOffset.top.fromBottom;

				if ( isNeedFollowing ) {
					followParent();
				}
			}
		};

		var checkEffectsPoint = function( distanceFromTriggerPoint ) {
			if ( isReachedEffectsPoint && -distanceFromTriggerPoint < settings.effectsOffset ) {
				$element.removeClass( settings.classes.stickyEffects );

				isReachedEffectsPoint = false;
			} else if ( ! isReachedEffectsPoint && -distanceFromTriggerPoint >= settings.effectsOffset ) {
				$element.addClass( settings.classes.stickyEffects );

				isReachedEffectsPoint = true;
			}
		};

		var checkPosition = function() {
			var offset = getOffset(),
				distanceFromTriggerPoint;

			if ( isSticky ) {
				var spacerViewportOffset = getElementViewportOffset( elements.$spacer );

				distanceFromTriggerPoint = 'top' === settings.to ? spacerViewportOffset.top.fromTop - offset : -spacerViewportOffset.bottom.fromBottom - offset;

				if ( settings.parent ) {
					checkParent();
				}

				if ( distanceFromTriggerPoint > 0 ) {
					unstick();
				}
			} else {
				var elementViewportOffset = getElementViewportOffset( $element );

				distanceFromTriggerPoint = 'top' === settings.to ? elementViewportOffset.top.fromTop - offset : -elementViewportOffset.bottom.fromBottom - offset;

				if ( distanceFromTriggerPoint <= 0 ) {
					stick();

					if ( settings.parent ) {
						checkParent();
					}
				}
			}

			checkEffectsPoint( distanceFromTriggerPoint );
		};

		var onWindowScroll = function() {
		    if (isSticky && settings.autoHide) {
			    var t = 10, // tolerance
				    autoHideOffset = settings.autoHideOffset.size * ('vh' === settings.autoHideOffset.unit ? $(window).height() / 100 : 1);

			    if (pageYOffset >= autoHideOffset && pageYOffset > prevPageYOffset + t && !$element.find('.elementor-cart--shown, .elementor-search--topbar').length) {
				    $element
					    .off('transitionend.ceSticky')
					    .css({
						    transition: 'transform ' + settings.autoHideDuration.size + 's',
						    transform: 'translateY(calc(-100% - ' + getOffset() + 'px))'
					    })
					    .addClass(settings.classes.stickyHide);
			    }
			    if (pageYOffset < autoHideOffset || pageYOffset < prevPageYOffset - t) {
				    $element
					    .removeClass(settings.classes.stickyHide)
					    .css('transform', '')
					    .one('transitionend.ceSticky', function () {
						    $element.css('transition', '');
					    });
			    }

			    prevPageYOffset = pageYOffset;
		    }

			checkPosition();
		};

		var onWindowResize = function() {
			if ( ! isSticky ) {
				return;
			}

			unstickElement();

			stickElement();

			if ( settings.parent ) {
				// Force recalculation of the relation between the element and its parent
				isFollowingParent = false;

				checkParent();
			}
		};

		this.destroy = function() {
			if ( isSticky ) {
				unstick();
			}

			unbindEvents();

		    $element.removeClass( settings.classes.sticky )
			    .css('transform', '')
			    .off('transitionend.ceSticky')
			    .removeClass(settings.classes.stickyHide);
		};

		init();
	};

	$.fn.sticky = function( settings ) {
		var isCommand = 'string' === typeof settings;

		this.each( function() {
			var $this = $( this );

			if ( ! isCommand ) {
				$this.data( 'sticky', new Sticky( this, settings ) );

				return;
			}

			var instance = $this.data( 'sticky' );

			if ( ! instance ) {
				throw Error( 'Trying to perform the `' + settings + '` method prior to initialization' );
			}

			if ( ! instance[ settings ] ) {
				throw ReferenceError( 'Method `' + settings + '` not found in sticky instance' );
			}

			instance[ settings ].apply( instance, Array.prototype.slice.call( arguments, 1 ) );

			if ( 'destroy' === settings ) {
				$this.removeData( 'sticky' );
			}
		} );

		return this;
	};

	window.Sticky = Sticky;
} )( jQuery );
