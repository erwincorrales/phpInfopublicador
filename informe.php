<?php

//script para capturar informe de base de datos de publicador
//y enviarlo por ajax a la vista

include 'seguridad.php';
include 'db.php';


$indice=$_REQUEST['num'];

$nombre="";
$publicaciones="";
$video ="";
$horas="";
$min="";
$revisita="";
$estudio=""; $comentario="";
$fecha = date('Y-m-d');

$sql= "SELECT nombre, info, congregacion, grupo, tipo FROM pub WHERE pub.num =$indice";
$consulta = $db->consulta($sql) or die('Error consulta valores del publicador'. mysqli_error());

while($req=mysqli_fetch_array($consulta)){
  $nombre = $req['nombre'];
  $info = $req['info'];
  $congregacion = $req['congregacion'];
  $grupo = $req['grupo'];
  $tipo =$req['tipo'];    
}

//GARANTIZAR QUE SOLO DE LA CONGREGACION PUEDAN EDITAR HERMANO

if($_SESSION['congregacion'] != $congregacion) 
   header('Location: menu.php');


if ($info == 1){
    $sql = "SELECT pub, video, horas, creditHoras, revisita, estudio, comentario, fecha fROM registro WHERE num = $indice";
    $consulta = $db->consulta($sql);

    while ($req=mysqli_fetch_array($consulta)){
        $publicaciones = $req['pub'];
        $video = $req['video'];
        $horas = $req['horas'];
        $creditHoras = $req['creditHoras'];
        $revisita = $req['revisita'];
        $estudio = $req['estudio'];
        $comentario =$req['comentario'];
        $fecha=$req['fecha'];
    }
    //transformar a minutos
    $min = ($horas - floor($horas))*60;
    $min = round($min,0);
    $horas = floor($horas);
}
else{
    $publicaciones ='';
    $video = '';
    $horas = '';
    $min = '';
    $creditHoras = '';
    $revisita = '';
    $estudio = '';
    $comentario = '';
    $fecha = $fecha;
}

$db->close();

$resp = [
    'nombre' => $nombre,
    'pub' => $publicaciones,
    'video' => $video,
    'horas' => $horas,
    'min' => $min,
    'creditHoras' => $creditHoras,
    'revisita' => $revisita,
    'estudio' => $estudio,
    'comentario' => $comentario,
    'tipo' => $tipo,
    'fecha' => $fecha,
    'info' => $info,
    'num' => $indice
    ];

    header('Content-type: application/json; charset=utf-8');
    echo json_encode($resp);
?>


