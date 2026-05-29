<?php
/**
 * Block Name: MWM Section 20 — Módulos con precio mercado/asesor
 *
 * @package bilky
 */

if ( ! isset( $block ) ) {
	$block = array();
}

$show_header       = get_field( 'show_header' );
$header_clients    = get_field( 'header_clients' );
$header_functions  = get_field( 'header_functions' );
$header_monthly    = get_field( 'header_monthly_price' );
$sections          = get_field( 'sections' );

if ( false === $show_header ) {
	$show_header = false;
} else {
	$show_header = (bool) $show_header;
}

$header_clients   = is_string( $header_clients ) ? $header_clients : '';
$header_functions = $header_functions ? $header_functions : __( 'Funciones', 'bilky' );
$header_monthly   = $header_monthly ? $header_monthly : __( 'Precio Mensual', 'bilky' );

$block_id = 'mwm-section-20';
if ( ! empty( $block['anchor'] ) ) {
	$block_id = sanitize_title( $block['anchor'] );
} elseif ( ! empty( $block['id'] ) ) {
	$block_id .= '-' . preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $block['id'] );
} else {
	$block_id .= '-' . uniqid();
}

$is_block_preview = ( isset( $is_preview ) && $is_preview ) || ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] );

if ( empty( $sections ) || ! is_array( $sections ) ) {
	if ( $is_block_preview ) {
		$sections = array(
			array(
				'section_title' => __( 'Módulos dentro del Paraguas', 'bilky' ),
				'default_open'  => true,
				'rows'          => array(
					array(
						'client_badge'        => __( 'M. Factuconnect (Empresas)', 'bilky' ),
						'functions_text'      => __( 'Todas las funciones del fichaje horario y control de vacaciones.', 'bilky' ),
						'price_mode'          => 'comparison',
						'price_strike'        => '19,99€',
						'price_market_label'  => __( 'mercado', 'bilky' ),
						'price_advisor'       => '9,99€',
						'price_advisor_label' => __( 'asesor', 'bilky' ),
					),
				),
			),
		);
		$show_header = true;
	} else {
		return;
	}
}
?>
<section class="mwm-section-20" id="<?php echo esc_attr( $block_id ); ?>">
	<div class="mwm-section-20__wrapper">
		<?php if ( $show_header ) : ?>
			<div class="mwm-section-20__header-row is-style-b-100" role="row">
				<div class="mwm-section-20__header-cell mwm-section-20__header-cell--clients<?php echo '' === trim( $header_clients ) ? ' mwm-section-20__header-cell--empty' : ''; ?>">
					<?php echo '' !== trim( $header_clients ) ? esc_html( $header_clients ) : '&nbsp;'; ?>
				</div>
				<div class="mwm-section-20__header-cell mwm-section-20__header-cell--functions">
					<?php echo esc_html( $header_functions ); ?>
				</div>
				<div class="mwm-section-20__header-cell mwm-section-20__header-cell--price">
					<?php echo esc_html( $header_monthly ); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="mwm-section-20__accordions">
			<?php
			foreach ( $sections as $index => $section ) :
				$section_title = isset( $section['section_title'] ) ? $section['section_title'] : '';
				if ( '' === trim( (string) $section_title ) ) {
					continue;
				}
				$default_open = ! empty( $section['default_open'] );
				$rows           = isset( $section['rows'] ) && is_array( $section['rows'] ) ? $section['rows'] : array();

				$panel_id   = $block_id . '-panel-' . (int) $index;
				$heading_id = $block_id . '-heading-' . (int) $index;
				?>
				<div class="mwm-section-20__accordion<?php echo $default_open ? ' is-open' : ''; ?>">
					<h3 class="mwm-section-20__accordion-heading">
						<button
							type="button"
							class="mwm-section-20__accordion-trigger"
							id="<?php echo esc_attr( $heading_id ); ?>"
							aria-expanded="<?php echo $default_open ? 'true' : 'false'; ?>"
							aria-controls="<?php echo esc_attr( $panel_id ); ?>"
						>
							<span class="mwm-section-20__accordion-title is-style-h-500">
								<?php echo esc_html( $section_title ); ?>
							</span>
							<span class="mwm-section-20__accordion-icon" aria-hidden="true">
								<span class="mwm-section-20__accordion-icon-plus"><i class="fa-solid fa-plus"></i></span>
								<span class="mwm-section-20__accordion-icon-minus"><i class="fa-solid fa-minus"></i></span>
							</span>
						</button>
					</h3>
					<div
						class="mwm-section-20__accordion-panel"
						id="<?php echo esc_attr( $panel_id ); ?>"
						role="region"
						aria-labelledby="<?php echo esc_attr( $heading_id ); ?>"
						aria-hidden="<?php echo $default_open ? 'false' : 'true'; ?>"
					>
						<div class="mwm-section-20__accordion-panel-inner">
						<?php
						$valid_rows = array();
						if ( ! empty( $rows ) ) {
							foreach ( $rows as $r ) {
								$b = isset( $r['client_badge'] ) ? $r['client_badge'] : '';
								if ( '' !== trim( (string) $b ) ) {
									$valid_rows[] = $r;
								}
							}
						}
						?>
						<?php if ( ! empty( $valid_rows ) ) : ?>
							<div class="mwm-section-20__rows">
								<?php
								$rows_count = count( $valid_rows );
								foreach ( $valid_rows as $idx => $row ) :
									$badge = isset( $row['client_badge'] ) ? $row['client_badge'] : '';
									$functions_text = isset( $row['functions_text'] ) ? $row['functions_text'] : '';
									$price_mode     = isset( $row['price_mode'] ) ? $row['price_mode'] : 'comparison';

									$row_pos_class = '';
									if ( 0 === $idx ) {
										$row_pos_class = ' mwm-section-20__table-row--first';
									}
									if ( $idx === $rows_count - 1 ) {
										$row_pos_class .= ' mwm-section-20__table-row--last';
									}
									?>
									<div class="mwm-section-20__table-row<?php echo esc_attr( $row_pos_class ); ?>" role="row">
										<div class="mwm-section-20__cell mwm-section-20__cell--client">
											<span class="mwm-section-20__client-badge">
												<?php echo esc_html( $badge ); ?>
											</span>
										</div>
										<div class="mwm-section-20__cell mwm-section-20__cell--functions is-style-b-200">
											<span class="mwm-section-20__col-label"><?php echo esc_html( $header_functions ); ?>:</span>
											<div class="mwm-section-20__functions-text">
												<?php echo nl2br( esc_html( $functions_text ) ); ?>
											</div>
										</div>
										<div class="mwm-section-20__cell mwm-section-20__cell--price">
											<span class="mwm-section-20__col-label"><?php echo esc_html( $header_monthly ); ?>:</span>
											<?php if ( 'custom' === $price_mode ) : ?>
												<?php
												$c_strong = isset( $row['price_custom_strong'] ) ? $row['price_custom_strong'] : '';
												$c_rest   = isset( $row['price_custom_rest'] ) ? $row['price_custom_rest'] : '';
												?>
												<p class="mwm-section-20__price-custom">
													<?php if ( '' !== trim( (string) $c_strong ) ) : ?>
														<span class="mwm-section-20__price-custom-strong"><?php echo esc_html( $c_strong ); ?></span>
													<?php endif; ?>
													<?php if ( '' !== trim( (string) $c_rest ) ) : ?>
														<span class="mwm-section-20__price-custom-rest"><?php echo esc_html( $c_rest ); ?></span>
													<?php endif; ?>
												</p>
											<?php else : ?>
												<?php
												$p_strike  = isset( $row['price_strike'] ) ? $row['price_strike'] : '';
												$p_mlabel  = isset( $row['price_market_label'] ) ? $row['price_market_label'] : __( 'mercado', 'bilky' );
												$p_adv     = isset( $row['price_advisor'] ) ? $row['price_advisor'] : '';
												$p_alabel  = isset( $row['price_advisor_label'] ) ? $row['price_advisor_label'] : __( 'asesor', 'bilky' );
												?>
												<div class="mwm-section-20__price-comparison">
													<?php
													$has_price_left  = '' !== trim( (string) $p_strike ) || '' !== trim( (string) $p_mlabel );
													$has_price_right = '' !== trim( (string) $p_adv ) || '' !== trim( (string) $p_alabel );
													?>
													<?php if ( $has_price_left ) : ?>
														<div class="mwm-section-20__price-inline">
															<?php if ( '' !== trim( (string) $p_strike ) ) : ?>
																<span class="mwm-section-20__price-strike"><?php echo esc_html( $p_strike ); ?></span>
															<?php endif; ?>
															<?php if ( '' !== trim( (string) $p_mlabel ) ) : ?>
																<sup class="mwm-section-20__price-market-label"><?php echo esc_html( $p_mlabel ); ?></sup>
															<?php endif; ?>
														</div>
													<?php endif; ?>
													<?php if ( $has_price_left && $has_price_right ) : ?>
														<span class="mwm-section-20__price-sep" aria-hidden="true">/</span>
													<?php endif; ?>
													<?php if ( $has_price_right ) : ?>
														<div class="mwm-section-20__price-inline">
															<?php if ( '' !== trim( (string) $p_adv ) ) : ?>
																<span class="mwm-section-20__price-advisor"><?php echo esc_html( $p_adv ); ?></span>
															<?php endif; ?>
															<?php if ( '' !== trim( (string) $p_alabel ) ) : ?>
																<sup class="mwm-section-20__price-advisor-label"><?php echo esc_html( $p_alabel ); ?></sup>
															<?php endif; ?>
														</div>
													<?php endif; ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
