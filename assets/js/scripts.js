/*==================================================================
	TABLE OF CONTENTS
====================================================================
	# MWM HEADER
	# MWM GTRANSLATE
	# SWIPER
	# MWM FILTER
	# MWM POPUP
	# MWM VIDEO — POSTER PRIMER FRAME
*/

/*	# MWM HEADER
=============================================== */

var opening;
jQuery(document).ready(function () {

	// OPEN MENU WHEN CLICK ON BARS
	jQuery('.mwm-header__toggle').click(function () {
		var $toggle = jQuery(this);
		var $icon = $toggle.find('i');
		
		opening = false;
		jQuery('body').toggleClass('menu-mobile-opened');
		
		// Toggle icon and color
		if ($icon.hasClass('fa-bars')) {
			$icon.removeClass('fa-bars').addClass('fa-close');
			$toggle.removeClass('mwm-icon-circle--terciary').addClass('mwm-icon-circle--secondary');
		} else {
			$icon.removeClass('fa-close').addClass('fa-bars');
			$toggle.removeClass('mwm-icon-circle--secondary').addClass('mwm-icon-circle--terciary');
		}
		
		setTimeout(function() {
			opening = true;
		}, 500);
	});

	// CREATE ELEMENT TO OPEN MENU ON MOBILE (ARROW)
	jQuery('.menu-item-has-children > a, .page_item_has_children > a').append(
		`<svg class="menu-item__btn" width="13" height="8" viewBox="0 0 13 8" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M5.57422 6.83203L0.324219 1.58203C-0.00390625 1.22656 -0.00390625 0.679688 0.324219 0.324219C0.679688 -0.00390625 1.22656 -0.00390625 1.58203 0.324219L6.20312 4.97266L10.8242 0.324219C11.1797 -0.00390625 11.7266 -0.00390625 12.082 0.324219C12.4102 0.679688 12.4102 1.22656 12.082 1.58203L6.83203 6.83203C6.47656 7.16016 5.92969 7.16016 5.57422 6.83203Z" fill="#051C45"/>
		</svg>`
	);

	// FUNCTION TO CLOSE ALL OPEN MENUS
	function closeAllMenus(excludeElement) {
		// Cerrar menús del header desktop (excepto el que se está abriendo)
		jQuery('.mwm-header .menu-item-has-children.is-open, .mwm-header .page_item_has_children.is-open').each(function() {
			if (!excludeElement || !jQuery(this).is(excludeElement)) {
				jQuery(this).removeClass('is-open');
				jQuery(this).find('.menu-item__btn').removeClass('rotate');
			}
		});
		
		// Cerrar menús del header mobile (excepto el que se está abriendo)
		jQuery('.mwm-header-mobile .menu-item-has-children.is-open, .mwm-header-mobile .page_item_has_children.is-open').each(function() {
			if (!excludeElement || !jQuery(this).is(excludeElement)) {
				jQuery(this).removeClass('is-open');
				jQuery(this).find('.sub-menu, .children').slideUp();
				jQuery(this).find('.menu-item__btn').removeClass('rotate');
			}
		});
	}

	// TOGGLE CLASS ONCE, WHEN CLICKING THE ARROW ON MOBILE MENU
	jQuery(document).on('click', '.mwm-header-mobile .menu-item-has-children > a,.mwm-header-mobile .page_item_has_children > a', function (event) {
		event.stopPropagation();
		event.preventDefault();
		var $parent = jQuery(this).parent();
		var isOpening = !$parent.hasClass('is-open');
		
		// Si se está abriendo, cerrar otros menús primero
		if (isOpening) {
			closeAllMenus($parent);
		}
		
		$parent.toggleClass('is-open');
		$parent.find('.sub-menu, .children').slideToggle();
		$parent.find('.menu-item__btn').toggleClass('rotate');
	});

	// TOGGLE CLASS ONCE, WHEN CLICKING THE ARROW ON DESKTOP MENU
	jQuery(document).on('click', '.mwm-header .menu-item-has-children > a,.mwm-header .page_item_has_children > a', function (event) {
		event.stopPropagation();
		event.preventDefault();
		var $parent = jQuery(this).parent();
		var isOpening = !$parent.hasClass('is-open');
		
		// Si se está abriendo, cerrar otros menús primero
		if (isOpening) {
			closeAllMenus($parent);
		}
		
		$parent.toggleClass('is-open');
		$parent.find('.menu-item__btn').toggleClass('rotate');
	});

	// CLOSE MENUS ON SCROLL
	let scrollTimer = null;
	jQuery(window).on('scroll', function() {
		// Usar debounce para evitar ejecutar demasiadas veces
		clearTimeout(scrollTimer);
		scrollTimer = setTimeout(function() {
			closeAllMenus();
		}, 100);
	});

	// SET HEADER HEIGHT
	const headerHeight = () => {
		const doc = document.documentElement;
		let header = jQuery('.mwm-header').innerHeight();
		doc.style.setProperty('--header-height', `${header}px`);
	};

	window.addEventListener('resize', headerHeight);

	headerHeight();

	// DETECT SCROLL: Add 'is-scrolled' class when main wrapper is out of viewport
	const mainWrapper = document.querySelector('.mwm-header__main-wrapper');
	const header = document.querySelector('.mwm-header');

	if (mainWrapper && header) {
		const observer = new IntersectionObserver((entries) => {
			entries.forEach((entry) => {
				if (!entry.isIntersecting) {
					// Element is out of viewport, add class
					header.classList.add('is-scrolled');
				} else {
					// Element is in viewport, remove class
					header.classList.remove('is-scrolled');
				}
			});
		}, {
			threshold: 0,
			rootMargin: '0px'
		});

		observer.observe(mainWrapper);
	}
});

