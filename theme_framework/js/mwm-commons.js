/* 
---- COMMONS JS ----
En este archivo encontrarás funciones generales que te ayudarán a realizar diferentes tipos de tests en tu desarrollos

Indice:
- detectar_dispositivo() -> Detecta en que dispositivo se esta ejecutando la web y devuelve:
---- IOS -> Si es dispositivo Apple
---- TACTILE -> Si es un dispositivo Tactil
---- NORMAL -> Si es cualquier otro dispositivo

*/

jQuery(document).ready(function(){

    function detectar_dispositivo(){
        if ( /iPad|iPhone|iPod/.test(navigator.platform) ) {
            console.log('IOS');
      
        } else if (navigator.userAgent.match(/Mac/) && navigator.maxTouchPoints && navigator.maxTouchPoints > 2) {
          console.log('TACTILE');
      
        } else {
          console.log('NORMAL');
        }
    }
  
  });