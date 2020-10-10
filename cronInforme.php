<?php
require 'db.php';
    
//CRON PARA REINICIAR EL MES DE CONFIGURACION DE INFORMES
$sql= "UPDATE pub SET info = 0";
$consulta = $db->consulta($sql);

$db->close()
?>