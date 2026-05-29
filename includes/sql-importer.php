<?php
/**
 * Importador de contenido desde un dump SQL.
 *
 * Fuente esperada (por defecto):
 * - /docu/frontend_bilky-20250119.sql/frontend_bilky-20250119.sql
 *
 * Mapeos:
 * - posts (SQL) -> post (WordPress)
 * - clients (SQL) -> clientes (CPT existente)
 * - partners (SQL) -> partners (CPT nuevo)
 * - articles + article_translations + categories (SQL) -> centro_de_ayuda (CPT existente)
 *
 * Uso (WP-CLI):
 * - wp bilky import-sql --file=/ruta/al/dump.sql --lang=es --only=posts,clients,partners,articles --media-base-url=https://... --media-base-dir=/ruta --dry-run
 */

/**
 * Página de administración (para entornos sin WP‑CLI).
 */
if ( is_admin() ) {
	add_action( 'admin_menu', 'bilky_sql_importer_register_admin_page' );
}

/**
 * Registra la página del importador en Herramientas.
 *
 * @return void
 */
function bilky_sql_importer_register_admin_page() {
	add_management_page(
		__( 'Importador SQL (Bilky)', 'bilky' ),
		__( 'Importador SQL', 'bilky' ),
		'manage_options',
		'bilky-sql-importer',
		'bilky_sql_importer_render_admin_page'
	);
}

/**
 * Renderiza la página del importador y procesa el formulario.
 *
 * @return void
 */
function bilky_sql_importer_render_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$allowed_sections = array( 'posts', 'clients', 'partners', 'articles' );

	$default_file     = bilky_sql_importer_default_file();
	$default_media_dir = bilky_sql_importer_default_media_dir();
	$file             = $default_file;
	$lang             = 'es';
	$only             = $allowed_sections;
	$media_base_url   = bilky_sql_importer_default_media_base_url();
	$media_base_dir   = $default_media_dir;
	$import_images    = true;
	$dry_run          = false;
	$import_result    = null;
	$media_dir_warning = '';

	$delete_only          = $allowed_sections;
	$delete_created_only  = true; // Más seguro: solo lo creado por el importador.
	$delete_terms         = false;
	$delete_attachments   = true;
	$delete_dry_run       = false;
	$delete_import_result = null;

	$update_video_url_match  = 'https://vimeo.com/840776345?share=copy';
	$update_video_dry_run    = false;
	$update_video_result     = null;

	if ( isset( $_POST['bilky_sql_importer_action'] ) && $_POST['bilky_sql_importer_action'] === 'run' ) {
		check_admin_referer( 'bilky_sql_importer_action', 'bilky_sql_importer_nonce' );

		$file_input = isset( $_POST['file'] ) ? sanitize_text_field( wp_unslash( $_POST['file'] ) ) : $default_file;
		$file       = $file_input !== '' ? $file_input : $default_file;

		$lang_input = isset( $_POST['lang'] ) ? sanitize_key( wp_unslash( $_POST['lang'] ) ) : 'es';
		$lang       = in_array( $lang_input, array( 'es', 'en' ), true ) ? $lang_input : 'es';

		$only_input = isset( $_POST['only'] ) ? (array) $_POST['only'] : array();
		$only       = array();
		foreach ( $only_input as $section ) {
			$section = sanitize_key( wp_unslash( $section ) );
			if ( in_array( $section, $allowed_sections, true ) ) {
				$only[] = $section;
			}
		}
		if ( empty( $only ) ) {
			$only = $allowed_sections;
		}

		$media_base_url = isset( $_POST['media_base_url'] ) ? esc_url_raw( wp_unslash( $_POST['media_base_url'] ) ) : '';
		$media_base_dir = isset( $_POST['media_base_dir'] ) ? sanitize_text_field( wp_unslash( $_POST['media_base_dir'] ) ) : $default_media_dir;
		$import_images  = isset( $_POST['import_images'] );
		$dry_run         = isset( $_POST['dry_run'] );

		$resolved = bilky_sql_importer_resolve_file_path( $file );
		if ( is_wp_error( $resolved ) ) {
			$import_result = $resolved;
		} else {
			$file = $resolved;

			// Resolver media base dir (opcional). Si falla pero hay URL, continuar solo con URL.
			$resolved_media_dir = '';
			if ( $import_images ) {
				$resolved_media_dir = bilky_sql_importer_resolve_media_base_dir( $media_base_dir );
				if ( is_wp_error( $resolved_media_dir ) ) {
					$media_dir_warning  = $resolved_media_dir->get_error_message();
					$resolved_media_dir = '';
				}
			}

			if ( ! is_wp_error( $import_result ) ) {
				if ( $import_images && $media_base_url === '' && $resolved_media_dir === '' ) {
					$media_dir_warning = $media_dir_warning !== ''
						? $media_dir_warning . ' ' . __( 'No hay Media base URL/DIR válido: se importará sin imágenes.', 'bilky' )
						: __( 'No hay Media base URL/DIR válido: se importará sin imágenes.', 'bilky' );
				}

				$media_base_dir = is_string( $resolved_media_dir ) ? $resolved_media_dir : '';
				@set_time_limit( 0 );
				$import_result = bilky_sql_importer_run(
					$file,
					array(
						'lang'           => $lang,
						'only'           => $only,
						'media_base_url' => $media_base_url,
						'media_base_dir' => $media_base_dir,
						'import_images'  => $import_images,
						'dry_run'        => $dry_run,
					)
				);
			}
		}
	}

	if ( isset( $_POST['bilky_sql_importer_action'] ) && $_POST['bilky_sql_importer_action'] === 'delete' ) {
		check_admin_referer( 'bilky_sql_importer_delete_action', 'bilky_sql_importer_delete_nonce' );

		$delete_only_input = isset( $_POST['delete_only'] ) ? (array) $_POST['delete_only'] : array();
		$delete_only       = array();
		foreach ( $delete_only_input as $section ) {
			$section = sanitize_key( wp_unslash( $section ) );
			if ( in_array( $section, $allowed_sections, true ) ) {
				$delete_only[] = $section;
			}
		}
		if ( empty( $delete_only ) ) {
			$delete_only = $allowed_sections;
		}

		$delete_created_only = isset( $_POST['delete_created_only'] );
		$delete_terms        = isset( $_POST['delete_terms'] );
		$delete_attachments  = isset( $_POST['delete_attachments'] );
		$delete_dry_run      = isset( $_POST['delete_dry_run'] );

		@set_time_limit( 0 );
		$delete_import_result = bilky_sql_importer_delete_imported_content(
			array(
				'only'         => $delete_only,
				'created_only' => $delete_created_only,
				'delete_terms' => $delete_terms,
				'delete_attachments' => $delete_attachments,
				'dry_run'      => $delete_dry_run,
			)
		);
	}

	if ( isset( $_POST['bilky_sql_importer_action'] ) && $_POST['bilky_sql_importer_action'] === 'update_video_urls' ) {
		check_admin_referer( 'bilky_sql_importer_update_video_urls_action', 'bilky_sql_importer_update_video_urls_nonce' );

		$file_input = isset( $_POST['file'] ) ? sanitize_text_field( wp_unslash( $_POST['file'] ) ) : $default_file;
		$file       = $file_input !== '' ? $file_input : $default_file;

		$lang_input = isset( $_POST['lang'] ) ? sanitize_key( wp_unslash( $_POST['lang'] ) ) : 'es';
		$lang       = in_array( $lang_input, array( 'es', 'en' ), true ) ? $lang_input : 'es';

		$update_video_url_match = isset( $_POST['video_url_match'] ) ? trim( (string) wp_unslash( $_POST['video_url_match'] ) ) : $update_video_url_match;
		$update_video_dry_run   = isset( $_POST['update_video_dry_run'] );

		$resolved = bilky_sql_importer_resolve_file_path( $file );
		if ( is_wp_error( $resolved ) ) {
			$update_video_result = $resolved;
		} else {
			$file = $resolved;
			@set_time_limit( 0 );
			$update_video_result = bilky_sql_importer_update_centro_de_ayuda_video_urls(
				$file,
				$lang,
				$update_video_url_match,
				$update_video_dry_run
			);
		}
	}

	echo '<div class="wrap">';
	echo '<h1>' . esc_html__( 'Importador SQL (Bilky)', 'bilky' ) . '</h1>';

	echo '<p>' . esc_html__( 'Este importador está pensado para entornos sin WP‑CLI. Sube el dump SQL por FTP dentro del tema y ejecútalo desde aquí.', 'bilky' ) . '</p>';
	echo '<p><strong>' . esc_html__( 'Ruta por defecto del dump:', 'bilky' ) . '</strong> <code>' . esc_html( $default_file ) . '</code></p>';

	if ( is_wp_error( $import_result ) ) {
		echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'Error:', 'bilky' ) . '</strong> ' . esc_html( $import_result->get_error_message() ) . '</p></div>';
	} elseif ( is_array( $import_result ) ) {
		echo '<div class="notice notice-success"><p><strong>' . esc_html__( 'Importación finalizada.', 'bilky' ) . '</strong></p></div>';
		if ( $dry_run ) {
			echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Modo dry-run:', 'bilky' ) . '</strong> ' . esc_html__( 'no se han escrito cambios.', 'bilky' ) . '</p></div>';
		}

		echo '<h2>' . esc_html__( 'Resumen', 'bilky' ) . '</h2>';
		echo '<table class="widefat striped"><thead><tr><th>' . esc_html__( 'Sección', 'bilky' ) . '</th><th>' . esc_html__( 'Creados', 'bilky' ) . '</th><th>' . esc_html__( 'Actualizados', 'bilky' ) . '</th><th>' . esc_html__( 'Omitidos', 'bilky' ) . '</th></tr></thead><tbody>';
		foreach ( $import_result as $section => $counts ) {
			echo '<tr>';
			echo '<td><code>' . esc_html( $section ) . '</code></td>';
			echo '<td>' . (int) $counts['created'] . '</td>';
			echo '<td>' . (int) $counts['updated'] . '</td>';
			echo '<td>' . (int) $counts['skipped'] . '</td>';
			echo '</tr>';
		}
		echo '</tbody></table>';
	}

	if ( $media_dir_warning !== '' ) {
		echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Aviso:', 'bilky' ) . '</strong> ' . esc_html( $media_dir_warning ) . '</p></div>';
	}

	if ( is_wp_error( $delete_import_result ) ) {
		echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'Error al borrar:', 'bilky' ) . '</strong> ' . esc_html( $delete_import_result->get_error_message() ) . '</p></div>';
	} elseif ( is_array( $delete_import_result ) ) {
		echo '<div class="notice notice-success"><p><strong>' . esc_html__( 'Borrado finalizado.', 'bilky' ) . '</strong></p></div>';
		if ( $delete_dry_run ) {
			echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Modo dry-run:', 'bilky' ) . '</strong> ' . esc_html__( 'no se han escrito cambios.', 'bilky' ) . '</p></div>';
		}

		echo '<h2>' . esc_html__( 'Resumen de borrado', 'bilky' ) . '</h2>';
		echo '<table class="widefat striped"><thead><tr><th>' . esc_html__( 'Sección', 'bilky' ) . '</th><th>' . esc_html__( 'Eliminados', 'bilky' ) . '</th><th>' . esc_html__( 'Omitidos', 'bilky' ) . '</th></tr></thead><tbody>';
		foreach ( $delete_import_result as $section => $counts ) {
			echo '<tr>';
			echo '<td><code>' . esc_html( $section ) . '</code></td>';
			echo '<td>' . (int) $counts['deleted'] . '</td>';
			echo '<td>' . (int) $counts['skipped'] . '</td>';
			echo '</tr>';
		}
		echo '</tbody></table>';
	}

	if ( is_wp_error( $update_video_result ) ) {
		echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'Error al actualizar vídeos:', 'bilky' ) . '</strong> ' . esc_html( $update_video_result->get_error_message() ) . '</p></div>';
	} elseif ( is_array( $update_video_result ) ) {
		echo '<div class="notice notice-success"><p><strong>' . esc_html__( 'Actualización de vídeos finalizada.', 'bilky' ) . '</strong></p></div>';
		if ( $update_video_dry_run ) {
			echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Modo dry-run:', 'bilky' ) . '</strong> ' . esc_html__( 'no se han escrito cambios.', 'bilky' ) . '</p></div>';
		}
		echo '<h2>' . esc_html__( 'Resumen de actualización de vídeos', 'bilky' ) . '</h2>';
		echo '<table class="widefat striped"><thead><tr><th>' . esc_html__( 'Métrica', 'bilky' ) . '</th><th>' . esc_html__( 'Valor', 'bilky' ) . '</th></tr></thead><tbody>';
		foreach ( $update_video_result as $k => $v ) {
			echo '<tr><td><code>' . esc_html( $k ) . '</code></td><td>' . esc_html( (string) $v ) . '</td></tr>';
		}
		echo '</tbody></table>';
	}

	echo '<hr>';
	echo '<form method="post">';
	wp_nonce_field( 'bilky_sql_importer_action', 'bilky_sql_importer_nonce' );
	echo '<input type="hidden" name="bilky_sql_importer_action" value="run">';

	echo '<table class="form-table" role="presentation"><tbody>';

	echo '<tr>';
	echo '<th scope="row"><label for="bilky_sql_importer_file">' . esc_html__( 'Fichero SQL', 'bilky' ) . '</label></th>';
	echo '<td><input type="text" class="regular-text" id="bilky_sql_importer_file" name="file" value="' . esc_attr( $file ) . '">';
	echo '<p class="description">' . esc_html__( 'Ruta absoluta dentro del tema o ruta relativa (se resolverá contra la carpeta del tema).', 'bilky' ) . '</p></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row"><label for="bilky_sql_importer_lang">' . esc_html__( 'Idioma', 'bilky' ) . '</label></th>';
	echo '<td><select id="bilky_sql_importer_lang" name="lang">';
	echo '<option value="es"' . selected( $lang, 'es', false ) . '>es</option>';
	echo '<option value="en"' . selected( $lang, 'en', false ) . '>en</option>';
	echo '</select></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html__( 'Qué importar', 'bilky' ) . '</th>';
	echo '<td>';
	foreach ( $allowed_sections as $section ) {
		$checked = in_array( $section, $only, true ) ? 'checked' : '';
		echo '<label style="display:block;margin:2px 0;"><input type="checkbox" name="only[]" value="' . esc_attr( $section ) . '" ' . $checked . '> <code>' . esc_html( $section ) . '</code></label>';
	}
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row"><label for="bilky_sql_importer_media_base_url">' . esc_html__( 'Media base URL (opcional)', 'bilky' ) . '</label></th>';
	echo '<td><input type="text" class="regular-text" id="bilky_sql_importer_media_base_url" name="media_base_url" value="' . esc_attr( $media_base_url ) . '">';
	echo '<p class="description">' . esc_html__( 'Si se indica, el importador podrá descargar imágenes (logos, thumbnails, imágenes de pasos, etc.) desde esta URL base. Si hay DIR y el fichero existe, se usará DIR; si no, se intentará por URL.', 'bilky' ) . '</p></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row"><label for="bilky_sql_importer_media_base_dir">' . esc_html__( 'Media base DIR (opcional)', 'bilky' ) . '</label></th>';
	echo '<td><input type="text" class="regular-text" id="bilky_sql_importer_media_base_dir" name="media_base_dir" value="' . esc_attr( $media_base_dir ) . '">';
	echo '<p class="description">' . esc_html__( 'Ruta en disco donde están las imágenes subidas por FTP. Por defecto apunta a una carpeta dentro del tema.', 'bilky' ) . ' ' . esc_html__( 'Si existe, se usa docu/frontend-files; si no, docu/import-media.', 'bilky' ) . '</p></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html__( 'Importar imágenes', 'bilky' ) . '</th>';
	echo '<td><label><input type="checkbox" name="import_images" value="1" ' . checked( $import_images, true, false ) . '> ' . esc_html__( 'Crear adjuntos en la librería de medios y asignar imagen destacada', 'bilky' ) . '</label></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html__( 'Dry run', 'bilky' ) . '</th>';
	echo '<td><label><input type="checkbox" name="dry_run" value="1" ' . checked( $dry_run, true, false ) . '> ' . esc_html__( 'No escribir cambios (solo simular)', 'bilky' ) . '</label></td>';
	echo '</tr>';

	echo '</tbody></table>';

	submit_button( __( 'Ejecutar importación', 'bilky' ) );
	echo '</form>';

	echo '<hr>';
	echo '<h2>' . esc_html__( 'Borrar contenido importado', 'bilky' ) . '</h2>';
	echo '<p>' . esc_html__( 'Esto elimina en bloque el contenido marcado por el importador. Por defecto solo borra lo CREADO por el importador (más seguro).', 'bilky' ) . '</p>';
	echo '<p><strong>' . esc_html__( 'Aviso:', 'bilky' ) . '</strong> ' . esc_html__( 'el borrado es permanente (no va a la papelera).', 'bilky' ) . '</p>';

	echo '<form method="post">';
	wp_nonce_field( 'bilky_sql_importer_delete_action', 'bilky_sql_importer_delete_nonce' );
	echo '<input type="hidden" name="bilky_sql_importer_action" value="delete">';

	echo '<table class="form-table" role="presentation"><tbody>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html__( 'Qué borrar', 'bilky' ) . '</th>';
	echo '<td>';
	foreach ( $allowed_sections as $section ) {
		$checked = in_array( $section, $delete_only, true ) ? 'checked' : '';
		echo '<label style="display:block;margin:2px 0;"><input type="checkbox" name="delete_only[]" value="' . esc_attr( $section ) . '" ' . $checked . '> <code>' . esc_html( $section ) . '</code></label>';
	}
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html__( 'Modo seguro', 'bilky' ) . '</th>';
	echo '<td><label><input type="checkbox" name="delete_created_only" value="1" ' . checked( $delete_created_only, true, false ) . '> ' . esc_html__( 'Borrar solo lo creado por el importador (recomendado)', 'bilky' ) . '</label></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html__( 'Términos (categorías/tags/tipos)', 'bilky' ) . '</th>';
	echo '<td><label><input type="checkbox" name="delete_terms" value="1" ' . checked( $delete_terms, true, false ) . '> ' . esc_html__( 'Borrar también términos creados por el importador', 'bilky' ) . '</label></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html__( 'Adjuntos (imágenes)', 'bilky' ) . '</th>';
	echo '<td><label><input type="checkbox" name="delete_attachments" value="1" ' . checked( $delete_attachments, true, false ) . '> ' . esc_html__( 'Borrar también adjuntos (imágenes) creados por el importador', 'bilky' ) . '</label></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html__( 'Dry run', 'bilky' ) . '</th>';
	echo '<td><label><input type="checkbox" name="delete_dry_run" value="1" ' . checked( $delete_dry_run, true, false ) . '> ' . esc_html__( 'No escribir cambios (solo simular)', 'bilky' ) . '</label></td>';
	echo '</tr>';

	echo '</tbody></table>';

	echo '<p class="submit">';
	echo '<input type="submit" class="button button-secondary" value="' . esc_attr__( 'Borrar contenido importado', 'bilky' ) . '" onclick="return confirm(\'' . esc_js( __( '¿Seguro? Esta acción es permanente y borrará contenido.', 'bilky' ) ) . '\');">';
	echo '</p>';
	echo '</form>';

	echo '<hr>';
	echo '<h2>' . esc_html__( 'Actualizar URL de vídeo (centro de ayuda)', 'bilky' ) . '</h2>';
	echo '<p>' . esc_html__( 'Esta acción lee el dump SQL y, para los artículos cuyo campo video tiene la URL indicada, actualiza SOLO el título y guarda la URL en el meta', 'bilky' ) . ' <code>bilky_video_url</code>. ' . esc_html__( 'No modifica el contenido.', 'bilky' ) . '</p>';

	echo '<form method="post">';
	wp_nonce_field( 'bilky_sql_importer_update_video_urls_action', 'bilky_sql_importer_update_video_urls_nonce' );
	echo '<input type="hidden" name="bilky_sql_importer_action" value="update_video_urls">';

	echo '<table class="form-table" role="presentation"><tbody>';

	echo '<tr>';
	echo '<th scope="row"><label for="bilky_sql_importer_video_file">' . esc_html__( 'Fichero SQL', 'bilky' ) . '</label></th>';
	echo '<td><input type="text" class="regular-text" id="bilky_sql_importer_video_file" name="file" value="' . esc_attr( $file ) . '"></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row"><label for="bilky_sql_importer_video_lang">' . esc_html__( 'Idioma', 'bilky' ) . '</label></th>';
	echo '<td><select id="bilky_sql_importer_video_lang" name="lang">';
	echo '<option value="es"' . selected( $lang, 'es', false ) . '>es</option>';
	echo '<option value="en"' . selected( $lang, 'en', false ) . '>en</option>';
	echo '</select></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row"><label for="bilky_sql_importer_video_url_match">' . esc_html__( 'URL de vídeo a localizar (opcional)', 'bilky' ) . '</label></th>';
	echo '<td><input type="text" class="regular-text" id="bilky_sql_importer_video_url_match" name="video_url_match" value="' . esc_attr( $update_video_url_match ) . '">';
	echo '<p class="description">' . esc_html__( 'Si lo dejas vacío, actualizará TODOS los artículos del dump que tengan video.url.', 'bilky' ) . '</p></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html__( 'Dry run', 'bilky' ) . '</th>';
	echo '<td><label><input type="checkbox" name="update_video_dry_run" value="1" ' . checked( $update_video_dry_run, true, false ) . '> ' . esc_html__( 'No escribir cambios (solo simular)', 'bilky' ) . '</label></td>';
	echo '</tr>';

	echo '</tbody></table>';

	submit_button( __( 'Actualizar vídeos (solo título + meta)', 'bilky' ) );
	echo '</form>';

	echo '</div>';
}

