<?php
include 'seguridad.php';
include 'db.php';

//DEFINIR PERIODO DE MES A VISUALIZAR
if(date('d') < 30){
   $primerdia=date('Y-m',strtotime('-1 month')).'-30';
   $ultimodia=date('Y-m').'-29';   
}
else{
   $primerdia = date('Y-m').'-30';
   $ultimodia = date('Y-m',strtotime('+1 month')).'-01';
}
   


//HACER LA LISTA DEL MES SOLO GRUPO O GLOBAL SUPERUSUARIO
if($_SESSION['tipousuario']=="secretario"  or $_SESSION['tipousuario'] == "individual")
   $sql = "SELECT `nombre`,`pub`,`video`,`horas`,`revisita`, `estudio`, `comentario` FROM pub LEFT JOIN registro ON pub.num = registro.num AND registro.fecha >= '$primerdia' AND registro.fecha <= '$ultimodia' WHERE pub.congregacion = '".$_SESSION['congregacion']."' ORDER BY nombre ASC";

else
   $sql = "SELECT `nombre`,`pub`,`video`,`horas`,`revisita`, `estudio`, `comentario` FROM pub LEFT JOIN registro ON pub.num = registro.num and registro.fecha >= '$primerdia' AND registro.fecha <= '$ultimodia' WHERE pub.congregacion = '".$_SESSION['congregacion']."' AND pub.grupo = '".$_SESSION['grupo']."' ORDER BY nombre ASC";

$consulta=$db->consulta($sql);
$db->close();


echo '
<table id="tabla-exportar">
   <tr>
      <th>Nombre</th>
      <th>Pubs</th>
      <th>Video</th>
      <th>Horas</th>      
      <th>Revisi</th>
      <th>Estud</th>
      <th>Obs</th>
   </tr>
   ';

   while($req=mysqli_fetch_array($consulta)){
      echo '<tr>
            <td id="jusTd">'.$req['nombre'].'</td>
            <td>'.$req['pub'].'</td>
            <td>'.$req['video'].'</td>';
            
      if ($req['horas']=="")
            echo '<td>'.$req['horas'].'</td>';
      else
            echo '<td>'.round($req['horas'],2).'</td>';
       
      echo '<td>'.$req['revisita'].'</td>
            <td>'.$req['estudio'].'</td>
            <td>'.$req['comentario'].'</td>
            </tr>';
   }
   
echo '</table>';




