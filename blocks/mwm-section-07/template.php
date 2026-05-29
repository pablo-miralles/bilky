<?php
/**
 * Block Name: MWM Section 07
 */

$variant = get_field('variant') ?: 'center';
$background = get_field('background') ?: 'transparent';
$show_breadcrumbs = get_field('show_breadcrumbs') !== false;
$breadcrumb_button_text = get_field('breadcrumb_button_text');
$breadcrumb_button_link = get_field('breadcrumb_button_link');
$show_breadcrumb_01 = get_field('show_breadcrumb_01') !== false;
$breadcrumb_01_text = get_field('breadcrumb_01_text');
$show_breadcrumb_02 = get_field('show_breadcrumb_02') !== false;
$breadcrumb_02_text = get_field('breadcrumb_02_text');
$title = get_field('title');
$show_text_body = get_field('show_text_body') !== false;
$text_body = get_field('text_body');
$show_btn = get_field('show_btn') !== false;
$btn_text = get_field('btn_text');
$btn_link = get_field('btn_link');
$show_btn_register = get_field('show_btn_register') !== false;
$btn_register_text = get_field('btn_register_text');
$btn_register_link = get_field('btn_register_link');
$show_avatar_text = get_field('show_avatar_text') !== false;
$show_avatar = get_field('show_avatar') !== false;
$avatars = get_field('avatars');
$users_text = get_field('users_text');

$variant_class = 'mwm-section-07--' . esc_attr( $variant );
$background_class = 'mwm-section-07--background-' . esc_attr( $background );
$classes = array( 'mwm-section-07', $variant_class, $background_class );
?>

<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="mwm-section-07__wrapper">
		<?php if ( $show_breadcrumbs ) : ?>
			<div class="mwm-section-07__breadcrumbs">
				<?php if ( $breadcrumb_button_text ) : ?>
				<?php
				$button_color = ( $background === 'transparent' ) ? 'terciary' : 'secundary';
				$button_args = array(
					'text' => esc_html( $breadcrumb_button_text ),
					'variant' => 'outline',
					'color' => $button_color,
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

				<?php if ( $show_breadcrumb_02 && $breadcrumb_02_text ) : ?>
					<div class="mwm-section-07__breadcrumb-item">
						<span class="mwm-section-07__breadcrumb-separator">|</span>
						<span class="mwm-section-07__breadcrumb-text">
							<?php echo esc_html( $breadcrumb_02_text ); ?>
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
						'size' => 'xl',
					) );
					?>
					<?php if ( $show_btn_register && $btn_register_text ) : ?>
						<?php echo mwm_button( array(
							'text' => esc_html( $btn_register_text ),
							'url' => esc_url( $btn_register_link ),
							'variant' => 'fill-icon',
							'color' => 'secundary',
							'size' => 'xl',
							'icon' => 'user',
						) ); ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $show_avatar_text ) : ?>
				<div class="mwm-section-07__avatars-section">
					<?php if ( $show_avatar && $avatars ) : ?>
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
							<?php echo esc_html( $users_text ); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