/**
 * Actualiza SOLO el título y guarda bilky_video_url para artículos del CPT centro_de_ayuda
 * cuyo campo "video" en SQL coincide con una URL concreta.
 *
 * NO modifica el contenido (post_content).
 *
 * @param string $file
 * @param string $lang
 * @param string $video_url_match
 * @param bool   $dry_run
 * @return array|WP_Error
 */
function bilky_sql_importer_update_centro_de_ayuda_video_urls( $file, $lang, $video_url_match, $dry_run ) {
	$file            = (string) $file;
	$lang            = (string) $lang;
	$video_url_match = trim( (string) $video_url_match );
	$dry_run         = (bool) $dry_run;

	$data = bilky_sql_load_dump_data( $file, array( 'articles' ) );
	if ( is_wp_error( $data ) ) {
		return $data;
	}

	$matched_in_sql   = 0;
	$updated_posts    = 0;
	$skipped_not_found = 0;
	$skipped_no_title = 0;
	$errors           = 0;

	$rows = isset( $data['articles'] ) && is_array( $data['articles'] ) ? $data['articles'] : array();

	foreach ( $rows as $row ) {
		$sql_id = isset( $row['id'] ) ? (int) $row['id'] : 0;
		if ( $sql_id <= 0 ) {
			continue;
		}

		$video_raw = isset( $row['video'] ) ? $row['video'] : '';
		if ( ! is_string( $video_raw ) || trim( $video_raw ) === '' ) {
			continue;
		}

		$decoded = json_decode( $video_raw, true );
		if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $decoded ) ) {
			continue;
		}

		$url = isset( $decoded['url'] ) && is_string( $decoded['url'] ) ? trim( $decoded['url'] ) : '';
		if ( $url === '' ) {
			continue;
		}
		if ( $video_url_match !== '' && $url !== $video_url_match ) {
			continue;
		}

		$matched_in_sql++;

		$slug  = bilky_sql_json_lang( isset( $row['slug'] ) ? $row['slug'] : '', $lang );
		$title = bilky_sql_json_lang( isset( $row['title'] ) ? $row['title'] : '', $lang );

		$slug  = $slug !== '' ? sanitize_title( $slug ) : '';
		$title = trim( (string) $title );

		if ( $slug === '' ) {
			// Fallback: mantener compatibilidad (por si el slug viene vacío en un idioma).
			$slug = $title !== '' ? sanitize_title( $title ) : '';
		}

		if ( $title === '' ) {
			$skipped_no_title++;
			continue;
		}

		$existing_id = bilky_sql_find_existing_post_id( 'centro_de_ayuda', 'articles', $sql_id, $slug, null, null );
		if ( $existing_id <= 0 ) {
			$skipped_not_found++;
			continue;
		}

		if ( $dry_run ) {
			$updated_posts++;
			continue;
		}

		$res = wp_update_post(
			array(
				'ID'         => (int) $existing_id,
				'post_title' => $title,
			),
			true
		);

		if ( is_wp_error( $res ) ) {
			$errors++;
			continue;
		}

		update_post_meta( (int) $existing_id, 'bilky_video_url', $url );
		$updated_posts++;
	}

	return array(
		'matched_in_sql'    => $matched_in_sql,
		'updated_posts'     => $updated_posts,
		'skipped_not_found' => $skipped_not_found,
		'skipped_no_title'  => $skipped_no_title,
		'errors'            => $errors,
		'meta_key'          => 'bilky_video_url',
	);
}

/**
 * Resuelve y valida la ruta del fichero SQL.
 * Por seguridad, solo permite leer ficheros dentro del tema.
 *
 * @param string $file
 * @return string|WP_Error
 */
function bilky_sql_importer_resolve_file_path( $file ) {
	$file = is_string( $file ) ? trim( $file ) : '';
	if ( $file === '' ) {
		return new WP_Error( 'bilky_sql_importer_empty_file', 'La ruta del fichero SQL está vacía.' );
	}

	$theme_dir = realpath( get_template_directory() );

	$real = realpath( $file );
	if ( $real === false ) {
		$candidate = trailingslashit( get_template_directory() ) . ltrim( $file, "/\\" );
		$real      = realpath( $candidate );
	}

	if ( $real === false || ! is_file( $real ) ) {
		return new WP_Error( 'bilky_sql_importer_missing_file', 'No se encuentra el fichero SQL: ' . $file );
	}

	if ( $theme_dir && strpos( $real, $theme_dir ) !== 0 ) {
		return new WP_Error( 'bilky_sql_importer_outside_theme', 'Por seguridad, el fichero debe estar dentro del tema.' );
	}

	return $real;
}

/**
 * Directorio por defecto para medios (imágenes) subidos por FTP.
 *
 * @return string
 */
function bilky_sql_importer_default_media_dir() {
	$theme_dir = trailingslashit( get_template_directory() );

	// Priorizar la carpeta de ficheros exportados del frontend si existe.
	$candidates = array(
		$theme_dir . 'docu/frontend-files',
		$theme_dir . 'docu/import-media',
	);

	foreach ( $candidates as $dir ) {
		$real = realpath( $dir );
		if ( $real && is_dir( $real ) ) {
			return $real;
		}
	}

	// Fallback: mantener compatibilidad.
	return $theme_dir . 'docu/import-media';
}

