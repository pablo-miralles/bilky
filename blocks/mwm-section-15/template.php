<?php
/**
 * Block Name: MWM Section 15
 */

$breadcrumb_button_text  = get_field( 'breadcrumb_button_text' );
$title                   = get_field( 'title' );
$text_body               = get_field( 'text_body' );
$list_icon_color         = get_field( 'list_icon_color' ) ?: 'green';
$list_items              = get_field( 'list_items' );
$avatars                 = get_field( 'avatars' );
$users_text              = get_field( 'users_text' );
$form_shortcode          = get_field( 'form_shortcode' );

// Sanitizar color del icono de la lista
$allowed_icon_colors = array( 'green', 'blue' );
if ( ! in_array( $list_icon_color, $allowed_icon_colors, true ) ) {
	$list_icon_color = 'green';
}
?>

<section class="mwm-section-15">
	<div class="mwm-section-15__wrapper">
		<div class="mwm-section-07">
			<div class="mwm-section-07__wrapper">
				<?php if ( $breadcrumb_button_text ) : ?>
					<div class="mwm-section-07__breadcrumbs">
						<?php
						echo mwm_button(
							array(
								'text'    => esc_html( $breadcrumb_button_text ),
								'variant' => 'outline',
								'color'   => 'secundary',
								'size'    => 'sm',
								'is_tag'  => true,
							)
						);
						?>
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
							<?php echo wp_kses_post( wpautop( $text_body ) ); ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $list_items ) && is_array( $list_items ) ) : ?>
						<?php
						// Clase de la lista según el color del icono
						$list_classes = array(
							'mwm-section-15__list',
							'mwm-section-15__list--icon-' . esc_attr( $list_icon_color ),
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
								<li class="mwm-section-15__list-item">
									<div class="mwm-section-15__list-item-inner">
										<span class="mwm-section-15__list-check" aria-hidden="true">
											<i class="fa-solid fa-<?php echo esc_attr( sanitize_text_field( $item_icon ) ); ?>"></i>
										</span>

										<span class="mwm-section-15__list-text is-style-b-200">
											<?php echo ( $item_text ); ?>
										</span>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( $avatars || $users_text ) : ?>
						<div class="mwm-section-07__avatars-section">
							<?php if ( $avatars ) : ?>
								<div class="mwm-section-07__avatars">
									<?php
									$avatar_count = 0;
									foreach ( $avatars as $avatar ) :
										$avatar_count++;
										$avatar_image_id = $avatar['image'];
										if ( $avatar_image_id ) :
											$avatar_image_alt = get_post_meta( $avatar_image_id, '_wp_attachment_image_alt', true );
											if ( empty( $avatar_image_alt ) ) {
												$avatar_image_alt = __( 'Avatar de usuario', 'bilky' );
											}
											$avatar_class = 'mwm-section-07__avatar';
											if ( $avatar_count === 4 ) {
												$avatar_class .= ' mwm-section-07__avatar--fourth';
											}
											?>
											<div class="<?php echo esc_attr( $avatar_class ); ?>">
												<?php echo wp_get_attachment_image( $avatar_image_id, 'thumbnail', false, array( 'alt' => esc_attr( $avatar_image_alt ), 'class' => 'mwm-section-07__avatar-image' ) ); ?>
											</div>
											<?php
										endif;
									endforeach;
									?>
								</div>
							<?php endif; ?>
							<?php if ( $users_text ) : ?>
								<div class="mwm-section-07__users-text">
									<?php echo wp_kses_post( wpautop( $users_text ) ); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php if ( $form_shortcode ) : ?>
			<div class="mwm-section-15__form">
				<?php echo do_shortcode( $form_shortcode ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>

