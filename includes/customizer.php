<?php

if ( ! function_exists( 'mwm_customize_register' ) ) {
	/**
	 * Registro de nuevos elementos en el customizer del tema
	 */
	function mwm_customize_register( $wp_customize ) {
		$wp_customize->add_panel( 'mwm_panel', array(
			'title' => __( 'Configuraciones del tema', 'bilky' ),
			'description' => __( 'Configuración de diferentes elementos de la web', 'bilky' ),
			'priority' => 0,
			'capability' => 'edit_theme_options',
		));
        mwm_cus_section_header( $wp_customize, 'mwm_panel', 'header' );
        mwm_cus_section_footer( $wp_customize, 'mwm_panel', 'footer' );
        mwm_cus_section_archive( $wp_customize, 'mwm_panel', 'archive' );
        mwm_cus_section_archive_centro_ayuda( $wp_customize, 'mwm_panel', 'archive_centro_ayuda' );
        mwm_cus_section_archive_webinars( $wp_customize, 'mwm_panel', 'archive_webinars' );
        mwm_cus_section_404( $wp_customize, 'mwm_panel', '404' );
	}
	add_action( 'customize_register', 'mwm_customize_register' );
}

function mwm_cus_section_header($wp_customize, $panel, $section){
    $wp_customize->add_section( $section , array(
        'title' 		=> __( 'Header', 'bilky' ),
        'panel' 		=> $panel,
        'priority' => 3,
        'capability'	=> 'edit_theme_options',
    ));

    // Botón Registrarse.
    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'mwm_header_register_button_text',
        'label' 	=> __( 'Texto botón Registrarse', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'mwm_header_register_button_link',
        'label' 	=> __( 'Link botón Registrarse', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section'   => $section,
        'settings'  => 'mwm_header_register_button_target_blank',
        'label'     => __( 'Abrir botón Registrarse en una pestaña nueva', 'bilky' ),
        'type'      => 'checkbox',
    ));

    // Botón Acceder.
    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'mwm_header_access_button_text',
        'label' 	=> __( 'Texto botón Acceder', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'mwm_header_access_button_link',
        'label' 	=> __( 'Link botón Acceder', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section'   => $section,
        'settings'  => 'mwm_header_access_button_target_blank',
        'label'     => __( 'Abrir botón Acceder en una pestaña nueva', 'bilky' ),
        'type'      => 'checkbox',
    ));
}

