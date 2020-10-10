<?php
include 'seguridad.php';
include 'db.php';

$nombre ="";$correo="";$user="";$pass="";$congregacion=$_SESSION['congregacion'];

//variables opcion select
$select="";

//consultar nombre del usuario
if(isset($_POST['num'])){
    $orden = $_REQUEST['num'];
    $sql5='SELECT nombre, correo, user, pass, congregacion, tipo FROM usuario WHERE orden ="'.$orden.'"';
    $consulta=$db->consulta($sql5);
    $db->close();
    
    $req = mysqli_fetch_row($consulta);
    $nombre = $req[0];
    $correo =$req[1];
    $user = $req[2];
    $pass = $req[3];
    $congregacion = $req[4];
    $tipo=$req[5];
   
    //SI MODIFICAS O CONSULTAS A ALGUIEN DE OTRA CONGREGACION DESTRUYE SESSION
    if($_SESSION['congregacion'] != $congregacion)
        session_destroy();
        
    

    //respuesta al cliente
    $resp['user']=$user;
    $resp['pass']=$pass;
    $resp['correo']=$correo;
    $resp['tipo']=$tipo;
    $resp['nombre']=$nombre;
    $resp['num']=$orden;
    
    echo json_encode($resp);  
}
?>

  


