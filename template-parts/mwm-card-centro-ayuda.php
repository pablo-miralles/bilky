<article class="mwm-card-centro-ayuda">
	<div class="mwm-card-centro-ayuda__wrapper">
		<div class="mwm-card-centro-ayuda__body">
			<h3 class="mwm-card-centro-ayuda__title"><?php the_title(); ?></h3>
			<div class="mwm-card-centro-ayuda__excerpt">
				<?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '...' ) ); ?>
			</div>
		</div>
		<div class="mwm-card-centro-ayuda__button">
			<?php
			echo mwm_button(
				array(
					'text'    => __( 'Ver más', 'bilky' ),
					'url'     => esc_url( get_the_permalink() ),
					'variant' => 'fill',
					'color'   => 'terciary',
					'size'    => 'sm',
				)
			);
			?>
		</div>
	</div>
</article>