/**
 * Resuelve y valida el directorio base de medios.
 * Por seguridad, solo permite leer dentro del tema o dentro del directorio de uploads.
 *
 * @param string $dir
 * @return string|WP_Error
 */
function bilky_sql_importer_resolve_media_base_dir( $dir ) {
	$dir = is_string( $dir ) ? trim( $dir ) : '';
	if ( $dir === '' ) {
		// Permitir vacío: se intentará con URL base si existe.
		return '';
	}

	$theme_dir = realpath( get_template_directory() );
	$uploads   = wp_upload_dir();
	$uploads_dir = isset( $uploads['basedir'] ) ? realpath( $uploads['basedir'] ) : false;

	$real = realpath( $dir );
	if ( $real === false ) {
		// Permitir ruta relativa al tema.
		$candidate = trailingslashit( get_template_directory() ) . ltrim( $dir, "/\\" );
		$real      = realpath( $candidate );
	}

	if ( $real === false || ! is_dir( $real ) ) {
		return new WP_Error( 'bilky_sql_importer_missing_media_dir', 'No se encuentra el directorio de medios: ' . $dir );
	}

	$allowed = false;
	if ( $theme_dir && strpos( $real, $theme_dir ) === 0 ) {
		$allowed = true;
	}
	if ( ! $allowed && $uploads_dir && strpos( $real, $uploads_dir ) === 0 ) {
		$allowed = true;
	}

	if ( ! $allowed ) {
		return new WP_Error( 'bilky_sql_importer_outside_allowed_media_dir', 'Por seguridad, el directorio de medios debe estar dentro del tema o dentro de uploads.' );
	}

	return $real;
}

/**
 * Borra contenido importado (marcado por meta) por secciones.
 *
 * @param array $options
 * @return array|WP_Error
 */
function bilky_sql_importer_delete_imported_content( $options = array() ) {
	$defaults = array(
		'only'         => array( 'posts', 'clients', 'partners', 'articles' ),
		'created_only' => true,
		'delete_terms' => false,
		'delete_attachments' => false,
		'dry_run'      => false,
	);
	$options = wp_parse_args( $options, $defaults );

	$only         = is_array( $options['only'] ) ? $options['only'] : array();
	$created_only = (bool) $options['created_only'];
	$delete_terms = (bool) $options['delete_terms'];
	$delete_attachments = (bool) $options['delete_attachments'];
	$dry_run      = (bool) $options['dry_run'];

	$map = array(
		'posts'    => array( 'post_type' => 'post', 'sql_table' => 'posts' ),
		'clients'  => array( 'post_type' => 'clientes', 'sql_table' => 'clients' ),
		'partners' => array( 'post_type' => 'partners', 'sql_table' => 'partners' ),
		'articles' => array( 'post_type' => 'centro_de_ayuda', 'sql_table' => 'articles' ),
	);

	$summary = array();
	foreach ( $only as $section ) {
		if ( ! isset( $map[ $section ] ) ) {
			continue;
		}

		$result             = bilky_sql_importer_delete_posts_by_table(
			$map[ $section ]['post_type'],
			$map[ $section ]['sql_table'],
			$created_only,
			$dry_run
		);
		$summary[ $section ] = $result;
	}

	if ( $delete_terms ) {
		$taxonomies = array();
		if ( in_array( 'articles', $only, true ) ) {
			$taxonomies[] = 'category';
		}
		if ( in_array( 'posts', $only, true ) ) {
			$taxonomies[] = 'post_tag';
		}
		if ( in_array( 'partners', $only, true ) ) {
			$taxonomies[] = 'partner_tipo';
		}
		if ( in_array( 'clients', $only, true ) ) {
			$taxonomies[] = 'cliente_categoria';
		}

		$terms_deleted = 0;
		$terms_skipped = 0;
		foreach ( array_unique( $taxonomies ) as $taxonomy ) {
			$res = bilky_sql_importer_delete_terms_by_taxonomy( $taxonomy, $created_only, $dry_run );
			if ( is_wp_error( $res ) ) {
				return $res;
			}
			$terms_deleted += (int) $res['deleted'];
			$terms_skipped += (int) $res['skipped'];
		}

		$summary['terms'] = array(
			'deleted' => $terms_deleted,
			'skipped' => $terms_skipped,
		);
	}

	if ( $delete_attachments ) {
		$tables = array();
		foreach ( $only as $section ) {
			if ( isset( $map[ $section ] ) && isset( $map[ $section ]['sql_table'] ) ) {
				$tables[] = (string) $map[ $section ]['sql_table'];
			}
		}
		$tables = array_values( array_unique( array_filter( $tables ) ) );

		$attachments_result = bilky_sql_importer_delete_attachments_by_tables( $tables, $created_only, $dry_run );
		if ( is_wp_error( $attachments_result ) ) {
			return $attachments_result;
		}
		$summary['attachments'] = $attachments_result;
	}

	return $summary;
}

/**
 * Borra posts por post_type y valor de _bilky_sql_table.
 *
 * @param string $post_type
 * @param string $sql_table
 * @param bool   $created_only
 * @param bool   $dry_run
 * @return array
 */
function bilky_sql_importer_delete_posts_by_table( $post_type, $sql_table, $created_only, $dry_run ) {
	$deleted = 0;
	$skipped = 0;

	$meta_query = array(
		array(
			'key'   => '_bilky_sql_table',
			'value' => (string) $sql_table,
		),
	);

	if ( $created_only ) {
		$meta_query[] = array(
			'key'   => '_bilky_sql_import_created',
			'value' => '1',
		);
	}

	while ( true ) {
		$ids = get_posts(
			array(
				'post_type'           => (string) $post_type,
				'post_status'         => 'any',
				'posts_per_page'      => 50,
				'fields'              => 'ids',
				'no_found_rows'       => true,
				'orderby'             => 'ID',
				'order'               => 'ASC',
				'suppress_filters'    => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'meta_query'          => $meta_query,
			)
		);

		if ( empty( $ids ) ) {
			break;
		}

		foreach ( $ids as $id ) {
			$id = (int) $id;
			if ( $id <= 0 ) {
				$skipped++;
				continue;
			}

			if ( $dry_run ) {
				$deleted++;
				continue;
			}

			$deleted_post = wp_delete_post( $id, true );
			if ( $deleted_post ) {
				$deleted++;
			} else {
				$skipped++;
			}
		}
	}

	return array(
		'deleted' => $deleted,
		'skipped' => $skipped,
	);
}

/**
 * Borra términos marcados por el importador dentro de una taxonomía.
 *
 * @param string $taxonomy
 * @param bool   $created_only
 * @param bool   $dry_run
 * @return array|WP_Error
 */
function bilky_sql_importer_delete_terms_by_taxonomy( $taxonomy, $created_only, $dry_run ) {
	$taxonomy = (string) $taxonomy;
	if ( $taxonomy === '' || ! taxonomy_exists( $taxonomy ) ) {
		return array( 'deleted' => 0, 'skipped' => 0 );
	}

	$meta_query = array(
		array(
			'key'   => '_bilky_sql_imported',
			'value' => '1',
		),
	);

	if ( $created_only ) {
		$meta_query[] = array(
			'key'   => '_bilky_sql_import_created',
			'value' => '1',
		);
	}

	$term_ids = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'fields'     => 'ids',
			'meta_query' => $meta_query,
		)
	);

	if ( is_wp_error( $term_ids ) ) {
		return $term_ids;
	}

	$deleted = 0;
	$skipped = 0;
	foreach ( $term_ids as $term_id ) {
		$term_id = (int) $term_id;
		if ( $term_id <= 0 ) {
			$skipped++;
			continue;
		}

		if ( $dry_run ) {
			$deleted++;
			continue;
		}

		$result = wp_delete_term( $term_id, $taxonomy );
		if ( is_wp_error( $result ) || ! $result ) {
			$skipped++;
		} else {
			$deleted++;
		}
	}

	return array(
		'deleted' => $deleted,
		'skipped' => $skipped,
	);
}

/**
 * Comando WP-CLI.
 */
class Bilky_SQL_Import_Command {
	/**
	 * Ejecuta el importador.
	 *
	 * ## OPCIONES
	 *
	 * [--file=<path>]
	 * : Ruta del dump SQL (por defecto la del repo).
	 *
	 * [--lang=<lang>]
	 * : Idioma a importar desde columnas JSON (por defecto: es).
	 *
	 * [--only=<list>]
	 * : Lista CSV de secciones: posts,clients,partners,articles (por defecto: todas).
	 *
	 * [--media-base-url=<url>]
	 * : Base URL para descargar imágenes (logos, thumbnails, pasos, etc.).
	 *
	 * [--media-base-dir=<path>]
	 * : Directorio local (en disco) donde están las imágenes (subidas por FTP).
	 *
	 * [--no-import-images]
	 * : No importar imágenes como adjuntos.
	 *
	 * [--dry-run]
	 * : No escribe cambios (solo calcula y muestra conteos).
	 */
	public function __invoke( $args, $assoc_args ) {
		$file = isset( $assoc_args['file'] ) && is_string( $assoc_args['file'] ) && $assoc_args['file'] !== ''
			? $assoc_args['file']
			: bilky_sql_importer_default_file();

		$lang = isset( $assoc_args['lang'] ) && is_string( $assoc_args['lang'] ) && $assoc_args['lang'] !== ''
			? $assoc_args['lang']
			: 'es';

		$only = isset( $assoc_args['only'] ) && is_string( $assoc_args['only'] ) && $assoc_args['only'] !== ''
			? array_filter( array_map( 'trim', explode( ',', $assoc_args['only'] ) ) )
			: array( 'posts', 'clients', 'partners', 'articles' );

		$media_base_url = isset( $assoc_args['media-base-url'] ) && is_string( $assoc_args['media-base-url'] )
			? trim( $assoc_args['media-base-url'] )
			: bilky_sql_importer_default_media_base_url();
		// Compat con versiones anteriores.
		if ( isset( $assoc_args['images-base-url'] ) && is_string( $assoc_args['images-base-url'] ) ) {
			$legacy = trim( $assoc_args['images-base-url'] );
			if ( $legacy !== '' ) {
				$media_base_url = $legacy;
			}
		}

		$media_base_dir = isset( $assoc_args['media-base-dir'] ) && is_string( $assoc_args['media-base-dir'] )
			? trim( $assoc_args['media-base-dir'] )
			: bilky_sql_importer_default_media_dir();

		$import_images = ! array_key_exists( 'no-import-images', $assoc_args );

		$dry_run = array_key_exists( 'dry-run', $assoc_args );

		$resolved_media_dir = '';
		if ( $import_images ) {
			$resolved_media_dir = bilky_sql_importer_resolve_media_base_dir( $media_base_dir );
			if ( is_wp_error( $resolved_media_dir ) ) {
				WP_CLI::warning( $resolved_media_dir->get_error_message() );
				$resolved_media_dir = '';
			}
		}

		$result = bilky_sql_importer_run(
			$file,
			array(
				'lang'           => $lang,
				'only'           => $only,
				'media_base_url' => $media_base_url,
				'media_base_dir' => $resolved_media_dir,
				'import_images'  => $import_images,
				'dry_run'        => $dry_run,
			)
		);

		if ( is_wp_error( $result ) ) {
			WP_CLI::error( $result->get_error_message() );
		}

		WP_CLI::success( 'Importación finalizada.' );
	}
}

// Registrar el comando WP-CLI (si aplica) una vez definida la clase.
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'bilky import-sql', 'Bilky_SQL_Import_Command' );
}

/**
 * Ruta por defecto al dump SQL dentro del repo/tema.
 *
 * @return string
 */
function bilky_sql_importer_default_file() {
	return trailingslashit( get_template_directory() ) . 'docu/frontend_bilky-20250119.sql/frontend_bilky-20250119.sql';
}

/**
 * Media base URL por defecto (assets históricos del frontend).
 *
 * @return string
 */
function bilky_sql_importer_default_media_base_url() {
	return 'https://assets.bilky.es/frontend';
}

/**
 * Ejecuta la importación.
 *
 * @param string $file
 * @param array  $options
 * @return array|WP_Error
 */
