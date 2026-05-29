<?php
/**
 * Campos (ACF / fallback) para el CPT webinar.
 *
 * ACF: el registro local debe hacerse en acf/init (no al cargar el archivo).
 *
 * @package bilky
 */

if ( ! function_exists( 'bilky_webinar_register_acf_field_group' ) ) {
	/**
	 * Registra el grupo de campos ACF (claves v2 para no chocar con grupos antiguos en BD).
	 */
	function bilky_webinar_register_acf_field_group() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group(
			array(
				'key'                   => 'group_bilky_webinar_v2',
				'title'                 => __( 'Webinar — Detalle de sesión', 'bilky' ),
				'fields'                => array(
					array(
						'key'           => 'field_bwk_v2_session_type',
						'label'         => __( 'Tipo de sesión', 'bilky' ),
						'name'          => 'bilky_webinar_session_type',
						'type'          => 'select',
						'choices'       => array(
							'live'     => __( 'En directo', 'bilky' ),
							'recorded' => __( 'Grabada', 'bilky' ),
						),
						'default_value' => 'live',
						'instructions'  => __( 'Define qué campos se muestran: en directo (fecha, inscripción antes del evento); grabada (reproducción del vídeo).', 'bilky' ),
						'return_format' => 'value',
					),
					array(
						'key'               => 'field_bwk_v2_start_datetime',
						'label'             => __( 'Fecha y hora de inicio', 'bilky' ),
						'name'              => 'bilky_webinar_start_datetime',
						'type'              => 'date_time_picker',
						'required'          => 0,
						'display_format'    => 'd/m/Y H:i',
						'return_format'     => 'Y-m-d H:i:s',
						'first_day'         => 1,
						'instructions'      => __( 'Solo sesiones en directo. Opcional: a partir de esta fecha se mostrará el vídeo en lugar del formulario de inscripción.', 'bilky' ),
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_bwk_v2_session_type',
									'operator' => '==',
									'value'    => 'live',
								),
							),
						),
					),
					array(
						'key'           => 'field_bwk_v2_duration_hours',
						'label'         => __( 'Duración — horas', 'bilky' ),
						'name'          => 'bilky_webinar_duration_hours',
						'type'          => 'number',
						'min'           => 0,
						'max'           => 99,
						'step'          => 1,
						'default_value' => 1,
					),
					array(
						'key'           => 'field_bwk_v2_duration_minutes',
						'label'         => __( 'Duración — minutos', 'bilky' ),
						'name'          => 'bilky_webinar_duration_minutes',
						'type'          => 'number',
						'min'           => 0,
						'max'           => 59,
						'step'          => 1,
						'default_value' => 0,
						'instructions'  => __( 'Se mostrará como “1h 30min” (en directo y grabada).', 'bilky' ),
					),
					array(
						'key'               => 'field_bwk_v2_registration_embed',
						'label'             => __( 'Formulario de inscripción', 'bilky' ),
						'name'              => 'bilky_webinar_registration_embed',
						'type'              => 'textarea',
						'rows'              => 6,
						'instructions'      => __( 'Solo sesiones en directo: shortcode (p. ej. Contact Form 7) o iframe antes de que comience la emisión.', 'bilky' ),
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_bwk_v2_session_type',
									'operator' => '==',
									'value'    => 'live',
								),
							),
						),
					),
					array(
						'key'          => 'field_bwk_v2_video_url',
						'label'        => __( 'URL del vídeo (Vimeo, YouTube, etc.)', 'bilky' ),
						'name'         => 'bilky_webinar_video_url',
						'type'         => 'url',
						'instructions' => __( 'Grabada: reproductor siempre visible. En directo: visible a partir de la fecha de inicio.', 'bilky' ),
					),
					array(
						'key'           => 'field_bwk_v2_card_color',
						'label'         => __( 'Color de la tarjeta (listado)', 'bilky' ),
						'name'          => 'bilky_webinar_card_color',
						'type'          => 'select',
						'choices'       => array(
							'default' => __( 'Por defecto (azul en directo, gris grabada)', 'bilky' ),
							'blue'    => __( 'Azul', 'bilky' ),
							'grey'    => __( 'Gris claro', 'bilky' ),
						),
						'default_value' => 'default',
						'return_format' => 'value',
						'instructions'  => __( 'Sustituye el color automático de la tarjeta en el archivo de webinars.', 'bilky' ),
					),
					array(
						'key'          => 'field_bwk_v2_card_meta_calendar',
						'label'        => __( 'Texto meta (fecha / calendario)', 'bilky' ),
						'name'         => 'bilky_webinar_card_meta_calendar',
						'type'         => 'text',
						'instructions' => __( 'Opcional. Si lo rellenas, sustituye el texto junto al icono de calendario en la tarjeta.', 'bilky' ),
					),
					array(
						'key'          => 'field_bwk_v2_card_meta_duration',
						'label'        => __( 'Texto meta (duración)', 'bilky' ),
						'name'         => 'bilky_webinar_card_meta_duration',
						'type'         => 'text',
						'instructions' => __( 'Opcional. Si lo rellenas, sustituye la duración mostrada junto al icono de reloj.', 'bilky' ),
					),
					array(
						'key'          => 'field_bwk_v2_card_button_text',
						'label'        => __( 'Texto del botón (tarjeta)', 'bilky' ),
						'name'         => 'bilky_webinar_card_button_text',
						'type'         => 'text',
						'instructions' => __( 'Opcional. Texto del CTA en la tarjeta del listado. Si está vacío, se usan los textos por defecto (ver sesión / registro).', 'bilky' ),
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'webinar',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'active'                => true,
			)
		);
	}
}
add_action( 'acf/init', 'bilky_webinar_register_acf_field_group', 5 );

