<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_mwm_section_16',
			'title'                 => __( 'MWM Section 16', 'bilky' ),
			'fields'                => array(
				array(
					'key'               => 'field_mwm_section_16_show_breadcrumbs',
					'label'             => __( 'Mostrar Breadcrumbs', 'bilky' ),
					'name'              => 'show_breadcrumbs',
					'type'              => 'true_false',
					'instructions'      => __( 'Activa o desactiva la sección de breadcrumbs', 'bilky' ),
					'required'          => 0,
					'default_value'     => 1,
					'ui'                => 1,
				),
				array(
					'key'               => 'field_mwm_section_16_breadcrumb_button_text',
					'label'             => __( 'Texto del botón breadcrumb', 'bilky' ),
					'name'              => 'breadcrumb_button_text',
					'type'              => 'text',
					'instructions'      => __( 'Texto del botón outline en los breadcrumbs (solo texto, sin link)', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_16_show_breadcrumbs',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				array(
					'key'               => 'field_mwm_section_16_show_breadcrumb_01',
					'label'             => __( 'Mostrar Breadcrumb 01', 'bilky' ),
					'name'              => 'show_breadcrumb_01',
					'type'              => 'true_false',
					'instructions'      => __( 'Muestra el primer breadcrumb adicional', 'bilky' ),
					'required'          => 0,
					'default_value'     => 1,
					'ui'                => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_16_show_breadcrumbs',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				array(
					'key'               => 'field_mwm_section_16_breadcrumb_01_text',
					'label'             => __( 'Texto Breadcrumb 01', 'bilky' ),
					'name'              => 'breadcrumb_01_text',
					'type'              => 'text',
					'instructions'      => __( 'Texto del primer breadcrumb adicional', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_16_show_breadcrumb_01',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				array(
					'key'               => 'field_mwm_section_16_show_title',
					'label'             => __( 'Mostrar título', 'bilky' ),
					'name'              => 'show_title',
					'type'              => 'true_false',
					'instructions'      => __( 'Activa o desactiva el título', 'bilky' ),
					'required'          => 0,
					'default_value'     => 1,
					'ui'                => 1,
				),
				array(
					'key'               => 'field_mwm_section_16_title',
					'label'             => __( 'Título', 'bilky' ),
					'name'              => 'title',
					'type'              => 'textarea',
					'instructions'      => __( 'Título principal (se puede dividir en líneas)', 'bilky' ),
					'required'          => 0,
					'rows'              => 3,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_16_show_title',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				array(
					'key'               => 'field_mwm_section_16_show_description',
					'label'             => __( 'Mostrar descripción', 'bilky' ),
					'name'              => 'show_description',
					'type'              => 'true_false',
					'instructions'      => __( 'Activa o desactiva la descripción', 'bilky' ),
					'required'          => 0,
					'default_value'     => 1,
					'ui'                => 1,
				),
				array(
					'key'               => 'field_mwm_section_16_description',
					'label'             => __( 'Descripción', 'bilky' ),
					'name'              => 'description',
					'type'              => 'textarea',
					'instructions'      => __( 'Texto descriptivo que aparece debajo del título', 'bilky' ),
					'required'          => 0,
					'rows'              => 4,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_16_show_description',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				array(
					'key'               => 'field_mwm_section_16_source_type',
					'label'             => __( 'Fuente de datos', 'bilky' ),
					'name'              => 'source_type',
					'type'              => 'select',
					'instructions'      => __( 'Elige de dónde obtener la información de las cards', 'bilky' ),
					'required'          => 0,
					'choices'           => array(
						'cpt'    => __( 'Usar CPT (Custom Post Type)', 'bilky' ),
						'manual' => __( 'Crear cards manualmente', 'bilky' ),
					),
					'default_value'     => 'manual',
					'allow_null'        => 0,
					'multiple'          => 0,
					'ui'                => 1,
					'ajax'              => 0,
					'return_format'     => 'value',
				),
				array(
					'key'               => 'field_mwm_section_16_cards_image_only',
					'label'             => __( 'Mostrar cards con solo imagen', 'bilky' ),
					'name'              => 'cards_image_only',
					'type'              => 'true_false',
					'instructions'      => __( 'Cuando está activado, solo se mostrará la imagen en las cards (sin título, descripción ni botón)', 'bilky' ),
					'required'          => 0,
					'default_value'     => 0,
					'ui'                => 1,
				),
				array(
					'key'               => 'field_mwm_section_16_post_type',
					'label'             => __( 'Tipo de contenido (CPT)', 'bilky' ),
					'name'              => 'post_type',
					'type'              => 'select',
					'instructions'      => __( 'Selecciona el Custom Post Type del que obtener las cards. El filtro usará automáticamente la primera taxonomía pública del CPT', 'bilky' ),
					'required'          => 0,
					'choices'           => array(),
					'default_value'     => '',
					'allow_null'        => 0,
					'multiple'          => 0,
					'ui'                => 1,
					'ajax'              => 0,
					'return_format'     => 'value',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_16_source_type',
								'operator' => '==',
								'value'    => 'cpt',
							),
						),
					),
				),
				array(
					'key'               => 'field_mwm_section_16_cpt_button_text',
					'label'             => __( 'Texto para botones de card', 'bilky' ),
					'name'              => 'cpt_button_text',
					'type'              => 'text',
					'instructions'      => __( 'Texto que aparecerá en los botones de las cards. Si no se rellena, se usará "Web" por defecto.', 'bilky' ),
					'required'          => 0,
					'default_value'     => '',
					'placeholder'       => __( 'Web', 'bilky' ),
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_16_source_type',
								'operator' => '==',
								'value'    => 'cpt',
							),
						),
					),
				),
				array(
					'key'               => 'field_mwm_section_16_cards',
					'label'             => __( 'Cards', 'bilky' ),
					'name'              => 'cards',
					'type'              => 'repeater',
					'instructions'      => __( 'Añade las cards manualmente', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_section_16_source_type',
								'operator' => '==',
								'value'    => 'manual',
							),
						),
					),
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'         => 'field_mwm_section_16_card_title',
					'min'               => 1,
					'max'               => 0,
					'layout'            => 'block',
					'button_label'      => __( 'Añadir Card', 'bilky' ),
					'sub_fields'        => array(
						array(
							'key'               => 'field_mwm_section_16_card_image',
							'label'             => __( 'Imagen', 'bilky' ),
							'name'              => 'image',
							'type'              => 'image',
							'instructions'      => __( 'Imagen de la card', 'bilky' ),
							'required'          => 0,
							'return_format'     => 'id',
							'preview_size'      => 'medium',
							'library'           => 'all',
						),
						array(
							'key'               => 'field_mwm_section_16_card_title',
							'label'             => __( 'Título', 'bilky' ),
							'name'              => 'title',
							'type'              => 'text',
							'instructions'      => __( 'Título de la card', 'bilky' ),
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_section_16_cards_image_only',
										'operator' => '==',
										'value'    => '0',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_section_16_card_description',
							'label'             => __( 'Descripción', 'bilky' ),
							'name'              => 'description',
							'type'              => 'textarea',
							'instructions'      => __( 'Descripción de la card', 'bilky' ),
							'required'          => 0,
							'rows'              => 3,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_mwm_section_16_cards_image_only',
										'operator' => '==',
										'value'    => '0',
									),
								),
							),
						),
						array(
							'key'               => 'field_mwm_section_16_card_link',
							'label'             => __( 'Enlace del botón', 'bilky' ),
							'name'              => 'link',
							'type'              => 'link',
							'instructions'      => __( 'Enlace del botón "Visitar sitio". Si está vacío, no se mostrará botón', 'bilky' ),
							'required'          => 0,
							'return_format'     => 'array',
						),
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/mwm-section-16',
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

	// Función para poblar las opciones de post types
	add_filter( 'acf/load_field/name=post_type', 'mwm_section_16_load_post_types' );
	function mwm_section_16_load_post_types( $field ) {
		$field['choices'] = array();
		
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		// Excluir tipos de contenido del sistema
		$exclude = array( 'attachment', 'nav_menu_item', 'revision' );
		
		foreach ( $post_types as $post_type ) {
			if ( ! in_array( $post_type->name, $exclude, true ) ) {
				$field['choices'][ $post_type->name ] = $post_type->label;
			}
		}
		
		return $field;
	}

endif;
