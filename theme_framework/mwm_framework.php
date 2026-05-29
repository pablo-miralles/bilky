<?php
/**
 * Framework de mowomo para themes
 */

 // Security
if (!defined('ABSPATH')) exit;

/*
---- Commons ---- 
Funciones que se pueden usar en cualquier parte del código
*/
require_once( get_template_directory() . '/theme_framework/commons/mwm_comm_testing.php' );
require_once( get_template_directory() . '/theme_framework/commons/mwm_comm_configurations.php' );
require_once( get_template_directory() . '/theme_framework/commons/mwm_comm_editor.php' );
require_once( get_template_directory() . '/theme_framework/commons/custom_fields/mwm_comm_field_advanced_table_fields.php' );


/*
---- Supports ---- 
Funciones que facilitarán el trabajo en diferentes partes del código en concreto.
 */
require_once( get_template_directory() . '/theme_framework/supports/mwm_supp_customizer.php' );
require_once( get_template_directory() . '/theme_framework/supports/mwm_supp_archives.php' );

/* 
---- ASSETS ----
Funciones que darán te darán apoyo al JS del sitio
*/ 

if ( ! function_exists( 'mwm_add_scripts' ) ) {
	function mwm_add_scripts( $scripts ) {
		foreach ( $scripts as $script => $data ) {
			wp_register_script( $script, $data['path'], $data['deps'], $data['ver'], $data['in_footer'] );
			wp_enqueue_script( $script );
		}
	}
}

if ( ! function_exists( 'mwm_add_styles' ) ) {
	function mwm_add_styles( $styles ) {
		foreach ( $styles as $style => $data ) {
			wp_register_style( $style, $data['path'], $data['deps'], $data['ver'], $data['media'] );
			wp_enqueue_style( $style );
		}
	}
}

if ( !function_exists( 'mwm_framework_commons_scripts' ) ) {
	function mwm_framework_commons_scripts() {
		if ( !wp_script_is( 'jquery', 'enqueued' ) ) {
			wp_enqueue_script( 'jQuery', 'https://code.jquery.com/jquery-3.7.1.min.js', array(), '3.3.1', true );
		}
	}
	add_action( 'wp_enqueue_scripts', 'mwm_framework_commons_scripts' );
}

if ( !function_exists( 'admin_mwm_framework_commons_scripts' ) ) {
	function admin_mwm_framework_commons_scripts() {

		$user = wp_get_current_user();
		if($user->user_login  != 'desarrollo'){
			wp_register_style( 'mwm-common-style', get_template_directory_uri() . '/theme_framework/css/common-styles.css', array(), mowomo_asset_version( '/theme_framework/css/common-styles.css' ) );
			wp_enqueue_style( 'mwm-common-style' );
		}
		
		wp_register_script( 'mwm-commons-js', get_template_directory_uri() . '/theme_framework/js/mwm-commons.js', array('jquery'), mowomo_asset_version( '/theme_framework/js/mwm-commons.js' ), true );
		wp_enqueue_script( 'mwm-commons-js' );

		wp_register_style( 'mwm-customize-controls', get_template_directory_uri() . '/theme_framework/css/styles.css', array(), mowomo_asset_version( '/theme_framework/css/styles.css' ) );
		wp_enqueue_style( 'mwm-customize-controls' );

		wp_register_style( 'mwm-admin-styles', get_template_directory_uri() . '/theme_framework/css/admin_styles.css', array(), mowomo_asset_version( '/theme_framework/css/admin_styles.css' ) );
		wp_enqueue_style( 'mwm-admin-styles' );
	}
	add_action( 'admin_enqueue_scripts', 'admin_mwm_framework_commons_scripts' );
}

/* 
---- BLOCKS ----
Bloques de Gutenberg de apoyo para el desarrollo de los temas.
*/

require_once( get_template_directory() . '/theme_framework/blocks/mwm_spliter/index.php' );