if ( ! function_exists( 'bilky_webinar_details_metabox_callback' ) ) {
	/**
	 * Contenido del metabox de webinar (solo sin ACF).
	 *
	 * @param WP_Post $post Post actual.
	 */
	function bilky_webinar_details_metabox_callback( $post ) {
		if ( ! $post || ! isset( $post->ID ) ) {
			return;
		}
		wp_nonce_field( 'bilky_webinar_details_save', 'bilky_webinar_details_nonce' );
		$post_id = (int) $post->ID;

		$session   = (string) get_post_meta( $post_id, 'bilky_webinar_session_type', true );
		$start     = (string) get_post_meta( $post_id, 'bilky_webinar_start_datetime', true );
		$hours     = get_post_meta( $post_id, 'bilky_webinar_duration_hours', true );
		$minutes   = get_post_meta( $post_id, 'bilky_webinar_duration_minutes', true );
		$embed     = (string) get_post_meta( $post_id, 'bilky_webinar_registration_embed', true );
		$video_url = (string) get_post_meta( $post_id, 'bilky_webinar_video_url', true );
		$card_color = (string) get_post_meta( $post_id, 'bilky_webinar_card_color', true );
		$meta_cal  = (string) get_post_meta( $post_id, 'bilky_webinar_card_meta_calendar', true );
		$meta_dur  = (string) get_post_meta( $post_id, 'bilky_webinar_card_meta_duration', true );
		$btn_text  = (string) get_post_meta( $post_id, 'bilky_webinar_card_button_text', true );

		if ( '' === $session ) {
			$session = 'live';
		}
		if ( '' === $hours && '' === $minutes ) {
			$hours = 1;
		} else {
			$hours = '' === $hours ? 0 : (int) $hours;
		}
		$minutes = '' === $minutes ? 0 : max( 0, min( 59, (int) $minutes ) );

		echo '<p><label for="bilky_webinar_session_type">' . esc_html__( 'Tipo de sesión', 'bilky' ) . '</label><br />';
		echo '<select id="bilky_webinar_session_type" name="bilky_webinar_session_type">';
		echo '<option value="live"' . selected( $session, 'live', false ) . '>' . esc_html__( 'En directo', 'bilky' ) . '</option>';
		echo '<option value="recorded"' . selected( $session, 'recorded', false ) . '>' . esc_html__( 'Grabada', 'bilky' ) . '</option>';
		echo '</select></p>';
		echo '<p class="description">' . esc_html__( 'En directo: fecha de inicio e inscripción. Grabada: prioriza la URL del vídeo. La duración aplica a ambos tipos.', 'bilky' ) . '</p>';

		$live_display = ( 'live' === $session ) ? '' : 'display:none;';
		echo '<div id="bilky-webinar-live-fields" class="bilky-webinar-live-fields" style="' . esc_attr( $live_display ) . '">';

		echo '<p><label for="bilky_webinar_start_datetime">' . esc_html__( 'Fecha y hora de inicio (opcional)', 'bilky' ) . '</label><br />';
		echo '<input type="datetime-local" class="widefat" id="bilky_webinar_start_datetime" name="bilky_webinar_start_datetime" value="' . esc_attr( bilky_webinar_metabox_datetime_local_value( $start ) ) . '"></p>';

		echo '<p><label for="bilky_webinar_registration_embed">' . esc_html__( 'Formulario de inscripción (shortcode o iframe)', 'bilky' ) . '</label><br />';
		echo '<textarea class="widefat" rows="6" id="bilky_webinar_registration_embed" name="bilky_webinar_registration_embed">' . esc_textarea( $embed ) . '</textarea></p>';

		echo '</div>';

		echo '<p><label for="bilky_webinar_duration_hours">' . esc_html__( 'Duración — horas', 'bilky' ) . '</label><br />';
		echo '<input type="number" min="0" max="99" class="small-text" id="bilky_webinar_duration_hours" name="bilky_webinar_duration_hours" value="' . esc_attr( (string) $hours ) . '"></p>';

		echo '<p><label for="bilky_webinar_duration_minutes">' . esc_html__( 'Duración — minutos', 'bilky' ) . '</label><br />';
		echo '<input type="number" min="0" max="59" class="small-text" id="bilky_webinar_duration_minutes" name="bilky_webinar_duration_minutes" value="' . esc_attr( (string) $minutes ) . '"></p>';

		echo '<p><label for="bilky_webinar_video_url">' . esc_html__( 'URL del vídeo', 'bilky' ) . '</label><br />';
		echo '<input type="url" class="widefat" id="bilky_webinar_video_url" name="bilky_webinar_video_url" value="' . esc_attr( $video_url ) . '"></p>';

		if ( '' === $card_color ) {
			$card_color = 'default';
		}
		$card_color_allowed = array( 'default', 'blue', 'grey' );
		if ( ! in_array( $card_color, $card_color_allowed, true ) ) {
			$card_color = 'default';
		}
		echo '<p><label for="bilky_webinar_card_color">' . esc_html__( 'Color de la tarjeta (listado)', 'bilky' ) . '</label><br />';
		echo '<select id="bilky_webinar_card_color" class="widefat" name="bilky_webinar_card_color">';
		$color_opts = array(
			'default' => __( 'Por defecto (azul en directo, gris grabada)', 'bilky' ),
			'blue'    => __( 'Azul', 'bilky' ),
			'grey'    => __( 'Gris claro', 'bilky' ),
		);
		foreach ( $color_opts as $val => $lab ) {
			echo '<option value="' . esc_attr( $val ) . '"' . selected( $card_color, $val, false ) . '>' . esc_html( $lab ) . '</option>';
		}
		echo '</select></p>';

		echo '<p><label for="bilky_webinar_card_meta_calendar">' . esc_html__( 'Texto meta (fecha / calendario)', 'bilky' ) . '</label><br />';
		echo '<input type="text" class="widefat" id="bilky_webinar_card_meta_calendar" name="bilky_webinar_card_meta_calendar" value="' . esc_attr( $meta_cal ) . '"></p>';

		echo '<p><label for="bilky_webinar_card_meta_duration">' . esc_html__( 'Texto meta (duración)', 'bilky' ) . '</label><br />';
		echo '<input type="text" class="widefat" id="bilky_webinar_card_meta_duration" name="bilky_webinar_card_meta_duration" value="' . esc_attr( $meta_dur ) . '"></p>';

		echo '<p><label for="bilky_webinar_card_button_text">' . esc_html__( 'Texto del botón (tarjeta)', 'bilky' ) . '</label><br />';
		echo '<input type="text" class="widefat" id="bilky_webinar_card_button_text" name="bilky_webinar_card_button_text" value="' . esc_attr( $btn_text ) . '"></p>';

		echo '<p class="description">' . esc_html__( 'Asigna ponentes y categorías desde las cajas de la derecha.', 'bilky' ) . '</p>';
	}
}

