<?php
include 'seguridad.php';
include 'db.php';
include 'inyeccionsql.php';

$congregacion = $_SESSION['congregacion'];

//CAPTURO NOMBRES DEL FORMULARIO
if (isset($_POST['nombre']))
    $nombre = locksql($_POST['nombre']);
else
    $nombre = locksql($_POST['nombre-oculto']);
$tel = locksql($_POST['tel']);
$direccion = locksql($_POST['direccion']);
$correo = locksql($_POST['correo']);
$tipo = locksql($_POST['radio']);
$num = locksql($_POST['num']);
$grupo = locksql($_POST['select-grupo']);
$fNacimiento = locksql($_POST['fNacimiento']);
$fBautismo = locksql($_POST['fBautismo']);
//$info = locksql($_POST['info']);

//ASEGURAR QUE LOS CAMPOS DE NUMERO VACIOS SE INSERTEN CERO
function siesvacio($columna){
	if ($columna == "")
		$columna = 0;
	return $columna;
}

$tel = siesvacio($tel);


//ABRO CONSULTA
	if ($num == '')	
		$sql= "INSERT INTO `pub` (`congregacion`, `grupo`, `nombre`, `telefono`, `direccion`, `email`, `fNacimiento`, `fBautismo`, `tipo`, `info`, `passcode`) VALUES ( $congregacion, $grupo, '$nombre', $tel, '$direccion', '$correo', ".($fNacimiento == '' ? "NULL" : "'$fNacimiento'").", ".($fBautismo == '' ? "NULL" : "'$fBautismo'").", '$tipo', 0, 0)";
   else
		$sql = "UPDATE `pub` SET `nombre`= '$nombre',`grupo` = '$grupo', `telefono` = '$tel', `direccion` = '$direccion', `email` = '$correo', `fNacimiento` = ".($fNacimiento == '' ? "NULL" : "'$fNacimiento'").", `fBautismo` = ".($fBautismo == '' ? "NULL" : "'$fBautismo'").", `tipo` = '$tipo' WHERE `num` ='$num'";	

$result=$db->consulta($sql);
$db->close();



if($num == '' &&  $result)
    echo '<div id="alert_ok">PUBLICADOR GUARDADO</div>';
else if($num !=='' && $result)
	echo '<div id="alert_ok">PUBLICADOR EDITADO</div>';
else
	echo '<div id="alert_error">ERROR AL GUARDAR</div>';

?>

