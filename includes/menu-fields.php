<?php
/**
 * Campos ACF para items del menú
 * Campos: checkbox para submenu extenso, selección de post o rellenado manual
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_menu_item_fields',
			'title'                 => __( 'Campos del Item del Menú', 'bilky' ),
			'fields'                => array(
				array(
					'key'               => 'field_menu_item_icon',
					'label'             => __( 'Icono del item', 'bilky' ),
					'name'              => 'menu_item_icon',
					'type'              => 'text',
					'instructions'      => __( 'Icono de Font Awesome para este item. Ejemplos válidos: briefcase, fa-briefcase o fa-solid fa-briefcase.', 'bilky' ),
					'required'          => 0,
					'default_value'     => '',
					'placeholder'       => 'briefcase',
				),
				array(
					'key'               => 'field_menu_use_extended_submenu',
					'label'             => __( 'Usar submenu extenso', 'bilky' ),
					'name'              => 'menu_use_extended_submenu',
					'type'              => 'true_false',
					'instructions'      => __( 'Este checkbox es para cuando el item del menú tiene subitems.', 'bilky' ),
					'required'          => 0,
					'default_value'     => 0,
					'ui'                => 1,
					'ui_on_text'        => '',
					'ui_off_text'       => '',
				),
				array(
					'key'               => 'field_menu_extended_source',
					'label'             => __( 'Origen del contenido destacado', 'bilky' ),
					'name'              => 'menu_extended_source',
					'type'              => 'button_group',
					'instructions'      => __( 'Elige una entrada de post (datos automáticos) o rellena manualmente los campos.', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_menu_use_extended_submenu',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'choices'           => array(
						'post'   => __( 'Entrada de post', 'bilky' ),
						'manual' => __( 'Manual', 'bilky' ),
					),
					'default_value'     => 'post',
					'allow_null'        => 0,
					'layout'            => 'horizontal',
					'return_format'     => 'value',
				),
				array(
					'key'               => 'field_menu_extended_post',
					'label'             => __( 'Entrada del post', 'bilky' ),
					'name'              => 'menu_extended_post',
					'type'              => 'post_object',
					'instructions'      => __( 'Selecciona una entrada de post para el submenu extenso. Los datos (título, imagen, enlace, categoría, fecha) se rellenarán automáticamente.', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_menu_use_extended_submenu',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'field'    => 'field_menu_extended_source',
								'operator' => '==',
								'value'    => 'post',
							),
						),
					),
					'post_type'         => array(
						0 => 'post',
					),
					'taxonomy'          => '',
					'allow_null'        => 1,
					'multiple'          => 0,
					'return_format'     => 'object',
					'ui'                => 1,
				),
				array(
					'key'               => 'field_menu_extended_manual_title',
					'label'             => __( 'Título (manual)', 'bilky' ),
					'name'              => 'menu_extended_manual_title',
					'type'              => 'text',
					'instructions'      => __( 'Título del contenido destacado.', 'bilky' ),
					'required'          => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_menu_use_extended_submenu',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'field'    => 'field_menu_extended_source',
								'operator' => '==',
								'value'    => 'manual',
							),
						),
					),
					'default_value'     => '',
					'placeholder'       => '',
				),
				array(
					'key'               => 'field_menu_extended_manual_url',
					'label'             => __( 'Enlace (manual)', 'bilky' ),
					'name'              => 'menu_extended_manual_url',
					'type'              => 'url',
					'instructions'      => __( 'URL de destino del contenido destacado.', 'bilky' ),
					'required'          => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_menu_use_extended_submenu',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'field'    => 'field_menu_extended_source',
								'operator' => '==',
								'value'    => 'manual',
							),
						),
					),
					'default_value'     => '',
					'placeholder'       => 'https://',
				),
				array(
					'key'               => 'field_menu_extended_manual_image',
					'label'             => __( 'Imagen (manual)', 'bilky' ),
					'name'              => 'menu_extended_manual_image',
					'type'              => 'image',
					'instructions'      => __( 'Imagen del contenido destacado. Opcional.', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_menu_use_extended_submenu',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'field'    => 'field_menu_extended_source',
								'operator' => '==',
								'value'    => 'manual',
							),
						),
					),
					'return_format'     => 'array',
					'preview_size'      => 'medium',
					'library'           => 'all',
				),
				array(
					'key'               => 'field_menu_extended_manual_category_name',
					'label'             => __( 'Nombre de categoría (manual)', 'bilky' ),
					'name'              => 'menu_extended_manual_category_name',
					'type'              => 'text',
					'instructions'      => __( 'Nombre de la categoría. Opcional. Se muestra como botón si también indicas la URL.', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_menu_use_extended_submenu',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'field'    => 'field_menu_extended_source',
								'operator' => '==',
								'value'    => 'manual',
							),
						),
					),
					'default_value'     => '',
					'placeholder'       => '',
				),
				array(
					'key'               => 'field_menu_extended_manual_category_url',
					'label'             => __( 'URL de categoría (manual)', 'bilky' ),
					'name'              => 'menu_extended_manual_category_url',
					'type'              => 'url',
					'instructions'      => __( 'Enlace de la categoría. Opcional.', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_menu_use_extended_submenu',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'field'    => 'field_menu_extended_source',
								'operator' => '==',
								'value'    => 'manual',
							),
						),
					),
					'default_value'     => '',
					'placeholder'       => 'https://',
				),
				array(
					'key'               => 'field_menu_extended_manual_date',
					'label'             => __( 'Fecha (manual)', 'bilky' ),
					'name'              => 'menu_extended_manual_date',
					'type'              => 'text',
					'instructions'      => __( 'Fecha a mostrar. Opcional. Ejemplo: 12.03.2025', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_menu_use_extended_submenu',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'field'    => 'field_menu_extended_source',
								'operator' => '==',
								'value'    => 'manual',
							),
						),
					),
					'default_value'     => '',
					'placeholder'       => 'dd.mm.yyyy',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'nav_menu_item',
						'operator' => '==',
						'value'    => 'all',
					),
				),
			),
			'menu_order'             => 0,
			'position'               => 'normal',
			'style'                  => 'default',
			'label_placement'        => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'         => '',
		)
	);

endif;

/**
 * Controla la visibilidad de campos ACF en items del menú según nivel.
 *
 * Reglas:
 * - Campos de "submenu extenso" (checkbox, origen y relacionados): solo en primer nivel.
 * - "Icono del item": solo en segundo nivel cuyo padre tenga activado "submenu extenso".
 *
 * @param string $hook_suffix Hook actual del admin.
 * @return void
 */
