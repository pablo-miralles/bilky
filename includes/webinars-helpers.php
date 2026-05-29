<?php
/**
 * Helpers del CPT webinar: duración, fechas, embeds y ponentes.
 *
 * @package bilky
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'bilky_webinar_metabox_datetime_local_value' ) ) {
	/**
	 * Convierte Y-m-d H:i:s a valor para input datetime-local (metabox sin ACF).
	 *
	 * @param string $stored Valor guardado.
	 * @return string
	 */
	function bilky_webinar_metabox_datetime_local_value( $stored ) {
		$stored = trim( (string) $stored );
		if ( '' === $stored ) {
			return '';
		}
		try {
			$dt = new DateTimeImmutable( $stored, wp_timezone() );
			return $dt->format( 'Y-m-d\TH:i' );
		} catch ( Exception $e ) {
			return '';
		}
	}
}

if ( ! function_exists( 'bilky_webinar_parse_datetime_local_to_storage' ) ) {
	/**
	 * Convierte datetime-local a Y-m-d H:i:s en zona del sitio.
	 *
	 * @param string $raw Valor del input.
	 * @return string Cadena vacía si no válido.
	 */
	function bilky_webinar_parse_datetime_local_to_storage( $raw ) {
		$raw = trim( (string) $raw );
		if ( '' === $raw ) {
			return '';
		}
		try {
			$dt = date_create_from_format( 'Y-m-d\TH:i', $raw, wp_timezone() );
			if ( ! $dt ) {
				return '';
			}
			return $dt->format( 'Y-m-d H:i:s' );
		} catch ( Exception $e ) {
			return '';
		}
	}
}

if ( ! function_exists( 'bilky_webinar_get_meta' ) ) {
	/**
	 * Obtiene un meta del webinar (ACF o post meta).
	 *
	 * @param int    $post_id Post ID.
	 * @param string $key     Clave del campo.
	 * @param mixed  $default Valor por defecto.
	 * @return mixed
	 */
	function bilky_webinar_get_meta( $post_id, $key, $default = '' ) {
		$post_id = (int) $post_id;
		if ( $post_id <= 0 ) {
			return $default;
		}
		if ( function_exists( 'get_field' ) ) {
			$val = get_field( $key, $post_id );
			if ( null !== $val && '' !== $val ) {
				if ( 'bilky_webinar_speaker_avatar' === $key && is_array( $val ) && isset( $val['ID'] ) ) {
					return (int) $val['ID'];
				}
				return $val;
			}
		}
		$meta = get_post_meta( $post_id, $key, true );
		if ( '' === $meta || null === $meta ) {
			return $default;
		}
		return $meta;
	}
}

if ( ! function_exists( 'bilky_webinar_format_duration' ) ) {
	/**
	 * Formatea horas y minutos como "1h 30min", "45min", "2h".
	 *
	 * @param int $hours   Horas (0–99).
	 * @param int $minutes Minutos (0–59).
	 * @return string
	 */
	function bilky_webinar_format_duration( $hours, $minutes ) {
		$hours   = max( 0, (int) $hours );
		$minutes = max( 0, min( 59, (int) $minutes ) );

		if ( 0 === $hours && 0 === $minutes ) {
			return '';
		}

		$parts = array();
		if ( $hours > 0 ) {
			$parts[] = sprintf(
				/* translators: %d: hours */
				__( '%dh', 'bilky' ),
				$hours
			);
		}
		if ( $minutes > 0 ) {
			$parts[] = sprintf(
				/* translators: %d: minutes */
				__( '%dmin', 'bilky' ),
				$minutes
			);
		}

		return implode( ' ', $parts );
	}
}

if ( ! function_exists( 'bilky_webinar_get_duration_parts' ) ) {
	/**
	 * Lee horas y minutos de metadatos.
	 *
	 * @param int $post_id Post ID.
	 * @return int[] { hours, minutes }
	 */
	function bilky_webinar_get_duration_parts( $post_id ) {
		$h = bilky_webinar_get_meta( $post_id, 'bilky_webinar_duration_hours', '' );
		$m = bilky_webinar_get_meta( $post_id, 'bilky_webinar_duration_minutes', '' );
		$h = ( '' === $h || null === $h ) ? 0 : max( 0, (int) $h );
		$m = ( '' === $m || null === $m ) ? 0 : max( 0, min( 59, (int) $m ) );
		return array( $h, $m );
	}
}

