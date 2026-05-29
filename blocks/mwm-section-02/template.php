<?php
/**
 * Block Name: MWM Section 02
 */

$background = get_field('background') ?: 'transparent';
$title = get_field('title');
$icon = get_field('icon');
$text_body = get_field('text_body');
$button_text = get_field('button_text');
$button_link = get_field('button_link');
$items = get_field('items');

$background_class = 'mwm-section-02--background-' . esc_attr( $background );

?>

<section class="mwm-section-02 <?php echo esc_attr( $background_class ); ?>">
	<div class="mwm-section-02__wrapper">
		<div class="mwm-section-02__text">
			<?php if ( $title ) : ?>
				<div class="mwm-section-02__title-row">
					<?php if ( $icon ) : ?>
						<div class="mwm-section-02__title-icon" aria-hidden="true">
							<i class="fa-solid fa-<?php echo esc_attr( sanitize_text_field( $icon ) ); ?>"></i>
						</div>
					<?php endif; ?>
					<h2 class="mwm-section-02__title is-style-h-300"><?php echo nl2br( esc_html( $title ) ); ?></h3>
				</div>
			<?php endif; ?>
			<?php if ( $text_body ) : ?>
			<div class="mwm-section-02__text-body">
				<?php echo wp_kses_post( $text_body ); ?>
			</div>
			<?php endif; ?>
			<?php if ( $button_text ) : ?>
				<div class="mwm-section-02__button">
					<?php echo mwm_button( array(
						'text' => esc_html( $button_text ),
						'url' => esc_url( $button_link ),
						'variant' => 'fill',
						'color' => 'terciary',
						'size' => 'xl-md',
					) ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $items && ! empty( $items ) ) : ?>
			<ul class="mwm-section-02__list">
				<?php foreach ( $items as $item ) : 
					$item_image = isset( $item['image'] ) ? $item['image'] : null;
					$item_title = isset( $item['title'] ) ? $item['title'] : '';
					$item_text = isset( $item['text'] ) ? $item['text'] : '';
					
					if ( ! $item_image || ! $item_title ) {
						continue;
					}
					
					// Solo usar enlace si el texto contiene una URL con http/https
					$item_link = '';
					if ( ! empty( $item_text ) ) {
						preg_match( '/https?:\/\/[^\s<>"\']+/i', $item_text, $matches );
						if ( ! empty( $matches[0] ) ) {
							$item_link = $matches[0];
						}
					}
					
					// Obtener alt de la imagen o usar el título
					$image_alt = get_post_meta( $item_image, '_wp_attachment_image_alt', true );
					$item_alt = ! empty( $image_alt ) ? $image_alt : $item_title;
				?>
					<li class="mwm-section-02__item">
						<?php if ( ! empty( $item_link ) ) : ?>
							<a href="<?php echo esc_url( $item_link ); ?>" target="_blank" class="mwm-section-02__item-wrapper">
						<?php else : ?>
							<div class="mwm-section-02__item-wrapper">
						<?php endif; ?>
							<div class="mwm-section-02__item-image">
								<?php echo wp_get_attachment_image( 
									$item_image, 
									'full', 
									false, 
									array( 
										'class' => 'mwm-section-02__item-image-image', 
										'alt' => esc_attr( $item_alt ) 
									) 
								); ?>
							</div>
							<div class="mwm-section-02__item-content">
								<h3 class="mwm-section-02__item-title is-style-b-300"><?php echo esc_html( $item_title ); ?></h3>
								<?php if ( ! empty( $item_text ) ) : ?>
									<div class="mwm-section-02__item-text is-style-b-300">
										<?php echo wp_kses_post( $item_text ); ?>
									</div>
								<?php endif; ?>
							</div>
						<?php if ( ! empty( $item_link ) ) : ?>
						</a>
						<?php else : ?>
							</div>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>