/*	# SWIPER
=============================================== */

jQuery(document).ready(function($) {
	// Inicializar sliders de mwm-section-12 con autoplay
	$('.mwm-section-12__swiper').each(function() {
		var $swiper = $(this);
		var swiperId = $swiper.attr('id');
		
		if (!swiperId || typeof Swiper === 'undefined') {
			return;
		}
		
		// Inicializar Swiper con autoplay normal
		var swiper = new Swiper('#' + swiperId, {
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
			// Sin navegación ni paginación
			navigation: false,
			pagination: false,
			// Permitir interacción con el mouse
			simulateTouch: true,
			touchRatio: 1,
			touchAngle: 45,
		});
	});

	// Inicializar sliders de mwm-section-05
	$('.mwm-section-05__swiper').each(function() {
		var $swiper = $(this);
		var swiperId = $swiper.attr('id');
		
		if (!swiperId || typeof Swiper === 'undefined') {
			return;
		}
		
		// Buscar los botones de navegación dentro del mismo wrapper
		var $wrapper = $swiper.closest('.mwm-section-05__wrapper');
		var $prevButton = $wrapper.find('.mwm-section-05__nav-button--prev');
		var $nextButton = $wrapper.find('.mwm-section-05__nav-button--next');
		
		var prevId = $prevButton.attr('id');
		var nextId = $nextButton.attr('id');
		
		// Inicializar Swiper
		var swiper = new Swiper('#' + swiperId, {
			loop: true,
			slidesPerView: 1,
			spaceBetween: 12,
			navigation: {
				nextEl: nextId ? '#' + nextId : null,
				prevEl: prevId ? '#' + prevId : null,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: true,
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
		});
	});

	// Inicializar sliders de mwm-section-08
	$('.mwm-section-08__swiper').each(function() {
		var $swiper = $(this);
		var swiperId = $swiper.attr('id');
		
		if (!swiperId || typeof Swiper === 'undefined') {
			return;
		}
		
		// Inicializar Swiper
		var swiper = new Swiper('#' + swiperId, {
			loop: true,
			slidesPerView: 1,
			spaceBetween: 12,
			pagination: {
				el: '.swiper-pagination',
				clickable: true,
			},
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				1280: {
					slidesPerView: 3,
				},
			},
		});
	});
});

/*	# MWM FILTER
=============================================== */

jQuery(document).ready(function($) {
	$('.mwm-filter__toggle').click(function() {
		$('.mwm-filter').toggleClass('is-open');
	});

	$(document).on('click', function(event) {
		if (!$(event.target).closest('.mwm-filter__mobile-wrapper').length && !$(event.target).closest('.mwm-filter__toggle').length) {
			$('.mwm-filter').removeClass('is-open');
		}
	});
});

/*	# MWM CENTRO AYUDA ARCHIVE LAYOUT TOGGLE
=============================================== */