if ( ! function_exists( 'bilky_webinar_get_formatted_duration' ) ) {
	/**
	 * Duración formateada para UI; compatibilidad con meta antiguo texto.
	 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	function bilky_webinar_get_formatted_duration( $post_id ) {
		$h = bilky_webinar_get_meta( $post_id, 'bilky_webinar_duration_hours', '' );
		$m = bilky_webinar_get_meta( $post_id, 'bilky_webinar_duration_minutes', '' );

		if ( ( '' === $h || null === $h ) && ( '' === $m || null === $m ) ) {
			$legacy = bilky_webinar_get_meta( $post_id, 'bilky_webinar_duration', '' );
			$legacy = is_string( $legacy ) ? trim( $legacy ) : '';
			if ( '' !== $legacy ) {
				return $legacy;
			}
			return '1h';
		}

		list( $hours, $minutes ) = bilky_webinar_get_duration_parts( $post_id );
		$formatted = bilky_webinar_format_duration( $hours, $minutes );
		return '' !== $formatted ? $formatted : '1h';
	}
}

if ( ! function_exists( 'bilky_webinar_get_start_timestamp' ) ) {
	/**
	 * Timestamp UTC de inicio o null si no hay fecha.
	 *
	 * @param int $post_id Post ID.
	 * @return int|null
	 */
	function bilky_webinar_get_start_timestamp( $post_id ) {
		$raw = bilky_webinar_get_meta( $post_id, 'bilky_webinar_start_datetime', '' );
		if ( ! is_string( $raw ) || '' === trim( $raw ) ) {
			return null;
		}
		$raw = trim( $raw );
		$dt  = DateTimeImmutable::createFromFormat( 'Y-m-d H:i:s', $raw, wp_timezone() );
		if ( $dt instanceof DateTimeImmutable ) {
			return $dt->getTimestamp();
		}
		try {
			$dt = new DateTimeImmutable( $raw, wp_timezone() );
			return $dt->getTimestamp();
		} catch ( Exception $e ) {
			$ts = strtotime( $raw );
			return false !== $ts ? $ts : null;
		}
	}
}

if ( ! function_exists( 'bilky_webinar_has_started' ) ) {
	/**
	 * Si el webinar en directo ya ha comenzado según fecha (o es grabado).
	 *
	 * @param int $post_id Post ID.
	 * @return bool
	 */
	function bilky_webinar_has_started( $post_id ) {
		$session = bilky_webinar_get_meta( $post_id, 'bilky_webinar_session_type', 'live' );
		if ( 'recorded' === $session ) {
			return true;
		}
		$ts = bilky_webinar_get_start_timestamp( $post_id );
		if ( null === $ts ) {
			return false;
		}
		return time() >= $ts;
	}
}

if ( ! function_exists( 'bilky_webinar_should_show_video' ) ) {
	/**
	 * Mostrar reproductor/embebido del vídeo (URL configurada y condiciones de tiempo/tipo).
	 *
	 * @param int $post_id Post ID.
	 * @return bool
	 */
	function bilky_webinar_should_show_video( $post_id ) {
		$url = bilky_webinar_get_meta( $post_id, 'bilky_webinar_video_url', '' );
		$url = is_string( $url ) ? trim( $url ) : '';
		if ( '' === $url ) {
			return false;
		}
		$session = bilky_webinar_get_meta( $post_id, 'bilky_webinar_session_type', 'live' );
		if ( 'recorded' === $session ) {
			return true;
		}
		return bilky_webinar_has_started( $post_id );
	}
}

if ( ! function_exists( 'bilky_webinar_get_webinar_category_names' ) ) {
	/**
	 * Nombres de categorías de webinar ordenados alfabéticamente.
	 *
	 * @param int $post_id Post ID.
	 * @return string[]
	 */
	function bilky_webinar_get_webinar_category_names( $post_id ) {
		$post_id = (int) $post_id;
		if ( $post_id <= 0 ) {
			return array();
		}
		$terms = get_the_terms( $post_id, 'category_webinar' );
		if ( ! $terms || is_wp_error( $terms ) ) {
			return array();
		}
		usort(
			$terms,
			function ( $a, $b ) {
				return strcmp( $a->name, $b->name );
			}
		);
		$out = array();
		foreach ( $terms as $term ) {
			$out[] = $term->name;
		}
		return $out;
	}
}

