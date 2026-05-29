<?php
/**
 * Campos (ACF / fallback) para el CPT centro_de_ayuda.
 */

/**
 * ACF: Campo URL de vídeo (se guarda en meta bilky_video_url).
 */
if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group(
		array(
			'key'                   => 'group_centro_de_ayuda_video_fields',
			'title'                 => __( 'Centro de ayuda — Vídeo', 'bilky' ),
			'fields'                => array(
				array(
					'key'          => 'field_bilky_video_url',
					'label'        => __( 'URL de vídeo', 'bilky' ),
					'name'         => 'bilky_video_url',
					'type'         => 'url',
					'instructions' => __( 'URL del vídeo (por ejemplo, Vimeo) asociada a este artículo.', 'bilky' ),
					'required'     => 0,
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'centro_de_ayuda',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'side',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
		)
	);
} else {
	/**
	 * Fallback sin ACF: metabox nativo para editar bilky_video_url.
	 */
	add_action(
		'add_meta_boxes',
		function () {
			add_meta_box(
				'bilky_video_url_metabox',
				__( 'URL de vídeo', 'bilky' ),
				function ( $post ) {
					if ( ! $post || ! isset( $post->ID ) ) {
						return;
					}
					wp_nonce_field( 'bilky_video_url_save', 'bilky_video_url_nonce' );
					$value = get_post_meta( (int) $post->ID, 'bilky_video_url', true );
					$value = is_string( $value ) ? $value : '';
					echo '<p><label for="bilky_video_url_field" class="screen-reader-text">' . esc_html__( 'URL de vídeo', 'bilky' ) . '</label></p>';
					echo '<input type="url" style="width:100%;" id="bilky_video_url_field" name="bilky_video_url" value="' . esc_attr( $value ) . '" placeholder="https://vimeo.com/...">';
					echo '<p class="description">' . esc_html__( 'URL del vídeo (por ejemplo, Vimeo) asociada a este artículo.', 'bilky' ) . '</p>';
				},
				'centro_de_ayuda',
				'side',
				'default'
			);
		}
	);

	add_action(
		'save_post_centro_de_ayuda',
		function ( $post_id ) {
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
			if ( ! isset( $_POST['bilky_video_url_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bilky_video_url_nonce'] ) ), 'bilky_video_url_save' ) ) {
				return;
			}
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			$value = isset( $_POST['bilky_video_url'] ) ? trim( (string) wp_unslash( $_POST['bilky_video_url'] ) ) : '';
			$value = $value !== '' ? esc_url_raw( $value ) : '';

			if ( $value === '' ) {
				delete_post_meta( $post_id, 'bilky_video_url' );
				return;
			}
			update_post_meta( $post_id, 'bilky_video_url', $value );
		}
	);
}

