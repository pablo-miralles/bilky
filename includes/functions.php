<?php

if ( ! function_exists( 'mowomo_asset_version' ) ) {
	/**
	 * Genera un parámetro de versión para `?ver=` a partir de la fecha de modificación del archivo.
	 * Si el fichero no existe, usa la versión declarada en el style.css del tema (child si aplica).
	 *
	 * @param string $relative_path Ruta relativa al directorio del tema, p. ej. '/assets/js/scripts.js'.
	 * @param string $base          'template' (tema padre) o 'stylesheet' (tema activo / hijo).
	 * @return string
	 */
	function mowomo_asset_version( $relative_path, $base = 'template' ) {
		$relative_path = is_string( $relative_path ) ? $relative_path : '';
		$relative_path = '/' . ltrim( str_replace( '\\', '/', $relative_path ), '/' );
		$dir           = ( 'stylesheet' === $base ) ? get_stylesheet_directory() : get_template_directory();
		$file_path     = $dir . $relative_path;

		if ( file_exists( $file_path ) && is_file( $file_path ) ) {
			return (string) filemtime( $file_path );
		}

		$version = wp_get_theme()->get( 'Version' );
		return is_string( $version ) && '' !== $version ? $version : '1.0';
	}
}

/**
 * Requires
 */
require_once get_template_directory() . '/includes/customizer.php';
require_once get_template_directory() . '/includes/page-fields.php';
require_once get_template_directory() . '/includes/post-fields.php';
require_once get_template_directory() . '/includes/menu-fields.php';
require_once get_template_directory() . '/includes/clientes-cpt.php';
require_once get_template_directory() . '/includes/clientes-fields.php';
require_once get_template_directory() . '/includes/centro-de-ayuda-cpt.php';
require_once get_template_directory() . '/includes/centro-de-ayuda-fields.php';
require_once get_template_directory() . '/includes/webinars-cpt.php';
require_once get_template_directory() . '/includes/webinars-helpers.php';
require_once get_template_directory() . '/includes/webinars-ponentes-admin.php';
require_once get_template_directory() . '/includes/webinars-fields.php';
require_once get_template_directory() . '/includes/partners-cpt.php';
require_once get_template_directory() . '/includes/partners-fields.php';
require_once get_template_directory() . '/includes/sql-importer.php';

if ( ! function_exists( 'mwm_get_archive_setting_key' ) ) {
	/**
	 * Devuelve la clave de opción del customizer según el contexto: blog, centro de ayuda o webinars.
	 * Usar en archive/single para mostrar breadcrumb, título y post destacado correctos.
	 *
	 * @param string $blog_key Clave usada para el blog (ej: 'mwm_archive_breadcrumb_1').
	 * @return string Clave a usar.
	 */
	function mwm_get_archive_setting_key( $blog_key ) {
		$post_type        = get_query_var( 'post_type' );
		$get_post_type    = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : '';
		$is_search_centro = is_search() && ( 'centro_de_ayuda' === $post_type || 'centro_de_ayuda' === $get_post_type || ( is_array( $post_type ) && in_array( 'centro_de_ayuda', $post_type, true ) ) );

		if ( is_post_type_archive( 'centro_de_ayuda' ) || is_singular( 'centro_de_ayuda' ) || is_tax( 'category_centro_de_ayuda' ) || $is_search_centro ) {
			$map = array(
				'mwm_archive_breadcrumb_1'  => 'mwm_centro_ayuda_breadcrumb_1',
				'mwm_archive_breadcrumb_2'  => 'mwm_centro_ayuda_breadcrumb_2',
				'mwm_archive_title'         => 'mwm_centro_ayuda_title',
				'mwm_archive_featured_post' => 'mwm_centro_ayuda_featured_post',
			);
			return isset( $map[ $blog_key ] ) ? $map[ $blog_key ] : $blog_key;
		}

		if ( is_post_type_archive( 'webinar' ) || is_singular( 'webinar' ) || is_tax( 'category_webinar' ) ) {
			$map = array(
				'mwm_archive_breadcrumb_1' => 'mwm_webinar_breadcrumb_1',
				'mwm_archive_breadcrumb_2' => 'mwm_webinar_breadcrumb_2',
				'mwm_archive_title'        => 'mwm_webinar_title',
			);
			return isset( $map[ $blog_key ] ) ? $map[ $blog_key ] : $blog_key;
		}

		return $blog_key;
	}
}

if ( ! function_exists( 'mwm_webinar_get_archive_text' ) ) {
	/**
	 * Texto del archive de webinars desde el Customizer, o fallback si está vacío.
	 *
	 * @param string $blog_key Clave genérica: mwm_archive_breadcrumb_1 o mwm_archive_title.
	 * @param string $fallback Texto por defecto (traducido).
	 * @return string
	 */
	function mwm_webinar_get_archive_text( $blog_key, $fallback ) {
		$key = mwm_get_archive_setting_key( $blog_key );
		$val = get_option( $key, '' );
		if ( '' === $val || ! is_string( $val ) ) {
			$val = get_theme_mod( $key, '' );
		}
		$val = is_string( $val ) ? trim( $val ) : '';
		return '' !== $val ? $val : $fallback;
	}
}

if ( ! function_exists( 'mwm_get_display_author_id' ) ) {
	/**
	 * Devuelve el ID del usuario que debe mostrarse como autor en los posts.
	 * Si hay un usuario con el checkbox "Mostrar como autor en todos los posts" marcado,
	 * devuelve su ID. Si no, devuelve null para que se use el autor real del post.
	 *
	 * @return int|null ID del usuario o null.
	 */
	function mwm_get_display_author_id() {
		$users = get_users( array( 'number' => -1 ) );
		foreach ( $users as $user ) {
			$checked = get_field( 'user_show_as_author_in_posts', 'user_' . $user->ID );
			if ( empty( $checked ) ) {
				$checked = get_user_meta( $user->ID, 'user_show_as_author_in_posts', true );
			}
			if ( ! empty( $checked ) && ( 1 === (int) $checked || true === $checked ) ) {
				return (int) $user->ID;
			}
		}
		return null;
	}
}

add_action( 'init', 'mwm_register_blocks' );

function mwm_register_blocks() {
    $blocks_directory = get_template_directory() . '/blocks';
    $blocks = scandir($blocks_directory);

    foreach ($blocks as $block) {
        if ($block !== '.' && $block !== '..') {
            $block_path = $blocks_directory . '/' . $block;
            if (is_dir($block_path)) {
                // No pasar "supports" aquí: register_block_type() hace merge de primer nivel y
                // sobrescribe todo supports{} del block.json (p. ej. align: false).
                register_block_type( $block_path );

                // Incluir el archivo de campos si existe
                $fields_file = $block_path . '/fields.php';
                if (file_exists($fields_file)) {
                    require_once $fields_file;
                }
            }
        }
    }
}

