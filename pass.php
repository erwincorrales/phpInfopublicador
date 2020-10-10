<?php
include 'db.php';
include 'inyeccionsql.php';

$user = locksql($_POST['usuario']);
$pass = locksql($_POST['password']);

//Encriptar la clave
//$pass = md5($pass);
$access = 0;


//COMPARAR CONTRASENAS CON USUARIOS REGISTRADOS
$consulta = $db->consulta("SELECT `pass` FROM `usuario` WHERE `user` = '$user' or `correo` = '$user'");
$reg=mysqli_fetch_row($consulta);

if (strcmp($reg[0], $pass)==0)
	$access = 1;

if ($access == 1){
	//findemes($db);   obsoleto reinicia la base de datos el mes por evento
	$sql = "SELECT * FROM usuario WHERE `user` = '$user' or `correo` = '$user'";
	$consulta = $db->consulta($sql);
	while($req=mysqli_fetch_array($consulta)){
		$usuario=$req['user'];
		$clave=$req['pass'];
		$orden=$req['orden'];
		$nombre=$req['nombre'];
		$congregacion=$req['congregacion'];
		$grupo=$req['grupo'];
    	$tipo=$req['tipo'];
		$correo = $req['correo'];
	}

    $sql="SELECT `nombregrupo` FROM `grupos` WHERE `congregacion` = '$congregacion' AND `id` = '$grupo'";
	$consulta = $db->consulta($sql);
	$req=mysqli_fetch_row($consulta);
	$nombregrupo = $req[0];

	$sql="SELECT `nombre_congregacion`, `mailsecretario` FROM `congregacion` WHERE id = ".$congregacion;
	$consulta = $db->consulta($sql);
	$req=mysqli_fetch_row($consulta);
	$nombrecongregacion = $req[0];
	$mailsecretario=$req[1];

//define todas las variables del usuario como globales de session
session_start();
$_SESSION['user'] = $usuario;
$_SESSION['pass'] = $clave;
$_SESSION['grupo'] = $grupo;
$_SESSION['nombre'] = $nombre;
$_SESSION['congregacion'] = $congregacion;
$_SESSION['correo'] = $correo;
$_SESSION['tipousuario'] =$tipo;
if(strcmp($tipo,"secretario") == 0)
    $_SESSION['nombregrupo'] = "";
else
    $_SESSION['nombregrupo'] = $nombregrupo;
$_SESSION['nombrecongregacion'] = $nombrecongregacion;
$_SESSION['mailsecretario'] = $mailsecretario;
$_SESSION['orden'] = $orden;

$db->close();
// header ('Location: menu.php');
echo 'ok';
}
else{
   $db->close();
   echo 'wrong';
}

//SE DEBE IMPLEMENTAR UNA SOLA VEZ AL MES COMO EVENTO DE BASE DE DATOS
//OBSOLETO REEMPLAZADO POR FUNCION EN SQL CRON

function findemes($db){
	//COMPARAMOS SI ES 29 DE UN MES PARA BORRAR INFORMADOS
    $hoy=date('Y-m-d');
    $finmes=date('Y-m')."-29";
    if($hoy>=$finmes){
      $sql='UPDATE `pub` SET `info`= 0';
      $db->consulta($sql);

	//ELIMINAR REGISTROS DE MAS DE 1 AÑO
		$haceunano=date('Y-m-d',strtotime('- 1 year'));
		$sql="DELETE * FROM registro WHERE fecha <= '$haceunano'";
		$db->consulta($sql);
	    }
}
?>
