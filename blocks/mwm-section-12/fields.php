<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_mwm_section_12',
    'title' => 'MWM Section 12',
    'fields' => array(
        array(
            'key' => 'field_mwm_section_12_card_background',
            'label' => 'Fondo de las cards',
            'name' => 'card_background',
            'type' => 'select',
            'instructions' => 'Elige el color de fondo para las cards de los logos',
            'required' => 0,
            'choices' => array(
                'transparent' => 'Transparente',
                'white' => 'Blanco',
            ),
            'default_value' => 'transparent',
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 1,
            'ajax' => 0,
            'return_format' => 'value',
        ),
        array(
            'key' => 'field_mwm_section_12_logos',
            'label' => 'Logos',
            'name' => 'logos',
            'type' => 'repeater',
            'instructions' => 'Añade los logos de clientes/partners',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => '',
            'min' => 1,
            'max' => 0,
            'layout' => 'table',
            'button_label' => 'Añadir Logo',
            'sub_fields' => array(
                array(
                    'key' => 'field_mwm_section_12_logo_image',
                    'label' => 'Logo',
                    'name' => 'logo',
                    'type' => 'image',
                    'instructions' => 'Logo del cliente/partner',
                    'required' => 0,
                    'return_format' => 'ID',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/mwm-section-12',
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

