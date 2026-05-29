<?php get_template_part('template-parts/floating-button'); ?>

<footer class="mwm-footer">
	<div class="mwm-footer__wrapper">
		<div class="mwm-footer__top">
			<div class="mwm-footer__left">
				<div class="mwm-footer__logo">
					<?php 
					$logo_1_id = get_option( 'footer_logo_1_image' );
					if ( $logo_1_id ) {
						$logo_1_alt = get_post_meta( $logo_1_id, '_wp_attachment_image_alt', true );
						if ( empty( $logo_1_alt ) ) {
							$logo_1_title = get_the_title( $logo_1_id );
							$logo_1_alt = ! empty( $logo_1_title ) ? $logo_1_title : __( 'Logo 1 de Bilky', 'bilky' );
						}
						echo wp_get_attachment_image( $logo_1_id, 'full', false, array( 'alt' => esc_attr( $logo_1_alt ) ) );
					}
					
					$logo_2_id = get_option( 'footer_logo_2_image' );
					if ( $logo_2_id ) {
						$logo_2_alt = get_post_meta( $logo_2_id, '_wp_attachment_image_alt', true );
						if ( empty( $logo_2_alt ) ) {
							$logo_2_title = get_the_title( $logo_2_id );
							$logo_2_alt = ! empty( $logo_2_title ) ? $logo_2_title : __( 'Logo 2 de Bilky', 'bilky' );
						}
						echo wp_get_attachment_image( $logo_2_id, 'full', false, array( 'alt' => esc_attr( $logo_2_alt ) ) );
					}
					?>
				</div>
				<div class="mwm-footer__certifications">
					<?php
					$iso_image = get_option( 'footer_iso_image' );
					$iso_url   = trim( (string) get_option( 'footer_iso_url', '' ) );
					$iso_new_tab = (int) get_option( 'footer_iso_new_tab', 0 );
					$iso_is_link = $iso_image && $iso_url;
					if ( $iso_is_link ) :
						$iso_alt = get_post_meta( $iso_image, '_wp_attachment_image_alt', true );
						if ( empty( $iso_alt ) ) {
							$iso_alt = __( 'ISO 27001', 'bilky' );
						}
						?>
						<a class="mwm-footer__certification" href="<?php echo esc_url( $iso_url ); ?>" aria-label="<?php echo esc_attr( $iso_alt ); ?>"<?php echo $iso_new_tab ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
							<?php echo wp_get_attachment_image( $iso_image, 'full', false, array( 'alt' => esc_attr( $iso_alt ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					<?php else : ?>
						<div class="mwm-footer__certification">
							<?php
							if ( $iso_image ) {
								$iso_alt = get_post_meta( $iso_image, '_wp_attachment_image_alt', true );
								if ( empty( $iso_alt ) ) {
									$iso_alt = __( 'ISO 27001', 'bilky' );
								}
								echo wp_get_attachment_image( $iso_image, 'full', false, array( 'alt' => esc_attr( $iso_alt ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							?>
						</div>
					<?php endif; ?>

					<?php
					$at_image = get_option( 'footer_agencia_tributaria_image' );
					$at_url   = trim( (string) get_option( 'footer_agencia_tributaria_url', '' ) );
					$at_new_tab = (int) get_option( 'footer_agencia_tributaria_new_tab', 0 );
					$at_is_link = $at_image && $at_url;
					if ( $at_is_link ) :
						$at_alt = get_post_meta( $at_image, '_wp_attachment_image_alt', true );
						if ( empty( $at_alt ) ) {
							$at_alt = __( 'Agencia Tributaria', 'bilky' );
						}
						?>
						<a class="mwm-footer__certification" href="<?php echo esc_url( $at_url ); ?>" aria-label="<?php echo esc_attr( $at_alt ); ?>"<?php echo $at_new_tab ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
							<?php echo wp_get_attachment_image( $at_image, 'full', false, array( 'alt' => esc_attr( $at_alt ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					<?php else : ?>
						<div class="mwm-footer__certification">
							<?php
							if ( $at_image ) {
								$at_alt = get_post_meta( $at_image, '_wp_attachment_image_alt', true );
								if ( empty( $at_alt ) ) {
									$at_alt = __( 'Agencia Tributaria', 'bilky' );
								}
								echo wp_get_attachment_image( $at_image, 'full', false, array( 'alt' => esc_attr( $at_alt ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="mwm-footer__menus">
				<?php
				// Menú Funcionalidades
				$funcionalidades_title = get_option( 'footer_menu_title_1', __( 'Funcionalidades', 'bilky' ) );
				?>
				<?php if ( has_nav_menu( 'FooterMenu1' ) ) : ?>
					<div class="mwm-footer__menu-column">
						<div class="mwm-footer__menu-toggle">
							<span class="mwm-footer__menu-toggle-text"><?php echo esc_html( $funcionalidades_title ); ?></span>
							<span class="mwm-footer__menu-toggle-icon">
								<i class="fa-solid fa-chevron-down"></i>
							</span>
						</div>
						<div class="mwm-footer__menu-list" id="footer-menu-funcionalidades">
							<?php
							wp_nav_menu( array(
								'theme_location' => 'FooterMenu1',
								'container' => false,
								'menu_class' => 'mwm-footer__menu-items',
							) );
							?>
						</div>
					</div>
				<?php endif; ?>

				<?php
				// Menú Recursos
				$recursos_title = get_option( 'footer_menu_title_2', __( 'Recursos', 'bilky' ) );
				?>
				<?php if ( has_nav_menu( 'FooterMenu2' ) ) : ?>
					<div class="mwm-footer__menu-column">
						<div class="mwm-footer__menu-toggle">
							<span class="mwm-footer__menu-toggle-text"><?php echo esc_html( $recursos_title ); ?></span>
							<span class="mwm-footer__menu-toggle-icon">
								<i class="fa-solid fa-chevron-down"></i>
							</span>
						</div>
						<div class="mwm-footer__menu-list" id="footer-menu-recursos">
							<?php
							wp_nav_menu( array(
								'theme_location' => 'FooterMenu2',
								'container' => false,
								'menu_class' => 'mwm-footer__menu-items',
							) );
							?>
						</div>
					</div>
				<?php endif; ?>

				<div class="mwm-footer__menu-column mwm-footer__menu-column--actions">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'FooterMenu3',
						'container' => false,
					) );
					?>

					<div class="mwm-footer__actions">
						<?php echo do_shortcode('[language-switcher]'); ?>
						<?php
						$acceder_url = get_option( 'footer_acceder_url', '#' );
						if ( $acceder_url ) :
						?>
							<?php echo mwm_button( array(
								'text' => __( 'Acceder' ),
								'variant' => 'fill',
								'color' => 'primary',
								'size' => 'xl-md',
								'url' => $acceder_url,
								'class' => 'mwm-footer__button-acceder',
							) ); ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="mwm-footer__bottom">
			<div class="mwm-footer__legal">
				<p class="mwm-footer__copyright">
					<?php
					$copyright_text = get_option( 'footer_copyright', sprintf( __( '© %d - %d Bilky', 'bilky' ), 2015, date( 'Y' ) ) );
					echo esc_html( $copyright_text );
					?>
				</p>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'FooterMenuLegal',
					'container' => false,
				) );
				?>
			</div>
			<div class="mwm-footer__social">
				<?php
				$social_links = array(
					'facebook' => get_option( 'footer_social_facebook', '' ),
					'instagram' => get_option( 'footer_social_instagram', '' ),
					'linkedin' => get_option( 'footer_social_linkedin', '' ),
					'tiktok' => get_option( 'footer_social_tiktok', '' ),
					'vimeo' => get_option( 'footer_social_vimeo', '' ),
					'youtube' => get_option( 'footer_social_youtube', '' ),
				);
				?>
				<?php if ( ! empty( $social_links['facebook'] ) ) : ?>
					<a href="<?php echo esc_url( $social_links['facebook'] ); ?>" class="mwm-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Facebook', 'bilky' ); ?>">
						<?php get_template_part('assets/images/icons/icon-facebook'); ?>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $social_links['instagram'] ) ) : ?>
					<a href="<?php echo esc_url( $social_links['instagram'] ); ?>" class="mwm-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Instagram', 'bilky' ); ?>">
						<?php get_template_part('assets/images/icons/icon-instagram'); ?>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $social_links['linkedin'] ) ) : ?>
					<a href="<?php echo esc_url( $social_links['linkedin'] ); ?>" class="mwm-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'LinkedIn', 'bilky' ); ?>">
						<?php get_template_part('assets/images/icons/icon-linkedin'); ?>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $social_links['tiktok'] ) ) : ?>
					<a href="<?php echo esc_url( $social_links['tiktok'] ); ?>" class="mwm-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'TikTok', 'bilky' ); ?>">
						<?php get_template_part('assets/images/icons/icon-tiktok'); ?>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $social_links['vimeo'] ) ) : ?>
					<a href="<?php echo esc_url( $social_links['vimeo'] ); ?>" class="mwm-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Vimeo', 'bilky' ); ?>">
						<?php get_template_part( 'assets/images/icons/icon-vimeo' ); ?>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $social_links['youtube'] ) ) : ?>
					<a href="<?php echo esc_url( $social_links['youtube'] ); ?>" class="mwm-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'YouTube', 'bilky' ); ?>">
						<?php get_template_part('assets/images/icons/icon-youtube'); ?>
					</a>
				<?php endif; ?>
				
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
