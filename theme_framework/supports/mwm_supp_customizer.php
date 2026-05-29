<?php /* 

---- CUSTOMIZER ----

En este archivo encontrarás funciones que te ayudarán a realizar la programación de las diferentes partes del customizer.

Indice:
- Class mwmw_custom_panel -> Esta clase añade nos permite crear diferentes paneles dentro del customizer.
---- type -> mwm_custom_panel.
- Class mwm_custom_section -> Esta clase añade nos permite crear diferentes secciones dentro del customizer.
---- type -> mwm_custom_section.
- Class mwm_custom_control -> Esta clase añade nos permite crear diferentes controles dentro del customizer.
---- Type -> mwm-slider-control. Este tipo de controlador te permitirá imprimir un input de tipo slider (rango de valores) que guardará el numero elegido en una variable. 
---- Type -> mwm-separator-control. Este tipo de controlador te permite imprimir un hr con titulo dentro del panel de customizer.
---- Type -> mwm-button-control. Este tipo de controlador te permite imprimir un botón que guarda un bool en una variable.
---- Type -> mwm-tinymce-editor. Este tipo de controlador te permite imprimir un editor enriquezido.

*/


// Security
if (!defined('ABSPATH')) exit;

if( class_exists( 'WP_Customize_Control' ) ) {
	// ========== GENERAL CONTROLS ==========

	if ( !class_exists( 'mwm_custom_panel' ) ) {
		class mwm_custom_panel extends WP_Customize_Panel {
	
			public $panel;
		
			public $type = 'mwm_custom_panel';
		
			public function json() {
				$array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'type', 'panel', ) );
				$array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
				$array['content'] = $this->get_content();
				$array['active'] = $this->active();
				$array['instanceNumber'] = $this->instance_number;
	
				return $array;
			}
		}
	}

	if ( !class_exists( 'mwm_custom_section' ) ) {
		class mwm_custom_section extends WP_Customize_Section {

			public $section;
		
			public $type = 'mwm_custom_section';
		
			public function json() {
				$array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'panel', 'type', 'description_hidden', 'section', ) );
				$array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
				$array['content'] = $this->get_content();
				$array['active'] = $this->active();
				$array['instanceNumber'] = $this->instance_number;
			
				if ( $this->panel ) {
					$array['customizeAction'] = sprintf( 'Customizing &#9656; %s', esc_html( $this->manager->get_panel( $this->panel )->title ) );
				} else {
					$array['customizeAction'] = 'Customizing';
				}
		
				return $array;
			}
		}
	}


	// ========== CUSTOM CONTROLS ==========

	if ( !class_exists( 'mwm_slider_control' ) ) {
		class mwm_slider_control extends WP_Customize_Control {

			/**
			 * The type of control being rendered
			 */
			public $type = 'mwm-slider-control';

			public function __construct( $manager, $id, $args = array() ) {
				parent::__construct( $manager, $id, $args );
				$defaults = array(
					'min' => 0,
					'max' => 100,
					'step' => 1
				);
				$args = wp_parse_args( $args, $defaults );

				$this->min = $args['min'];
				$this->max = $args['max'];
				$this->step = $args['step'];
			}
			
			/**
			 * Render the control in the customizer
			 */
			public function render_content() {
			?>
				<label class="mwm-custom-controls__container">
					<span class="mwm-custom-controls__title"><?php echo esc_html( $this->label ); ?></span>
					<input id="<?php echo $this->id; ?>" class='mwm-custom-controls__range-slider' min="<?php echo $this->min ?>" max="<?php echo $this->max ?>" step="<?php echo $this->step ?>" type='range' <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>" oninput="jQuery(this).next('input').val( jQuery(this).val() );">
					<input class="mwm-custom-controls__input-number" min="<?php echo $this->min ?>" max="<?php echo $this->max ?>" step="<?php echo $this->step ?>" onKeyUp="jQuery(this).prev('input').val( jQuery(this).val() ).trigger('change');" onChange="jQuery(this).prev('input').val( jQuery(this).val() ).trigger('change');" type='number' value='<?php echo esc_attr( $this->value() ); ?>'>
				</label>
			<?php
			}
		}
	}

	if ( !class_exists( 'mwm_separator_control' ) ) {
		class mwm_separator_control extends WP_Customize_Control {

			/**
			 * The type of control being rendered
			 */
			public $type = 'mwm-separator-control';

			public function __construct( $manager, $id, $args = array() ) {
				parent::__construct( $manager, $id, $args );
				$defaults = array(
					'description' => '',
					'line' => false
				);
				$args = wp_parse_args( $args, $defaults );

				$this->description = $args['description'];
				$this->line = $args['line'];
			}
			
			/**
			 * Render the control in the customizer
			 */
			public function render_content() {
			?>
				<label class="mwm-custom-controls__container">
					<?php if ( $this->label ) : ?>
						<span class="mwm-custom-controls__title mwm-custom-controls__header"><strong><?php echo esc_html( $this->label ); ?></strong></span>
					<?php endif; ?>

					<?php if ( $this->description && $this->label ) : ?>
						<p class="description"><?php echo $this->description; ?></p>
					<?php endif; ?>

					<?php if ( $this->line ) : ?>
						<hr />
					<?php endif; ?>
				</label>
			<?php
			}
		}
	}

	if ( !class_exists( 'mwm_button_control' ) ) {
		class mwm_button_control extends WP_Customize_Control {

			/**
			 * The type of control being rendered
			 */
			public $type = 'mwm-button-control';

			public function __construct( $manager, $id, $args = array() ) {
				parent::__construct( $manager, $id, $args );
				$defaults = array(
					'id'	=>	'',
					'class'	=>	'button button-primary',
					'help' 	=> ''
				);
				$args = wp_parse_args( $args, $defaults );

				$this->id = $args['id'];
				$this->class = $args['class'];
				$this->help = $args['help'];
			}
			
			/**
			 * Render the control in the customizer
			 */
			public function render_content() {
			?>
				<label class="mwm-custom-controls__container">
					<button type="button" id="<?php echo $this->id; ?>" class="<?php echo $this->class; ?>"><?php echo $this->label; ?></button>
					<?php if ($this->help) : ?>
						<p class="mwm-custom-controls__help"><?php echo $this->help; ?></p>
					<?php endif; ?>
				</label>
			<?php
			}
		}

		if ( !class_exists( 'mwm_TinyMCE_Custom_Control' ) ) {
			class mwm_TinyMCE_Custom_Control extends WP_Customize_Control {
				
				public $type = 'mwm-tinymce-editor';
				
				public function render_content() { ?>
					<label>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<?php
							$settings = array(
								'media_buttons' => false,
								'quicktags' => false
							);
							$this->filter_editor_setting_link();
							wp_editor($this->value(), $this->id, $settings );
						?>
						</label>
					<?php
					do_action('admin_footer');
					do_action('admin_print_footer_scripts');

					}

				private function filter_editor_setting_link() {
					add_filter( 'the_editor', function( $output ) { return preg_replace( '/<textarea/', '<textarea ' . $this->get_link(), $output, 1 ); } );
				}
			}
		}
	
	}


	// ========= ASSETS ==========

	// Enqueue scripts
	if ( !function_exists( 'mwm_customize_custom_controls_scripts' ) ) {
		function mwm_customize_custom_controls_scripts() {
			wp_register_script( 'mwm-customize-controls', get_template_directory_uri() . '/theme_framework/js/customizer-script.js', array('jquery'), mowomo_asset_version( '/theme_framework/js/customizer-script.js' ), true );
			wp_register_script( 'mwm-customize-controls-preview', get_template_directory_uri() . '/theme_framework/js/preview-script.js', array('jquery'), mowomo_asset_version( '/theme_framework/js/preview-script.js' ), true );

			wp_enqueue_script( 'mwm-customize-controls' );
			wp_enqueue_script( 'mwm-customize-controls-preview' );
		}
		add_action( 'customize_controls_enqueue_scripts', 'mwm_customize_custom_controls_scripts' );
	}
	
	// Enqueue styles
	if ( !function_exists( 'mwm_customize_custom_controls_styles' ) ) {
		function mwm_customize_custom_controls_styles() {
			wp_register_style( 'mwm-customize-controls', get_template_directory_uri() . '/theme_framework/css/styles.css', array(), mowomo_asset_version( '/theme_framework/css/styles.css' ) );
			wp_enqueue_style( 'mwm-customize-controls' );
		}
		add_action( 'customize_controls_print_styles', 'mwm_customize_custom_controls_styles' );
	}
}

