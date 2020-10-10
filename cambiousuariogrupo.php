<?php

    //erwin corrales 2019 SCRIPT PARA CAMBIAR GRUPO DESDE SELECT usuarios En conf_usuarios.php

  include 'seguridad.php';
  include 'db.php';
  include 'inyeccionsql.php';
   
   //NO EJECUTAR SI NO ES USUARIO TIPO SECRETARIO
   if ($_SESSION['tipousuario'] != "secretario"){
       session_destroy();
   }
  
  //CAPTURAR DATOS DE SELECT CAMBIO DE GRUPO conf_usuario.php
  $grupo = locksql($_POST['grupo']);
  $user = locksql($_POST['user']);    
  
 
//  //ASEGURARSE QUE EL USUARIO A EDITAR PERTENECE A LA MISMA CONGREGACION     
//  $sql="SELECT congregacion FROM usuario WHERE orden = '$user'"; 
//  $consulta = $db->consulta($sql);
//  while($req = mysqli_fetch_row($consulta)){
//      $congregacion = $req[0];
//  }     
//  if ($congregacion != $_SESSION['congregacion']){
//      session_destroy();
//  }

  
  //CAMBIO EN BASE DE DATOS DE USUARIO
  $sql="UPDATE `usuario` SET grupo = $grupo WHERE orden = $user AND `congregacion` = ".$_SESSION['congregacion'];
  $consulta = $db->consulta($sql);
  $db->close();


?>


