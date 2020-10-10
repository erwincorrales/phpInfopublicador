<?php
include 'inyeccionsql.php';
include 'db.php';

//control de acceso
if (!$_POST)
   header('Location: login.php');
else{
   $correo =  locksql($_POST['correo']);
    
  //consulta si existe usuario correo en db
   $sql = "SELECT `user`, `pass`, `nombre`, `correo` FROM `usuario` WHERE `correo` = '$correo'";
   $consulta = $db->consulta($sql);
   $req=mysqli_fetch_row($consulta);
   $db->close();

   if ($correo == $req[3])
	  $access = 1;
   else
     $access = 0;

   if ($access == 1){
         $usuario=$req[0];
         $clave=$req[1];
         $nombre=$req[2];
         $destinatario = $req[3];


   //ENVIO DE CORREO ELECTRONICO
   $asunto = "Recuperacion Clave Usuario infoPublicador";

     //  headers
    $header = "From: infoPublicador < infopub@e1max.co >\r\n";
    $header .= "Reply-To: ".$destinatario."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: text/html; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\n";

    $mensaje = '
      <!DOCTYPE html>
      <html lang="es">
      <head>
         <meta charset="UTF-8">
         <title>Recordar Contrase単a Acceso Infopubicador</title>
         <style type="text/css">body { font-size: 16px; padding: 20px}</style>
      </head>
      <body>
         <img src = "http://www.e1max.co/infopublicador/images/infopub-negro.png" alt="infoPub" width = 100 height = 40>
         <br>
         <br>
         Hola! Hno: <b>'.$nombre.'</b><br> Le recordamos de infoPublicador sus credenciales de ingreso:
         <br>
         <br>

         <div style = "background: #ddd; margin: 20px; padding: 40px">
         	Su usuario es: <b>'.$usuario.'</b><br>
         	Su Clave de Acceso es: <b>'.$clave.'</b>
         </div>
         <br>
         <br>
         Puedes cambiar su clave en <b>CONFIGURAR USUARIO</b>.<br>
         Puede continuar su acceso en programa InformePublicador.<br>
         <a href="http://infopub.e1max.co">infopub.e1max.co</a>
         <br>
         <br>
         &copy; e1maxIndustries Inc.

      </body>
      </html>
      ';

    $exito = mail($destinatario, $asunto, $mensaje, $header);
    if($exito)
        echo "OK";
    else
        echo "ERROR";
    
  }
}


//    $mensaje = "Hola! Le comunicamos de infoPublicador sus credenciales de ingreso:.\r\n\n";
//    $mensaje.= "Hola Sr ".$nombre.".\r\n";
//    $mensaje.= "Su usuario es: ".$usuario." .\r\n";
//    $mensaje.= "Su Clave de Acceso es: ".$clave." .\r\n\n";
//    $mensaje.= "Puede continuar su acceso en programa InformePublicador.\r\n";
//    $mensaje.="infopub.e1max.co";
//

?>
