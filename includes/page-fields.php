<?php
/**
 * Campos ACF para páginas
 * Campos: video e icono
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_page_card_fields',
			'title'                 => __( 'Campos para Cards', 'bilky' ),
			'fields'                => array(
				array(
					'key'               => 'field_page_video',
					'label'             => __( 'Video', 'bilky' ),
					'name'              => 'page_video',
					'type'              => 'file',
					'instructions'      => __( 'Sube un archivo de video. Si se rellena, se mostrará en lugar de la imagen destacada en las cards.', 'bilky' ),
					'required'          => 0,
					'return_format'     => 'url',
					'library'           => 'all',
					'min_size'          => '',
					'max_size'          => '',
					'mime_types'       => 'mp4,webm,ogg',
				),
				array(
					'key'               => 'field_page_icon',
					'label'             => __( 'Icono', 'bilky' ),
					'name'              => 'page_icon',
					'type'              => 'text',
					'instructions'      => __( 'Nombre del icono de Font Awesome 7 Pro (ej: briefcase, user, etc.)', 'bilky' ),
					'required'          => 0,
					'default_value'     => 'briefcase',
					'placeholder'       => 'briefcase',
				),
				array(
					'key'               => 'field_page_alternative_title',
					'label'             => __( 'Título alternativo', 'bilky' ),
					'name'              => 'page_alternative_title',
					'type'              => 'text',
					'instructions'      => __( 'Título alternativo para mostrar en las cards. Si se deja vacío, se usará el título de la página.', 'bilky' ),
					'required'          => 0,
					'default_value'     => '',
					'placeholder'       => '',
				),
				array(
					'key'               => 'field_page_card_description',
					'label'             => __( 'Descripción para Card', 'bilky' ),
					'name'              => 'page_card_description',
					'type'              => 'wysiwyg',
					'instructions'      => __( 'Contenido que se mostrará en las cards. Puedes usar negrita para resaltar palabras importantes.', 'bilky' ),
					'required'          => 0,
					'tabs'              => 'all',
					'toolbar'           => 'basic',
					'media_upload'      => 0,
					'delay'             => 0,
				),
				array(
					'key'               => 'field_page_floating_button_link',
					'label'             => __( 'Botón flotante', 'bilky' ),
					'name'              => 'page_floating_button_link',
					'type'              => 'link',
					'instructions'      => __( 'Configura el botón flotante de esta página. Usa el título del enlace como texto del botón. Si no se rellena, el botón no se mostrará.', 'bilky' ),
					'required'          => 0,
					'return_format'     => 'array',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'page',
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

