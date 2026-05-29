<?php
/**
 * Block Name: MWM Card 06 Group
 */

$width = get_field( 'width' ) ?: 'wide';
$card_type = get_field( 'card_type' ) ?: 'v1';
$cards = get_field( 'cards' );

if ( ! $cards || empty( $cards ) ) {
	return;
}

// Sanitizar ancho
$allowed_widths = array( 'wide', 'narrow' );
if ( ! in_array( $width, $allowed_widths, true ) ) {
	$width = 'wide';
}

// Sanitizar tipo de card
$allowed_card_types = array( 'v1', 'v2' );
if ( ! in_array( $card_type, $allowed_card_types, true ) ) {
	$card_type = 'v1';
}

$width_class = 'mwm-section-10--width-' . esc_attr( $width );
$card_type_class = 'mwm-card-06--' . esc_attr( $card_type );
?>

<section class="mwm-section-10 <?php echo esc_attr( $width_class ); ?> <?php echo esc_attr( $card_type_class ); ?>">
	<div class="mwm-section-10__wrapper">
		<?php foreach ( $cards as $card ) : ?>
			<?php
			$show_tag = isset( $card['show_tag'] ) ? ( $card['show_tag'] !== false ) : false;
			$tag = isset( $card['tag'] ) ? $card['tag'] : '';
			$show_icon = isset( $card['show_icon'] ) ? ( $card['show_icon'] !== false ) : true;
			$icon = isset( $card['icon'] ) ? $card['icon'] : 'wifi';
			$show_title = isset( $card['show_title'] ) ? ( $card['show_title'] !== false ) : true;
			$card_title = isset( $card['title'] ) ? $card['title'] : '';
			$show_description = isset( $card['show_description'] ) ? ( $card['show_description'] !== false ) : true;
			$description = isset( $card['description'] ) ? $card['description'] : '';
			$show_list = isset( $card['show_list'] ) ? ( $card['show_list'] !== false ) : false;
			$list_icon_color = isset( $card['list_icon_color'] ) ? $card['list_icon_color'] : 'primary';
			$list_items = isset( $card['list_items'] ) ? $card['list_items'] : array();
			$show_button = isset( $card['show_button'] ) ? ( $card['show_button'] !== false ) : false;
			$button_text = isset( $card['button_text'] ) ? $card['button_text'] : '';
			$button_url = isset( $card['button_url'] ) ? $card['button_url'] : '';
			
			// Sanitizar color de icono de la lista
			$allowed_icon_colors = array( 'primary', 'secondary', 'terciary' );
			if ( ! in_array( $list_icon_color, $allowed_icon_colors, true ) ) {
				$list_icon_color = 'primary';
			}
			?>
			<article class="mwm-card-06 <?php echo esc_attr( $card_type_class ); ?>">
				<div class="mwm-card-06__header">
					<?php if ( $show_tag && $tag ) : ?>
						<div class="mwm-card-06__tag">
							<?php echo mwm_button( array( 'text' => esc_html( $tag ), 'variant' => 'outline', 'color' => 'secondary', 'size' => 'sm', 'is_tag' => true ) ); ?>
						</div>
					<?php endif; ?>

					<div class="mwm-card-06__icon-wrapper">
						<?php if ( $show_icon && $icon ) : ?>
							<div class="mwm-card-06__icon">
								<?php echo mwm_icon_circle( array( 'icon' => esc_attr( $icon ), 'variant' => 'fill-icon', 'color' => 'primary', 'size' => 'md' ) ); ?>
							</div>
						<?php endif; ?>

						<h3 class="mwm-card-06__title <?php echo ( $card_type === 'v2' ) ? 'is-style-h-500' : 'is-style-h-400'; ?> <?php echo ( ! $show_title ) ? ' mwm-card-06__title--hidden' : ''; ?>">
							<?php echo esc_html( $show_title && $card_title ? $card_title : '' ); ?>
						</h3>
					</div>
					<?php if ( $show_description && $description ) : ?>
						<div class="mwm-card-06__description is-style-b-200">
							<?php echo wp_kses_post( wpautop( $description ) ); ?>
						</div>
					<?php endif; ?>
				</div>


				<?php if ( $show_list && ! empty( $list_items ) && is_array( $list_items ) ) : ?>
					<?php
					// Clase de la lista según el color del icono
					$list_classes = array(
						'mwm-card-06__list',
						'mwm-card-06__list--icon-' . esc_attr( $list_icon_color ),
					);
					?>
					<ul class="<?php echo esc_attr( implode( ' ', $list_classes ) ); ?>">
						<?php foreach ( $list_items as $item ) : ?>
							<?php
							$item_text = isset( $item['text'] ) ? $item['text'] : '';
							$item_icon = isset( $item['icon'] ) ? $item['icon'] : 'circle-check';
							$item_subtext = isset( $item['subtext'] ) ? $item['subtext'] : '';

							if ( empty( $item_text ) ) {
								continue;
							}
							?>
							<li class="mwm-card-06__list-item">
								<div class="mwm-card-06__list-item-inner">
									<span class="mwm-card-06__list-check" aria-hidden="true">
										<i class="fa-solid fa-<?php echo esc_attr( sanitize_text_field( $item_icon ) ); ?>"></i>
									</span>

									<div class="mwm-card-06__list-content">
										<span class="mwm-card-06__list-text is-style-b-200">
											<?php echo ($item_text ); ?>
										</span>
									</div>
								</div>
								<?php if ( $item_subtext ) : ?>
									<span class="mwm-card-06__list-subtext is-style-b-300">
										<?php echo esc_html( $item_subtext ); ?>
									</span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $show_button && ! empty( $button_text ) ) : ?>
					<div class="mwm-card-06__button">
						<?php
						echo mwm_button(
							array(
								'text'    => esc_html( $button_text ),
								'url'     => esc_url( $button_url ),
								'variant' => 'fill-icon',
								'color'   => 'terciary',
								'size'    => 'xl',
							)
						);
						?>
					</div>
				<?php endif; ?>
			</article>
		<?php endforeach; ?>
	</div>
</section>