jQuery(document).ready(function($) {
	$('.mwm-centro-ayuda-archive').each(function() {
		var $archive = $(this);
		var $list = $archive.find('[data-layout-list]');
		var $buttons = $archive.find('.mwm-centro-ayuda-archive__view-circle[data-layout]');

		if ($list.length === 0 || $buttons.length === 0) {
			return;
		}

		$buttons.on('click', function() {
			var $button = $(this);
			var layout = $button.attr('data-layout');

			$buttons.removeClass('is-active');
			$button.addClass('is-active');

			$list.removeClass('mwm-centro-ayuda-archive__list--grid mwm-centro-ayuda-archive__list--rows');
			if (layout === 'rows') {
				$list.addClass('mwm-centro-ayuda-archive__list--rows');
			} else {
				$list.addClass('mwm-centro-ayuda-archive__list--grid');
			}
		});
	});
});

/*	# FANCYBOX
=============================================== */

jQuery(document).ready(function () {
	if (typeof Fancybox !== 'undefined') {
		Fancybox.bind('[data-fancybox="video"]', {
			Toolbar: {
				display: {
					left: [],
					middle: [],
					right: ['close'],
				},
			},
			YouTube: {
				autoplay: true,
			},
			Video: {
				tpl: '<video class="fancybox__html5video" playsinline controls controlsList="nodownload" poster="{{poster}}">' +
					'<source src="{{src}}" type="{{format}}" />' +
					'Sorry, your browser doesn\'t support embedded videos.</video>',
			},
			on: {
				"Carousel.createSlide": function (fancybox, carousel, slide) {
					slide.width  = Math.round(window.innerWidth  * 0.92);
					slide.height = Math.round(window.innerHeight * 0.85);
				},
			},
		});
	}
});

/*	# MWM CARD 01
=============================================== */

jQuery(document).ready(function($) {
	// Calcular flex-grow dinámico para cada card según la altura del excerpt, footer-wrapper y title-alternative
	$('.mwm-card-01').each(function() {
		var $card = $(this);
		var $excerpt = $card.find('.mwm-card-01__excerpt');
		var $footerWrapper = $card.find('.mwm-card-01__footer-wrapper');
		var $titleMain = $card.find('.mwm-card-01__title-main');
		var $titleAlternative = $card.find('.mwm-card-01__title-alternative');
		
		// Si no hay excerpt, usar valor por defecto
		if ($excerpt.length === 0) {
			$card.css('--card-media-grow', '0.9');
			return;
		}
		
		// Obtener la altura del excerpt directamente incluyendo paddings
		// offsetHeight incluye: contenido + padding + border (incluso si está oculto)
		var excerptElement = $excerpt[0];
		var excerptHeight = excerptElement.offsetHeight;
		
		// Si offsetHeight es 0, usar scrollHeight (contenido + padding) como fallback
		if (excerptHeight === 0) {
			excerptHeight = excerptElement.scrollHeight;
		}
		
		// Calcular altura del footer-wrapper considerando el title-alternative
		var footerWrapperHeight = 0;
		var titleHeightDifference = 0;
		
		if ($footerWrapper.length > 0) {
			// Obtener altura base del footer-wrapper
			footerWrapperHeight = $footerWrapper[0].offsetHeight;
			
			// Medir ambos títulos para ver cuál es más alto
			var titleMainHeight = 0;
			var titleAlternativeHeight = 0;
			
			if ($titleMain.length > 0) {
				// Medir title-main (puede estar visible u oculto)
				titleMainHeight = $titleMain[0].scrollHeight || $titleMain[0].offsetHeight || 0;
			}
			
			if ($titleAlternative.length > 0) {
				// Medir title-alternative (puede estar oculto, usar scrollHeight)
				// scrollHeight funciona incluso si el elemento está oculto
				titleAlternativeHeight = $titleAlternative[0].scrollHeight || $titleAlternative[0].offsetHeight || 0;
			}
			
			// Calcular la diferencia de altura entre los títulos
			// Si el title-alternative es más alto, el footer-wrapper necesitará más espacio
			titleHeightDifference = Math.max(0, titleAlternativeHeight - titleMainHeight);
			
			// Ajustar la altura del footer-wrapper sumando la diferencia del título
			// Esto simula el espacio adicional que necesitará cuando se muestre el title-alternative
			footerWrapperHeight += titleHeightDifference;
		}
		
		// Obtener la altura total de la card
		var cardHeight = $card.outerHeight();
		
		// Calcular la altura total que necesita espacio (excerpt + footer-wrapper ajustado)
		// El excerpt está posicionado absolutamente sobre el media, pero necesita espacio
		// El footer-wrapper también necesita espacio y puede solaparse con el excerpt
		var totalContentHeight = excerptHeight + footerWrapperHeight;
		
		// Calcular la proporción que ocupa el contenido total respecto a la card
		// Si el contenido ocupa más espacio, el media debe ceder más (flex-grow menor)
		var contentRatio = totalContentHeight / cardHeight;
		
		// Calcular el flex-grow: 
		// - Base: 0.9 (valor actual)
		// - Reducir proporcionalmente según la altura del contenido total
		// - Mínimo: 0.5 (para no reducir demasiado)
		// - Máximo: 0.9 (valor por defecto)
		// Ajustar el factor de reducción para tener en cuenta tanto excerpt como footer
		var flexGrow = Math.max(0.5, 0.9 - (contentRatio * 0.5));
		
		// Guardar el valor en una variable CSS específica de esta card
		$card.css('--card-media-grow', flexGrow);
	});
});

