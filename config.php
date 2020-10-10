<?php
include 'seguridad.php';
include 'db.php';


//consultar nombre del usuario por num o si no especificas cargas datos de usuario de session


if(isset($_REQUEST['num'])){
    $orden = $_REQUEST['num'];
    $sql5='SELECT nombre, correo, user, pass, congregacion FROM usuario WHERE orden ="'.$orden.'"';

$consulta=$db->consulta($sql5);

$req = mysqli_fetch_row($consulta);
$nombre = $req[0];
$correo =$req[1];
$user = $req[2];
$pass = $req[3];
$congregacion = $req[4];   
$db->close();

//SI MODIFICAS O CONSULTAS A ALGUIEN DE OTRA CONGREGACION DESTRUYE SESSION
if($_SESSION['congregacion'] != $congregacion)
    session_destroy();
}

//al no especificar num:
else{
    $nombre = $_SESSION['nombre'];
    $correo = $_SESSION['correo'];
    $user = $_SESSION['user'];
    $pass = $_SESSION['pass'];
    $orden = $_SESSION['orden'];
}

$resp =[
    'nombre' => $nombre,
    'correo' => $correo,
    'user' => $user,
    'pass' => $pass,
    'num' => $orden
];

    echo json_encode($resp)
?>



