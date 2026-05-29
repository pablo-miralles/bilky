<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_mwm_section_19',
			'title'                 => __( 'MWM Section 19', 'bilky' ),
			'fields'                => array(
				array(
					'key'               => 'field_mwm_section_19_sections',
					'label'             => __( 'Secciones (acordeón)', 'bilky' ),
					'name'              => 'sections',
					'type'              => 'repeater',
					'instructions'      => __( 'Cada sección es un acordeón con título y contenido enriquecido.', 'bilky' ),
					'required'          => 0,
					'collapsed'         => 'field_mwm_section_19_section_title',
					'min'               => 1,
					'max'               => 0,
					'layout'            => 'block',
					'button_label'      => __( 'Añadir sección', 'bilky' ),
					'sub_fields'        => array(
						array(
							'key'           => 'field_mwm_section_19_section_title',
							'label'         => __( 'Título de la sección', 'bilky' ),
							'name'          => 'section_title',
							'type'          => 'text',
							'instructions'  => __( 'Aparece en la cabecera del acordeón.', 'bilky' ),
							'required'      => 1,
						),
						array(
							'key'           => 'field_mwm_section_19_section_open',
							'label'         => __( 'Abierta por defecto', 'bilky' ),
							'name'          => 'default_open',
							'type'          => 'true_false',
							'instructions'  => __( 'Si está activo, el panel se muestra expandido al cargar.', 'bilky' ),
							'required'      => 0,
							'default_value' => 0,
							'ui'            => 1,
						),
						array(
							'key'           => 'field_mwm_section_19_section_body',
							'label'         => __( 'Contenido', 'bilky' ),
							'name'          => 'section_body',
							'type'          => 'wysiwyg',
							'required'      => 0,
							'tabs'          => 'all',
							'toolbar'       => 'full',
							'media_upload'  => 1,
							'delay'         => 0,
						),
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/mwm-section-19',
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
