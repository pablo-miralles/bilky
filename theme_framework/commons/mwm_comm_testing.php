<?php
/* En este archivo encontrarás funciones generales que te ayudarán a realizar diferentes tipos de tests en tu desarrollos

Indice:
- echop() -> Imprime una variable usando var_dump correctamente formateado.
- echopa() -> Imprime una variable usando var_dump correctamente formateado y solo visible para el usuario administrador. Ideal para sitios en producción.
- echokill() -> Imprime una variable usando var_dump correctamente formateado y provocando un die() en el sitio web. NUNCA usar en producción.
- mwm_echo_mod() -> Imprimer un theme_mod del customizer. Sin necesidad de poner echo delante, Cool!! :D
- mwm_echo_mod_img() -> Imprimer un theme_mod del customizer como imagen. Sin necesidad de poner echo delante y con todos los metas necesarios, Cool!! :D
- mwm_testing_action() -> Crea un actión llamado mwm_testing_action que te permite enganchar una función a un ese hook para poder hacer testing más complejo.
*/

// Security
if (!defined('ABSPATH')) exit;


if ( !function_exists( 'echop' ) ) {
    /**
	 * Function that shows all the content of a variable
	 */
    function echop( $var ) {
        echo '<pre>', var_dump( $var ), '</pre>';
    }
}

if ( ! function_exists( 'echopa' ) ) {
	/**
	 * Function that shows all the content of a variable only to editors and admins
	 */
	function echopa( $var ) {
		if( current_user_can('editor') || current_user_can('administrator') ) {
			echop( $var );
		}
	}
}

if ( ! function_exists( 'echokill' ) ) {
	/**
	 * Function that shows all the content and kill the execution
	 */
	function echokill( $var ) {
		echop( $var );
		die();
	}
}

if ( ! function_exists( 'mwm_echo_mod' ) ) {
	/**
	 * Function that returns the value of a theme support
	 */
	function mwm_echo_mod( $slug, $args = null ) {
		if ( $text = get_option( $slug ) ) {
			echo $text;
		} else if ( $text = get_theme_mod( $slug ) ) {
			echo $text;
		}
	}
}

if ( ! function_exists( 'mwm_echo_mod_img' ) ) {
	/**
	 * Function that returns the value of a theme support
	 */
	function mwm_echo_mod_img( $slug, $size = 'full' ) {
		if ( $img = get_option( $slug ) ) {
			echo wp_get_attachment_image( $img, $size );
		} else if ( $img = get_theme_mod( $slug ) ) {
			echo wp_get_attachment_image( $img, $size );
		}
	}
}


if ( ! function_exists( 'mwm_testing_action' ) ) {
	/**
	 * Function that prepares an action for testing
	 */
	function mwm_testing_action() {
		if ( isset( $_GET['mwm_testing'] ) ) {
			do_action( 'mwm_testing' );
			die();
		}
	}
	add_action( 'init' , 'mwm_testing_action', 10 );
}