/*	# MWM SECTION 10 - EQUALIZE CARD HEADERS
=============================================== */

jQuery(document).ready(function($) {
	
	var $section10 = $('.mwm-section-10');
	
	if ($section10.length) {
		// Función para igualar las alturas de los headers
		function equalizeCardHeaders() {
			$section10.each(function() {
				var $section = $(this);
				var $cards = $section.find('.mwm-card-06');
				var $headers = $cards.find('.mwm-card-06__header');
				
				if ($headers.length === 0) {
					return;
				}
				
				// Resetear alturas para recalcular
				$headers.css('height', 'auto');
				
				// Encontrar la altura máxima
				var maxHeight = 0;
				$headers.each(function() {
					var height = $(this).outerHeight();
					if (height > maxHeight) {
						maxHeight = height;
					}
				});
				
				// Aplicar la altura máxima a todos los headers
				if (maxHeight > 0) {
					$headers.css('height', maxHeight + 'px');
				}
			});
		}
		
		// Ejecutar al cargar
		equalizeCardHeaders();
		
		// Ejecutar en resize con debounce
		var resizeTimeout;
		$(window).on('resize', function() {
			clearTimeout(resizeTimeout);
			resizeTimeout = setTimeout(function() {
				equalizeCardHeaders();
			}, 250);
		});
	}
});

/*	# MWM FORM CONTROL
=============================================== */

jQuery(document).ready(function ($) {
	// Agregar clase 'is-focus' al label cuando el input esté en focus
	$('.mwm-form-control').on('focus', 'input, select, textarea', function () {
		var $input = $(this);
		var $label = $input.closest('label');
		
		if ($label.length) {
			$label.addClass('is-focus');
		}
	});

	// Remover clase 'is-focus' del label cuando el input pierda el focus
	$('.mwm-form-control').on('blur', 'input, select, textarea', function () {
		var $input = $(this);
		var $label = $input.closest('label');
		
		if ($label.length) {
			$label.removeClass('is-focus');
		}
	});

	// Custom file input UI para .mwm-form-control-file (Contact Form 7)
	function initMwmFormControlFile() {
		$('.mwm-form-control-file').each(function () {
			var $wrap = $(this).find('.wpcf7-form-control-wrap');
			var $fileInput = $wrap.find('input[type="file"]');
			if (!$fileInput.length || $wrap.find('.mwm-form-control-file__inner').length) {
				return;
			}
			var $inner = $('<div class="mwm-form-control-file__inner"></div>');
			var $trigger = $('<span class="mwm-form-control-file__trigger" aria-hidden="true"></span>').text('Elegir archivo');
			var $filename = $('<span class="mwm-form-control-file__filename" aria-live="polite"></span>').text('Ningún archivo elegido');
			$inner.append($trigger).append($filename).append($fileInput);
			$wrap.append($inner);
			$fileInput.on('change', function () {
				// Solo actualizar si el usuario eligió un archivo; si canceló el diálogo no borrar el anterior
				if (this.files && this.files.length > 0) {
					$filename.text(this.files[0].name);
					$inner.addClass('mwm-form-control-file__inner--has-file');
				}
			});
			$fileInput.on('focus', function () {
				$fileInput.closest('label').addClass('is-focus');
			});
			$fileInput.on('blur', function () {
				$fileInput.closest('label').removeClass('is-focus');
			});
		});
	}
	initMwmFormControlFile();

	// Re-aplicar cuando CF7 reemplace el formulario (envío AJAX)
	$(document).on('wpcf7mailsent wpcf7mailfailed wpcf7invalid wpcf7spam', function () {
		initMwmFormControlFile();
	});
});

/*	# MWM SINGLE POST
=============================================== */

