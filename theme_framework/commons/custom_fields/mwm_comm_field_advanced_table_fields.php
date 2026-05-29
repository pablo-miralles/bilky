<?php

// Security
if (!defined('ABSPATH')) exit;

if ( ! function_exists( 'mwm_advanced_table_fields' ) ) {
	function mwm_advanced_table_fields( $args ) {

		global $post;

		$defaults = array(
			'post_id'	=> $post->ID,
			'id' 		=> '',
			'name' 		=> '',
			'class' 	=> '',
			'label' 	=> '',
			'fields' 	=> array(),
			'new_row' 	=> __( 'Nueva fila', 'bilky' ),
			'remove_row' => __( 'Eliminar', 'bilky' ),
		);

		$args = wp_parse_args( $args, $defaults );

		if ( empty( $args['fields'] ) ) return __( 'Inserta campos en el parámetro "fields"', bilky );

		$metas = array();
		foreach ( $args['fields'] as $field_key => $field ) {
			$metas[ $field['name'] ] = get_post_meta( $args['post_id'], $field['name'], true ) ? get_post_meta( $args['post_id'], $field['name'], true ) : array();
		}

		echop( $args );
		echop( $metas );

		ob_start();

		?>
			<style>
				.mwm_metabox__table__remove_row {
					width: 100%;
					background-color: #b32d2e !important;
					border-color: #741718 !important;
					transition: all 200ms ease-in-out;
					color: #fff !important;
				}
				.mwm_metabox__table__remove_row:hover {
					background-color: #921d1f !important;
				}

				.mwm_metabox__table {
					width: 85%;
					border-collapse: collapse;
				}

				.mwm_metabox__table tfoot button {
					margin-top: 20px;
					transition: all 200ms ease-in-out;
				}

				.mwm_metabox__table td,
				.mwm_metabox__table th {
					border: 1px solid #bbb;
					padding: 8px;
				}

				.mwm_metabox__table thead th {
					text-align: center;
				}

				.mwm_metabox__table tr {
					transition: background-color 200ms ease-in-out;
				}
				.mwm_metabox__table tbody tr:last-of-type {
					display: none;
				}
				.mwm_metabox__table tbody tr:hover {
					background-color: #efefef;
				}

				.mwm_metabox__table th {
					padding-top: 12px;
					padding-bottom: 12px;
					background-color: #dfdfdf;
					color: #3c434a;
					font-size: 13px;
				}

				.mwm_metabox__table tfoot th {
					text-align: left;
				}

				.mwm_metabox__table tbody tr td:first-child,
				.mwm_metabox__table tbody tr td:last-child {
					width: 30px;
					font-weight: bold;
				}

				/* Campo selector avanzado ajax */
				.mwm_metabox__table__custom_selector {
					position: relative;
				}

				.mwm_metabox__table__index {
					text-align: center;
					margin: 0;
				}

				.mwm_metabox__table__model {
					width: 70px;
				}

				.mwm_metabox__table__bord {
					width: 50px;
					text-align: center;
				}

				.mwm_metabox__table__col2 {
					width: 80px;
					text-align: center;
				}

				.mwm_metabox__table__select {
					width: 100% !important;
				}

				.mwm_metabox__table__custom_selector__input {
					width: 100% !important;
				}

				.mwm_metabox__table__custom_selector__list {
					position: absolute;
					z-index: 999;
					width: 100%;
					background-color: white;
					margin: 0;
					max-height: 200px;
					overflow-y: auto;
				}

				.mwm_metabox__table__custom_selector__list li {
					padding: 5px 15px;
					border: 1px solid #cdcdcd;
					margin: 0;
					cursor: pointer;
				}
				.mwm_metabox__table__custom_selector__list li:not(:last-child) {
					border-bottom: 0px;
				}
				.mwm_metabox__table__custom_selector__list li:hover {
					background-color: #efefef;
				}
			</style>
			<div class="mwm_metabox__field">
				<label for="<?php echo $args['id']; ?>"><?php echo $args['label']; ?></label>
				<table id="<?php echo $args['id']; ?>" class="mwm_metabox__table">
					<thead>
						<tr>
							<th>#</th>
							<?php foreach ( $args['fields'] as $field ) : ?>
								<th><?php echo $field['label']; ?></th>
							<?php endforeach; ?>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( reset( $metas ) as $key => $v ) : ?>
							<tr>
								<td class="mwm_metabox__table__index"><?php echo $key; ?></td>
								<?php foreach ( $args['fields'] as $field_key => $field ) : ?>
									<td>
										<?php
										if ( ! empty( $field['default'] ) ) :
											$value = ! empty( $metas[ $field['name'] ][ $key ] ) ? $metas[ $field['name'] ][ $key ] : $field['default'];
										else :
											$value = ! empty( $metas[ $field['name'] ][ $key ] ) ? $metas[ $field['name'] ][ $key ] : '';
										endif;
										?>

										<?php 
										switch ( $field['type'] ) {
											case 'image' : break;
											case 'textarea' : 
												?>
													<textarea name="<?php echo $field['name'] . '[' . $key . ']'; ?>"><?php echo $value; ?></textarea>
												<?php
												break;
										
											default :
												?>
													<input type="<?php echo $field['type']; ?>" name="<?php echo $field['name'] . '[' . $key . ']'; ?>" value="<?php echo $value; ?>"/>
												<?php
										}
										?>
									</td>
								<?php endforeach; ?>
								<td><a href="javascript:void(0);" class="button mwm_metabox__table__remove_row"><?php echo $args['remove_row']; ?></a></td>
							</tr>
						<?php endforeach; ?>
						<tr>
							<td class="mwm_metabox__table__index">9999</td>
							<?php foreach ( $args['fields'] as $field_key => $field ) : ?>
								<td>
									<?php
									$value = ! empty( $field['default'] ) ? $field['default'] : '';

									switch ( $field['type'] ) {
										case 'image' : break;

										case 'textarea':
											?>
												<textarea name="<?php echo $field['name'] . '[9999]'; ?>"><?php echo $value; ?></textarea>
											<?php
											break;
									
										default :
											?>
												<input type="<?php echo $field['type']; ?>" name="<?php echo $field['name'] . '[9999]'; ?>" value="<?php echo $value; ?>"/>
											<?php
									}
									?>
								</td>
							<?php endforeach; ?>
							<td><a href="javascript:void(0);" class="button mwm_metabox__table__remove_row"><?php echo $args['remove_row']; ?></a></td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="<?php echo ( count( array_keys( $args['fields'] ) ) + 2 ); ?>">
								<button type="button" class="button button-primary mwm_metabox__table__add_row"><?php echo $args['new_row']; ?></button>
							</th>
						</tr>
					</tfoot>
				</table>

				<script async defer>
					jQuery(window).load(function() {
						var table_id = '<?php echo $args['id']; ?>';
						var table_selector = '#' + table_id;
						var table = jQuery( table_selector );
						var row_template = '<tr>' + jQuery( table_selector + ' tbody tr' ).last().html() + '</tr>';

						console.log( table_id );
						console.log( table_selector );
						console.log( table );
						console.log( row_template );

						regenerate_row_ids();

						jQuery( document.body ).on( 'click', table_selector + ' .mwm_metabox__table__add_row', function() {
							console.log( table );
							table.find( 'tbody' ).append( row_template );
							regenerate_row_ids();
						});

						jQuery( document.body ).on( 'click', table_selector + ' .mwm_metabox__table__remove_row', function() {
							if ( confirm('<?php _e( '¿Quieres borrar la fila?', bilky ); ?>') ) {
								var row = jQuery( this ).closest( 'tr' );
								row.remove();
								regenerate_row_ids();
							}
						});

						function regenerate_row_ids() {
							var row_count = 0;
							var tbody = table.find( 'tbody' );

							tbody.find( 'tr' ).each( function() {
								var row = jQuery( this );

								<?php foreach ( $args['fields'] as $field_key => $field ) : ?>
									row.find( '.input' ).eq( <?php echo $field_key + 1; ?> ).attr( 'name', '<?php echo $field['name']; ?>[' + row_count + ']' );
								<?php endforeach; ?>

								row.find( '.mwm_metabox__table__index' ).text( '#' + ( row_count + 1 ) );
								row_count++;
							});

							tbody.sortable();
							tbody.on( 'sortupdate', function( e, ui ) {
								regenerate_row_ids();
							});
						}
					});
				</script>
			</div>
		<?php
		$html = ob_get_clean();
		return $html;

		add_action( 'save_post', function( $args ) {
			foreach ( $args['fields'] as $field ) {
				switch ( $field['type'] ) {
					case 'checkbox':
						if ( ! empty( $_POST[ $field['name'] ] ) ) {
							update_post_meta( $args['post_id'], $field['name'], '1' );
						} else {
							delete_post_meta( $args['post_id'], $field['name'] );
						}
						break;
					default:
						if ( ! empty( $_POST[ $field['name'] ] ) ) {
							update_post_meta( $args['post_id'], $field['name'], $_POST[ $field['name'] ] );
						}
				}
			}
		});
	}
}
