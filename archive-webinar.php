<?php
/**
 * Archive: webinars
 *
 * @package bilky
 */

get_header();

$desc_key = 'mwm_webinar_description';
$desc     = get_option( $desc_key, '' );
if ( '' === $desc ) {
	$desc = get_theme_mod( $desc_key, '' );
}
$desc = is_string( $desc ) ? trim( $desc ) : '';

$archive_link = get_post_type_archive_link( 'webinar' );

$current_term = null;
if ( is_tax( 'category_webinar' ) ) {
	$current_term = get_queried_object();
}
?>

<main class="mwm-main-container">
	<div class="wp-block-group has-white-background-color has-background is-right-after-header">
		<div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
			<section class="mwm-webinars-archive">
				<div class="mwm-webinars-archive__wrapper">
					<header class="mwm-webinars-archive__hero">
						<div class="mwm-webinars-archive__hero-content">
							<div class="mwm-section-07__breadcrumbs">
								<div class="mwm-button mwm-button--outline mwm-button--secundary mwm-button--sm mwm-button--tag">
									<span class="mwm-button__dot mwm-button__dot--sm"></span>
									<span class="mwm-button__text"><?php echo esc_html( mwm_webinar_get_archive_text( 'mwm_archive_breadcrumb_1', __( 'Sesiones en directo', 'bilky' ) ) ); ?></span>
								</div>
								<?php
								$breadcrumb_2_key  = mwm_get_archive_setting_key( 'mwm_archive_breadcrumb_2' );
								$breadcrumb_2_text = get_option( $breadcrumb_2_key, '' );
								if ( empty( $breadcrumb_2_text ) ) {
									$breadcrumb_2_text = get_theme_mod( $breadcrumb_2_key, '' );
								}
								if ( ! empty( trim( (string) $breadcrumb_2_text ) ) ) :
									?>
									<div class="mwm-section-07__breadcrumb-item">
										<span class="mwm-section-07__breadcrumb-separator">|</span>
										<span class="mwm-section-07__breadcrumb-text"><?php echo esc_html( $breadcrumb_2_text ); ?></span>
									</div>
								<?php endif; ?>
							</div>
							<h1 class="mwm-webinars-archive__title is-style-h-100">
								<?php
								echo esc_html(
									mwm_webinar_get_archive_text(
										'mwm_archive_title',
										__( 'Controla y gestiona las facturas de tus clientes desde un único lugar.', 'bilky' )
									)
								);
								?>
							</h1>
							<?php if ( '' !== $desc ) : ?>
								<p class="mwm-webinars-archive__description is-style-b-100"><?php echo esc_html( $desc ); ?></p>
							<?php endif; ?>
						</div>

						<div class="mwm-filter mwm-webinars-archive__filters">
							<div class="mwm-webinars-archive__filters-block">
								<div class="mwm-filter__label is-style-b-300">
									<?php esc_html_e( 'Categoriza por tipo de contenido', 'bilky' ); ?>
								</div>
								<ul class="mwm-filter__categories">
									<li>
										<?php
										echo mwm_button(
											array(
												'text'    => __( 'Ver todos', 'bilky' ),
												'url'     => esc_url( $archive_link ),
												'variant' => 'fill',
												'color'   => ! $current_term ? 'primary' : 'terciary',
												'size'    => 'sm',
											)
										);
										?>
									</li>
									<?php
									$terms = get_terms(
										array(
											'taxonomy'   => 'category_webinar',
											'hide_empty' => true,
										)
									);

									if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) :
										foreach ( $terms as $term ) :
											$term_link = get_term_link( $term );
											if ( is_wp_error( $term_link ) ) {
												continue;
											}
											?>
											<li>
												<?php
												echo mwm_button(
													array(
														'text'    => esc_html( $term->name ),
														'url'     => esc_url( $term_link ),
														'variant' => 'fill',
														'color'   => ( $current_term && (int) $current_term->term_id === (int) $term->term_id ) ? 'primary' : 'terciary',
														'size'    => 'sm',
													)
												);
												?>
											</li>
											<?php
										endforeach;
									endif;
									?>
								</ul>
							</div>
						</div>
					</header>

					<div class="mwm-webinars-archive__results">
						<div class="mwm-webinars-archive__grid">
							<?php
							if ( have_posts() ) :
								while ( have_posts() ) :
									the_post();
									get_template_part( 'template-parts/mwm-card-webinar' );
								endwhile;
							else :
								?>
								<p class="mwm-webinars-archive__empty is-style-b-100">
									<?php esc_html_e( 'No hay webinars disponibles.', 'bilky' ); ?>
								</p>
								<?php
							endif;
							?>
						</div>

						<?php
						ob_start();
						mwm_load_more( '.mwm-webinars-archive__grid', 'template-parts/mwm-card-webinar' );
						$load_more = ob_get_clean();

						if ( ! empty( trim( $load_more ) ) ) :
							?>
							<div class="mwm-webinars-archive__load-more">
								<?php echo $load_more; ?>
							</div>
							<?php
						endif;
						?>
					</div>
				</div>
			</section>
		</div>
	</div>
</main>

<?php
get_footer();