if ( ! function_exists( 'mwm_register_block_type_args_acf_anchor' ) ) {
    /**
     * Ancla HTML en bloques ACF del tema (sustituye el supports.anchor del antiguo register_block_type).
     *
     * @param array  $args Args del bloque.
     * @param string $name Nombre del bloque.
     * @return array
     */
    function mwm_register_block_type_args_acf_anchor( $args, $name ) {
        if ( 0 !== strpos( $name, 'acf/' ) ) {
            return $args;
        }
        if ( ! isset( $args['supports'] ) || ! is_array( $args['supports'] ) ) {
            $args['supports'] = array();
        }
        $args['supports']['anchor'] = true;
        return $args;
    }
    add_filter( 'register_block_type_args', 'mwm_register_block_type_args_acf_anchor', 10, 2 );
}

/**
 * Genera un botón con todas las variantes según el diseño de Figma
 *
 * @param array $args {
 *     Argumentos del botón
 *     @type string   $text          Texto del botón (opcional si hay icono)
 *     @type string   $url           URL si es un enlace (opcional)
 *     @type string   $variant       Variante: 'fill-icon', 'fill', 'link', 'outline', 'soft' (default: 'fill')
 *     @type string   $color         Color: 'primary', 'secundary', 'terciary' (default: 'primary')
 *     @type string   $size          Tamaño: 'sm', 'md', 'xl-md', 'xl' (default: 'xl-md')
 *     @type string   $state         Estado: 'active-focus', 'hover', 'disabled' (default: 'active-focus')
 *     @type bool     $show_icon     Mostrar icono (default: true para fill-icon, false para otros)
 *     @type string   $icon          Nombre del icono Font Awesome (default: 'chevron-right' para fill-icon, 'chevron-down' para link)
 *     @type string   $tag           Tag HTML: 'button', 'a', o 'div' (default: 'a' si hay url, 'button' si no)
 *     @type bool     $is_tag        Si es true, usa 'div' en lugar de 'button' o 'a' (default: false)
 *     @type string|array $class     Clases CSS adicionales (opcional, puede ser string o array)
 *     @type string   $id            ID del elemento (opcional)
 *     @type array    $attributes    Atributos HTML adicionales (opcional)
 *     @type bool     $target_blank  Si es true, agrega target="_blank" y rel="noopener noreferrer" (opcional)
 * }
 * @return string HTML del botón
 */
function mwm_button( $args = array() ) {
    $defaults = array(
        'text'          => '',
        'url'           => '',
        'variant'       => 'fill',
        'color'         => 'primary',
        'size'          => 'xl-md',
        'state'         => 'active-focus',
        'show_icon'     => null, // Se determina automáticamente según variant
        'icon'          => null, // Se determina automáticamente según variant
        'tag'           => null, // Se determina automáticamente según url
        'is_tag'        => false, // Si es true, usa div en lugar de button/a
        'class'         => '',
        'id'            => '',
        'attributes'    => array(),
        'target_blank'  => false, // Si es true, agrega target="_blank"
    );

    $args = wp_parse_args( $args, $defaults );

    // Determinar show_icon automáticamente si no está definido
    if ( is_null( $args['show_icon'] ) ) {
        $args['show_icon'] = ( $args['variant'] === 'fill-icon' || ( $args['variant'] === 'link' && $args['size'] !== 'sm' ) );
    }

    // Si no hay texto, forzar show_icon a true si hay icono definido
    if ( empty( $args['text'] ) && ! empty( $args['icon'] ) ) {
        $args['show_icon'] = true;
    }

    // Validar que haya texto o icono
    if ( empty( $args['text'] ) && ! $args['show_icon'] ) {
        return '';
    }

    // Si is_tag es true, forzar div
    if ( $args['is_tag'] === true ) {
        $args['tag'] = 'div';
    } elseif ( is_null( $args['tag'] ) ) {
        // Determinar tag automáticamente
        $args['tag'] = ! empty( $args['url'] ) ? 'a' : 'button';
    }

    // Validar tag permitido
    $allowed_tags = array( 'button', 'a', 'div' );
    if ( ! in_array( $args['tag'], $allowed_tags, true ) ) {
        $args['tag'] = 'button';
    }

    // Determinar icono automáticamente
    if ( is_null( $args['icon'] ) ) {
        if ( $args['variant'] === 'link' ) {
            $args['icon'] = 'chevron-down';
        } else {
            $args['icon'] = 'chevron-right';
        }
    }

    // Determinar si es botón solo con icono
    $is_icon_only = empty( $args['text'] ) && $args['show_icon'];

    // Construir clases base
    $classes = array( 'mwm-button' );
    $classes[] = 'mwm-button--' . esc_attr( $args['variant'] );
    $classes[] = 'mwm-button--' . esc_attr( $args['color'] );
    $classes[] = 'mwm-button--' . esc_attr( $args['size'] );
    
    // Agregar clase para botón solo con icono
    if ( $is_icon_only ) {
        $classes[] = 'mwm-button--icon-only';
    }
    
    // No agregar clase de estado si es div
    if ( $args['tag'] !== 'div' ) {
        $classes[] = 'mwm-button--' . esc_attr( $args['state'] );
    } else {
        // Si es div (tag), agregar clase especial
        $classes[] = 'mwm-button--tag';
    }

    // Agregar clases adicionales (puede ser string o array)
    if ( ! empty( $args['class'] ) ) {
        if ( is_array( $args['class'] ) ) {
            $classes = array_merge( $classes, $args['class'] );
        } else {
            $classes[] = $args['class'];
        }
    }

    // Agregar ID si se proporciona
    if ( ! empty( $args['id'] ) ) {
        $args['attributes']['id'] = $args['id'];
    }

    // Si target_blank es true, agregar target y rel (solo para enlaces)
    if ( $args['target_blank'] && $args['tag'] === 'a' ) {
        $args['attributes']['target'] = '_blank';
        $args['attributes']['rel'] = 'noopener noreferrer';
    }

    // Si está disabled, agregar atributo (solo para button y a, no para div)
    if ( $args['state'] === 'disabled' && $args['tag'] !== 'div' ) {
        if ( $args['tag'] === 'button' ) {
            $args['attributes']['disabled'] = 'disabled';
        } else {
            $args['attributes']['aria-disabled'] = 'true';
            $args['attributes']['tabindex'] = '-1';
        }
    }

    // Construir atributos HTML
    $attributes = '';
    if ( ! empty( $args['attributes'] ) ) {
        foreach ( $args['attributes'] as $key => $value ) {
            $attributes .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
        }
    }

    // Construir href si es enlace (solo para tag 'a')
    $href = '';
    if ( $args['tag'] === 'a' && ! empty( $args['url'] ) ) {
        $href = ' href="' . esc_url( $args['url'] ) . '"';
    }

    // Construir icono
    $icon_html = '';
    if ( $args['show_icon'] ) {
        // Si es solo icono, no usar span wrapper
        if ( $is_icon_only ) {
            $icon_html = '<i class="fa-solid fa-' . esc_attr( $args['icon'] ) . '"></i>';
        } else {
            // Si hay texto, usar span wrapper con clases
            $icon_classes = 'mwm-button__icon';
            $icon_size_class = '';
            
            // Determinar tamaño del icono según el tamaño del botón
            if ( $args['size'] === 'xl' ) {
                $icon_size_class = 'mwm-button__icon--xl';
            } elseif ( $args['size'] === 'md' ) {
                $icon_size_class = 'mwm-button__icon--md';
            } else {
                $icon_size_class = 'mwm-button__icon--sm';
            }

            $icon_html = '<span class="' . esc_attr( $icon_classes . ' ' . $icon_size_class ) . '">';
            $icon_html .= '<i class="fa-solid fa-' . esc_attr( $args['icon'] ) . '"></i>';
            $icon_html .= '</span>';
        }
    }

    // Construir elemento decorativo para outline
    $decorative_dot = '';
    if ( $args['variant'] === 'outline' ) {
        $dot_size = ( $args['size'] === 'sm' ) ? 'mwm-button__dot--sm' : 'mwm-button__dot--md';
        $decorative_dot = '<span class="mwm-button__dot ' . esc_attr( $dot_size ) . '"></span>';
    }

    // Construir HTML
    $output = '<' . esc_attr( $args['tag'] ) . $href . ' class="' . esc_attr( implode( ' ', $classes ) ) . '"' . $attributes . '>';
    
    // Para outline, agregar punto decorativo antes del texto (solo si hay texto)
    if ( $args['variant'] === 'outline' && ! $is_icon_only ) {
        $output .= $decorative_dot;
    }
    
    // Imprimir texto solo si existe
    if ( ! empty( $args['text'] ) ) {
        $output .= '<span class="mwm-button__text">' . esc_html( $args['text'] ) . '</span>';
    }
    
    // Para fill-icon, el icono va después del texto (o solo si es icon-only)
    if ( $args['variant'] === 'fill-icon' && $args['show_icon'] ) {
        $output .= $icon_html;
    }
    
    // Para link, el icono va después del texto (o solo si es icon-only)
    if ( $args['variant'] === 'link' && $args['show_icon'] ) {
        $output .= $icon_html;
    }
    
    // Si es icon-only y no es fill-icon ni link, mostrar el icono directamente
    if ( $is_icon_only && $args['variant'] !== 'fill-icon' && $args['variant'] !== 'link' ) {
        $output .= $icon_html;
    }
    
    $output .= '</' . esc_attr( $args['tag'] ) . '>';

    return $output;
}

