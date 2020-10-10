<?php
  include 'seguridad.php';
  include 'db.php';
    
  if(isset($_REQUEST['num'])){
      $numpublicador=$_REQUEST['num']; 
      //borra de la lista publicador
      $sql = "DELETE FROM `pub` WHERE pub.num = $numpublicador";
      $query=$db->consulta($sql);

      //borra todos los registros asociados al publicador.
      $sql = "DELETE FROM `registro` WHERE `registro`.`num` = $numpublicador";
      $query=$db->consulta($sql);
      echo '<div id="alert_ok">Usuario Eliminado!</div>';
  }
  else
      echo '<div id="alert_error" No se pudo borrar publicador!></div>';
  
  $db->close();
 ?>
