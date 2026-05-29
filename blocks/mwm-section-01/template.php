<?php
/**
 * Block Name: MWM Section 01
 */

$position_media = get_field( 'position_media' ) ?: 'left';
$background_outline = get_field( 'background_outline' ) ?: 'none-none';
$color_text = get_field( 'color_text' ) ?: 'blue';

$show_media = get_field( 'show_media' ) !== false;
$media_type = get_field( 'media_type' ) ?: 'image';
$media_image = get_field( 'media_image' );
$media_video = get_field( 'media_video' );

$show_badge = get_field( 'show_badge' ) !== false;
$badge_text = get_field( 'badge_text' );
$badge_url = get_field( 'badge_url' );

$show_icon = get_field( 'show_icon' ) !== false;
$icon = get_field( 'icon' );
$icon_color = get_field( 'icon_color' ) ?: 'green';

$title = get_field( 'title' );
$description = get_field( 'description' );
$show_description_opacity = get_field( 'show_description_opacity' ) !== false;

$show_list = get_field( 'show_list' ) !== false;
$list_style = get_field( 'list_style' ) ?: 'auto';
$list_icon_color = get_field( 'list_icon_color' ) ?: 'green';
$list_items = get_field( 'list_items' );

$show_button = get_field( 'show_button' ) !== false;
$button_text = get_field( 'button_text' );
$button_url = get_field( 'button_url' );

// Sanitizar variantes.
$allowed_positions = array( 'left', 'right', 'center-top', 'center-bottom' );
$allowed_backgrounds = array( 'blue-none', 'grey', 'none-blue', 'white-none', 'none-none', 'border-grey' );
$allowed_text_colors = array( 'blue', 'white' );

if ( ! in_array( $position_media, $allowed_positions, true ) ) {
	$position_media = 'left';
}

if ( ! in_array( $background_outline, $allowed_backgrounds, true ) ) {
	$background_outline = 'none-none';
}

if ( ! in_array( $color_text, $allowed_text_colors, true ) ) {
	$color_text = 'blue';
}

// Resolver estilo de lista (auto -> según variantes del componente).
$allowed_list_styles = array( 'auto', 'grey-blue', 'white-blue', 'none' );
if ( ! in_array( $list_style, $allowed_list_styles, true ) ) {
	$list_style = 'auto';
}

if ( 'auto' === $list_style ) {
	if ( 'center-top' === $position_media || 'center-bottom' === $position_media ) {
		$list_style = ( 'white' === $color_text ) ? 'none' : 'grey-blue';
	} else {
		$list_style = ( 'blue-none' === $background_outline ) ? 'white-blue' : 'grey-blue';
	}
}

$classes = array(
	'mwm-section-01',
	'mwm-section-01--pos-' . $position_media,
	'mwm-section-01--bg-' . $background_outline,
	'mwm-section-01--text-' . $color_text,
);
?>

<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
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
							'alt' => esc_attr( $media_alt ),
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

						echo mwm_button(
							array(
								'text' => $badge_text,
								'url' => $badge_url,
								'variant' => 'outline',
								'color' => $badge_color,
								'size' => 'xl-md',
							)
						);
						?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $title ) ) : ?>
					<div class="mwm-section-01__title-row">
						<?php if ( $show_icon ) : ?>
							<?php
							// Sanitizar color del icono
							$allowed_icon_colors = array( 'green', 'grey' );
							if ( ! in_array( $icon_color, $allowed_icon_colors, true ) ) {
								$icon_color = 'green';
							}
							
							// Construir clases del icono
							$icon_classes = array(
								'mwm-section-01__title-icon',
								'mwm-section-01__title-icon--' . esc_attr( $icon_color ),
							);
							?>
							<div class="<?php echo esc_attr( implode( ' ', $icon_classes ) ); ?>" aria-hidden="true">
								<i class="fa-solid fa-<?php echo esc_attr( sanitize_text_field( $icon ?: 'briefcase' ) ); ?>"></i>
							</div>
						<?php endif; ?>

						<h2 class="mwm-section-01__title is-style-h-300">
							<?php echo esc_html( $title ); ?>
						</h2>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $description ) ) : ?>
					<div class="mwm-section-01__description is-style-b-200 <?php echo esc_attr( $show_description_opacity ? 'is-style-desc-opacity' : '' ); ?>">
						<?php echo wp_kses_post( wpautop( $description ) ); ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $show_list && ! empty( $list_items ) && is_array( $list_items ) ) : ?>
				<?php
				// Sanitizar color del icono de la lista
				$allowed_icon_colors = array( 'green', 'blue' );
				if ( ! in_array( $list_icon_color, $allowed_icon_colors, true ) ) {
					$list_icon_color = 'green';
				}

				// Clase de la lista según el color del icono
				$list_classes = array(
					'mwm-section-01__list',
					'mwm-section-01__list--' . esc_attr( $list_style ),
					'mwm-section-01__list--icon-' . esc_attr( $list_icon_color ),
				);
				?>
				<ul class="<?php echo esc_attr( implode( ' ', $list_classes ) ); ?>">
					<?php foreach ( $list_items as $item ) : ?>
						<?php
						$item_text = isset( $item['text'] ) ? $item['text'] : '';
						$item_icon = isset( $item['icon'] ) ? $item['icon'] : 'circle-check';

						if ( empty( $item_text ) ) {
							continue;
						}
						?>
						<li class="mwm-section-01__list-item">
							<div class="mwm-section-01__list-item-inner">
								<span class="mwm-section-01__list-check" aria-hidden="true">
									<i class="fa-solid fa-<?php echo esc_attr( sanitize_text_field( $item_icon ) ); ?>"></i>
								</span>

								<h3 class="mwm-section-01__list-text is-style-b-200">
									<?php echo ( $item_text ); ?>
								</h3>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php if ( $show_button && ! empty( $button_text ) ) : ?>
				<div class="mwm-section-01__button">
					<?php
					// Color del botón inferior según el fondo (Figma).
					if ( 'white-none' === $background_outline || 'grey' === $background_outline ) {
						$bottom_button_color = 'secundary';
					} elseif ( 'blue-none' === $background_outline ) {
						$bottom_button_color = 'primary';
					} else {
						$bottom_button_color = 'terciary';
					}

					echo mwm_button(
						array(
							'text' => $button_text,
							'url' => $button_url,
							'variant' => 'fill',
							'color' => $bottom_button_color,
							'size' => 'sm',
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

