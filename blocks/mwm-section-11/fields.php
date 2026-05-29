<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_mwm_section_11',
			'title'                 => __( 'MWM Section 11', 'bilky' ),
			'fields'                => array(
				// Header fields
				array(
					'key'               => 'field_mwm_section_11_breadcrumb_text_1',
					'label'             => __( 'Breadcrumb 1', 'bilky' ),
					'name'              => 'breadcrumb_text_1',
					'type'              => 'text',
					'instructions'      => __( 'Texto del primer breadcrumb', 'bilky' ),
					'required'          => 0,
				),
				array(
					'key'               => 'field_mwm_section_11_breadcrumb_text_2',
					'label'             => __( 'Breadcrumb 2', 'bilky' ),
					'name'              => 'breadcrumb_text_2',
					'type'              => 'text',
					'instructions'      => __( 'Texto del segundo breadcrumb', 'bilky' ),
					'required'          => 0,
				),
				array(
					'key'               => 'field_mwm_section_11_title',
					'label'             => __( 'Título', 'bilky' ),
					'name'              => 'title',
					'type'              => 'textarea',
					'instructions'      => __( 'Título principal (se puede dividir en líneas)', 'bilky' ),
					'required'          => 0,
					'rows'              => 3,
				),
				array(
					'key'               => 'field_mwm_section_11_text_body',
					'label'             => __( 'Texto del cuerpo', 'bilky' ),
					'name'              => 'text_body',
					'type'              => 'wysiwyg',
					'instructions'      => __( 'Texto descriptivo que aparece debajo del título', 'bilky' ),
					'required'          => 0,
					'rows'              => 4,
					'toolbar'           => 'basic',
					'media_upload'      => 0,
				),
				// Sections repeater
				array(
					'key'               => 'field_mwm_section_11_sections',
					'label'             => __( 'Secciones', 'bilky' ),
					'name'              => 'sections',
					'type'              => 'repeater',
					'instructions'      => __( 'Añade secciones tipo section-01 (sin posición de media arriba/abajo y sin lista)', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'          => 'field_mwm_section_11_section_title',
					'min'                => 0,
					'max'                => 0,
					'layout'             => 'block',
					'button_label'       => __( 'Añadir Sección', 'bilky' ),
					'sub_fields'         => array(
						array(
							'key'               => 'field_mwm_section_11_section_position_media',
							'label'             => __( 'Posición del media', 'bilky' ),
							'name'              => 'position_media',
							'type'              => 'select',
							'instructions'      => __( 'Selecciona la posición del bloque de media (imagen).', 'bilky' ),
							'required'          => 0,
							'choices'           => array(
								'left'  => __( 'Media izquierda', 'bilky' ),
								'right' => __( 'Media derecha', 'bilky' ),
							),
							'default_value'     => 'left',
							'allow_null'        => 0,
							'multiple'          => 0,
							'ui'                => 1,
							'ajax'              => 0,
							'return_format'     => 'value',
						),
						array(
							'key'               => 'field_mwm_section_11_section_show_media',
							'label'             => __( 'Mostrar media', 'bilky' ),
							'name'              => 'show_media',
							'type'              => 'true_false',
							'instructions'      => __( 'Activa o desactiva el bloque de media.', 'bilky' ),
							'required'          => 0,
							'default_value'     => 1,
							'ui'                => 1,
						),
						array(
							'key'               => 'field_mwm_section_11_section_media_type',
							'label'             => __( 'Tipo de media', 'bilky' ),
							'name'              => 'media_type',
							'type'              => 'select',
							'instructions'      => __( 'Selecciona si quieres usar video o imagen.', 'bilky' ),
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
										'field'    => 'field_mwm_section_11_section_show_media',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_section_11_section_media_image',
							'label'             => __( 'Imagen', 'bilky' ),
							'name'              => 'media_image',
							'type'              => 'image',
							'instructions'      => __( 'Sube una imagen, si se deja vacío se mostrará un placeholder', 'bilky' ),
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_section_11_section_show_media',
										'operator' => '==',
										'value'    => '1',
									),
									array(
										'field'    => 'field_mwm_section_11_section_media_type',
										'operator' => '==',
										'value'    => 'image',
									),
								),
							),
							'return_format'     => 'ID',
							'preview_size'      => 'medium',
							'library'           => 'all',
						),
						array(
							'key'               => 'field_mwm_section_11_section_media_video',
							'label'             => __( 'Video', 'bilky' ),
							'name'              => 'media_video',
							'type'              => 'file',
							'instructions'      => __( 'Sube un archivo de video. El video se reproducirá automáticamente, sin sonido y en bucle.', 'bilky' ),
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_section_11_section_show_media',
										'operator' => '==',
										'value'    => '1',
									),
									array(
										'field'    => 'field_mwm_section_11_section_media_type',
										'operator' => '==',
										'value'    => 'video',
									),
								),
							),
							'return_format'     => 'url',
							'library'           => 'all',
							'min_size'          => '',
							'max_size'          => '',
							'mime_types'        => 'mp4,webm,ogg',
						),
						array(
							'key'               => 'field_mwm_section_11_section_show_badge',
							'label'             => __( 'Mostrar botón superior (badge)', 'bilky' ),
							'name'              => 'show_badge',
							'type'              => 'true_false',
							'instructions'      => __( 'Activa o desactiva el botón superior tipo badge.', 'bilky' ),
							'required'          => 0,
							'default_value'     => 1,
							'ui'                => 1,
						),
						array(
							'key'               => 'field_mwm_section_11_section_badge_text',
							'label'             => __( 'Texto del badge', 'bilky' ),
							'name'              => 'badge_text',
							'type'              => 'text',
							'instructions'      => __( 'Texto del botón outline superior.', 'bilky' ),
							'required'          => 0,
							'default_value'     => 'Button',
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_section_11_section_show_badge',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_section_11_section_badge_url',
							'label'             => __( 'URL del badge', 'bilky' ),
							'name'              => 'badge_url',
							'type'              => 'text',
							'instructions'      => __( 'URL del botón superior (si se deja vacío se renderiza como botón).', 'bilky' ),
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_section_11_section_show_badge',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_section_11_section_title',
							'label'             => __( 'Título', 'bilky' ),
							'name'              => 'title',
							'type'              => 'text',
							'instructions'      => __( 'Título principal.', 'bilky' ),
							'required'          => 0,
							'default_value'     => __( '¿Cómo funciona Bilky?', 'bilky' ),
						),
						array(
							'key'               => 'field_mwm_section_11_section_description',
							'label'             => __( 'Descripción', 'bilky' ),
							'name'              => 'description',
							'type'              => 'textarea',
							'instructions'      => __( 'Texto descriptivo.', 'bilky' ),
							'required'          => 0,
							'rows'              => 4,
							'default_value'     => __( 'Bilky es una plataforma digital que une todos tus procesos —facturación, documentos, comunicación y RRHH— en portales conectados entre sí, para que asesorías, empresas y empleados trabajen siempre alineados.', 'bilky' ),
						),
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/mwm-section-11',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
		)
	);

endif;

