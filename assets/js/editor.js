(function() {
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var ToggleControl = wp.components.ToggleControl;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var PanelBody = wp.components.PanelBody;

	// Extender el bloque de grupo
	var withGroupExtension = wp.compose.createHigherOrderComponent(function(BlockEdit) {
		return function(props) {
			// Solo aplicar al bloque de grupo
			if (props.name !== 'core/group') {
				return el(BlockEdit, props);
			}

			var isRightAfterHeader = props.attributes.isRightAfterHeader || false;

			var toggleRightAfterHeader = function() {
				props.setAttributes({
					isRightAfterHeader: !isRightAfterHeader
				});
			};

			return el(
				wp.element.Fragment,
				{},
				el(BlockEdit, props),
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{
							title: __('Configuración del grupo', 'bilky'),
							initialOpen: false
						},
						el(ToggleControl, {
							label: __('Primer grupo', 'bilky'),
							checked: isRightAfterHeader,
							onChange: toggleRightAfterHeader,
							help: __('Marca esta opción si este es el primer grupo después del header', 'bilky')
						})
					)
				)
			);
		};
	}, 'withGroupExtension');

	// Registrar la extensión
	wp.hooks.addFilter(
		'editor.BlockEdit',
		'bilky/group-extension',
		withGroupExtension
	);

	// Añadir el atributo al bloque de grupo
	wp.hooks.addFilter(
		'blocks.registerBlockType',
		'bilky/group-attributes',
		function(settings, name) {
			if (name === 'core/group') {
				settings.attributes = Object.assign(settings.attributes || {}, {
					isRightAfterHeader: {
						type: 'boolean',
						default: false
					}
				});
			}
			return settings;
		}
	);

	// Añadir la clase al bloque en el frontend
	wp.hooks.addFilter(
		'blocks.getSaveContent.extraProps',
		'bilky/group-class',
		function(props, block, attributes) {
			if (block.name === 'core/group' && attributes.isRightAfterHeader) {
				var className = props.className || '';
				if (className.indexOf('is-right-after-header') === -1) {
					props.className = className + ' is-right-after-header';
				}
			}
			return props;
		}
	);

	// Añadir la clase en el editor también
	wp.hooks.addFilter(
		'editor.BlockListBlock',
		'bilky/group-class-editor',
		function(BlockListBlock) {
			return function(props) {
				if (props.name === 'core/group' && props.attributes.isRightAfterHeader) {
					props.className = (props.className || '') + ' is-right-after-header';
				}
				return el(BlockListBlock, props);
			};
		}
	);
})();

/**
 * Inicializa Swiper dentro del iframe del lienzo de Gutenberg.
 * Usa el elemento DOM del iframe (no selectores globales) para que coincida con el documento correcto.
 */
