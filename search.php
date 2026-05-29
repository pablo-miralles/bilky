<?php
/**
 * Plantilla de búsqueda.
 *
 * Si la búsqueda es del CPT centro_de_ayuda, reutiliza su archive.
 *
 * @package Bilky
 */

$post_type     = get_query_var( 'post_type' );
$get_post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : '';
$is_centro     = false;

if ( is_array( $post_type ) ) {
	$is_centro = in_array( 'centro_de_ayuda', $post_type, true );
} else {
	$is_centro = 'centro_de_ayuda' === $post_type;
}

if ( ! $is_centro && 'centro_de_ayuda' === $get_post_type ) {
	$is_centro = true;
}

if ( $is_centro ) {
	locate_template( array( 'archive-centro_de_ayuda.php' ), true );
	return;
}

locate_template( array( 'index.php' ), true );
