<?php
/**
 * Helper: obtiene el array de items para el bloque Section 16 desde un CPT filtrado por término.
 *
 * @param string $post_type      Slug del CPT.
 * @param string $taxonomy       Slug de la taxonomía.
 * @param string $term_slug      Slug del término (categoría).
 * @param string $cpt_button_text Texto del botón (vacío = "Web").
 * @return array Lista de items con title, image_id, description, url, target, button_text.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'mwm_section_16_singularize' ) ) {
	/**
	 * Singulariza un string de forma básica (quitar 's' final).
	 *
	 * @param string $string Ej: "clientes".
	 * @return string Ej: "cliente".
	 */
	function mwm_section_16_singularize( $string ) {
		$singular = $string;
		if ( substr( $string, -1 ) === 's' ) {
			$singular = substr( $string, 0, -1 );
		}
		return $singular;
	}
}

if ( ! function_exists( 'mwm_section_16_get_cpt_items' ) ) {
	/**
	 * Construye el array de items para Section 16 desde posts del CPT.
	 *
	 * @param string $post_type       Slug del CPT.
	 * @param string $taxonomy       Slug de la taxonomía.
	 * @param string $term_slug      Slug del término; si está vacío se traen todos.
	 * @param string $cpt_button_text Texto del botón.
	 * @return array Items para cards-list.php.
	 */
	function mwm_section_16_get_cpt_items( $post_type, $taxonomy, $term_slug, $cpt_button_text ) {
		$items = array();
		$button_text = ! empty( $cpt_button_text ) ? $cpt_button_text : __( 'Web', 'bilky' );

		$posts_args = array(
			'post_type'      => $post_type,
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		);

		if ( ! empty( $taxonomy ) && ! empty( $term_slug ) ) {
			$term = get_term_by( 'slug', $term_slug, $taxonomy );
			if ( $term && ! is_wp_error( $term ) ) {
				$posts_args['tax_query'] = array(
					array(
						'taxonomy' => $taxonomy,
						'field'    => 'term_id',
						'terms'    => $term->term_id,
					),
				);
			}
		}

		$posts = get_posts( $posts_args );

		$possible_url_fields = array(
			$post_type . '_url',
			mwm_section_16_singularize( $post_type ) . '_url',
			'url',
			'link',
		);

		foreach ( $posts as $post_item ) {
			$item_title = get_the_title( $post_item->ID );
			if ( empty( $item_title ) ) {
				continue;
			}

			$item_image_id = get_post_thumbnail_id( $post_item->ID );
			$item_excerpt  = get_the_excerpt( $post_item->ID );
			$item_content  = $post_item->post_content;
			$item_description = '';
			if ( ! empty( $item_excerpt ) ) {
				$item_description = $item_excerpt;
			} elseif ( ! empty( $item_content ) ) {
				$item_description = wp_trim_words( $item_content, 20 );
			}

			$item_url = '';
			foreach ( $possible_url_fields as $field_name ) {
				$url_value = get_field( $field_name, $post_item->ID );
				if ( ! empty( $url_value ) ) {
					$item_url = is_array( $url_value ) && isset( $url_value['url'] ) ? $url_value['url'] : $url_value;
					break;
				}
			}

			$items[] = array(
				'title'       => $item_title,
				'image_id'    => $item_image_id,
				'description' => $item_description,
				'url'         => $item_url,
				'target'      => true,
				'button_text' => $button_text,
			);
		}

		return $items;
	}
}
