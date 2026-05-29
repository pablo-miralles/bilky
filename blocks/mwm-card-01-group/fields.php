<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_mwm_card_01_group',
			'title'                 => 'MWM Card 01 Group',
			'fields'                => array(
				array(
					'key'               => 'field_mwm_card_01_group_cards',
					'label'             => __( 'Cards', 'bilky' ),
					'name'              => 'cards',
					'type'              => 'repeater',
					'instructions'      => __( 'Selecciona hasta 2 páginas para mostrar como cards. El video, icono y título se obtienen de cada página. Puedes sobrescribir estos campos activando "Mostrar campos de sobrescritura".', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'          => 'field_mwm_card_01_group_card_page',
					'min'                => 1,
					'max'                => 2,
					'layout'             => 'block',
					'button_label'       => __( 'Añadir Card', 'bilky' ),
					'sub_fields'         => array(
						array(
							'key'               => 'field_mwm_card_01_group_card_page',
							'label'             => __( 'Página', 'bilky' ),
							'name'              => 'page',
							'type'              => 'post_object',
							'instructions'      => __( 'Selecciona la página que quieres mostrar en esta card', 'bilky' ),
							'required'          => 0,
							'post_type'         => array(
								0 => 'page',
							),
							'taxonomy'          => '',
							'allow_null'        => 0,
							'multiple'          => 0,
							'return_format'     => 'id',
							'ui'                => 1,
						),
						array(
							'key'               => 'field_mwm_card_01_group_card_button_text',
							'label'             => __( 'Texto del botón', 'bilky' ),
							'name'              => 'button_text',
							'type'              => 'text',
							'instructions'      => __( 'Texto personalizado para el botón. Si se deja vacío, se usará "Ver más"', 'bilky' ),
							'required'          => 0,
							'default_value'     => '',
							'placeholder'       => __( 'Ver más', 'bilky' ),
						),
						array(
							'key'               => 'field_mwm_card_01_group_show_override_fields',
							'label'             => __( 'Mostrar campos custom', 'bilky' ),
							'name'              => 'show_override_fields',
							'type'              => 'true_false',
							'instructions'      => __( 'Activa esta opción para mostrar los campos personalizados que permiten sobrescribir los datos de la página', 'bilky' ),
							'required'          => 0,
							'default_value'     => 0,
							'ui'                => 1,
						),
						array(
							'key'               => 'field_mwm_card_01_group_override_title',
							'label'             => __( 'Sobrescribir título', 'bilky' ),
							'name'              => 'override_title',
							'type'              => 'true_false',
							'instructions'      => __( 'Activa para usar un título personalizado en lugar del título de la página', 'bilky' ),
							'required'          => 0,
							'default_value'     => 0,
							'ui'                => 1,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_card_01_group_show_override_fields',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_card_01_group_custom_title',
							'label'             => __( 'Título personalizado', 'bilky' ),
							'name'              => 'custom_title',
							'type'              => 'text',
							'instructions'      => __( 'Si se rellena, este título se usará en lugar del título de la página', 'bilky' ),
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_card_01_group_show_override_fields',
										'operator' => '==',
										'value'    => '1',
									),
									array(
										'field'    => 'field_mwm_card_01_group_override_title',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_card_01_group_override_alternative_title',
							'label'             => __( 'Sobrescribir título al hacer hover', 'bilky' ),
							'name'              => 'override_alternative_title',
							'type'              => 'true_false',
							'instructions'      => __( 'Activa para usar un título personalizado al hacer hover', 'bilky' ),
							'required'          => 0,
							'default_value'     => 0,
							'ui'                => 1,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_card_01_group_show_override_fields',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_card_01_group_custom_alternative_title',
							'label'             => __( 'Título personalizado al hacer hover', 'bilky' ),
							'name'              => 'custom_alternative_title',
							'type'              => 'text',
							'instructions'      => __( 'Si se rellena, este título se usará al hacer hover en lugar del de la página', 'bilky' ),
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_card_01_group_show_override_fields',
										'operator' => '==',
										'value'    => '1',
									),
									array(
										'field'    => 'field_mwm_card_01_group_override_alternative_title',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_card_01_group_override_icon',
							'label'             => __( 'Sobrescribir icono', 'bilky' ),
							'name'              => 'override_icon',
							'type'              => 'true_false',
							'instructions'      => __( 'Activa para usar un icono personalizado en lugar del icono de la página', 'bilky' ),
							'required'          => 0,
							'default_value'     => 0,
							'ui'                => 1,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_card_01_group_show_override_fields',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_card_01_group_custom_icon',
							'label'             => __( 'Icono personalizado', 'bilky' ),
							'name'              => 'custom_icon',
							'type'              => 'text',
							'instructions'      => __( 'Nombre del icono de Font Awesome 7 Pro (ej: briefcase). Si se deja vacío, se usará el icono de la página o "briefcase" por defecto', 'bilky' ),
							'required'          => 0,
							'default_value'     => '',
							'placeholder'       => 'briefcase',
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_card_01_group_show_override_fields',
										'operator' => '==',
										'value'    => '1',
									),
									array(
										'field'    => 'field_mwm_card_01_group_override_icon',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_card_01_group_override_media',
							'label'             => __( 'Sobrescribir media', 'bilky' ),
							'name'              => 'override_media',
							'type'              => 'true_false',
							'instructions'      => __( 'Activa para usar un media personalizado (video o imagen) en lugar del media de la página', 'bilky' ),
							'required'          => 0,
							'default_value'     => 0,
							'ui'                => 1,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_card_01_group_show_override_fields',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_card_01_group_custom_media_type',
							'label'             => __( 'Tipo de media', 'bilky' ),
							'name'              => 'custom_media_type',
							'type'              => 'select',
							'instructions'      => __( 'Selecciona si quieres usar video o imagen', 'bilky' ),
							'required'          => 0,
							'choices'           => array(
								'image' => __( 'Imagen', 'bilky' ),
								'video' => __( 'Video', 'bilky' ),
							),
							'default_value'     => 'image',
							'allow_null'        => 0,
							'multiple'          => 0,
							'ui'                => 1,
							'ajax'              => 0,
							'return_format'     => 'value',
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_card_01_group_show_override_fields',
										'operator' => '==',
										'value'    => '1',
									),
									array(
										'field'    => 'field_mwm_card_01_group_override_media',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_card_01_group_custom_video',
							'label'             => __( 'Video personalizado', 'bilky' ),
							'name'              => 'custom_video',
							'type'              => 'file',
							'instructions'      => __( 'Sube un archivo de video. Si se sube, este video se usará en lugar del video de la página', 'bilky' ),
							'required'          => 0,
							'return_format'     => 'url',
							'library'           => 'all',
							'min_size'          => '',
							'max_size'          => '',
							'mime_types'        => 'mp4,webm,ogg',
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_card_01_group_show_override_fields',
										'operator' => '==',
										'value'    => '1',
									),
									array(
										'field'    => 'field_mwm_card_01_group_override_media',
										'operator' => '==',
										'value'    => '1',
									),
									array(
										'field'    => 'field_mwm_card_01_group_custom_media_type',
										'operator' => '==',
										'value'    => 'video',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_card_01_group_custom_image',
							'label'             => __( 'Imagen personalizada', 'bilky' ),
							'name'              => 'custom_image',
							'type'              => 'image',
							'instructions'      => __( 'Sube una imagen. Si se sube, esta imagen se usará en lugar de la imagen destacada de la página', 'bilky' ),
							'required'          => 0,
							'return_format'     => 'id',
							'preview_size'      => 'medium',
							'library'           => 'all',
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_card_01_group_show_override_fields',
										'operator' => '==',
										'value'    => '1',
									),
									array(
										'field'    => 'field_mwm_card_01_group_override_media',
										'operator' => '==',
										'value'    => '1',
									),
									array(
										'field'    => 'field_mwm_card_01_group_custom_media_type',
										'operator' => '==',
										'value'    => 'image',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_card_01_group_override_excerpt',
							'label'             => __( 'Sobrescribir extracto', 'bilky' ),
							'name'              => 'override_excerpt',
							'type'              => 'true_false',
							'instructions'      => __( 'Activa para usar un extracto personalizado en lugar del extracto de la página', 'bilky' ),
							'required'          => 0,
							'default_value'     => 0,
							'ui'                => 1,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_card_01_group_show_override_fields',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_card_01_group_custom_excerpt',
							'label'             => __( 'Extracto personalizado', 'bilky' ),
							'name'              => 'custom_excerpt',
							'type'              => 'textarea',
							'instructions'      => __( 'Si se rellena, este extracto se usará en lugar del extracto de la página', 'bilky' ),
							'required'          => 0,
							'rows'              => 4,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_card_01_group_show_override_fields',
										'operator' => '==',
										'value'    => '1',
									),
									array(
										'field'    => 'field_mwm_card_01_group_override_excerpt',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
						),
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/mwm-card-01-group',
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

