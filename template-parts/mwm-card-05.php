<article class="mwm-card-05">
	<div class="mwm-card-05__wrapper">
		<div class="mwm-card-05__media">
			<a href="<?php the_permalink(); ?>"></a>
			<?php the_post_thumbnail(); ?>
		</div>
		<div class="mwm-card-05__content">
			<div class="mwm-card-05__meta">
				<?php
				$categories = get_the_category();
				if ( ! empty( $categories ) ) {
					$category = $categories[0];
					echo mwm_button(
						array(
							'text'    => esc_html( $category->name ),
							'url'     => esc_url( get_category_link( $category->term_id ) ),
							'variant' => 'soft',
							'color'   => 'terciary',
							'size'    => 'sm',
							'class'   => 'mwm-card-05__category',
						)
					);
				}
				?>
				<div class="mwm-card-05__date"><?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?></div>
			</div>
			<h3 class="mwm-card-05__title"><?php the_title(); ?></h3>
		</div>
	</div>
</article>