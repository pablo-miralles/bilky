<?php
/**
 * Campos ACF para el CPT Clientes
 * Campo: URL del cliente
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_clientes_fields',
			'title'                 => __( 'Campos del Cliente', 'bilky' ),
			'fields'                => array(
				array(
					'key'               => 'field_cliente_url',
					'label'             => __( 'URL de la página del cliente', 'bilky' ),
					'name'              => 'cliente_url',
					'type'              => 'url',
					'instructions'      => __( 'URL de la página web del cliente', 'bilky' ),
					'required'          => 0,
					'default_value'     => '',
					'placeholder'       => 'https://ejemplo.com',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'clientes',
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

