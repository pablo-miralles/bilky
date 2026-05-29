<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_mwm_section_06',
    'title' => 'MWM Section 06',
    'fields' => array(
        array(
            'key' => 'field_mwm_section_06_title',
            'label' => __( 'Título de la lista', 'bilky' ),
            'name' => 'title',
            'type' => 'text',
            'instructions' => __( 'Título que aparece sobre la lista de items', 'bilky' ),
            'required' => 0,
        ),
        array(
            'key' => 'field_mwm_section_06_list_items',
            'label' => __( 'Items de la lista', 'bilky' ),
            'name' => 'list_items',
            'type' => 'repeater',
            'instructions' => __( 'Añade los items que aparecerán en la lista', 'bilky' ),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => 'field_mwm_section_06_list_item_text',
            'min' => 1,
            'max' => 0,
            'layout' => 'table',
            'button_label' => __( 'Añadir Item', 'bilky' ),
            'sub_fields' => array(
                array(
                    'key' => 'field_mwm_section_06_list_item_text',
                    'label' => __( 'Texto', 'bilky' ),
                    'name' => 'text',
                    'type' => 'text',
                    'instructions' => __( 'Texto del item de la lista', 'bilky' ),
                    'required' => 0,
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_06_card',
            'label' => __( 'Card', 'bilky' ),
            'name' => 'card',
            'type' => 'group',
            'instructions' => __( 'Configuración de la card que aparece a la derecha', 'bilky' ),
            'required' => 0,
            'layout' => 'block',
            'sub_fields' => array(
                array(
                    'key' => 'field_mwm_section_06_card_title',
                    'label' => __( 'Título de la card', 'bilky' ),
                    'name' => 'title',
                    'type' => 'wysiwyg',
                    'instructions' => __( 'Título principal de la card', 'bilky' ),
                    'required' => 0,
                    'tabs' => 'all',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                    'delay' => 0,
                ),
                array(
                    'key' => 'field_mwm_section_06_card_avatars',
                    'label' => __( 'Avatares', 'bilky' ),
                    'name' => 'avatars',
                    'type' => 'repeater',
                    'instructions' => __( 'Añade los avatares que aparecerán en la card', 'bilky' ),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'collapsed' => 'field_mwm_section_06_card_avatar_image',
                    'min' => 0,
                    'max' => 0,
                    'layout' => 'table',
                    'button_label' => __( 'Añadir Avatar', 'bilky' ),
                    'sub_fields' => array(
                        array(
                            'key' => 'field_mwm_section_06_card_avatar_image',
                            'label' => __( 'Imagen', 'bilky' ),
                            'name' => 'image',
                            'type' => 'image',
                            'instructions' => __( 'Imagen del avatar', 'bilky' ),
                            'required' => 0,
                            'return_format' => 'ID',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_mwm_section_06_card_button_text',
                    'label' => __( 'Texto del botón', 'bilky' ),
                    'name' => 'button_text',
                    'type' => 'text',
                    'instructions' => __( 'Texto del botón de la card', 'bilky' ),
                    'required' => 0,
                ),
                array(
                    'key' => 'field_mwm_section_06_card_button_url',
                    'label' => __( 'URL del botón', 'bilky' ),
                    'name' => 'button_url',
                    'type' => 'text',
                    'instructions' => __( 'URL del botón de la card', 'bilky' ),
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_mwm_section_06_card_button_text',
                                'operator' => '!=empty',
                            ),
                        ),
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
                'value' => 'acf/mwm-section-06',
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

