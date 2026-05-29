<?php
/**
 * Block Name: MWM Card 01 Group
 */

$cards = get_field( 'cards' );

if ( ! $cards || empty( $cards ) ) {
	return;
}
?>

<section class="mwm-card-01-group">
	<div class="mwm-card-01-group__wrapper">
		<?php
		foreach ( $cards as $card ) :
			$page_id = isset( $card['page'] ) ? intval( $card['page'] ) : 0;
			
			if ( ! $page_id ) {
				continue;
			}

			// Obtener datos de la página
			$page = get_post( $page_id );
			if ( ! $page ) {
				continue;
			}

			// Obtener campos ACF de la página (valores por defecto)
			$page_video            = get_field( 'page_video', $page_id );
			$page_icon             = get_field( 'page_icon', $page_id ) ?: 'briefcase';
			$page_alternative_title = get_field( 'page_alternative_title', $page_id );
			
			// Obtener título de la página (valor por defecto)
			$title = get_the_title( $page_id );
			$title_alternative = get_field( 'page_alternative_title', $page_id );

			// Obtener extracto de la página (valor por defecto)
			$excerpt = get_the_excerpt( $page_id );
			
			// Imagen destacada: para sustituir al video si no hay video, y como poster del <video> si lo hay.
			$featured_image_id = get_post_thumbnail_id( $page_id );
			if ( $page_video ) {
				$featured_image_id = null;
			}

			// Sobrescribir con campos personalizados si existen
			// Título
			if ( isset( $card['override_title'] ) && $card['override_title'] && ! empty( $card['custom_title'] ) ) {
				$title = $card['custom_title'];
			}
			
			// Título alternativo
			if ( isset( $card['override_alternative_title'] ) && $card['override_alternative_title'] && ! empty( $card['custom_alternative_title'] ) ) {
				$title_alternative = $card['custom_alternative_title'];
			}
			
			// Icono
			if ( isset( $card['override_icon'] ) && $card['override_icon'] && ! empty( $card['custom_icon'] ) ) {
				$page_icon = $card['custom_icon'];
			}
			
			// Media (video o imagen)
			if ( isset( $card['override_media'] ) && $card['override_media'] ) {
				$custom_media_type = isset( $card['custom_media_type'] ) ? $card['custom_media_type'] : 'image';
				
				if ( 'video' === $custom_media_type && ! empty( $card['custom_video'] ) ) {
					$page_video        = $card['custom_video'];
					$featured_image_id = null;
				} elseif ( 'image' === $custom_media_type && ! empty( $card['custom_image'] ) ) {
					// Si hay imagen personalizada, no usar video ni imagen destacada
					$page_video = null;
					$featured_image_id = intval( $card['custom_image'] );
				}
			}
			
			// Extracto
			if ( isset( $card['override_excerpt'] ) && $card['override_excerpt'] && ! empty( $card['custom_excerpt'] ) ) {
				$excerpt = $card['custom_excerpt'];
			}
			
			// Texto del botón (del repeater o por defecto)
			$button_text = isset( $card['button_text'] ) && ! empty( $card['button_text'] ) 
				? $card['button_text'] 
				: __( 'Ver más', 'bilky' );
			
			// URL del botón (URL de la página)
			$button_url = get_permalink( $page_id );
			?>
			<article class="mwm-card-01">
				<a href="<?php echo esc_url( $button_url ); ?>" title="<?php echo esc_attr( $title ); ?>"></a>
				<div class="mwm-card-01__media">
					<?php if ( $page_video ) : ?>
						<video
							class="mwm-card-01__video"
							width="1920"
							height="1080"
							autoplay
							loop
							playsinline
							muted
							preload="metadata"
							controlsList="nodownload"
							data-mwm-video-first-frame-poster
						>
							<source src="<?php echo esc_url( $page_video ); ?>" type="video/mp4">
						</video>
					<?php elseif ( $featured_image_id ) : ?>
						<?php
						$image_alt = get_post_meta( $featured_image_id, '_wp_attachment_image_alt', true );
						if ( empty( $image_alt ) ) {
							$image_alt = $title ? $title : __( 'Card image', 'bilky' );
						}
						$image_attrs = array(
							'class'    => 'mwm-card-01__image',
							'alt'      => $image_alt,
							'decoding' => 'async',
						);
						$img_w = 0;
						$img_h = 0;
						$img_src = wp_get_attachment_image_src( $featured_image_id, 'full' );
						if ( $img_src && isset( $img_src[1], $img_src[2] ) ) {
							$img_w = (int) $img_src[1];
							$img_h = (int) $img_src[2];
						}
						// Reserva de ratio para CLS: fallback si "full" no devuelve dimensiones (metadatos rotos o pendientes).
						if ( ( $img_w <= 0 || $img_h <= 0 ) && $featured_image_id ) {
							$meta = wp_get_attachment_metadata( $featured_image_id );
							if ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
								$img_w = (int) $meta['width'];
								$img_h = (int) $meta['height'];
							}
						}
						if ( $img_w > 0 && $img_h > 0 ) {
							$image_attrs['width']  = $img_w;
							$image_attrs['height'] = $img_h;
						}
						echo wp_get_attachment_image(
							$featured_image_id,
							'full',
							false,
							$image_attrs
						);
						?>
					<?php endif; ?>
				</div>
				<div class="mwm-card-01__footer">
					<div class="mwm-card-01__footer-wrapper">						
						<div class="mwm-card-01__info">
							<?php if ( $page_icon ) : ?>
								<div class="mwm-card-01__icon">
									<i class="fa-solid fa-<?php echo esc_attr( $page_icon ); ?>"></i>
								</div>
							<?php endif; ?>
							<?php if ( $title ) : ?>
								<h3 class="mwm-card-01__title is-style-h-400">
									<span class="mwm-card-01__title-main"><?php echo esc_html( $title ); ?></span>
									<span class="mwm-card-01__title-alternative"><?php echo esc_html( $title_alternative ?: $title ); ?></span>
								</h3>
							<?php endif; ?>
						</div>
						<?php if ( $button_text ) : ?>
							<div class="mwm-card-01__button">
								<?php
								echo mwm_button(
									array(
										'text'    => esc_html( $button_text ),
										'url'     => esc_url( $button_url ),
										'variant' => 'fill',
										'color'   => 'terciary',
										'size'    => 'xl-md',
									)
								);
								?>
							</div>
						<?php endif; ?>
					</div>
					<?php if ( $excerpt ) : ?>
						<div class="mwm-card-01__excerpt">
							<?php echo wp_kses_post( wpautop( $excerpt ) ); ?>
						</div>
					<?php endif; ?>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>