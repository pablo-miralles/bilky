<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_mwm_image_video_automatic_01',
			'title'                 => __( 'MWM Image Video Automatic 01', 'bilky' ),
			'fields'                => array(
				array(
					'key'               => 'field_mwm_image_video_automatic_01_media_type',
					'label'             => __( 'Tipo de media', 'bilky' ),
					'name'              => 'media_type',
					'type'              => 'select',
					'instructions'      => __( 'Selecciona si quieres usar video de WordPress o imagen', 'bilky' ),
					'required'          => 0,
					'choices'           => array(
						'video-wordpress' => __( 'Video (WordPress)', 'bilky' ),
						'image'           => __( 'Imagen', 'bilky' ),
					),
					'default_value'     => 'image',
					'allow_null'        => 0,
					'multiple'          => 0,
					'ui'                => 1,
					'ajax'              => 0,
					'return_format'     => 'value',
				),
				array(
					'key'               => 'field_mwm_image_video_automatic_01_video_wordpress_url',
					'label'             => __( 'URL del video (WordPress)', 'bilky' ),
					'name'              => 'video_wordpress_url',
					'type'              => 'url',
					'instructions'      => __( 'Ingresa la URL del video subido a WordPress (copia la URL del archivo de video desde la librería de medios). El video se reproducirá automáticamente, sin sonido y sin controles.', 'bilky' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_mwm_image_video_automatic_01_media_type',
								'operator' => '==',
								'value'    => 'video-wordpress',
							),
						),
					),
				),
				array(
					'key'               => 'field_mwm_image_video_automatic_01_image',
					'label'             => __( 'Imagen', 'bilky' ),
					'name'              => 'image',
					'type'              => 'image',
					'instructions'      => __( 'Imagen a mostrar. Si es video, esta imagen se usará como poster (thumbnail) del video.', 'bilky' ),
					'required'          => 0,
					'return_format'     => 'ID',
					'preview_size'      => 'medium',
					'library'           => 'all',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/mwm-image-video-automatic-01',
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