/**
 * Genera un círculo con icono según el diseño de Figma
 *
 * @param array $args {
 *     Argumentos del círculo con icono
 *     @type string   $icon          Nombre del icono Font Awesome (requerido)
 *     @type string   $variant       Variante: 'fill-icon', 'icon' (default: 'fill-icon')
 *     @type string   $color         Color: 'primary', 'secondary', 'terciary' (default: 'primary')
 *     @type string   $size          Tamaño: 'xl', 'md', 'sm' (default: 'xl')
 *     @type string|array $class      Clases CSS adicionales (opcional, puede ser string o array)
 *     @type string   $id            ID del elemento (opcional)
 *     @type array    $attributes    Atributos HTML adicionales (opcional)
 * }
 * @return string HTML del círculo con icono
 */
function mwm_icon_circle( $args = array() ) {
	$defaults = array(
		'icon'          => '',
		'variant'       => 'fill-icon',
		'color'         => 'primary',
		'size'          => 'xl',
		'class'         => '',
		'id'            => '',
		'attributes'    => array(),
	);

	$args = wp_parse_args( $args, $defaults );

	// Validar icono requerido
	if ( empty( $args['icon'] ) ) {
		return '';
	}

	// Construir clases base
	$classes = array( 'mwm-icon-circle' );
	$classes[] = 'mwm-icon-circle--' . esc_attr( $args['variant'] );
	$classes[] = 'mwm-icon-circle--' . esc_attr( $args['color'] );
	$classes[] = 'mwm-icon-circle--' . esc_attr( $args['size'] );

	// Agregar clases adicionales (puede ser string o array)
	if ( ! empty( $args['class'] ) ) {
		if ( is_array( $args['class'] ) ) {
			$classes = array_merge( $classes, $args['class'] );
		} else {
			$classes[] = $args['class'];
		}
	}

	// Agregar ID si se proporciona
	if ( ! empty( $args['id'] ) ) {
		$args['attributes']['id'] = $args['id'];
	}

	// Construir atributos HTML
	$attributes = '';
	if ( ! empty( $args['attributes'] ) ) {
		foreach ( $args['attributes'] as $key => $value ) {
			$attributes .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}
	}

	// Construir HTML
	$output = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '"' . $attributes . '>';
	$output .= '<i class="fa-solid fa-' . esc_attr( $args['icon'] ) . '"></i>';
	$output .= '</div>';

	return $output;
}

/**
 * Obtiene la miniatura de un video de YouTube
 *
 * @param string $url URL del video de YouTube
 * @return string|false URL de la miniatura o false si no se puede obtener
 */
function mwm_get_youtube_thumbnail( $url ) {
	if ( empty( $url ) ) {
		return false;
	}

	// Extraer el ID del video de YouTube
	$pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
	preg_match( $pattern, $url, $matches );

	if ( ! empty( $matches[1] ) ) {
		$video_id = $matches[1];
		// Retornar la URL de la miniatura en alta calidad
		return 'https://img.youtube.com/vi/' . esc_attr( $video_id ) . '/maxresdefault.jpg';
	}

	return false;
}

/**
 * Extrae el ID del video de YouTube desde una URL
 *
 * @param string $url URL del video de YouTube
 * @return string|false ID del video o false si no se puede extraer
 */
function mwm_get_youtube_id( $url ) {
	if ( empty( $url ) ) {
		return false;
	}

	$pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
	preg_match( $pattern, $url, $matches );

	if ( ! empty( $matches[1] ) ) {
		return $matches[1];
	}

	return false;
}

/**
 * Agrega la clase 'mwm-menu-item--extend' a items del menú de primer nivel
 * que tengan el checkbox "Usar submenu extenso" activado
 *
 * @param array    $classes Array de clases CSS del item del menú
 * @param WP_Post  $item    Objeto del item del menú
 * @param stdClass $args    Argumentos del menú
 * @return array Array de clases modificado
 */
function mwm_add_extend_menu_class( $classes, $item, $args ) {
	// Verificar que el item sea de primer nivel (no tenga padre)
	if ( isset( $item->menu_item_parent ) && (int) $item->menu_item_parent === 0 ) {
		// Verificar que el checkbox esté activo
		$use_extended = get_field( 'menu_use_extended_submenu', $item->ID );
		
		if ( $use_extended ) {
			$classes[] = 'mwm-menu-item--extend';
		}
	}
	
	return $classes;
}
add_filter( 'nav_menu_css_class', 'mwm_add_extend_menu_class', 10, 3 );

