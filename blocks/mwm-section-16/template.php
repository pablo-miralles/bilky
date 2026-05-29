<?php
/**
 * Block Name: MWM Section 16
 */

$show_breadcrumbs = get_field( 'show_breadcrumbs' ) !== false;
$breadcrumb_button_text = get_field( 'breadcrumb_button_text' );
$show_breadcrumb_01 = get_field( 'show_breadcrumb_01' ) !== false;
$breadcrumb_01_text = get_field( 'breadcrumb_01_text' );
$show_title = get_field( 'show_title' ) !== false;
$title = get_field( 'title' );
$show_description = get_field( 'show_description' ) !== false;
$description = get_field( 'description' );
$source_type = get_field( 'source_type' ) ?: 'manual';
$cards_image_only = get_field( 'cards_image_only' ) !== false;
$post_type = get_field( 'post_type' );
$cpt_button_text = get_field( 'cpt_button_text' );
$manual_cards = get_field( 'cards' );

$items = array();
$categories = array();
$active_category_id = 0;
$active_category_slug = '';
$show_filter = false;
$taxonomy = '';

// Si es CPT, obtener taxonomía, categorías y items
if ( 'cpt' === $source_type && ! empty( $post_type ) ) {
	$taxonomies = get_object_taxonomies( $post_type, 'objects' );
	if ( ! empty( $taxonomies ) ) {
		foreach ( $taxonomies as $tax ) {
			if ( $tax->public ) {
				$taxonomy = $tax->name;
				break;
			}
		}
	}

	if ( ! empty( $taxonomy ) ) {
		$categories = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
			)
		);

		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			$show_filter = true;
			$active_category_slug = isset( $_GET['categoria'] ) ? sanitize_text_field( wp_unslash( $_GET['categoria'] ) ) : '';
			if ( $active_category_slug ) {
				$active_term = get_term_by( 'slug', $active_category_slug, $taxonomy );
				if ( $active_term ) {
					$active_category_id = $active_term->term_id;
				}
			} elseif ( ! empty( $categories[0] ) ) {
				$active_category_id = $categories[0]->term_id;
				$active_category_slug = $categories[0]->slug;
			}
		}
	}

	require_once __DIR__ . '/get-cpt-items.php';
	$items = mwm_section_16_get_cpt_items( $post_type, $taxonomy, $active_category_slug, $cpt_button_text ?? '' );
} elseif ( 'manual' === $source_type && ! empty( $manual_cards ) ) {
	// Construir array de items desde cards manuales
	foreach ( $manual_cards as $card ) {
		// Si cards_image_only está activado, solo verificar que haya imagen
		// Si no, verificar que haya título
		if ( $cards_image_only ) {
			if ( empty( $card['image'] ) ) {
				continue;
			}
		} else {
			if ( empty( $card['title'] ) ) {
				continue;
			}
		}

		// Obtener el enlace del campo link
		$card_link = isset( $card['link'] ) ? $card['link'] : array();
		$card_url = '';
		$card_target = false;
		$card_button_text = __( 'Web', 'bilky' );
		
		if ( ! empty( $card_link ) && is_array( $card_link ) && ! empty( $card_link['url'] ) ) {
			$card_url = $card_link['url'];
			$card_target = isset( $card_link['target'] ) && ( '_blank' === $card_link['target'] );

			// Si el campo link tiene título, usarlo como texto del botón
			if ( ! empty( $card_link['title'] ) ) {
				$card_button_text = $card_link['title'];
			}
		}

		$items[] = array(
			'title'       => isset( $card['title'] ) ? $card['title'] : '',
			'image_id'    => isset( $card['image'] ) ? $card['image'] : 0,
			'description' => isset( $card['description'] ) ? $card['description'] : '',
			'url'         => $card_url,
			'target'      => $card_target,
			'button_text' => $card_button_text,
		);
	}
}

// URL base para el filtro (enlaces tradicionales; JS puede interceptar para filtro sin recarga)
$current_url = remove_query_arg( 'categoria' );

// Atributos para filtro AJAX (solo en modo CPT con filtro)
$section_data_attrs = '';
if ( 'cpt' === $source_type && $show_filter && ! empty( $post_type ) && ! empty( $taxonomy ) ) {
	$section_data_attrs = sprintf(
		' data-post-type="%s" data-taxonomy="%s" data-cpt-button-text="%s" data-cards-image-only="%s"',
		esc_attr( $post_type ),
		esc_attr( $taxonomy ),
		esc_attr( $cpt_button_text ?? '' ),
		$cards_image_only ? '1' : '0'
	);
}
?>

<section class="mwm-section-16"<?php echo $section_data_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="mwm-section-16__wrapper">
		<div class="mwm-section-16__header">
			<div class="mwm-section-07 mwm-section-07--center mwm-section-07--background-white">
				<div class="mwm-section-07__wrapper">
					<?php if ( $show_breadcrumbs ) : ?>
						<div class="mwm-section-07__breadcrumbs">
							<?php if ( $breadcrumb_button_text ) : ?>
								<?php
								echo mwm_button(
									array(
										'text'    => esc_html( $breadcrumb_button_text ),
										'variant' => 'outline',
										'color'   => 'secundary',
										'size'    => 'sm',
										'is_tag'  => true,
									)
								);
								?>
							<?php endif; ?>

							<?php if ( $show_breadcrumb_01 && $breadcrumb_01_text ) : ?>
								<div class="mwm-section-07__breadcrumb-item">
									<span class="mwm-section-07__breadcrumb-separator">|</span>
									<span class="mwm-section-07__breadcrumb-text">
										<?php echo esc_html( $breadcrumb_01_text ); ?>
									</span>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="mwm-section-07__content">
						<?php if ( $show_title && $title ) : ?>
							<h2 class="mwm-section-07__title is-style-h-100">
								<?php
								$title_lines = explode( "\n", $title );
								foreach ( $title_lines as $index => $line ) {
									$line = trim( $line );
									if ( ! empty( $line ) ) {
										echo '<span class="mwm-section-07__title-line">' . esc_html( $line ) . '</span>';
										if ( $index < count( $title_lines ) - 1 ) {
											echo ' ';
										}
									}
								}
								?>
							</h2>
						<?php endif; ?>

						<?php if ( $show_description && $description ) : ?>
							<div class="mwm-section-07__text-body is-style-b-200">
								<?php echo wp_kses_post( wpautop( $description ) ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<?php if ( $show_filter && ! empty( $categories ) && ! is_wp_error( $categories ) && ! empty( $taxonomy ) ) : ?>
			<div class="mwm-section-16__filters">
				<?php foreach ( $categories as $category ) : ?>
					<?php
					$is_active = ( $category->term_id === $active_category_id );
					$filter_url = add_query_arg( 'categoria', $category->slug, $current_url );
					$button_class = $is_active ? 'mwm-button--active' : '';
					?>
					<a href="<?php echo esc_url( $filter_url ); ?>" class="mwm-button mwm-button--terciary mwm-button--sm <?php echo esc_attr( $button_class ); ?>">
						<span class="mwm-button__text"><?php echo esc_html( $category->name ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $items ) ) : ?>
			<div class="mwm-section-16__list">
				<?php require __DIR__ . '/cards-list.php'; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