(function() {
	'use strict';

	function debounce( fn, ms ) {
		var t;
		return function() {
			var ctx = this;
			var args = arguments;
			clearTimeout( t );
			t = setTimeout( function() {
				fn.apply( ctx, args );
			}, ms );
		};
	}

	function getCanvasDocument() {
		var iframe =
			document.querySelector( 'iframe[name="editor-canvas"]' ) ||
			document.querySelector( '.block-editor__iframe' ) ||
			document.querySelector( '.block-editor__container iframe' ) ||
			document.querySelector( '#editor iframe' );
		if ( iframe && iframe.contentDocument ) {
			return iframe.contentDocument;
		}
		return null;
	}

	function destroySwiperIf( el ) {
		if ( el && el.swiper ) {
			el.swiper.destroy( true, true );
		}
	}

	function initSwipersInDocument( doc ) {
		if ( typeof window.Swiper === 'undefined' || ! doc ) {
			return;
		}

		doc.querySelectorAll( '.mwm-section-12__swiper' ).forEach( function( el ) {
			if ( ! el.id ) {
				return;
			}
			destroySwiperIf( el );
			new window.Swiper( el, {
				loop: true,
				slidesPerView: 'auto',
				spaceBetween: 12,
				speed: 600,
				autoplay: {
					delay: 3000,
					disableOnInteraction: false,
					pauseOnMouseEnter: true,
				},
				allowTouchMove: true,
				grabCursor: true,
				navigation: false,
				pagination: false,
				simulateTouch: true,
				touchRatio: 1,
				touchAngle: 45,
				observer: true,
				observeParents: true,
			} );
		} );

		doc.querySelectorAll( '.mwm-section-05__swiper' ).forEach( function( el ) {
			if ( ! el.id ) {
				return;
			}
			destroySwiperIf( el );
			var wrapper = el.closest( '.mwm-section-05__wrapper' );
			var prevBtn = wrapper ? wrapper.querySelector( '.mwm-section-05__nav-button--prev' ) : null;
			var nextBtn = wrapper ? wrapper.querySelector( '.mwm-section-05__nav-button--next' ) : null;
			var pagEl = el.querySelector( '.swiper-pagination' );
			var opts = {
				loop: true,
				slidesPerView: 1,
				spaceBetween: 12,
				navigation: {
					nextEl: nextBtn || null,
					prevEl: prevBtn || null,
				},
				breakpoints: {
					768: {
						slidesPerView: 2,
						spaceBetween: 12,
					},
					1024: {
						slidesPerView: 3,
						spaceBetween: 12,
					},
					1280: {
						slidesPerView: 4,
						spaceBetween: 12,
					},
				},
				observer: true,
				observeParents: true,
			};
			if ( pagEl ) {
				opts.pagination = { el: pagEl, clickable: true };
			}
			new window.Swiper( el, opts );
		} );

		doc.querySelectorAll( '.mwm-section-08__swiper' ).forEach( function( el ) {
			if ( ! el.id ) {
				return;
			}
			destroySwiperIf( el );
			var pagEl = el.querySelector( '.swiper-pagination' );
			var opts = {
				loop: true,
				slidesPerView: 1,
				spaceBetween: 12,
				breakpoints: {
					768: {
						slidesPerView: 2,
					},
					1280: {
						slidesPerView: 3,
					},
				},
				observer: true,
				observeParents: true,
			};
			if ( pagEl ) {
				opts.pagination = { el: pagEl, clickable: true };
			}
			new window.Swiper( el, opts );
		} );

		doc.querySelectorAll( '.mwm-card-03-group__swiper' ).forEach( function( el ) {
			if ( ! el.id ) {
				return;
			}
			destroySwiperIf( el );
			var wrapper = el.closest( '.mwm-card-03-group__wrapper' );
			var prevBtn = wrapper ? wrapper.querySelector( '.mwm-card-03-group__nav-button--prev' ) : null;
			var nextBtn = wrapper ? wrapper.querySelector( '.mwm-card-03-group__nav-button--next' ) : null;
			new window.Swiper( el, {
				loop: true,
				slidesPerView: 1,
				spaceBetween: 12,
				navigation: {
					nextEl: nextBtn || null,
					prevEl: prevBtn || null,
				},
				breakpoints: {
					768: {
						slidesPerView: 2,
						spaceBetween: 12,
					},
					1280: {
						slidesPerView: 4,
						spaceBetween: 12,
					},
				},
				observer: true,
				observeParents: true,
			} );
		} );
	}

	var runSwiperInEditor = debounce( function() {
		var doc = getCanvasDocument();
		if ( doc && doc.body ) {
			initSwipersInDocument( doc );
		}
	}, 400 );

	function hookEditorSwiper() {
		if ( typeof window.Swiper === 'undefined' ) {
			return;
		}
		runSwiperInEditor();

		if ( window.wp && window.wp.data && typeof window.wp.data.subscribe === 'function' ) {
			window.wp.data.subscribe( runSwiperInEditor );
		}

		var iframeSelectors = [
			'iframe[name="editor-canvas"]',
			'.block-editor__iframe',
			'.block-editor__container iframe',
		];
		iframeSelectors.forEach( function( sel ) {
			var iframe = document.querySelector( sel );
			if ( iframe ) {
				iframe.addEventListener( 'load', function() {
					setTimeout( runSwiperInEditor, 100 );
				} );
			}
		} );

		var pollTimer = setInterval( function() {
			var d = getCanvasDocument();
			if ( d && d.body ) {
				runSwiperInEditor();
				clearInterval( pollTimer );
			}
		}, 200 );
		setTimeout( function() {
			clearInterval( pollTimer );
		}, 15000 );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', hookEditorSwiper );
	} else {
		hookEditorSwiper();
	}
} )();

