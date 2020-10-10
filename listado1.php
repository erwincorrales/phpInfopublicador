<?php
include 'seguridad.php';
include 'db.php';

$congregacion = $_SESSION['congregacion'];

//desplegar publicadores al grupo asignado a menos que sea superusuario
//if ($_SESSION['tipousuario'] == "secretario" )
    $sql = "SELECT pub.nombre, pub.num, pub.tipo, pub.info, pub.grupo, grupos.nombregrupo FROM `pub` LEFT JOIN `grupos` on pub.grupo = grupos.id WHERE pub.congregacion = '$congregacion' ORDER BY `pub`.`nombre` ASC";    
//else
//    $sql = "SELECT * FROM `pub` where grupo = ".$_SESSION['grupo']." AND `congregacion` = '$congregacion' ORDER BY `pub`.`nombre` ASC";

$query=$db->consulta($sql);
$db->close();

echo '<table id = "lista">';

  while ($req=mysqli_fetch_array($query)){

    if($req['info']==1)
      echo('<tr class = "selected">');
    else     
      echo('<tr>');
    
    if($req['nombregrupo'] == '')
      echo '<td id="jusTd" style = "color: #b72a6c; font-weight:bold">'.$req['nombre'].'</td>';  
    else{
      if ($req['tipo'] == 'R')     
        echo('<td id="jusTd" style = "color: #8c3504">'.$req['nombre'].'</td>');
      else if ($req['tipo'] == 'A')  
        echo('<td id="jusTd" style = "color: #d85206">'.$req['nombre'].'</td>'); 
      else
        echo('<td id="jusTd">'.$req['nombre'].'</td>'); 
    }
        
    echo '<td class="oculto">'.$req['nombregrupo'].'</td>';  
          
    if($req['info']==1)       
       echo('<td id="jusTd">'.'<a href="'.$req['num'].'" cat="i">Editar Informe</a></td>');
    else
       echo('<td id="jusTd">'.'<a href="'.$req['num'].'" cat ="i">Agregar Informe</a></td>');
      
    echo('<td id="jusTd">'.'<a href="'.$req['num'].'" cat="e">Datos Publicador</a></td>');
         
    if ($_SESSION['tipousuario'] == "secretario" or $_SESSION['tipousuario'] == "individual"){
         echo('<td id="jusTd">'.'<a href="#" onclick="saveborrado('.$req['num'].');">Borrar Publicador</a></td>');    
     }
    }
    echo('</tr></table>');
    ?>

