/**
 * Filtro de categoría del bloque Section 16 sin recargar la página (AJAX).
 */
(function () {
	'use strict';

	function getTermSlugFromUrl(url) {
		try {
			var params = new URL(url, window.location.origin).searchParams;
			return params.get('categoria') || '';
		} catch (e) {
			return '';
		}
	}

	function init() {
		var sections = document.querySelectorAll('.mwm-section-16[data-taxonomy]');
		if (!sections.length || typeof mwmSection16 === 'undefined') {
			return;
		}

		sections.forEach(function (section) {
			var list = section.querySelector('.mwm-section-16__list');
			var filterLinks = section.querySelectorAll('.mwm-section-16__filters a');
			if (!list || !filterLinks.length) {
				return;
			}

			filterLinks.forEach(function (link) {
				link.addEventListener('click', function (e) {
					e.preventDefault();
					var termSlug = getTermSlugFromUrl(link.getAttribute('href') || '');
					var postType = section.getAttribute('data-post-type') || '';
					var taxonomy = section.getAttribute('data-taxonomy') || '';
					var cptButtonText = section.getAttribute('data-cpt-button-text') || '';
					var cardsImageOnly = section.getAttribute('data-cards-image-only') || '0';

					if (!postType || !taxonomy) {
						return;
					}

					list.classList.add('mwm-section-16__list--loading');

					var formData = new FormData();
					formData.append('action', 'mwm_section_16_filter');
					formData.append('nonce', mwmSection16.nonce);
					formData.append('post_type', postType);
					formData.append('taxonomy', taxonomy);
					formData.append('term_slug', termSlug);
					formData.append('cpt_button_text', cptButtonText);
					formData.append('cards_image_only', cardsImageOnly);

					fetch(mwmSection16.ajaxurl, {
						method: 'POST',
						body: formData,
						credentials: 'same-origin'
					})
						.then(function (response) {
							return response.json();
						})
						.then(function (data) {
							list.classList.remove('mwm-section-16__list--loading');
							if (data.success && data.data && data.data.html !== undefined) {
								list.innerHTML = data.data.html;
							}
							filterLinks.forEach(function (a) {
								a.classList.remove('mwm-button--active');
							});
							link.classList.add('mwm-button--active');
						})
						.catch(function () {
							list.classList.remove('mwm-section-16__list--loading');
						});
				});
			});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
