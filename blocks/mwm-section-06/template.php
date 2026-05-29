<?php
/**
 * Block Name: MWM Section 06
 */

$list_title = get_field('title');
$list_items = get_field('list_items');
$card = get_field('card');


if ( ! $card || empty( $card ) ) {
	return;
}

$card_title = $card['title'];
$card_avatars = $card['avatars'];
$card_button_text = $card['button_text'];
$card_button_url = $card['button_url'];

?>

<section class="mwm-section-06">
	<div class="mwm-section-06__wrapper">
		<div class="mwm-section-06__list">
			<div class="mwm-section-06__list-title"><?php echo esc_html( $list_title ); ?></div>
			<div class="mwm-section-06__list-items">
				<?php foreach ( $list_items as $item ) : ?>
					<div class="mwm-section-06__list-item">
						<?php echo mwm_button( array(
							'text' => esc_html( $item['text'] ),
							'is_tag' => true,
							'variant' => 'soft',
							'color' => 'terciary',
							'size' => 'sm',
						) ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php if ( $card ) : ?>
			<div class="mwm-card-11">
				<div class="mwm-card-11__title is-style-b-200"><?php echo ( $card_title ); ?></div>
				<div class="mwm-card-11__content">
					<?php if ( $card_avatars && ! empty( $card_avatars ) ) : ?>
						<div class="mwm-card-11__avatars">
							<?php foreach ( $card_avatars as $avatar_count => $avatar ) : ?>
								<?php $avatar_class = 'mwm-card-11__avatar'; ?>
								<?php if ( $avatar_count === 4 ) {
									$avatar_class .= ' mwm-card-11__avatar--fourth';
								} ?>
								<div class="<?php echo esc_attr( $avatar_class ); ?>">
									<?php echo wp_get_attachment_image( $avatar['image'], 'full', false, array( 'class' => 'mwm-card-11__avatar-image', 'alt' => __( 'Avatar de usuario', 'bilky' ) ) ); ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php if ( $card_button_text ) : ?>
						<div class="mwm-card-11__button">
							<?php echo mwm_button( array(
								'text' => esc_html( $card_button_text ),
								'url' => esc_url( $card_button_url ),
								'variant' => 'fill-icon',
								'color' => 'primary',
								'size' => 'xl',
							) ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

