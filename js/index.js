
//FUNCIONES DE AJUSTAR EL FOOTER DE ACUERDO A LA VENTANA

$(window).resize(function(){
    //  if ( $('body').height() > ( $(window).height() ) )
    //       $('footer').css({'position': 'relative'});
    //   else
    //       $('footer').css({'position': 'absolute', 'bottom': '0'});
    
         //reparar sidebar
     if( $('body').width() >= 620)
         $('#sidebar').removeClass('sidebar-deslizar')
});

//ocultar todos los div de funciones en listado
mostrarprincipal()

//CONTROLAR VENTANAS DE ACUERDO A SU FUNCION
function moverMenu(){
  if($('#sidebar').css('position') == "absolute"){
      $('#sidebar').toggleClass('sidebar-deslizar')
  }
}

function mostrarprincipal(){
    ocultarmenus()
    recargarlistado()
    $('#listado-principal').show()
}

function ocultarmenus(){
    $('#informe, #editar-publicador, #listado-principal, #exportar-datos, #usuario, #conf_user, #editar_user, .nuevogrupo, #estadisticas, #resumen-publicador').hide()
}

//botones de menu
$('#div-menu').find('input[name=btn1]').click(function(e){
    e.preventDefault()
    ocultarmenus()
    moverMenu()
    $('#listado-principal').show()
})

$('#div-menu').find('input[name=btn2]').click(function(e){
    e.preventDefault()
    ocultarmenus()
    moverMenu()
    
    //iniciaizar radiobuttons
    
    $('#editar-publicador form')[0].reset()
    $('#editar-publicador').find('span').eq(0).text('')
    $('#editar-publicador').find('span').eq(1).text('')
    $('#editar-publicador input').eq(9).val('')
    ordenarTipoPublicador($('#editar-publicador input[type=radio]'))
    $('#editar-publicador input[type=radio]').eq(0).attr('checked',true)
    
        
    $.ajax({
        url: 'nuevoPublicador.php',
        type: 'POST',
        dataType: 'json',
        success: function(data){
            cargarSelectGrupos(data.id, data.nombregrupo, $('#editar-publicador select')[0], data.grupo)
            
        }
    })
    
    $('#editar-publicador').show()
    $(window).resize()
})

$('#div-menu').find('input[name=btn3]').click(function(e){
    e.preventDefault()
    ocultarmenus()
    moverMenu()
    $.ajax({
        url: 'estadisticas.php',
        type: 'post',
        dataType: 'html',
        success: function(data){
            $('#estadisticas').children('div#contenedor').html(data)
            $('#estadisticas').show()
        }
    })
    
})

$('#div-menu').find('input[name=btn4]').click(function(e){
    e.preventDefault()
    ocultarmenus()
    moverMenu()
    
    $.ajax({
        url: 'datos.php',
        type: 'POST',
        success: function(data){
            $('#iframe-datos').html(data);
        }
    })
    $('#exportar-datos').show()
})

$('.menu a').eq(0).click(function(e){
    e.preventDefault()
    ocultarmenus()
    moverMenu()
    
    $.ajax({
        url: 'config.php',
        type:'POST',
        dataType: 'JSON',
        success: function(usuario){
            $('#usuario input').eq(0).val(usuario.user)
            $('#usuario input').eq(1).val(usuario.pass)
            $('#usuario input').eq(2).val(usuario.pass)
            $('#usuario input').eq(3).val(usuario.correo)
            $('#usuario input').eq(4).val(usuario.nombre)
            $('#usuario input').eq(5).val(usuario.num)
            
        }
    })
    
    $('#usuario').show()
    
})

$('.menu a').eq(1).click(function(e){
    e.preventDefault()
    ocultarmenus()
    moverMenu()
    //mostrar eventos usuarios
    mostrarGruposYUsuarios()
})
    

