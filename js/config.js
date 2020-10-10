// funcion para acomodar footer

$(function(){
    if ( $('body').height() > ($(window).height() ) )
          $('footer').css({'position': 'relative'});
      else
          $('footer').css({'position': 'absolute', 'bottom': '0'});
});

$(window).resize(function(){

     if ( $('body').height() > ( $(window).height() ) )
          $('footer').css({'position': 'relative'});
      else
          $('footer').css({'position': 'absolute', 'bottom': '0'});
});


//funcion de formaulario cambiar credenciales de acceso




// funcion de cerrado de pagina logout
function retardo_pagina() {
	setTimeout( function() {document.location = "login.php"; }, 5000);
}

// funcion de cierre de session al no utilizar la pagina en 5 minutos.
function killSession() {
	setTimeout( function() {document.location = "logout.php"; }, 500000);
}

// ocultar alertas
function alert_ocultardiv(){
    setTimeout(function(){
      $('#alert_error').fadeOut(500);
      $('#alert_ok').fadeOut(500);
   }, 3000);
}
