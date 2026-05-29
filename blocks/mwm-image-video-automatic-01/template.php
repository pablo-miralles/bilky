<?php
/**
 * Block Name: MWM Image Video Automatic 01
 */

$media_type          = get_field( 'media_type' ) ?: 'image';
$video_wordpress_url = get_field( 'video_wordpress_url' );
$image               = get_field( 'image' );

// Si no hay contenido, no mostrar nada
if ( $media_type === 'video-wordpress' && empty( $video_wordpress_url ) && ! $image ) {
	return;
}

if ( $media_type === 'image' && ! $image ) {
	return;
}

// Obtener alt de la imagen si existe
$image_alt = '';
if ( $image ) {
	$image_alt = get_post_meta( $image, '_wp_attachment_image_alt', true );
	if ( empty( $image_alt ) ) {
		$image_title = get_the_title( $image );
		$image_alt   = ! empty( $image_title ) ? $image_title : __( 'Imagen', 'bilky' );
	}
} elseif ( $media_type === 'video-wordpress' ) {
	$image_alt = __( 'Video', 'bilky' );
}

$poster_url = $image ? wp_get_attachment_image_url( $image, 'full' ) : '';
?>

<div class="mwm-image-video-automatic-01">
	<div class="mwm-image-video-automatic-01__wrapper">
		<div class="mwm-image-video-automatic-01__media">
			<?php if ( $media_type === 'video-wordpress' && ! empty( $video_wordpress_url ) ) : ?>
				<video
					class="mwm-image-video-automatic-01__video"
					autoplay
					playsinline
					muted
					loop
					preload="auto"
					<?php if ( $poster_url ) : ?>
						poster="<?php echo esc_url( $poster_url ); ?>"
					<?php endif; ?>
				>
					<source src="<?php echo esc_url( $video_wordpress_url ); ?>" type="video/mp4">
				</video>
			<?php elseif ( $image ) : ?>
				<?php
				echo wp_get_attachment_image(
					$image,
					'full',
					false,
					array(
						'class' => 'mwm-image-video-automatic-01__image',
						'alt'   => esc_attr( $image_alt ),
					)
				);
				?>
			<?php else : ?>
				<div class="mwm-image-video-automatic-01__media-placeholder" aria-hidden="true"></div>
			<?php endif; ?>
		</div>
	</div>
</div>