function mostrarGruposYUsuarios(){
    $.ajax({
        url: 'conf_usuarios.php',
        type: 'post',
        success: function(data){
            $('#conf_user > div').html(data)
            $('#conf_user').show()
            $(window).resize()
            
            //EVENTOS DE CONFIG GRUPOS - USUARIOS-------------------

            //AGREGAR EVENTO PARA CARGAR EN FORMULARIO GRUPO A MODIFICA O BORRAR
            $('#conf_user table').eq(0).find('a').click(function(e){
                e.preventDefault()
                $('.nuevogrupo input[type="hidden"]').val( $(this).attr("href"))
                $('.nuevogrupo input[type="text"]').val($(this).parent().siblings().eq(0).html())
                ocultarmenus()
                $('.nuevogrupo').show()
            })
            
            //evento boton para activar form nuevo usuario
            $('#conf_user input[name="nuevogrupobtn"]').click(e=>{
                e.preventDefault()
                $('.nuevogrupo input[type="hidden"]').val("")
                $('.nuevogrupo input[type="text"]').val("")
                ocultarmenus()
                $('.nuevogrupo').show()
            })    

            //agregar listeners para tabla usuarios al ser cargada
            $('#conf_user select#selectgrupo').on('change',function(){
                $.ajax({
                    url:'cambiousuariogrupo.php',
                    type: 'post',
                    data: { grupo: $(this).val(),
                            user : $(this).parent().next().find('a').attr('href')
                          },
                    success: function(){
                        alert('Cambio en grupo Realizado!')
                        mostrarGruposYUsuarios()
                    }
                })
            })
            //listener click  editar cargar nombre usuario a formulario
            $('#conf_user table').eq(1).find('a').click(function(e){
                e.preventDefault()
                ocultarmenus()
                
                $.ajax({
                    url: 'usuarionuevo.php',
                    type: 'post',
                    dataType: 'json',
                    data: {num: $(this).attr('href')},
                    success: function(usuario){
                        $('#formNuevoUser input').eq(0).val(usuario.user)
                        $('#formNuevoUser input').eq(1).val(usuario.pass)
                        $('#formNuevoUser input').eq(2).val(usuario.pass)
                        $('#formNuevoUser input').eq(3).val(usuario.correo)
                        $('#formNuevoUser input').eq(4).val(usuario.nombre)
                        $('#formNuevoUser input').eq(5).val(usuario.num)
                        $('#formNuevoUser select').eq(0).val(usuario.tipo)
                        ocultarmenus()
                        $('#formNuevoUser .borrar').attr('disabled',false)
                        $('#editar_user').show()
                        
                        
                    }
                })
                
            })
            //evento para boton de crear nuevo usuario
            $('#conf_user input').eq(1).click(e=>{
                e.preventDefault()
                $('#formNuevoUser')[0].reset()
                $('#formNuevoUser .borrar').attr('disabled',true)
                $('#conf_user').hide()
                $('#editar_user').show()
                
            })
        }
    }) 
}

//Al hace submit en formulario nuevo usuario
$('.nuevogrupo form').submit(e=>{
    e.preventDefault()
    $.ajax({
        url:'creargrupo.php', 
        type:'POST',
        data: $('.nuevogrupo form').serialize(),
        success: function(data){
            ocultarmenus()
            mostrarGruposYUsuarios()
            $('#conf_user span').html(data)
            alert_ocultardiv()

        }
    })
})

//evento eliminar grupo
$('.nuevogrupo input[type="button"]').eq(0).click(e=>{
    e.preventDefault()
    $.ajax({
        url: 'borrargrupo.php', type: 'POST', data: $('.nuevogrupo form').serialize(),
        success: function(data){
            recargarlistado()
            ocultarmenus()
            mostrarGruposYUsuarios()
            $('#conf_user span').html(data)
            alert_ocultardiv()
            

        }
    })
})

$('.nuevogrupo input[type="button"]').eq(1).click(e=>{
    e.preventDefault()
    $('.nuevogrupo').hide()
    $('#conf_user').show()
})


