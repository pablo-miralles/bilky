<?php
/**
 * Block Name: MWM Card 03 Group
 */

$cards = get_field('cards');

if ( ! $cards || empty( $cards ) ) {
	return;
}

// Generar ID único para este slider
$slider_id = 'mwm-card-03-group-' . uniqid();
$prev_id = $slider_id . '-prev';
$next_id = $slider_id . '-next';
?>

<section class="mwm-card-03-group">
	<div class="mwm-card-03-group__wrapper">
		<div class="mwm-card-03-group__navigation">
			<button 
				type="button" 
				class="mwm-card-03-group__nav-button mwm-card-03-group__nav-button--prev" 
				id="<?php echo esc_attr( $prev_id ); ?>"
				aria-label="<?php esc_attr_e( 'Slide anterior', 'bilky' ); ?>"
			>
				<span class="mwm-card-03-group__nav-icon">
					<i class="fa-solid fa-chevron-left"></i>
				</span>
			</button>
			<button 
				type="button" 
				class="mwm-card-03-group__nav-button mwm-card-03-group__nav-button--next" 
				id="<?php echo esc_attr( $next_id ); ?>"
				aria-label="<?php esc_attr_e( 'Slide siguiente', 'bilky' ); ?>"
			>
				<span class="mwm-card-03-group__nav-icon">
					<i class="fa-solid fa-chevron-right"></i>
				</span>
			</button>
		</div>

		<div class="swiper mwm-card-03-group__swiper" id="<?php echo esc_attr( $slider_id ); ?>">
			<div class="swiper-wrapper">
				<?php foreach ( $cards as $card ) : 
					$number = $card['number'] ?: '01';
					$icon = $card['icon'] ?: 'wifi';
					$card_title = $card['title'];
					$description = $card['description'];
				?>
					<div class="swiper-slide">
						<article class="mwm-card-03-group__card">
							<div class="mwm-card-03-group__card-header">
								<?php if ( $number ) : ?>
									<div class="mwm-card-03-group__card-number">
										<?php echo esc_html( $number ); ?>
									</div>
								<?php endif; ?>
								<?php if ( $icon ) : ?>
									<div class="mwm-card-03-group__card-icon">
										<i class="fa-solid fa-<?php echo esc_attr( $icon ); ?>"></i>
									</div>
								<?php endif; ?>
							</div>

							<?php if ( $card_title ) : ?>
								<h3 class="mwm-card-03-group__card-title is-style-h-300">
									<?php echo esc_html( $card_title ); ?>
								</h3>
							<?php endif; ?>

							<?php if ( $description ) : ?>
								<div class="mwm-card-03-group__card-description is-style-b-200">
									<?php echo wp_kses_post( wpautop( $description ) ); ?>
								</div>
							<?php endif; ?>
						</article>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<script>
jQuery(document).ready(function($) {
	var swiperId = '<?php echo esc_js( $slider_id ); ?>';
	var swiperPrevId = '<?php echo esc_js( $prev_id ); ?>';
	var swiperNextId = '<?php echo esc_js( $next_id ); ?>';

	if ($('#' + swiperId).length && typeof Swiper !== 'undefined') {
		var swiper = new Swiper('#' + swiperId, {
			loop: true,
			slidesPerView: 1,
			spaceBetween: 12,
			navigation: {
				nextEl: '#' + swiperNextId,
				prevEl: '#' + swiperPrevId,
			},
			breakpoints: {
				768: {
					slidesPerView: 2,
					spaceBetween: 12,
				},
				1280: {
					slidesPerView: 4,
					spaceBetween: 12,
				},
			},
		});
	}
});
</script>