jQuery(document).ready(function () {
	var tocList = jQuery("<ul></ul>");
	var count = 1;
	var sidebar = jQuery(".mwm-section-post__sidebar");

	if (sidebar.length) {
		jQuery(".mwm-section-post__content h2").each(function () {
			var sectionId = "section-" + count;
			jQuery(this).attr("id", sectionId);

			// Obtener el texto original del H2
			var originalText = jQuery(this).text();

			// Limpiar el texto: remover numeración existente y guiones
			var cleanText = originalText
				.replace(/^\d+[\.\s\-]*/, "") // Remover numeración al inicio (1., 1 , 1-, etc.)
				.replace(/^\s*[-–—]\s*/, "") // Remover guiones al inicio
				.trim(); // Remover espacios extra

			var tocItem = jQuery("<li></li>");
			var tocLink = jQuery("<a></a>")
				.attr("href", "#" + sectionId)
				.text(cleanText);
			tocItem.append(tocLink);
			tocList.append(tocItem);

			count++;
		});

		sidebar.find(".mwm-section-post__sidebar-list").append(tocList);

		var hasListItems = sidebar.find(".mwm-section-post__sidebar-list ul li").length > 0;
		if (hasListItems) {
			sidebar.addClass("mwm-section-post__sidebar--has-items");
		}

		jQuery('a[href^="#"], a[href^="/#"]').on("click", function (event) {
			var href = jQuery(this).attr("href");
			var normalizedHref = href.startsWith("/#") ? href.slice(1) : href;

			var target = jQuery(normalizedHref);
			if (target.length) {
				event.preventDefault();
				
				// Obtener la posición del elemento objetivo
				var targetPosition = target.offset().top - 150;
				
				// Smooth scroll nativo
				window.scrollTo({
					top: targetPosition,
					behavior: 'smooth'
				});
			}
		});

		// Scroll spy: detectar qué heading está visible y marcar como activo
		var headings = jQuery(".mwm-section-post__content h2[id^='section-']");
		var tocLinks = sidebar.find(".mwm-section-post__sidebar-list a[href^='#']");

		if (headings.length > 0 && tocLinks.length > 0) {
			// Opciones para Intersection Observer
			var observerOptions = {
				root: null, // viewport
				rootMargin: '-20% 0px -60% 0px', // Considerar activo cuando está en el 20% superior del viewport
				threshold: 0
			};

			// Callback del observer
			var observerCallback = function(entries) {
				// Remover clase activa de todos los enlaces
				tocLinks.removeClass('is-active');

				// Encontrar el heading que está más cerca de la parte superior del viewport
				var activeHeading = null;
				var minDistance = Infinity;

				headings.each(function() {
					var heading = jQuery(this);
					var rect = this.getBoundingClientRect();
					
					// Si el heading está visible y por encima del punto de activación
					if (rect.top >= 0 && rect.top < window.innerHeight * 0.3) {
						var distance = rect.top;
						if (distance < minDistance) {
							minDistance = distance;
							activeHeading = heading;
						}
					}
				});

				// Si no hay heading en la zona superior, buscar el último que pasó
				if (!activeHeading) {
					var lastPassed = null;
					headings.each(function() {
						var heading = jQuery(this);
						var rect = this.getBoundingClientRect();
						if (rect.top < 0) {
							lastPassed = heading;
						}
					});
					if (lastPassed) {
						activeHeading = lastPassed;
					} else if (headings.length > 0) {
						// Si estamos al inicio, activar el primero
						activeHeading = headings.first();
					}
				}

				// Agregar clase activa al enlace correspondiente
				if (activeHeading) {
					var headingId = activeHeading.attr('id');
					var correspondingLink = tocLinks.filter('[href="#' + headingId + '"]');
					if (correspondingLink.length) {
						correspondingLink.addClass('is-active');
					}
				}
			};

			// Crear observer para cada heading
			var observer = new IntersectionObserver(observerCallback, observerOptions);
			
			headings.each(function() {
				observer.observe(this);
			});

			// También escuchar scroll para actualización más precisa
			var scrollTimeout;
			jQuery(window).on('scroll', function() {
				clearTimeout(scrollTimeout);
				scrollTimeout = setTimeout(function() {
					observerCallback([]);
				}, 50);
			});

			// Ejecutar una vez al cargar para marcar el inicial
			setTimeout(function() {
				observerCallback([]);
			}, 100);
		}
	}
});

/*	# MWM SECTION 11 - STICKY ELEMENT SCROLL SPY
=============================================== */