if ( ! function_exists( 'bilky_webinar_get_card_theme_class' ) ) {
	/**
	 * Clase BEM para la paleta de tarjeta (vacío si usa solo directo/grabada).
	 *
	 * @param string $color Valor de bilky_webinar_card_color.
	 * @return string
	 */
	function bilky_webinar_get_card_theme_class( $color ) {
		$color = is_string( $color ) ? trim( $color ) : '';
		if ( '' === $color || 'default' === $color ) {
			return '';
		}
		$allowed = array( 'blue', 'grey' );
		if ( ! in_array( $color, $allowed, true ) ) {
			return '';
		}
		return 'mwm-card-webinar--theme-' . $color;
	}
}

if ( ! function_exists( 'bilky_webinar_get_primary_ponente' ) ) {
	/**
	 * Primer ponente (término) asignado al webinar con imagen y cargo.
	 *
	 * @param int $post_id Post ID.
	 * @return array{name:string,cargo:string,image_id:int}|null
	 */
	function bilky_webinar_get_primary_ponente( $post_id ) {
		$terms = get_the_terms( $post_id, 'ponente_webinar' );
		if ( ! $terms || is_wp_error( $terms ) ) {
			return null;
		}
		usort(
			$terms,
			function ( $a, $b ) {
				return strcmp( $a->name, $b->name );
			}
		);
		$term = $terms[0];
		return array(
			'name'    => $term->name,
			'cargo'   => (string) get_term_meta( $term->term_id, 'bilky_ponente_cargo', true ),
			'image_id' => (int) get_term_meta( $term->term_id, 'bilky_ponente_image', true ),
		);
	}
}

