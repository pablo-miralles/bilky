<?php
/**
 * Block Name: MWM Section 03
 */

$background = get_field('background') ?: 'transparent';
$position_media = get_field('position_media') ?: 'left';
$media_type = get_field('media_type') ?: 'image';
$video = get_field('video');
$image = get_field('image');
$show_icon = get_field('show_icon') !== false;
$icon = get_field('icon') ?: 'briefcase';
$title = get_field('title');
$show_text = get_field('show_text') !== false;
$text = get_field('text');
$stats = get_field('stats');

$background_class = 'mwm-section-03--background-' . esc_attr( $background );
$position_class = 'mwm-section-03--media-' . esc_attr( $position_media );

// Limitar a 4 estadísticas máximo
if ( $stats && count( $stats ) > 4 ) {
	$stats = array_slice( $stats, 0, 4 );
}
?>

<section class="mwm-section-03 <?php echo esc_attr( $background_class ); ?> <?php echo esc_attr( $position_class ); ?>">
	<div class="mwm-section-03__wrapper">
		<?php if ( $position_media === 'left' && ( $video || $image ) ) : ?>
			<div class="mwm-section-03__media">
				<?php if ( $media_type === 'video' && $video ) : ?>
					<video 
						class="mwm-section-03__video" 
						autoplay 
						loop 
						playsinline 
						controlsList="nodownload"
					>
						<source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
					</video>
				<?php elseif ( $media_type === 'image' && $image ) : 
					$image_alt = get_post_meta( $image, '_wp_attachment_image_alt', true );
					if ( empty( $image_alt ) ) {
						$image_title = get_the_title( $image );
						$image_alt = ! empty( $image_title ) ? $image_title : __( 'Imagen de la sección', 'bilky' );
					}
				?>
					<?php 
					echo wp_get_attachment_image( 
						$image, 
						'full', 
						false, 
						array( 
							'class' => 'mwm-section-03__image',
							'alt' => esc_attr( $image_alt ),
						) 
					); 
					?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="mwm-section-03__content">
			<div class="mwm-section-03__header">
				<?php if ( $show_icon && $icon ) : ?>
					<div class="mwm-section-03__icon">
						<i class="fa-solid fa-<?php echo esc_attr( $icon ); ?>"></i>
					</div>
				<?php endif; ?>
				<?php if ( $title ) : ?>
					<div class="mwm-section-03__title-wrapper">
						<h2 class="mwm-section-03__title is-style-h-300">
							<?php 
							// Dividir el título en líneas si hay saltos de línea
							$title_lines = explode( "\n", $title );
							foreach ( $title_lines as $index => $line ) {
								$line = trim( $line );
								if ( ! empty( $line ) ) {
									echo '<span class="mwm-section-03__title-line">' . esc_html( $line ) . '</span>';
									if ( $index < count( $title_lines ) - 1 ) {
										echo ' ';
									}
								}
							}
							?>
						</h2>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $show_text && $text ) : ?>
				<div class="mwm-section-03__text is-style-b-200">
					<?php echo wp_kses_post( wpautop( $text ) ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $stats && ! empty( $stats ) ) : ?>
				<div class="mwm-section-03__stats">
					<?php 
					$stat_count = 0;
					foreach ( $stats as $stat ) : 
						$stat_count++;
						$percentage = $stat['percentage'];
						$stat_description = $stat['description'];
						$stat_color = $stat['color'] ?: 'grey';
						
						// Si es la última y no tiene color definido, usar verde
						if ( $stat_count === count( $stats ) && $stat_color === 'grey' ) {
							$stat_color = 'green';
						}
						
						$color_class = 'mwm-section-03__stat--' . esc_attr( $stat_color );
					?>
						<div class="mwm-section-03__stat <?php echo esc_attr( $color_class ); ?>">
							<div class="mwm-section-03__stat-content">
								<?php if ( $percentage ) : ?>
									<h3 class="mwm-section-03__stat-percentage is-style-d-100">
										<?php echo esc_html( $percentage ); ?>
									</h3>
								<?php endif; ?>
								<?php if ( $stat_description ) : ?>
									<div class="mwm-section-03__stat-description is-style-b-300">
										<?php 
										// Dividir la descripción en líneas si hay saltos de línea
										$desc_lines = explode( "\n", $stat_description );
										foreach ( $desc_lines as $index => $line ) {
											$line = trim( $line );
											if ( ! empty( $line ) ) {
												echo '<p class="mwm-section-03__stat-description-line">' . esc_html( $line ) . '</p>';
											}
										}
										?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $position_media === 'right' && ( $video || $image ) ) : ?>
			<div class="mwm-section-03__media">
				<?php if ( $media_type === 'video' && $video ) : ?>
					<video 
						class="mwm-section-03__video" 
						autoplay 
						loop 
						playsinline 
						controlsList="nodownload"
					>
						<source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
					</video>
				<?php elseif ( $media_type === 'image' && $image ) : 
					$image_alt = get_post_meta( $image, '_wp_attachment_image_alt', true );
					if ( empty( $image_alt ) ) {
						$image_title = get_the_title( $image );
						$image_alt = ! empty( $image_title ) ? $image_title : __( 'Imagen de la sección', 'bilky' );
					}
				?>
					<?php 
					echo wp_get_attachment_image( 
						$image, 
						'full', 
						false, 
						array( 
							'class' => 'mwm-section-03__image',
							'alt' => esc_attr( $image_alt ),
						) 
					); 
					?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