function bilky_menu_fields_admin_visibility( $hook_suffix ) {
	if ( 'nav-menus.php' !== $hook_suffix ) {
		return;
	}

	wp_enqueue_script( 'jquery' );

	$inline_script = <<<'JS'
(function ($) {
	'use strict';

	function getMenuItemDepth($menuItem) {
		var match = ($menuItem.attr('class') || '').match(/menu-item-depth-(\d+)/);
		return match ? parseInt(match[1], 10) : 0;
	}

	function isExtendedEnabled($menuItem) {
		var $checkbox = $menuItem.find('.acf-field[data-key="field_menu_use_extended_submenu"] input[type="checkbox"]');
		return $checkbox.length ? $checkbox.is(':checked') : false;
	}

	function getParentLevelZeroItem($menuItem) {
		var depth = getMenuItemDepth($menuItem);
		var parentId;
		var $parentInput;

		if (depth !== 1) {
			return $();
		}

		$parentInput = $menuItem.find('input.menu-item-data-parent-id');
		if (!$parentInput.length) {
			$parentInput = $menuItem.find('input[name^="menu-item-parent-id"]');
		}

		parentId = parseInt($parentInput.val(), 10);
		if (!parentId) {
			return $();
		}

		return $('#menu-item-' + parentId);
	}

	function toggleField($field, shouldShow) {
		if (!$field.length) {
			return;
		}

		$field.toggle(shouldShow);
	}

	function updateMenuFieldsVisibility() {
		var extendedFieldKeys = [
			'field_menu_use_extended_submenu',
			'field_menu_extended_source',
			'field_menu_extended_post',
			'field_menu_extended_manual_title',
			'field_menu_extended_manual_url',
			'field_menu_extended_manual_image',
			'field_menu_extended_manual_category_name',
			'field_menu_extended_manual_category_url',
			'field_menu_extended_manual_date'
		];

		$('#menu-to-edit .menu-item').each(function () {
			var $item = $(this);
			var depth = getMenuItemDepth($item);
			var $iconField = $item.find('.acf-field[data-key="field_menu_item_icon"]');
			var showExtendedFields = (depth === 0);
			var showIconField = false;
			var i;

			// Campos de submenu extenso solo en primer nivel.
			for (i = 0; i < extendedFieldKeys.length; i++) {
				toggleField($item.find('.acf-field[data-key="' + extendedFieldKeys[i] + '"]'), showExtendedFields);
			}

			// Icono solo en segundo nivel y con padre extendido activo.
			if (depth === 1) {
				var $parent = getParentLevelZeroItem($item);
				showIconField = $parent.length && isExtendedEnabled($parent);
			}

			toggleField($iconField, showIconField);
		});
	}

	$(document).ready(function () {
		updateMenuFieldsVisibility();
	});

	$(document).on('change', '.acf-field[data-key="field_menu_use_extended_submenu"] input[type="checkbox"]', function () {
		updateMenuFieldsVisibility();
	});

	$(document).on('change', 'input.menu-item-data-parent-id, input[name^="menu-item-parent-id"]', function () {
		updateMenuFieldsVisibility();
	});

	$(document).ajaxComplete(function () {
		updateMenuFieldsVisibility();
	});

	$(document).on('sortstop', '#menu-to-edit', function () {
		updateMenuFieldsVisibility();
	});

	$(document).on('sortupdate', '#menu-to-edit', function () {
		updateMenuFieldsVisibility();
	});

	$(document).on('mouseup keyup', '#menu-to-edit .menu-item-bar', function () {
		setTimeout(updateMenuFieldsVisibility, 0);
	});
})(jQuery);
JS;

	wp_add_inline_script( 'jquery', $inline_script );
}
add_action( 'admin_enqueue_scripts', 'bilky_menu_fields_admin_visibility' );

