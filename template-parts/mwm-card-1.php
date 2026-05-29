<article class="mwm-card-1">
	<div class="mwm-card-1__img">
		<?php the_post_thumbnail(); ?>
	</div>
	<div class="mwm-card-1__info">
		<div class="mwm-card-1__title-wrapper">
			<div class="mwm-card-1__cat is-style-l200">
				<?php
				$categories = get_the_category();
				if (!empty($categories)) {
					$category_link = get_category_link($categories[0]->term_id);
					echo '<a href="' . esc_url($category_link) . '">' . esc_html($categories[0]->name) . '</a>';
				}
				?>
			</div>
			<h3 class="mwm-card-1__title is-style-b100">
				<a href="<?php the_permalink(); ?>">
					<?php the_title(); ?>
				</a>
			</h3>
		</div>
		<div class="mwm-btn-1 has-only-icon">
			<span>
				<?php get_template_part( '/assets/images/icons/icon-add-large' ); ?>
			</span>
		</div>
	</div>
</article>