<?php
session_start();
session_destroy();
?>



<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>SESION FINALIZADA</title>
		<link rel="stylesheet" href="css/estilo.css">
		<link rel="icon" type="image/png" href="images/favicon.png" />
		<script src="js/config.js" type="text/javascript"></script>
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>

<body onload="retardo_pagina()">
	<h3> Has finalizado Satisfactoriamente la Sesion!</h3>
	<img id ="logout-image" src="images/1011259_univ_sqr_lg.jpg" alt=""/>
	<br>
	<a id = "final" href = "login.php">VOLVER A PAGINA PRINCIPAL</a>
</body>
</html>
