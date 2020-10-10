<?php
session_start();
if(isset($_SESSION['user']))
    header('location:menu.php')
?>

<!doctype html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <title>InformesPublicador Programa informes de congregacion</title>
   <link rel="stylesheet" href="css/estilo.css">
   <link rel="stylesheet" type="text/css" href="css/fontello.css">
   <link rel="icon" type="image/png" href="images/favicon.png" />
   <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>

<body>
<h3> INFORME PUBLICADOR </h3>
<div id = "contenedor-index">
    <div><img id="imagenlogin" src="images/banner.jpg"></div>
    <div id="index">
		  <form id="login-form" autocomplete="off" name="form1">
				<h3> LOGIN </h3>
				<input name="usuario" type="text" placeholder="Escribe Usuario o Email"
				required maxlength="40">
				<input name="password" type="password" placeholder="Contraseña"
				required maxlength="30">
				<input name="submit" type="submit" value="Ingresar">
				<div id="mensaje-login"></div>
				<div id="div-registro">
					<a href="olvidoclave.html">¿Olvidaste contraseña?</a>
					<a href="index.html"><i class = "icon-user-plus"></i>REGISTRESE</a>
				</div>
			</form>

	  <div id="div-objprograma">
			<p id="objprograma">Programa para manejo centralizado de los informes Servicio del Campo de toda una congregación por grupos de salida a la predicación y estadísticas de análisis.</p>
		</div>

   </div>
</div>

<footer>
&copy;&nbsp;e1maxSystemas 2016	 ---- infopub@e1max.co
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src = "js/config.js"></script>
<script>
	$(function(){
		
    $('#login-form').submit(function(e){
			e.preventDefault()

			$.ajax({
				url: 'pass.php',
				type: 'POST',
				data: $(this).serialize(),
        success:function(data){
          if(data == "ok")
            window.location.href="menu.php"
          else{
            $('#login-form')[0].reset()  
            $('#mensaje-login').html('<div id="alert_error">Usuario o clave incorrecta</div>')
            alert_ocultardiv()
          }
        }
			})
		})

		$('footer').click(e=>{
			e.preventDefault()
			window.location.href="http://www.e1max.co"
		})
	})


</script>
</body>
</html>
