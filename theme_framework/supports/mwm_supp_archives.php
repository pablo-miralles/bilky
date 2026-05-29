<?php /* 

---- ARCHIVES ----

En este archivo encontrarás funciones que te ayudarán a realizar la programación de las páginas de tipo archivo

Indice:
- mwm_load_more() -> Función que se encarga de cargar más posts con ajax

*/

// Security
if (!defined('ABSPATH')) exit;

/**
 * Función que carga el botón de load more
 * @param [string] $container Id del contenedor donde se sacarán los posts. Por defecto, se cargarán encima del botón de load more
 * @param [string] $template Ruta del template part del custom post type. Por defecto, se cargará el template part del blog
 * @return string
 */
if ( ! function_exists( 'mwm_load_more' ) ) {
	function mwm_load_more( $container = '', $template = '' ) {

		global $wp_query;

		// Count number of load mores
		if ( defined( 'mwm_load_more_count' ) ) {
			define( 'mwm_load_more_count', mwm_load_more_count + 1 );
		} else {
			define( 'mwm_load_more_count', 1 );
		}

		if ( $wp_query->max_num_pages <= 1 ) return;

		if ( $template == '' ) {
			$template = 'template-parts/post-loop';
		}

		// Show button
		?>
			<!-- Button mwm load more #<?php //echo mwm_load_more_count; ?> -->
			<style>
				#<?php echo 'mwm-load-more-' . mwm_load_more_count; ?> {
					display: none;
				}
			</style>
			<div id="<?php echo 'mwm-load-more-' . mwm_load_more_count; ?>" class="mwm-load-more mwm-load-more__button" >
				<a href="javascript:void(0);" class="mwm-button mwm-button--fill-icon mwm-button--terciary mwm-button--xl mwm-button--active-focus">
					<span class="mwm-button__text"><?php _e( 'Ver más', 'bilky' ); ?></span>
					<span class="mwm-button__icon mwm-button__icon--xl"><i class="fa-solid fa-chevron-right"></i></span>
				</a>
			</div>
		<?php

		// Scripts
		?>
			<!-- Scripts mwm load more #<?php echo mwm_load_more_count; ?> -->
			<script type="text/javascript" defer>
				jQuery(window).on('load', function(){
					var button = jQuery('#<?php echo 'mwm-load-more-' . mwm_load_more_count; ?>');
					var text = button.find('.mwm-button__text');
					var ajaxurl = '<?php echo site_url() . '/wp-admin/admin-ajax.php'; ?>';
					var posts = '<?php echo json_encode( $wp_query->query_vars ); ?>';
					var current_page = <?php echo get_query_var( 'paged' ) ? get_query_var('paged') : 1; ?>;
					var max_page = <?php echo $wp_query->max_num_pages; ?>;
					var container = <?php echo $container !== '' ? 'jQuery( "' . $container . '")' : 'false'; ?>;

					button.show();

					button.on('click', function(){
						var data = {
							'action': 'mwm_load_more_ajax',
							'query': posts,
							'page' : current_page,
							'template': '<?php echo $template; ?>',
							'load_more_count': <?php echo mwm_load_more_count; ?>
						};
						jQuery.ajax({
							url : ajaxurl,
							data : data,
							type : 'POST',
							beforeSend : function ( xhr ) {
								text.text('<?php _e( 'Cargando', 'bilky' ); ?>');
							},
							success : function( data ){
								if( data ) { 
									text.text( '<?php _e( 'Ver más', 'bilky' ); ?>' );
									current_page++;

									if ( container ) {
										container.append( data );
									} else {
										button.before( data );
									}

									if ( current_page == max_page ) 
										button.remove();
								} else {
									button.remove();
								}
							}
						});
					});
				});
			</script>
		<?php

	}
}

/**
 * Función que prepara el ajax del botón de load more
 */
if ( ! function_exists( 'mwm_load_more_ajax' ) ) {
	function mwm_load_more_ajax() {

		$args = json_decode( stripslashes( $_POST['query'] ), true );
		$args['paged'] = $_POST['page'] + 1;
		$args['post_status'] = 'publish';

		query_posts( $args );

		if( have_posts() ) :
			while( have_posts() ): the_post();
				get_template_part( $_POST['template'] );
			endwhile;
		endif;

		die;

	}
	
	add_action( 'wp_ajax_mwm_load_more_ajax', 'mwm_load_more_ajax' );
	add_action( 'wp_ajax_nopriv_mwm_load_more_ajax', 'mwm_load_more_ajax' );
}
