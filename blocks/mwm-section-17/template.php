<?php
/**
 * Block Name: MWM Section 17
 */

$breadcrumb_01_text = get_field( 'breadcrumb_01_text' );
$title = get_field( 'title' );
$show_text_body = get_field( 'show_text_body' ) !== false;
$text_body = get_field( 'text_body' );
$layout = get_field( 'layout' ) ?: 'normal';
$cards = get_field( 'cards' );
$tabs = get_field( 'tabs' );

// Sanitizar layout
$allowed_layouts = array( 'normal', 'tabs' );
if ( ! in_array( $layout, $allowed_layouts, true ) ) {
	$layout = 'normal';
}

/**
 * Función helper para renderizar una card
 *
 * @param array $card Datos de la card.
 * @return void
 */
if ( ! function_exists( 'mwm_section_17_render_card' ) ) {
	function mwm_section_17_render_card( $card ) {
	// Sanitizar variantes de fondo permitidas
	$allowed_backgrounds = array( 'grey', 'blue' );
	$background = isset( $card['background'] ) ? $card['background'] : 'grey';
	if ( ! in_array( $background, $allowed_backgrounds, true ) ) {
		$background = 'grey';
	}

	$tag_text = isset( $card['tag_text'] ) ? $card['tag_text'] : '';
	$card_title = isset( $card['title'] ) ? $card['title'] : '';
	$card_description = isset( $card['description'] ) ? $card['description'] : '';
	$show_list = isset( $card['show_list'] ) ? ( $card['show_list'] !== false ) : false;
	$list_items = isset( $card['list_items'] ) ? $card['list_items'] : array();
	$show_button = isset( $card['show_button'] ) ? ( $card['show_button'] !== false ) : false;
	$button_text = isset( $card['button_text'] ) ? $card['button_text'] : '';
	$button_url = isset( $card['button_url'] ) ? $card['button_url'] : '';

	$card_classes = array(
		'mwm-card-07',
		'mwm-card-07--background-' . esc_attr( $background ),
	);
	?>
	<article class="<?php echo esc_attr( implode( ' ', $card_classes ) ); ?>">
		<div class="mwm-card-07__header">
			<?php if ( $tag_text ) : ?>
				<?php
				// Color del tag según el fondo: terciary si es azul, secondary si es gris
				$tag_color = ( 'blue' === $background ) ? 'terciary' : 'secondary';
				?>
				<h3 class="mwm-card-07__tag is-style-b-200">
					<?php echo mwm_button( array( 'text' => esc_html( $tag_text ), 'variant' => 'outline', 'color' => $tag_color, 'size' => 'xl-md', 'is_tag' => true ) ); ?>
				</h3>
			<?php endif; ?>

			<?php if ( $card_title ) : ?>
				<h4 class="mwm-card-07__title is-style-h-200">
					<?php echo esc_html( $card_title ); ?>
				</h4>
			<?php endif; ?>

			<?php if ( '' !== trim( (string) $card_description ) ) : ?>
				<div class="mwm-card-07__description is-style-b-200">
					<?php echo wp_kses_post( wpautop( (string) $card_description ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $show_list && ! empty( $list_items ) && is_array( $list_items ) ) : ?>
			<ul class="mwm-card-07__list">
				<?php foreach ( $list_items as $item ) : ?>
					<?php
					$item_text = isset( $item['text'] ) ? $item['text'] : '';

					if ( empty( $item_text ) ) {
						continue;
					}
					?>
					<li class="mwm-card-07__list-item">
						<div class="mwm-card-07__list-item-inner">
							<span class="mwm-card-07__list-check" aria-hidden="true">
								<i class="fa-solid fa-circle-check"></i>
							</span>

							<span class="mwm-card-07__list-text is-style-b-200">
								<?php echo esc_html( $item_text ); ?>
							</span>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $show_button && ! empty( $button_text ) ) : ?>
			<div class="mwm-card-07__button">
				<?php
				// Color del botón según el fondo: primary si es azul, terciary si es gris
				$button_color = ( 'blue' === $background ) ? 'primary' : 'terciary';

				echo mwm_button(
					array(
						'text'    => esc_html( $button_text ),
						'url'     => esc_url( $button_url ),
						'variant' => 'fill-icon',
						'color'   => $button_color,
						'size'    => 'xl',
					)
				);
				?>
			</div>
		<?php endif; ?>
	</article>
	<?php
	}
}

