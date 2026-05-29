<?php
// Obtener el enlace del botón flotante (campo ACF de tipo link)
$floating_button_link = get_field( 'page_floating_button_link' );

// Solo mostrar el botón si hay un enlace configurado
if ( $floating_button_link && ! empty( $floating_button_link['url'] ) ) :
	$button_url    = $floating_button_link['url'];
	$button_text   = ! empty( $floating_button_link['title'] ) ? $floating_button_link['title'] : __( 'Contáctanos', 'bilky' );
	$button_target = isset( $floating_button_link['target'] ) && ( '_blank' === $floating_button_link['target'] );

	// Si por alguna razón no hay texto, no mostramos el botón
	if ( ! empty( $button_text ) ) :
		?>
		<div class="mwm-floating-button">
			<div class="mwm-floating-button__wrapper">
				<?php echo mwm_button( array(
					'text'         => esc_html( $button_text ),
					'variant'      => 'fill',
					'color'        => 'primary',
					'size'         => 'xl-md',
					'url'          => esc_url( $button_url ),
					'target_blank' => $button_target,
				) ); ?>
			</div>
		</div>
		<?php
	endif;
endif;
?>