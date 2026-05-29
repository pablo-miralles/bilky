<?php get_header(); ?>

<main class="mwm-main-container">

	<div class="wp-block-group has-white-background-color has-background is-right-after-header">
		<div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
			<section class="mwm-section-blog">
				<div class="mwm-section-blog__header">
					<div class="mwm-section-07 mwm-section-07--background-white">
						<div class="mwm-section-07__wrapper">
							<div class="mwm-section-07__breadcrumbs">
								<div class="mwm-button mwm-button--outline mwm-button--secundary mwm-button--sm mwm-button--tag">
									<span class="mwm-button__dot mwm-button__dot--sm"></span>
									<span class="mwm-button__text"><?php mwm_echo_mod( mwm_get_archive_setting_key( 'mwm_archive_breadcrumb_1' ) ); ?></span>
								</div>
								<?php
								$breadcrumb_2_key = mwm_get_archive_setting_key( 'mwm_archive_breadcrumb_2' );
								$breadcrumb_2_text = get_option( $breadcrumb_2_key, '' );
								if ( empty( $breadcrumb_2_text ) ) {
									$breadcrumb_2_text = get_theme_mod( $breadcrumb_2_key, '' );
								}
								if ( ! empty( trim( $breadcrumb_2_text ) ) ) :
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
							</div>
						</div>
					</div>
					<div class="mwm-filter">
						<div class="mwm-filter__label">
							<?php _e( 'Categorías de contenido', 'bilky' ); ?>
						</div>
						<ul class="mwm-filter__categories">
							<?php
							$queried_object     = get_queried_object();
							$is_centro_archive  = is_post_type_archive( 'centro_de_ayuda' );
							$is_centro_tax      = is_tax( 'category_centro_de_ayuda' );
							$is_centro_context  = $is_centro_archive || $is_centro_tax;

							// URL "Todas": blog = página de entradas; centro de ayuda = archive del CPT.
							if ( $is_centro_context ) {
								$all_url          = get_post_type_archive_link( 'centro_de_ayuda' );
								$is_all_active    = $is_centro_archive;
								$all_button_color = $is_all_active ? 'primary' : 'terciary';
							} else {
								$is_home_page     = is_home() && ! is_category();
								$all_url          = get_permalink( get_option( 'page_for_posts' ) );
								if ( ! $all_url ) {
									$all_url = home_url( '/' );
								}
								$all_button_color = $is_home_page ? 'primary' : 'terciary';
							}
							?>
							<li>
								<?php
								echo mwm_button(
									array(
										'text'    => __( 'Todas', 'bilky' ),
										'url'     => esc_url( $all_url ),
										'variant' => 'fill',
										'color'   => $all_button_color,
										'size'    => 'sm',
									)
								);
								?>
							</li>

							<?php
							if ( $is_centro_context ) {
								// Listar categorías específicas del centro de ayuda.
								$centro_terms = get_terms(
									array(
										'taxonomy'   => 'category_centro_de_ayuda',
										'hide_empty' => true,
									)
								);

								if ( ! is_wp_error( $centro_terms ) && ! empty( $centro_terms ) ) {
									foreach ( $centro_terms as $term ) {
										$is_active    = isset( $queried_object->term_id ) && (int) $queried_object->term_id === (int) $term->term_id;
										$button_color = $is_active ? 'primary' : 'terciary';

										echo '<li>';
										echo mwm_button(
											array(
												'text'    => esc_html( $term->name ),
												'url'     => esc_url( get_term_link( $term ) ),
												'variant' => 'fill',
												'color'   => $button_color,
												'size'    => 'sm',
											)
										);
										echo '</li>';
									}
								}
							} else {
								// Listar categorías del blog.
								$categories = get_categories();

								foreach ( $categories as $category ) {
									// Excluir categorías con slugs específicos.
									$excluded_slugs = array( 'sin-categoria', 'sin-categoria-en', 'uncategorized' );
									if ( in_array( $category->slug, $excluded_slugs, true ) ) {
										continue;
									}

									// Determinar si esta categoría está activa.
									$is_active = isset( $queried_object->term_id ) && (int) $queried_object->term_id === (int) $category->term_id;

									// Color del botón: primary si está activo, terciary si no.
									$button_color = $is_active ? 'primary' : 'terciary';

									echo '<li>';
									echo mwm_button(
										array(
											'text'    => esc_html( $category->name ),
											'url'     => esc_url( get_category_link( $category->term_id ) ),
											'variant' => 'fill',
											'color'   => $button_color,
											'size'    => 'sm',
										)
									);
									echo '</li>';
								}
							}
							?>
						</ul>
					</div>
				</div>
				<div class="mwm-section-blog__content">
					<?php
					// Obtener el post/artículo destacado del customizer (blog o centro de ayuda según contexto)
					// Solo mostrarlo si NO estamos en una página de categoría del blog, o si estamos en archive del centro de ayuda
					$show_featured = ( is_home() && ! is_category() ) || is_post_type_archive( 'centro_de_ayuda' );
					if ( $show_featured ) {
						$featured_post_id = get_option( mwm_get_archive_setting_key( 'mwm_archive_featured_post' ), '' );
						if ( ! empty( $featured_post_id ) ) {
							$featured_post = get_post( $featured_post_id );
							if ( $featured_post ) {
								global $post;
								$original_post = $post;
								$post = $featured_post;
								setup_postdata( $post );
								?>
								<div class="mwm-section-blog__featured">
									<article class="mwm-card-05">
										<div class="mwm-card-05__wrapper">
											<div class="mwm-card-05__media">
												<a href="<?php the_permalink(); ?>">
													<?php
													$thumbnail_id = get_post_thumbnail_id( $post->ID );
													if ( $thumbnail_id ) {
														echo wp_get_attachment_image( $thumbnail_id, 'full', false, array( 'alt' => get_the_title() ) );
													}
													?>
												</a>
											</div>
											<div class="mwm-card-05__content">
												<div class="mwm-card-05__meta">
													<?php
													$categories = get_the_category( $post->ID );
													if ( ! empty( $categories ) ) {
														$category = $categories[0];
														echo mwm_button(
															array(
																'text'    => esc_html( $category->name ),
																'url'     => esc_url( get_category_link( $category->term_id ) ),
																'variant' => 'soft',
																'color'   => 'terciary',
																'size'    => 'sm',
															)
														);
													}
													?>
													<div class="mwm-card-05__date"><?php echo esc_html( get_the_date( 'd.m.Y', $post->ID ) ); ?></div>
												</div>
												<h3 class="mwm-card-05__title"><?php echo esc_html( get_the_title( $post->ID ) ); ?></h3>
												<div class="mwm-card-05__button">
													<?php
													echo mwm_button(
														array(
															'text'    => __( 'Leer más', 'bilky' ),
															'url'     => esc_url( get_the_permalink( $post->ID ) ),
															'variant' => 'fill',
															'color'   => 'secundary',
															'size'    => 'xl-md',
														)
													);
													?>
												</div>
											</div>
										</div>
									</article>
								</div>
								<?php
								$post = $original_post;
								wp_reset_postdata();
							}
						}
					}
					?>
					<div class="mwm-section-blog__list">
						<?php
						if ( have_posts() ) {
							while ( have_posts() ) {
								the_post();
								get_template_part( 'template-parts/mwm-card-05' );
							}
						}
						?>
					</div>
					<?php 
					ob_start(); 

					mwm_load_more('.mwm-section-blog__list', 'template-parts/mwm-card-05'); 

					$output = ob_get_clean(); 

					if ( ! empty( trim( $output ) ) ) : ?>
						<div class="mwm-section-blog__btn">
							<?php echo $output; ?>
						</div>
					<?php endif; ?>
				</div>
			</section>
		</div>
	</div>

</main>

<?php get_footer(); ?>