function bilky_sql_importer_run( $file, $options = array() ) {
	$defaults = array(
		'lang'           => 'es',
		'only'           => array( 'posts', 'clients', 'partners', 'articles' ),
		'media_base_url' => bilky_sql_importer_default_media_base_url(),
		'media_base_dir' => bilky_sql_importer_default_media_dir(),
		'import_images'  => true,
		'dry_run'        => false,
	);
	$options = wp_parse_args( $options, $defaults );

	$lang            = (string) $options['lang'];
	$only            = is_array( $options['only'] ) ? $options['only'] : array();
	$media_base_url  = (string) $options['media_base_url'];
	$media_base_dir  = (string) $options['media_base_dir'];
	$import_images   = (bool) $options['import_images'];
	$dry_run         = (bool) $options['dry_run'];

	// Compat: si alguien pasa images_base_url (versión anterior), usarlo como media_base_url.
	if ( $media_base_url === '' && isset( $options['images_base_url'] ) && is_string( $options['images_base_url'] ) ) {
		$media_base_url = (string) $options['images_base_url'];
	}

	if ( ! file_exists( $file ) ) {
		return new WP_Error( 'bilky_sql_importer_missing_file', 'No se encuentra el fichero SQL: ' . $file );
	}

	// Intentar ejecutar con un admin para evitar filtrados de HTML.
	$admin_ids = get_users(
		array(
			'role'   => 'administrator',
			'number' => 1,
			'fields' => 'ID',
		)
	);
	if ( ! empty( $admin_ids ) ) {
		wp_set_current_user( (int) $admin_ids[0] );
	}

	$wanted_tables = array(
		'categories',
		'tags',
		'article_translations',
		'articles',
		'posts',
		'clients',
		'partners',
	);

	$data = bilky_sql_load_dump_data( $file, $wanted_tables );
	if ( is_wp_error( $data ) ) {
		return $data;
	}

	$summary = array(
		'posts'    => array( 'created' => 0, 'updated' => 0, 'skipped' => 0 ),
		'clients'  => array( 'created' => 0, 'updated' => 0, 'skipped' => 0 ),
		'partners' => array( 'created' => 0, 'updated' => 0, 'skipped' => 0 ),
		'articles' => array( 'created' => 0, 'updated' => 0, 'skipped' => 0 ),
	);

	$category_term_map = array();
	$post_tag_term_map = array();

	if ( in_array( 'articles', $only, true ) && ! empty( $data['categories'] ) ) {
		$category_term_map = bilky_sql_import_categories_as_wp_categories( $data['categories'], $lang, $dry_run );
	}

	if ( in_array( 'posts', $only, true ) && ! empty( $data['tags'] ) ) {
		$post_tag_term_map = bilky_sql_import_tags_as_wp_post_tags( $data['tags'], $lang, $dry_run );
	}

	if ( in_array( 'clients', $only, true ) && ! empty( $data['clients'] ) ) {
		$clients_result = bilky_sql_import_clients(
			$data['clients'],
			$lang,
			array(
				'base_url'     => $media_base_url,
				'base_dir'     => $media_base_dir,
				'import'       => $import_images,
			),
			$dry_run
		);
		if ( is_wp_error( $clients_result ) ) {
			return $clients_result;
		}
		$summary['clients'] = $clients_result;
	}

	if ( in_array( 'partners', $only, true ) && ! empty( $data['partners'] ) ) {
		$partners_result = bilky_sql_import_partners(
			$data['partners'],
			$lang,
			array(
				'base_url' => $media_base_url,
				'base_dir' => $media_base_dir,
				'import'   => $import_images,
			),
			$dry_run
		);
		if ( is_wp_error( $partners_result ) ) {
			return $partners_result;
		}
		$summary['partners'] = $partners_result;
	}

	if ( in_array( 'posts', $only, true ) && ! empty( $data['posts'] ) ) {
		$posts_result = bilky_sql_import_posts(
			$data['posts'],
			$post_tag_term_map,
			$lang,
			array(
				'base_url' => $media_base_url,
				'base_dir' => $media_base_dir,
				'import'   => $import_images,
			),
			$dry_run
		);
		if ( is_wp_error( $posts_result ) ) {
			return $posts_result;
		}
		$summary['posts'] = $posts_result;
	}

	if ( in_array( 'articles', $only, true ) && ! empty( $data['articles'] ) ) {
		$translations_by_article_id = bilky_sql_group_article_translations( $data['article_translations'], $lang );
		$articles_result            = bilky_sql_import_articles(
			$data['articles'],
			$translations_by_article_id,
			$category_term_map,
			$lang,
			array(
				'base_url' => $media_base_url,
				'base_dir' => $media_base_dir,
				'import'   => $import_images,
			),
			$dry_run
		);
		if ( is_wp_error( $articles_result ) ) {
			return $articles_result;
		}
		$summary['articles'] = $articles_result;
	}

	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		WP_CLI::line( '' );
		WP_CLI::line( 'Resumen:' );
		foreach ( $summary as $section => $counts ) {
			WP_CLI::line(
				sprintf(
					'- %s: creados=%d, actualizados=%d, omitidos=%d',
					$section,
					(int) $counts['created'],
					(int) $counts['updated'],
					(int) $counts['skipped']
				)
			);
		}
		if ( $dry_run ) {
			WP_CLI::warning( 'Modo dry-run: no se han escrito cambios.' );
		}
	}

	return $summary;
}

/**
 * Carga datos del dump SQL para un conjunto de tablas.
 *
 * @param string $file
 * @param array  $tables
 * @return array|WP_Error
 */
function bilky_sql_load_dump_data( $file, $tables ) {
	$tables = is_array( $tables ) ? $tables : array();

	$columns_by_table = array(
		'clients'              => array( 'id', 'order', 'name', 'url', 'testimonial', 'logo', 'color', 'type', 'priority', 'created_at', 'updated_at' ),
		'partners'             => array( 'id', 'order', 'priority', 'name', 'url', 'information', 'type', 'logo', 'created_at', 'updated_at' ),
		'posts'                => array( 'id', 'status', 'slug', 'title', 'preview_text', 'content', 'tags', 'seo_title', 'seo_description', 'seo_keywords', 'cover_image', 'content_image', 'thumbnail', 'published_at', 'featured', 'active_languages', 'visits', 'created_at', 'updated_at' ),
		'articles'             => array( 'id', 'category_id', 'tags', 'status', 'slug', 'title', 'text', 'video_type', 'video', 'active_languages', 'featured', 'visits', 'created_at', 'updated_at' ),
		'article_translations' => array( 'id', 'article_id', 'order', 'title', 'text', 'image', 'created_at', 'updated_at' ),
		'categories'           => array( 'id', 'name', 'slug', 'order', 'icon', 'color', 'created_at', 'updated_at' ),
		'tags'                 => array( 'id', 'type', 'category_id', 'name', 'slug', 'created_at', 'updated_at' ),
	);

	$initial = array();
	foreach ( $tables as $t ) {
		$initial[ $t ] = array();
	}

	$handle = fopen( $file, 'rb' );
	if ( ! $handle ) {
		return new WP_Error( 'bilky_sql_importer_open_failed', 'No se pudo abrir el fichero SQL: ' . $file );
	}

	while ( ( $line = fgets( $handle ) ) !== false ) {
		$line_trim = ltrim( $line );
		if ( strncmp( $line_trim, 'INSERT INTO `', 13 ) !== 0 ) {
			continue;
		}

		// Extraer el nombre de tabla sin parsear VALUES (evita procesar tablas enormes no deseadas).
		$table_start = 13;
		$table_end   = strpos( $line_trim, '`', $table_start );
		if ( $table_end === false ) {
			continue;
		}

		$table = substr( $line_trim, $table_start, $table_end - $table_start );
		if ( $table === '' ) {
			continue;
		}

		if ( ! isset( $initial[ $table ] ) ) {
			continue;
		}

		if ( ! isset( $columns_by_table[ $table ] ) ) {
			continue;
		}

		$parsed = bilky_sql_parse_insert_line( $line_trim );
		if ( ! is_array( $parsed ) ) {
			continue;
		}

		$columns = $columns_by_table[ $table ];
		foreach ( $parsed['rows'] as $row ) {
			if ( count( $row ) !== count( $columns ) ) {
				continue;
			}
			$assoc = array_combine( $columns, $row );
			if ( is_array( $assoc ) ) {
				$initial[ $table ][] = $assoc;
			}
		}
	}

	fclose( $handle );
	return $initial;
}

/**
 * Parsea una línea INSERT INTO `table` VALUES (...),(...);
 *
 * @param string $line
 * @return array|null { table: string, rows: array<array<mixed>> }
 */
function bilky_sql_parse_insert_line( $line ) {
	$line = trim( $line );
	if ( $line === '' ) {
		return null;
	}

	if ( strncmp( $line, 'INSERT INTO `', 13 ) !== 0 ) {
		return null;
	}

	$table_start = 13;
	$table_end   = strpos( $line, '`', $table_start );
	if ( $table_end === false ) {
		return null;
	}

	$table = substr( $line, $table_start, $table_end - $table_start );
	if ( $table === '' ) {
		return null;
	}

	$values_pos = strpos( $line, ' VALUES ' );
	if ( $values_pos === false ) {
		return null;
	}

	$values_part = substr( $line, $values_pos + 8 ); // strlen(' VALUES ') === 8
	$values_part = rtrim( $values_part );
	if ( $values_part === '' ) {
		return null;
	}
	if ( substr( $values_part, -1 ) === ';' ) {
		$values_part = substr( $values_part, 0, -1 );
	}

	return array(
		'table' => $table,
		'rows'  => bilky_sql_parse_insert_values( $values_part ),
	);
}

/**
 * Parsea la parte VALUES de un INSERT.
 *
 * @param string $values_part Ej: "(1,'a',NULL),(2,'b',NULL)"
 * @return array
 */
function bilky_sql_parse_insert_values( $values_part ) {
	$rows = array();
	$len  = strlen( $values_part );
	$i    = 0;

	while ( $i < $len ) {
		// Saltar separadores entre filas.
		while ( $i < $len ) {
			$ch = $values_part[ $i ];
			if ( $ch === '(' ) {
				break;
			}
			$i++;
		}

		if ( $i >= $len || $values_part[ $i ] !== '(' ) {
			break;
		}

		$i++; // entrar en '('
		$row        = array();
		$token      = '';
		$in_string  = false;
		$was_quoted = false;

		while ( $i < $len ) {
			$ch = $values_part[ $i ];

			if ( $in_string ) {
				if ( $ch === '\\' ) {
					$i++;
					if ( $i >= $len ) {
						break;
					}
					$esc    = $values_part[ $i ];
					$token .= bilky_sql_unescape_mysql_char( $esc );
					$i++;
					continue;
				}
				if ( $ch === "'" ) {
					$in_string = false;
					$i++;
					continue;
				}

				$token .= $ch;
				$i++;
				continue;
			}

			// No estamos dentro de string.
			if ( $ch === "'" ) {
				$in_string  = true;
				$was_quoted = true;
				$i++;
				continue;
			}

			if ( $ch === ',' ) {
				$row[]      = bilky_sql_cast_token( $token, $was_quoted );
				$token      = '';
				$was_quoted = false;
				$i++;
				continue;
			}

			if ( $ch === ')' ) {
				$row[] = bilky_sql_cast_token( $token, $was_quoted );
				$token = '';
				$i++; // salir de ')'
				break;
			}

			$token .= $ch;
			$i++;
		}

		$rows[] = $row;
		$i++; // avanzar por si hay coma u otros separadores
	}

	return $rows;
}

/**
 * Convierte un token SQL a valor PHP.
 *
 * @param string $token
 * @param bool   $was_quoted
 * @return mixed
 */
function bilky_sql_cast_token( $token, $was_quoted ) {
	if ( $was_quoted ) {
		return (string) $token;
	}

	$trim = trim( $token );
	if ( $trim === '' ) {
		return '';
	}

	if ( strtoupper( $trim ) === 'NULL' ) {
		return null;
	}

	if ( preg_match( '/^-?\d+$/', $trim ) ) {
		return (int) $trim;
	}

	if ( preg_match( '/^-?\d+\.\d+$/', $trim ) ) {
		return (float) $trim;
	}

	return $trim;
}

/**
 * Unescape básico de caracteres en strings de mysqldump.
 *
 * @param string $ch
 * @return string
 */
function bilky_sql_unescape_mysql_char( $ch ) {
	switch ( $ch ) {
		case '0':
			return "\0";
		case 'b':
			return "\b";
		case 'n':
			return "\n";
		case 'r':
			return "\r";
		case 't':
			return "\t";
		case 'Z':
			return chr( 26 );
		case "'":
			return "'";
		case '"':
			return '"';
		case '\\':
			return '\\';
		default:
			// En MySQL, \X suele equivaler a X (backslash removido).
			return (string) $ch;
	}
}

/**
 * Extrae el valor de un JSON multilenguaje, priorizando $lang.
 *
 * @param mixed  $value
 * @param string $lang
 * @return string
 */
function bilky_sql_json_lang( $value, $lang ) {
	if ( $value === null ) {
		return '';
	}
	if ( is_string( $value ) ) {
		$value = trim( $value );
		if ( $value === '' ) {
			return '';
		}
		$decoded = json_decode( $value, true );
		if ( json_last_error() === JSON_ERROR_NONE && is_array( $decoded ) ) {
			if ( isset( $decoded[ $lang ] ) && is_string( $decoded[ $lang ] ) && trim( $decoded[ $lang ] ) !== '' ) {
				return $decoded[ $lang ];
			}
			// Fallbacks comunes.
			foreach ( array( 'es', 'en' ) as $fallback ) {
				if ( isset( $decoded[ $fallback ] ) && is_string( $decoded[ $fallback ] ) && trim( $decoded[ $fallback ] ) !== '' ) {
					return $decoded[ $fallback ];
				}
			}
			// Último fallback: primer string no vacío.
			foreach ( $decoded as $v ) {
				if ( is_string( $v ) && trim( $v ) !== '' ) {
					return $v;
				}
			}
			return '';
		}
		return $value;
	}

	return (string) $value;
}

/**
 * Decodifica un JSON a array (si aplica).
 *
 * @param mixed $value
 * @return array
 */
function bilky_sql_json_array( $value ) {
	if ( $value === null ) {
		return array();
	}
	if ( ! is_string( $value ) ) {
		return array();
	}
	$value = trim( $value );
	if ( $value === '' ) {
		return array();
	}

	$decoded = json_decode( $value, true );
	if ( json_last_error() === JSON_ERROR_NONE && is_array( $decoded ) ) {
		return $decoded;
	}

	return array();
}

/**
 * Importa categorías del SQL como términos de la taxonomía nativa "category".
 *
 * @param array  $rows
 * @param string $lang
 * @param bool   $dry_run
 * @return array Mapa category_id(SQL) => term_id(WP)
 */
