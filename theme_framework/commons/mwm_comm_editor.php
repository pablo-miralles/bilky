<?php
/* En este archivo encontrarás funciones generales que te ayudarán en el desarrollo cuando tenemos que modificar el contenido de una página.

Indice:
- mwm_comm_split_cont() -> Imprime una variable usando var_dump correctamente formateado.
- mwm_comm_acf_no_autop() -> Elimina los <p> que añade ACF a los campos de texto.

*/


if ( !function_exists( 'mwm_comm_split_cont' ) ) {
    /**
	 * Function that shows all the content of a variable
	 */
    function mwm_comm_split_cont( $content ) {

        $contents = array();
        $parts = explode('<p>mwm-split-content</p>', $content);

        foreach ($parts as $key => $part) {
            $content = '';
            foreach(parse_blocks($part) as $block){
                $content .= $block['innerHTML'];
            }
            array_push($contents, $content);
        }

        return $contents; 
    }
}

// if ( !function_exists( 'mwm_comm_acf_no_autop' ) ) {

//     function mwm_comm_acf_no_autop() {
//         remove_filter('acf_the_content', 'wpautop' );
//     }
//     add_action('acf/init', 'mwm_comm_acf_no_autop');

// }