/**
 * Modifica la estructura HTML de los subitems cuando su padre tiene el checkbox "Usar submenu extenso" activado
 * Agrega el icono y la descripción del item del menú
 *
 * @param string   $item_output El HTML del item del menú
 * @param WP_Post  $item        Objeto del item del menú
 * @param int      $depth       Profundidad del item en el menú
 * @param stdClass $args        Argumentos del menú
 * @return string HTML modificado del item
 */
function mwm_modify_extended_submenu_items( $item_output, $item, $depth, $args ) {
	// Solo modificar subitems (depth > 0 o menu_item_parent > 0)
	if ( (int) $item->menu_item_parent === 0 ) {
		return $item_output;
	}

	// Obtener el ID del padre
	$parent_id = (int) $item->menu_item_parent;
	
	// Verificar si el padre tiene el checkbox activado
	$parent_use_extended = get_field( 'menu_use_extended_submenu', $parent_id );
	
	if ( ! $parent_use_extended ) {
		return $item_output;
	}

	// Obtener el ID del objeto al que apunta el item del menú
	$object_id = (int) $item->object_id;
	$object_type = $item->object;

	// Prioridad de icono:
	// 1) Campo icono del item de menú
	// 2) Campo icono de la página vinculada
	// 3) Valor por defecto
	$page_icon = '';
	$menu_item_icon_raw = trim( (string) get_field( 'menu_item_icon', $item->ID ) );

	if ( '' !== $menu_item_icon_raw ) {
		$menu_item_icon_parts = preg_split( '/\s+/', $menu_item_icon_raw );
		foreach ( $menu_item_icon_parts as $menu_item_icon_part ) {
			if ( strpos( $menu_item_icon_part, 'fa-' ) !== 0 ) {
				continue;
			}

			if ( in_array( $menu_item_icon_part, array( 'fa-solid', 'fa-regular', 'fa-light', 'fa-thin', 'fa-duotone', 'fa-brands' ), true ) ) {
				continue;
			}

			$page_icon = substr( $menu_item_icon_part, 3 );
			break;
		}

		if ( '' === $page_icon ) {
			$page_icon = preg_replace( '/^fa-/', '', $menu_item_icon_raw );
		}
	}

	if ( '' === $page_icon && $object_type === 'page' && ! empty( $object_id ) ) {
		$page_icon = (string) get_field( 'page_icon', $object_id );
	}
	
	// Obtener la descripción del item del menú (campo nativo de WordPress)
	$menu_item_description = ! empty( $item->description ) ? trim( $item->description ) : '';

	// Construir el nuevo HTML - siempre mostrar el icono ya que tiene valor por defecto
	// Asegurarse de que el icono no esté vacío
	if ( empty( $page_icon ) ) {
		$page_icon = 'briefcase';
	}
	
	$icon_html = mwm_icon_circle( array(
		'icon'    => esc_attr( $page_icon ),
		'variant' => 'fill-icon',
		'color'   => 'terciary',
		'size'    => 'md',
	) );
	
	// Verificar que el icono se haya generado correctamente
	if ( empty( $icon_html ) ) {
		// Fallback: crear el icono manualmente si la función falla
		$icon_html = '<div class="mwm-icon-circle mwm-icon-circle--fill-icon mwm-icon-circle--primary mwm-icon-circle--sm"><i class="fa-solid fa-' . esc_attr( $page_icon ) . '"></i></div>';
	}

	// Extraer el enlace del item_output original para obtener sus atributos
	preg_match( '/<a[^>]*href=["\']([^"\']*)["\'][^>]*>(.*?)<\/a>/s', $item_output, $link_matches );
	
	// Construir el enlace con los atributos originales
	$link_attrs = '';
	if ( ! empty( $link_matches[0] ) ) {
		// Extraer todos los atributos del enlace original
		preg_match_all( '/(\w+(?:-\w+)*)=["\']([^"\']*)["\']/', $link_matches[0], $attr_matches, PREG_SET_ORDER );
		foreach ( $attr_matches as $attr ) {
			if ( $attr[1] !== 'href' ) {
				$link_attrs .= ' ' . esc_attr( $attr[1] ) . '="' . esc_attr( $attr[2] ) . '"';
			}
		}
	}
	
	$link_url = ! empty( $link_matches[1] ) ? $link_matches[1] : $item->url;
	$link_text = ! empty( $link_matches[2] ) ? $link_matches[2] : $item->title;
	
	// Construir la nueva estructura HTML con todo dentro del <a>
	// Primero construir el contenido interno
	$inner_content = '<div class="mwm-menu-item-extended__wrapper">';
	$inner_content .= '<div class="mwm-menu-item-extended__icon">' . $icon_html . '</div>';
	$inner_content .= '<div class="mwm-menu-item-extended__content">';
	$inner_content .= '<div class="mwm-menu-item-extended__title">' . esc_html( $link_text ) . '</div>';
	
	// Mostrar descripción del menú solo si existe
	if ( ! empty( $menu_item_description ) ) {
		$inner_content .= '<div class="mwm-menu-item-extended__excerpt">' . esc_html( $menu_item_description ) . '</div>';
	}
	
	$inner_content .= '</div>';
	$inner_content .= '</div>';
	
	// Construir el enlace con toda la estructura dentro
	$new_content = '<a href="' . esc_url( $link_url ) . '" class="mwm-menu-item-extended__link"' . $link_attrs . '>' . $inner_content . '</a>';

	// Verificar si el output ya fue modificado (para evitar procesamiento múltiple)
	if ( strpos( $item_output, 'mwm-menu-item-extended__link' ) !== false ) {
		// Ya fue procesado, no hacer nada más
		return $item_output;
	}
	
	// El filtro walker_nav_menu_start_el puede recibir solo el contenido del enlace
	// o el contenido completo del <li>. Intentamos ambos casos.
	
	// Caso 1: Si el output contiene un <li>, reemplazar solo su contenido
	if ( preg_match( '/<li[^>]*>/', $item_output ) ) {
		// Reemplazar el contenido del <li> manteniendo el <li> y su cierre
		$item_output = preg_replace( '/(<li[^>]*>)(.*?)(<\/li>)/s', '$1' . $new_content . '$3', $item_output );
	} else {
		// Caso 2: Si solo contiene el enlace, reemplazar el enlace completo
		if ( preg_match( '/<a[^>]*>/', $item_output ) ) {
			// Reemplazar el enlace con la nueva estructura
			$item_output = preg_replace( '/<a[^>]*>.*?<\/a>/s', $new_content, $item_output );
		} else {
			// Caso 3: Si no hay estructura reconocible, simplemente agregar la nueva estructura
			$item_output = $new_content;
		}
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'mwm_modify_extended_submenu_items', 10, 4 );

/**
 * Agrega un item adicional al submenu con información de la entrada seleccionada
 * cuando el padre tiene el checkbox "Usar submenu extenso" activado
 *
 * @param string $nav_menu HTML del menú completo
 * @param object $args     Argumentos del menú
 * @return string HTML modificado del menú
 */
function mwm_add_extended_post_to_submenu( $nav_menu, $args ) {
	// Solo procesar si es un menú del header desktop
	if ( ! isset( $args->theme_location ) || ! in_array( $args->theme_location, array( 'HeaderMenu1', 'HeaderMenu2' ), true ) ) {
		return $nav_menu;
	}

	// Solo procesar si el menú es de desktop (no mobile)
	if ( ! isset( $args->menu_context ) || $args->menu_context !== 'desktop' ) {
		return $nav_menu;
	}

	// Obtener el ID del menú
	$menu_id = isset( $args->menu->term_id ) ? $args->menu->term_id : null;
	
	if ( ! $menu_id ) {
		// Intentar obtener el ID del menú desde la ubicación
		$locations = get_nav_menu_locations();
		if ( isset( $locations[ $args->theme_location ] ) ) {
			$menu_id = $locations[ $args->theme_location ];
		} else {
			return $nav_menu;
		}
	}

	// Buscar todos los items del menú
	$menu_items = wp_get_nav_menu_items( $menu_id );
	
	if ( ! $menu_items ) {
		return $nav_menu;
	}

	foreach ( $menu_items as $menu_item ) {
		// Solo procesar items de primer nivel
		if ( (int) $menu_item->menu_item_parent !== 0 ) {
			continue;
		}

		// Verificar si tiene el checkbox activado
		$use_extended = get_field( 'menu_use_extended_submenu', $menu_item->ID );
		
		if ( ! $use_extended ) {
			continue;
		}

		$post_url       = '';
		$post_title     = '';
		$thumbnail_html = '';
		$category_name  = '';
		$category_url   = '';
		$post_date      = '';

		$extended_source = get_field( 'menu_extended_source', $menu_item->ID );
		// Compatibilidad: si no existe el campo, asumir origen 'post'
		if ( empty( $extended_source ) ) {
			$extended_source = 'post';
		}

		if ( 'manual' === $extended_source ) {
			// Modo manual: usar los campos rellenados a mano
			$manual_title   = get_field( 'menu_extended_manual_title', $menu_item->ID );
			$manual_url     = get_field( 'menu_extended_manual_url', $menu_item->ID );
			$manual_image   = get_field( 'menu_extended_manual_image', $menu_item->ID );
			$manual_cat     = get_field( 'menu_extended_manual_category_name', $menu_item->ID );
			$manual_cat_url = get_field( 'menu_extended_manual_category_url', $menu_item->ID );
			$manual_date    = get_field( 'menu_extended_manual_date', $menu_item->ID );

			if ( empty( $manual_title ) || empty( $manual_url ) ) {
				continue;
			}

			$post_title = $manual_title;
			$post_url   = $manual_url;
			$post_date  = ! empty( $manual_date ) ? $manual_date : '';

			if ( ! empty( $manual_cat ) ) {
				$category_name = esc_html( $manual_cat );
				$category_url  = ! empty( $manual_cat_url ) ? esc_url( $manual_cat_url ) : '';
			}

			if ( ! empty( $manual_image ) && is_array( $manual_image ) && isset( $manual_image['ID'] ) ) {
				$thumbnail_alt = ! empty( $manual_image['alt'] ) ? $manual_image['alt'] : $post_title;
				$thumbnail_html = wp_get_attachment_image( $manual_image['ID'], 'full', false, array(
					'alt' => esc_attr( $thumbnail_alt ),
				) );
			}
		} else {
			// Modo post: obtener la entrada seleccionada o la última publicada si no hay ninguna
			$extended_post = get_field( 'menu_extended_post', $menu_item->ID );
			if ( ! $extended_post || ! is_object( $extended_post ) ) {
				$latest_posts = get_posts( array(
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'posts_per_page' => 1,
					'orderby'        => 'date',
					'order'          => 'DESC',
				) );
				$extended_post = ! empty( $latest_posts[0] ) ? $latest_posts[0] : null;
			}
			if ( ! $extended_post || ! is_object( $extended_post ) ) {
				continue;
			}

			$post_id    = $extended_post->ID;
			$post_url   = get_permalink( $post_id );
			$post_title = get_the_title( $post_id );

			// Obtener thumbnail
			$thumbnail_id = get_post_thumbnail_id( $post_id );
			if ( $thumbnail_id ) {
				$thumbnail_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
				if ( empty( $thumbnail_alt ) ) {
					$thumbnail_alt = $post_title;
				}
				$thumbnail_html = wp_get_attachment_image( $thumbnail_id, 'full', false, array(
					'alt' => esc_attr( $thumbnail_alt ),
				) );
			}

			// Obtener categorías
			$categories = get_the_category( $post_id );
			if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
				$category   = $categories[0];
				$category_name = esc_html( $category->name );
				$category_url  = get_category_link( $category->term_id );
			}

			// Obtener fecha en formato d.m.Y (ej: 12.03.2025)
			$post_date = get_the_date( 'd.m.Y', $post_id );
		}

		// Verificar que tenemos al menos título y enlace para mostrar el card
		if ( empty( $post_title ) || empty( $post_url ) ) {
			continue;
		}
		
		// Construir el HTML del post (no será un <li> ya que estará fuera del <ul>)
		$post_item_html = '<div class="mwm-card-05 mwm-card-05--has-border">';
		$post_item_html .= '<div class="mwm-card-05__wrapper">';
		
		if ( ! empty( $thumbnail_html ) ) {
			$post_item_html .= '<div class="mwm-card-05__media">';
			$post_item_html .= '<a href="' . esc_url( $post_url ) . '"></a>';
			$post_item_html .= $thumbnail_html;
			$post_item_html .= '</div>';
		}
		
		$post_item_html .= '<div class="mwm-card-05__content">';
		
		$post_item_html .= '<div class="mwm-card-05__meta">';
		if ( ! empty( $category_name ) && ! empty( $category_url ) ) {
			$post_item_html .= mwm_button( array(
				'text'    => $category_name,
				'url'     => $category_url,
				'variant' => 'soft',
				'color'   => 'terciary',
				'size'    => 'sm',
				'class'   => 'mwm-card-05__category',
			) );
		}
		
		if ( ! empty( $post_date ) ) {
			$post_item_html .= '<div class="mwm-card-05__date">' . esc_html( $post_date ) . '</div>';
		}
		$post_item_html .= '</div>';
		
		$post_item_html .= '<div class="mwm-card-05__title">' . esc_html( $post_title ) . '</div>';
		$post_item_html .= '</div>';
		$post_item_html .= '</div>';
		$post_item_html .= '</div>';
		
		// Buscar el submenu de este item y envolverlo junto con el post en un contenedor
		$menu_item_id = 'menu-item-' . $menu_item->ID;
		$menu_item_class = 'menu-item-' . $menu_item->ID;
		
		// Buscar el submenu específico de este item y envolverlo en un contenedor
		// Patrón con ID (menú principal): captura el <li> completo con su contenido
		// Estructura: <li>...<a>...</a><ul>...</ul></li>
		$submenu_pattern_with_id = '/(<li[^>]*id="' . preg_quote( $menu_item_id, '/' ) . '"[^>]*>.*?<\/a>)(\s*<ul[^>]*class="[^"]*sub-menu[^"]*"[^>]*>)(.*?)(<\/ul>\s*)(<\/li>)/s';
		
		// Patrón con clase (menú fixed)
		$submenu_pattern_with_class = '/(<li[^>]*class="[^"]*' . preg_quote( $menu_item_class, '/' ) . '[^"]*"[^>]*>.*?<\/a>)(\s*<ul[^>]*class="[^"]*sub-menu[^"]*"[^>]*>)(.*?)(<\/ul>\s*)(<\/li>)/s';
		
		// Intentar primero con ID
		if ( preg_match( $submenu_pattern_with_id, $nav_menu, $matches ) ) {
			// Reconstruir: <li>...<a>...</a> + <div> + <ul> + contenido + </ul> + post + </div> + </li>
			$replacement = '$1<div class="mwm-menu-extended-container">$2$3$4' . $post_item_html . '</div>$5';
			$nav_menu = preg_replace( $submenu_pattern_with_id, $replacement, $nav_menu );
		} elseif ( preg_match( $submenu_pattern_with_class, $nav_menu, $matches ) ) {
			// Si no encuentra con ID, buscar por clase
			$replacement = '$1<div class="mwm-menu-extended-container">$2$3$4' . $post_item_html . '</div>$5';
			$nav_menu = preg_replace( $submenu_pattern_with_class, $replacement, $nav_menu );
		}
	}

	return $nav_menu;
}
add_filter( 'wp_nav_menu', 'mwm_add_extended_post_to_submenu', 10, 2 );

