<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if( function_exists('acf_add_local_field_group') ):
    
    acf_add_local_field_group(array(
        'key' => 'group_62b9b353de7b6',
        'title' => 'Configuración de cabecera y footer',
        'fields' => array(
            array(
                'key' => 'field_62b9b37e73206',
                'label' => 'Fondo de la barra de menú',
                'name' => 'navbar_bg',
                'type' => 'radio',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'navbar-dark' => 'Oscuro',
                    'navbar-light' => 'Claro',
                    'transparent' => 'Transparente',
                ),
                'allow_null' => 1,
                'other_choice' => 0,
                'default_value' => '',
                'layout' => 'vertical',
                'return_format' => 'value',
                'save_other_choice' => 0,
            ),
            array(
                'key' => 'field_62c210963a1e9',
                'label' => 'Ocultar Prefooter',
                'name' => 'ocultar_prefooter',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    ));
    
    endif;		