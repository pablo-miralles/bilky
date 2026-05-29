<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_mwm_section_05',
    'title' => 'MWM Slider Cards 01',
    'fields' => array(
        array(
            'key' => 'field_mwm_section_05_title',
            'label' => 'Título',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Título que aparece a la izquierda de los controles de navegación',
            'required' => 0,
        ),
        array(
            'key' => 'field_mwm_section_05_card_description_opacity',
            'label' => 'Descripción con opacidad 100%',
            'name' => 'description_opacity',
            'type' => 'true_false',
            'instructions' => 'Activa para mostrar la descripción con opacidad 100%.',
            'required' => 0,
            'default_value' => 0,
            'ui' => 1,
        ),
        array(
            'key' => 'field_mwm_section_05_source_type',
            'label' => 'Fuente de datos',
            'name' => 'source_type',
            'type' => 'select',
            'instructions' => 'Elige de dónde obtener la información de las cards',
            'required' => 0,
            'choices' => array(
                'manual' => 'Crear cards manualmente',
                'pages' => 'Usar páginas',
            ),
            'default_value' => 'manual',
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 1,
            'ajax' => 0,
            'return_format' => 'value',
        ),
        array(
            'key' => 'field_mwm_section_05_cards',
            'label' => 'Cards',
            'name' => 'cards',
            'type' => 'repeater',
            'instructions' => 'Añade las cards que quieras mostrar en el slider',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_05_source_type',
                        'operator' => '==',
                        'value' => 'manual',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => 'field_mwm_section_05_card_title',
            'min' => 1,
            'max' => 0,
            'layout' => 'block',
            'button_label' => 'Añadir Card',
            'sub_fields' => array(
                array(
                    'key' => 'field_mwm_section_05_card_icon',
                    'label' => 'Icono',
                    'name' => 'icon',
                    'type' => 'text',
                    'instructions' => 'Nombre del icono de Font Awesome 7 Pro (ej: briefcase, user, etc.)',
                    'required' => 0,
                    'default_value' => 'briefcase',
                    'placeholder' => 'briefcase',
                ),
                array(
                    'key' => 'field_mwm_section_05_card_title',
                    'label' => 'Título',
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => 'Título de la card',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_mwm_section_05_card_description',
                    'label' => 'Descripción',
                    'name' => 'description',
                    'type' => 'wysiwyg',
                    'instructions' => 'Descripción de la card. Puedes usar negrita para resaltar palabras importantes.',
                    'required' => 0,
                    'tabs' => 'all',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                ),
                array(
                    'key' => 'field_mwm_section_05_card_show_button',
                    'label' => 'Mostrar botón',
                    'name' => 'show_button',
                    'type' => 'true_false',
                    'instructions' => 'Activa o desactiva el botón',
                    'required' => 0,
                    'default_value' => 1,
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_mwm_section_05_card_button_text',
                    'label' => 'Texto del botón',
                    'name' => 'button_text',
                    'type' => 'text',
                    'instructions' => 'Texto del botón',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_mwm_section_05_card_show_button',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_mwm_section_05_card_button_url',
                    'label' => 'URL del botón',
                    'name' => 'button_url',
                    'type' => 'url',
                    'instructions' => 'URL del botón',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_mwm_slider_cards_01_card_show_button',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_05_pages',
            'label' => 'Páginas',
            'name' => 'pages',
            'type' => 'post_object',
            'instructions' => 'Selecciona las páginas que quieres mostrar como cards. De cada página se obtendrá: icono (campo personalizado), título, excerpt y URL.',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_05_source_type',
                        'operator' => '==',
                        'value' => 'pages',
                    ),
                ),
            ),
            'post_type' => array(
                0 => 'page',
            ),
            'taxonomy' => '',
            'allow_null' => 0,
            'multiple' => 1,
            'return_format' => 'id',
            'ui' => 1,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/mwm-section-05',
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

