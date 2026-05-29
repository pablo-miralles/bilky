<?php
/**
 * Block Name: MWM Section 05
 */

$title = get_field('title');
$source_type = get_field('source_type') ?: 'manual';
$description_opacity = get_field('description_opacity') ?: 0;
$cards = array();

// Obtener cards según el tipo de fuente
if ( $source_type === 'pages' ) {
	// Obtener páginas seleccionadas
	$page_ids = get_field('pages');
	
	if ( $page_ids && ! empty( $page_ids ) ) {
		foreach ( $page_ids as $page_id ) {
			$page = get_post( $page_id );
			if ( ! $page ) {
				continue;
			}
			
			// Obtener datos de la página
			$page_icon = get_field( 'page_icon', $page_id ) ?: 'briefcase';
			$page_title = get_the_title( $page_id );
			$page_description = get_field( 'page_card_description', $page_id );
			$page_url = get_permalink( $page_id );
			
			// Construir array de card con estructura similar al repeater
			$cards[] = array(
				'icon' => $page_icon,
				'title' => $page_title,
				'description' => $page_description,
				'show_button' => true,
				'button_text' => __( 'Ver más', 'bilky' ),
				'button_url' => $page_url,
			);
		}
	}
} else {
	// Usar cards manuales (por defecto)
	$cards = get_field('cards');
}

if ( ! $cards || empty( $cards ) ) {
	return;
}

// Generar ID único para este slider
$slider_id = 'mwm-section-05-slider-' . uniqid();
$prev_id = $slider_id . '-prev';
$next_id = $slider_id . '-next';
?>

<section class="mwm-section-05">
	<div class="mwm-section-05__wrapper">
		<div class="mwm-section-05__header">
			<?php if ( $title ) : ?>
				<div class="mwm-section-05__title is-style-h-400">
					<?php echo esc_html( $title ); ?>
				</div>
			<?php endif; ?>

			<div class="mwm-section-05__navigation">
				<button 
					type="button" 
					class="mwm-section-05__nav-button mwm-section-05__nav-button--prev" 
					id="<?php echo esc_attr( $prev_id ); ?>"
					aria-label="<?php esc_attr_e( 'Slide anterior', 'bilky' ); ?>"
				>
					<span class="mwm-section-05__nav-icon">
						<i class="fa-solid fa-chevron-left"></i>
					</span>
				</button>
				<button 
					type="button" 
					class="mwm-section-05__nav-button mwm-section-05__nav-button--next" 
					id="<?php echo esc_attr( $next_id ); ?>"
					aria-label="<?php esc_attr_e( 'Slide siguiente', 'bilky' ); ?>"
				>
					<span class="mwm-section-05__nav-icon">
						<i class="fa-solid fa-chevron-right"></i>
					</span>
				</button>
			</div>
		</div>

		<div class="swiper mwm-section-05__swiper" id="<?php echo esc_attr( $slider_id ); ?>">
			<div class="swiper-wrapper">
				<?php foreach ( $cards as $card ) : 
					$icon = $card['icon'] ?: 'briefcase';
					$card_title = $card['title'];
					$description = $card['description'];
					$show_button = $card['show_button'] !== false;
					$button_text = $card['button_text'];
					$button_url = $card['button_url'];
				?>
					<div class="swiper-slide">
						<article class="mwm-card-02">
							<div class="mwm-card-02__header">
								<?php if ( $icon ) : ?>
									<div class="mwm-card-02__icon">
										<i class="fa-solid fa-<?php echo esc_attr( $icon ); ?>"></i>
									</div>
								<?php endif; ?>
								<?php if ( $card_title ) : ?>
									<h3 class="mwm-card-02__title is-style-h-300">
										<?php echo esc_html( $card_title ); ?>
									</h3>
								<?php endif; ?>
							</div>

							<?php if ( $description ) : ?>
								<div class="mwm-card-02__description is-style-b-200 <?php echo esc_attr( $description_opacity ? 'is-opacity-100' : '' ); ?>">
									<?php echo wp_kses_post( $description ); ?>
								</div>
							<?php endif; ?>

							<?php if ( $show_button && $button_text ) : ?>
								<div class="mwm-card-02__button">
									<a href="<?php echo esc_url( $button_url ); ?>" class="mwm-button mwm-button--fill mwm-button--terciary mwm-button--sm mwm-button--active-focus">
										<span class="mwm-button__text"><?php echo esc_html( $button_text ); ?></span>
									</a>
								</div>
							<?php endif; ?>
						</article>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="swiper-pagination"></div>
		</div>
	</div>
</section>