function bilky_sql_import_categories_as_wp_categories( $rows, $lang, $dry_run ) {
	$map = array();

	foreach ( $rows as $row ) {
		$cat_id = isset( $row['id'] ) ? (int) $row['id'] : 0;
		if ( $cat_id <= 0 ) {
			continue;
		}

		$name = bilky_sql_json_lang( isset( $row['name'] ) ? $row['name'] : '', $lang );
		$slug = bilky_sql_json_lang( isset( $row['slug'] ) ? $row['slug'] : '', $lang );

		$name = $name !== '' ? $name : 'Categoría ' . $cat_id;
		$slug = $slug !== '' ? sanitize_title( $slug ) : sanitize_title( $name );

		$term = term_exists( $slug, 'category' );
		if ( $term && ! is_wp_error( $term ) ) {
			$term_id      = (int) ( is_array( $term ) ? $term['term_id'] : $term );
			$map[ $cat_id ] = $term_id;

			if ( ! $dry_run ) {
				$existing = get_term( $term_id, 'category' );
				if ( $existing && ! is_wp_error( $existing ) && $existing->name !== $name ) {
					wp_update_term( $term_id, 'category', array( 'name' => $name ) );
				}
				if ( isset( $row['icon'] ) && is_string( $row['icon'] ) && $row['icon'] !== '' ) {
					update_term_meta( $term_id, 'bilky_icon', $row['icon'] );
				}
				if ( isset( $row['color'] ) && is_string( $row['color'] ) && $row['color'] !== '' ) {
					update_term_meta( $term_id, 'bilky_color', $row['color'] );
				}
				if ( isset( $row['order'] ) && $row['order'] !== null && $row['order'] !== '' ) {
					update_term_meta( $term_id, 'bilky_order', $row['order'] );
				}
			}
			continue;
		}

		if ( $dry_run ) {
			// Crear un ID virtual para el mapa en dry-run.
			$map[ $cat_id ] = 0;
			continue;
		}

		$inserted = wp_insert_term(
			$name,
			'category',
			array(
				'slug' => $slug,
			)
		);
		if ( is_wp_error( $inserted ) ) {
			continue;
		}

		$term_id        = (int) $inserted['term_id'];
		$map[ $cat_id ] = $term_id;

		// Marcar término como creado por el importador (para poder borrarlo en bloque).
		update_term_meta( $term_id, '_bilky_sql_imported', '1' );
		update_term_meta( $term_id, '_bilky_sql_import_created', '1' );
		update_term_meta( $term_id, '_bilky_sql_imported_at', current_time( 'mysql' ) );
		update_term_meta( $term_id, '_bilky_sql_table', 'categories' );
		update_term_meta( $term_id, '_bilky_sql_id', (string) $cat_id );

		if ( isset( $row['icon'] ) && is_string( $row['icon'] ) && $row['icon'] !== '' ) {
			update_term_meta( $term_id, 'bilky_icon', $row['icon'] );
		}
		if ( isset( $row['color'] ) && is_string( $row['color'] ) && $row['color'] !== '' ) {
			update_term_meta( $term_id, 'bilky_color', $row['color'] );
		}
		if ( isset( $row['order'] ) && $row['order'] !== null && $row['order'] !== '' ) {
			update_term_meta( $term_id, 'bilky_order', $row['order'] );
		}
	}

	return $map;
}

/**
 * Importa tags del SQL (type=post) como términos "post_tag".
 *
 * @param array  $rows
 * @param string $lang
 * @param bool   $dry_run
 * @return array Mapa tag_id(SQL) => term_id(WP)
 */
function bilky_sql_import_tags_as_wp_post_tags( $rows, $lang, $dry_run ) {
	$map = array();

	foreach ( $rows as $row ) {
		if ( ! isset( $row['id'] ) ) {
			continue;
		}
		if ( ! isset( $row['type'] ) || $row['type'] !== 'post' ) {
			continue;
		}

		$tag_id = (int) $row['id'];
		if ( $tag_id <= 0 ) {
			continue;
		}

		$name = bilky_sql_json_lang( isset( $row['name'] ) ? $row['name'] : '', $lang );
		$slug = bilky_sql_json_lang( isset( $row['slug'] ) ? $row['slug'] : '', $lang );

		$name = $name !== '' ? $name : 'Tag ' . $tag_id;
		$slug = $slug !== '' ? sanitize_title( $slug ) : sanitize_title( $name );

		$term = term_exists( $slug, 'post_tag' );
		if ( $term && ! is_wp_error( $term ) ) {
			$term_id      = (int) ( is_array( $term ) ? $term['term_id'] : $term );
			$map[ $tag_id ] = $term_id;
			continue;
		}

		if ( $dry_run ) {
			$map[ $tag_id ] = 0;
			continue;
		}

		$inserted = wp_insert_term(
			$name,
			'post_tag',
			array(
				'slug' => $slug,
			)
		);
		if ( is_wp_error( $inserted ) ) {
			continue;
		}

		$map[ $tag_id ] = (int) $inserted['term_id'];

		// Marcar término como creado por el importador (para poder borrarlo en bloque).
		update_term_meta( (int) $inserted['term_id'], '_bilky_sql_imported', '1' );
		update_term_meta( (int) $inserted['term_id'], '_bilky_sql_import_created', '1' );
		update_term_meta( (int) $inserted['term_id'], '_bilky_sql_imported_at', current_time( 'mysql' ) );
		update_term_meta( (int) $inserted['term_id'], '_bilky_sql_table', 'tags' );
		update_term_meta( (int) $inserted['term_id'], '_bilky_sql_id', (string) $tag_id );
	}

	return $map;
}

/**
 * Agrupa traducciones de artículos por article_id.
 *
 * @param array  $rows
 * @param string $lang
 * @return array
 */
function bilky_sql_group_article_translations( $rows, $lang ) {
	$grouped = array();

	foreach ( $rows as $row ) {
		$article_id = isset( $row['article_id'] ) ? (int) $row['article_id'] : 0;
		if ( $article_id <= 0 ) {
			continue;
		}

		$order = isset( $row['order'] ) ? (int) $row['order'] : 0;
		$title = bilky_sql_json_lang( isset( $row['title'] ) ? $row['title'] : '', $lang );
		$text  = bilky_sql_json_lang( isset( $row['text'] ) ? $row['text'] : '', $lang );
		$image = bilky_sql_json_lang( isset( $row['image'] ) ? $row['image'] : '', $lang );

		if ( ! isset( $grouped[ $article_id ] ) ) {
			$grouped[ $article_id ] = array();
		}

		$grouped[ $article_id ][] = array(
			'order' => $order,
			'title' => $title,
			'text'  => $text,
			'image' => $image,
		);
	}

	// Ordenar por "order".
	foreach ( $grouped as $article_id => $steps ) {
		usort(
			$steps,
			function ( $a, $b ) {
				return (int) $a['order'] <=> (int) $b['order'];
			}
		);
		$grouped[ $article_id ] = $steps;
	}

	return $grouped;
}

/**
 * Importa clientes (SQL clients) al CPT clientes.
 *
 * @param array  $rows
 * @param string $lang
 * @param bool   $dry_run
 * @return array|WP_Error
 */
function bilky_sql_import_clients( $rows, $lang, $media, $dry_run ) {
	$counts = array( 'created' => 0, 'updated' => 0, 'skipped' => 0 );

	$media = is_array( $media ) ? $media : array();
	$media_base_url = isset( $media['base_url'] ) ? (string) $media['base_url'] : '';
	$media_base_dir = isset( $media['base_dir'] ) ? (string) $media['base_dir'] : '';
	$import_images  = isset( $media['import'] ) ? (bool) $media['import'] : true;

	foreach ( $rows as $row ) {
		$sql_id = isset( $row['id'] ) ? (int) $row['id'] : 0;
		if ( $sql_id <= 0 ) {
			$counts['skipped']++;
			continue;
		}

		$name = isset( $row['name'] ) ? (string) $row['name'] : '';
		$name = trim( $name );
		if ( $name === '' ) {
			$counts['skipped']++;
			continue;
		}

		$url         = isset( $row['url'] ) ? (string) $row['url'] : '';
		$order_value = isset( $row['order'] ) ? $row['order'] : null;
		$priority    = isset( $row['priority'] ) ? $row['priority'] : null;
		$type        = isset( $row['type'] ) ? (string) $row['type'] : '';
		$testimonial = isset( $row['testimonial'] ) ? (string) $row['testimonial'] : '';

		$slug = sanitize_title( $name );

		$existing_id = bilky_sql_find_existing_post_id(
			'clientes',
			'clients',
			$sql_id,
			$slug,
			'cliente_url',
			$url
		);

		$postarr = array(
			'post_type'    => 'clientes',
			'post_title'   => $name,
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_content' => $testimonial,
		);

		if ( isset( $row['created_at'] ) && is_string( $row['created_at'] ) && $row['created_at'] !== '' ) {
			$postarr['post_date'] = $row['created_at'];
		}
		if ( isset( $row['updated_at'] ) && is_string( $row['updated_at'] ) && $row['updated_at'] !== '' ) {
			$postarr['post_modified'] = $row['updated_at'];
		}

		$order_int = is_numeric( $order_value ) ? (int) $order_value : 0;
		if ( $order_int > 0 ) {
			$postarr['menu_order'] = $order_int;
		}

		$post_id = 0;
		if ( $dry_run ) {
			$post_id = $existing_id ? $existing_id : 0;
		} else {
			$post_id = bilky_sql_upsert_post( $existing_id, $postarr );
			if ( is_wp_error( $post_id ) ) {
				return $post_id;
			}
		}

		if ( $existing_id ) {
			$counts['updated']++;
		} else {
			$counts['created']++;
		}

		if ( $dry_run || ! $post_id ) {
			continue;
		}

		// Meta de trazabilidad.
		update_post_meta( $post_id, '_bilky_sql_table', 'clients' );
		update_post_meta( $post_id, '_bilky_sql_id', (string) $sql_id );
		update_post_meta( $post_id, '_bilky_sql_imported', '1' );
		update_post_meta( $post_id, '_bilky_sql_imported_at', current_time( 'mysql' ) );
		if ( ! $existing_id ) {
			update_post_meta( $post_id, '_bilky_sql_import_created', '1' );
		}

		// ACF / Custom fields.
		if ( function_exists( 'update_field' ) ) {
			update_field( 'field_cliente_url', $url, $post_id );
			update_field( 'field_cliente_order', $order_int > 0 ? $order_int : null, $post_id );
		} else {
			update_post_meta( $post_id, 'cliente_url', $url );
			update_post_meta( $post_id, 'cliente_order', $order_int > 0 ? $order_int : '' );
		}
		if ( $priority !== null && $priority !== '' ) {
			update_post_meta( $post_id, 'cliente_priority', $priority );
		}
		if ( isset( $row['logo'] ) && is_string( $row['logo'] ) && $row['logo'] !== '' ) {
			update_post_meta( $post_id, 'cliente_logo_filename', $row['logo'] );
		}
		if ( isset( $row['color'] ) && is_string( $row['color'] ) && $row['color'] !== '' ) {
			update_post_meta( $post_id, 'cliente_color', $row['color'] );
		}
		if ( $type !== '' ) {
			update_post_meta( $post_id, 'cliente_type_source', $type );
		}

		// Tipo (empresa / asesor) -> taxonomía existente.
		$tipo_slug = '';
		$tipo_name = '';
		if ( $type === 'consultant' ) {
			$tipo_slug = 'asesor';
			$tipo_name = 'Asesor';
		} elseif ( $type === 'business' ) {
			$tipo_slug = 'empresa';
			$tipo_name = 'Empresa';
		} elseif ( $type !== '' ) {
			$tipo_slug = sanitize_title( $type );
			$tipo_name = ucfirst( str_replace( '-', ' ', $tipo_slug ) );
		}

		if ( $tipo_slug !== '' ) {
			$term_id = bilky_sql_ensure_term( $tipo_slug, $tipo_name, 'cliente_categoria' );
			if ( $term_id ) {
				wp_set_object_terms( $post_id, array( $term_id ), 'cliente_categoria', false );
			}
		}

		// Imagen (logo) -> imagen destacada.
		if ( $import_images && isset( $row['logo'] ) && is_string( $row['logo'] ) && trim( $row['logo'] ) !== '' ) {
			$logo_filename  = trim( $row['logo'] );
			$logo_for_import = $logo_filename;
			if ( $logo_for_import !== '' && strpos( $logo_for_import, '/' ) === false && strpos( $logo_for_import, '\\' ) === false ) {
				$logo_for_import = 'references/clients/' . $logo_for_import;
			}
			$ids           = bilky_sql_importer_attach_images_to_post(
				$post_id,
				array( $logo_for_import ),
				array(
					'base_url' => $media_base_url,
					'base_dir' => $media_base_dir,
				),
				array(
					'alt'                => $name,
					'additional_meta_key' => 'bilky_import_additional_images',
				),
				$dry_run
			);

			// Mantener compatibilidad: aunque no se pueda importar la imagen, no se aborta el import.
			if ( is_wp_error( $ids ) ) {
				update_post_meta( $post_id, 'bilky_import_image_error', $ids->get_error_message() );
			}
		}
	}

	return $counts;
}

/**
 * Importa partners (SQL partners) al CPT partners.
 *
 * @param array  $rows
 * @param string $lang
 * @param bool   $dry_run
 * @return array|WP_Error
 */
