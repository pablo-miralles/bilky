<?php
/**
 * Partial: lista de cards para MWM Section 16.
 * Requiere $items (array) y $cards_image_only (bool).
 */

if ( empty( $items ) || ! is_array( $items ) ) {
	return;
}

$default_button_text = __( 'Web', 'bilky' );
?>
<?php foreach ( $items as $item ) : ?>
	<?php
	$item_title       = isset( $item['title'] ) ? $item['title'] : '';
	$item_image_id   = isset( $item['image_id'] ) ? $item['image_id'] : 0;
	$item_description = isset( $item['description'] ) ? $item['description'] : '';
	$item_url        = isset( $item['url'] ) ? $item['url'] : '';
	$item_target     = isset( $item['target'] ) ? $item['target'] : false;
	$item_button_text = isset( $item['button_text'] ) ? $item['button_text'] : $default_button_text;

	$image_alt = '';
	if ( $item_image_id ) {
		$image_alt = get_post_meta( $item_image_id, '_wp_attachment_image_alt', true );
		if ( empty( $image_alt ) && ! empty( $item_title ) ) {
			$image_alt = esc_attr( $item_title );
		}
	}

	$card_classes = array( 'mwm-card-05', 'mwm-card-05--bg-grey', 'mwm-card-05--media-logo' );
	if ( ! empty( $cards_image_only ) ) {
		$card_classes[] = 'mwm-card-05--only-media';
	}
	?>
	<article class="<?php echo esc_attr( implode( ' ', $card_classes ) ); ?>">
		<div class="mwm-card-05__wrapper">
			<?php if ( $item_image_id ) : ?>
				<div class="mwm-card-05__media">
					<?php if ( $item_url ) : ?>
						<a href="<?php echo esc_url( $item_url ); ?>" <?php echo $item_target ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>></a>
					<?php endif; ?>
					<?php echo wp_get_attachment_image( $item_image_id, 'full', false, array( 'alt' => $image_alt ) ); ?>
				</div>
			<?php endif; ?>

			<?php if ( empty( $cards_image_only ) ) : ?>
				<div class="mwm-card-05__content">
					<h3 class="mwm-card-05__title is-style-h-300">
						<?php echo esc_html( $item_title ); ?>
					</h3>

					<?php if ( ! empty( $item_description ) ) : ?>
						<div class="mwm-card-05__desc is-style-b-200">
							<?php echo wp_kses_post( wpautop( $item_description ) ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $item_url ) : ?>
						<div class="mwm-card-05__button">
							<?php
							echo mwm_button(
								array(
									'text'         => esc_html( $item_button_text ),
									'url'          => esc_url( $item_url ),
									'variant'      => 'fill',
									'color'        => 'terciary',
									'size'         => 'sm',
									'target_blank' => $item_target,
								)
							);
							?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</article>
<?php endforeach; ?>
