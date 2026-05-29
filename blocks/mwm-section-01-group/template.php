<?php
/**
 * Block Name: MWM Section 01 Group
 */

/**
 * Renderiza una sección individual mwm-section-01
 *
 * @param array $fields Array con los campos de la sección (con prefijo section_fields_ o sin prefijo).
 * @return void
 */
if ( ! function_exists( 'mwm_render_section_01_from_fields' ) ) {
	function mwm_render_section_01_from_fields( $fields ) {
	// Extraer campos con o sin prefijo
	$position_media = isset( $fields['section_fields_position_media'] ) ? $fields['section_fields_position_media'] : ( isset( $fields['position_media'] ) ? $fields['position_media'] : 'left' );
	$background_outline = isset( $fields['section_fields_background_outline'] ) ? $fields['section_fields_background_outline'] : ( isset( $fields['background_outline'] ) ? $fields['background_outline'] : 'none-none' );
	$color_text = isset( $fields['section_fields_color_text'] ) ? $fields['section_fields_color_text'] : ( isset( $fields['color_text'] ) ? $fields['color_text'] : 'blue' );

	$show_media = isset( $fields['section_fields_show_media'] ) ? ( $fields['section_fields_show_media'] !== false ) : ( isset( $fields['show_media'] ) ? ( $fields['show_media'] !== false ) : true );
	$media_type = isset( $fields['section_fields_media_type'] ) ? $fields['section_fields_media_type'] : ( isset( $fields['media_type'] ) ? $fields['media_type'] : 'image' );
	$media_image = isset( $fields['section_fields_media_image'] ) ? $fields['section_fields_media_image'] : ( isset( $fields['media_image'] ) ? $fields['media_image'] : null );
	$media_video = isset( $fields['section_fields_media_video'] ) ? $fields['section_fields_media_video'] : ( isset( $fields['media_video'] ) ? $fields['media_video'] : null );

	$show_badge = isset( $fields['section_fields_show_badge'] ) ? ( $fields['section_fields_show_badge'] !== false ) : ( isset( $fields['show_badge'] ) ? ( $fields['show_badge'] !== false ) : true );
	$badge_text = isset( $fields['section_fields_badge_text'] ) ? $fields['section_fields_badge_text'] : ( isset( $fields['badge_text'] ) ? $fields['badge_text'] : '' );
	$badge_url = isset( $fields['section_fields_badge_url'] ) ? $fields['section_fields_badge_url'] : ( isset( $fields['badge_url'] ) ? $fields['badge_url'] : '' );

	$show_icon = isset( $fields['section_fields_show_icon'] ) ? ( $fields['section_fields_show_icon'] !== false ) : ( isset( $fields['show_icon'] ) ? ( $fields['show_icon'] !== false ) : true );
	$icon = isset( $fields['section_fields_icon'] ) ? $fields['section_fields_icon'] : ( isset( $fields['icon'] ) ? $fields['icon'] : '' );

	$title = isset( $fields['section_fields_title'] ) ? $fields['section_fields_title'] : ( isset( $fields['title'] ) ? $fields['title'] : '' );
	$description = isset( $fields['section_fields_description'] ) ? $fields['section_fields_description'] : ( isset( $fields['description'] ) ? $fields['description'] : '' );
	$show_description_opacity = isset( $fields['section_fields_show_description_opacity'] ) ? ( $fields['section_fields_show_description_opacity'] !== false ) : ( isset( $fields['show_description_opacity'] ) ? ( $fields['show_description_opacity'] !== false ) : false );
	$show_list = isset( $fields['section_fields_show_list'] ) ? ( $fields['section_fields_show_list'] !== false ) : ( isset( $fields['show_list'] ) ? ( $fields['show_list'] !== false ) : true );
	$list_style = isset( $fields['section_fields_list_style'] ) ? $fields['section_fields_list_style'] : ( isset( $fields['list_style'] ) ? $fields['list_style'] : 'auto' );
	$list_icon_color = isset( $fields['section_fields_list_icon_color'] ) ? $fields['section_fields_list_icon_color'] : ( isset( $fields['list_icon_color'] ) ? $fields['list_icon_color'] : 'green' );
	$list_items = isset( $fields['section_fields_list_items'] ) ? $fields['section_fields_list_items'] : ( isset( $fields['list_items'] ) ? $fields['list_items'] : array() );

	$show_button = isset( $fields['section_fields_show_button'] ) ? ( $fields['section_fields_show_button'] !== false ) : ( isset( $fields['show_button'] ) ? ( $fields['show_button'] !== false ) : true );
	$button_text = isset( $fields['section_fields_button_text'] ) ? $fields['section_fields_button_text'] : ( isset( $fields['button_text'] ) ? $fields['button_text'] : '' );
	$button_url = isset( $fields['section_fields_button_url'] ) ? $fields['section_fields_button_url'] : ( isset( $fields['button_url'] ) ? $fields['button_url'] : '' );

	// Sanitizar variantes.
	$allowed_positions = array( 'left', 'right', 'center-top', 'center-button' );
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
	$allowed_list_styles = array( 'auto', 'grey-blue', 'white-blue', 'none-blue', 'none-white' );
	if ( ! in_array( $list_style, $allowed_list_styles, true ) ) {
		$list_style = 'auto';
	}

	if ( 'auto' === $list_style ) {
		if ( 'center-top' === $position_media || 'center-button' === $position_media ) {
			$list_style = ( 'white' === $color_text ) ? 'none-white' : 'none-blue';
		} else {
			$list_style = ( 'blue-none' === $background_outline ) ? 'white-blue' : 'grey-blue';
		}
	}

	$classes = array(
		'mwm-section-01',
		'mwm-section-01--pos-' . esc_attr( $position_media ),
		'mwm-section-01--bg-' . esc_attr( $background_outline ),
		'mwm-section-01--text-' . esc_attr( $color_text ),
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
								<div class="mwm-section-01__title-icon" aria-hidden="true">
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

									<span class="mwm-section-01__list-text is-style-b-200">
										<?php echo esc_html( $item_text ); ?>
									</span>
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
	<?php
	}
} // End if function_exists

// Obtener secciones del repeater
$sections = get_field( 'sections' );

if ( ! $sections || empty( $sections ) ) {
	return;
}
?>

<div class="mwm-section-01-group">
	<div class="mwm-section-01-group__wrapper">
		<?php foreach ( $sections as $section ) : ?>
			<?php
			// Los campos clonados con prefix_name: 1 se almacenan con el prefijo 'section_fields_'
			// directamente en el array del repeater
			// La función helper maneja ambos casos (con y sin prefijo)
			$section_fields = $section;
			
			// Renderizar la sección
			mwm_render_section_01_from_fields( $section_fields );
			?>
		<?php endforeach; ?>
	</div>
</div>