if ( ! function_exists( 'mwm_centro_ayuda_sanitize_slug_param' ) ) {
	/**
	 * Limpia slugs en query/GET cuando la URL está mal concatenada (p. ej. `general?mwm_debug=1` en vez de `&mwm_debug=1`).
	 *
	 * @param string $raw Valor crudo.
	 * @return string
	 */
	function mwm_centro_ayuda_sanitize_slug_param( $raw ) {
		if ( ! is_string( $raw ) || '' === $raw ) {
			return '';
		}
		$raw = wp_unslash( $raw );
		if ( false !== strpos( $raw, '?' ) ) {
			$parts = explode( '?', $raw, 2 );
			$raw   = $parts[0];
		}
		return sanitize_text_field( $raw );
	}
}

if ( ! function_exists( 'mwm_centro_ayuda_request_clean_category_slug' ) ) {
	/**
	 * Corrige el query var de categoría antes de que WP construya la consulta.
	 *
	 * @param array $query_vars Query vars.
	 * @return array
	 */
	function mwm_centro_ayuda_request_clean_category_slug( $query_vars ) {
		$tax = 'category_centro_de_ayuda';
		if ( isset( $query_vars[ $tax ] ) && is_string( $query_vars[ $tax ] ) ) {
			$query_vars[ $tax ] = mwm_centro_ayuda_sanitize_slug_param( $query_vars[ $tax ] );
		}
		return $query_vars;
	}
	add_filter( 'request', 'mwm_centro_ayuda_request_clean_category_slug', 0 );
}

