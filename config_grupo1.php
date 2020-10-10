<?php

include 'seguridad.php';
include 'db.php';
include 'inyeccionsql.php';

$grupo=locksql($_POST['nombregrupo']);
$correo = locksql($_POST['correo']);

$sql="UPDATE `grupos` SET `nombregrupo` = '$grupo' WHERE `congregacion` = ". $_SESSION['congregacion'];
$db->consulta($sql);
$sql ="UPDATE `congregacion` SET `mailsecretario` = '$correo' WHERE `id` = ".$_SESSION['congregacion'];
$db->consulta($sql);
$db->close();

header ('Location: config_grupo.php?op=1');