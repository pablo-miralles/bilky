<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_mwm_media_video_play',
    'title' => 'MWM Media Video Play 01',
    'fields' => array(
        array(
            'key' => 'field_mwm_media_video_play_media_type',
            'label' => 'Tipo de media',
            'name' => 'media_type',
            'type' => 'select',
            'instructions' => 'Selecciona si quieres usar video de YouTube, video de WordPress o imagen',
            'required' => 0,
            'choices' => array(
                'video' => 'Video (YouTube)',
                'video-wordpress' => 'Video (WordPress)',
                'image' => 'Imagen',
            ),
            'default_value' => 'video',
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 1,
            'ajax' => 0,
            'return_format' => 'value',
        ),
        array(
            'key' => 'field_mwm_media_video_play_video_url',
            'label' => 'URL del video (YouTube)',
            'name' => 'video_url',
            'type' => 'url',
            'instructions' => 'Ingresa la URL del video de YouTube. Si no hay imagen, se usará la miniatura automáticamente.',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_media_video_play_media_type',
                        'operator' => '==',
                        'value' => 'video',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_media_video_play_video_wordpress_url',
            'label' => __( 'URL del video (WordPress)', 'bilky' ),
            'name' => 'video_wordpress_url',
            'type' => 'url',
            'instructions' => __( 'Ingresa la URL del video subido a WordPress (copia la URL del archivo de video desde la librería de medios).', 'bilky' ),
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_media_video_play_media_type',
                        'operator' => '==',
                        'value' => 'video-wordpress',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_media_video_play_image',
            'label' => 'Imagen',
            'name' => 'image',
            'type' => 'image',
            'instructions' => 'Imagen de fondo. Si es video y no hay imagen, se usará la miniatura de YouTube automáticamente.',
            'required' => 0,
            'return_format' => 'ID',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
        array(
            'key' => 'field_mwm_media_video_play_button_text',
            'label' => 'Texto del botón',
            'name' => 'button_text',
            'type' => 'text',
            'instructions' => 'Texto del botón para abrir el video',
            'required' => 0,
            'default_value' => 'Ver video',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/mwm-media-video-play-01',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
));

endif;