jQuery(document).ready(function () {
	var $section11 = jQuery('.mwm-section-11');
	
	if ($section11.length) {
		var $stickyElement = $section11.find('.mwm-section-11__sticky-element');
		var $allStickyNumbers = $section11.find('.mwm-section-11__sticky-element-number');
		var $sections = $section11.find('.mwm-section-11__sections-wrapper .mwm-section-01');
		
		if ($stickyElement.length && $allStickyNumbers.length && $sections.length) {
			// Calcular la posición del sticky element
			// top: calc(var(--header-height) + var(--wp-admin--admin-bar--height, 0px) + 5rem)
			var headerHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--header-height')) || 0;
			var adminBarHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--wp-admin--admin-bar--height')) || 0;
			var stickyTop = headerHeight + adminBarHeight + 80; // 5rem = 80px
			
			// Función para actualizar qué número sticky está activo
			function updateActiveSticky(activeIndex) {
				$allStickyNumbers.each(function() {
					var $number = jQuery(this);
					var numberIndex = parseInt($number.attr('data-sticky-index')) || 0;
					
					if (numberIndex === activeIndex) {
						// Activar este número
						$number.addClass('is-active');
					} else {
						// Desactivar otros números
						$number.removeClass('is-active');
					}
				});
			}
			
			// Callback del observer
			var observerCallback = function(entries) {
				var activeSection = null;
				var sectionsInView = [];
				
				// Recopilar todas las secciones que están en el viewport
				$sections.each(function() {
					var $section = jQuery(this);
					var rect = this.getBoundingClientRect();
					var sectionIndex = parseInt($section.attr('data-section-index')) || 1;
					
					// Verificar si la sección está visible en el viewport (aunque sea parcialmente)
					var isVisible = rect.top < window.innerHeight && rect.bottom > 0;
					
					if (isVisible) {
						// Calcular la distancia desde la posición del sticky element
						// Si rect.top es menor que stickyTop, la sección ya pasó la posición del sticky
						var distanceFromStickyTop = rect.top - stickyTop;
						
						// Si la sección está por encima de la posición del sticky (negativo), ya pasó
						// Si está por debajo (positivo), aún no ha llegado
						var hasPassedStickyTop = distanceFromStickyTop <= 0;
						
						sectionsInView.push({
							index: sectionIndex,
							top: rect.top,
							bottom: rect.bottom,
							height: rect.height,
							distanceFromStickyTop: distanceFromStickyTop,
							hasPassedStickyTop: hasPassedStickyTop
						});
					}
				});
				
				// Si hay secciones visibles, encontrar la que está más presente
				if (sectionsInView.length > 0) {
					// Ordenar: priorizar la sección que está más cerca o ha pasado la posición del sticky
					sectionsInView.sort(function(a, b) {
						// Si una ya pasó la posición del sticky y la otra no, priorizar la que pasó
						if (a.hasPassedStickyTop && !b.hasPassedStickyTop) {
							return -1;
						}
						if (b.hasPassedStickyTop && !a.hasPassedStickyTop) {
							return 1;
						}
						// Si ambas pasaron o ninguna pasó, usar la que está más cerca de la posición del sticky
						// (menor distancia absoluta desde stickyTop)
						return Math.abs(a.distanceFromStickyTop) - Math.abs(b.distanceFromStickyTop);
					});
					
					activeSection = sectionsInView[0].index;
				} else {
					// Si no hay secciones visibles, buscar la última que pasó completamente
					var lastPassed = null;
					$sections.each(function() {
						var $section = jQuery(this);
						var rect = this.getBoundingClientRect();
						var sectionIndex = parseInt($section.attr('data-section-index')) || 1;
						
						// Si la sección ya pasó completamente (está arriba del viewport)
						// Solo cambiar cuando la sección salga completamente (bottom <= 0)
						if (rect.bottom <= 0) {
							lastPassed = sectionIndex;
						}
					});
					
					// Si hay una última sección que pasó, usarla; si no, mantener la primera
					activeSection = lastPassed || 1;
				}
				
				// Actualizar el elemento sticky activo
				if (activeSection) {
					updateActiveSticky(activeSection);
				}
			};
			
			// Opciones para Intersection Observer
			var observerOptions = {
				root: null,
				rootMargin: '0px',
				threshold: [0, 0.1, 0.25, 0.5, 0.75, 1]
			};
			
			// Crear observer para cada sección
			var observer = new IntersectionObserver(observerCallback, observerOptions);
			
			$sections.each(function() {
				observer.observe(this);
			});
			
			// También escuchar scroll para actualización más precisa
			var scrollTimeout;
			jQuery(window).on('scroll', function() {
				clearTimeout(scrollTimeout);
				scrollTimeout = setTimeout(function() {
					observerCallback([]);
				}, 50);
			});
			
			// Activar el primer elemento por defecto
			updateActiveSticky(1);
			
			// Ejecutar una vez al cargar para establecer el inicial
			setTimeout(function() {
				observerCallback([]);
			}, 100);
		}
	}
});

