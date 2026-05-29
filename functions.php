<?php

/*  TEXT DOMAIN CONSTANT
=============================================== */
/* Esto tiene que ser lo mismo que en el Text Domain del style.scss */

if (!defined('bilky')) {
    define('bilky', 'base');
}

/*  INCLUDES
=============================================== */
require_once( get_template_directory() . '/theme_framework/mwm_framework.php' );
require_once( get_template_directory() . '/includes/functions.php' );

/*
 * ACF Blocks v3: panel lateral ampliado (“expanded editing”) y lápiz en la barra del bloque.
 * Requiere ACF PRO 6.6+ (https://www.advancedcustomfields.com/resources/acf-blocks-v3/).
 */
add_filter(
	'acf/blocks/default_block_version',
	static function ( $version, $block ) {
		return 3;
	},
	10,
	2
);

/*  THEME SUPPORTS
=============================================== */

$supports = array(
	'align-wide',
	'custom-logo',
	'title-tag',
	'post_type_support' => array(
        'page' => 'excerpt',
    )
);

mwm_add_theme_support($supports);

/*  THEME SETUP
=============================================== */

function mwm_theme_setup() {

    // Add custom font sizes.
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => 'D100',
            'size' => 40,
            'slug' => 'd100'
        ),
        array(
            'name' => 'H100',
            'size' => 38,
            'slug' => 'h100'
        ),
        array(
            'name' => 'H200',
            'size' => 24,
            'slug' => 'h200'
        ),
        array(
            'name' => 'H300',
            'size' => 26,
            'slug' => 'h300'
        ),
        array(
            'name' => 'H400',
            'size' => 24,
            'slug' => 'h400'
        ),
        array(
            'name' => 'H500',
            'size' => 20,
            'slug' => 'h500'
        ),
        array(
            'name' => 'B100',
            'size' => 16,
            'slug' => 'b100'
        ),
        array(
            'name' => 'B200',
            'size' => 14,
            'slug' => 'b200'
        ),
        array(
            'name' => 'B300',
            'size' => 12,
            'slug' => 'b300'
        ),
    ));

    // Mismas hojas de estilo del front dentro del iframe del editor (Gutenberg).
    add_theme_support( 'editor-styles' );
    add_editor_style(
        array(
            'assets/css/normalize.css',
            'assets/fonts/fonts.css',
            'assets/js/swiper/swiper-bundle.min.css',
            'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css',
            'style.min.css',
        )
    );
}

add_action('after_setup_theme', 'mwm_theme_setup');

/**
 * Evita que el CSS global del tema se inyecte en TinyMCE (ACF WYSIWYG).
 *
 * Mantiene los estilos del iframe de Gutenberg para bloques, pero limpia
 * el editor enriquecido para que no herede maquetacion global.
 *
 * @param string $mce_css Lista de URLs separadas por coma.
 * @return string
 */
function mwm_strip_theme_css_from_tinymce( $mce_css ) {
	if ( ! is_admin() || empty( $mce_css ) ) {
		return $mce_css;
	}

	$theme_uri = untrailingslashit( get_template_directory_uri() );
	$blocked   = array(
		$theme_uri . '/style.css',
		$theme_uri . '/style.min.css',
		$theme_uri . '/assets/css/normalize.css',
		$theme_uri . '/assets/fonts/fonts.css',
		$theme_uri . '/assets/js/swiper/swiper-bundle.min.css',
		'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css',
	);

	$styles = array_filter( array_map( 'trim', explode( ',', (string) $mce_css ) ) );

	$styles = array_filter(
		$styles,
		static function ( $style ) use ( $blocked ) {
			$normalized = strtok( (string) $style, '?' );
			return ! in_array( $normalized, $blocked, true );
		}
	);

	return implode( ',', $styles );
}
add_filter( 'mce_css', 'mwm_strip_theme_css_from_tinymce', 20 );

/*  MENUS
=============================================== */

$menus = array (
	'HeaderMenu1' => 'Menú superior 1',
	'HeaderMenu2' => 'Menú superior 2',
	'FooterMenu1' => 'Menú footer 1',
	'FooterMenu2' => 'Menú footer 2',
	'FooterMenu3' => 'Menú footer 3',
	'FooterMenuLegal' => 'Menú footer legal',
);
mwm_add_menu($menus);

/*  STYLES AND SCRIPTS
=============================================== */