if ( ! function_exists( 'mwm_centro_ayuda_query_posts_per_page' ) ) {
	/**
	 * Misma paginación en archive, categoría y búsqueda del centro de ayuda (por defecto WP suele ser 10).
	 *
	 * @param WP_Query $query Query principal.
	 */
	function mwm_centro_ayuda_query_posts_per_page( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$pt  = 'centro_de_ayuda';
		$tax = 'category_centro_de_ayuda';

		$apply = false;
		if ( $query->is_post_type_archive( $pt ) || $query->is_tax( $tax ) ) {
			$apply = true;
		}

		if ( $query->is_search() ) {
			$get_pt = isset( $_GET['post_type'] ) ? mwm_centro_ayuda_sanitize_slug_param( wp_unslash( $_GET['post_type'] ) ) : '';
			$qv_pt  = $query->get( 'post_type' );
			if ( $pt === $get_pt || $pt === $qv_pt || ( is_array( $qv_pt ) && in_array( $pt, $qv_pt, true ) ) ) {
				$apply = true;
			}
		}

		if ( ! $apply ) {
			return;
		}

		/**
		 * Número de artículos por página en listados del centro de ayuda.
		 *
		 * @param int $ppp Por defecto 12.
		 */
		$ppp = (int) apply_filters( 'mwm_centro_ayuda_posts_per_page', 12 );
		if ( $ppp > 0 ) {
			$query->set( 'posts_per_page', $ppp );
		}
	}
	add_action( 'pre_get_posts', 'mwm_centro_ayuda_query_posts_per_page', 5 );
}

if ( ! function_exists( 'mwm_webinar_query_posts_per_page' ) ) {
	/**
	 * Paginación del archive y taxonomía de webinars.
	 *
	 * @param WP_Query $query Query principal.
	 */
	function mwm_webinar_query_posts_per_page( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( ! $query->is_post_type_archive( 'webinar' ) && ! $query->is_tax( 'category_webinar' ) ) {
			return;
		}

		/**
		 * Número de webinars por página en archivo y categoría.
		 *
		 * @param int $ppp Por defecto 12.
		 */
		$ppp = (int) apply_filters( 'mwm_webinar_posts_per_page', 12 );
		if ( $ppp > 0 ) {
			$query->set( 'posts_per_page', $ppp );
		}
	}
	add_action( 'pre_get_posts', 'mwm_webinar_query_posts_per_page', 5 );
}

