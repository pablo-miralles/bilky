<?php
/**
 * Custom Post Type: Partners
 *
 * CPT para gestionar partners (asociaciones, software, etc.).
 */
 
if ( ! function_exists( 'mwm_register_partners_cpt' ) ) {
	/**
	 * Registrar el CPT Partners
	 */
	function mwm_register_partners_cpt() {
		$labels = array(
			'name'                  => _x( 'Partners', 'Post Type General Name', 'bilky' ),
			'singular_name'         => _x( 'Partner', 'Post Type Singular Name', 'bilky' ),
			'menu_name'             => __( 'Partners', 'bilky' ),
			'name_admin_bar'        => __( 'Partner', 'bilky' ),
			'archives'              => __( 'Archivo de Partners', 'bilky' ),
			'attributes'            => __( 'Atributos del Partner', 'bilky' ),
			'parent_item_colon'     => __( 'Partner padre:', 'bilky' ),
			'all_items'             => __( 'Todos los Partners', 'bilky' ),
			'add_new_item'          => __( 'Añadir nuevo Partner', 'bilky' ),
			'add_new'               => __( 'Añadir nuevo', 'bilky' ),
			'new_item'              => __( 'Nuevo Partner', 'bilky' ),
			'edit_item'             => __( 'Editar Partner', 'bilky' ),
			'update_item'           => __( 'Actualizar Partner', 'bilky' ),
			'view_item'             => __( 'Ver Partner', 'bilky' ),
			'view_items'            => __( 'Ver Partners', 'bilky' ),
			'search_items'          => __( 'Buscar Partner', 'bilky' ),
			'not_found'             => __( 'No encontrado', 'bilky' ),
			'not_found_in_trash'    => __( 'No encontrado en papelera', 'bilky' ),
			'featured_image'        => __( 'Imagen destacada', 'bilky' ),
			'set_featured_image'    => __( 'Establecer imagen destacada', 'bilky' ),
			'remove_featured_image' => __( 'Quitar imagen destacada', 'bilky' ),
			'use_featured_image'    => __( 'Usar como imagen destacada', 'bilky' ),
			'insert_into_item'      => __( 'Insertar en el Partner', 'bilky' ),
			'uploaded_to_this_item' => __( 'Subido a este Partner', 'bilky' ),
			'items_list'            => __( 'Lista de Partners', 'bilky' ),
			'items_list_navigation' => __( 'Navegación de lista de Partners', 'bilky' ),
			'filter_items_list'     => __( 'Filtrar lista de Partners', 'bilky' ),
		);
 
		$args = array(
			'label'                 => __( 'Partner', 'bilky' ),
			'description'           => __( 'Custom Post Type para gestionar partners', 'bilky' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'            => array( 'partner_tipo' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 22,
			'menu_icon'             => 'dashicons-admin-multisite',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => false,
			'publicly_queryable'    => false, // Deshabilitar singles por defecto
			'capability_type'       => 'post',
			'show_in_rest'          => false, // Deshabilitar Gutenberg/REST API por defecto
		);
 
		register_post_type( 'partners', $args );
	}
	add_action( 'init', 'mwm_register_partners_cpt', 0 );
}
 
if ( ! function_exists( 'mwm_disable_gutenberg_for_partners' ) ) {
	/**
	 * Deshabilitar Gutenberg para el CPT Partners
	 */
	function mwm_disable_gutenberg_for_partners( $use_block_editor, $post_type ) {
		if ( 'partners' === $post_type ) {
			return false;
		}
		return $use_block_editor;
	}
	add_filter( 'use_block_editor_for_post_type', 'mwm_disable_gutenberg_for_partners', 10, 2 );
}
 
if ( ! function_exists( 'mwm_redirect_partners_single' ) ) {
	/**
	 * Redirigir los singles de partners al home (si están deshabilitados).
	 */
	function mwm_redirect_partners_single() {
		if ( is_singular( 'partners' ) ) {
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	}
	add_action( 'template_redirect', 'mwm_redirect_partners_single' );
}
 
if ( ! function_exists( 'mwm_register_partner_tipo_taxonomy' ) ) {
	/**
	 * Registrar taxonomía para tipo de partner
	 */
	function mwm_register_partner_tipo_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Tipos de Partner', 'Taxonomy General Name', 'bilky' ),
			'singular_name'              => _x( 'Tipo de Partner', 'Taxonomy Singular Name', 'bilky' ),
			'menu_name'                  => __( 'Tipos', 'bilky' ),
			'all_items'                  => __( 'Todos los tipos', 'bilky' ),
			'parent_item'                => __( 'Tipo padre', 'bilky' ),
			'parent_item_colon'          => __( 'Tipo padre:', 'bilky' ),
			'new_item_name'              => __( 'Nuevo tipo', 'bilky' ),
			'add_new_item'               => __( 'Añadir nuevo tipo', 'bilky' ),
			'edit_item'                  => __( 'Editar tipo', 'bilky' ),
			'update_item'                => __( 'Actualizar tipo', 'bilky' ),
			'view_item'                  => __( 'Ver tipo', 'bilky' ),
			'separate_items_with_commas' => __( 'Separar tipos con comas', 'bilky' ),
			'add_or_remove_items'        => __( 'Añadir o quitar tipos', 'bilky' ),
			'choose_from_most_used'      => __( 'Elegir entre los más usados', 'bilky' ),
			'popular_items'              => __( 'Tipos populares', 'bilky' ),
			'search_items'               => __( 'Buscar tipos', 'bilky' ),
			'not_found'                  => __( 'No encontrado', 'bilky' ),
			'no_terms'                   => __( 'No hay tipos', 'bilky' ),
			'items_list'                 => __( 'Lista de tipos', 'bilky' ),
			'items_list_navigation'      => __( 'Navegación de lista de tipos', 'bilky' ),
		);
 
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => false,
			'show_in_rest'               => false,
		);
 
		register_taxonomy( 'partner_tipo', array( 'partners' ), $args );
	}
	add_action( 'init', 'mwm_register_partner_tipo_taxonomy', 0 );
}

