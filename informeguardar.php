<?php
include 'seguridad.php';
include 'db.php';
include 'inyeccionsql.php';

$num = locksql($_REQUEST['num']);
$publicaciones = locksql($_REQUEST['publicaciones']);
$video = locksql($_REQUEST['video']);
$horas = locksql($_REQUEST['horas']);
$minutos = locksql($_REQUEST['minutos']);
$creditHoras = locksql($_REQUEST['creditHoras']);
$revisita = locksql($_REQUEST['revisitas']);
$estudio = locksql($_REQUEST['estudios']);
$comentario = locksql($_REQUEST['comentario']);
$fecha = locksql($_REQUEST['fecha']);
$tipo = locksql($_REQUEST['tipo']);
$agregar_editar = locksql($_REQUEST['agregar_actualizar']);

//VALIDAR SI SON VACIOS PARA INSERTAR 0 EN BASE DE DATOS
function siesvacio($columna){
	if ($columna == "")
		$columna = "0";

	return $columna;
}

$publicaciones = siesvacio($publicaciones);
$video = siesvacio($video);
$horas = siesvacio($horas);
$minutos = siesvacio($minutos);
$creditHoras = siesvacio($creditHoras);
$revisita = siesvacio($revisita);
$estudio = siesvacio($estudio);
$horas = $horas + $minutos/60;


//AGREGA O EDITA VALORES DE INFORME
if($agregar_editar == 1){
  $sql="UPDATE `registro` SET `pub` = '$publicaciones', `video` = '$video',`horas` = '$horas', `creditHoras` = '$creditHoras', `revisita` = '$revisita', `estudio`= '$estudio', `comentario` = '$comentario', `tipopub`='$tipo' WHERE `num` = '$num' AND `fecha` = '$fecha'";
   $estado=$db->consulta($sql); 
}
else{
      $sql = "INSERT INTO registro (num, pub, video, horas, creditHoras, revisita, estudio, comentario, tipopub, fecha) VALUES ('$num','$publicaciones','$video','$horas','$creditHoras','$revisita','$estudio','$comentario','$tipo','$fecha')";

    $estado=$db->consulta($sql);
    if($estado){
        $sql="UPDATE pub SET info = 1 WHERE num = '$num'";
        $estado1=$db->consulta($sql);
    }   
  }

$db->close();


    if($agregar_editar == 1)
        echo '<div id="alert_ok">INFORME EDITADO!</div>';
    else
        echo '<div id="alert_ok">INFORME GUARDADO!</div>';    

//    echo '<div id="alert_error">INFORME NO GUARDADO!</div>';

?>
