<?php
/**
 * Single: webinar (estructura tipo single post).
 *
 * @package bilky
 */

get_header();

while ( have_posts() ) :
	the_post();
	$post_id = get_the_ID();

	$archive_link = get_post_type_archive_link( 'webinar' );
	if ( ! $archive_link ) {
		$archive_link = home_url( '/' );
	}

	$webinar_category_names = bilky_webinar_get_webinar_category_names( $post_id );
	$breadcrumb_2_text      = ! empty( $webinar_category_names )
		? implode( ' / ', $webinar_category_names )
		: mwm_webinar_get_archive_text( 'mwm_archive_breadcrumb_2', '' );

	$ponentes = get_the_terms( $post_id, 'ponente_webinar' );
	$show_video = bilky_webinar_should_show_video( $post_id );
	$embed_raw  = bilky_webinar_get_meta( $post_id, 'bilky_webinar_registration_embed', '' );
	$embed_raw  = is_string( $embed_raw ) ? $embed_raw : '';
	$video_url  = bilky_webinar_get_meta( $post_id, 'bilky_webinar_video_url', '' );
	$video_url  = is_string( $video_url ) ? trim( $video_url ) : '';

	$session = bilky_webinar_get_meta( $post_id, 'bilky_webinar_session_type', 'live' );
	if ( ! in_array( $session, array( 'live', 'recorded' ), true ) ) {
		$session = 'live';
	}

	$thumbnail_id = get_post_thumbnail_id();
	$hero_video_html = '';
	if ( $show_video && '' !== $video_url ) {
		$hero_video_html = bilky_webinar_get_video_embed_html( $video_url );
	}

	$has_ponentes = $ponentes && ! is_wp_error( $ponentes ) && ! empty( $ponentes );
	$has_registration = '' !== trim( $embed_raw );
	?>

