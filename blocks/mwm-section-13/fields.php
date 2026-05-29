<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_mwm_section_13',
    'title' => 'MWM Section 13',
    'fields' => array(
        array(
            'key' => 'field_mwm_section_13_shortcode',
            'label' => 'Shortcode',
            'name' => 'shortcode',
            'type' => 'text',
            'instructions' => 'Shortcode de la sección',
            'required' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/mwm-section-13',
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

