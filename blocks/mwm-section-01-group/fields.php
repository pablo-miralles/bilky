<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_mwm_section_01_group',
			'title'                 => __( 'MWM Section 01 Group', 'bilky' ),
			'fields'                => array(
				array(
					'key'               => 'field_mwm_section_01_group_sections',
					'label'             => __( 'Secciones', 'bilky' ),
					'name'              => 'sections',
					'type'              => 'repeater',
					'instructions'      => __( 'Añade dos secciones tipo section-01', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'          => 'field_mwm_section_01_group_section_fields',
					'min'                => 2,
					'max'                => 2,
					'layout'             => 'block',
					'button_label'       => __( 'Añadir Sección', 'bilky' ),
					'sub_fields'         => array(
						array(
							'key'               => 'field_mwm_section_01_group_section_fields',
							'label'             => __( 'Configuración de la sección', 'bilky' ),
							'name'              => 'section_fields',
							'type'              => 'clone',
							'instructions'      => __( 'Configuración completa de la sección', 'bilky' ),
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'clone'             => array(
								0 => 'group_mwm_section_01',
							),
							'display'           => 'seamless',
							'layout'            => 'block',
							'prefix_label'      => 0,
							'prefix_name'       => 1,
						),
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/mwm-section-01-group',
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