function bilky_sql_import_partners( $rows, $lang, $media, $dry_run ) {
	$counts = array( 'created' => 0, 'updated' => 0, 'skipped' => 0 );

	$media = is_array( $media ) ? $media : array();
	$media_base_url = isset( $media['base_url'] ) ? (string) $media['base_url'] : '';
	$media_base_dir = isset( $media['base_dir'] ) ? (string) $media['base_dir'] : '';
	$import_images  = isset( $media['import'] ) ? (bool) $media['import'] : true;

	foreach ( $rows as $row ) {
		$sql_id = isset( $row['id'] ) ? (int) $row['id'] : 0;
		if ( $sql_id <= 0 ) {
			$counts['skipped']++;
			continue;
		}

		$name = isset( $row['name'] ) ? (string) $row['name'] : '';
		$name = trim( $name );
		if ( $name === '' ) {
			$counts['skipped']++;
			continue;
		}

		$url         = isset( $row['url'] ) ? (string) $row['url'] : '';
		$info        = isset( $row['information'] ) ? (string) $row['information'] : '';
		$order_value = isset( $row['order'] ) ? $row['order'] : null;
		$priority    = isset( $row['priority'] ) ? $row['priority'] : null;
		$type        = isset( $row['type'] ) ? (string) $row['type'] : '';

		$slug = sanitize_title( $name );

		$existing_id = bilky_sql_find_existing_post_id(
			'partners',
			'partners',
			$sql_id,
			$slug,
			'partner_url',
			$url
		);

		$postarr = array(
			'post_type'    => 'partners',
			'post_title'   => $name,
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_content' => $info,
		);

		if ( isset( $row['created_at'] ) && is_string( $row['created_at'] ) && $row['created_at'] !== '' ) {
			$postarr['post_date'] = $row['created_at'];
		}
		if ( isset( $row['updated_at'] ) && is_string( $row['updated_at'] ) && $row['updated_at'] !== '' ) {
			$postarr['post_modified'] = $row['updated_at'];
		}

		$order_int = is_numeric( $order_value ) ? (int) $order_value : 0;
		if ( $order_int > 0 ) {
			$postarr['menu_order'] = $order_int;
		}

		$post_id = 0;
		if ( $dry_run ) {
			$post_id = $existing_id ? $existing_id : 0;
		} else {
			$post_id = bilky_sql_upsert_post( $existing_id, $postarr );
			if ( is_wp_error( $post_id ) ) {
				return $post_id;
			}
		}

		if ( $existing_id ) {
			$counts['updated']++;
		} else {
			$counts['created']++;
		}

		if ( $dry_run || ! $post_id ) {
			continue;
		}

		update_post_meta( $post_id, '_bilky_sql_table', 'partners' );
		update_post_meta( $post_id, '_bilky_sql_id', (string) $sql_id );
		update_post_meta( $post_id, '_bilky_sql_imported', '1' );
		update_post_meta( $post_id, '_bilky_sql_imported_at', current_time( 'mysql' ) );
		if ( ! $existing_id ) {
			update_post_meta( $post_id, '_bilky_sql_import_created', '1' );
		}

		if ( function_exists( 'update_field' ) ) {
			update_field( 'field_partner_url', $url, $post_id );
			update_field( 'field_partner_order', $order_int > 0 ? $order_int : null, $post_id );
			update_field( 'field_partner_priority', $priority !== null && $priority !== '' ? $priority : null, $post_id );
		} else {
			update_post_meta( $post_id, 'partner_url', $url );
			update_post_meta( $post_id, 'partner_order', $order_int > 0 ? $order_int : '' );
			update_post_meta( $post_id, 'partner_priority', $priority !== null && $priority !== '' ? $priority : '' );
		}

		if ( isset( $row['logo'] ) && is_string( $row['logo'] ) && $row['logo'] !== '' ) {
			update_post_meta( $post_id, 'partner_logo_filename', $row['logo'] );
		}
		if ( $type !== '' ) {
			update_post_meta( $post_id, 'partner_type_source', $type );
		}

		// Tipo -> taxonomía custom.
		if ( $type !== '' ) {
			$tipo_slug = sanitize_title( $type );
			$tipo_name = ucfirst( str_replace( '-', ' ', $tipo_slug ) );
			$term_id   = bilky_sql_ensure_term( $tipo_slug, $tipo_name, 'partner_tipo' );
			if ( $term_id ) {
				wp_set_object_terms( $post_id, array( $term_id ), 'partner_tipo', false );
			}
		}

		// Imagen (logo) -> imagen destacada.
		if ( $import_images && isset( $row['logo'] ) && is_string( $row['logo'] ) && trim( $row['logo'] ) !== '' ) {
			$logo_filename  = trim( $row['logo'] );
			$logo_for_import = $logo_filename;
			if ( $logo_for_import !== '' && strpos( $logo_for_import, '/' ) === false && strpos( $logo_for_import, '\\' ) === false ) {
				$logo_for_import = 'references/partners/' . $logo_for_import;
			}
			$ids           = bilky_sql_importer_attach_images_to_post(
				$post_id,
				array( $logo_for_import ),
				array(
					'base_url' => $media_base_url,
					'base_dir' => $media_base_dir,
				),
				array(
					'alt'                => $name,
					'additional_meta_key' => 'bilky_import_additional_images',
				),
				$dry_run
			);

			if ( is_wp_error( $ids ) ) {
				update_post_meta( $post_id, 'bilky_import_image_error', $ids->get_error_message() );
			}
		}
	}

	return $counts;
}

/**
 * Importa posts del SQL al post_type "post".
 *
 * @param array  $rows
 * @param array  $post_tag_term_map Mapa tag_id(SQL) => term_id(WP)
 * @param string $lang
 * @param bool   $dry_run
 * @return array|WP_Error
 */
function bilky_sql_import_posts( $rows, $post_tag_term_map, $lang, $media, $dry_run ) {
	$counts = array( 'created' => 0, 'updated' => 0, 'skipped' => 0 );

	$media = is_array( $media ) ? $media : array();
	$media_base_url = isset( $media['base_url'] ) ? (string) $media['base_url'] : '';
	$media_base_dir = isset( $media['base_dir'] ) ? (string) $media['base_dir'] : '';
	$import_images  = isset( $media['import'] ) ? (bool) $media['import'] : true;

	foreach ( $rows as $row ) {
		$sql_id = isset( $row['id'] ) ? (int) $row['id'] : 0;
		if ( $sql_id <= 0 ) {
			$counts['skipped']++;
			continue;
		}

		$slug  = bilky_sql_json_lang( isset( $row['slug'] ) ? $row['slug'] : '', $lang );
		$title = bilky_sql_json_lang( isset( $row['title'] ) ? $row['title'] : '', $lang );

		$slug  = $slug !== '' ? sanitize_title( $slug ) : '';
		$title = $title !== '' ? $title : 'Post ' . $sql_id;

		if ( $slug === '' ) {
			$slug = sanitize_title( $title );
		}

		$excerpt = bilky_sql_json_lang( isset( $row['preview_text'] ) ? $row['preview_text'] : '', $lang );
		$content = bilky_sql_json_lang( isset( $row['content'] ) ? $row['content'] : '', $lang );

		$status_src = isset( $row['status'] ) ? (string) $row['status'] : '';
		$status     = $status_src === 'published' ? 'publish' : 'draft';

		$existing_id = bilky_sql_find_existing_post_id( 'post', 'posts', $sql_id, $slug, null, null );

		$postarr = array(
			'post_type'    => 'post',
			'post_title'   => $title,
			'post_status'  => $status,
			'post_name'    => $slug,
			'post_excerpt' => $excerpt,
			'post_content' => $content,
		);

		// Fecha.
		$created_at   = isset( $row['created_at'] ) && is_string( $row['created_at'] ) ? $row['created_at'] : '';
		$updated_at   = isset( $row['updated_at'] ) && is_string( $row['updated_at'] ) ? $row['updated_at'] : '';
		$published_at = isset( $row['published_at'] ) && is_string( $row['published_at'] ) ? $row['published_at'] : '';

		if ( $created_at !== '' ) {
			$postarr['post_date'] = $created_at;
		}
		if ( $updated_at !== '' ) {
			$postarr['post_modified'] = $updated_at;
		}
		if ( $published_at !== '' ) {
			$time = $created_at !== '' && strlen( $created_at ) >= 19 ? substr( $created_at, 11, 8 ) : '00:00:00';
			$postarr['post_date'] = $published_at . ' ' . $time;
		}

		$post_id = 0;
		if ( $dry_run ) {
			$post_id = $existing_id ? $existing_id : 0;
		} else {
			$post_id = bilky_sql_upsert_post( $existing_id, $postarr );
			if ( is_wp_error( $post_id ) ) {
				return $post_id;
			}
		}

		if ( $existing_id ) {
			$counts['updated']++;
		} else {
			$counts['created']++;
		}

		if ( $dry_run || ! $post_id ) {
			continue;
		}

		update_post_meta( $post_id, '_bilky_sql_table', 'posts' );
		update_post_meta( $post_id, '_bilky_sql_id', (string) $sql_id );
		update_post_meta( $post_id, '_bilky_sql_imported', '1' );
		update_post_meta( $post_id, '_bilky_sql_imported_at', current_time( 'mysql' ) );
		if ( ! $existing_id ) {
			update_post_meta( $post_id, '_bilky_sql_import_created', '1' );
		}

		// SEO (guardado como meta propia, sin depender de plugins).
		$seo_title       = bilky_sql_json_lang( isset( $row['seo_title'] ) ? $row['seo_title'] : '', $lang );
		$seo_description = bilky_sql_json_lang( isset( $row['seo_description'] ) ? $row['seo_description'] : '', $lang );
		$seo_keywords    = bilky_sql_json_lang( isset( $row['seo_keywords'] ) ? $row['seo_keywords'] : '', $lang );

		if ( $seo_title !== '' ) {
			update_post_meta( $post_id, '_bilky_seo_title', $seo_title );
		}
		if ( $seo_description !== '' ) {
			update_post_meta( $post_id, '_bilky_seo_description', $seo_description );
		}
		if ( $seo_keywords !== '' ) {
			update_post_meta( $post_id, '_bilky_seo_keywords', $seo_keywords );
		}

		// Imágenes (solo almacenar filename para futuras migraciones de media).
		foreach ( array( 'cover_image', 'content_image', 'thumbnail' ) as $img_key ) {
			if ( isset( $row[ $img_key ] ) && is_string( $row[ $img_key ] ) && $row[ $img_key ] !== '' ) {
				update_post_meta( $post_id, 'bilky_' . $img_key . '_filename', $row[ $img_key ] );
			}
		}

		// Importar imágenes como adjuntos + imagen destacada.
		if ( $import_images ) {
			$thumb   = isset( $row['thumbnail'] ) && is_string( $row['thumbnail'] ) ? trim( $row['thumbnail'] ) : '';
			$cover   = isset( $row['cover_image'] ) && is_string( $row['cover_image'] ) ? trim( $row['cover_image'] ) : '';
			$content_img = isset( $row['content_image'] ) && is_string( $row['content_image'] ) ? trim( $row['content_image'] ) : '';

			// En el dump suelen venir solo basenames; el frontend los organiza por directorios.
			$thumb_for_import = $thumb;
			if ( $thumb_for_import !== '' && strpos( $thumb_for_import, '/' ) === false && strpos( $thumb_for_import, '\\' ) === false ) {
				$thumb_for_import = 'post/thumbnail/' . $thumb_for_import;
			}
			$cover_for_import = $cover;
			if ( $cover_for_import !== '' && strpos( $cover_for_import, '/' ) === false && strpos( $cover_for_import, '\\' ) === false ) {
				$cover_for_import = 'post/cover/' . $cover_for_import;
			}
			$content_for_import = $content_img;
			if ( $content_for_import !== '' && strpos( $content_for_import, '/' ) === false && strpos( $content_for_import, '\\' ) === false ) {
				$content_for_import = 'post/content/' . $content_for_import;
			}

			$image_filenames = array();
			// Orden de preferencia: thumbnail > cover_image > content_image.
			foreach ( array( $thumb_for_import, $cover_for_import, $content_for_import ) as $f ) {
				if ( $f !== '' ) {
					$image_filenames[] = $f;
				}
			}

			if ( ! empty( $image_filenames ) ) {
				$ids = bilky_sql_importer_attach_images_to_post(
					$post_id,
					$image_filenames,
					array(
						'base_url' => $media_base_url,
						'base_dir' => $media_base_dir,
					),
					array(
						'alt'                => $title,
						'additional_meta_key' => 'bilky_import_additional_images',
					),
					$dry_run
				);

				if ( is_wp_error( $ids ) ) {
					update_post_meta( $post_id, 'bilky_import_image_error', $ids->get_error_message() );
				}
			}
		}

		// Tags.
		$tag_ids_or_names = bilky_sql_json_array( isset( $row['tags'] ) ? $row['tags'] : null );
		if ( ! empty( $tag_ids_or_names ) ) {
			$term_ids = array();
			foreach ( $tag_ids_or_names as $t ) {
				if ( is_int( $t ) || ( is_string( $t ) && preg_match( '/^\d+$/', $t ) ) ) {
					$tid = (int) $t;
					if ( isset( $post_tag_term_map[ $tid ] ) && (int) $post_tag_term_map[ $tid ] > 0 ) {
						$term_ids[] = (int) $post_tag_term_map[ $tid ];
					}
				} elseif ( is_string( $t ) ) {
					$slug = sanitize_title( $t );
					$id   = bilky_sql_ensure_term( $slug, $t, 'post_tag' );
					if ( $id ) {
						$term_ids[] = $id;
					}
				}
			}
			if ( ! empty( $term_ids ) ) {
				wp_set_post_terms( $post_id, array_values( array_unique( $term_ids ) ), 'post_tag', false );
			}
		}
	}

	return $counts;
}

/**
 * Importa artículos al CPT centro_de_ayuda (SEO crítico: slug/fecha/contenido).
 *
 * @param array  $rows
 * @param array  $translations_by_article_id
 * @param array  $category_term_map
 * @param string $lang
 * @param array  $media { base_url?: string, base_dir?: string, import?: bool }
 * @param bool   $dry_run
 * @return array|WP_Error
 */