function mwm_cus_section_footer($wp_customize, $panel, $section){
    $wp_customize->add_section( $section , array(
        'title' 		=> __( 'Footer', 'bilky' ),
        'panel' 		=> $panel,
        'priority' => 4,
        'capability'	=> 'edit_theme_options',
    ));

    // Logo
    mwm_cus_separador( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_logo_sep',
        'label' 	=> __( 'Logo', 'bilky' ),
        'line'      => 'true',
    ));

    mwm_cus_image( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_logo_1_image',
        'label' 	=> __( 'Logo 1 del footer', 'bilky' ),
    ));

    mwm_cus_image( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_logo_2_image',
        'label' 	=> __( 'Logo 2 del footer', 'bilky' ),
    ));

    // Títulos de menús
    mwm_cus_separador( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_menus_sep',
        'label' 	=> __( 'Títulos de Menús', 'bilky' ),
        'line'      => 'true',
    ));

    // Certificaciones
    mwm_cus_separador( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_certifications_sep',
        'label' 	=> __( 'Certificaciones', 'bilky' ),
        'line'      => 'true',
    ));

    mwm_cus_image( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_iso_image',
        'label' 	=> __( 'Imagen ISO 27001', 'bilky' ),
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_iso_url',
        'label' 	=> __( 'Enlace ISO 27001 (opcional)', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section'   => $section,
        'settings'  => 'footer_iso_new_tab',
        'label'     => __( 'Abrir enlace ISO 27001 en una pestaña nueva', 'bilky' ),
        'type'      => 'checkbox',
    ));

    mwm_cus_image( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_agencia_tributaria_image',
        'label' 	=> __( 'Imagen Agencia Tributaria', 'bilky' ),
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_agencia_tributaria_url',
        'label' 	=> __( 'Enlace Agencia Tributaria (opcional)', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section'   => $section,
        'settings'  => 'footer_agencia_tributaria_new_tab',
        'label'     => __( 'Abrir enlace Agencia Tributaria en una pestaña nueva', 'bilky' ),
        'type'      => 'checkbox',
    ));

    // Botón Acceder
    mwm_cus_separador( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_button_sep',
        'label' 	=> __( 'Botón Acceder', 'bilky' ),
        'line'      => 'true',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_acceder_url',
        'label' 	=> __( 'URL botón Acceder', 'bilky' ),
        'type' 		=> 'text',
    ));

    // Copyright
    mwm_cus_separador( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_copyright_sep',
        'label' 	=> __( 'Copyright', 'bilky' ),
        'line'      => 'true',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_menu_title_1',
        'label' 	=> __( 'Título menú 1', 'bilky' ),
        'type' 		=> 'text',
        'default'   => __( 'Funcionalidades', 'bilky' ),
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_menu_title_2',
        'label' 	=> __( 'Título menú 2', 'bilky' ),
        'type' 		=> 'text',
        'default'   => __( 'Recursos', 'bilky' ),
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_copyright',
        'label' 	=> __( 'Texto de copyright', 'bilky' ),
        'type' 		=> 'text',
        'default'   => sprintf( __( '© %d - %d Bilky', 'bilky' ), 2015, date( 'Y' ) ),
    ));

    // Redes sociales
    mwm_cus_separador( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_social_sep',
        'label' 	=> __( 'Redes Sociales', 'bilky' ),
        'line'      => 'true',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_social_facebook',
        'label' 	=> __( 'URL Facebook', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_social_instagram',
        'label' 	=> __( 'URL Instagram', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_social_linkedin',
        'label' 	=> __( 'URL LinkedIn', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_social_tiktok',
        'label' 	=> __( 'URL TikTok', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_social_vimeo',
        'label' 	=> __( 'URL Vimeo', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'footer_social_youtube',
        'label' 	=> __( 'URL YouTube', 'bilky' ),
        'type' 		=> 'text',
    ));
}

function mwm_cus_section_archive($wp_customize, $panel, $section){
    $wp_customize->add_section( $section , array(
        'title' 		=> __( 'Archive blog', 'bilky' ),
        'panel' 		=> $panel,
        'priority' => 3,
        'capability'	=> 'edit_theme_options',
    ));


    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'mwm_archive_breadcrumb_1',
        'label' 	=> __( 'Texto de la breadcrumb 1', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'mwm_archive_breadcrumb_2',
        'label' 	=> __( 'Texto de la breadcrumb 2', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'mwm_archive_title',
        'label' 	=> __( 'Título de la página', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_post( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'mwm_archive_featured_post',
        'label' 	=> __( 'Post destacado', 'bilky' ),
        'description' => __( 'Selecciona el post que se mostrará en la sección destacada', 'bilky' ),
        'post_type' => 'post',
    ));
}

/**
 * Sección del customizer para el archive y single del CPT Centro de ayuda.
 * Mismos campos que el archive del blog pero con opciones propias.
 */
function mwm_cus_section_archive_centro_ayuda( $wp_customize, $panel, $section ) {
	$wp_customize->add_section(
		$section,
		array(
			'title'       => __( 'Archive centro de ayuda', 'bilky' ),
			'panel'       => $panel,
			'priority'    => 3,
			'capability'  => 'edit_theme_options',
		)
	);

	mwm_cus_input(
		$wp_customize,
		array(
			'section'  => $section,
			'settings' => 'mwm_centro_ayuda_breadcrumb_1',
			'label'    => __( 'Texto de la breadcrumb 1', 'bilky' ),
			'type'     => 'text',
		)
	);

	mwm_cus_input(
		$wp_customize,
		array(
			'section'  => $section,
			'settings' => 'mwm_centro_ayuda_breadcrumb_2',
			'label'    => __( 'Texto de la breadcrumb 2', 'bilky' ),
			'type'     => 'text',
		)
	);

	mwm_cus_input(
		$wp_customize,
		array(
			'section'  => $section,
			'settings' => 'mwm_centro_ayuda_title',
			'label'    => __( 'Título de la página', 'bilky' ),
			'type'     => 'text',
		)
	);

	mwm_cus_post(
		$wp_customize,
		array(
			'section'      => $section,
			'settings'    => 'mwm_centro_ayuda_featured_post',
			'label'       => __( 'Artículo destacado', 'bilky' ),
			'description' => __( 'Selecciona el artículo del centro de ayuda que se mostrará en la sección destacada', 'bilky' ),
			'post_type'   => 'centro_de_ayuda',
		)
	);
}

/**
 * Sección del customizer para el archive de webinars.
 */
function mwm_cus_section_archive_webinars( $wp_customize, $panel, $section ) {
	$wp_customize->add_section(
		$section,
		array(
			'title'      => __( 'Archive webinars', 'bilky' ),
			'panel'      => $panel,
			'priority'   => 4,
			'capability' => 'edit_theme_options',
		)
	);

	mwm_cus_input(
		$wp_customize,
		array(
			'section'  => $section,
			'settings' => 'mwm_webinar_breadcrumb_1',
			'label'    => __( 'Texto de la breadcrumb 1', 'bilky' ),
			'type'     => 'text',
			'default'  => __( 'Sesiones en directo', 'bilky' ),
		)
	);

	mwm_cus_input(
		$wp_customize,
		array(
			'section'  => $section,
			'settings' => 'mwm_webinar_breadcrumb_2',
			'label'    => __( 'Texto de la breadcrumb 2 (opcional)', 'bilky' ),
			'type'     => 'text',
		)
	);

	mwm_cus_input(
		$wp_customize,
		array(
			'section'  => $section,
			'settings' => 'mwm_webinar_title',
			'label'    => __( 'Título de la página', 'bilky' ),
			'type'     => 'text',
			'default'  => __( 'Controla y gestiona las facturas de tus clientes desde un único lugar.', 'bilky' ),
		)
	);

	mwm_cus_input(
		$wp_customize,
		array(
			'section'  => $section,
			'settings' => 'mwm_webinar_description',
			'label'    => __( 'Descripción bajo el título (opcional)', 'bilky' ),
			'type'     => 'textarea',
		)
	);
}

function mwm_cus_section_404($wp_customize, $panel, $section){
    $wp_customize->add_section( $section , array(
        'title' 		=> __( 'Página 404', 'bilky' ),
        'panel' 		=> $panel,
        'priority' => 3,
        'capability'	=> 'edit_theme_options',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'mwm_404_breadcrumb_1',
        'label' 	=> __( 'Texto de la breadcrumb 1', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'mwm_404_title',
        'label' 	=> __( 'Título de la página', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'mwm_404_description',
        'label' 	=> __( 'Descripción de la página', 'bilky' ),
        'type' 		=> 'textarea',
    ));

    mwm_cus_input( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'mwm_404_button_text',
        'label' 	=> __( 'Texto botón', 'bilky' ),
        'type' 		=> 'text',
    ));

    mwm_cus_image( $wp_customize, array(
        'section' 	=> $section,
        'settings'	=> 'mwm_404_img',
        'label' 	=> __( 'Imagen de fondo', 'bilky' ),
    ));
}
