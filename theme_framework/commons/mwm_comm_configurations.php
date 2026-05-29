<?php
/* En este archivo encontrarás funciones generales que te ayudarán a configurar elementos básicos de una instalación de WordPress.

Indice:
- mwm_add_menu() -> Registra localizaciones de menu en WordPress
- mwm_show_menu() -> Muestra un menu en el sitio.
- mwm_exclude_pages_from_front_searches() -> Excluye de los resultados de busqueda del front las el tipo de contenido "Paginas"
- mwm_add_post_thumbnail_image() -> Añade una imagen destacada a los posts, pages y los contenidos personalizados (No por defecto) creados en el sitio.
- mwm_add_src_param() -> Añade una marca de tiempo a los src de los archivos encolados por el tema. (Seguridad y Optimización)
- mwm_remove_wordpress_src_version() -> Elimina la marca que deja WordPress con su versión en la url de los archivos encolados.
- mwm_responsive_video_content_filter() -> Sustituye el iframe tradicional de html por una versión amigable con la versión movil.
- mwm_add_theme_support() -> Función para optimizar el añadir theme support en el tema.
- mwm_add_body_class() -> Añade una clase al body del sitio.
*/

if (!function_exists('mwm_add_menu')) {
    function mwm_add_menu($menus = array()) {

        add_theme_support('menus');
        register_nav_menus (
            $menus
        );
    }	

}

if (!function_exists('mwm_show_menu')) {
    function mwm_show_menu($name){
        $menu = wp_nav_menu( array( 
            'theme_location' => $name,
            'container'       => false,
            'echo'           => true,
        ));

        if($menu){
            echo $menu;
        } else {
            echo '<p>No hay ningún menú registrado por ese nombre</p>';
        }
    }
}

if (!function_exists('mwm_exclude_pages_from_front_searches')) {
    function mwm_exclude_pages_from_front_searches($query) {
        if ( !is_admin() && $query->is_search) {
            $query->set('post_type', array('post'));
        };
        return $query;
    }

    add_filter('pre_get_posts','mwm_exclude_pages_from_front_searches');
}


if ( ! function_exists( 'mwm_add_post_thumbnail_image' ) ) {
	function mwm_add_post_thumbnail_image() {
		if ( function_exists( 'add_theme_support' ) ) {
			$cpts = array( 'post', 'page' );
			$cpts_custom = get_post_types( array( 'public' => true, '_builtin' => false ) );
			if ( ! empty( $cpts_custom ) ) {
				$cpts = array_merge( $cpts, array_keys( $cpts_custom ) );
			}
			add_theme_support( 'post-thumbnails' , apply_filters( 'mwm_add_post_thumbnail_image', $cpts ) );
		}
	}
    add_action( 'init', 'mwm_add_post_thumbnail_image' );
}

if ( ! function_exists( 'mwm_remove_wordpress_src_version' ) ) {
    function mwm_remove_wordpress_src_version ( $url ) {
        $url = preg_replace('/([?&])' . 'ver' . '=[^&]+(&|$)/','$1',$url);

        if (preg_match("/\?$/", $url) || preg_match("/\&$/", $url)) {
            return substr($url, 0, -1);
        } else {
            return $url;
        }
    }
}

if(!function_exists('mwm_responsive_video_content_filter')) {
	function mwm_responsive_video_content_filter($content) {

		$pattern = '/<iframe.*?src=".*?(vimeo|youtu\.?be).*?".*?<\/iframe>/';
		preg_match_all($pattern, $content, $matches);

		foreach ($matches[0] as $match) {
		$wrappedframe = '<div class="iframe-wrapper">' . $match . '</div>';

		$content = str_replace($match, $wrappedframe, $content);
		}
		return $content;
	}
	add_filter( 'the_content', 'mwm_responsive_video_content_filter' );
	add_filter( 'widget_text', 'mwm_responsive_video_content_filter' );
}

if(!function_exists('mwm_add_theme_support')) {
    function mwm_add_theme_support($supports) {
        foreach ($supports as $args => $support) {
            if ($args === 'post_type_support') {
                foreach ($support as $post_type => $post_support) {
                    add_post_type_support($post_type, $post_support);
                }
            } elseif(is_array($args)) {
                add_theme_support($support, $args);
            } else {
                add_theme_support($support);
            }
        }
    }
}

if ( ! function_exists( 'mwm_breadcrumb' ) ) {
	/**
	 * Breadcrumb personalizado
	 */
	function mwm_breadcrumb() {
	
		$sep = '<span class="breadcrumbs__sep"></span>';
	
		if (!is_front_page()) {
		
			echo '<div class="breadcrumbs">';
			echo '<div class="breadcrumbs__wrapper">';
			echo '<a class="breadcrumbs__icon" href="' . get_option('home') . '">';
			get_template_part( '/assets/images/icons/icon-breadcrumbs-home' );
			echo '</a>';
			echo $sep;
		
			if (is_category() || is_single() ){
				the_category('title_li=');
			} elseif (is_archive() || is_single()){
				if ( is_day() ) {
					printf( __( '%s', 'desi' ), get_the_date() );
				} elseif ( is_month() ) {
					printf( __( '%s', 'desi' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'desi' ) ) );
				} elseif ( is_year() ) {
					printf( __( '%s', 'desi' ), get_the_date( _x( 'Y', 'yearly archives date format', 'desi' ) ) );
				} else {
					_e( 'Blog Archives', 'desi' );
				}
			}
		
			if (is_single()) {
				echo $sep;
				echo '<p class="breadcrumbs__current-page">' . get_the_title() . '</p>';
			}
		
			if (is_page()) {
				echo '<p class="breadcrumbs__current-page">' . get_the_title() . '</p>';
			}
	
			if (is_home()){
				global $post;
				$page_for_posts_id = get_option('page_for_posts');
				if ( $page_for_posts_id ) { 
					$post = get_post($page_for_posts_id);
					setup_postdata($post);
					echo '<p class="breadcrumbs__current-page">' . get_the_title() . '</p>';
					rewind_posts();
				}
			}
	
			echo '</div>';
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'mwm_add_body_class' ) ) {

	function mwm_add_body_class($clases = array()){
		add_filter( 'body_class', function( $classes ) {
			return array_merge( $classes, $clases );
		} );
	}

}
