<?php

//erwin corrales 2019  script para crear nuevo grupo a la predicacion o editar existente.

include 'seguridad.php';
include 'inyeccionsql.php';
include 'db.php';

$congregacion = $_SESSION['congregacion'];
$nombregrupo = $_POST['nombreGrupo'];

if($_POST['num']!=""){
   $sql="UPDATE grupos SET nombregrupo = '".$nombregrupo."' WHERE id =".$_POST['num'];
   $consulta = $db->consulta($sql);

   if($consulta){
      echo '<div id="alert_ok">Grupo Actualizado</div>';
   }else{
      echo '<div id="alert_error">No se pudo Actualizar</div>';   
   }

}else{
   $sql="INSERT INTO grupos (`nombregrupo`, `congregacion`) VALUES ('".$nombregrupo."', ".$congregacion.")";
   $consulta= $db->consulta($sql);
   
   if($consulta){
      echo '<div id="alert_ok">Grupo Creado</div>';
   }else{
      echo '<div id="alert_error">No se pudo Crear Grupo</div>';   
   }
}
?>

