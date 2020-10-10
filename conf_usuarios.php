<?php
   include 'seguridad.php';
   include 'db.php';
  

   if ($_SESSION['tipousuario'] !== "secretario"){
       session_destroy();
   }
   $congregacion = $_SESSION['congregacion'];

   $sql5 = "SELECT `nombre`,`orden`,`grupo`, `tipo` FROM `usuario` LEFT JOIN `grupos` ON usuario.grupo = grupos.id WHERE usuario.congregacion = ".$congregacion;
   $sql6 = "SELECT id, nombregrupo from grupos WHERE grupos.congregacion =".$congregacion." ORDER BY nombregrupo";
   $sql7 = "select m.id, nombregrupo, cuenta, lista from (select id, nombregrupo, count(*) as cuenta from grupos left join pub on grupos.id = pub.grupo where grupos.congregacion = ".$congregacion." group by id) as m LEFT JOIN  (select id, GROUP_CONCAT(nombre separator ' - ') as lista from usuario inner join grupos on id = usuario.grupo group by id) as n on m.id = n.id ORDER BY nombregrupo";
   $consulta5 = $db->consulta($sql5);
   $consulta6 = $db->consulta($sql6);
   $consulta7 = $db->consulta($sql7);
?>
  
   <h5>GRUPOS</h5>
   <table>
      <tr>
         <th style="max-width: 50px;">Nombre</th>
         <th>NumPub</th>
         <th>Hno_Encargado_Grupo</th>
         <th></th>
      </tr>

      	 <?php   //INFORMACION DE LOS GRUPOS DE PREDICACION
      	 	while ($req = mysqli_fetch_array($consulta7)){
      	 	     echo "<tr>
      	 		<td>".$req['nombregrupo']."</td>
      	 		<td>".$req['cuenta']."</td>
         		<td>".$req['lista']."</td>
         		<td><a href=".$req['id'].">EDITAR</a></td>
         		</tr>";
      	 	}
      	 ?>

   </table>
   <input style = "font-family: fontello" type="button" value= "&#xe801 Agregar Nuevo Grupo" name="nuevogrupobtn">

   <br>
     <h5>USUARIOS</h5>
       <table>
      <tr>
         <th>Nombre</th>
         <th>TipoUsuario</th>
         <th>Grupo_Predicación</th>
         <th></th>
      </tr>
      <?php
      while ($req = mysqli_fetch_array($consulta5)){
         echo "
         <tr>
            <td style=\"max-width:60px;\" >".$req['nombre']."</td>
            <td>".$req['tipo']."</td>
            <td><select name='grupo' id='selectgrupo'>
            <option value = 0 >--Escoger grupo--</option>";
         while ($req1 = mysqli_fetch_array($consulta6)){
              if ($req['grupo'] == $req1['id'])
              	echo '<option value="'.$req1['id'].'" selected>'.$req1['nombregrupo'].'</option>';
              else
              	echo '<option value="'.$req1['id'].'" >'.$req1['nombregrupo'].'</option>';
         }
         mysqli_data_seek($consulta6,0);
         echo "
            </select></td>
            <td><a href='".$req['orden']."'>EDITAR</a></td>
         </tr>";
      }
      ?>
   </table>
   <input style = "font-family: fontello;" type="button" name="newUserBtn" value= " &#xf234 Agregar Nuevo Usuario">