if ( ! function_exists( 'bilky_webinar_enqueue_metabox_fallback_script' ) ) {
	/**
	 * Muestra/oculta campos solo para “en directo” (metabox sin ACF).
	 *
	 * @param string $hook_suffix Pantalla actual.
	 */
	function bilky_webinar_enqueue_metabox_fallback_script( $hook_suffix ) {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}
		if ( 'post.php' !== $hook_suffix && 'post-new.php' !== $hook_suffix ) {
			return;
		}
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen || 'webinar' !== $screen->post_type ) {
			return;
		}
		wp_register_script( 'bilky-webinar-meta-fallback', false, array(), false, true );
		wp_enqueue_script( 'bilky-webinar-meta-fallback' );
		wp_add_inline_script(
			'bilky-webinar-meta-fallback',
			'(function(){function bilkyWebinarToggleLive(){var s=document.getElementById("bilky_webinar_session_type");var b=document.getElementById("bilky-webinar-live-fields");if(!s||!b){return;}b.style.display=s.value==="live"?"":"none";}function bilkyWebinarBind(){bilkyWebinarToggleLive();var s=document.getElementById("bilky_webinar_session_type");if(s){s.addEventListener("change",bilkyWebinarToggleLive);}}if(document.readyState==="loading"){document.addEventListener("DOMContentLoaded",bilkyWebinarBind);}else{bilkyWebinarBind();}})();'
		);
	}
}
add_action( 'admin_enqueue_scripts', 'bilky_webinar_enqueue_metabox_fallback_script', 20 );