<main class="mwm-main-container">

	<div class="wp-block-group has-white-background-color has-background is-right-after-header">
		<div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
			<div class="mwm-section-post mwm-section-post--webinar mwm-single-webinar mwm-section-07 mwm-section-07--center mwm-section-07--background-white">
				<div class="mwm-section-post__header">
					<div class="mwm-section-07__wrapper">
						<div class="mwm-section-07__breadcrumbs">
							<a href="<?php echo esc_url( $archive_link ); ?>" class="mwm-button mwm-button--outline mwm-button--secundary mwm-button--sm mwm-button--active-focus">
								<span class="mwm-button__dot mwm-button__dot--sm"></span>
								<span class="mwm-button__text"><?php echo esc_html( mwm_webinar_get_archive_text( 'mwm_archive_breadcrumb_1', __( 'Sesiones en directo', 'bilky' ) ) ); ?></span>
							</a>
							<?php if ( trim( (string) $breadcrumb_2_text ) !== '' ) : ?>
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
									$max_length = 60;
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
					<div class="mwm-section-post__info-wrapper mwm-section-post__info-wrapper--webinar-media">
						<div class="mwm-section-post__media mwm-single-webinar__hero-media">
							<?php if ( '' !== $hero_video_html ) : ?>
								<?php
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- oEmbed HTML.
								echo $hero_video_html;
								?>
							<?php elseif ( $thumbnail_id ) : ?>
								<?php
								$image_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
								if ( empty( $image_alt ) ) {
									$image_alt = get_the_title();
								}
								$full_src = wp_get_attachment_image_url( $thumbnail_id, 'full' );
								if ( $full_src ) {
									?>
									<img
										src="<?php echo esc_url( $full_src ); ?>"
										alt="<?php echo esc_attr( $image_alt ); ?>"
										class="mwm-single-webinar__hero-media-img"
										fetchpriority="high"
									/>
									<?php
								}
								?>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="mwm-section-post__body mwm-section-post__body--webinar">
					<?php if ( $has_ponentes ) : ?>
						<div class="mwm-section-post__sidebar mwm-section-post__sidebar--has-items">
							<div class="mwm-single-webinar__sidebar-ponentes">
								<div class="mwm-single-webinar__sidebar-ponentes-header is-style-b-100">
									<?php esc_html_e( 'Ponentes', 'bilky' ); ?>
								</div>
								<div class="mwm-single-webinar__sidebar-ponentes-divider" aria-hidden="true"></div>
								<ul class="mwm-single-webinar__sidebar-ponentes-list">
									<?php
									foreach ( $ponentes as $term ) :
										$p_img   = (int) get_term_meta( $term->term_id, 'bilky_ponente_image', true );
										$p_cargo = (string) get_term_meta( $term->term_id, 'bilky_ponente_cargo', true );
										?>
										<li class="mwm-single-webinar__sidebar-ponente">
											<div class="mwm-single-webinar__sidebar-ponente-avatar">
												<?php
												if ( $p_img > 0 ) {
													echo wp_get_attachment_image(
														$p_img,
														array( 50, 50 ),
														false,
														array(
															'class' => 'mwm-single-webinar__sidebar-ponente-img',
															'alt'   => esc_attr( $term->name ),
														)
													);
												} else {
													?>
													<span class="mwm-single-webinar__sidebar-ponente-placeholder" aria-hidden="true"></span>
													<?php
												}
												?>
											</div>
											<div class="mwm-single-webinar__sidebar-ponente-text">
												<p class="mwm-single-webinar__sidebar-ponente-name is-style-b-200"><?php echo esc_html( $term->name ); ?></p>
												<?php if ( '' !== trim( $p_cargo ) ) : ?>
													<p class="mwm-single-webinar__sidebar-ponente-cargo is-style-b-300"><?php echo esc_html( $p_cargo ); ?></p>
												<?php endif; ?>
											</div>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					<?php endif; ?>
					<div class="mwm-section-post__content">
						<?php if ( $has_registration ) : ?>
							<section class="mwm-single-webinar__content-registration" aria-label="<?php esc_attr_e( 'Inscripción al webinar', 'bilky' ); ?>">
								<div class="mwm-single-webinar__embed">
									<?php
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses + shortcodes en helper.
									echo bilky_webinar_render_registration_embed( $embed_raw );
									?>
								</div>
							</section>
						<?php endif; ?>

						<?php the_content(); ?>

						<?php
						$bilky_video_url = get_post_meta( $post_id, 'bilky_video_url', true );
						$bilky_video_url = is_string( $bilky_video_url ) ? trim( $bilky_video_url ) : '';

						$bilky_video_embed_url = '';
						if ( '' !== $bilky_video_url ) {
							$parsed = wp_parse_url( $bilky_video_url );
							if ( ( ! is_array( $parsed ) || empty( $parsed['host'] ) ) && preg_match( '#^vimeo\.com/#i', $bilky_video_url ) ) {
								$parsed = wp_parse_url( 'https://' . $bilky_video_url );
							}
							$host  = isset( $parsed['host'] ) && is_string( $parsed['host'] ) ? strtolower( $parsed['host'] ) : '';
							$path  = isset( $parsed['path'] ) && is_string( $parsed['path'] ) ? $parsed['path'] : '';
							$query = isset( $parsed['query'] ) && is_string( $parsed['query'] ) ? $parsed['query'] : '';

							$is_vimeo = 'vimeo.com' === $host || 'www.vimeo.com' === $host || 'player.vimeo.com' === $host;
							if ( $is_vimeo ) {
								$vimeo_id = '';
								if ( preg_match( '#/video/(\d+)#', $path, $m ) ) {
									$vimeo_id = $m[1];
								} elseif ( preg_match( '#(\d+)$#', $path, $m ) ) {
									$vimeo_id = $m[1];
								}

								if ( '' !== $vimeo_id ) {
									$args = array( 'dnt' => '1' );
									if ( '' !== $query ) {
										parse_str( $query, $q );
										if ( is_array( $q ) ) {
											foreach ( array( 'h', 'hash', 'autoplay', 'loop', 'muted' ) as $k ) {
												if ( isset( $q[ $k ] ) && '' !== $q[ $k ] ) {
													$args[ $k ] = $q[ $k ];
												}
											}
										}
									}

									$bilky_video_embed_url = add_query_arg( $args, 'https://player.vimeo.com/video/' . rawurlencode( $vimeo_id ) );
								}
							}
						}

						if ( '' !== $bilky_video_embed_url ) :
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

</main>

	<?php
endwhile;

get_footer();
