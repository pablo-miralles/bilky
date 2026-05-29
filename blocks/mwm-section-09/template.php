<?php
/**
 * Block Name: MWM Section 09
 */

$text_color = get_field( 'text_color' ) ?: 'blue';
$card_background = get_field( 'card_background' ) ?: 'white';
$breadcrumb_text = get_field( 'breadcrumb_button_text' );
$title = get_field( 'title' );
$rows = get_field( 'rows' );

// Sanitizar color del texto
$allowed_text_colors = array( 'blue', 'white' );
if ( ! in_array( $text_color, $allowed_text_colors, true ) ) {
	$text_color = 'blue';
}

// Sanitizar fondo de las cards
$allowed_card_backgrounds = array( 'white', 'transparent', 'grey' );
if ( ! in_array( $card_background, $allowed_card_backgrounds, true ) ) {
	$card_background = 'white';
}

$classes = array(
	'mwm-section-09',
	'mwm-section-09--text-' . esc_attr( $text_color ),
);
?>

<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="mwm-section-09__wrapper">
		<?php if ( $breadcrumb_text || $title ) : ?>
			<div class="mwm-section-09__header">
				<?php if ( $breadcrumb_text ) : ?>
					<div class="mwm-section-09__tag">
						<?php
							$button_color = ( 'white' === $text_color ) ? 'terciary' : 'secundary';
							$button_args = array(
								'text'    => esc_html( $breadcrumb_text ),
								'variant' => 'outline',
								'color'   => $button_color,
								'size'    => 'sm',
								'is_tag'  => true,
							);
						echo mwm_button( $button_args );
						?>
					</div>
				<?php endif; ?>
				<?php if ( $title ) : ?>
					<h2 class="mwm-section-09__title is-style-h-100">
						<?php
						$title_lines = explode( "\n", $title );
						foreach ( $title_lines as $index => $line ) {
							$line = trim( $line );
							if ( ! empty( $line ) ) {
								echo '<span class="mwm-section-09__title-line">' . esc_html( $line ) . '</span>';
								if ( $index < count( $title_lines ) - 1 ) {
									echo ' ';
								}
							}
						}
						?>
					</h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ( $rows && ! empty( $rows ) ) : ?>
			<div class="mwm-section-09__rows">
				<?php foreach ( $rows as $row ) : ?>
					<?php
					$row_cards = isset( $row['cards'] ) ? $row['cards'] : array();
					if ( empty( $row_cards ) || ! is_array( $row_cards ) ) {
						continue;
					}
	
					// Determinar número de columnas según la cantidad de cards
					$card_count = count( $row_cards );
					$columns_class = 'mwm-section-09__row--cols-' . $card_count;
					?>
					<div class="mwm-section-09__row <?php echo esc_attr( $columns_class ); ?>">
						<?php foreach ( $row_cards as $card ) : ?>
							<?php
							$card_icon = isset( $card['icon'] ) ? $card['icon'] : 'briefcase';
							$card_title = isset( $card['title'] ) ? $card['title'] : '';
							$card_subtitle = isset( $card['subtitle'] ) ? $card['subtitle'] : '';
							$card_description = isset( $card['description'] ) ? $card['description'] : '';
	
							if ( empty( $card_title ) ) {
								continue;
							}
	
							// Determinar clase de fondo según el selector
							$card_bg_class = 'mwm-card-04--bg-' . esc_attr( $card_background );
							?>
							<article class="mwm-card-04 <?php echo esc_attr( $card_bg_class ); ?>">
								<div class="mwm-card-04__header">
									<div class="mwm-card-04__icon">
										<?php
										echo mwm_icon_circle(
											array(
												'icon'    => $card_icon,
												'variant' => 'fill-icon',
												'color'   => 'terciary',
												'size'    => 'xl',
											)
										);
										?>
									</div>
									<div class="mwm-card-04__header-text">
										<h3 class="mwm-card-04__title is-style-h-500">
											<?php echo esc_html( $card_title ); ?>
										</h3>
										<?php if ( ! empty( $card_subtitle ) ) : ?>
											<p class="mwm-card-04__subtitle is-style-b-100">
												<?php echo esc_html( $card_subtitle ); ?>
											</p>
										<?php endif; ?>
									</div>
								</div>
								<?php if ( ! empty( $card_description ) ) : ?>
									<div class="mwm-card-04__description is-style-b-200">
										<?php echo wp_kses_post( wpautop( $card_description ) ); ?>
									</div>
								<?php endif; ?>
							</article>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

</section>