function bilky_sql_import_articles( $rows, $translations_by_article_id, $category_term_map, $lang, $media, $dry_run ) {
	$counts = array( 'created' => 0, 'updated' => 0, 'skipped' => 0 );

	$media = is_array( $media ) ? $media : array();
	$media_base_url = isset( $media['base_url'] ) ? (string) $media['base_url'] : '';
	$media_base_dir = isset( $media['base_dir'] ) ? (string) $media['base_dir'] : '';
	$import_images  = isset( $media['import'] ) ? (bool) $media['import'] : true;

	foreach ( $rows as $row ) {
		$sql_id = isset( $row['id'] ) ? (int) $row['id'] : 0;
		if ( $sql_id <= 0 ) {
			$counts['skipped']++;
			continue;
		}

		$slug  = bilky_sql_json_lang( isset( $row['slug'] ) ? $row['slug'] : '', $lang );
		$title = bilky_sql_json_lang( isset( $row['title'] ) ? $row['title'] : '', $lang );
		$text  = bilky_sql_json_lang( isset( $row['text'] ) ? $row['text'] : '', $lang );

		$slug  = $slug !== '' ? sanitize_title( $slug ) : '';
		$title = $title !== '' ? $title : 'Artículo ' . $sql_id;

		if ( $slug === '' ) {
			$slug = sanitize_title( $title );
		}

		$status_src = isset( $row['status'] ) ? (string) $row['status'] : '';
		$status     = $status_src === 'published' ? 'publish' : 'draft';

		$content = '';
		if ( $text !== '' ) {
			$content .= $text . "\n\n";
		}

		$steps = isset( $translations_by_article_id[ $sql_id ] ) ? $translations_by_article_id[ $sql_id ] : array();
		$step_image_filenames = array();
		foreach ( $steps as $step ) {
			$step_title = isset( $step['title'] ) ? trim( (string) $step['title'] ) : '';
			$step_text  = isset( $step['text'] ) ? (string) $step['text'] : '';
			$step_image = isset( $step['image'] ) ? trim( (string) $step['image'] ) : '';

			if ( $step_title !== '' ) {
				$content .= '<h2>' . esc_html( $step_title ) . '</h2>' . "\n";
			}
			if ( $step_text !== '' ) {
				$content .= $step_text . "\n\n";
			}
			if ( $step_image !== '' ) {
				$step_image_for_import = $step_image;
				if ( $step_image_for_import !== '' && strpos( $step_image_for_import, '/' ) === false && strpos( $step_image_for_import, '\\' ) === false ) {
					$step_image_for_import = 'help/' . $step_image_for_import;
				}

				$step_image_filenames[] = $step_image_for_import;

				// Renderizamos tras el upsert (cuando ya existe $post_id).
				$content .= '<!-- bilky-import-step-image: ' . esc_html( $step_image_for_import ) . ' -->' . "\n\n";
			}
		}

		$existing_id = bilky_sql_find_existing_post_id( 'centro_de_ayuda', 'articles', $sql_id, $slug, null, null );

		$postarr = array(
			'post_type'    => 'centro_de_ayuda',
			'post_title'   => $title,
			'post_status'  => $status,
			'post_name'    => $slug,
			'post_content' => $content,
		);

		if ( isset( $row['created_at'] ) && is_string( $row['created_at'] ) && $row['created_at'] !== '' ) {
			$postarr['post_date'] = $row['created_at'];
		}
		if ( isset( $row['updated_at'] ) && is_string( $row['updated_at'] ) && $row['updated_at'] !== '' ) {
			$postarr['post_modified'] = $row['updated_at'];
		}

		$post_id = 0;
		if ( $dry_run ) {
			$post_id = $existing_id ? $existing_id : 0;
		} else {
			$post_id = bilky_sql_upsert_post( $existing_id, $postarr );
			if ( is_wp_error( $post_id ) ) {
				return $post_id;
			}
		}

		if ( $existing_id ) {
			$counts['updated']++;
		} else {
			$counts['created']++;
		}

		if ( $dry_run || ! $post_id ) {
			continue;
		}

		update_post_meta( $post_id, '_bilky_sql_table', 'articles' );
		update_post_meta( $post_id, '_bilky_sql_id', (string) $sql_id );
		update_post_meta( $post_id, '_bilky_sql_imported', '1' );
		update_post_meta( $post_id, '_bilky_sql_imported_at', current_time( 'mysql' ) );
		if ( ! $existing_id ) {
			update_post_meta( $post_id, '_bilky_sql_import_created', '1' );
		}

		if ( isset( $row['featured'] ) ) {
			update_post_meta( $post_id, 'bilky_article_featured', $row['featured'] );
		}
		if ( isset( $row['visits'] ) ) {
			update_post_meta( $post_id, 'bilky_article_visits', $row['visits'] );
		}

		// Categoría (SQL category_id) -> WP category.
		$category_id = isset( $row['category_id'] ) ? (int) $row['category_id'] : 0;
		if ( $category_id > 0 && isset( $category_term_map[ $category_id ] ) && (int) $category_term_map[ $category_id ] > 0 ) {
			wp_set_post_terms( $post_id, array( (int) $category_term_map[ $category_id ] ), 'category', false );
		}

		// Importar imágenes de pasos como adjuntos y asignar destacada + custom field con el resto.
		if ( $import_images && ! empty( $step_image_filenames ) ) {
			$ids = bilky_sql_importer_attach_images_to_post(
				$post_id,
				$step_image_filenames,
				array(
					'base_url' => $media_base_url,
					'base_dir' => $media_base_dir,
				),
				array(
					'alt'                => $title,
					'additional_meta_key' => 'bilky_import_additional_images',
				),
				$dry_run
			);

			if ( is_wp_error( $ids ) ) {
				update_post_meta( $post_id, 'bilky_import_image_error', $ids->get_error_message() );
			} elseif ( is_array( $ids ) && ! empty( $ids ) ) {
				// Reemplazar marcadores de imagen por HTML de la imagen adjunta (si hay base URL/DIR).
				$content_with_images = $postarr['post_content'];
				$unique_filenames    = array_values( array_unique( array_filter( array_map( 'trim', $step_image_filenames ) ) ) );
				foreach ( $unique_filenames as $fn ) {
					if ( ! isset( $ids[ $fn ] ) ) {
						continue;
					}
					$att_id = (int) $ids[ $fn ];
					$img    = wp_get_attachment_image( $att_id, 'full', false, array( 'class' => 'bilky-import-image' ) );
					if ( $img ) {
						$replacement = '<figure class="bilky-import-image">' . $img . '</figure>' . "\n\n";
						$content_with_images = preg_replace(
							'/<!--\s*bilky-import-step-image:\s*' . preg_quote( $fn, '/' ) . '\s*-->\s*/',
							$replacement,
							$content_with_images,
							1
						);
					}
				}

				// Guardar el contenido actualizado con imágenes embebidas.
				wp_update_post(
					array(
						'ID'           => $post_id,
						'post_content' => $content_with_images,
					)
				);
			}
		}
	}

	return $counts;
}

/**
 * Encuentra un post existente por meta (tabla+id), o por slug, o por URL-meta.
 *
 * @param string      $post_type
 * @param string      $sql_table
 * @param int         $sql_id
 * @param string|null $slug
 * @param string|null $url_meta_key
 * @param string|null $url
 * @return int
 */
function bilky_sql_find_existing_post_id( $post_type, $sql_table, $sql_id, $slug = null, $url_meta_key = null, $url = null ) {
	$post_type = (string) $post_type;
	$sql_table = (string) $sql_table;
	$sql_id    = (int) $sql_id;

	$existing = get_posts(
		array(
			'post_type'      => $post_type,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => '_bilky_sql_table',
					'value' => $sql_table,
				),
				array(
					'key'   => '_bilky_sql_id',
					'value' => (string) $sql_id,
				),
			),
		)
	);
	if ( ! empty( $existing ) ) {
		return (int) $existing[0];
	}

	if ( $slug && is_string( $slug ) && $slug !== '' ) {
		$by_path = get_page_by_path( $slug, OBJECT, $post_type );
		if ( $by_path && isset( $by_path->ID ) ) {
			return (int) $by_path->ID;
		}
	}

	if ( $url_meta_key && $url && is_string( $url_meta_key ) && is_string( $url ) && trim( $url ) !== '' ) {
		$existing_by_url = get_posts(
			array(
				'post_type'      => $post_type,
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => $url_meta_key,
				'meta_value'     => $url,
			)
		);
		if ( ! empty( $existing_by_url ) ) {
			return (int) $existing_by_url[0];
		}
	}

	return 0;
}

/**
 * Inserta o actualiza un post.
 *
 * @param int   $existing_id
 * @param array $postarr
 * @return int|WP_Error
 */
function bilky_sql_upsert_post( $existing_id, $postarr ) {
	$existing_id = (int) $existing_id;

	if ( $existing_id > 0 ) {
		$postarr['ID'] = $existing_id;
		$result        = wp_update_post( $postarr, true );
	} else {
		$result = wp_insert_post( $postarr, true );
	}

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	return (int) $result;
}

/**
 * Asegura que exista un término por slug en una taxonomía.
 *
 * @param string $slug
 * @param string $name
 * @param string $taxonomy
 * @return int Term ID o 0
 */
function bilky_sql_ensure_term( $slug, $name, $taxonomy ) {
	$slug     = sanitize_title( $slug );
	$taxonomy = (string) $taxonomy;
	$name     = $name !== '' ? (string) $name : $slug;

	if ( $slug === '' ) {
		return 0;
	}

	$term = term_exists( $slug, $taxonomy );
	if ( $term && ! is_wp_error( $term ) ) {
		return (int) ( is_array( $term ) ? $term['term_id'] : $term );
	}

	$inserted = wp_insert_term(
		$name,
		$taxonomy,
		array(
			'slug' => $slug,
		)
	);
	if ( is_wp_error( $inserted ) ) {
		return 0;
	}

	$term_id = (int) $inserted['term_id'];

	// Marcar término como creado por el importador (para poder borrarlo en bloque).
	update_term_meta( $term_id, '_bilky_sql_imported', '1' );
	update_term_meta( $term_id, '_bilky_sql_import_created', '1' );
	update_term_meta( $term_id, '_bilky_sql_imported_at', current_time( 'mysql' ) );

	return $term_id;
}

/**
 * Adjunta imágenes a un post como adjuntos de WordPress.
 * - La primera imagen se asigna como imagen destacada.
 * - El resto se guarda en un custom field (meta) como array de IDs de adjunto.
 *
 * @param int   $post_id
 * @param array $filenames Lista de filenames relativos (tal como vienen del SQL)
 * @param array $media { base_url?: string, base_dir?: string }
 * @param array $options { alt?: string, additional_meta_key?: string }
 * @param bool  $dry_run
 * @return array|WP_Error Mapa filename => attachment_id
 */
function bilky_sql_importer_attach_images_to_post( $post_id, $filenames, $media, $options, $dry_run ) {
	$post_id = (int) $post_id;
	if ( $post_id <= 0 ) {
		return new WP_Error( 'bilky_sql_importer_invalid_post_id', 'Post ID inválido para adjuntar imágenes.' );
	}

	if ( $dry_run ) {
		return array();
	}

	$media   = is_array( $media ) ? $media : array();
	$options = is_array( $options ) ? $options : array();

	$base_url = isset( $media['base_url'] ) ? trim( (string) $media['base_url'] ) : '';
	$base_dir = isset( $media['base_dir'] ) ? trim( (string) $media['base_dir'] ) : '';

	$filenames = is_array( $filenames ) ? $filenames : array();
	$filenames = bilky_sql_importer_unique_non_empty_strings( $filenames );
	if ( empty( $filenames ) ) {
		return array();
	}

	if ( $base_url === '' && $base_dir === '' ) {
		// No hay origen de medios configurado: no adjuntar imágenes.
		return array();
	}

	bilky_sql_importer_require_media_includes();

	$alt = isset( $options['alt'] ) ? trim( (string) $options['alt'] ) : '';
	$additional_meta_key = isset( $options['additional_meta_key'] ) && is_string( $options['additional_meta_key'] ) && $options['additional_meta_key'] !== ''
		? $options['additional_meta_key']
		: 'bilky_import_additional_images';

	$parent_sql_table = get_post_meta( $post_id, '_bilky_sql_table', true );
	$parent_sql_id    = get_post_meta( $post_id, '_bilky_sql_id', true );

	$by_filename = array();

	foreach ( $filenames as $filename ) {
		$filename = trim( (string) $filename );
		if ( $filename === '' ) {
			continue;
		}

		$existing = bilky_sql_importer_find_existing_attachment_for_post( $post_id, $filename );
		if ( $existing > 0 ) {
			$by_filename[ $filename ] = $existing;
			continue;
		}

		$is_url     = preg_match( '#^https?://#i', $filename ) === 1;
		$local_path = $is_url ? '' : bilky_sql_importer_try_resolve_local_media_path( $base_dir, $filename );
		$url        = '';
		if ( $is_url ) {
			$url = $filename;
		} elseif ( $base_url !== '' ) {
			$url = bilky_sql_importer_build_media_url( $base_url, $filename );
		}

		$attachment_id = bilky_sql_importer_create_attachment_from_source(
			$post_id,
			$filename,
			$local_path,
			$url,
			array(
				'alt'            => $alt,
				'parent_table'   => is_string( $parent_sql_table ) ? $parent_sql_table : '',
				'parent_sql_id'  => is_string( $parent_sql_id ) ? $parent_sql_id : '',
			)
		);

		if ( is_wp_error( $attachment_id ) ) {
			// No abortar toda la importación si una imagen falla.
			continue;
		}

		$by_filename[ $filename ] = (int) $attachment_id;
	}

	if ( empty( $by_filename ) ) {
		return array();
	}

	$ids = array_values( $by_filename );

	// Imagen destacada.
	set_post_thumbnail( $post_id, (int) $ids[0] );

	// Custom field con el resto.
	$additional = array_slice( $ids, 1 );
	if ( ! empty( $additional ) ) {
		update_post_meta( $post_id, $additional_meta_key, array_values( array_map( 'intval', $additional ) ) );
	} else {
		delete_post_meta( $post_id, $additional_meta_key );
	}

	return $by_filename;
}

