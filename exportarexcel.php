<?php
include 'seguridad.php';
include 'crearexcel.php';

//error_reporting(0);
$nombre_grupo = $_SESSION['nombregrupo'];

$arch = "informe".$nombre_grupo.".xlsx";

// ENCABEZADO SI SE VA DESCARGAR ARCHIVO CON NAVEGADOR
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename($arch));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
//header('Content-Length: ' . filesize($arch));
ob_clean();
flush();
//readfile($arch);
$objWriter->save('php://output');
//borrar archivo del servidor
//unlink($arch);

//VOLVER A PAGINA
//header('Location: exportardatos.php'); 
?>