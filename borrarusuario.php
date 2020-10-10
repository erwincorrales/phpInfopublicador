<?php 

include 'seguridad.php';
include 'db.php';
    
//erwin corrales 2019 permite al secretario borrar un usuario

if($_POST['num']){
    $sql="DELETE FROM usuario WHERE orden = ".$_POST['num']." and congregacion = ".$_SESSION['congregacion'];
    $consulta = $db->consulta($sql);
    
    echo '<div id="alert_ok">Usuario Eliminado</div>';
}else
    echo '<div id="alert_error">No se ha podido eliminar usuario!</div>';
?>