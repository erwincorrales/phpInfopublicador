var cambiotestimonio = 0;

var reloj= setInterval(testimonio,5000);

function testimonio(){
  cambiotestimonio++;
  $('.testimonios p').text("")
  switch(cambiotestimonio){
    case 1: $('.testimonios p').text('"Puedo consultar en cualquier momento con acceso a internet, me facilita el trabajo!"');
            break;
    case 2: $('.testimonios p').text('"Evito datos erroneos y puedo trabajar en equipo!"');
             break;
    case 3: $('.testimonios p').text('"Puedo ver el historial de los ultimos seis meses y filtrar faltantes al instante"');
  }
  if(cambiotestimonio == 3)
    cambiotestimonio = 0;

}

//  setTimeout(function(){
//       $('#alert_error').fadeOut(500);
//       $('#alert_ok').fadeOut(500);
//    }, 3000);
