<?php
include 'seguridad.php';

if (isset($_REQUEST['op'])){
	if ($_REQUEST['op'] == 1)
	$mensaje = "Datos de Grupo y Correo ajustados!";
}
else
	$mensaje ="";


//PRECARGAR VALORES ESTABLECIDOS EN FORMULARIO:
$congregacion = $_SESSION['congregacion'];
$grupo = $_SESSION['grupo']; 
$mailsecretario = $_SESSION['mailsecretario'];
$nombregrupo = $_SESSION['nombregrupo'];
?>


<!DOCTYPE html>
<html>
<head>
	<title>CONFIGURACION GRUPO - USUARIOS</title>
	<link rel="stylesheet" href="css/estilo.css">
	<link rel="icon" type="image/png" href="images/favicon.png" />
	<script type="text/javascript" src="js/config.js"></script>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>
<body onload = "killSession()">
	<h3>CONFIGURACION DE GRUPO PREDICACION</h3>
	
	<form action="config_grupo1.php" autocomplete="off" onsubmit="return form_vacio()" method="post">
		<label for="">Nombre Grupo Predicacion:</label>
		<input type="text" name="nombre-grupo" required <?php echo 'value="'.$nombregrupo.'"'?>>
		<label for="">Direccion Correo Secretario:</label>
		<input type="email" name="correo" required <?php echo 'value="'.$mailsecretario.'"'?> >
		
				
		<input type="submit" value="CONFIGURAR">
		<input type="button" name="btn" value="VOLVER A MENU" onclick="location='menu.php'">

		<p> <?php echo $mensaje ?> </p>
	</form>	
		
	<footer>
		e1maxSystems 2016
	</footer>		
</body>
</html>