if ( ! function_exists( 'mwm_enqueue_scripts' ) ) {
	function mwm_enqueue_scripts() {
		$scripts = array(
			'fontawesome' => array(
				'path' => 'https://kit.fontawesome.com/3f911259d4.js',
				'deps' => array(),
				'ver' => '6.0.0',
				'in_footer' => false
			),
			'swiper' => array(
				'path' => get_template_directory_uri() . '/assets/js/swiper/swiper-bundle.min.js',
				'deps' => array('jquery'),
				'ver' => mowomo_asset_version( '/assets/js/swiper/swiper-bundle.min.js' ),
				'in_footer' => true
			),
			'fancybox' => array(
				'path' => 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js',
				'deps' => array('jquery'),
				'ver' => '5.0.0',
				'in_footer' => true
			),
			'mwm-scripts' => array(
				'path' => get_template_directory_uri() . '/assets/js/scripts.js',
				'deps' => array('jquery', 'swiper', 'fancybox'),
				'ver' => mowomo_asset_version( '/assets/js/scripts.js' ),
				'in_footer' => true
			)
		);
		mwm_add_scripts($scripts);
		
		$styles = array(
			'mwm-normalize' => array(
				'path' => get_template_directory_uri() . '/assets/css/normalize.css',
				'deps' => array(),
				'ver' => mowomo_asset_version( '/assets/css/normalize.css' ),
				'media' => 'all'
			),
			'mwm-fonts' => array(
				'path' => get_template_directory_uri() . '/assets/fonts/fonts.css',
				'deps' => array(),
				'ver' => mowomo_asset_version( '/assets/fonts/fonts.css' ),
				'media' => 'all'
			),
			'swiper' => array(
				'path' => get_template_directory_uri() . '/assets/js/swiper/swiper-bundle.min.css',
				'deps' => array(),
				'ver' => mowomo_asset_version( '/assets/js/swiper/swiper-bundle.min.css' ),
				'media' => 'all',
				'in_footer' => true
			),
			'fancybox' => array(
				'path' => 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css',
				'deps' => array(),
				'ver' => '5.0.0',
				'media' => 'all'
			),
			'mwm-styles' => array(
				'path' => get_template_directory_uri() . '/style.min.css',
				'deps' => array('mwm-normalize', 'mwm-fonts', 'swiper', 'fancybox'),
				'ver' => mowomo_asset_version( '/style.min.css' ),
				'media' => 'all'
			)
		);
		mwm_add_styles($styles);
	}
	add_action( 'wp_enqueue_scripts', 'mwm_enqueue_scripts' );

	// Agregar atributo crossorigin al script de Font Awesome
	if ( ! function_exists( 'mwm_fontawesome_script_loader_tag' ) ) {
		function mwm_fontawesome_script_loader_tag( $tag, $handle, $src ) {
			if ( 'fontawesome' === $handle ) {
				$tag = str_replace( '<script ', '<script crossorigin="anonymous" ', $tag );
			}
			return $tag;
		}
		add_filter( 'script_loader_tag', 'mwm_fontawesome_script_loader_tag', 10, 3 );
	}
}

// Registrar scripts del editor (shell de Gutenberg).
if ( ! function_exists( 'mwm_enqueue_editor_scripts' ) ) {
	function mwm_enqueue_editor_scripts() {
		wp_enqueue_script(
			'swiper',
			get_template_directory_uri() . '/assets/js/swiper/swiper-bundle.min.js',
			array( 'jquery' ),
			mowomo_asset_version( '/assets/js/swiper/swiper-bundle.min.js' ),
			true
		);
		wp_enqueue_script(
			'mwm-editor',
			get_template_directory_uri() . '/assets/js/editor.js',
			array(
				'jquery',
				'swiper',
				'wp-blocks',
				'wp-element',
				'wp-block-editor',
				'wp-components',
				'wp-data',
				'wp-hooks',
				'wp-compose',
				'wp-i18n',
			),
			mowomo_asset_version( '/assets/js/editor.js' ),
			true
		);
	}
	add_action( 'enqueue_block_editor_assets', 'mwm_enqueue_editor_scripts' );
}

// Font Awesome + JS de Swiper en el editor (lienzo e iframe de contenido; el front sigue usando mwm_enqueue_scripts).
if ( ! function_exists( 'mwm_enqueue_block_editor_shared_assets' ) ) {
	/**
	 * Kit FA y Swiper (JS) para previews de bloques y coherencia con el front. El CSS de Swiper ya va con add_editor_style.
	 * Solo en admin: en el front `enqueue_block_assets` también se dispara, pero aquí se omite.
	 *
	 * @return void
	 */
	function mwm_enqueue_block_editor_shared_assets() {
		if ( ! is_admin() ) {
			return;
		}

		wp_enqueue_script(
			'fontawesome',
			'https://kit.fontawesome.com/3f911259d4.js',
			array(),
			'6.0.0',
			false
		);

		// El CSS de Swiper ya entra en el iframe vía add_editor_style() en mwm_theme_setup().
		wp_enqueue_script(
			'swiper',
			get_template_directory_uri() . '/assets/js/swiper/swiper-bundle.min.js',
			array( 'jquery' ),
			mowomo_asset_version( '/assets/js/swiper/swiper-bundle.min.js' ),
			true
		);
	}
	add_action( 'enqueue_block_assets', 'mwm_enqueue_block_editor_shared_assets', 5 );
}

// Añadir la clase is-right-after-header al bloque de grupo en el frontend
if ( ! function_exists( 'mwm_group_block_class' ) ) {
	function mwm_group_block_class( $block_content, $block ) {
		if ( isset( $block['blockName'] ) && $block['blockName'] === 'core/group' ) {
			if ( isset( $block['attrs']['isRightAfterHeader'] ) && $block['attrs']['isRightAfterHeader'] ) {
				// Añadir la clase al wrapper del bloque
				$block_content = preg_replace(
					'/class="([^"]*wp-block-group[^"]*)"/',
					'class="$1 is-right-after-header"',
					$block_content
				);
			}
		}
		return $block_content;
	}
	add_filter( 'render_block', 'mwm_group_block_class', 10, 2 );
}

// Funcion para obtener el contenido de un bloque reutilizable

function mwm_get_reusable_block( $block_id = '' ) { 
    if ( empty( $block_id ) || (int) $block_id !== $block_id ) {
        return;
    }
    $content = get_post_field( 'post_content', $block_id );
    return apply_filters( 'the_content', $content );
}

/**
 * Show Complianz banner when clicking a menu item where the <li> has class 'cmplz-show-banner'
 */
function cmplz_show_banner_on_click() {
?>
<script>
function addEvent(event, selector, callback) {
    document.addEventListener(event, function(e) {
        const el = e.target.closest(selector);
        if (el) {
            e.preventDefault(); // evita navegación del <a>
            callback(e, el);
        }
    });
}

addEvent('click', '.cmplz-show-banner', function(){
    document.querySelectorAll('.cmplz-manage-consent').forEach(function(obj){
        obj.click();
    });
});
</script>
<?php
}
add_action('wp_footer', 'cmplz_show_banner_on_click');