//EVENTOS DE BOTON PARA FORMULRAIO DE MODIFICAR O CREAR NUEVO USUARIO
$('#formNuevoUser .borrar').click(e=>{
    var e = confirm('Desea Eliminar de Siervo De Grupo al Publicador?')
    if (e){
        $.ajax({
            url:'borrarusuario.php', type:'POST',
            data: {num: $('#formNuevoUser input[name="num"]').val()},
            success: data=>{
                 ocultarmenus()
                 mostrarGruposYUsuarios()
                 $('#conf_user span').html(data)
                 alert_ocultardiv()
        }
    })    
    }
})


$('#editar_user input[type="button"]').eq(1).click(e=>{
    $('#editar_user').hide()
    $('#conf_user').show()
})

$('#formNuevoUser').submit( (e)=>{
    e.preventDefault()
    $.ajax({
        url: 'usuarionuevo1.php',
        type:'POST',
        data: $('#formNuevoUser').serialize(),
        success: (resp)=>{
            if(resp == 'OK'){
                $.ajax({
                    url: 'olvidoclave1.php', type:'POST', data: {correo: $('#formNuevoUser input[name="correo"]').val()},
                    success: (exito)=>{
                        if(exito ="OK")
                            $('#conf_user span').html('<div id="alert_ok">Notificacion enviada a usuario</div>')
                        else
                            $('#conf_user span').html('<div id="alert_error">Error al enviar Notificacion al Correo!</div>')     
                        
                        $('#editar_user').hide()
                        alert_ocultardiv()
                    }
                })
                ocultarmenus()
                mostrarGruposYUsuarios()
                $(window).resize()
            }
        }
    })
})




$('.icon-menu, #conf_user label').click(function(e){
    e.preventDefault()
    moverMenu()
})

function cargarSelectGrupos(valor, grupos, select, grupo){
    $(select).find('option').remove()
    for(var i = 0; i < valor.length; i++){
        var option = document.createElement('option')
        option.text = grupos[i]
        option.value = valor[i]
        select.add(option)    
    }
    if(grupo)
        select.value = grupo
}

function ordenarTipoPublicador(radio){
    for (var i = 0; i <radio.length; i++ ){
        $(radio).eq(i).attr('checked',false)
    }
}


$('.button_back').click(function(e){
    e.preventDefault()
    $('#sidebar').toggleClass('sidebar-deslizar')
})

//AL SUBIR INFORME AL SERVIDOR
$('#informe form').eq(0).submit(function(e){
    e.preventDefault()
    if($('#informe').find('input[name=horas]').val() != "" || $('#informe').find('input[name=minutos]').val() != ""){
        $.ajax({
            url: 'informeguardar.php',
            dataType: 'html',
            type: 'post',
            cache: false,
            data: $(this).serialize(),
            success: function(data){
                mostrarprincipal()
                $(data).insertBefore($('#div-listado-pub'))
                alert_ocultardiv()
            }
        })
    }
})


$('#editar-publicador form').eq(0).submit(function(e){
  e.preventDefault()
  $.ajax({
      url: 'nuevopublicador1.php',
      type: 'POST',
      data: $(this).serialize(),
      success: function(data){
          $(data).insertBefore($('#div-listado-pub'))
          mostrarprincipal()
          alert_ocultardiv()
      }
      
  })    
})

function ordenar(){
    //funcion encargada de filtrar los publicadores por faltantes y grupos    
    var listapub = $('#div-listado-pub').find('tr'); 
    var entregados = 0;
    var total = 0;
    var grupo = $('#select-grupos').val()
    $(listapub).show()   
   
    listapub.each(function(){        
        //filtrar por grupo
        if(grupo != 'FFFFF'){
          if( $(this).find('td').eq(1).text() == grupo ){
              total++
              //ocultar o mostrar faltantes         
              if($(this).hasClass('selected')){
                 entregados++
                 if ($('#faltantes').prop('checked')){
                   $(this).hide();   
                 }
              }     
          }
          else{
              $(this).hide()
          }
        }
        else{
            total++
             if($(this).hasClass('selected')){    
                 entregados++
                 if ($('#faltantes').prop('checked'))
                   $(this).hide(); 
             }
        }          
    })
    //actualizar conteo
    $('.inicial').find('td').eq(1).text(entregados)   
    $('.inicial').find('td').eq(3).text(total-entregados)   
   } 

