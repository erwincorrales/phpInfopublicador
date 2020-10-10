<?php

    //erwin corrales 2019. script para eliminar grupo de predicacion y actualizar registro de usuarios

    include 'seguridad.php';
    include 'inyeccionsql.php';
    include 'db.php';

    if($_POST['num'] != ""){
        $num = locksql($_POST['num']);
        $sql="DELETE FROM grupos where  id = $num";
        $consulta=$db->consulta($sql);
        
        if($consulta){
            $sql = "UPDATE pub SET `grupo` = 0 WHERE grupo = $num ";
            $consulta = $db->consulta($sql);
            $sql = "update usuarios SET `grupo` = 0 WHERE grupo = $num";
            $consulta = $db->consulta($sql);    

            echo '<div id="alert_ok">Grupo Predicación Eliminado</div>';
        }
        else
            echo '<div id="alert_error">No se pudo Eliminar Grupo!</div>';
    }

?>