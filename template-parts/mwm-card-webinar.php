<?php
/**
 * Card de webinar (archive).
 *
 * @package bilky
 */

$post_id = get_the_ID();
$session = bilky_webinar_get_meta( $post_id, 'bilky_webinar_session_type', 'live' );
if ( ! in_array( $session, array( 'live', 'recorded' ), true ) ) {
	$session = 'live';
}

$is_live = ( 'live' === $session );

$ponente = bilky_webinar_get_primary_ponente( $post_id );

$speaker_name = '';
$speaker_role = '';
$avatar_id      = 0;

if ( $ponente ) {
	$speaker_name = $ponente['name'];
	$speaker_role = $ponente['cargo'];
	$avatar_id    = (int) $ponente['image_id'];
} else {
	$speaker_name = bilky_webinar_get_meta( $post_id, 'bilky_webinar_speaker_name', '' );
	$speaker_name = is_string( $speaker_name ) ? $speaker_name : '';
	if ( '' === trim( $speaker_name ) ) {
		$speaker_name = get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) );
	}
	$speaker_role = bilky_webinar_get_meta( $post_id, 'bilky_webinar_speaker_role', '' );
	$speaker_role = is_string( $speaker_role ) ? $speaker_role : '';
	$avatar_id    = bilky_webinar_get_meta( $post_id, 'bilky_webinar_speaker_avatar', 0 );
	$avatar_id    = (int) $avatar_id;
}

if ( $avatar_id <= 0 && has_post_thumbnail() ) {
	$avatar_id = (int) get_post_thumbnail_id();
}

$duration = bilky_webinar_get_formatted_duration( $post_id );

$start_ts = bilky_webinar_get_start_timestamp( $post_id );
$session_label = $is_live
	? __( 'Sesión en directo', 'bilky' )
	: __( 'Sesión grabada', 'bilky' );

if ( $is_live ) {
	$calendar_label = $session_label;
} elseif ( $start_ts ) {
	$calendar_label = date_i18n(
		get_option( 'date_format' ) . ' ' . get_option( 'time_format' ),
		$start_ts
	);
} else {
	$calendar_label = $session_label;
}

$show_watch_cta = ( 'recorded' === $session ) || ( $is_live && bilky_webinar_has_started( $post_id ) );

$cta_text = $show_watch_cta
	? __( 'Ver sesión gratis', 'bilky' )
	: __( 'Regístrate gratis', 'bilky' );

$cta_btn_override = bilky_webinar_get_meta( $post_id, 'bilky_webinar_card_button_text', '' );
$cta_btn_override = is_string( $cta_btn_override ) ? trim( $cta_btn_override ) : '';
if ( '' !== $cta_btn_override ) {
	$cta_text = $cta_btn_override;
}

$cta_url = get_permalink( $post_id );

$category_names = bilky_webinar_get_webinar_category_names( $post_id );

$card_mod = $is_live ? 'mwm-card-webinar--live' : 'mwm-card-webinar--recorded';

$card_color = bilky_webinar_get_meta( $post_id, 'bilky_webinar_card_color', 'default' );
$card_color = is_string( $card_color ) ? trim( $card_color ) : 'default';
if ( '' === $card_color ) {
	$card_color = 'default';
}
$theme_class = bilky_webinar_get_card_theme_class( $card_color );

$meta_cal_override = bilky_webinar_get_meta( $post_id, 'bilky_webinar_card_meta_calendar', '' );
$meta_cal_override = is_string( $meta_cal_override ) ? trim( $meta_cal_override ) : '';
if ( '' !== $meta_cal_override ) {
	$calendar_label = $meta_cal_override;
}

$meta_dur_override = bilky_webinar_get_meta( $post_id, 'bilky_webinar_card_meta_duration', '' );
$meta_dur_override = is_string( $meta_dur_override ) ? trim( $meta_dur_override ) : '';
if ( '' !== $meta_dur_override ) {
	$duration = $meta_dur_override;
}

$title_text = get_the_title();

$avatar_alt = $speaker_name ? $speaker_name : $title_text;
?>
<article class="mwm-card-webinar <?php echo esc_attr( trim( $card_mod . ' ' . $theme_class ) ); ?>">
	<div class="mwm-card-webinar__inner">
		<div class="mwm-card-webinar__speaker">
			<div class="mwm-card-webinar__avatar">
				<?php
				if ( $avatar_id > 0 ) {
					echo wp_get_attachment_image(
						$avatar_id,
						'thumbnail',
						false,
						array(
							'class' => 'mwm-card-webinar__avatar-img',
							'alt'   => esc_attr( $avatar_alt ),
						)
					);
				} else {
					?>
					<span class="mwm-card-webinar__avatar-placeholder" aria-hidden="true"></span>
					<?php
				}
				?>
			</div>
			<div class="mwm-card-webinar__speaker-text">
				<p class="mwm-card-webinar__speaker-name is-style-b-100"><?php echo esc_html( $speaker_name ); ?></p>
				<?php if ( '' !== trim( $speaker_role ) ) : ?>
					<p class="mwm-card-webinar__speaker-role is-style-b-200"><?php echo esc_html( $speaker_role ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<div class="mwm-card-webinar__divider" aria-hidden="true"></div>

		<div class="mwm-card-webinar__content">
			<?php if ( ! empty( $category_names ) ) : ?>
				<div class="mwm-card-webinar__categories">
					<?php foreach ( $category_names as $cat_label ) : ?>
						<p class="mwm-card-webinar__category is-style-b-200"><?php echo esc_html( $cat_label ); ?></p>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<h3 class="mwm-card-webinar__title is-style-h-400"><?php echo esc_html( $title_text ); ?></h3>

			<?php if ( has_excerpt( $post_id ) ) : ?>
				<p class="mwm-card-webinar__excerpt is-style-b-200"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
			<?php endif; ?>

			<div class="mwm-card-webinar__content-footer">
				<div class="mwm-card-webinar__divider" aria-hidden="true"></div>
				<div class="mwm-card-webinar__meta">
					<span class="mwm-card-webinar__meta-item">
						<i class="fa-solid fa-calendar mwm-card-webinar__meta-icon" aria-hidden="true"></i>
						<span class="is-style-b-200"><?php echo esc_html( $calendar_label ); ?></span>
					</span>
					<span class="mwm-card-webinar__meta-sep" aria-hidden="true"></span>
					<span class="mwm-card-webinar__meta-item mwm-card-webinar__meta-item--end">
						<i class="fa-solid fa-clock mwm-card-webinar__meta-icon" aria-hidden="true"></i>
						<span class="is-style-b-200"><?php echo esc_html( $duration ); ?></span>
					</span>
				</div>
			</div>
		</div>

		<div class="mwm-card-webinar__cta">
			<?php
			echo mwm_button(
				array(
					'text'      => $cta_text,
					'url'       => esc_url( $cta_url ),
					'variant'   => 'fill',
					'color'     => $is_live ? 'primary' : 'terciary',
					'size'      => 'xl-md',
					'class'     => 'mwm-card-webinar__button',
					'show_icon' => false,
				)
			);
			?>
		</div>
	</div>
</article>