/*	# MWM SECTION 17 - TABS
=============================================== */

jQuery(document).ready(function () {
	jQuery('.mwm-section-17--layout-tabs').each(function() {
		var $container = jQuery(this);
		var $buttons = $container.find('.mwm-section-17__tab-button');
		var $contents = $container.find('.mwm-section-17__tab-content');
		var $tabsContentWrapper = $container.find('.mwm-section-17__tabs-content');
		
		// Función para calcular y establecer la altura máxima
		function setMaxHeight() {
			var maxHeight = 0;
			
			// Medir cada tab directamente
			$contents.each(function() {
				var $content = jQuery(this);
				var originalPosition = $content.css('position');
				
				// Cambiar temporalmente a relative para medir correctamente
				$content.css('position', 'relative');
				
				// Obtener altura real
				var height = $content.outerHeight(true);
				if (height > maxHeight) {
					maxHeight = height;
				}
				
				// Restaurar position original
				$content.css('position', originalPosition);
			});
			
			// Aplicar altura máxima al contenedor
			if (maxHeight > 0) {
				$tabsContentWrapper.css('min-height', maxHeight + 'px');
			}
		}
		
		// Calcular altura al cargar
		setMaxHeight();
		
		// Recalcular en resize (con debounce)
		var resizeTimeout;
		jQuery(window).on('resize', function() {
			clearTimeout(resizeTimeout);
			resizeTimeout = setTimeout(setMaxHeight, 250);
		});
		
		$buttons.on('click', function(e) {
			e.preventDefault();
			
			var $button = jQuery(this);
			var tabId = $button.data('tab');
			var $content = $container.find('[data-tab-content="' + tabId + '"]');
			
			// Si ya está activo, no hacer nada
			if ($button.hasClass('is-active')) {
				return;
			}
			
			// Quitar activo de todos
			$buttons.removeClass('is-active');
			$contents.removeClass('is-active');
			
			// Activar el seleccionado
			$button.addClass('is-active');
			$content.addClass('is-active');
		});
	});
});

/*	# MWM VIDEO — POSTER DESDE PRIMER FRAME (data-mwm-video-first-frame-poster)
=============================================== */

jQuery(document).ready(function () {
	var posterDataUrlBySrc = {};

	function mwmCaptureVideoPosterFromSrc(src, done) {
		if (posterDataUrlBySrc[src]) {
			done(posterDataUrlBySrc[src]);
			return;
		}

		var video = document.createElement('video');
		video.muted = true;
		video.playsInline = true;
		video.preload = 'auto';
		video.crossOrigin = 'anonymous';

		function finish(result) {
			video.removeAttribute('src');
			video.load();
			done(result);
		}

		video.addEventListener('error', function () {
			finish(null);
		});

		video.addEventListener('loadedmetadata', function onMeta() {
			video.removeEventListener('loadedmetadata', onMeta);
			var seek = 0.05;
			if (video.duration && !isNaN(video.duration) && isFinite(video.duration) && video.duration > 0) {
				seek = Math.min(0.15, Math.max(0.05, video.duration * 0.01));
				if (seek >= video.duration) {
					seek = Math.max(0, video.duration - 0.04);
				}
			}

			function onSeeked() {
				video.removeEventListener('seeked', onSeeked);
				try {
					var w = video.videoWidth;
					var h = video.videoHeight;
					if (!w || !h) {
						finish(null);
						return;
					}
					var canvas = document.createElement('canvas');
					canvas.width = w;
					canvas.height = h;
					canvas.getContext('2d').drawImage(video, 0, 0);
					var dataUrl = canvas.toDataURL('image/jpeg', 0.82);
					posterDataUrlBySrc[src] = dataUrl;
					finish(dataUrl);
				} catch (err) {
					finish(null);
				}
			}

			video.addEventListener('seeked', onSeeked);
			video.currentTime = seek;
		});

		video.src = src;
	}

	jQuery('video[data-mwm-video-first-frame-poster]').each(function () {
		var el = this;
		var src = jQuery(el).find('source').first().attr('src');
		if (!src) {
			return;
		}
		mwmCaptureVideoPosterFromSrc(src, function (dataUrl) {
			if (dataUrl) {
				el.setAttribute('poster', dataUrl);
			}
		});
	});
});

