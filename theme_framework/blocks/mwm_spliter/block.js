( function ( blocks, element ) {
    var el = element.createElement;
 
    blocks.registerBlockType( 'mwm-blocks/spliter', {
        edit: function () {
            return el( 'p', {}, 'Separador de contenido para temas de WordPress' );
        },
        save: function () {
            return el( 'p', {}, 'mwm-split-content' );
        },
    } );
} )( window.wp.blocks, window.wp.element );