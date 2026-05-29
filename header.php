<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name=viewport content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>

</head>
<body <?php body_class( ); ?>>

	<?php wp_body_open(); ?>

	<header class="mwm-header" id="mwm-header">

		<?php 
			$register_button_text = get_option( 'mwm_header_register_button_text' ); 
			$register_button_link = get_option( 'mwm_header_register_button_link' );
			$access_button_text   = get_option( 'mwm_header_access_button_text' ); 
			$access_button_link   = get_option( 'mwm_header_access_button_link' );

			// Usamos comparación estricta con '1' para evitar el bug de (bool) '0' === true.
			$register_button_target_blank_raw = get_option( 'mwm_header_register_button_target_blank', '0' );
			$access_button_target_blank_raw   = get_option( 'mwm_header_access_button_target_blank', '0' );

			$register_button_target_blank = ( '1' === $register_button_target_blank_raw );
			$access_button_target_blank   = ( '1' === $access_button_target_blank_raw );
		?>
	
		<div class="mwm-max-1">
			<div class="mwm-header__wrapper mwm-header__main-wrapper">
				
				<a class="mwm-header__logo" href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo( 'name' ); ?>">
					<?php echo wp_get_attachment_image(get_theme_mod( 'custom_logo', '' ) , 'full' ); ?>
				</a>

				<nav class="mwm-header__menu" id="mwm-header-menu-1">
					<?php
						wp_nav_menu( array( 
							'theme_location' => 'HeaderMenu1',
							'container'       => false,
							'menu_context'    => 'desktop',
						));
					?>
				</nav>

				<nav class="mwm-header__menu" id="mwm-header-menu-2">
					<?php
						wp_nav_menu( array( 
							'theme_location' => 'HeaderMenu2',
							'container'       => false,
							'menu_context'    => 'desktop',
						));
					?>
				</nav>

				<div class="mwm-header__buttons">
					<?php echo mwm_button( array(
						'text' => __( $register_button_text ),
						'variant' => 'fill',
						'color' => 'secundary',
						'size' => 'sm',
						'url' => $register_button_link,
						'class' => 'mwm-header__button-register',
						'target_blank' => $register_button_target_blank,
					) ); ?>
					<?php echo mwm_button( array(
						'text' => __( $access_button_text ),
						'variant' => 'fill',
						'color' => 'primary',
						'size' => 'sm',
						'url' => $access_button_link,
						'class' => 'mwm-header__button-access',
						'target_blank' => $access_button_target_blank,
					) ); ?>
				</div>


				<?php echo mwm_icon_circle( array(
					'icon' => 'bars',
					'variant' => 'fill-icon',
					'color' => 'terciary',
					'size' => 'md',
					'class' => 'mwm-header__toggle',
				) ); ?>

			</div>
			<div class="mwm-header__fixed">
				<div class="mwm-max-1">
					<div class="mwm-header__wrapper mwm-header__fixed-wrapper">
						
						<a class="mwm-header__logo" href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo( 'name' ); ?>">
							<?php echo wp_get_attachment_image(get_theme_mod( 'custom_logo', '' ) , 'full' ); ?>
						</a>
		
						<nav class="mwm-header__menu" id="mwm-header-menu-1">
							<?php
								wp_nav_menu( array( 
									'theme_location' => 'HeaderMenu1',
									'container'       => false,
									'menu_context'    => 'desktop',
								));
							?>
						</nav>

						<nav class="mwm-header__menu" id="mwm-header-menu-2">
							<?php
								wp_nav_menu( array( 
									'theme_location' => 'HeaderMenu2',
									'container'       => false,
									'menu_context'    => 'desktop',
								));
							?>
						</nav>
		
						<div class="mwm-header__buttons">
							<?php echo mwm_button( array(
								'text' => __( $register_button_text ),
								'variant' => 'fill',
								'color' => 'secundary',
								'size' => 'sm',
								'url' => $register_button_link,
								'class' => 'mwm-header__button-register',
								'target_blank' => $register_button_target_blank,
							) ); ?>
							<?php echo mwm_button( array(
								'text' => __( $access_button_text ),
								'variant' => 'fill',
								'color' => 'primary',
								'size' => 'sm',
								'url' => $access_button_link,
								'class' => 'mwm-header__button-access',
								'target_blank' => $access_button_target_blank,
							) ); ?>
						</div>
		
						<?php echo mwm_icon_circle( array(
							'icon' => 'bars',
							'variant' => 'fill-icon',
							'color' => 'terciary',
							'size' => 'md',
							'class' => 'mwm-header__toggle',
						) ); ?>
		
					</div>
				</div>
			</div>
		</div>
	</header>

	<div class="mwm-header-mobile">
		<div class="mwm-header-mobile__wrapper">
			<div class="mwm-header-mobile__buttons">
				<?php echo mwm_button( array(
					'text' => __( $register_button_text ),
					'variant' => 'fill',
					'color' => 'secundary',
					'size' => 'sm',
					'url' => $register_button_link,
					'class' => 'mwm-header-mobile__button-access',
					'target_blank' => $register_button_target_blank,
				) ); ?>
				<?php echo mwm_button( array(
					'text' => __( $access_button_text ),
					'variant' => 'fill',
					'color' => 'primary',
					'size' => 'sm',
					'url' => $access_button_link,
					'class' => 'mwm-header-mobile__button-access',
					'target_blank' => $access_button_target_blank,
				) ); ?>
			</div>
			<nav class="mwm-header-mobile__menu" id="mwm-header-mobile-menu-1">
				<?php
					wp_nav_menu( array( 
						'theme_location' => 'HeaderMenu1',
						'container'       => false,
					));
				?>
			</nav>
			<nav class="mwm-header-mobile__menu" id="mwm-header-mobile-menu-2">
				<?php
					wp_nav_menu( array( 
						'theme_location' => 'HeaderMenu2',
						'container'       => false,
					));
				?>
			</nav>
		</div>
	</div>