if ( ! function_exists( 'bilky_webinar_add_metabox_if_no_acf' ) ) {
	/**
	 * Metabox nativo solo si ACF no está disponible.
	 */
	function bilky_webinar_add_metabox_if_no_acf() {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}
		add_meta_box(
			'bilky_webinar_details_metabox',
			__( 'Detalle del webinar', 'bilky' ),
			'bilky_webinar_details_metabox_callback',
			'webinar',
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'bilky_webinar_add_metabox_if_no_acf', 10 );

if ( ! function_exists( 'bilky_webinar_save_metabox_fallback' ) ) {
	/**
	 * Guardado del metabox sin ACF.
	 *
	 * @param int $post_id Post ID.
	 */
	function bilky_webinar_save_metabox_fallback( $post_id ) {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}
		$post_id = (int) $post_id;
		if ( $post_id <= 0 ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( ! isset( $_POST['bilky_webinar_details_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bilky_webinar_details_nonce'] ) ), 'bilky_webinar_details_save' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$session = isset( $_POST['bilky_webinar_session_type'] ) ? sanitize_text_field( wp_unslash( $_POST['bilky_webinar_session_type'] ) ) : 'live';
		if ( ! in_array( $session, array( 'live', 'recorded' ), true ) ) {
			$session = 'live';
		}
		update_post_meta( $post_id, 'bilky_webinar_session_type', $session );

		if ( isset( $_POST['bilky_webinar_start_datetime'] ) ) {
			$raw = trim( (string) wp_unslash( $_POST['bilky_webinar_start_datetime'] ) );
			if ( '' === $raw ) {
				delete_post_meta( $post_id, 'bilky_webinar_start_datetime' );
			} else {
				$parsed = bilky_webinar_parse_datetime_local_to_storage( $raw );
				if ( '' !== $parsed ) {
					update_post_meta( $post_id, 'bilky_webinar_start_datetime', $parsed );
				}
			}
		}

		$h = isset( $_POST['bilky_webinar_duration_hours'] ) ? (int) $_POST['bilky_webinar_duration_hours'] : 0;
		$m = isset( $_POST['bilky_webinar_duration_minutes'] ) ? (int) $_POST['bilky_webinar_duration_minutes'] : 0;
		$h = max( 0, min( 99, $h ) );
		$m = max( 0, min( 59, $m ) );
		update_post_meta( $post_id, 'bilky_webinar_duration_hours', $h );
		update_post_meta( $post_id, 'bilky_webinar_duration_minutes', $m );

		if ( isset( $_POST['bilky_webinar_registration_embed'] ) ) {
			$embed = wp_unslash( $_POST['bilky_webinar_registration_embed'] );
			$embed = is_string( $embed ) ? wp_check_invalid_utf8( $embed ) : '';
			update_post_meta( $post_id, 'bilky_webinar_registration_embed', $embed );
		}

		if ( isset( $_POST['bilky_webinar_video_url'] ) ) {
			$url = trim( (string) wp_unslash( $_POST['bilky_webinar_video_url'] ) );
			$url = '' !== $url ? esc_url_raw( $url ) : '';
			if ( '' === $url ) {
				delete_post_meta( $post_id, 'bilky_webinar_video_url' );
			} else {
				update_post_meta( $post_id, 'bilky_webinar_video_url', $url );
			}
		}

		$allowed_colors = array( 'default', 'blue', 'grey' );
		$cc              = isset( $_POST['bilky_webinar_card_color'] ) ? sanitize_text_field( wp_unslash( $_POST['bilky_webinar_card_color'] ) ) : 'default';
		if ( ! in_array( $cc, $allowed_colors, true ) ) {
			$cc = 'default';
		}
		if ( 'default' === $cc ) {
			delete_post_meta( $post_id, 'bilky_webinar_card_color' );
		} else {
			update_post_meta( $post_id, 'bilky_webinar_card_color', $cc );
		}

		if ( isset( $_POST['bilky_webinar_card_meta_calendar'] ) ) {
			$mc = sanitize_text_field( wp_unslash( $_POST['bilky_webinar_card_meta_calendar'] ) );
			if ( '' === $mc ) {
				delete_post_meta( $post_id, 'bilky_webinar_card_meta_calendar' );
			} else {
				update_post_meta( $post_id, 'bilky_webinar_card_meta_calendar', $mc );
			}
		}
		if ( isset( $_POST['bilky_webinar_card_meta_duration'] ) ) {
			$md = sanitize_text_field( wp_unslash( $_POST['bilky_webinar_card_meta_duration'] ) );
			if ( '' === $md ) {
				delete_post_meta( $post_id, 'bilky_webinar_card_meta_duration' );
			} else {
				update_post_meta( $post_id, 'bilky_webinar_card_meta_duration', $md );
			}
		}
		if ( isset( $_POST['bilky_webinar_card_button_text'] ) ) {
			$bt = sanitize_text_field( wp_unslash( $_POST['bilky_webinar_card_button_text'] ) );
			if ( '' === $bt ) {
				delete_post_meta( $post_id, 'bilky_webinar_card_button_text' );
			} else {
				update_post_meta( $post_id, 'bilky_webinar_card_button_text', $bt );
			}
		}
	}
}
add_action( 'save_post_webinar', 'bilky_webinar_save_metabox_fallback', 10 );
