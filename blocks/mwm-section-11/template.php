<?php
/**
 * Block Name: MWM Section 11
 */

// Obtener campos del header
$breadcrumb_text_1 = get_field( 'breadcrumb_text_1' );
$breadcrumb_text_2 = get_field( 'breadcrumb_text_2' );
$title = get_field( 'title' );
$text_body = get_field( 'text_body' );
// Obtener secciones del repetidor
$sections = get_field( 'sections' );

?>

<section class="mwm-section-11">
	<div class="mwm-section-11__wrapper">
		<?php if ( $breadcrumb_text_1 || $breadcrumb_text_2 || $title || $text_body ) : ?>
			<div class="mwm-section-11__header">
				<div class="mwm-section-07 mwm-section-07--center mwm-section-07--background-white">
					<div class="mwm-section-07__wrapper">
						<?php if ( $breadcrumb_text_1 || $breadcrumb_text_2 ) : ?>
							<div class="mwm-section-07__breadcrumbs">
								<?php if ( $breadcrumb_text_1 ) : ?>
									<?php
									$button_args = array(
										'text'    => esc_html( $breadcrumb_text_1 ),
										'variant' => 'outline',
										'color'   => 'secundary',
										'size'    => 'sm',
										'is_tag'  => true, // Sin enlace, solo texto
									);
									echo mwm_button( $button_args );
									?>
								<?php endif; ?>
	
								<?php if ( $breadcrumb_text_2 ) : ?>
									<div class="mwm-section-07__breadcrumb-item">
										<span class="mwm-section-07__breadcrumb-separator">|</span>
										<span class="mwm-section-07__breadcrumb-text">
											<?php echo esc_html( $breadcrumb_text_2 ); ?>
										</span>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
	
						<div class="mwm-section-07__content">
							<?php if ( $title ) : ?>
								<h2 class="mwm-section-07__title is-style-h-100">
									<?php
									// Dividir el título en líneas si hay saltos de línea
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
	
							<?php if ( $text_body ) : ?>
								<div class="mwm-section-07__text-body is-style-b-200">
									<?php echo wp_kses_post( $text_body ); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $sections && ! empty( $sections ) ) : ?>
			<div class="mwm-section-11__sections">
				<div class="mwm-section-11__line"></div>
				<div class="mwm-section-11__sticky-element">
					<div class="mwm-section-11__sticky-element-content">
						<?php 
						$section_index = 0;
						foreach ( $sections as $section ) : 
							$section_index++;
						?>
							<span class="mwm-section-11__sticky-element-number<?php echo ( $section_index === 1 ) ? ' is-active' : ''; ?>" data-sticky-index="<?php echo esc_attr( $section_index ); ?>"><?php echo esc_html( $section_index ); ?></span>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="mwm-section-11__sections-wrapper">
					<?php 
					$section_index = 0;
					foreach ( $sections as $section ) : 
						$section_index++;
					?>
						<?php
						// Extraer campos de la sección
						$position_media = isset( $section['position_media'] ) ? $section['position_media'] : 'left';
						
						// Valores por defecto: fondo blanco sin borde y texto azul
						$background_outline = 'white-none';
						$color_text = 'blue';
	
						$show_media = isset( $section['show_media'] ) ? ( $section['show_media'] !== false ) : true;
						$media_type = isset( $section['media_type'] ) ? $section['media_type'] : 'image';
						$media_image = isset( $section['media_image'] ) ? $section['media_image'] : null;
						$media_video = isset( $section['media_video'] ) ? $section['media_video'] : null;
	
						$show_badge = isset( $section['show_badge'] ) ? ( $section['show_badge'] !== false ) : true;
						$badge_text = isset( $section['badge_text'] ) ? $section['badge_text'] : '';
						$badge_url = isset( $section['badge_url'] ) ? $section['badge_url'] : '';
	
						$section_title = isset( $section['title'] ) ? $section['title'] : '';
						$description = isset( $section['description'] ) ? $section['description'] : '';
	
						// Sanitizar variantes
						$allowed_positions = array( 'left', 'right' );
						if ( ! in_array( $position_media, $allowed_positions, true ) ) {
							$position_media = 'left';
						}
	
						$classes = array(
							'mwm-section-01',
							'mwm-section-01--pos-' . esc_attr( $position_media ),
							'mwm-section-01--bg-' . esc_attr( $background_outline ),
							'mwm-section-01--text-' . esc_attr( $color_text ),
						);
						?>
						<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-section-index="<?php echo esc_attr( $section_index ); ?>">
							<div class="mwm-section-01__wrapper">
								<?php if ( $show_media ) : ?>
									<div class="mwm-section-01__media">
										<?php if ( $media_type === 'video' && $media_video ) : ?>
											<video
												class="mwm-section-01__media-video"
												autoplay
												playsinline
												muted
												loop
												preload="metadata"
												data-mwm-video-first-frame-poster
											>
												<source src="<?php echo esc_url( $media_video ); ?>" type="video/mp4">
											</video>
										<?php elseif ( $media_type === 'image' && $media_image ) : ?>
											<?php
											$media_alt = get_post_meta( (int) $media_image, '_wp_attachment_image_alt', true );
											if ( empty( $media_alt ) ) {
												$media_title = get_the_title( (int) $media_image );
												$media_alt = ! empty( $media_title ) ? $media_title : __( 'Imagen', 'bilky' );
											}
	
											echo wp_get_attachment_image(
												(int) $media_image,
												'full',
												false,
												array(
													'class' => 'mwm-section-01__media-image',
													'alt'   => esc_attr( $media_alt ),
												)
											);
											?>
										<?php else : ?>
											<div class="mwm-section-01__media-placeholder" aria-hidden="true"></div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
	
								<div class="mwm-section-01__content">
									<div class="mwm-section-01__header">
										<?php if ( $show_badge && ! empty( $badge_text ) ) : ?>
											<div class="mwm-section-01__badge">
												<?php
												$badge_color = ( 'white' === $color_text ) ? 'terciary' : 'secundary';
	
												$badge_button_args = array(
													'text'    => $badge_text,
													'variant' => 'outline',
													'color'   => $badge_color,
													'size'    => 'xl-md',
												);
	
												// Si no hay URL, renderizar como tag (sin enlace)
												if ( empty( $badge_url ) ) {
													$badge_button_args['is_tag'] = true;
												} else {
													$badge_button_args['url'] = $badge_url;
												}
	
												echo mwm_button( $badge_button_args );
												?>
											</div>
										<?php endif; ?>
	
										<?php if ( ! empty( $section_title ) ) : ?>
											<div class="mwm-section-01__title-row">
												<h2 class="mwm-section-01__title is-style-h-300">
													<?php echo esc_html( $section_title ); ?>
												</h2>
											</div>
										<?php endif; ?>
	
										<?php if ( ! empty( $description ) ) : ?>
											<div class="mwm-section-01__description is-style-b-200">
												<?php echo wp_kses_post( wpautop( $description ) ); ?>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</section>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

