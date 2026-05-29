<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_mwm_section_18',
			'title'                 => __( 'MWM Section 18', 'bilky' ),
			'fields'                => array(
				array(
					'key'           => 'field_mwm_section_18_show_header',
					'label'         => __( 'Mostrar fila de cabecera', 'bilky' ),
					'name'          => 'show_header',
					'type'          => 'true_false',
					'instructions'  => __( 'Muestra la fila con los nombres de las columnas (Funcionalidades, planes, etc.).', 'bilky' ),
					'required'      => 0,
					'default_value' => 1,
					'ui'            => 1,
				),
				array(
					'key'           => 'field_mwm_section_18_header_feature',
					'label'         => __( 'Cabecera: columna funcionalidades', 'bilky' ),
					'name'          => 'header_feature',
					'type'          => 'text',
					'instructions'  => __( 'Texto de la primera columna.', 'bilky' ),
					'required'      => 0,
					'default_value' => __( 'Funcionalidades', 'bilky' ),
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_18_show_header',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				array(
					'key'           => 'field_mwm_section_18_header_basic',
					'label'         => __( 'Cabecera: columna 1', 'bilky' ),
					'name'          => 'header_basic',
					'type'          => 'text',
					'instructions'  => __( 'Texto de la segunda columna (primer plan).', 'bilky' ),
					'required'      => 0,
					'default_value' => __( 'Columna 1', 'bilky' ),
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_18_show_header',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				array(
					'key'           => 'field_mwm_section_18_header_premium',
					'label'         => __( 'Cabecera: columna 2', 'bilky' ),
					'name'          => 'header_premium',
					'type'          => 'text',
					'instructions'  => __( 'Texto de la tercera columna (segundo plan).', 'bilky' ),
					'required'      => 0,
					'default_value' => __( 'Columna 2', 'bilky' ),
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_18_show_header',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				array(
					'key'           => 'field_mwm_section_18_header_third',
					'label'         => __( 'Cabecera: columna 3 (opcional)', 'bilky' ),
					'name'          => 'header_third',
					'type'          => 'text',
					'instructions'  => __( 'Si este campo está vacío, la columna extra no se mostrará.', 'bilky' ),
					'required'      => 0,
					'default_value' => '',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_18_show_header',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				array(
					'key'               => 'field_mwm_section_18_sections',
					'label'             => __( 'Secciones (acordeón)', 'bilky' ),
					'name'              => 'sections',
					'type'              => 'repeater',
					'instructions'      => __( 'Cada sección es un acordeón con su tabla de filas.', 'bilky' ),
					'required'          => 0,
					'collapsed'         => 'field_mwm_section_18_section_title',
					'min'               => 1,
					'max'               => 0,
					'layout'            => 'block',
					'button_label'      => __( 'Añadir sección', 'bilky' ),
					'sub_fields'        => array(
						array(
							'key'           => 'field_mwm_section_18_section_title',
							'label'         => __( 'Título de la sección', 'bilky' ),
							'name'          => 'section_title',
							'type'          => 'text',
							'instructions'  => __( 'Aparece en la cabecera del acordeón.', 'bilky' ),
							'required'      => 1,
						),
						array(
							'key'           => 'field_mwm_section_18_section_open',
							'label'         => __( 'Abierta por defecto', 'bilky' ),
							'name'          => 'default_open',
							'type'          => 'true_false',
							'instructions'  => __( 'Si está activo, el panel se muestra expandido al cargar.', 'bilky' ),
							'required'      => 0,
							'default_value' => 0,
							'ui'            => 1,
						),
						array(
							'key'               => 'field_mwm_section_18_rows',
							'label'             => __( 'Filas de la tabla', 'bilky' ),
							'name'              => 'rows',
							'type'              => 'repeater',
							'instructions'      => __( 'Cada fila: nombre de la funcionalidad e inclusión en cada plan.', 'bilky' ),
							'required'          => 0,
							'collapsed'         => 'field_mwm_section_18_row_label',
							'min'               => 0,
							'max'               => 0,
							'layout'            => 'table',
							'button_label'      => __( 'Añadir fila', 'bilky' ),
							'sub_fields'        => array(
								array(
									'key'   => 'field_mwm_section_18_row_label',
									'label' => __( 'Funcionalidad', 'bilky' ),
									'name'  => 'feature_label',
									'type'  => 'text',
									'required' => 0,
								),
								array(
									'key'           => 'field_mwm_section_18_row_basic',
									'label'         => __( 'Incluido en Básico', 'bilky' ),
									'name'          => 'basic_included',
									'type'          => 'true_false',
									'required'      => 0,
									'default_value' => 1,
									'ui'            => 1,
								),
								array(
									'key'           => 'field_mwm_section_18_row_premium',
									'label'         => __( 'Incluido en columna 2', 'bilky' ),
									'name'          => 'premium_included',
									'type'          => 'true_false',
									'required'      => 0,
									'default_value' => 1,
									'ui'            => 1,
								),
								array(
									'key'           => 'field_mwm_section_18_row_third',
									'label'         => __( 'Incluido en columna 3', 'bilky' ),
									'name'          => 'third_included',
									'type'          => 'true_false',
									'required'      => 0,
									'default_value' => 1,
									'ui'            => 1,
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
						'value'    => 'acf/mwm-section-18',
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
