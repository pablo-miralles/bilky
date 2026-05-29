<?php get_header(); ?>

<main class="mwm-main-container">

	<div class="wp-block-group has-white-background-color has-background is-right-after-header">
		<div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
			<div class="mwm-section-post mwm-section-07 mwm-section-07--center mwm-section-07--background-white">
				<div class="mwm-section-post__header">
					<div class="mwm-section-07__wrapper">
						<div class="mwm-section-07__breadcrumbs">
							<?php
							$back_url = is_singular( 'centro_de_ayuda' )
								? get_post_type_archive_link( 'centro_de_ayuda' )
								: get_permalink( get_option( 'page_for_posts' ) );
							if ( ! $back_url ) {
								$back_url = home_url( '/' );
							}
							?>
							<a href="<?php echo esc_url( $back_url ); ?>" class="mwm-button mwm-button--outline mwm-button--secundary mwm-button--sm mwm-button--active-focus">
								<span class="mwm-button__dot mwm-button__dot--sm"></span>
								<span class="mwm-button__text"><?php mwm_echo_mod( mwm_get_archive_setting_key( 'mwm_archive_breadcrumb_1' ) ); ?></span>
							</a>
							<?php
							$breadcrumb_2_text = '';
							if ( is_singular( 'centro_de_ayuda' ) ) {
								$breadcrumb_2_text = get_option( mwm_get_archive_setting_key( 'mwm_archive_breadcrumb_2' ), '' );
								if ( empty( $breadcrumb_2_text ) ) {
									$breadcrumb_2_text = get_theme_mod( mwm_get_archive_setting_key( 'mwm_archive_breadcrumb_2' ), '' );
								}
							} else {
								$categories = get_the_category();
								if ( ! empty( $categories ) ) {
									$breadcrumb_2_text = $categories[0]->name;
								}
							}
							if ( ! empty( trim( $breadcrumb_2_text ) ) ) :
								?>
							<div class="mwm-section-07__breadcrumb-item">
								<span class="mwm-section-07__breadcrumb-separator">|</span>
								<span class="mwm-section-07__breadcrumb-text"><?php echo esc_html( $breadcrumb_2_text ); ?></span>
							</div>
							<?php endif; ?>
							<div class="mwm-section-07__breadcrumb-item">
								<span class="mwm-section-07__breadcrumb-separator">|</span>
								<span class="mwm-section-07__breadcrumb-text">
									<?php
									$title = get_the_title();
									$max_length = 60; // Longitud máxima antes de truncar
									if ( mb_strlen( $title ) > $max_length ) {
										$title = mb_substr( $title, 0, $max_length ) . '...';
									}
									echo esc_html( $title );
									?>
								</span>
							</div>
						</div>
						<div class="mwm-section-07__content">
							<h1 class="mwm-section-07__title is-style-h-100">
								<span class="mwm-section-07__title-line"><?php the_title(); ?></span>
							</h1>
							<?php if ( has_excerpt() ) : ?>
								<div class="mwm-section-07__text-body is-style-b-200">
									<?php the_excerpt(); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="mwm-section-post__info">
					<div class="mwm-section-post__info-wrapper">
						<div class="mwm-section-post__meta">
							<div class="mwm-section-post__author">
								<?php
								// Obtener el ID del autor a mostrar: si hay un usuario marcado como "mostrar como autor en todos los posts", usar ese; si no, el autor del post
								$display_author_id = mwm_get_display_author_id();
								$author_id         = ( $display_author_id )
									? $display_author_id
									: get_post_field( 'post_author', get_the_ID() );
								
								if ( $author_id ) {
									$author_data = get_userdata( $author_id );
									
									if ( $author_data ) {
										// Obtener nombre del autor
										$author_name = $author_data->display_name;
										if ( empty( $author_name ) ) {
											$author_name = $author_data->user_nicename;
										}
										if ( empty( $author_name ) ) {
											$author_name = $author_data->user_login;
										}
										
										// Obtener campo ACF del puesto
										$user_job = get_field( 'user_job', 'user_' . $author_id );
										if ( empty( $user_job ) ) {
											$user_job = get_user_meta( $author_id, 'user_job', true );
										}
										?>
										<div class="mwm-section-post__author-avatar">
											<?php echo get_avatar( $author_id, 32 ); ?>
										</div>
										<div class="mwm-section-post__author-info">
											<div class="mwm-section-post__author-name is-style-b-200">
												<?php echo esc_html( $author_name ); ?>
											</div>
											<?php if ( ! empty( $user_job ) ) : ?>
												<div class="mwm-section-post__author-job is-style-b-300">
													<?php echo esc_html( $user_job ); ?>
												</div>
											<?php endif; ?>
										</div>
										<?php
									}
								}
								?>
							</div>
							<div class="mwm-section-post__categories">
								<?php
									$categories = get_the_category();
									if ( ! empty( $categories ) ) {
										$category = $categories[0];
										echo mwm_button( array(
											'url' => esc_url( get_category_link( $category->term_id ) ),
											'text' => esc_html( $category->name ),
											'variant' => 'soft',
											'color' => 'terciary',
											'size' => 'sm',
										) );
									}
								?>
							</div>
							<div class="mwm-section-post__date">
								<?php echo esc_html( get_the_date( 'd.m.Y', get_the_ID() ) ); ?>
							</div>
						</div>
						<div class="mwm-section-post__media">
							<?php
							$media_type = get_field( 'post_media_type' ) ?: 'thumbnail';
							$video_url = get_field( 'post_video_url' );
							$video_vimeo_url = get_field( 'post_video_vimeo_url' );
							$video_wordpress_url = get_field( 'post_video_wordpress_url' );

							// Determinar la imagen a mostrar
							$display_image = null;
							$image_alt = '';

							if ( $media_type === 'video' && $video_url ) {
								// Obtener miniatura de YouTube
								$youtube_thumbnail = mwm_get_youtube_thumbnail( $video_url );
								if ( $youtube_thumbnail ) {
									$display_image = $youtube_thumbnail;
								}
							} elseif ( $media_type === 'video-vimeo' ) {
								// Vimeo: usar la imagen destacada de la entrada como miniatura
								$thumbnail_id = get_post_thumbnail_id();
								if ( $thumbnail_id ) {
									$display_image = $thumbnail_id;
									$image_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
									if ( empty( $image_alt ) ) {
										$image_alt = esc_attr( get_the_title() );
									}
								}
							} elseif ( $media_type === 'thumbnail' ) {
								// Usar el thumbnail del post (imagen destacada)
								$thumbnail_id = get_post_thumbnail_id();
								if ( $thumbnail_id ) {
									$display_image = $thumbnail_id;
									$image_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
									if ( empty( $image_alt ) ) {
										$image_alt = esc_attr( get_the_title() );
									}
								}
							}

							// Preparar URL del video para Fancybox
							$fancybox_url = '';
							$has_video = false;

							if ( $media_type === 'video' && ! empty( $video_url ) ) {
								// Para YouTube, usar la URL original (no embed) según la documentación de Fancybox
								$fancybox_url = esc_url( $video_url );
								$has_video = true;
							} elseif ( $media_type === 'video-vimeo' && ! empty( $video_vimeo_url ) ) {
								// Vimeo: Fancybox abre la URL de Vimeo igual que YouTube
								$fancybox_url = esc_url( $video_vimeo_url );
								$has_video = true;
							} elseif ( $media_type === 'video-wordpress' && ! empty( $video_wordpress_url ) ) {
								// URL para Fancybox con video de WordPress
								$fancybox_url = esc_url( $video_wordpress_url );
								$has_video = true;
							}

							// Si no hay imagen ni video, usar imagen destacada como fallback
							if ( ! $display_image && ! $has_video ) {
								$thumbnail_id = get_post_thumbnail_id();
								if ( $thumbnail_id ) {
									$display_image = $thumbnail_id;
									$image_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
									if ( empty( $image_alt ) ) {
										$image_alt = get_the_title();
									}
								}
							}

							if ( ! $display_image && ! $has_video ) {
								// No mostrar nada si no hay media ni imagen destacada
							} else {
								// URL externa (YouTube) = string; ID de WordPress (thumbnail o fallback) = numérico
								$is_external_image = ( $display_image && ! is_numeric( $display_image ) );
								?>
								<div class="mwm-media-video-play-01">
									<div class="mwm-media-video-play-01__wrapper">
										<div class="mwm-media-video-play-01__media">
											<?php if ( $is_external_image ) : ?>
												<img 
													src="<?php echo esc_url( $display_image ); ?>" 
													alt="<?php echo esc_attr( $image_alt ); ?>"
													class="mwm-media-video-play-01__image"
												/>
											<?php elseif ( $display_image ) : ?>
												<?php
												$full_src = wp_get_attachment_image_url( $display_image, 'full' );
												if ( $full_src ) :
													?>
													<img
														src="<?php echo esc_url( $full_src ); ?>"
														alt="<?php echo esc_attr( $image_alt ); ?>"
														class="mwm-media-video-play-01__image"
														fetchpriority="high"
													/>
												<?php endif; ?>
											<?php elseif ( $has_video && $media_type === 'video-wordpress' && ! empty( $video_wordpress_url ) ) : ?>
												<?php
												// Mostrar el video HTML5 pausado como thumbnail para videos de WordPress
												?>
												<video 
													class="mwm-media-video-play-01__video-thumbnail"
													playsinline
													muted
													preload="metadata"
												>
													<source src="<?php echo esc_url( $video_wordpress_url ); ?>" type="video/mp4">
												</video>
											<?php elseif ( $has_video && ! $display_image ) : ?>
												<?php
												// Placeholder para videos sin imagen - el CSS puede manejar el fondo
												?>
												<div class="mwm-media-video-play-01__media-placeholder" aria-hidden="true"></div>
											<?php endif; ?>
										</div>
										
										<?php if ( $has_video && ! empty( $fancybox_url ) ) : ?>
											<div class="mwm-media-video-play-01__button-wrapper">
												<?php
												// Según la documentación de Fancybox, usar href directamente para ambos tipos
												// Fancybox detecta automáticamente si es YouTube o video HTML5
												echo mwm_button( array(
													'icon' => 'play',
													'url' => esc_url( $fancybox_url ),
													'variant' => 'fill-icon',
													'color' => 'secundary',
													'size' => 'xl',
													'attributes' => array(
														'data-fancybox' => 'video',
													),
												) );
												?>
											</div>
										<?php endif; ?>
									</div>
								</div>
								<?php
							}
							?>
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
						<?php
						$bilky_video_url = get_post_meta( get_the_ID(), 'bilky_video_url', true );
						$bilky_video_url = is_string( $bilky_video_url ) ? trim( $bilky_video_url ) : '';

						$bilky_video_embed_url = '';
						if ( $bilky_video_url !== '' ) {
							$parsed = wp_parse_url( $bilky_video_url );
							if ( ( ! is_array( $parsed ) || empty( $parsed['host'] ) ) && preg_match( '#^vimeo\.com/#i', $bilky_video_url ) ) {
								$parsed = wp_parse_url( 'https://' . $bilky_video_url );
							}
							$host   = isset( $parsed['host'] ) && is_string( $parsed['host'] ) ? strtolower( $parsed['host'] ) : '';
							$path   = isset( $parsed['path'] ) && is_string( $parsed['path'] ) ? $parsed['path'] : '';
							$query  = isset( $parsed['query'] ) && is_string( $parsed['query'] ) ? $parsed['query'] : '';

							$is_vimeo = $host === 'vimeo.com' || $host === 'www.vimeo.com' || $host === 'player.vimeo.com';
							if ( $is_vimeo ) {
								$vimeo_id = '';
								if ( preg_match( '#/video/(\d+)#', $path, $m ) ) {
									$vimeo_id = $m[1];
								} elseif ( preg_match( '#(\d+)$#', $path, $m ) ) {
									$vimeo_id = $m[1];
								}

								if ( $vimeo_id !== '' ) {
									$args = array( 'dnt' => '1' );
									if ( $query !== '' ) {
										parse_str( $query, $q );
										if ( is_array( $q ) ) {
											// Mantener solo parámetros relevantes para el player.
											foreach ( array( 'h', 'hash', 'autoplay', 'loop', 'muted' ) as $k ) {
												if ( isset( $q[ $k ] ) && $q[ $k ] !== '' ) {
													$args[ $k ] = $q[ $k ];
												}
											}
										}
									}

									$bilky_video_embed_url = add_query_arg( $args, 'https://player.vimeo.com/video/' . rawurlencode( $vimeo_id ) );
								}
							}
						}

						if ( $bilky_video_embed_url !== '' ) :
							?>
							<div class="mwm-video-embed iframe-wrapper">
								<iframe
									class="mwm-video-embed__iframe"
									src="<?php echo esc_url( $bilky_video_embed_url ); ?>"
									title="<?php echo esc_attr( sprintf( __( 'Vídeo: %s', 'bilky' ), get_the_title() ) ); ?>"
									loading="lazy"
									allow="autoplay; fullscreen; picture-in-picture"
									allowfullscreen
								></iframe>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if ( ! is_singular( 'centro_de_ayuda' ) ) : ?>
	<section class="mwm-section-08">
		<?php
		// Obtener categorías del post actual
		$current_post_id = get_the_ID();
		$categories = get_the_category( $current_post_id );
		$category_ids = array();

		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			foreach ( $categories as $category ) {
				// Excluir categorías no deseadas
				$excluded_slugs = array( 'sin-categoria', 'sin-categoria-en', 'uncategorized' );
				if ( ! in_array( $category->slug, $excluded_slugs, true ) ) {
					$category_ids[] = $category->term_id;
				}
			}
		}

		// Query para posts relacionados
		$related_args = array(
			'post_type'      => 'post',
			'posts_per_page' => 3,
			'post_status'    => 'publish',
			'post__not_in'   => array( $current_post_id ),
			'orderby'        => 'rand',
		);

		// Si hay categorías, filtrar por categorías relacionadas
		if ( ! empty( $category_ids ) ) {
			$related_args['tax_query'] = array(
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $category_ids,
				),
			);
		}

		$related_posts = get_posts( $related_args );
		$related_count = count( $related_posts );

		// Si no hay suficientes posts relacionados, rellenar con posts aleatorios
		if ( $related_count < 3 ) {
			$needed_count = 3 - $related_count;
			$excluded_ids = array( $current_post_id );

			// Agregar IDs de posts relacionados ya obtenidos
			foreach ( $related_posts as $post ) {
				$excluded_ids[] = $post->ID;
			}

			// Query para posts aleatorios
			$random_args = array(
				'post_type'      => 'post',
				'posts_per_page' => $needed_count,
				'post_status'    => 'publish',
				'post__not_in'   => $excluded_ids,
				'orderby'        => 'rand',
			);

			// Excluir categorías no deseadas también en posts aleatorios
			$excluded_category_slugs = array( 'sin-categoria', 'sin-categoria-en', 'uncategorized' );
			$excluded_category_ids = array();
			foreach ( $excluded_category_slugs as $slug ) {
				$term = get_term_by( 'slug', $slug, 'category' );
				if ( $term && ! is_wp_error( $term ) ) {
					$excluded_category_ids[] = $term->term_id;
				}
			}

			if ( ! empty( $excluded_category_ids ) ) {
				$random_args['tax_query'] = array(
					array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => $excluded_category_ids,
						'operator' => 'NOT IN',
					),
				);
			}

			$random_posts = get_posts( $random_args );
			$related_posts = array_merge( $related_posts, $random_posts );
		}

		// Limitar a 3 posts máximo
		$related_posts = array_slice( $related_posts, 0, 3 );

		if ( empty( $related_posts ) ) {
			return;
		}

		// Generar ID único para este slider
		$slider_id = 'mwm-section-08-slider-' . uniqid();

		$blog_url = get_permalink( get_option( 'page_for_posts' ) );
		if ( ! $blog_url ) {
			$blog_url = home_url();
		}
		?>
		<div class="mwm-section-08__wrapper">
			<div class="mwm-section-08__header">
				<h2 class="mwm-section-08__title is-style-h-400">
					<?php echo esc_html( __( 'También te puede interesar.', 'bilky' ) ); ?>
				</h2>
				<div class="mwm-section-08__button">
					<?php
					echo mwm_button(
						array(
							'text'    => __( 'Ver blog', 'bilky' ),
							'url'     => esc_url( $blog_url ),
							'variant' => 'fill',
							'color'   => 'terciary',
							'size'    => 'xl-md',
						)
					);
					?>
				</div>
			</div>

			<div class="swiper mwm-section-08__swiper" id="<?php echo esc_attr( $slider_id ); ?>">
				<div class="swiper-wrapper">
					<?php
					global $post;
					$original_post = $post;

					foreach ( $related_posts as $related_post ) {
						$post = $related_post;
						setup_postdata( $post );
						?>
						<div class="swiper-slide">
							<?php get_template_part( 'template-parts/mwm-card-05' ); ?>
						</div>
						<?php
					}

					$post = $original_post;
					wp_reset_postdata();
					?>
				</div>
				<div class="swiper-pagination"></div>
			</div>
		</div>
	</section>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
