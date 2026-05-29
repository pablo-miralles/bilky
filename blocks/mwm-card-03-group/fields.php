<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_mwm_card_03_group',
    'title' => 'MWM Card 03 Group',
    'fields' => array(
        array(
            'key' => 'field_mwm_card_03_group_cards',
            'label' => 'Cards',
            'name' => 'cards',
            'type' => 'repeater',
            'instructions' => 'Añade las cards que quieras mostrar en el slider',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => 'field_mwm_card_03_group_card_title',
            'min' => 1,
            'max' => 0,
            'layout' => 'block',
            'button_label' => 'Añadir Card',
            'sub_fields' => array(
                array(
                    'key' => 'field_mwm_card_03_group_card_number',
                    'label' => 'Número',
                    'name' => 'number',
                    'type' => 'text',
                    'instructions' => 'Número que aparece en la esquina superior izquierda (ej: 01, 02, 03)',
                    'required' => 0,
                    'default_value' => '01',
                    'placeholder' => '01',
                ),
                array(
                    'key' => 'field_mwm_card_03_group_card_icon',
                    'label' => 'Icono',
                    'name' => 'icon',
                    'type' => 'text',
                    'instructions' => 'Nombre del icono de Font Awesome 7 Pro (ej: wifi, headset, refresh, etc.)',
                    'required' => 0,
                    'default_value' => 'wifi',
                    'placeholder' => 'wifi',
                ),
                array(
                    'key' => 'field_mwm_card_03_group_card_title',
                    'label' => 'Título',
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => 'Título de la card',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_mwm_card_03_group_card_description',
                    'label' => 'Descripción',
                    'name' => 'description',
                    'type' => 'textarea',
                    'instructions' => 'Descripción de la card',
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
                'value' => 'acf/mwm-card-03-group',
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