/**
 * Asegura includes de media para sideload.
 *
 * @return void
 */
function bilky_sql_importer_require_media_includes() {
	if ( ! function_exists( 'media_handle_sideload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
	}
	if ( ! function_exists( 'download_url' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}
	if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}
}

/**
 * Devuelve strings no vacíos, únicos, preservando orden.
 *
 * @param array $items
 * @return array
 */
function bilky_sql_importer_unique_non_empty_strings( $items ) {
	$out  = array();
	$seen = array();

	foreach ( $items as $v ) {
		if ( ! is_string( $v ) && ! is_numeric( $v ) ) {
			continue;
		}
		$s = trim( (string) $v );
		if ( $s === '' ) {
			continue;
		}
		if ( isset( $seen[ $s ] ) ) {
			continue;
		}
		$seen[ $s ] = true;
		$out[]      = $s;
	}

	return $out;
}

/**
 * Busca un adjunto ya creado por el importador para un post y filename.
 *
 * @param int    $post_id
 * @param string $filename
 * @return int
 */
function bilky_sql_importer_find_existing_attachment_for_post( $post_id, $filename ) {
	$post_id  = (int) $post_id;
	$filename = (string) $filename;

	if ( $post_id <= 0 || $filename === '' ) {
		return 0;
	}

	$filename_norm = ltrim( str_replace( array( '\\', "\0" ), '/', $filename ), '/' );
	$basename      = basename( $filename_norm );

	$ids = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'post_parent'    => $post_id,
			'meta_key'       => '_bilky_import_source_filename',
			'meta_value'     => $filename,
		)
	);

	if ( ! empty( $ids ) ) {
		return (int) $ids[0];
	}

	// Compatibilidad: versiones previas guardaban solo el basename como clave.
	if ( $basename !== '' && $basename !== $filename ) {
		$ids = get_posts(
			array(
				'post_type'      => 'attachment',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'post_parent'    => $post_id,
				'meta_key'       => '_bilky_import_source_filename',
				'meta_value'     => $basename,
			)
		);

		if ( ! empty( $ids ) ) {
			return (int) $ids[0];
		}
	}

	return 0;
}

/**
 * Intenta resolver la ruta local de una imagen dentro de un base_dir.
 *
 * @param string $base_dir
 * @param string $filename
 * @return string Ruta real del fichero o '' si no existe/permitido.
 */
function bilky_sql_importer_try_resolve_local_media_path( $base_dir, $filename ) {
	$base_dir = is_string( $base_dir ) ? trim( $base_dir ) : '';
	$filename = is_string( $filename ) ? trim( $filename ) : '';

	if ( $base_dir === '' || $filename === '' ) {
		return '';
	}

	$base_real = realpath( $base_dir );
	if ( $base_real === false ) {
		return '';
	}

	$relative = ltrim( str_replace( array( '\\', "\0" ), '/', $filename ), '/' );
	if ( $relative === '' ) {
		return '';
	}

	$candidates = array();

	// 1) Ruta directa: base_dir + relative.
	$candidates[] = trailingslashit( $base_real ) . $relative;

	// 2) Evitar duplicados si base_dir ya termina con el primer segmento.
	$segments = array_values( array_filter( explode( '/', $relative ), 'strlen' ) );
	if ( ! empty( $segments ) ) {
		$base_leaf = basename( str_replace( '\\', '/', $base_real ) );
		if ( $base_leaf !== '' && $base_leaf === $segments[0] ) {
			$rel_no_dup = implode( '/', array_slice( $segments, 1 ) );
			if ( $rel_no_dup !== '' ) {
				$candidates[] = trailingslashit( $base_real ) . $rel_no_dup;
			}
		}
	}

	// 3) Si existe un subdirectorio "frontend", probar también bajo él.
	$frontend_dir = trailingslashit( $base_real ) . 'frontend';
	if ( is_dir( $frontend_dir ) && ( empty( $segments ) || $segments[0] !== 'frontend' ) ) {
		$candidates[] = trailingslashit( $base_real ) . 'frontend/' . $relative;
	}

	foreach ( $candidates as $candidate ) {
		$real = realpath( $candidate );
		if ( $real === false || ! is_file( $real ) ) {
			continue;
		}

		// Evitar traversal.
		if ( strpos( $real, $base_real ) !== 0 ) {
			continue;
		}

		return $real;
	}

	// Fallback: buscar por basename en el árbol (solo si no se encontró por ruta directa).
	$basename = basename( $relative );
	if ( $basename === '' ) {
		return '';
	}

	static $index_by_base = array();
	static $built_by_base = array();

	if ( ! isset( $built_by_base[ $base_real ] ) ) {
		$built_by_base[ $base_real ] = false;
		$index_by_base[ $base_real ] = array();
	}

	if ( ! $built_by_base[ $base_real ] ) {
		$built_by_base[ $base_real ] = true;

		try {
			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator(
					$base_real,
					FilesystemIterator::SKIP_DOTS
				)
			);

			foreach ( $iterator as $file ) {
				if ( ! $file instanceof SplFileInfo ) {
					continue;
				}
				if ( ! $file->isFile() ) {
					continue;
				}

				$path = $file->getPathname();
				if ( ! is_string( $path ) || $path === '' ) {
					continue;
				}

				// Seguridad adicional.
				if ( strpos( $path, $base_real ) !== 0 ) {
					continue;
				}

				$base = $file->getBasename();
				if ( $base === '' ) {
					continue;
				}
				if ( isset( $index_by_base[ $base_real ][ $base ] ) ) {
					continue;
				}

				$index_by_base[ $base_real ][ $base ] = $path;
			}
		} catch ( Throwable $e ) {
			// Si el indexado falla, continuar sin fallback.
		}
	}

	if ( isset( $index_by_base[ $base_real ][ $basename ] ) ) {
		$path = (string) $index_by_base[ $base_real ][ $basename ];
		if ( $path !== '' && is_file( $path ) && strpos( $path, $base_real ) === 0 ) {
			return $path;
		}
	}

	return '';
}

/**
 * Construye una URL segura a partir de base_url + filename.
 *
 * @param string $base_url
 * @param string $filename
 * @return string
 */
function bilky_sql_importer_build_media_url( $base_url, $filename ) {
	$base_url = rtrim( (string) $base_url, '/' );
	$filename = ltrim( str_replace( array( '\\', "\0" ), '/', (string) $filename ), '/' );

	// Si ya es una URL, devolverla tal cual.
	if ( preg_match( '#^https?://#i', $filename ) === 1 ) {
		return $filename;
	}

	$rel_segments = array_values( array_filter( explode( '/', $filename ), 'strlen' ) );

	// Evitar duplicados si la base_url ya contiene parte del path.
	$base_segments = array();
	$base_path     = parse_url( $base_url, PHP_URL_PATH );
	if ( is_string( $base_path ) ) {
		$base_segments = array_values( array_filter( explode( '/', trim( $base_path, '/' ) ), 'strlen' ) );
	}

	$max_k = min( count( $base_segments ), count( $rel_segments ) );
	for ( $k = $max_k; $k > 0; $k-- ) {
		$base_suffix = array_slice( $base_segments, -$k );
		$rel_prefix  = array_slice( $rel_segments, 0, $k );
		if ( $base_suffix === $rel_prefix ) {
			$rel_segments = array_slice( $rel_segments, $k );
			break;
		}
	}

	$parts = array_map( 'rawurlencode', $rel_segments );
	$path  = implode( '/', $parts );

	return $path !== '' ? $base_url . '/' . $path : $base_url;
}

/**
 * Crea un adjunto a partir de un fichero local o URL.
 *
 * @param int    $post_id
 * @param string $source_filename
 * @param string $local_path
 * @param string $url
 * @param array  $options { alt?: string, parent_table?: string, parent_sql_id?: string }
 * @return int|WP_Error
 */
function bilky_sql_importer_create_attachment_from_source( $post_id, $source_filename, $local_path, $url, $options = array() ) {
	$post_id         = (int) $post_id;
	$source_filename = (string) $source_filename;
	$local_path      = (string) $local_path;
	$url             = (string) $url;
	$options         = is_array( $options ) ? $options : array();

	if ( $post_id <= 0 || $source_filename === '' ) {
		return new WP_Error( 'bilky_sql_importer_invalid_attachment_args', 'Parámetros inválidos al crear adjunto.' );
	}

	$tmp = '';

	if ( $local_path !== '' && is_file( $local_path ) ) {
		$tmp = wp_tempnam( basename( $source_filename ) );
		if ( ! $tmp ) {
			return new WP_Error( 'bilky_sql_importer_tmp_failed', 'No se pudo crear fichero temporal para importar imagen.' );
		}
		if ( ! @copy( $local_path, $tmp ) ) {
			@unlink( $tmp );
			return new WP_Error( 'bilky_sql_importer_copy_failed', 'No se pudo copiar la imagen desde el directorio base.' );
		}
	} elseif ( $url !== '' ) {
		$downloaded = download_url( $url );
		if ( is_wp_error( $downloaded ) ) {
			return $downloaded;
		}
		$tmp = (string) $downloaded;
	} else {
		return new WP_Error( 'bilky_sql_importer_media_not_found', 'No se encontró la imagen ni en DIR ni en URL.' );
	}

	$file_array = array(
		'name'     => basename( $source_filename ),
		'tmp_name' => $tmp,
	);

	$attachment_title = pathinfo( basename( $source_filename ), PATHINFO_FILENAME );
	$attachment_id    = media_handle_sideload(
		$file_array,
		$post_id,
		null,
		array(
			'post_title' => $attachment_title !== '' ? $attachment_title : basename( $source_filename ),
		)
	);

	if ( is_wp_error( $attachment_id ) ) {
		@unlink( $tmp );
		return $attachment_id;
	}

	$attachment_id = (int) $attachment_id;

	// Marcar adjunto como importado.
	update_post_meta( $attachment_id, '_bilky_sql_imported', '1' );
	update_post_meta( $attachment_id, '_bilky_sql_import_created', '1' );
	update_post_meta( $attachment_id, '_bilky_sql_imported_at', current_time( 'mysql' ) );
	update_post_meta( $attachment_id, '_bilky_import_source_filename', $source_filename );

	if ( isset( $options['parent_table'] ) && is_string( $options['parent_table'] ) && $options['parent_table'] !== '' ) {
		update_post_meta( $attachment_id, '_bilky_sql_table', $options['parent_table'] );
	}
	if ( isset( $options['parent_sql_id'] ) && is_string( $options['parent_sql_id'] ) && $options['parent_sql_id'] !== '' ) {
		update_post_meta( $attachment_id, '_bilky_sql_id', $options['parent_sql_id'] );
	}

	if ( isset( $options['alt'] ) && is_string( $options['alt'] ) && trim( $options['alt'] ) !== '' ) {
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', trim( $options['alt'] ) );
	}

	return $attachment_id;
}

/**
 * Borra adjuntos creados por el importador filtrando por tablas SQL (posts/clients/partners/articles).
 *
 * @param array $sql_tables
 * @param bool  $created_only
 * @param bool  $dry_run
 * @return array|WP_Error
 */
function bilky_sql_importer_delete_attachments_by_tables( $sql_tables, $created_only, $dry_run ) {
	$sql_tables  = is_array( $sql_tables ) ? array_values( array_unique( array_filter( array_map( 'strval', $sql_tables ) ) ) ) : array();
	$created_only = (bool) $created_only;
	$dry_run      = (bool) $dry_run;

	if ( empty( $sql_tables ) ) {
		return array( 'deleted' => 0, 'skipped' => 0 );
	}

	$deleted = 0;
	$skipped = 0;

	$meta_query = array(
		array(
			'key'   => '_bilky_sql_imported',
			'value' => '1',
		),
		array(
			'key'     => '_bilky_sql_table',
			'value'   => $sql_tables,
			'compare' => 'IN',
		),
	);

	if ( $created_only ) {
		$meta_query[] = array(
			'key'   => '_bilky_sql_import_created',
			'value' => '1',
		);
	}

	while ( true ) {
		$ids = get_posts(
			array(
				'post_type'      => 'attachment',
				'post_status'    => 'any',
				'posts_per_page' => 50,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'orderby'        => 'ID',
				'order'          => 'ASC',
				'suppress_filters' => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'meta_query'     => $meta_query,
			)
		);

		if ( empty( $ids ) ) {
			break;
		}

		foreach ( $ids as $id ) {
			$id = (int) $id;
			if ( $id <= 0 ) {
				$skipped++;
				continue;
			}

			if ( $dry_run ) {
				$deleted++;
				continue;
			}

			$deleted_att = wp_delete_attachment( $id, true );
			if ( $deleted_att ) {
				$deleted++;
			} else {
				$skipped++;
			}
		}
	}

	return array(
		'deleted' => $deleted,
		'skipped' => $skipped,
	);
}