if ( ! function_exists( 'bilky_webinar_embed_allowed_html' ) ) {
	/**
	 * HTML permitido para inscripción (iframes, formularios, scripts externos tipo Typeform/HubSpot, etc.).
	 *
	 * @return array
	 */
	function bilky_webinar_embed_allowed_html() {
		$allowed = wp_kses_allowed_html( 'post' );

		// Atributos data-* habituales en embeds (Typeform, Calendly, HubSpot…).
		$data_attrs = array(
			'data-tf-live'                     => true,
			'data-tf-inline'                   => true,
			'data-tf-widget'                   => true,
			'data-tf-opacity'                  => true,
			'data-tf-hubspot'                  => true,
			'data-tf-transitive-search-params' => true,
			'data-tf-search-params'            => true,
			'data-tf-medium'                   => true,
			'data-tf-hidden'                   => true,
			'data-url'                         => true,
			'data-testid'                      => true,
			'data-hs-cf-bound'                 => true,
			'data-form-id'                     => true,
			'data-region'                      => true,
			'data-mode'                        => true,
			'data-auto-load'                   => true,
		);

		if ( isset( $allowed['div'] ) && is_array( $allowed['div'] ) ) {
			$allowed['div'] = array_merge( $allowed['div'], $data_attrs );
		} else {
			$allowed['div'] = $data_attrs;
		}

		$iframe  = array(
			'iframe' => array(
				'src'             => true,
				'width'           => true,
				'height'          => true,
				'frameborder'     => true,
				'allowfullscreen' => true,
				'allow'           => true,
				'style'           => true,
				'title'           => true,
				'class'           => true,
				'id'              => true,
				'name'            => true,
				'scrolling'       => true,
				'loading'         => true,
			),
		);

		// Solo scripts con src (externos); no se permite JS inline por seguridad.
		$scripts = array(
			'script' => array(
				'src'         => true,
				'async'       => true,
				'defer'       => true,
				'type'        => true,
				'charset'     => true,
				'crossorigin' => true,
				'integrity'   => true,
				'nomodule'    => true,
				'class'       => true,
				'id'          => true,
			),
			'noscript' => array(),
		);

		$forms = array(
			'form'     => array(
				'action'         => true,
				'method'         => true,
				'class'          => true,
				'id'             => true,
				'enctype'        => true,
				'name'           => true,
				'novalidate'     => true,
				'target'         => true,
				'accept-charset' => true,
				'autocomplete'   => true,
			),
			'fieldset' => array(
				'class' => true,
				'id'    => true,
			),
			'legend'   => array(
				'class' => true,
			),
			'label'    => array(
				'for'   => true,
				'class' => true,
				'id'    => true,
			),
			'input'    => array(
				'type'             => true,
				'name'             => true,
				'value'            => true,
				'class'            => true,
				'id'               => true,
				'placeholder'      => true,
				'required'         => true,
				'checked'          => true,
				'disabled'         => true,
				'readonly'         => true,
				'maxlength'        => true,
				'minlength'        => true,
				'min'              => true,
				'max'              => true,
				'step'             => true,
				'size'             => true,
				'pattern'          => true,
				'accept'           => true,
				'multiple'         => true,
				'aria-label'       => true,
				'aria-describedby' => true,
				'aria-invalid'     => true,
				'aria-required'    => true,
				'tabindex'         => true,
				'autocomplete'     => true,
			),
			'textarea' => array(
				'name'             => true,
				'class'            => true,
				'id'               => true,
				'rows'             => true,
				'cols'             => true,
				'placeholder'      => true,
				'required'         => true,
				'disabled'         => true,
				'readonly'         => true,
				'maxlength'        => true,
				'minlength'        => true,
				'aria-label'       => true,
				'aria-describedby' => true,
				'aria-invalid'     => true,
				'aria-required'    => true,
				'tabindex'         => true,
				'autocomplete'     => true,
			),
			'select'   => array(
				'name'             => true,
				'class'            => true,
				'id'               => true,
				'required'         => true,
				'disabled'         => true,
				'multiple'         => true,
				'aria-label'       => true,
				'aria-describedby' => true,
				'tabindex'         => true,
				'autocomplete'     => true,
			),
			'option'   => array(
				'value'    => true,
				'selected' => true,
				'disabled' => true,
				'class'    => true,
			),
			'button'   => array(
				'type'      => true,
				'name'      => true,
				'value'     => true,
				'class'     => true,
				'id'        => true,
				'disabled'  => true,
				'tabindex'  => true,
				'aria-label'=> true,
			),
		);
		return array_merge( $allowed, $iframe, $scripts, $forms );
	}
}

if ( ! function_exists( 'bilky_webinar_render_registration_embed' ) ) {
	/**
	 * Procesa shortcodes (CF7) y HTML seguro para el bloque de inscripción.
	 *
	 * @param string $content Contenido crudo.
	 * @return string
	 */
	function bilky_webinar_render_registration_embed( $content ) {
		if ( ! is_string( $content ) || '' === trim( $content ) ) {
			return '';
		}
		// URLs protocol-relative (//domain) no pasan siempre la validación de wp_kses; forzar https.
		$content = preg_replace( '#\b(src|href)=(["\'])//#i', '$1=$2https://', $content );
		$content = do_shortcode( $content );
		$allowed   = bilky_webinar_embed_allowed_html();
		/**
		 * Filtro para ampliar etiquetas/atributos permitidos en el embed de inscripción (wp_kses).
		 *
		 * @param array $allowed Mapa de etiquetas permitidas.
		 */
		$allowed = apply_filters( 'bilky_webinar_registration_embed_allowed_html', $allowed );
		return wp_kses( $content, $allowed );
	}
}

if ( ! function_exists( 'bilky_webinar_get_video_embed_html' ) ) {
	/**
	 * Devuelve HTML embebido para URL de vídeo (oembed).
	 *
	 * @param string $url URL del vídeo.
	 * @return string
	 */
	function bilky_webinar_get_video_embed_html( $url ) {
		$url = esc_url_raw( trim( (string) $url ) );
		if ( '' === $url ) {
			return '';
		}
		$embed = wp_oembed_get( $url, array( 'width' => 800 ) );
		if ( $embed ) {
			return '<div class="mwm-single-webinar__video-embed">' . $embed . '</div>';
		}
		return '';
	}
}