function recargarlistado(){
   //funcion que refresca lista y cuenta cuantos faltan
    $.ajax({
        url: 'listado1.php',
        type: 'POST',
        success: function(data){
           $('#div-listado-pub').html(data); 
        }
    }).done(function(){
        //FUNCIONES AL HACER CLICK AL CONSULTAR INFORME*********************
         //AL AHACER CLICK EN a EDITAR PUBLICADOR
        

         function edad(fecha){
            if (!fecha) return ''
            var hoy = new Date();
            var fechaInicio = new Date(fecha)
            return `${((hoy-fechaInicio)/(3600000*24*365)).toFixed(1)} años`
        }

        $('#editar-publicador').find('input').eq(4).blur(function(e){
            e.preventDefault()
            $('#editar-publicador').find('span').eq(0).text(edad($(this).val()))
        })

        $('#editar-publicador').find('input').eq(5).blur(function(e){
            e.preventDefault()
            $('#editar-publicador').find('span').eq(1).text(edad($(this).val()))
        })


        //AL HACER CLICK EN AGREGAR EDITAR INFORME
        $('#div-listado-pub').find('a[cat=i]').click(function(e){
            e.preventDefault()
            ocultarmenus()

            //cargar informe de usuario si existe en formulario
            $.ajax({
            url: 'informe.php',
            dataType: 'json',
            cache: false,    
            type: 'post',
            data: {'num' : $(e.target).attr('href')},
            success: function(resp){  
                $('#informe').find('h3').text(resp.nombre)   
                $('#informe').find('input').eq(0).val(resp.pub)   
                $('#informe').find('input').eq(1).val(resp.video)   
                $('#informe').find('input').eq(2).val(resp.horas)   
                $('#informe').find('input').eq(3).val(resp.min)
                $('#informe').find('input').eq(4).val(resp.creditHoras)
                $('#informe').find('input').eq(5).val(resp.revisita)   
                $('#informe').find('input').eq(6).val(resp.estudio)   
                $('#informe').find('input').eq(7).val(resp.comentario)   
                $('#informe').find('input').eq(8).val(resp.fecha)   
                $('#informe').find('input').eq(9).val(resp.info)   
                $('#informe').find('input').eq(10).val(resp.tipo)   
                $('#informe').find('input').eq(11).val(resp.num)
                
                if(resp.tipo == 'R')
                    $('#creditoHoras').removeClass('oculto')
                else
                    $('#creditoHoras').addClass('oculto')
            }
            })
            $('#informe').show()
        })

        //AL HACER CLICK EN RESUMEN DE PUBLICADOR
        $('#div-listado-pub').find('a[cat=e]').click(function(e){
            e.preventDefault()
            $.ajax({
                url: 'estadistica-publicador.php',
                type: 'post',
                data: {'num' : $(e.target).attr('href')},
                success: function(data){
                    $('#resumen-anio').html(data)
                    $('#resumen-publicador').show()
                }
            })
            $.ajax({
                url: 'nuevoPublicador.php',
                type: 'post',  
                dataType: 'json',
                data: {'num' : $(e.target).attr('href')},
                success: function(resp){
                    $('#resumen-publicador').find('h3').eq(0).text(resp.nombre)
                    $('#resumen-publicador').find('td').eq(1).text(resp.direccion)   
                    $('#resumen-publicador').find('td').eq(3).text(resp.tel)   
                    $('#resumen-publicador').find('td').eq(5).text(resp.correo)
                    $('#resumen-publicador').find('span').eq(0).text(resp.fNacimiento)
                    $('#resumen-publicador').find('span').eq(2).text(resp.fBautismo)
    
                    //calculo años
                    $('#resumen-publicador').find('span').eq(1).text(edad(resp.fNacimiento))
                    $('#resumen-publicador').find('span').eq(3).text(edad(resp.fBautismo))

                    $('#resumen-publicador').find('button').eq(0).click(function(ef){
                        ef.preventDefault()
                        ocultarmenus()
                        //cargar datos de usuario si existe en formulario
                        $.ajax({
                        url: 'nuevoPublicador.php',
                        type: 'post',  
                        dataType: 'json',
                        data: {'num' : $(e.target).attr('href')},
                        success: function(resp){

                            $('#editar-publicador').find('input').eq(0).val(resp.nombre)
                            $('#editar-publicador').find('input').eq(1).val(resp.tel)   
                            $('#editar-publicador').find('input').eq(2).val(resp.direccion)   
                            $('#editar-publicador').find('input').eq(3).val(resp.correo)
                            $('#editar-publicador').find('input').eq(4).val(resp.fNacimiento)
                            $('#editar-publicador').find('input').eq(5).val(resp.fBautismo)

                            //calculo años
                            $('#editar-publicador').find('span').eq(0).text(edad(resp.fNacimiento))
                            $('#editar-publicador').find('span').eq(1).text(edad(resp.fBautismo))

                            //asegurar no edicion en cuenta distinta a admin  
                            if(resp.admin == 0)
                            $('#editar-publicador').find('input').eq(0).attr('readonly',true)
                            else
                                $('#editar-publicador').find('input').eq(0).attr('readonly',false)
                            

                            ordenarTipoPublicador($('#editar-publicador input[type=radio]'))  

                            if(resp.tipo == '') 
                                $('#editar-publicador').find('input[type=radio]').eq(0).attr('checked',true)
                            else if(resp.tipo =='A')
                                $('#editar-publicador').find('input[type=radio]').eq(1).attr('checked',true)
                            else if(resp.tipo =='R')
                                $('#editar-publicador').find('input[type=radio]').eq(2).attr('checked',true)

                            cargarSelectGrupos(resp.id, resp.nombregrupo, $('#editar-publicador select')[0], resp.grupo)   

                            $('#editar-publicador').find('input').eq(9).val(resp.num)  

                        }
                        })  
                        $('#editar-publicador').show();
                    })
                    $('#resumen-publicador').find('button').eq(1).click(function(ef){
                        $('#resumen-publicador').hide()
                    })

                
                }
            })

        })   
        ordenar()  
    })
}


