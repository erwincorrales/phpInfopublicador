<?php

//erwin corrales 2019 script para consultar si nombre uusario y correo han sido usados en la base de datos y responde en formulario
//validacion de formulario

include 'seguridad.php';
include 'db.php';
include 'inyeccionsql.php';

if (isset($_POST['user'])){
   $user = locksql($_POST['user']);

   $sql = "SELECT `user` FROM usuario WHERE `user` = '$user'";
   $resultado = $db->consulta($sql);

   $req = mysqli_fetch_row($resultado); 
   $existe = mysqli_num_rows($resultado);
    
    
   if($user == $_SESSION['user']){
      $resp->info ='<div id ="alert_ok">Nombre de Usuario Actual!</div>';
      $resp->op = true;      
   }
   else if($existe == 1 && $user != $_SESSION['user']){
      $resp->info ='<div id ="alert_error">Usuario No Disponible!</div>';
      $resp->op = false;
   }
   else{
      $resp->info ='<div id =alert_ok>Nombre de Usuario disponible!</div>';
      $resp->op = true;
   }

}

if (isset($_POST['mail'])){
   $correo = locksql($_POST['mail']);

   $sql = "SELECT `correo` FROM usuario WHERE `correo` = '$correo'";
   $resultado = $db->consulta($sql);

   $existe = mysqli_num_rows($resultado);
   $req = mysqli_fetch_row($resultado);    
   
   if($correo == $_SESSION['correo']){
      $resp->info ='<div id ="alert_ok">Direccion de Correo Actual!</div>';
      $resp->op = true;      
   }
   else if($existe == 1 && $user != $_SESSION['user']){
      $resp->info ='<div id ="alert_error">Correo ya Registrado! </div>';
      $resp->op = false;
   }
   else{
      $resp->info ='<div id =alert_ok>Direccion de Correo disponible!</div>';
      $resp->op = true;
   }
}
$db->close();

echo json_encode($resp);
   
