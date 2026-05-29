<?php
/**
 * Custom Post Type: Centro de ayuda
 *
 * CPT para artículos del centro de ayuda.
 */

if ( ! function_exists( 'mwm_register_centro_de_ayuda_cpt' ) ) {
	/**
	 * Registrar el CPT Centro de ayuda.
	 */
	function mwm_register_centro_de_ayuda_cpt() {
		$labels = array(
			'name'                  => _x( 'Centro de ayuda', 'Post Type General Name', 'bilky' ),
			'singular_name'         => _x( 'Artículo de ayuda', 'Post Type Singular Name', 'bilky' ),
			'menu_name'             => __( 'Centro de ayuda', 'bilky' ),
			'name_admin_bar'        => __( 'Artículo de ayuda', 'bilky' ),
			'archives'              => __( 'Archivo del centro de ayuda', 'bilky' ),
			'attributes'            => __( 'Atributos del artículo de ayuda', 'bilky' ),
			'parent_item_colon'     => __( 'Artículo padre:', 'bilky' ),
			'all_items'             => __( 'Todos los artículos de ayuda', 'bilky' ),
			'add_new_item'          => __( 'Añadir nuevo artículo de ayuda', 'bilky' ),
			'add_new'               => __( 'Añadir nuevo', 'bilky' ),
			'new_item'              => __( 'Nuevo artículo de ayuda', 'bilky' ),
			'edit_item'             => __( 'Editar artículo de ayuda', 'bilky' ),
			'update_item'           => __( 'Actualizar artículo de ayuda', 'bilky' ),
			'view_item'             => __( 'Ver artículo de ayuda', 'bilky' ),
			'view_items'            => __( 'Ver artículos de ayuda', 'bilky' ),
			'search_items'          => __( 'Buscar artículo de ayuda', 'bilky' ),
			'not_found'             => __( 'No encontrado', 'bilky' ),
			'not_found_in_trash'    => __( 'No encontrado en papelera', 'bilky' ),
			'featured_image'        => __( 'Imagen destacada', 'bilky' ),
			'set_featured_image'    => __( 'Establecer imagen destacada', 'bilky' ),
			'remove_featured_image' => __( 'Quitar imagen destacada', 'bilky' ),
			'use_featured_image'    => __( 'Usar como imagen destacada', 'bilky' ),
			'insert_into_item'      => __( 'Insertar en el artículo de ayuda', 'bilky' ),
			'uploaded_to_this_item' => __( 'Subido a este artículo de ayuda', 'bilky' ),
			'items_list'            => __( 'Lista de artículos de ayuda', 'bilky' ),
			'items_list_navigation' => __( 'Navegación de lista de artículos de ayuda', 'bilky' ),
			'filter_items_list'     => __( 'Filtrar lista de artículos de ayuda', 'bilky' ),
		);

		$args = array(
			'label'               => __( 'Artículo de ayuda', 'bilky' ),
			'description'         => __( 'Custom Post Type para gestionar contenidos del centro de ayuda', 'bilky' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			// Las categorías y etiquetas del centro de ayuda se gestionan con taxonomías específicas.
			'taxonomies'          => array( 'category_centro_de_ayuda', 'tag_centro_de_ayuda' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 21,
			'menu_icon'           => 'dashicons-sos',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => array(
				'slug'       => 'centro-de-ayuda',
				'with_front' => false,
			),
			'capability_type'     => 'post',
			'show_in_rest'        => true,
		);

		// Clave del CPT: máximo 20 caracteres, solo minúsculas y guiones bajos.
		register_post_type( 'centro_de_ayuda', $args );

		// Registrar taxonomía de categorías específica para el centro de ayuda.
		$tax_labels = array(
			'name'              => _x( 'Categorías del centro de ayuda', 'taxonomy general name', 'bilky' ),
			'singular_name'     => _x( 'Categoría del centro de ayuda', 'taxonomy singular name', 'bilky' ),
			'search_items'      => __( 'Buscar categorías del centro de ayuda', 'bilky' ),
			'all_items'         => __( 'Todas las categorías del centro de ayuda', 'bilky' ),
			'parent_item'       => __( 'Categoría superior', 'bilky' ),
			'parent_item_colon' => __( 'Categoría superior:', 'bilky' ),
			'edit_item'         => __( 'Editar categoría del centro de ayuda', 'bilky' ),
			'update_item'       => __( 'Actualizar categoría del centro de ayuda', 'bilky' ),
			'add_new_item'      => __( 'Añadir nueva categoría del centro de ayuda', 'bilky' ),
			'new_item_name'     => __( 'Nombre de la nueva categoría del centro de ayuda', 'bilky' ),
			'menu_name'         => __( 'Categorías del centro de ayuda', 'bilky' ),
		);

		$tax_args = array(
			'hierarchical'      => true,
			'labels'            => $tax_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				// Mantener la taxonomía debajo del slug del CPT.
				'slug'       => 'centro-de-ayuda/categoria',
				'with_front' => false,
			),
			'show_in_rest'      => true,
		);

		register_taxonomy( 'category_centro_de_ayuda', array( 'centro_de_ayuda' ), $tax_args );

		// Registrar taxonomía de etiquetas para el centro de ayuda.
		$tag_labels = array(
			'name'                       => _x( 'Etiquetas del centro de ayuda', 'taxonomy general name', 'bilky' ),
			'singular_name'              => _x( 'Etiqueta del centro de ayuda', 'taxonomy singular name', 'bilky' ),
			'search_items'               => __( 'Buscar etiquetas del centro de ayuda', 'bilky' ),
			'popular_items'              => __( 'Etiquetas populares del centro de ayuda', 'bilky' ),
			'all_items'                  => __( 'Todas las etiquetas del centro de ayuda', 'bilky' ),
			'edit_item'                  => __( 'Editar etiqueta del centro de ayuda', 'bilky' ),
			'update_item'                => __( 'Actualizar etiqueta del centro de ayuda', 'bilky' ),
			'add_new_item'               => __( 'Añadir nueva etiqueta del centro de ayuda', 'bilky' ),
			'new_item_name'              => __( 'Nombre de la nueva etiqueta del centro de ayuda', 'bilky' ),
			'separate_items_with_commas' => __( 'Separar etiquetas con comas', 'bilky' ),
			'add_or_remove_items'        => __( 'Añadir o quitar etiquetas', 'bilky' ),
			'choose_from_most_used'      => __( 'Elegir entre las etiquetas más usadas', 'bilky' ),
			'menu_name'                  => __( 'Etiquetas del centro de ayuda', 'bilky' ),
		);

		$tag_args = array(
			'hierarchical'      => false,
			'labels'            => $tag_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			// No queremos archivo propio de etiquetas; las usaremos combinadas con categoría.
			'rewrite'           => false,
			'show_in_rest'      => true,
			'public'            => true,
		);

		register_taxonomy( 'tag_centro_de_ayuda', array( 'centro_de_ayuda' ), $tag_args );
	}

		add_action( 'init', 'mwm_register_centro_de_ayuda_cpt', 0 );
}

if ( ! function_exists( 'mwm_centro_ayuda_author_edit_others' ) ) {
	/**
	 * Permitir que el rol Author pueda editar artículos de otros autores en el centro de ayuda.
	 * Añade edit_others_posts para poder cambiar el autor en el metabox y editar artículos ajenos.
	 */
	function mwm_centro_ayuda_author_edit_others() {
		$author_role = get_role( 'author' );
		if ( $author_role && ! $author_role->has_cap( 'edit_others_posts' ) ) {
			$author_role->add_cap( 'edit_others_posts' );
		}
	}
	add_action( 'init', 'mwm_centro_ayuda_author_edit_others', 20 );
}

