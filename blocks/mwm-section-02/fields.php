<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_mwm_section_02',
    'title' => 'MWM Section 02',
    'fields' => array(
        array(
            'key' => 'field_mwm_section_02_background',
            'label' => __( 'Fondo', 'bilky' ),
            'name' => 'background',
            'type' => 'select',
            'instructions' => __( 'Selecciona el tipo de fondo de la sección', 'bilky' ),
            'required' => 0,
            'choices' => array(
                'transparent' => __( 'Fondo transparente', 'bilky' ),
                'grey' => __( 'Fondo gris', 'bilky' ),
            ),
            'default_value' => 'transparent',
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 1,
            'ajax' => 0,
            'return_format' => 'value',
        ),
        array(
            'key' => 'field_mwm_section_02_show_icon',
            'label' => __( 'Mostrar icono', 'bilky' ),
            'name' => 'show_icon',
            'type' => 'true_false',
            'instructions' => __( 'Activa para mostrar el icono', 'bilky' ),
            'required' => 0,
            'default_value' => 0,
            'ui' => 1,
        ),
        array(
            'key' => 'field_mwm_section_02_icon',
            'label' => __( 'Icono', 'bilky' ),
            'name' => 'icon',
            'type' => 'text',
            'instructions' => __( 'Icono que aparecerá a la izquierda del título', 'bilky' ),
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_02_show_icon',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_02_title',
            'label' => __( 'Título', 'bilky' ),
            'name' => 'title',
            'type' => 'textarea',
            'instructions' => __( 'Título principal de la sección', 'bilky' ),
            'required' => 0,
            'rows' => 3,
        ),
        array(
            'key' => 'field_mwm_section_02_text_body',
            'label' => __( 'Texto del cuerpo', 'bilky' ),
            'name' => 'text_body',
            'type' => 'wysiwyg',
            'instructions' => __( 'Texto descriptivo de la sección', 'bilky' ),
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
        ),
        array(
            'key' => 'field_mwm_section_02_button_text',
            'label' => __( 'Texto del botón', 'bilky' ),
            'name' => 'button_text',
            'type' => 'text',
            'instructions' => __( 'Texto del botón', 'bilky' ),
            'required' => 0,
        ),
        array(
            'key' => 'field_mwm_section_02_button_link',
            'label' => __( 'Link del botón', 'bilky' ),
            'name' => 'button_link',
            'type' => 'text',
            'instructions' => __( 'Link del botón', 'bilky' ),
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_mwm_section_02_button_text',
                        'operator' => '!=empty',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_mwm_section_02_items',
            'label' => __( 'Items', 'bilky' ),
            'name' => 'items',
            'type' => 'repeater',
            'instructions' => __( 'Añade los items que aparecerán en la lista', 'bilky' ),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => 'field_mwm_section_02_item_title',
            'min' => 1,
            'max' => 0,
            'layout' => 'block',
            'button_label' => __( 'Añadir Item', 'bilky' ),
            'sub_fields' => array(
                array(
                    'key' => 'field_mwm_section_02_item_image',
                    'label' => __( 'Imagen', 'bilky' ),
                    'name' => 'image',
                    'type' => 'image',
                    'instructions' => __( 'Imagen del item', 'bilky' ),
                    'required' => 0,
                    'return_format' => 'ID',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ),
                array(
                    'key' => 'field_mwm_section_02_item_title',
                    'label' => __( 'Título', 'bilky' ),
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => __( 'Título del item', 'bilky' ),
                    'required' => 0,
                ),
                array(
                    'key' => 'field_mwm_section_02_item_text',
                    'label' => __( 'Texto', 'bilky' ),
                    'name' => 'text',
                    'type' => 'text',
                    'instructions' => __( 'Texto descriptivo del item. Puedes incluir una URL (ej: wolterskluwer.com) y se usará como enlace.', 'bilky' ),
                    'required' => 0,
                    'rows' => 3,
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/mwm-section-02',
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

