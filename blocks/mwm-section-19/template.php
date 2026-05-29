<?php
/**
 * Block Name: MWM Section 19 — Acordeón con contenido
 *
 * @package bilky
 */

if ( ! isset( $block ) ) {
	$block = array();
}

$sections = get_field( 'sections' );

$block_id = 'mwm-section-19';
if ( ! empty( $block['anchor'] ) ) {
	$block_id = sanitize_title( $block['anchor'] );
} elseif ( ! empty( $block['id'] ) ) {
	$block_id .= '-' . preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $block['id'] );
} else {
	$block_id .= '-' . uniqid();
}

$is_block_preview = ( isset( $is_preview ) && $is_preview ) || ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] );

if ( empty( $sections ) || ! is_array( $sections ) ) {
	if ( $is_block_preview ) {
		$sections = array(
			array(
				'section_title' => __( 'Pregunta frecuente ?¿', 'bilky' ),
				'default_open'  => true,
				'section_body'  => '<p>' . esc_html__( 'Crea períodos compartidos con cada cliente para ir recibiendo las facturas de forma continua y organizada. Tus clientes podrán subir las facturas con un clic desde cualquier dispositivo.', 'bilky' ) . '</p>',
			),
		);
	} else {
		return;
	}
}
?>
<section class="mwm-section-19" id="<?php echo esc_attr( $block_id ); ?>">
	<div class="mwm-section-19__wrapper">
		<div class="mwm-section-19__accordions">
			<?php
			foreach ( $sections as $index => $section ) :
				$section_title = isset( $section['section_title'] ) ? $section['section_title'] : '';
				if ( '' === trim( (string) $section_title ) ) {
					continue;
				}
				$default_open = ! empty( $section['default_open'] );
				// section_body: nombre estable (evita conflicto con el atributo «content» del bloque en Gutenberg).
				$content        = isset( $section['section_body'] ) ? $section['section_body'] : ( isset( $section['content'] ) ? $section['content'] : '' );

				$panel_id   = $block_id . '-panel-' . (int) $index;
				$heading_id = $block_id . '-heading-' . (int) $index;
				?>
				<div class="mwm-section-19__accordion<?php echo $default_open ? ' is-open' : ''; ?>">
					<h3 class="mwm-section-19__accordion-heading">
						<button
							type="button"
							class="mwm-section-19__accordion-trigger"
							id="<?php echo esc_attr( $heading_id ); ?>"
							aria-expanded="<?php echo $default_open ? 'true' : 'false'; ?>"
							aria-controls="<?php echo esc_attr( $panel_id ); ?>"
						>
							<span class="mwm-section-19__accordion-title is-style-h-500">
								<?php echo esc_html( $section_title ); ?>
							</span>
							<span class="mwm-section-19__accordion-icon" aria-hidden="true">
								<span class="mwm-section-19__accordion-icon-plus"><i class="fa-solid fa-plus"></i></span>
								<span class="mwm-section-19__accordion-icon-minus"><i class="fa-solid fa-minus"></i></span>
							</span>
						</button>
					</h3>
					<div
						class="mwm-section-19__accordion-panel"
						id="<?php echo esc_attr( $panel_id ); ?>"
						role="region"
						aria-labelledby="<?php echo esc_attr( $heading_id ); ?>"
						aria-hidden="<?php echo $default_open ? 'false' : 'true'; ?>"
					>
						<div class="mwm-section-19__accordion-panel-inner">
							<?php if ( '' !== trim( (string) wp_strip_all_tags( $content ) ) ) : ?>
								<div class="mwm-section-19__content">
									<div class="mwm-section-19__content-inner is-style-b-200">
										<div class="mwm-section-19__content-text">
											<?php echo wp_kses_post( (string) $content ); ?>
										</div>
									</div>
								</div>
							<?php elseif ( $is_block_preview ) : ?>
								<div class="mwm-section-19__content">
									<div class="mwm-section-19__content-inner is-style-b-200">
										<div class="mwm-section-19__content-text">
											<p><?php echo esc_html__( 'Anade contenido en el campo de texto de esta seccion.', 'bilky' ); ?></p>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
