<?php 

/*
Template Name: Página Legal
*/

get_header(); ?>

<main class="mwm-main-container">

	<div class="wp-block-group has-white-background-color has-background is-right-after-header">
		<div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
			<div class="mwm-section-post mwm-section-07 mwm-section-07--center mwm-section-07--background-white">
				<div class="mwm-section-post__header">
					<div class="mwm-section-07__wrapper">
						<div class="mwm-section-07__content">
							<h1 class="mwm-section-07__title is-style-h-100">
								<span class="mwm-section-07__title-line"><?php the_title(); ?></span>
							</h1>
						</div>
					</div>
				</div>
				<div class="mwm-section-post__body">
					<div class="mwm-section-post__sidebar">
						<div class="mwm-section-post__sidebar-header">
							<?php _e( 'Índice de contenido', 'bilky' ); ?>
						</div>
						<div class="mwm-section-post__sidebar-list"></div>
					</div>
					<div class="mwm-section-post__content">
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
