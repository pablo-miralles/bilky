<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_mwm_section_04',
    'title' => 'MWM Section 04',
    'fields' => array(
        array(
            'key' => 'field_mwm_section_04_variant',
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
            'key' => 'field_mwm_section_04_show_breadcrumbs',
            'label' => 'Mostrar Breadcrumbs',
            'name' => 'show_breadcrumbs',
            'type' => 'true_false',
            'instructions' => 'Activa o desactiva la sección de breadcrumbs',
            'required' => 0,
            'default_value' => 1,
            'ui' => 1,
        ),
        array(
            'key' => 'field_mwm_section_04_breadcrumb_button_text',
            'label' => 'Texto del botón breadcrumb',
            'name' => 'breadcrumb_button_text',
            'type' => 'text',
            'instructions' => 'Texto del botón outline en los breadcrumbs',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_04_show_breadcrumbs',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_04_breadcrumb_button_link',
            'label' => 'Link del botón breadcrumb',
            'name' => 'breadcrumb_button_link',
            'type' => 'text',
            'instructions' => 'Link del botón outline en los breadcrumbs',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_04_show_breadcrumbs',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_04_title',
            'label' => 'Título',
            'name' => 'title',
            'type' => 'textarea',
            'instructions' => 'Título principal del hero (se puede dividir en líneas)',
            'required' => 0,
            'rows' => 3,
        ),
        array(
            'key' => 'field_mwm_section_04_show_avatar_text',
            'label' => 'Mostrar avatares y texto',
            'name' => 'show_avatar_text',
            'type' => 'true_false',
            'instructions' => 'Activa o desactiva la sección de avatares y texto',
            'required' => 0,
            'default_value' => 0,
            'ui' => 1,
        ),
        array(
            'key' => 'field_mwm_section_04_show_avatar',
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
                        'field' => 'field_mwm_section_04_show_avatar_text',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_04_avatars',
            'label' => 'Avatares',
            'name' => 'avatars',
            'type' => 'repeater',
            'instructions' => 'Añade los avatares de usuarios (máximo 4)',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_04_show_avatar',
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
                    'key' => 'field_mwm_section_04_avatar_image',
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
            'key' => 'field_mwm_section_04_users_text',
            'label' => 'Texto de usuarios',
            'name' => 'users_text',
            'type' => 'text',
            'instructions' => 'Texto que aparece junto a los avatares (ej: + 350.000 usuarios)',
            'required' => 0,
            'default_value' => '+ 350.000 usuarios',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_04_show_avatar_text',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_04_cards',
            'label' => 'Cards',
            'name' => 'cards',
            'type' => 'repeater',
            'layout' => 'block',
            'instructions' => 'Añade las cards',
            'button_label' => 'Añadir Card',
            'required' => 0,
            'sub_fields' => array(
                array(
                    'key' => 'field_mwm_section_04_card_tag',
                    'label' => 'Tag',
                    'name' => 'tag',
                    'type' => 'text',
                    'instructions' => 'Tag de la card',
                    'required' => 0,
                    'default_value' => 'Tag',
                    'placeholder' => 'Tag',
                ),
                array(
                    'key' => 'field_mwm_section_04_card_icon',
                    'label' => 'Icono',
                    'name' => 'icon',
                    'type' => 'text',
                    'instructions' => 'Nombre del icono de Font Awesome 7 Pro (ej: briefcase, user, etc.)',
                    'required' => 0,
                    'default_value' => 'briefcase',
                    'placeholder' => 'briefcase',
                ),
                array(
                    'key' => 'field_mwm_section_04_card_title',
                    'label' => 'Título',
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => 'Título de la card',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_mwm_section_04_card_description',
                    'label' => 'Descripción',
                    'name' => 'description',
                    'type' => 'textarea',
                    'instructions' => 'Descripción de la card',
                    'required' => 0,
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/mwm-section-04',
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

