<?php get_header(); ?>

<main class="mwm-main-container">
	<div class="wp-block-group has-white-background-color has-background is-right-after-header">
		<div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
			<section class="mwm-centro-ayuda-archive">
				<div class="mwm-centro-ayuda-archive__wrapper">
					<header class="mwm-section-07 mwm-section-07--left mwm-section-07--archive-with-filters">
						<div class="mwm-section-07__wrapper">
							<div class="mwm-section-07__breadcrumbs">
								<div class="mwm-button mwm-button--outline mwm-button--secundary mwm-button--sm mwm-button--tag">
									<span class="mwm-button__dot mwm-button__dot--sm"></span>
									<span class="mwm-button__text"><?php mwm_echo_mod( mwm_get_archive_setting_key( 'mwm_archive_breadcrumb_1' ) ); ?></span>
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
							<div class="mwm-section-07__content">
								<h1 class="mwm-section-07__title is-style-h-100">
									<?php mwm_echo_mod( mwm_get_archive_setting_key( 'mwm_archive_title' ) ); ?>
								</h1>
								<p class="mwm-section-07__text-body is-style-b-100">
									<?php esc_html_e( 'Aquí encontrarás todo lo que necesites saber de nuestra plataforma. Si no encuentras lo que buscas no dudes en contactar con nuestro equipo. Estamos encantados de atenderte para intentar resolver tus dudas.', 'bilky' ); ?>
								</p>
							</div>
						</div>

						<div class="mwm-filter">
							<div class="mwm-filter__group">
								<div class="mwm-filter__label is-style-b-300">
									<?php esc_html_e( 'Categoriza por tipo de contenido', 'bilky' ); ?>
								</div>
								<ul class="mwm-filter__categories">
									<?php
									$current_search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
									$archive_link   = get_post_type_archive_link( 'centro_de_ayuda' );
									$all_link       = $archive_link;

									$current_term = null;
									if ( is_tax( 'category_centro_de_ayuda' ) ) {
										$current_term = get_queried_object();
									} elseif ( ! empty( $_GET['category_centro_de_ayuda'] ) ) {
										$term_slug = function_exists( 'mwm_centro_ayuda_sanitize_slug_param' )
											? mwm_centro_ayuda_sanitize_slug_param( wp_unslash( $_GET['category_centro_de_ayuda'] ) )
											: sanitize_text_field( wp_unslash( $_GET['category_centro_de_ayuda'] ) );
										$term_obj  = get_term_by( 'slug', $term_slug, 'category_centro_de_ayuda' );
										if ( $term_obj && ! is_wp_error( $term_obj ) ) {
											$current_term = $term_obj;
										}
									}
									?>
									<li>
										<?php
										echo mwm_button(
											array(
												'text'    => __( 'Ver todos', 'bilky' ),
												'url'     => esc_url( $all_link ),
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
											'taxonomy'   => 'category_centro_de_ayuda',
											'hide_empty' => true,
										)
									);

									if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) :
										foreach ( $terms as $term ) :
											$term_link = get_term_link( $term );
											if ( $current_search ) {
												$term_link = add_query_arg(
													array(
														's'         => $current_search,
														'post_type' => 'centro_de_ayuda',
													),
													$term_link
												);
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
							<div class="mwm-filter__search-block">
								<div class="mwm-filter__label is-style-b-300">
									<?php esc_html_e( 'Busca lo que necesites', 'bilky' ); ?>
								</div>
								<form class="mwm-filter__search-form" action="<?php echo esc_url( $archive_link ); ?>" method="get">
									<label class="screen-reader-text" for="mwm-filter-archive-search-centro">
										<?php esc_html_e( 'Buscar en centro de ayuda', 'bilky' ); ?>
									</label>
									<button type="submit" class="mwm-filter__search-icon" aria-label="<?php esc_attr_e( 'Buscar', 'bilky' ); ?>"><i class="fa-solid fa-magnifying-glass mwm-filter__search-icon-glyph"></i></button>
									<span class="mwm-filter__search-divider" aria-hidden="true"></span>
									<input id="mwm-filter-archive-search-centro" class="mwm-filter__search-input" type="search" name="s" value="<?php echo esc_attr( $current_search ); ?>" placeholder="<?php esc_attr_e( 'Escribe aquí lo que te interese...', 'bilky' ); ?>">
									<input type="hidden" name="post_type" value="centro_de_ayuda">
									<?php if ( $current_term && ! is_wp_error( $current_term ) ) : ?>
										<input type="hidden" name="category_centro_de_ayuda" value="<?php echo esc_attr( $current_term->slug ); ?>">
									<?php endif; ?>
								</form>
							</div>
						</div>
					</header>

					<div class="mwm-centro-ayuda-archive__results">
						<div class="mwm-centro-ayuda-archive__results-head">
							<p class="mwm-centro-ayuda-archive__results-text is-style-b-300">
								<?php
								printf(
									/* translators: %d: number of results */
									esc_html__( 'Resultados de búsqueda (%d)', 'bilky' ),
									(int) $wp_query->found_posts
								);
								?>
							</p>
							<div class="mwm-centro-ayuda-archive__results-view" aria-hidden="true">
								<button type="button" class="mwm-centro-ayuda-archive__view-circle mwm-centro-ayuda-archive__view-circle--grid is-active" data-layout="grid" aria-label="<?php esc_attr_e( 'Vista en cuadrícula', 'bilky' ); ?>">
									<i class="fa-solid fa-grip"></i>
								</button>
								<button type="button" class="mwm-centro-ayuda-archive__view-circle mwm-centro-ayuda-archive__view-circle--list" data-layout="rows" aria-label="<?php esc_attr_e( 'Vista en lista', 'bilky' ); ?>">
									<i class="fa-solid fa-list"></i>
								</button>
							</div>
						</div>

						<div class="mwm-centro-ayuda-archive__list mwm-centro-ayuda-archive__list--grid" data-layout-list>
							<?php
							if ( have_posts() ) :
								while ( have_posts() ) :
									the_post();
									get_template_part( 'template-parts/mwm-card-centro-ayuda' );
								endwhile;
							else :
								?>
								<p class="mwm-centro-ayuda-archive__results-text is-style-b-100">
									<?php esc_html_e( 'No se han encontrado resultados.', 'bilky' ); ?>
								</p>
								<?php
							endif;
							?>
						</div>

						<?php
						ob_start();
						mwm_load_more( '.mwm-centro-ayuda-archive__list', 'template-parts/mwm-card-centro-ayuda' );
						$load_more = ob_get_clean();

						if ( ! empty( trim( $load_more ) ) ) :
							?>
							<div class="mwm-centro-ayuda-archive__load-more">
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

<?php get_footer(); ?>
