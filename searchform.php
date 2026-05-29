<div class="search-form">
	<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="formbuscar">
		<input type="search" name="s" id="s" placeholder="Buscar" class="buscar" required/>
		<button>
			<?php get_template_part( '/assets/images/icons/icon-search' ); ?>
		</button>
	</form>
</div>