function mwm_cus_separador( $wp_customize, $args ) {
	$wp_customize->add_setting( $args['settings'], array(
		'type' => 'option',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( new mwm_separator_control( 
		$wp_customize, 
		$args['settings'],
		$args,
	));
}

/**
 * Sanitiza valores de checkbox del customizer para que siempre sean 0 ó 1.
 *
 * @param mixed $checked Valor recibido por el customizer.
 * @return int 1 si está marcado, 0 si no.
 */
function mwm_sanitize_checkbox( $checked ) {
	// Consideramos marcado solo cuando el valor sea 1, '1', true o 'on'.
	if ( isset( $checked ) && ( 1 === $checked || '1' === $checked || true === $checked || 'on' === $checked ) ) {
		return 1;
	}

	return 0;
}

function mwm_cus_input( $wp_customize, $args ) {
	$setting_args = array(
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	);

	if ( isset( $args['default'] ) ) {
		$setting_args['default'] = $args['default'];
	}

	// Para checkboxes, forzar sanitización a 0/1, evitando que se queden siempre en "marcado".
	if ( isset( $args['type'] ) && 'checkbox' === $args['type'] ) {
		$setting_args['sanitize_callback'] = 'mwm_sanitize_checkbox';
	}

	$wp_customize->add_setting( $args['settings'], $setting_args );
	$wp_customize->add_control( $args['settings'], $args );
	$wp_customize->selective_refresh->add_partial( $args['settings'], array(
		'selector' => '#' . $args['settings'],
	) );
}

function mwm_cus_image( $wp_customize, $args ) {
	$wp_customize->add_setting( $args['settings'], array(
		'type' => 'option',
		'default' => '', 
	) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $args['settings'], $args ) );
	$wp_customize->selective_refresh->add_partial( $args['settings'], array(
		'selector' => '#' . $args['settings'],
	) );
}

function mwm_cus_post( $wp_customize, $args ) {
	// Obtener todos los posts publicados
	$posts = get_posts( array(
		'post_type'      => isset( $args['post_type'] ) ? $args['post_type'] : 'post',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC',
	) );

	// Construir opciones para el dropdown
	$choices = array( '' => __( '— Seleccionar —', 'bilky' ) );
	foreach ( $posts as $post ) {
		$choices[ $post->ID ] = esc_html( $post->post_title );
	}

	$setting_args = array(
		'type' => 'option',
		'capability' => 'edit_theme_options',
		'default' => isset( $args['default'] ) ? $args['default'] : '',
	);

	$wp_customize->add_setting( $args['settings'], $setting_args );

	$control_args = array(
		'label'    => isset( $args['label'] ) ? $args['label'] : '',
		'section'  => $args['section'],
		'settings' => $args['settings'],
		'type'     => 'select',
		'choices'  => $choices,
	);

	if ( isset( $args['description'] ) ) {
		$control_args['description'] = $args['description'];
	}

	$wp_customize->add_control( $args['settings'], $control_args );

	$wp_customize->selective_refresh->add_partial( $args['settings'], array(
		'selector' => '#' . $args['settings'],
	) );
}
