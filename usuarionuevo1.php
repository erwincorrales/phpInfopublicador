<?php

include 'seguridad.php';
include 'db.php';
include 'inyeccionsql.php';


//permite editar usuarios al secretario de congregacion en conf_user

$user = locksql($_POST['user']);
$pass = locksql($_POST['pass']);
$nombre_user = locksql($_POST['nombre']);
$correo = locksql($_POST['correo']);
$tipo = locksql($_POST['tipousuario']);

//ENCRIPTAR CLAVE
//$pass = md5($pass);

if ($_POST['num'] != null)
    $sql="UPDATE usuario SET `user` = '$user', `pass` = '$pass', `nombre` = '$nombre_user', `tipo` = '$tipo' ,`correo` = '$correo' where `orden` = '".locksql($_POST['num'])."'";
else{
    $sql="INSERT INTO `usuario`(`user`, `pass`, `nombre`, `congregacion`, `grupo`, `tipo`, `correo`) VALUES ('$user', '$pass', '$nombre_user', ".$_SESSION['congregacion'].", '0','$tipo', '$correo')";

    // $_SESSION['user']=$user;
    // $_SESSION['correo']=$correo;
    // $_SESSION['pass']=$pass;
    // $_SESSION['nombre']=$nombre_user;
}

$db->consulta($sql);
$db->close();

echo "OK";

?>
