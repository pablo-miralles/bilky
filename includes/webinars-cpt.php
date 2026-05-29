<?php
/**
 * Custom Post Type: Webinars
 *
 * CPT para sesiones y webinars con categorías propias.
 */

if ( ! function_exists( 'mwm_register_webinar_cpt' ) ) {
	/**
	 * Registrar el CPT Webinar y la taxonomía de categorías.
	 */
	function mwm_register_webinar_cpt() {
		$labels = array(
			'name'                  => _x( 'Webinars', 'Post Type General Name', 'bilky' ),
			'singular_name'         => _x( 'Webinar', 'Post Type Singular Name', 'bilky' ),
			'menu_name'             => __( 'Webinars', 'bilky' ),
			'name_admin_bar'        => __( 'Webinar', 'bilky' ),
			'archives'              => __( 'Archivo de webinars', 'bilky' ),
			'attributes'            => __( 'Atributos del webinar', 'bilky' ),
			'parent_item_colon'     => __( 'Webinar padre:', 'bilky' ),
			'all_items'             => __( 'Todos los webinars', 'bilky' ),
			'add_new_item'          => __( 'Añadir nuevo webinar', 'bilky' ),
			'add_new'               => __( 'Añadir nuevo', 'bilky' ),
			'new_item'              => __( 'Nuevo webinar', 'bilky' ),
			'edit_item'             => __( 'Editar webinar', 'bilky' ),
			'update_item'           => __( 'Actualizar webinar', 'bilky' ),
			'view_item'             => __( 'Ver webinar', 'bilky' ),
			'view_items'            => __( 'Ver webinars', 'bilky' ),
			'search_items'          => __( 'Buscar webinar', 'bilky' ),
			'not_found'             => __( 'No encontrado', 'bilky' ),
			'not_found_in_trash'    => __( 'No encontrado en papelera', 'bilky' ),
			'featured_image'        => __( 'Imagen destacada', 'bilky' ),
			'set_featured_image'    => __( 'Establecer imagen destacada', 'bilky' ),
			'remove_featured_image' => __( 'Quitar imagen destacada', 'bilky' ),
			'use_featured_image'    => __( 'Usar como imagen destacada', 'bilky' ),
			'insert_into_item'      => __( 'Insertar en el webinar', 'bilky' ),
			'uploaded_to_this_item' => __( 'Subido a este webinar', 'bilky' ),
			'items_list'            => __( 'Lista de webinars', 'bilky' ),
			'items_list_navigation' => __( 'Navegación de lista de webinars', 'bilky' ),
			'filter_items_list'     => __( 'Filtrar lista de webinars', 'bilky' ),
		);

		$args = array(
			'label'               => __( 'Webinar', 'bilky' ),
			'description'         => __( 'Webinars y sesiones en directo o grabadas', 'bilky' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'taxonomies'          => array( 'category_webinar', 'ponente_webinar' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 22,
			'menu_icon'           => 'dashicons-video-alt3',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => array(
				'slug'       => 'webinars',
				'with_front' => false,
			),
			'capability_type'     => 'post',
			'show_in_rest'        => true,
		);

		register_post_type( 'webinar', $args );

		$tax_labels = array(
			'name'              => _x( 'Categorías de webinar', 'taxonomy general name', 'bilky' ),
			'singular_name'     => _x( 'Categoría de webinar', 'taxonomy singular name', 'bilky' ),
			'search_items'      => __( 'Buscar categorías', 'bilky' ),
			'all_items'         => __( 'Todas las categorías', 'bilky' ),
			'parent_item'       => __( 'Categoría superior', 'bilky' ),
			'parent_item_colon' => __( 'Categoría superior:', 'bilky' ),
			'edit_item'         => __( 'Editar categoría', 'bilky' ),
			'update_item'       => __( 'Actualizar categoría', 'bilky' ),
			'add_new_item'      => __( 'Añadir categoría', 'bilky' ),
			'new_item_name'     => __( 'Nombre de la categoría', 'bilky' ),
			'menu_name'         => __( 'Categorías', 'bilky' ),
		);

		$tax_args = array(
			'hierarchical'      => true,
			'labels'            => $tax_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'       => 'webinars/categoria',
				'with_front' => false,
			),
			'show_in_rest'      => true,
		);

		register_taxonomy( 'category_webinar', array( 'webinar' ), $tax_args );

		$ponentes_labels = array(
			'name'              => _x( 'Ponentes', 'taxonomy general name', 'bilky' ),
			'singular_name'     => _x( 'Ponente', 'taxonomy singular name', 'bilky' ),
			'search_items'      => __( 'Buscar ponentes', 'bilky' ),
			'popular_items'     => __( 'Ponentes frecuentes', 'bilky' ),
			'all_items'         => __( 'Todos los ponentes', 'bilky' ),
			'edit_item'         => __( 'Editar ponente', 'bilky' ),
			'update_item'       => __( 'Actualizar ponente', 'bilky' ),
			'add_new_item'      => __( 'Añadir ponente', 'bilky' ),
			'new_item_name'     => __( 'Nombre del ponente', 'bilky' ),
			'separate_items_with_commas' => __( 'Separa los ponentes con comas', 'bilky' ),
			'add_or_remove_items'        => __( 'Añadir o quitar ponentes', 'bilky' ),
			'choose_from_most_used'      => __( 'Elegir entre los más usados', 'bilky' ),
			'not_found'                  => __( 'No se han encontrado ponentes.', 'bilky' ),
			'menu_name'                  => __( 'Ponentes', 'bilky' ),
		);

		$ponentes_args = array(
			'hierarchical'          => false,
			'labels'                => $ponentes_labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'show_in_nav_menus'     => false,
			'show_tagcloud'         => false,
			'show_in_quick_edit'    => true,
			'public'                => false,
			'publicly_queryable'    => false,
			'rewrite'               => false,
			'query_var'             => false,
			'show_in_rest'          => true,
			'meta_box_cb'           => 'post_tags_meta_box',
		);

		register_taxonomy( 'ponente_webinar', array( 'webinar' ), $ponentes_args );
	}

	add_action( 'init', 'mwm_register_webinar_cpt', 0 );
}