if ( ! function_exists( 'mwm_centro_ayuda_main_query_search_and_tax' ) ) {
	/**
	 * Combina búsqueda (s) con categoría del centro de ayuda en la query principal.
	 *
	 * WordPress no aplica bien tax_query + s solo con parámetros GET; hay que fijarlo aquí.
	 *
	 * @param WP_Query $query Query principal.
	 */
	function mwm_centro_ayuda_main_query_search_and_tax( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$tax = 'category_centro_de_ayuda';
		$pt  = 'centro_de_ayuda';

		$has_s = isset( $_GET['s'] ) && '' !== trim( sanitize_text_field( wp_unslash( $_GET['s'] ) ) );
		$s     = $has_s ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

		$cat_slug_get = isset( $_GET[ $tax ] ) ? mwm_centro_ayuda_sanitize_slug_param( wp_unslash( $_GET[ $tax ] ) ) : '';
		$cat_slug_qv  = mwm_centro_ayuda_sanitize_slug_param( (string) $query->get( $tax ) );

		/**
		 * Resuelve término por slug (GET, query var o path).
		 *
		 * @param string $slug Slug.
		 * @return WP_Term|null
		 */
		$mwm_centro_term_from_slug = function ( $slug ) use ( $tax ) {
			if ( '' === $slug ) {
				return null;
			}
			$term = get_term_by( 'slug', $slug, $tax );
			return ( $term && ! is_wp_error( $term ) ) ? $term : null;
		};

		// Archivo de término: mantener CPT y mezclar búsqueda con el término ya aplicado por WP.
		if ( $query->is_tax( $tax ) ) {
			$query->set( 'post_type', $pt );
			if ( $has_s ) {
				$query->set( 's', $s );
			}
			return;
		}

		/*
		 * Refuerzo: /centro-de-ayuda/?s=…&post_type=centro_de_ayuda&category_centro_de_ayuda=…
		 * En algunos entornos la query principal no queda como is_search(); forzamos CPT + s + tax aquí.
		 */
		$get_pt = isset( $_GET['post_type'] ) ? mwm_centro_ayuda_sanitize_slug_param( wp_unslash( $_GET['post_type'] ) ) : '';
		if ( $has_s && $cat_slug_get && $pt === $get_pt ) {
			$query->set( 'post_type', $pt );
			$query->set( 's', $s );
			$term = $mwm_centro_term_from_slug( $cat_slug_get );
			if ( $term ) {
				$query->set(
					'tax_query',
					array(
						array(
							'taxonomy' => $tax,
							'field'    => 'term_id',
							'terms'    => (int) $term->term_id,
						),
					)
				);
			}
			return;
		}

		// Búsqueda acotada a centro de ayuda (+ categoría opcional por GET).
		if ( $query->is_search() ) {
			$post_type_q = $query->get( 'post_type' );
			$get_pt      = isset( $_GET['post_type'] ) ? mwm_centro_ayuda_sanitize_slug_param( wp_unslash( $_GET['post_type'] ) ) : '';
			$is_centro   = ( $pt === $post_type_q )
				|| ( is_array( $post_type_q ) && in_array( $pt, $post_type_q, true ) )
				|| ( $pt === $get_pt );

			if ( ! $is_centro ) {
				return;
			}

			$query->set( 'post_type', $pt );

			$slug = $cat_slug_get ? $cat_slug_get : $cat_slug_qv;
			$term = $mwm_centro_term_from_slug( $slug );
			if ( $term ) {
				$query->set(
					'tax_query',
					array(
						array(
							'taxonomy' => $tax,
							'field'    => 'term_id',
							'terms'    => (int) $term->term_id,
						),
					)
				);
			}
			return;
		}

		// Archive del CPT: s y/o categoría en GET.
		if ( $query->is_post_type_archive( $pt ) ) {
			if ( $has_s ) {
				$query->set( 's', $s );
			}
			$slug = $cat_slug_get ? $cat_slug_get : $cat_slug_qv;
			$term = $mwm_centro_term_from_slug( $slug );
			if ( $term ) {
				$query->set(
					'tax_query',
					array(
						array(
							'taxonomy' => $tax,
							'field'    => 'term_id',
							'terms'    => (int) $term->term_id,
						),
					)
				);
			}
			return;
		}
	}
	add_action( 'pre_get_posts', 'mwm_centro_ayuda_main_query_search_and_tax', 99 );
}

if ( ! function_exists( 'mwm_centro_ayuda_force_centro_search_after_plugins' ) ) {
	/**
	 * Refuerzo muy tardío: plugins de búsqueda suelen forzar post_type=post encima de nuestra query.
	 *
	 * @param WP_Query $query Query principal.
	 */
	function mwm_centro_ayuda_force_centro_search_after_plugins( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
			return;
		}
		$pt = 'centro_de_ayuda';
		if ( ! isset( $_GET['post_type'] ) ) {
			return;
		}
		$get_pt = mwm_centro_ayuda_sanitize_slug_param( wp_unslash( $_GET['post_type'] ) );
		if ( $pt !== $get_pt ) {
			return;
		}
		$query->set( 'post_type', $pt );

		$tax  = 'category_centro_de_ayuda';
		$slug = isset( $_GET[ $tax ] ) ? mwm_centro_ayuda_sanitize_slug_param( wp_unslash( $_GET[ $tax ] ) ) : '';
		if ( '' === $slug ) {
			return;
		}
		$term = get_term_by( 'slug', $slug, $tax );
		if ( ! $term || is_wp_error( $term ) ) {
			return;
		}
		$query->set(
			'tax_query',
			array(
				array(
					'taxonomy' => $tax,
					'field'    => 'term_id',
					'terms'    => (int) $term->term_id,
				),
			)
		);
	}
	add_action( 'pre_get_posts', 'mwm_centro_ayuda_force_centro_search_after_plugins', 999 );
}

if ( ! function_exists( 'mwm_exclude_featured_and_uncategorized_posts' ) ) {
	/**
	 * Excluir el post/artículo destacado de la lista del blog o del archive del centro de ayuda.
	 */
	function mwm_exclude_featured_and_uncategorized_posts( $query ) {
		// Solo aplicar en el frontend y en la query principal
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$featured_post_id = '';
		if ( $query->is_home() || $query->is_category() ) {
			$featured_post_id = get_option( 'mwm_archive_featured_post', '' );
		} elseif (
			$query->is_post_type_archive( 'centro_de_ayuda' )
			|| $query->is_tax( 'category_centro_de_ayuda' )
			|| (
				$query->is_search()
				&& (
					'centro_de_ayuda' === $query->get( 'post_type' )
					|| ( isset( $_GET['post_type'] ) && 'centro_de_ayuda' === sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) )
				)
			)
		) {
			$featured_post_id = get_option( 'mwm_centro_ayuda_featured_post', '' );
		}

		if ( empty( $featured_post_id ) ) {
			return;
		}

		$query->set( 'post__not_in', array( (int) $featured_post_id ) );
	}
	add_action( 'pre_get_posts', 'mwm_exclude_featured_and_uncategorized_posts' );
}

/**
 * Evitar que las etiquetas del centro de ayuda tengan archivo propio sin categoría.
 *
 * No debe ser accesible /?tag_centro_de_ayuda=xxx ni cualquier petición solo por etiqueta.
 * Siempre deben ir combinadas con una categoría del centro de ayuda.
 */
if ( ! function_exists( 'mwm_restrict_centro_de_ayuda_tags_archive' ) ) {
	/**
	 * Fuerza 404 cuando se consulta solo tag_centro_de_ayuda sin categoría.
	 *
	 * @param WP_Query $query Consulta principal.
	 */
	function mwm_restrict_centro_de_ayuda_tags_archive( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$tag  = $query->get( 'tag_centro_de_ayuda' );
		$cat  = $query->get( 'category_centro_de_ayuda' );

		// Si hay etiqueta del centro de ayuda pero no categoría asociada, devolver 404.
		if ( ! empty( $tag ) && empty( $cat ) ) {
			$query->set_404();
		}
	}
	add_action( 'pre_get_posts', 'mwm_restrict_centro_de_ayuda_tags_archive', 20 );
}

/**
 * AJAX: filtro de categoría del bloque Section 16 sin recargar página.
 */