/*	# MWM SECTION 18 — TABLA COMPARATIVA / ACORDEÓN
=============================================== */

jQuery(document).ready(function () {
	jQuery('.mwm-section-18, .mwm-section-19, .mwm-section-20').each(function () {
		var $section = jQuery(this);
		var triggerSel = $section.hasClass('mwm-section-18')
			? '.mwm-section-18__accordion-trigger'
			: $section.hasClass('mwm-section-19')
				? '.mwm-section-19__accordion-trigger'
				: '.mwm-section-20__accordion-trigger';
		var accordionSel = $section.hasClass('mwm-section-18')
			? '.mwm-section-18__accordion'
			: $section.hasClass('mwm-section-19')
				? '.mwm-section-19__accordion'
				: '.mwm-section-20__accordion';

		$section.find(triggerSel).on('click', function () {
			var $btn = jQuery(this);
			var $accordion = $btn.closest(accordionSel);
			var $panel = $section.find('#' + $btn.attr('aria-controls'));
			var expanded = $btn.attr('aria-expanded') === 'true';

			if (expanded) {
				$btn.attr('aria-expanded', 'false');
				$accordion.removeClass('is-open');
				if ($panel.length) {
					$panel.attr('aria-hidden', 'true');
				}
			} else {
				$btn.attr('aria-expanded', 'true');
				$accordion.addClass('is-open');
				if ($panel.length) {
					$panel.attr('aria-hidden', 'false');
				}
			}
		});
	});
});

/*	# MWM FLOATING BUTTON - SCROLL BEHAVIOR
=============================================== */

jQuery(document).ready(function () {
	var $floatingButton = jQuery('.mwm-floating-button');
	var $header = jQuery('.mwm-header');
	
	if ($floatingButton.length > 0) {
		// Empezar oculto
		$floatingButton.addClass('is-hidden');
		
		function handleFloatingButton() {
			var scrollTop = jQuery(window).scrollTop();
			var windowHeight = jQuery(window).height();
			var $footer = jQuery('.mwm-footer');
			var hasHeader = $header.length > 0;
			var isHeaderScrolled = hasHeader && $header.hasClass('is-scrolled');
			
			// Verificar si el footer existe
			if ($footer.length === 0) {
				// Si no hay footer:
				// - Si hay header, usar el mismo estado que el header fijo (is-scrolled)
				// - Si no hay header, usar fallback por distancia de scroll
				if (hasHeader) {
					if (isHeaderScrolled) {
						$floatingButton.removeClass('is-hidden').addClass('is-visible');
					} else {
						$floatingButton.removeClass('is-visible').addClass('is-hidden');
					}
				} else {
					var showDistance = 200;
					var atTop = scrollTop < showDistance;
					
					if (atTop) {
						$floatingButton.removeClass('is-visible').addClass('is-hidden');
					} else {
						$floatingButton.removeClass('is-hidden').addClass('is-visible');
					}
				}
				return;
			}
			
			// Calcular posición del footer
			var footerTop = $footer.offset().top;
			var viewportBottom = scrollTop + windowHeight;
			
			// Verificar si el viewport ha entrado en el footer
			// El botón se oculta cuando el bottom del viewport alcanza el top del footer
			var inFooter = viewportBottom >= footerTop;
			
			// Mostrar u ocultar según las condiciones:
			// - Solo se muestra cuando el header está en estado "is-scrolled"
			// - Nunca se muestra dentro del footer
			if (!isHeaderScrolled || inFooter) {
				$floatingButton.removeClass('is-visible').addClass('is-hidden');
			} else {
				$floatingButton.removeClass('is-hidden').addClass('is-visible');
			}
		}
		
		// Ejecutar al cargar
		handleFloatingButton();
		
		// Ejecutar en scroll (con throttle)
		var scrollTimeout;
		jQuery(window).on('scroll', function() {
			clearTimeout(scrollTimeout);
			scrollTimeout = setTimeout(handleFloatingButton, 50);
		});
		
		// Ejecutar en resize (por si cambia la posición del footer)
		var resizeTimeout;
		jQuery(window).on('resize', function() {
			clearTimeout(resizeTimeout);
			resizeTimeout = setTimeout(handleFloatingButton, 250);
		});
	}
});


