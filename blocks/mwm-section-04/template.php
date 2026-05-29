<?php
/**
 * Block Name: MWM Section 05
 */

$title = get_field('title');
$variant = get_field('variant') ?: 'center';
$variant_class = 'mwm-section-07--' . esc_attr( $variant );
$classes = array( 'mwm-section-07', $variant_class );
$show_breadcrumbs = get_field('show_breadcrumbs') !== false;
$breadcrumb_button_text = get_field('breadcrumb_button_text');
$breadcrumb_button_link = get_field('breadcrumb_button_link');
$show_avatar_text = get_field('show_avatar_text') !== false;
$show_avatar = get_field('show_avatar') !== false;
$avatars = get_field('avatars');
$users_text = get_field('users_text');
$cards = get_field('cards');
?>

<section class="mwm-section-04">
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<div class="mwm-section-07__wrapper">
			<?php if ( $show_breadcrumbs ) : ?>
				<div class="mwm-section-07__breadcrumbs">
					<?php if ( $breadcrumb_button_text ) : ?>
					<?php
					$button_args = array(
						'text' => esc_html( $breadcrumb_button_text ),
						'variant' => 'outline',
						'color' => 'secundary',
						'size' => 'xl-md',
					);
					if ( empty( $breadcrumb_button_link ) ) {
						$button_args['is_tag'] = true;
					} else {
						$button_args['url'] = esc_url( $breadcrumb_button_link );
					}
					echo mwm_button( $button_args );
					?>
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
	</div>
	<div class="mwm-section-04__cards">
		<?php foreach ( $cards as $card ) : ?>
			<div class="mwm-card-03">
				<div class="mwm-card-03__header">
					<span class="mwm-card-03__tag is-style-b-200"><?php echo esc_html( $card['tag'] ); ?></span>
					<div class="mwm-card-03__icon">
						<?php echo mwm_icon_circle( array(
							'icon' => $card['icon'],
							'variant' => 'fill-icon',
							'color' => 'terciary',
							'size' => 'xl',
						) ); ?>
					</div>
				</div>
				<div class="mwm-card-03__content">
					<h3 class="mwm-card-03__title is-style-h-300"><?php echo esc_html( $card['title'] ); ?></h3>
					<div class="mwm-card-03__description is-style-b-200"><?php echo esc_html( $card['description'] ); ?></div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>