//funcion ordenar los publicadores por apellido
$('#label-az').click(function(){
    var lista = $('#lista tbody').find('tr').toArray();
    var estado = $('input[type="checkbox"]').eq(1).checked;

    for(var i = 1; i< lista.length; i++){
        var v2 = $(lista[i-1]).find('td')[0].innerText;
        var v1 = $(lista[i]).find('td')[0].innerText;
        burbuja(v1,v2,estado,i);
    }

    lista.map(el => $('#lista tbody').append(el));
});

function burbuja(v1,v2,apellido,idx){
    if(!apellido){
        if(v2.split(' ')[1] > v1.split('')[1]){
            var k = lista[idx];
            lista[idx-1] = lista[idx];
            lista[idx] = k;
        }
    }else{
        if(v2 > v1){
            var k = lista[idx];
            lista[idx-1] = lista[idx];
            lista[idx] = k;
        }
    }
}

   

    //SCRIPT BUSQUEDA *******************************************
   $('.busca').click(function(){
      $('#buscar').toggle(600);
      $('#buscar').val("");
      $('#buscar').focus();
   });

  $('#buscar').keyup(function(){
     var busqueda =  $('#buscar').val();
     //RECORRER TODOS LAS FILAS DE TABLA
     var listapub = $('#div-listado-pub').contents().find('tr');   
     listapub.each(function(){
        var data1 = $(this).find('td').eq(0).text();
        data1 = data1.toLowerCase();
        busqueda = busqueda.toLowerCase();

        if (data1.indexOf(busqueda) > -1 )
               $(this).show();
        else
               $(this).hide();

        $('#resultado').text(busqueda);
        $('#faltantes').prop('checked', false)     
     });
 });
    

  $('#faltantes, #select-grupos').change(function(){
     ordenar()
  }) 
