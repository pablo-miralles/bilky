<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_mwm_section_07',
    'title' => 'MWM Section 07',
    'fields' => array(
        array(
            'key' => 'field_mwm_section_07_variant',
            'label' => 'Variante',
            'name' => 'variant',
            'type' => 'select',
            'instructions' => 'Selecciona la variante de alineación del bloque',
            'required' => 0,
            'default_value' => 'center',
            'choices' => array(
                'center' => 'Centrado',
                'left' => 'Izquierda',
            ),
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 1,
            'ajax' => 0,
            'return_format' => 'value',
        ),
        array(
            'key' => 'field_mwm_section_07_background',
            'label' => 'Fondo',
            'name' => 'background',
            'type' => 'select',
            'instructions' => 'Selecciona el tipo de fondo del bloque',
            'required' => 0,
            'default_value' => 'white',
            'choices' => array(
                'transparent' => 'Transparente',
                'grey' => 'Gris',
                'white' => 'Blanco',
            ),
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 1,
            'ajax' => 0,
            'return_format' => 'value',
        ),
        array(
            'key' => 'field_mwm_section_07_show_breadcrumbs',
            'label' => 'Mostrar Breadcrumbs',
            'name' => 'show_breadcrumbs',
            'type' => 'true_false',
            'instructions' => 'Activa o desactiva la sección de breadcrumbs',
            'required' => 0,
            'default_value' => 1,
            'ui' => 1,
        ),
        array(
            'key' => 'field_mwm_section_07_breadcrumb_button_text',
            'label' => 'Texto del botón breadcrumb',
            'name' => 'breadcrumb_button_text',
            'type' => 'text',
            'instructions' => 'Texto del botón outline en los breadcrumbs',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_07_show_breadcrumbs',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_07_breadcrumb_button_link',
            'label' => 'Link del botón breadcrumb',
            'name' => 'breadcrumb_button_link',
            'type' => 'text',
            'instructions' => 'Link del botón outline en los breadcrumbs',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_07_show_breadcrumbs',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_07_show_breadcrumb_01',
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
                        'field' => 'field_mwm_section_07_show_breadcrumbs',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_07_breadcrumb_01_text',
            'label' => 'Texto Breadcrumb 01',
            'name' => 'breadcrumb_01_text',
            'type' => 'text',
            'instructions' => 'Texto del primer breadcrumb adicional',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_07_show_breadcrumb_01',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_07_show_breadcrumb_02',
            'label' => 'Mostrar Breadcrumb 02',
            'name' => 'show_breadcrumb_02',
            'type' => 'true_false',
            'instructions' => 'Muestra el segundo breadcrumb adicional',
            'required' => 0,
            'default_value' => 1,
            'ui' => 1,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_07_show_breadcrumbs',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_07_breadcrumb_02_text',
            'label' => 'Texto Breadcrumb 02',
            'name' => 'breadcrumb_02_text',
            'type' => 'text',
            'instructions' => 'Texto del segundo breadcrumb adicional',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_07_show_breadcrumb_02',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_07_title',
            'label' => 'Título',
            'name' => 'title',
            'type' => 'textarea',
            'instructions' => 'Título principal del hero (se puede dividir en líneas)',
            'required' => 0,
            'rows' => 3,
        ),
        array(
            'key' => 'field_mwm_section_07_show_text_body',
            'label' => 'Mostrar texto del cuerpo',
            'name' => 'show_text_body',
            'type' => 'true_false',
            'instructions' => 'Activa o desactiva el texto descriptivo',
            'required' => 0,
            'default_value' => 1,
            'ui' => 1,
        ),
        array(
            'key' => 'field_mwm_section_07_text_body',
            'label' => 'Texto del cuerpo',
            'name' => 'text_body',
            'type' => 'textarea',
            'instructions' => 'Texto descriptivo que aparece debajo del título',
            'required' => 0,
            'rows' => 4,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_07_show_text_body',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_07_show_btn',
            'label' => 'Mostrar botón',
            'name' => 'show_btn',
            'type' => 'true_false',
            'instructions' => 'Activa o desactiva el botón principal',
            'required' => 0,
            'default_value' => 1,
            'ui' => 1,
        ),
        array(
            'key' => 'field_mwm_section_07_btn_text',
            'label' => 'Texto del botón',
            'name' => 'btn_text',
            'type' => 'text',
            'instructions' => 'Texto del botón principal',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_07_show_btn',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_07_btn_link',
            'label' => 'Link del botón',
            'name' => 'btn_link',
            'type' => 'text',
            'instructions' => 'Link del botón principal',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_07_show_btn',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_07_show_btn_register',
            'label' => 'Mostrar botón Registrarse',
            'name' => 'show_btn_register',
            'type' => 'true_false',
            'instructions' => 'Activa o desactiva el botón Registrarse',
            'required' => 0,
            'default_value' => 0,
            'ui' => 1,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_07_show_btn',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_07_btn_register_text',
            'label' => 'Texto del botón Registrarse',
            'name' => 'btn_register_text',
            'type' => 'text',
            'instructions' => 'Texto del botón Registrarse',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_07_show_btn_register',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_07_btn_register_link',
            'label' => 'Link del botón Registrarse',
            'name' => 'btn_register_link',
            'type' => 'text',
            'instructions' => 'Link del botón Registrarse',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_07_show_btn_register',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_07_show_avatar_text',
            'label' => 'Mostrar avatares y texto',
            'name' => 'show_avatar_text',
            'type' => 'true_false',
            'instructions' => 'Activa o desactiva la sección de avatares y texto',
            'required' => 0,
            'default_value' => 0,
            'ui' => 1,
        ),
        array(
            'key' => 'field_mwm_section_07_show_avatar',
            'label' => 'Mostrar avatares',
            'name' => 'show_avatar',
            'type' => 'true_false',
            'instructions' => 'Activa o desactiva los avatares',
            'required' => 0,
            'default_value' => 0,
            'ui' => 1,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_07_show_avatar_text',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_07_avatars',
            'label' => 'Avatares',
            'name' => 'avatars',
            'type' => 'repeater',
            'instructions' => 'Añade los avatares de usuarios (máximo 4)',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_07_show_avatar',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => '',
            'min' => 1,
            'max' => 4,
            'layout' => 'table',
            'button_label' => 'Añadir Avatar',
            'sub_fields' => array(
                array(
                    'key' => 'field_mwm_section_07_avatar_image',
                    'label' => 'Imagen',
                    'name' => 'image',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 0,
                    'return_format' => 'ID',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_07_users_text',
            'label' => 'Texto de usuarios',
            'name' => 'users_text',
            'type' => 'text',
            'instructions' => 'Texto que aparece junto a los avatares (ej: + 350.000 usuarios)',
            'required' => 0,
            'default_value' => '+ 350.000 usuarios',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_07_show_avatar_text',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/mwm-section-07',
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

