<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_mwm_section_14',
    'title' => 'MWM Section 14',
    'fields' => array(
        array(
            'key' => 'field_mwm_section_14_show_breadcrumbs',
            'label' => 'Mostrar Breadcrumbs',
            'name' => 'show_breadcrumbs',
            'type' => 'true_false',
            'instructions' => 'Activa o desactiva la sección de breadcrumbs',
            'required' => 0,
            'default_value' => 1,
            'ui' => 1,
        ),
        array(
            'key' => 'field_mwm_section_14_breadcrumb_button_text',
            'label' => 'Texto del botón breadcrumb',
            'name' => 'breadcrumb_button_text',
            'type' => 'text',
            'instructions' => 'Texto del botón outline en los breadcrumbs',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_14_show_breadcrumbs',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_14_breadcrumb_button_link',
            'label' => 'Link del botón breadcrumb',
            'name' => 'breadcrumb_button_link',
            'type' => 'text',
            'instructions' => 'Link del botón outline en los breadcrumbs',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_14_show_breadcrumbs',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_14_show_breadcrumb_01',
            'label' => 'Mostrar Breadcrumb 01',
            'name' => 'show_breadcrumb_01',
            'type' => 'true_false',
            'instructions' => 'Muestra el primer breadcrumb adicional',
            'required' => 0,
            'default_value' => 1,
            'ui' => 1,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_14_show_breadcrumbs',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_14_breadcrumb_01_text',
            'label' => 'Texto Breadcrumb 01',
            'name' => 'breadcrumb_01_text',
            'type' => 'text',
            'instructions' => 'Texto del primer breadcrumb adicional',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_14_show_breadcrumb_01',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_14_title',
            'label' => 'Título',
            'name' => 'title',
            'type' => 'textarea',
            'instructions' => 'Título principal del hero (se puede dividir en líneas)',
            'required' => 0,
            'rows' => 3,
        ),
        array(
            'key' => 'field_mwm_section_14_show_text_body',
            'label' => 'Mostrar texto del cuerpo',
            'name' => 'show_text_body',
            'type' => 'true_false',
            'instructions' => 'Activa o desactiva el texto descriptivo',
            'required' => 0,
            'default_value' => 1,
            'ui' => 1,
        ),
        array(
            'key' => 'field_mwm_section_14_text_body',
            'label' => 'Texto del cuerpo',
            'name' => 'text_body',
            'type' => 'textarea',
            'instructions' => 'Texto descriptivo que aparece debajo del título',
            'required' => 0,
            'rows' => 4,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_14_show_text_body',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_14_show_btn',
            'label' => 'Mostrar botón',
            'name' => 'show_btn',
            'type' => 'true_false',
            'instructions' => 'Activa o desactiva el botón principal',
            'required' => 0,
            'default_value' => 1,
            'ui' => 1,
        ),
        array(
            'key' => 'field_mwm_section_14_btn_text',
            'label' => 'Texto del botón',
            'name' => 'btn_text',
            'type' => 'text',
            'instructions' => 'Texto del botón principal',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_14_show_btn',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_14_btn_link',
            'label' => 'Link del botón',
            'name' => 'btn_link',
            'type' => 'text',
            'instructions' => 'Link del botón principal',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_14_show_btn',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_14_media_type',
            'label' => 'Tipo de media',
            'name' => 'media_type',
            'type' => 'select',
            'instructions' => __( 'Selecciona si quieres usar video de YouTube, video de WordPress o imagen', 'bilky' ),
            'required' => 0,
            'choices' => array(
                'video' => __( 'Video (YouTube)', 'bilky' ),
                'video-wordpress' => __( 'Video (WordPress)', 'bilky' ),
                'image' => __( 'Imagen', 'bilky' ),
            ),
            'default_value' => 'image',
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 1,
            'ajax' => 0,
            'return_format' => 'value',
        ),
        array(
            'key' => 'field_mwm_section_14_video_url',
            'label' => 'URL del video (YouTube)',
            'name' => 'video_url',
            'type' => 'url',
            'instructions' => __( 'Ingresa la URL del video de YouTube. Si no hay imagen, se usará la miniatura automáticamente.', 'bilky' ),
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_14_media_type',
                        'operator' => '==',
                        'value' => 'video',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_14_video_wordpress_url',
            'label' => __( 'URL del video (WordPress)', 'bilky' ),
            'name' => 'video_wordpress_url',
            'type' => 'url',
            'instructions' => __( 'Ingresa la URL del video subido a WordPress (copia la URL del archivo de video desde la librería de medios).', 'bilky' ),
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_14_media_type',
                        'operator' => '==',
                        'value' => 'video-wordpress',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_14_media_image',
            'label' => 'Imagen / Thumbnail',
            'name' => 'media_image',
            'type' => 'image',
            'instructions' => __( 'Imagen de fondo o thumbnail. Si es video y no hay imagen, se usará la miniatura de YouTube automáticamente.', 'bilky' ),
            'required' => 0,
            'return_format' => 'ID',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
        
       
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/mwm-section-14',
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