if ( ! function_exists( 'mwm_section_16_ajax_filter' ) ) {
	/**
	 * Devuelve HTML de las cards filtradas por término para Section 16.
	 */
	function mwm_section_16_ajax_filter() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'mwm_section_16_filter' ) ) {
			wp_send_json_error( array( 'message' => __( 'Seguridad no válida.', 'bilky' ) ) );
		}

		$post_type       = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : '';
		$taxonomy        = isset( $_POST['taxonomy'] ) ? sanitize_text_field( wp_unslash( $_POST['taxonomy'] ) ) : '';
		$term_slug       = isset( $_POST['term_slug'] ) ? sanitize_text_field( wp_unslash( $_POST['term_slug'] ) ) : '';
		$cpt_button_text = isset( $_POST['cpt_button_text'] ) ? sanitize_text_field( wp_unslash( $_POST['cpt_button_text'] ) ) : '';
		$cards_image_only = isset( $_POST['cards_image_only'] ) && '1' === $_POST['cards_image_only'];

		if ( empty( $post_type ) || empty( $taxonomy ) ) {
			wp_send_json_error( array( 'message' => __( 'Parámetros insuficientes.', 'bilky' ) ) );
		}

		$block_dir = get_template_directory() . '/blocks/mwm-section-16';
		require_once $block_dir . '/get-cpt-items.php';

		$items = mwm_section_16_get_cpt_items( $post_type, $taxonomy, $term_slug, $cpt_button_text );

		ob_start();
		require $block_dir . '/cards-list.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}
	add_action( 'wp_ajax_mwm_section_16_filter', 'mwm_section_16_ajax_filter' );
	add_action( 'wp_ajax_nopriv_mwm_section_16_filter', 'mwm_section_16_ajax_filter' );
}

/**
 * Encolar script del filtro Section 16 cuando el bloque está en la página.
 */
if ( ! function_exists( 'mwm_section_16_enqueue_filter_script' ) ) {
	/**
	 * Encola el JS del filtro y localiza ajaxurl y nonce.
	 *
	 * @param string $block_content Contenido renderizado del bloque.
	 * @param array  $block         Bloque.
	 * @return string Sin cambios.
	 */
	function mwm_section_16_enqueue_filter_script( $block_content, $block ) {
		if ( isset( $block['blockName'] ) && 'acf/mwm-section-16' === $block['blockName'] ) {
			wp_enqueue_script(
				'mwm-section-16-filter',
				get_template_directory_uri() . '/blocks/mwm-section-16/filter.js',
				array( 'jquery' ),
				mowomo_asset_version( '/blocks/mwm-section-16/filter.js' ),
				true
			);
			wp_localize_script(
				'mwm-section-16-filter',
				'mwmSection16',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'mwm_section_16_filter' ),
				)
			);
		}
		return $block_content;
	}
	add_filter( 'render_block', 'mwm_section_16_enqueue_filter_script', 10, 2 );
}

/**
 * Reglas explícitas de rewrite para el centro de ayuda.
 *
 * Fuerza URLs del tipo:
 * - /centro-de-ayuda/categoria/{term}/
 * - /centro-de-ayuda/categoria/{term}/page/{paged}/
 * - /centro-de-ayuda/categoria/{term}/tag/{tag}/
 * - /centro-de-ayuda/categoria/{term}/tag/{tag}/page/{paged}/
 */
if ( ! function_exists( 'mwm_centro_de_ayuda_tax_rewrite_rules' ) ) {
	/**
	 * Añade reglas personalizadas para las taxonomías del centro de ayuda.
	 */
	function mwm_centro_de_ayuda_tax_rewrite_rules() {
		// Categoría + paginación.
		add_rewrite_rule(
			'^centro-de-ayuda/categoria/([^/]+)/page/([0-9]{1,})/?$',
			'index.php?category_centro_de_ayuda=$matches[1]&paged=$matches[2]',
			'top'
		);

		// Solo categoría.
		add_rewrite_rule(
			'^centro-de-ayuda/categoria/([^/]+)/?$',
			'index.php?category_centro_de_ayuda=$matches[1]',
			'top'
		);

		// Categoría + etiqueta + paginación.
		add_rewrite_rule(
			'^centro-de-ayuda/categoria/([^/]+)/tag/([^/]+)/page/([0-9]{1,})/?$',
			'index.php?category_centro_de_ayuda=$matches[1]&tag_centro_de_ayuda=$matches[2]&paged=$matches[3]',
			'top'
		);

		// Categoría + etiqueta.
		add_rewrite_rule(
			'^centro-de-ayuda/categoria/([^/]+)/tag/([^/]+)/?$',
			'index.php?category_centro_de_ayuda=$matches[1]&tag_centro_de_ayuda=$matches[2]',
			'top'
		);
	}
	add_action( 'init', 'mwm_centro_de_ayuda_tax_rewrite_rules', 20 );
}

/**
 * Reglas explícitas de rewrite para webinars.
 *
 * URLs:
 * - /webinars/categoria/{term}/
 * - /webinars/categoria/{term}/page/{paged}/
 *
 * Tras activar el tema o este código, visita Ajustes → Enlaces permanentes y guarda
 * (o ejecuta flush_rewrite_rules una vez) para regenerar las reglas.
 */
if ( ! function_exists( 'mwm_webinar_tax_rewrite_rules' ) ) {
	/**
	 * Añade reglas personalizadas para la taxonomía category_webinar.
	 */
	function mwm_webinar_tax_rewrite_rules() {
		add_rewrite_rule(
			'^webinars/categoria/([^/]+)/page/([0-9]{1,})/?$',
			'index.php?category_webinar=$matches[1]&paged=$matches[2]',
			'top'
		);

		add_rewrite_rule(
			'^webinars/categoria/([^/]+)/?$',
			'index.php?category_webinar=$matches[1]',
			'top'
		);
	}
	add_action( 'init', 'mwm_webinar_tax_rewrite_rules', 20 );
}

if ( ! function_exists( 'mwm_print_poppins_font_preloads' ) ) {
	/**
	 * Precarga los WOFF2 de Poppins usados en primera vista para acortar la ruta crítica.
	 *
	 * Sin preload, el navegador suele descubrir las fuentes al parsear fonts.css (cadena larga).
	 * Los ficheros deben existir en assets/fonts/poppins-v24-latin/ (ruta igual que en fonts.css).
	 */
	function mwm_print_poppins_font_preloads() {
		if ( is_admin() ) {
			return;
		}

		$base = trailingslashit( get_template_directory_uri() ) . 'assets/fonts/poppins-v24-latin/';
		$files = array(
			'poppins-v24-latin-regular.woff2',
			'poppins-v24-latin-700.woff2',
			'poppins-v24-latin-300.woff2',
		);

		foreach ( $files as $file ) {
			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>',
				esc_url( $base . $file )
			);
			echo "\n";
		}
	}
	add_action( 'wp_head', 'mwm_print_poppins_font_preloads', 1 );
}