//-------------------------------------------------------------------    

function saveborrado(x){
      var e = confirm('Si tiene que cambiar algo del usuario es mejor editar que borrarlos. Es posible que al borrarlo ELIMINE TODO SU HISTORIAL de predicacion. Estas seguro de borrar Publicador y eliminar todos sus registros?');
      if (e==true)
        $.ajax({
            url: 'borrarpub.php',
            type: 'post',
            data: {'num': x},
            success: function(data){
                $(data).insertBefore($('#div-listado-pub'))
                alert_ocultardiv()
                recargarlistado()
            }
        }) 
}


//EVENTOS DE FORMULARIO USUARIO
$('#usuario #user').blur(function(e){
    e.preventDefault()
      var consulta = $(this).val();

     $.ajax({
        url: 'comprobaruser.php',
        method: 'POST',
        data: {user : consulta},
        dataType: 'JSON', 
        success: function(data){
            $(data.info).insertAfter($('#usuario #user'))
            if (!data.op)
                $('#usuario #user').focus()
            actpost(data.op)
            alert_ocultardiv()
        }  
            
      })
   });

$('#usuario #mail').blur(function(){
    e.preventDefault() 
    var consulta = $('#mail').val();
    
      $.ajax({
            url: 'comprobaruser.php',
            method: 'POST',
            data: { mail : consulta},
            dataType: 'json',
            success: function(data){
                $(data.info).insertAfter($('#usuario #mail'))
                if (!data.op)
                $('#usuario #mail').focus()
                actpost(data.op)
                alert_ocultardiv()
            }  
      })
   })


//
function actpost(valor){
         $('#submitbutton').attr('disabled', !valor);
   }

$('#usuario form').submit(function(e){
       e.preventDefault()
       var ok = true
       var usuario = document.getElementById("user").value;
       var correo = document.getElementById("mail").value;
       var p1 = document.getElementById("p1").value;
       var p2 = document.getElementById("p2").value;

         if (usuario == null || usuario.length == 0) {
          alert('No puede el usuario estar en blanco');
          $('#usuario #user').focus() 
          ok=false     
          actpost(false);
       }

       if (correo == null || correo.length == 0) {
          alert('No puede el correo estar en blanco');
          $('#usuario #correo').focus()
          ok=false   
       }

       if (p1 != p2) {
            alert("Las contraseñas no coinciden");
            actpost(false);
            ok=false
            $('#usuario #p1').focus()
        }

        if (p1 == "" && p2 == "") {
            alert("Los campos de contraseña NO pueden estar vacios!");
            $('#usuario #p1').focus()
            ok=false
            actpost(false);
        }
       
        if (ok)
            $.ajax({
                url: 'config1.php',
                type: 'post',
                data: $('#usuario form').serialize(),
                success: function(data){
                   $(data).insertAfter($('#usuario h5'))
                    alert_ocultardiv()
                }
            })
   })


//FUNCIONES PARA VALIDAR FORMULARIO USUARIOS EN CONF_USERS 

function verificarContrasena(p1, p2){
    var clave1 = $(p1).val()
    var clave2 = $(p2).val()
    
    if (clave1.length < 8){
       alert('ERROR: La contraseña debe tener mínimo 8 caracteres!')
       return false;
       $(p1).focus()    
    }
    else if(clave1 == ""){
        alert("ERROR: No puede tener contraseña vacias")
        $(p1).focus()
        return false
    }
    
    else if(clave2 == ""){
        alert("ERROR: No puede tener contraseña vacias")
        $(p2).focus()
        return false
    }
    
    else if (clave1 != clave2){
        alert("ERROR: Las contraseñas no coinciden")
        $(p1).focus()
        return false
    }
    
    else{
        var exp =[/[0-9]/,/[a-z]/,/[A-Z]/,/^\S+$/]
       var conteo = null;
        for(var i=0;i<exp.length;i++){
            var m = (exp[i].test(clave1))
            if(m)
                conteo++
        }
        if(conteo != 4 ){
            alert("Contraseña debe contener minusculas, mayusculas, numeros y no tener espacios. Opcional símbolos")
            p1.focus()
            return false
        }
        else
            return true
    }    
}

