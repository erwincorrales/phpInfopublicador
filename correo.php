<?php
include 'crearexcel.php';

$correo_destinatario = $_POST['correo'];

//Agregar nombre de grupo a archivo
$nombre_grupo = $_SESSION['nombregrupo'];
$nombre_congregacion = $_SESSION['nombrecongregacion'];
$db->close();


//GUARDAR ARCHIVO EN SERVIDOR PHPEXCEL
$objWriter->save(__DIR__."/informe".$nombre_grupo."-".$nombre_congregacion.".xlsx");

//CREAR EL CORREO CON CABECERAS PARA ADJUNTAR.

$my_file = "informe".$nombre_grupo."-".$nombre_congregacion.".xlsx"; // puede ser cualquier formato
$my_path = getcwd().'/';
$my_name = $_SESSION['nombre'];
$my_mail = "infopub@e1max.co";
$my_replyto = "infopub@e1max.co";
$my_subject = "Informe Predicacion ".$nombre_grupo." ".$fecha1;
$my_message = '

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <title>Document</title>
</head>
<body>
   Hola. Adjunto informacion del mes de '.$fecha1.'<br>
   Cong: <b>'.$_SESSION['nombregrupo'].'-'.$_SESSION["nombrecongregacion"].'</b><br><br>'.
   $_SESSION["nombre"].'    
   <br>
   <br>
   Powered by <a href="http://infopub.e1max.co"><img src="http://www.e1max.co/infopublicador/images/infopub-negro.png" height= "20" alt=""></a>
   <br>&copy; e1maxIndustries. 
</body>
</html>
';

mail_attachment($my_file, $my_path, $correo_destinatario, $my_mail, $my_name, $my_replyto, $my_subject, $my_message);

function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
    $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(time());
    $name = basename($file);
    
    //HEADERS
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\n";
    //TEXT HEADERS
    $cuerpo = "--".$uid."\r\n";
    $cuerpo .= "Content-Type: text/html; charset=iso-8859-1\r\n";
    $cuerpo .= "Content-Transfer-Encoding: 7bit\r\n\n";
    $cuerpo .= $message."\r\n";
    //FILE HEADERS
    $cuerpo .= "--".$uid."\n";
    $cuerpo .= "Content-Type: application/octet-stream; name=\"".$name."\"\r\n"; 
    // use different content types here
    $cuerpo .= "Content-Transfer-Encoding: base64\r\n";
    $cuerpo .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\n";
    $cuerpo .= $content;
    
    //FIN DEL CUERPO SIEMPRE ES BOUNDARY DESPUES DOBLE ESPACIO
    $cuerpo .="\r\n--".$uid."--\n";
    $result = mail($mailto, $subject, $cuerpo, $header);
   
    //eliminar archivo realizado
   unlink($file);
    
}
header('Location: menu.php');
?>



