<?php
/**
 * Campos ACF para el CPT Partners
 * Campos: URL, orden y prioridad
 */
 
if ( function_exists( 'acf_add_local_field_group' ) ) :
 
	acf_add_local_field_group(
		array(
			'key'                   => 'group_partners_fields',
			'title'                 => __( 'Campos del Partner', 'bilky' ),
			'fields'                => array(
				array(
					'key'           => 'field_partner_url',
					'label'         => __( 'URL del partner', 'bilky' ),
					'name'          => 'partner_url',
					'type'          => 'url',
					'instructions'  => __( 'URL de la web del partner', 'bilky' ),
					'required'      => 0,
					'default_value' => '',
					'placeholder'   => 'https://ejemplo.com',
				),
				array(
					'key'           => 'field_partner_order',
					'label'         => __( 'Orden', 'bilky' ),
					'name'          => 'partner_order',
					'type'          => 'number',
					'instructions'  => __( 'Orden de aparición del partner', 'bilky' ),
					'required'      => 0,
					'default_value' => '',
				),
				array(
					'key'           => 'field_partner_priority',
					'label'         => __( 'Prioridad', 'bilky' ),
					'name'          => 'partner_priority',
					'type'          => 'number',
					'instructions'  => __( 'Prioridad (según el origen de datos)', 'bilky' ),
					'required'      => 0,
					'default_value' => '',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'partners',
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