$section_classes = array(
	'mwm-section-17',
	'mwm-section-17--layout-' . esc_attr( $layout ),
);
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
	<div class="mwm-section-17__wrapper">
		<?php if ( $breadcrumb_01_text || $title ) : ?>
			<div class="mwm-section-17__header">
				<div class="mwm-section-07 mwm-section-07--center mwm-section-07--background-white">
					<div class="mwm-section-07__wrapper">
						<?php if ( $breadcrumb_01_text ) : ?>
							<div class="mwm-section-07__breadcrumbs">
								<div class="mwm-section-07__breadcrumb-item">
									<?php echo mwm_button( array( 'text' => esc_html( $breadcrumb_01_text ), 'variant' => 'outline', 'color' => 'primary', 'size' => 'sm', 'is_tag' => true ) ); ?>
								</div>
							</div>
						<?php endif; ?>

						<div class="mwm-section-07__content">
							<?php if ( $title ) : ?>
								<h2 class="mwm-section-07__title is-style-h-100">
									<?php
									// Dividir el título en líneas si hay saltos de línea
									$title_lines = explode( "\n", $title );
									foreach ( $title_lines as $index => $line ) {
										$line = trim( $line );
										if ( ! empty( $line ) ) {
											echo '<span class="mwm-section-07__title-line">' . esc_html( $line ) . '</span>';
											if ( $index < count( $title_lines ) - 1 ) {
												echo ' ';
											}
										}
									}
									?>
								</h2>
							<?php endif; ?>

							<?php if ( $show_text_body && $text_body ) : ?>
								<div class="mwm-section-07__text-body is-style-b-200">
									<?php echo wp_kses_post( $text_body ); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( 'normal' === $layout && ! empty( $cards ) && is_array( $cards ) ) : ?>
			<div class="mwm-section-17__cards">
				<?php foreach ( $cards as $card ) : ?>
					<?php mwm_section_17_render_card( $card ); ?>
				<?php endforeach; ?>
			</div>
		<?php elseif ( 'tabs' === $layout && ! empty( $tabs ) && is_array( $tabs ) ) : ?>
			<div class="mwm-section-17__tabs">
				<div class="mwm-section-17__tabs-buttons">
					<?php
					$tab_index = 0;
					foreach ( $tabs as $tab ) :
						$tab_title = isset( $tab['tab_title'] ) ? $tab['tab_title'] : '';
						$tab_id = 'mwm-section-17-tab-' . $tab_index;
						$is_first = ( 0 === $tab_index );
						?>
						<?php if ( $tab_title ) : ?>
							<button 
								class="mwm-section-17__tab-button<?php echo $is_first ? ' is-active' : ''; ?>" 
								data-tab="<?php echo esc_attr( $tab_id ); ?>"
							>
								<?php echo esc_html( $tab_title ); ?>
							</button>
						<?php endif; ?>
						<?php
						$tab_index++;
					endforeach;
					?>
				</div>
				<div class="mwm-section-17__tabs-content">
					<?php
					$tab_index = 0;
					foreach ( $tabs as $tab ) :
						$tab_cards = isset( $tab['tab_cards'] ) ? $tab['tab_cards'] : array();
						$tab_id = 'mwm-section-17-tab-' . $tab_index;
						$is_first = ( 0 === $tab_index );
						?>
						<div 
							class="mwm-section-17__tab-content<?php echo $is_first ? ' is-active' : ''; ?>" 
							data-tab-content="<?php echo esc_attr( $tab_id ); ?>"
						>
							<div class="mwm-section-17__cards">
								<?php if ( ! empty( $tab_cards ) && is_array( $tab_cards ) ) : ?>
									<?php foreach ( $tab_cards as $card ) : ?>
										<?php mwm_section_17_render_card( $card ); ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>
						<?php
						$tab_index++;
					endforeach;
					?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

