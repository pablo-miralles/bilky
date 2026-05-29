<?php
/**
 * Metadatos de la taxonomía Ponentes (imagen, cargo) — sin archivos públicos para términos.
 *
 * @package bilky
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra metadatos de término.
 */
function bilky_ponente_register_term_meta() {
	register_term_meta(
		'ponente_webinar',
		'bilky_ponente_image',
		array(
			'type'              => 'integer',
			'single'            => true,
			'sanitize_callback' => 'absint',
			'show_in_rest'      => true,
		)
	);
	register_term_meta(
		'ponente_webinar',
		'bilky_ponente_cargo',
		array(
			'type'              => 'string',
			'single'            => true,
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
		)
	);
}
add_action( 'init', 'bilky_ponente_register_term_meta' );

/**
 * Formulario: añadir término.
 */
function bilky_ponente_add_form_fields() {
	wp_nonce_field( 'bilky_ponente_term_meta', 'bilky_ponente_term_meta_nonce' );
	?>
	<div class="form-field term-group">
		<label for="bilky_ponente_cargo"><?php esc_html_e( 'Cargo', 'bilky' ); ?></label>
		<input type="text" name="bilky_ponente_cargo" id="bilky_ponente_cargo" value="" class="regular-text" />
		<p class="description"><?php esc_html_e( 'Ej.: Soporte y ventas', 'bilky' ); ?></p>
	</div>
	<div class="form-field term-group">
		<label for="bilky_ponente_image"><?php esc_html_e( 'Imagen (ID de adjunto)', 'bilky' ); ?></label>
		<input type="hidden" name="bilky_ponente_image" id="bilky_ponente_image" value="" />
		<div id="bilky-ponente-image-preview" style="margin:8px 0;"></div>
		<button type="button" class="button" id="bilky-ponente-upload-image"><?php esc_html_e( 'Seleccionar imagen', 'bilky' ); ?></button>
		<button type="button" class="button" id="bilky-ponente-remove-image" style="display:none;"><?php esc_html_e( 'Quitar imagen', 'bilky' ); ?></button>
	</div>
	<?php
}
add_action( 'ponente_webinar_add_form_fields', 'bilky_ponente_add_form_fields' );

/**
 * Formulario: editar término.
 *
 * @param WP_Term $term Término.
 */
function bilky_ponente_edit_form_fields( $term ) {
	$image_id = (int) get_term_meta( $term->term_id, 'bilky_ponente_image', true );
	$cargo    = (string) get_term_meta( $term->term_id, 'bilky_ponente_cargo', true );
	wp_nonce_field( 'bilky_ponente_term_meta', 'bilky_ponente_term_meta_nonce' );
	?>
	<tr class="form-field term-group-wrap">
		<th scope="row"><label for="bilky_ponente_cargo"><?php esc_html_e( 'Cargo', 'bilky' ); ?></label></th>
		<td>
			<input type="text" name="bilky_ponente_cargo" id="bilky_ponente_cargo" value="<?php echo esc_attr( $cargo ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Ej.: Soporte y ventas', 'bilky' ); ?></p>
		</td>
	</tr>
	<tr class="form-field term-group-wrap">
		<th scope="row"><label for="bilky_ponente_image"><?php esc_html_e( 'Imagen', 'bilky' ); ?></label></th>
		<td>
			<input type="hidden" name="bilky_ponente_image" id="bilky_ponente_image" value="<?php echo esc_attr( (string) $image_id ); ?>" />
			<div id="bilky-ponente-image-preview" style="margin:8px 0;">
				<?php
				if ( $image_id > 0 ) {
					echo wp_get_attachment_image( $image_id, 'thumbnail', false, array( 'style' => 'max-width:120px;height:auto;' ) );
				}
				?>
			</div>
			<button type="button" class="button" id="bilky-ponente-upload-image"><?php esc_html_e( 'Seleccionar imagen', 'bilky' ); ?></button>
			<button type="button" class="button" id="bilky-ponente-remove-image" <?php echo $image_id ? '' : 'style="display:none;"'; ?>><?php esc_html_e( 'Quitar imagen', 'bilky' ); ?></button>
		</td>
	</tr>
	<?php
}
add_action( 'ponente_webinar_edit_form_fields', 'bilky_ponente_edit_form_fields' );

/**
 * Guarda metadatos al crear o editar término.
 *
 * @param int $term_id ID del término.
 */
function bilky_ponente_save_term_meta( $term_id ) {
	if ( ! isset( $_POST['bilky_ponente_term_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bilky_ponente_term_meta_nonce'] ) ), 'bilky_ponente_term_meta' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_term', $term_id ) ) {
		return;
	}
	if ( isset( $_POST['bilky_ponente_cargo'] ) ) {
		update_term_meta( $term_id, 'bilky_ponente_cargo', sanitize_text_field( wp_unslash( $_POST['bilky_ponente_cargo'] ) ) );
	}
	$img = isset( $_POST['bilky_ponente_image'] ) ? (int) $_POST['bilky_ponente_image'] : 0;
	if ( $img > 0 ) {
		update_term_meta( $term_id, 'bilky_ponente_image', $img );
	} else {
		delete_term_meta( $term_id, 'bilky_ponente_image' );
	}
}
add_action( 'created_ponente_webinar', 'bilky_ponente_save_term_meta' );
add_action( 'edited_ponente_webinar', 'bilky_ponente_save_term_meta' );

/**
 * Scripts y estilos media en pantallas de la taxonomía.
 *
 * @param string $hook_suffix Hook actual.
 */
function bilky_ponente_admin_enqueue( $hook_suffix ) {
	if ( 'edit-tags.php' !== $hook_suffix && 'term.php' !== $hook_suffix ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'ponente_webinar' !== $screen->taxonomy ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script(
		'bilky-ponente-admin',
		get_template_directory_uri() . '/assets/js/bilky-ponente-admin.js',
		array( 'jquery' ),
		mowomo_asset_version( '/assets/js/bilky-ponente-admin.js' ),
		true
	);
}
add_action( 'admin_enqueue_scripts', 'bilky_ponente_admin_enqueue' );