function comprobarUserName(p1){
    var user = $(p1).val()
    var retorno
    if (user == ""){
        alert('Debe especificar un usuario')
        return false
    }else{
        let promise = checkUser(user)
        promise.then(data=> retorno = data.op)
        return retorno 
    }
      
            
}

function checkUser(user){
    return new Promise((resolve, reject)=>{
        $.ajax({
            url: 'comprobaruser.php', method: 'POST',
            data: {user : user},
            dataType: 'JSON',
            success: function(data){
                   resolve(data)
                },
            error: function(err){
                    reject(err)
                }
            
          })
    }) 
}


function comprobarCorreo(p1){
    var correo = $(p1).val()
    var retorno = true
    exp=/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i
    if(!exp.test(correo)){
        alert('ERROR: no es un correo válido')
        return false
    }
    else{
        $.ajax({
            async: false,
            url: 'comprobaruser.php',
            method: 'POST',
            data: { mail : correo},
            dataType: 'json',
            success: function(data){
                retorno = data.op
                if (!data.op){
                    $(p1).focus()
                    alert(data.info)
                }
            }  
      })
      return retorno    
    }
}

//EVENTOS DE PAGINAS ACTIVAS EN SESSION******************************----
// function retardo_pagina() {
//	setTimeout( function() {document.location = "login.php"; }, 5000);
// }

// ocultar alertas

$('#ajaxCarga').hide()
$(document).ajaxStart(function(){   
                      $('#ajaxCarga').show();
               })
               .ajaxStop(function(){
                      $('#ajaxCarga').hide()    
})     



function alert_ocultardiv(){
  setTimeout(function(){
  $('#alert_error, #alert_ok' ).fadeOut(1000).remove()
   }, 3000);
}

// funciones para botones de estadisticas grupo y publicador
function mostrargrupo() {
    ocultarestadisticas()
    document.getElementById('grupo').style.display = "block";
	$(window).resize()
}

function mostrarpublicador() {
    ocultarestadisticas()
	$.ajax({
        url: 'publicadoresSelect.php',
        type: 'post',
        dataType: 'json',
        success: function(data){
            $('#estadisticas #pubselect').empty()
            $('#estadisticas #pubselect').append('<option value="0">Escoge Publicador</option')
            $.each(data, function(value,id){
                  $('#estadisticas #pubselect').append('<option value="'+id[1]+'">'+id[0]+'</option')    
            })
               
        }
    })
    
    $('#estadisticas #pubselect').change(function(e){
        e.preventDefault()
        $.ajax({
            url: 'estadistica-publicador.php',
            type: 'post',
            data: {num: $(this).val()},
            success: function(data){
                $('#estadisticas #pubselect').next().html(data)
            }
        })
    })
       
	document.getElementById('publicador').style.display = "block";
    $(window).resize()
}

function mostrardatosjw() {
    ocultarestadisticas()
    document.getElementById('datosjw').style.display = "block";
    $(window).resize()
}

function mostrarprecursores() {
    ocultarestadisticas()
    document.getElementById('precursores').style.display = "block";
    $(window).resize()
}

function ocultarestadisticas(){
    document.getElementById('grupo').style.display = "none";
     document.getElementById('publicador').style.display = "none";
    document.getElementById('datosjw').style.display = "none";
    document.getElementById('precursores').style.display = "none";
 }

// funcion de cierre de session al no utilizar la pagina en 5 minutos.
function killSession() {
	   setTimeout(function(){document.location = "logout.php"}, 500000)
    }
