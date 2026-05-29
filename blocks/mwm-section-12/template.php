<?php
/**
 * Block Name: MWM Section 12
 */

$logos = get_field('logos');
$card_background = get_field('card_background') ?: 'transparent';

if ( ! $logos || empty( $logos ) ) {
	return;
}

// Generar ID único para este slider
$slider_id = 'mwm-section-12-slider-' . uniqid();

// Clase para el background de las cards
$card_bg_class = 'mwm-section-12--card-bg-' . esc_attr( $card_background );
?>

<section class="mwm-section-12 <?php echo esc_attr( $card_bg_class ); ?>">
	<div class="mwm-section-12__wrapper">
		<div class="swiper mwm-section-12__swiper" id="<?php echo esc_attr( $slider_id ); ?>">
			<div class="swiper-wrapper">
				<?php 
				foreach ( $logos as $logo_item ) : 
					$logo_image = $logo_item['logo'];
					if ( ! $logo_image ) continue;
					
					$logo_alt = get_post_meta( $logo_image, '_wp_attachment_image_alt', true );
					if ( empty( $logo_alt ) ) {
						$logo_title = get_the_title( $logo_image );
						$logo_alt = ! empty( $logo_title ) ? $logo_title : __( 'Logo de cliente', 'bilky' );
					}
				?>
					<div class="swiper-slide">
						<div class="mwm-section-12__card">
							<div class="mwm-section-12__card-image">
								<?php echo wp_get_attachment_image( $logo_image, 'full', false, array( 'alt' => esc_attr( $logo_alt ) ) ); ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

