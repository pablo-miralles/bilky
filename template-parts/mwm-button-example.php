<?php
/**
 * Template Name: Ejemplo de uso del componente mwm_button
 *
 * Este archivo muestra ejemplos de uso del componente mwm_button
 * con todas sus variantes, colores, tamaños y estados.
 */

// Ejemplo 1: Botón fill-icon primary xl (default)
echo mwm_button( array(
	'text' => __( 'Button', 'bilky' ),
	'variant' => 'fill-icon',
	'color' => 'primary',
	'size' => 'xl',
) );

// Ejemplo 2: Botón fill primary xl-md como enlace
echo mwm_button( array(
	'text' => __( 'Button', 'bilky' ),
	'url' => '#',
	'variant' => 'fill',
	'color' => 'primary',
	'size' => 'xl-md',
) );

// Ejemplo 3: Botón fill-icon secundary md
echo mwm_button( array(
	'text' => __( 'Button', 'bilky' ),
	'variant' => 'fill-icon',
	'color' => 'secundary',
	'size' => 'md',
) );

// Ejemplo 4: Botón fill-icon terciary sm
echo mwm_button( array(
	'text' => __( 'Button', 'bilky' ),
	'variant' => 'fill-icon',
	'color' => 'terciary',
	'size' => 'sm',
) );

// Ejemplo 5: Botón link secundary xl-md
echo mwm_button( array(
	'text' => __( 'Button', 'bilky' ),
	'url' => '#',
	'variant' => 'link',
	'color' => 'secundary',
	'size' => 'xl-md',
) );

// Ejemplo 6: Botón outline secundary xl-md
echo mwm_button( array(
	'text' => __( 'Button', 'bilky' ),
	'url' => '#',
	'variant' => 'outline',
	'color' => 'secundary',
	'size' => 'xl-md',
) );

// Ejemplo 7: Botón outline terciary sm
echo mwm_button( array(
	'text' => __( 'Button', 'bilky' ),
	'url' => '#',
	'variant' => 'outline',
	'color' => 'terciary',
	'size' => 'sm',
) );

// Ejemplo 8: Botón soft terciary xl-md
echo mwm_button( array(
	'text' => __( 'Button', 'bilky' ),
	'url' => '#',
	'variant' => 'soft',
	'color' => 'terciary',
	'size' => 'xl-md',
) );

// Ejemplo 9: Botón disabled
echo mwm_button( array(
	'text' => __( 'Button', 'bilky' ),
	'variant' => 'fill-icon',
	'color' => 'primary',
	'size' => 'xl',
	'state' => 'disabled',
) );

// Ejemplo 10: Botón como <button> tag
echo mwm_button( array(
	'text' => __( 'Submit', 'bilky' ),
	'tag' => 'button',
	'variant' => 'fill',
	'color' => 'primary',
	'size' => 'xl-md',
	'attributes' => array(
		'type' => 'submit',
		'name' => 'submit',
	),
) );

