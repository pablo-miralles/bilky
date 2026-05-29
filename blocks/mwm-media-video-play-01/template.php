<?php
/**
 * Block Name: MWM Media Video Play 01
 */

$media_type = get_field('media_type') ?: 'video';
$video_url = get_field('video_url');
$video_wordpress_url = get_field('video_wordpress_url');
$image = get_field('image');
$button_text = get_field('button_text');

// Determinar la imagen a mostrar
$display_image = null;
$image_alt = '';

if ( $media_type === 'video' && $video_url ) {
	// Si es video de YouTube, usar imagen si existe, sino obtener miniatura de YouTube
	if ( $image ) {
		$display_image = $image;
		$image_alt = get_post_meta( $image, '_wp_attachment_image_alt', true );
	} else {
		// Obtener miniatura de YouTube
		$youtube_thumbnail = mwm_get_youtube_thumbnail( $video_url );
		if ( $youtube_thumbnail ) {
			$display_image = $youtube_thumbnail;
		}
	}
} elseif ( $media_type === 'video-wordpress' && $video_wordpress_url ) {
	// Si es video de WordPress, usar imagen si existe
	if ( $image ) {
		$display_image = $image;
		$image_alt = get_post_meta( $image, '_wp_attachment_image_alt', true );
	}
} elseif ( $media_type === 'image' && $image ) {
	$display_image = $image;
	$image_alt = get_post_meta( $image, '_wp_attachment_image_alt', true );
	if ( empty( $image_alt ) ) {
		$button_text = get_field('button_text');
		$image_alt = esc_attr( $button_text );
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

// Si no hay imagen ni video, no mostrar nada
if ( ! $display_image && ! $has_video ) {
	return;
}

// Si hay video de WordPress pero no imagen, establecer alt por defecto
if ( $media_type === 'video-wordpress' && $has_video && ! $display_image ) {
	if ( empty( $image_alt ) ) {
		$image_alt = __( 'Video', 'bilky' );
	}
}

// Determinar si la imagen es una URL externa (YouTube thumbnail) o un ID de WordPress
$is_external_image = ( $media_type === 'video' && ! $image && $display_image );
?>

<div class="mwm-media-video-play-01">
	<div class="mwm-media-video-play-01__wrapper">
		<div class="mwm-media-video-play-01__media">
			<?php if ( $is_external_image ) : ?>
				<img 
					src="<?php echo esc_url( $display_image ); ?>" 
					alt="<?php echo esc_attr( $image_alt ); ?>"
					class="mwm-media-video-play-01__image"
				/>
			<?php elseif ( $display_image ) : ?>
				<?php 
				echo wp_get_attachment_image( 
					$display_image, 
					'full', 
					false, 
					array( 
						'class' => 'mwm-media-video-play-01__image',
						'alt' => esc_attr( $image_alt ),
					) 
				); 
				?>
			<?php elseif ( $has_video && ! $display_image && $media_type === 'video-wordpress' && ! empty( $video_wordpress_url ) ) : ?>
				<?php
				// Mostrar el video HTML5 pausado como thumbnail para videos de WordPress
				$poster_url = $image ? wp_get_attachment_image_url( $image, 'full' ) : '';
				?>
				<video 
					class="mwm-media-video-play-01__video-thumbnail"
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
				<?php
				// Placeholder para videos sin imagen - el CSS puede manejar el fondo
				?>
				<div class="mwm-media-video-play-01__media-placeholder" aria-hidden="true"></div>
			<?php endif; ?>
		</div>
		
		<?php if ( $has_video && ! empty( $fancybox_url ) ) : ?>
			<div class="mwm-media-video-play-01__button-wrapper">
				<?php
				// Según la documentación de Fancybox, usar href directamente para ambos tipos
				// Fancybox detecta automáticamente si es YouTube o video HTML5
				echo mwm_button( array(
					'icon' => 'play',
					'url' => esc_url( $fancybox_url ),
					'text' => empty($button_text) ? '' : $button_text,
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
</div>

