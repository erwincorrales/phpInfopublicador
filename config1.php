<?php

include 'seguridad.php';
include 'db.php';
include 'inyeccionsql.php';


//erwin corrales 2019 cambiar credenciales a usuario de SESSION

$user = locksql($_POST['user']);
$pass = locksql($_POST['pass']);
$nombre_user = locksql($_POST['nombre']);
$correo = locksql($_POST['correo']);


//ENCRIPTAR CLAVE
//$pass = md5($pass);

    $sql="UPDATE usuario SET `user` = '$user', `pass` = '$pass', `nombre` = '$nombre_user', `correo` = '$correo' where `user` = '".$_SESSION['user']."'";
    
    $_SESSION['user']=$user;
    $_SESSION['correo']=$correo;
    $_SESSION['pass']=$pass;
    $_SESSION['nombre']=$nombre_user;

$result=$db->consulta($sql);
$db->close();

if ($result)
    echo '<div id="alert_ok">Datos Actualizados!</div>';
else
    echo '<div id="alert_error">Datos Actualizados!</div>';
?>