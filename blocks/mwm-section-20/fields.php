<?php
/**
 * ACF fields — MWM Section 20.
 *
 * @package bilky
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_mwm_section_20',
			'title'                 => __( 'MWM Section 20', 'bilky' ),
			'fields'                => array(
				array(
					'key'           => 'field_mwm_section_20_show_header',
					'label'         => __( 'Mostrar fila de cabecera', 'bilky' ),
					'name'          => 'show_header',
					'type'          => 'true_false',
					'instructions'  => __( 'Muestra la fila con los títulos de columnas.', 'bilky' ),
					'required'      => 0,
					'default_value' => 1,
					'ui'            => 1,
				),
				array(
					'key'               => 'field_mwm_section_20_header_clients',
					'label'             => __( 'Cabecera: clientes / módulos', 'bilky' ),
					'name'              => 'header_clients',
					'type'              => 'text',
					'instructions'      => __( 'Primera columna (puede dejarse vacía).', 'bilky' ),
					'required'          => 0,
					'default_value'     => '',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_20_show_header',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				array(
					'key'               => 'field_mwm_section_20_header_functions',
					'label'             => __( 'Cabecera: funciones', 'bilky' ),
					'name'              => 'header_functions',
					'type'              => 'text',
					'instructions'      => __( 'Segunda columna.', 'bilky' ),
					'required'          => 0,
					'default_value'     => __( 'Funciones', 'bilky' ),
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_20_show_header',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				array(
					'key'               => 'field_mwm_section_20_header_monthly',
					'label'             => __( 'Cabecera: precio mensual', 'bilky' ),
					'name'              => 'header_monthly_price',
					'type'              => 'text',
					'instructions'      => __( 'Tercera columna.', 'bilky' ),
					'required'          => 0,
					'default_value'     => __( 'Precio Mensual', 'bilky' ),
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_20_show_header',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				array(
					'key'               => 'field_mwm_section_20_sections',
					'label'             => __( 'Secciones (acordeón)', 'bilky' ),
					'name'              => 'sections',
					'type'              => 'repeater',
					'instructions'      => __( 'Cada sección es un acordeón con filas de módulos.', 'bilky' ),
					'required'          => 0,
					'collapsed'         => 'field_mwm_section_20_section_title',
					'min'               => 1,
					'max'               => 0,
					'layout'            => 'block',
					'button_label'      => __( 'Añadir sección', 'bilky' ),
					'sub_fields'        => array(
						array(
							'key'          => 'field_mwm_section_20_section_title',
							'label'        => __( 'Título de la sección', 'bilky' ),
							'name'         => 'section_title',
							'type'         => 'text',
							'instructions' => __( 'Aparece en la cabecera del acordeón.', 'bilky' ),
							'required'     => 1,
						),
						array(
							'key'           => 'field_mwm_section_20_section_open',
							'label'         => __( 'Abierta por defecto', 'bilky' ),
							'name'          => 'default_open',
							'type'          => 'true_false',
							'instructions'  => __( 'Si está activo, el panel se muestra expandido al cargar.', 'bilky' ),
							'required'      => 0,
							'default_value' => 0,
							'ui'            => 1,
						),
						array(
							'key'           => 'field_mwm_section_20_rows',
							'label'         => __( 'Filas', 'bilky' ),
							'name'          => 'rows',
							'type'          => 'repeater',
							'instructions'  => __( 'Cliente/módulo (pill), descripción y precio.', 'bilky' ),
							'required'        => 0,
							'collapsed'       => 'field_mwm_section_20_row_client_badge',
							'min'             => 0,
							'max'             => 0,
							'layout'          => 'block',
							'button_label'    => __( 'Añadir fila', 'bilky' ),
							'sub_fields'      => array(
								array(
									'key'          => 'field_mwm_section_20_row_client_badge',
									'label'        => __( 'Cliente / módulo (pill)', 'bilky' ),
									'name'         => 'client_badge',
									'type'         => 'text',
									'instructions' => __( 'Ej.: M. Factuconnect (Empresas).', 'bilky' ),
									'required'     => 1,
								),
								array(
									'key'          => 'field_mwm_section_20_row_functions_text',
									'label'        => __( 'Funciones', 'bilky' ),
									'name'         => 'functions_text',
									'type'         => 'textarea',
									'instructions' => __( 'Descripción del módulo (varias líneas permitidas).', 'bilky' ),
									'required'     => 0,
									'rows'         => 3,
									'new_lines'    => 'br',
								),
								array(
									'key'           => 'field_mwm_section_20_row_price_mode',
									'label'         => __( 'Tipo de precio', 'bilky' ),
									'name'          => 'price_mode',
									'type'          => 'select',
									'instructions'  => __( 'Mercado/asesor según diseño, o texto libre (ej. descuento).', 'bilky' ),
									'required'      => 1,
									'choices'       => array(
										'comparison' => __( 'Mercado / asesor', 'bilky' ),
										'custom'     => __( 'Texto libre', 'bilky' ),
									),
									'default_value' => 'comparison',
									'return_format' => 'value',
									'ui'            => 1,
								),
								array(
									'key'               => 'field_mwm_section_20_row_price_strike',
									'label'             => __( 'Precio mercado (tachado)', 'bilky' ),
									'name'              => 'price_strike',
									'type'              => 'text',
									'instructions'      => __( 'Ej.: 19,99€', 'bilky' ),
									'required'          => 0,
									'conditional_logic' => array(
										array(
											array(
												'field'    => 'field_mwm_section_20_row_price_mode',
												'operator' => '==',
												'value'    => 'comparison',
											),
										),
									),
								),
								array(
									'key'               => 'field_mwm_section_20_row_price_market_label',
									'label'             => __( 'Etiqueta tras precio mercado', 'bilky' ),
									'name'              => 'price_market_label',
									'type'              => 'text',
									'instructions'      => __( 'Ej.: mercado', 'bilky' ),
									'required'          => 0,
									'default_value'     => __( 'mercado', 'bilky' ),
									'conditional_logic' => array(
										array(
											array(
												'field'    => 'field_mwm_section_20_row_price_mode',
												'operator' => '==',
												'value'    => 'comparison',
											),
										),
									),
								),
								array(
									'key'               => 'field_mwm_section_20_row_price_advisor',
									'label'             => __( 'Precio asesor', 'bilky' ),
									'name'              => 'price_advisor',
									'type'              => 'text',
									'instructions'      => __( 'Ej.: 9,99€', 'bilky' ),
									'required'          => 0,
									'conditional_logic' => array(
										array(
											array(
												'field'    => 'field_mwm_section_20_row_price_mode',
												'operator' => '==',
												'value'    => 'comparison',
											),
										),
									),
								),
								array(
									'key'               => 'field_mwm_section_20_row_price_advisor_label',
									'label'             => __( 'Etiqueta precio asesor', 'bilky' ),
									'name'              => 'price_advisor_label',
									'type'              => 'text',
									'instructions'      => __( 'Ej.: asesor', 'bilky' ),
									'required'          => 0,
									'default_value'     => __( 'asesor', 'bilky' ),
									'conditional_logic' => array(
										array(
											array(
												'field'    => 'field_mwm_section_20_row_price_mode',
												'operator' => '==',
												'value'    => 'comparison',
											),
										),
									),
								),
								array(
									'key'               => 'field_mwm_section_20_row_price_custom_strong',
									'label'             => __( 'Texto destacado', 'bilky' ),
									'name'              => 'price_custom_strong',
									'type'              => 'text',
									'instructions'      => __( 'Parte en negrita (ej.: -50%).', 'bilky' ),
									'required'          => 0,
									'conditional_logic' => array(
										array(
											array(
												'field'    => 'field_mwm_section_20_row_price_mode',
												'operator' => '==',
												'value'    => 'custom',
											),
										),
									),
								),
								array(
									'key'               => 'field_mwm_section_20_row_price_custom_rest',
									'label'             => __( 'Texto complementario', 'bilky' ),
									'name'              => 'price_custom_rest',
									'type'              => 'text',
									'instructions'      => __( 'Resto del mensaje (ej.: en el plan).', 'bilky' ),
									'required'          => 0,
									'conditional_logic' => array(
										array(
											array(
												'field'    => 'field_mwm_section_20_row_price_mode',
												'operator' => '==',
												'value'    => 'custom',
											),
										),
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
						'value'    => 'acf/mwm-section-20',
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
