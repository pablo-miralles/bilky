<?php
/**
 * Block Name: MWM Section 14
 */

$show_breadcrumbs = get_field('show_breadcrumbs') !== false;
$breadcrumb_button_text = get_field('breadcrumb_button_text');
$breadcrumb_button_link = get_field('breadcrumb_button_link');
$show_breadcrumb_01 = get_field('show_breadcrumb_01') !== false;
$breadcrumb_01_text = get_field('breadcrumb_01_text');
$title = get_field('title');
$show_text_body = get_field('show_text_body') !== false;
$text_body = get_field('text_body');
$show_btn = get_field('show_btn') !== false;
$btn_text = get_field('btn_text');
$btn_link = get_field('btn_link');

// Campos de media
$media_type = get_field('media_type') ?: 'image';
$video_url = get_field('video_url');
$video_wordpress_url = get_field('video_wordpress_url');
$media_image = get_field('media_image');

// Determinar la imagen a mostrar
$display_image = null;
$image_alt = '';

if ( $media_type === 'video' && $video_url ) {
	// Si es video de YouTube, usar imagen si existe, sino obtener miniatura de YouTube
	if ( $media_image ) {
		$display_image = $media_image;
		$image_alt = get_post_meta( $media_image, '_wp_attachment_image_alt', true );
	} else {
		// Obtener miniatura de YouTube
		$youtube_thumbnail = mwm_get_youtube_thumbnail( $video_url );
		if ( $youtube_thumbnail ) {
			$display_image = $youtube_thumbnail;
		}
	}
	
	// Si no hay alt text, usar el título de la sección
	if ( empty( $image_alt ) && $title ) {
		$image_alt = esc_attr( $title );
	} elseif ( empty( $image_alt ) ) {
		$image_alt = __( 'Imagen de la sección', 'bilky' );
	}
} elseif ( $media_type === 'video-wordpress' && $video_wordpress_url ) {
	// Si es video de WordPress, usar imagen si existe
	if ( $media_image ) {
		$display_image = $media_image;
		$image_alt = get_post_meta( $media_image, '_wp_attachment_image_alt', true );
	}
	
	// Si no hay alt text, usar el título de la sección
	if ( empty( $image_alt ) && $title ) {
		$image_alt = esc_attr( $title );
	} elseif ( empty( $image_alt ) ) {
		$image_alt = __( 'Video de la sección', 'bilky' );
	}
} elseif ( $media_type === 'image' && $media_image ) {
	$display_image = $media_image;
	$image_alt = get_post_meta( $media_image, '_wp_attachment_image_alt', true );
	if ( empty( $image_alt ) ) {
		$image_alt = $title ? esc_attr( $title ) : __( 'Imagen de la sección', 'bilky' );
	}
}

// Preparar URL del video para Fancybox
$fancybox_url = '';
$has_video = false;

if ( $media_type === 'video' && ! empty( $video_url ) ) {
	// Para YouTube, usar la URL original (no embed) según la documentación de Fancybox
	$fancybox_url = esc_url( $video_url );
	$has_video = true;
} elseif ( $media_type === 'video-wordpress' && ! empty( $video_wordpress_url ) ) {
	// URL para Fancybox con video de WordPress
	$fancybox_url = esc_url( $video_wordpress_url );
		$has_video = true;
}

// Determinar si la imagen es una URL externa (YouTube thumbnail) o un ID de WordPress
$is_external_image = ( $media_type === 'video' && ! $media_image && $display_image );

?>

<section class="mwm-section-14">
	<div class="mwm-section-14__wrapper">
		<div class="mwm-section-07">
			<div class="mwm-section-07__wrapper">
				<?php if ( $show_breadcrumbs ) : ?>
					<div class="mwm-section-07__breadcrumbs">
						<?php if ( $breadcrumb_button_text ) : ?>
						<?php
						$button_args = array(
							'text' => esc_html( $breadcrumb_button_text ),
							'variant' => 'outline',
							'color' => 'secundary',
							'size' => 'sm',
						);
						if ( empty( $breadcrumb_button_link ) ) {
							$button_args['is_tag'] = true;
						} else {
							$button_args['url'] = esc_url( $breadcrumb_button_link );
						}
						echo mwm_button( $button_args );
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
		
					<?php if ( $show_text_body && $text_body ) : ?>
						<div class="mwm-section-07__text-body is-style-b-200">
							<?php echo wp_kses_post( wpautop( $text_body ) ); ?>
						</div>
					<?php endif; ?>
		
					<?php if ( $show_btn && $btn_text ) : ?>
						<div class="mwm-section-07__button">
							<?php
							echo mwm_button( array(
								'text' => esc_html( $btn_text ),
								'url' => esc_url( $btn_link ),
								'variant' => 'fill-icon',
								'color' => 'primary',
								'size' => 'md',
							) );
							?>
						</div>
					<?php endif; ?>
	
				</div>
			</div>
		</div>
		<?php if ( $display_image || $has_video ) : ?>
			<div class="mwm-section-14__media">
				<?php if ( $is_external_image ) : ?>
					<img 
						src="<?php echo esc_url( $display_image ); ?>" 
						alt="<?php echo esc_attr( $image_alt ); ?>"
						class="mwm-section-14__media-image"
					/>
				<?php elseif ( $display_image ) : ?>
					<?php 
					echo wp_get_attachment_image( 
						$display_image, 
						'full', 
						false, 
						array( 
							'class' => 'mwm-section-14__media-image',
							'alt' => esc_attr( $image_alt ),
						) 
					); 
					?>
				<?php elseif ( $has_video && ! $display_image && $media_type === 'video-wordpress' && ! empty( $video_wordpress_url ) ) : ?>
					<?php
					// Mostrar el video HTML5 pausado como thumbnail para videos de WordPress
					$poster_url = $media_image ? wp_get_attachment_image_url( $media_image, 'full' ) : '';
					?>
					<video 
						class="mwm-section-14__media-video-thumbnail"
						playsinline
						muted
						preload="metadata"
						<?php if ( $poster_url ) : ?>
							poster="<?php echo esc_url( $poster_url ); ?>"
						<?php endif; ?>
					>
						<source src="<?php echo esc_url( $video_wordpress_url ); ?>" type="video/mp4">
					</video>
				<?php elseif ( $has_video && ! $display_image ) : ?>
					<div class="mwm-section-14__media-placeholder" aria-hidden="true"></div>
				<?php endif; ?>
				
				<?php if ( $has_video && ! empty( $fancybox_url ) ) : ?>
					<div class="mwm-section-14__media-button-wrapper">
						<?php
						// Según la documentación de Fancybox, usar href directamente para ambos tipos
						// Fancybox detecta automáticamente si es YouTube o video HTML5
						echo mwm_button( array(
							'icon' => 'play',
							'url' => esc_url( $fancybox_url ),
							'variant' => 'fill-icon',
							'color' => 'secundary',
							'size' => 'xl',
							'attributes' => array(
								'data-fancybox' => 'video',
							),
						) );
						?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
