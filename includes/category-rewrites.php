<?php
/**
 * Rewrite rules y helper para URLs de categorías contextuales.
 *
 * Permite mantener:
 * - miweb.com/centro-de-ayuda/category/nombre-categoria (centro de ayuda)
 * - miweb.com/blogs/category/nombre-categoria (blog, según slug de la página de entradas)
 */

if ( ! function_exists( 'mwm_is_centro_de_ayuda_context' ) ) {
	/**
	 * Indica si estamos en contexto del centro de ayuda (archive, category archive o single).
	 *
	 * @return bool True si es centro de ayuda.
	 */
	function mwm_is_centro_de_ayuda_context() {
		if ( is_post_type_archive( 'centro_de_ayuda' ) || is_singular( 'centro_de_ayuda' ) ) {
			return true;
		}
		return is_category() && 'centro_de_ayuda' === get_query_var( 'post_type' );
	}
}

if ( ! function_exists( 'mwm_get_blog_page_slug' ) ) {
	/**
	 * Obtiene el slug de la página de entradas (page_for_posts).
	 *
	 * @return string Slug de la página de entradas o 'blogs' por defecto.
	 */
	function mwm_get_blog_page_slug() {
		$page_id = (int) get_option( 'page_for_posts', 0 );
		if ( $page_id > 0 ) {
			$page = get_post( $page_id );
			if ( $page && 'publish' === $page->post_status && ! empty( $page->post_name ) ) {
				return $page->post_name;
			}
		}
		return 'blogs';
	}
}

if ( ! function_exists( 'mwm_get_category_link_for_context' ) ) {
	/**
	 * Genera el enlace de categoría según el contexto (blog o centro de ayuda).
	 *
	 * @param int|object $term   ID del término o objeto WP_Term.
	 * @param string     $context 'post' para blog, 'centro_de_ayuda' para centro de ayuda.
	 * @return string URL de la categoría.
	 */
	function mwm_get_category_link_for_context( $term, $context = 'post' ) {
		if ( is_numeric( $term ) ) {
			$term = get_term( $term, 'category' );
		}
		if ( ! $term || is_wp_error( $term ) ) {
			return home_url( '/' );
		}

		$slug = $term->slug;
		if ( 'centro_de_ayuda' === $context ) {
			return home_url( '/centro-de-ayuda/category/' . $slug . '/' );
		}

		$blog_slug = mwm_get_blog_page_slug();
		return home_url( '/' . $blog_slug . '/category/' . $slug . '/' );
	}
}

if ( ! function_exists( 'mwm_add_category_context_rewrite_rules' ) ) {
	/**
	 * Registra las reglas de rewrite para categorías contextuales.
	 */
	function mwm_add_category_context_rewrite_rules() {
		$blog_slug = mwm_get_blog_page_slug();

		add_rewrite_rule(
			'^centro-de-ayuda/category/([^/]+)/?$',
			'index.php?category_name=$matches[1]&post_type=centro_de_ayuda',
			'top'
		);

		add_rewrite_rule(
			'^' . preg_quote( $blog_slug, '/' ) . '/category/([^/]+)/?$',
			'index.php?category_name=$matches[1]',
			'top'
		);
	}
	add_action( 'init', 'mwm_add_category_context_rewrite_rules', 11 );
}

if ( ! function_exists( 'mwm_prevent_redirect_canonical_for_centro_categories' ) ) {
	/**
	 * Evita que redirect_canonical redirija las URLs de categoría del centro de ayuda.
	 *
	 * @param string $redirect_url  URL a la que WordPress redirigiría.
	 * @param string $requested_url URL solicitada por el usuario.
	 * @return string|false URL de redirección o false para evitarla.
	 */
	function mwm_prevent_redirect_canonical_for_centro_categories( $redirect_url, $requested_url ) {
		if ( preg_match( '#/centro-de-ayuda/category/[^/]+/?$#', $requested_url ) ) {
			return false;
		}
		return $redirect_url;
	}
	add_filter( 'redirect_canonical', 'mwm_prevent_redirect_canonical_for_centro_categories', 10, 2 );
}

if ( ! function_exists( 'mwm_flush_rewrite_rules_on_activation' ) ) {
	/**
	 * Flush rewrite rules al activar el tema para que las nuevas reglas funcionen.
	 */
	function mwm_flush_rewrite_rules_on_activation() {
		mwm_add_category_context_rewrite_rules();
		flush_rewrite_rules();
	}
	add_action( 'after_switch_theme', 'mwm_flush_rewrite_rules_on_activation' );
}
