/* global wp */
jQuery( function ( $ ) {
	var frame;
	var $input = $( '#bilky_ponente_image' );
	var $preview = $( '#bilky-ponente-image-preview' );
	var $remove = $( '#bilky-ponente-remove-image' );

	$( document ).on( 'click', '#bilky-ponente-upload-image', function ( e ) {
		e.preventDefault();
		if ( frame ) {
			frame.open();
			return;
		}
		frame = wp.media( {
			title: 'Seleccionar imagen',
			button: { text: 'Usar esta imagen' },
			multiple: false
		} );
		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			$input.val( attachment.id );
			$preview.html( '<img src="' + attachment.url + '" style="max-width:120px;height:auto;" alt="" />' );
			$remove.show();
		} );
		frame.open();
	} );

	$( document ).on( 'click', '#bilky-ponente-remove-image', function ( e ) {
		e.preventDefault();
		$input.val( '' );
		$preview.empty();
		$( this ).hide();
	} );
} );
