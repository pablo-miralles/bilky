<?php
/**
 * Custom Post Type: Clientes
 * CPT con editor clásico, thumbnail y título
 */

if ( ! function_exists( 'mwm_register_clientes_cpt' ) ) {
	/**
	 * Registrar el CPT Clientes
	 */
	function mwm_register_clientes_cpt() {
		$labels = array(
			'name'                  => _x( 'Clientes', 'Post Type General Name', 'bilky' ),
			'singular_name'         => _x( 'Cliente', 'Post Type Singular Name', 'bilky' ),
			'menu_name'             => __( 'Clientes', 'bilky' ),
			'name_admin_bar'        => __( 'Cliente', 'bilky' ),
			'archives'              => __( 'Archivo de Clientes', 'bilky' ),
			'attributes'            => __( 'Atributos del Cliente', 'bilky' ),
			'parent_item_colon'     => __( 'Cliente padre:', 'bilky' ),
			'all_items'             => __( 'Todos los Clientes', 'bilky' ),
			'add_new_item'          => __( 'Añadir nuevo Cliente', 'bilky' ),
			'add_new'               => __( 'Añadir nuevo', 'bilky' ),
			'new_item'              => __( 'Nuevo Cliente', 'bilky' ),
			'edit_item'             => __( 'Editar Cliente', 'bilky' ),
			'update_item'           => __( 'Actualizar Cliente', 'bilky' ),
			'view_item'             => __( 'Ver Cliente', 'bilky' ),
			'view_items'            => __( 'Ver Clientes', 'bilky' ),
			'search_items'          => __( 'Buscar Cliente', 'bilky' ),
			'not_found'             => __( 'No encontrado', 'bilky' ),
			'not_found_in_trash'    => __( 'No encontrado en papelera', 'bilky' ),
			'featured_image'        => __( 'Imagen destacada', 'bilky' ),
			'set_featured_image'    => __( 'Establecer imagen destacada', 'bilky' ),
			'remove_featured_image' => __( 'Quitar imagen destacada', 'bilky' ),
			'use_featured_image'    => __( 'Usar como imagen destacada', 'bilky' ),
			'insert_into_item'      => __( 'Insertar en el Cliente', 'bilky' ),
			'uploaded_to_this_item' => __( 'Subido a este Cliente', 'bilky' ),
			'items_list'            => __( 'Lista de Clientes', 'bilky' ),
			'items_list_navigation' => __( 'Navegación de lista de Clientes', 'bilky' ),
			'filter_items_list'     => __( 'Filtrar lista de Clientes', 'bilky' ),
		);

		$args = array(
			'label'                 => __( 'Cliente', 'bilky' ),
			'description'           => __( 'Custom Post Type para gestionar clientes', 'bilky' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'            => array( 'cliente_categoria' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-groups',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => false,
			'publicly_queryable'    => false, // Deshabilitar singles
			'capability_type'       => 'post',
			'show_in_rest'          => false, // Deshabilitar Gutenberg/REST API
		);

		register_post_type( 'clientes', $args );
	}
	add_action( 'init', 'mwm_register_clientes_cpt', 0 );
}

if ( ! function_exists( 'mwm_disable_gutenberg_for_clientes' ) ) {
	/**
	 * Deshabilitar Gutenberg para el CPT Clientes
	 */
	function mwm_disable_gutenberg_for_clientes( $use_block_editor, $post_type ) {
		if ( 'clientes' === $post_type ) {
			return false;
		}
		return $use_block_editor;
	}
	add_filter( 'use_block_editor_for_post_type', 'mwm_disable_gutenberg_for_clientes', 10, 2 );
}

if ( ! function_exists( 'mwm_redirect_clientes_single' ) ) {
	/**
	 * Redirigir los singles de clientes al home (ya que el archive está desactivado)
	 */
	function mwm_redirect_clientes_single() {
		if ( is_singular( 'clientes' ) ) {
			wp_safe_redirect( home_url( '/' ), 301 );
				exit;
		}
	}
	add_action( 'template_redirect', 'mwm_redirect_clientes_single' );
}

if ( ! function_exists( 'mwm_register_cliente_categoria_taxonomy' ) ) {
	/**
	 * Registrar taxonomía para categorías de clientes
	 */
	function mwm_register_cliente_categoria_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Categorías de Clientes', 'Taxonomy General Name', 'bilky' ),
			'singular_name'              => _x( 'Categoría de Cliente', 'Taxonomy Singular Name', 'bilky' ),
			'menu_name'                  => __( 'Categorías', 'bilky' ),
			'all_items'                  => __( 'Todas las categorías', 'bilky' ),
			'parent_item'                => __( 'Categoría padre', 'bilky' ),
			'parent_item_colon'          => __( 'Categoría padre:', 'bilky' ),
			'new_item_name'              => __( 'Nueva categoría', 'bilky' ),
			'add_new_item'               => __( 'Añadir nueva categoría', 'bilky' ),
			'edit_item'                  => __( 'Editar categoría', 'bilky' ),
			'update_item'                => __( 'Actualizar categoría', 'bilky' ),
			'view_item'                  => __( 'Ver categoría', 'bilky' ),
			'separate_items_with_commas' => __( 'Separar categorías con comas', 'bilky' ),
			'add_or_remove_items'        => __( 'Añadir o quitar categorías', 'bilky' ),
			'choose_from_most_used'      => __( 'Elegir entre las más usadas', 'bilky' ),
			'popular_items'              => __( 'Categorías populares', 'bilky' ),
			'search_items'               => __( 'Buscar categorías', 'bilky' ),
			'not_found'                  => __( 'No encontrado', 'bilky' ),
			'no_terms'                   => __( 'No hay categorías', 'bilky' ),
			'items_list'                 => __( 'Lista de categorías', 'bilky' ),
			'items_list_navigation'      => __( 'Navegación de lista de categorías', 'bilky' ),
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

		register_taxonomy( 'cliente_categoria', array( 'clientes' ), $args );
	}
	add_action( 'init', 'mwm_register_cliente_categoria_taxonomy', 0 );
}

