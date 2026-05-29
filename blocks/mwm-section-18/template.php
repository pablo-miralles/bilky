<?php
/**
 * Block Name: MWM Section 18 — Tabla comparativa con acordeón
 *
 * @package bilky
 */

if ( ! isset( $block ) ) {
	$block = array();
}

$show_header    = get_field( 'show_header' );
$header_feature = get_field( 'header_feature' );
$header_basic   = get_field( 'header_basic' );
$header_premium = get_field( 'header_premium' );
$header_third   = get_field( 'header_third' );
$sections       = get_field( 'sections' );

if ( false === $show_header ) {
	$show_header = false;
} else {
	$show_header = (bool) $show_header;
}

$header_feature = $header_feature ? $header_feature : __( 'Funcionalidades', 'bilky' );
$header_basic   = $header_basic ? $header_basic : __( 'Columna 1', 'bilky' );
$header_premium = $header_premium ? $header_premium : __( 'Columna 2', 'bilky' );
$header_third   = is_string( $header_third ) ? trim( $header_third ) : '';
$has_third_plan = '' !== $header_third;

$block_id = 'mwm-section-18';
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
				'section_title' => __( 'Facturación', 'bilky' ),
				'default_open'  => true,
				'rows'          => array(
					array(
						'feature_label'    => __( 'Businesses / Self Employed', 'bilky' ),
						'basic_included'   => true,
						'premium_included' => true,
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
<section class="mwm-section-18" id="<?php echo esc_attr( $block_id ); ?>">
	<div class="mwm-section-18__wrapper<?php echo $has_third_plan ? ' mwm-section-18__wrapper--has-third-plan' : ''; ?>">
		<?php if ( $show_header ) : ?>
			<div class="mwm-section-18__header-row is-style-b-100<?php echo $has_third_plan ? ' mwm-section-18__header-row--has-third-plan' : ''; ?>" role="row">
				<div class="mwm-section-18__header-cell mwm-section-18__header-cell--feature">
					<?php echo esc_html( $header_feature ); ?>
				</div>
				<div class="mwm-section-18__header-cell mwm-section-18__header-cell--plan">
					<?php echo esc_html( $header_basic ); ?>
				</div>
				<div class="mwm-section-18__header-cell mwm-section-18__header-cell--plan">
					<?php echo esc_html( $header_premium ); ?>
				</div>
				<?php if ( $has_third_plan ) : ?>
					<div class="mwm-section-18__header-cell mwm-section-18__header-cell--plan">
						<?php echo esc_html( $header_third ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="mwm-section-18__accordions">
			<?php
			foreach ( $sections as $index => $section ) :
				$section_title = isset( $section['section_title'] ) ? $section['section_title'] : '';
				if ( '' === trim( (string) $section_title ) ) {
					continue;
				}
				$default_open = ! empty( $section['default_open'] );
				$rows         = isset( $section['rows'] ) && is_array( $section['rows'] ) ? $section['rows'] : array();

				$panel_id  = $block_id . '-panel-' . (int) $index;
				$heading_id = $block_id . '-heading-' . (int) $index;
				?>
				<div class="mwm-section-18__accordion<?php echo $default_open ? ' is-open' : ''; ?>">
					<h3 class="mwm-section-18__accordion-heading">
						<button
							type="button"
							class="mwm-section-18__accordion-trigger"
							id="<?php echo esc_attr( $heading_id ); ?>"
							aria-expanded="<?php echo $default_open ? 'true' : 'false'; ?>"
							aria-controls="<?php echo esc_attr( $panel_id ); ?>"
						>
							<span class="mwm-section-18__accordion-title is-style-h-500">
								<?php echo esc_html( $section_title ); ?>
							</span>
							<span class="mwm-section-18__accordion-icon" aria-hidden="true">
								<span class="mwm-section-18__accordion-icon-plus"><i class="fa-solid fa-plus"></i></span>
								<span class="mwm-section-18__accordion-icon-minus"><i class="fa-solid fa-minus"></i></span>
							</span>
						</button>
					</h3>
					<div
						class="mwm-section-18__accordion-panel"
						id="<?php echo esc_attr( $panel_id ); ?>"
						role="region"
						aria-labelledby="<?php echo esc_attr( $heading_id ); ?>"
						aria-hidden="<?php echo $default_open ? 'false' : 'true'; ?>"
					>
						<div class="mwm-section-18__accordion-panel-inner">
						<?php if ( ! empty( $rows ) ) : ?>
							<div class="mwm-section-18__rows">
								<?php
								$row_index = 0;
								$rows_count = count( $rows );
								foreach ( $rows as $row ) :
									$label   = isset( $row['feature_label'] ) ? $row['feature_label'] : '';
									$basic   = isset( $row['basic_included'] ) ? ( $row['basic_included'] !== false ) : false;
									$premium = isset( $row['premium_included'] ) ? ( $row['premium_included'] !== false ) : false;
									$third   = isset( $row['third_included'] ) ? ( $row['third_included'] !== false ) : false;

									$row_pos_class = '';
									if ( 0 === $row_index ) {
										$row_pos_class = ' mwm-section-18__table-row--first';
									}
									if ( $row_index === $rows_count - 1 ) {
										$row_pos_class .= ' mwm-section-18__table-row--last';
									}
									++$row_index;
									?>
									<div class="mwm-section-18__table-row<?php echo esc_attr( $row_pos_class ); ?><?php echo $has_third_plan ? ' mwm-section-18__table-row--has-third-plan' : ''; ?>" role="row">
										<div class="mwm-section-18__cell mwm-section-18__cell--feature is-style-b-200">
											<?php echo esc_html( $label ); ?>
										</div>
										<div class="mwm-section-18__cell mwm-section-18__cell--plan mwm-section-18__cell--plan-basic">
											<span class="mwm-section-18__plan-label"><?php echo esc_html( $header_basic ); ?>:</span>
											<?php
											if ( $basic ) :
												?>
												<span class="mwm-section-18__check mwm-section-18__check--yes" title="<?php echo esc_attr__( 'Incluido', 'bilky' ); ?>">
													<i class="fa-solid fa-circle-check" aria-hidden="true"></i>
													<span class="screen-reader-text"><?php echo esc_html__( 'Incluido en Básico', 'bilky' ); ?></span>
												</span>
											<?php else : ?>
												<span class="mwm-section-18__check mwm-section-18__check--no" title="<?php echo esc_attr__( 'No incluido', 'bilky' ); ?>">
													<i class="fa-solid fa-circle-xmark" aria-hidden="true"></i>
													<span class="screen-reader-text"><?php echo esc_html__( 'No incluido en Básico', 'bilky' ); ?></span>
												</span>
											<?php endif; ?>
										</div>
										<div class="mwm-section-18__cell mwm-section-18__cell--plan mwm-section-18__cell--plan-premium">
											<span class="mwm-section-18__plan-label"><?php echo esc_html( $header_premium ); ?>:</span>
											<?php
											if ( $premium ) :
												?>
												<span class="mwm-section-18__check mwm-section-18__check--yes" title="<?php echo esc_attr__( 'Incluido', 'bilky' ); ?>">
													<i class="fa-solid fa-circle-check" aria-hidden="true"></i>
													<span class="screen-reader-text"><?php echo esc_html__( 'Incluido en Premium', 'bilky' ); ?></span>
												</span>
											<?php else : ?>
												<span class="mwm-section-18__check mwm-section-18__check--no" title="<?php echo esc_attr__( 'No incluido', 'bilky' ); ?>">
													<i class="fa-solid fa-circle-xmark" aria-hidden="true"></i>
													<span class="screen-reader-text"><?php echo esc_html__( 'No incluido en Premium', 'bilky' ); ?></span>
												</span>
											<?php endif; ?>
										</div>
										<?php if ( $has_third_plan ) : ?>
											<div class="mwm-section-18__cell mwm-section-18__cell--plan mwm-section-18__cell--plan-third">
												<span class="mwm-section-18__plan-label"><?php echo esc_html( $header_third ); ?>:</span>
												<?php
												if ( $third ) :
													?>
													<span class="mwm-section-18__check mwm-section-18__check--yes" title="<?php echo esc_attr__( 'Incluido', 'bilky' ); ?>">
														<i class="fa-solid fa-circle-check" aria-hidden="true"></i>
														<span class="screen-reader-text"><?php echo esc_html__( 'Incluido en columna 3', 'bilky' ); ?></span>
													</span>
												<?php else : ?>
													<span class="mwm-section-18__check mwm-section-18__check--no" title="<?php echo esc_attr__( 'No incluido', 'bilky' ); ?>">
														<i class="fa-solid fa-circle-xmark" aria-hidden="true"></i>
														<span class="screen-reader-text"><?php echo esc_html__( 'No incluido en columna 3', 'bilky' ); ?></span>
													</span>
												<?php endif; ?>
											</div>
										<?php endif; ?>
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
