<?php
/**
 * Campos ACF para posts y Centro de ayuda
 * Campos: media (video YouTube, video Vimeo, video WordPress, imagen) y puesto del autor
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_post_fields',
			'title'                 => __( 'Campos del Post', 'bilky' ),
			'fields'                => array(
				array(
					'key'               => 'field_post_media_type',
					'label'             => __( 'Tipo de media', 'bilky' ),
					'name'              => 'post_media_type',
					'type'              => 'select',
					'instructions'      => __( 'Selecciona si quieres usar video de YouTube, video de Vimeo, video de WordPress o usar thumbnail', 'bilky' ),
					'required'          => 0,
					'choices'           => array(
						'video'           => __( 'Video (YouTube)', 'bilky' ),
						'video-vimeo'     => __( 'Video (Vimeo)', 'bilky' ),
						'video-wordpress' => __( 'Video (WordPress)', 'bilky' ),
						'thumbnail'       => __( 'Usar thumbnail', 'bilky' ),
					),
					'default_value'     => 'thumbnail',
					'allow_null'        => 0,
					'multiple'          => 0,
					'ui'                => 1,
					'ajax'              => 0,
					'return_format'     => 'value',
				),
				array(
					'key'               => 'field_post_video_url',
					'label'             => __( 'URL del video (YouTube)', 'bilky' ),
					'name'              => 'post_video_url',
					'type'              => 'url',
					'instructions'      => __( 'Ingresa la URL del video de YouTube. Se usará la miniatura automáticamente.', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_post_media_type',
								'operator' => '==',
								'value'    => 'video',
							),
						),
					),
				),
				array(
					'key'               => 'field_post_video_vimeo_url',
					'label'             => __( 'URL del video (Vimeo)', 'bilky' ),
					'name'              => 'post_video_vimeo_url',
					'type'              => 'url',
					'instructions'      => __( 'Ingresa la URL del video de Vimeo. Se usará la imagen destacada de la entrada como miniatura con botón de reproducción.', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_post_media_type',
								'operator' => '==',
								'value'    => 'video-vimeo',
							),
						),
					),
				),
				array(
					'key'               => 'field_post_video_wordpress_url',
					'label'             => __( 'URL del video (WordPress)', 'bilky' ),
					'name'              => 'post_video_wordpress_url',
					'type'              => 'url',
					'instructions'      => __( 'Ingresa la URL del video subido a WordPress (copia la URL del archivo de video desde la librería de medios). El video se mostrará pausado sin controles.', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_post_media_type',
								'operator' => '==',
								'value'    => 'video-wordpress',
							),
						),
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'post',
					),
				),
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'centro_de_ayuda',
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

	// Campo para el puesto del autor (user meta)
	acf_add_local_field_group(
		array(
			'key'                   => 'group_user_fields',
			'title'                 => __( 'Información del Usuario', 'bilky' ),
			'fields'                => array(
				array(
					'key'               => 'field_user_job',
					'label'             => __( 'Puesto', 'bilky' ),
					'name'              => 'user_job',
					'type'              => 'text',
					'instructions'      => __( 'Puesto o cargo del usuario', 'bilky' ),
					'required'          => 0,
					'default_value'     => '',
					'placeholder'       => __( 'Ej: Director de Marketing', 'bilky' ),
				),
				array(
					'key'               => 'field_user_show_as_author_in_posts',
					'label'             => __( 'Mostrar como autor en todos los posts', 'bilky' ),
					'name'              => 'user_show_as_author_in_posts',
					'type'              => 'true_false',
					'instructions'      => __( 'Si está marcado, este usuario se mostrará como autor en la sección de autor de todos los posts, en lugar del autor real de cada entrada.', 'bilky' ),
					'required'          => 0,
					'default_value'     => 0,
					'ui'                => 1,
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'user_form',
						'operator' => '==',
						'value'    => 'all',
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

