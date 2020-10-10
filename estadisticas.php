<?php
include 'seguridad.php';
include 'db.php';

$grupo=$_SESSION['grupo'];
$congregacion = $_SESSION['congregacion'];
$tabla_baja_actividad;
$tabla_sin_estudios;
$baja_actividad = 10;

//SACAR TOTALES FECHA DE ANALISIS-------------------------------------------------------
if(date('d') < 30){
   $fecha_inicial=date('Y-m',strtotime('-1 month')).'-29';
   $fecha_final=date('Y-m-d');

}
else{
   $fecha_inicial = date('Y-m').'-30';
   $fecha_final = date('Y-m-d');
}
$fecha_doceMeses = date('Y-m-d', strtotime('-12 months'));

if(strcmp($_SESSION['tipousuario'],"secretario") == 0){
	$sql="SELECT `nombre`,`tipo`,`info`, `grupo`,`pub`, `video`, `horas`, `revisita`, `estudio` FROM pub LEFT JOIN registro on pub.num = registro.num WHERE registro.fecha >= '$fecha_inicial' and registro.fecha <= '$fecha_final' and  pub.congregacion = '$congregacion' order by registro.horas";
   $sql1="SELECT COUNT(*) FROM pub WHERE pub.congregacion = '$congregacion'";
//   $consulta = $db->consulta($sql1);
//   $req=mysqli_fetch_row($consulta);
//   $tot_publicadores = $req[0];

   //consultas para calculo de precursores regulares y auxiliares
   $sql2 ="SELECT pub.nombre, sum(registro.horas) as suma, avg(registro.horas) as promedio, count(*) as meses, (840 div 12 * count(*) - sum(registro.horas)) as faltantes from registro inner join pub on pub.num = registro.num where pub.tipo = 'R' and pub.congregacion = '$congregacion' and registro.fecha > '$fecha_doceMeses' group by registro.num ORDER BY `faltantes` ASC";
   $sql3 ="SELECT pub.nombre, sum(registro.horas) as suma, avg(registro.horas) as promedio, count(*) as meses, (600 div 12 * count(*) - sum(registro.horas)) as faltantes from registro inner join pub on pub.num = registro.num where pub.tipo = 'A' and pub.congregacion = '$congregacion' and registro.fecha > '$fecha_doceMeses' group by registro.num ORDER BY `faltantes` ASC";
   $consulta1 = $db->consulta($sql2);
   $consulta2 = $db->consulta($sql3);

}
else
	$sql="SELECT `nombre`, `tipo`,`info`, `grupo`,`pub`, `video`, `horas`, `revisita`, `estudio` FROM pub LEFT JOIN registro on pub.num = registro.num WHERE registro.fecha >= '$fecha_inicial' and registro.fecha <= '$fecha_final' and  pub.congregacion = '$congregacion' and pub.grupo = '$grupo' order by registro.horas";

$consulta = $db->consulta($sql);

//CALCULO DE TOTALES-BAJA ACTIVIDAD-SIN ESTUDIOS

$tot_pub=0; $tot_video =0; $tot_horas =0; $tot_revisitas=0; $tot_estudios=0; $i=0; $informantes=0; $publicadores=0; $regulares=0; $auxiliares = 0;

while($req = mysqli_fetch_array($consulta)){

	//TOTALES PUBLICADORES PAUX y PREG
	$i++;
   if ($req['info'] == 1 and $req['horas'] > 0)
      $informantes++;

   if($req['tipo'] == ''){
      $publicadores++;
      $pub_pubs +=$req['pub'];
      $pub_videos += $req['video'];
      $pub_horas += $req['horas'];
      $pub_revisitas += $req['revisita'];
      $pub_estudios += $req['estudio'];
   }
   else if ($req['tipo'] == 'R'){
      $regulares++;
      $reg_pubs +=$req['pub'];
      $reg_videos += $req['video'];
      $reg_horas += $req['horas'];
      $reg_revisitas += $req['revisita'];
      $reg_estudios += $req['estudio'];
   }
   else if ($req['tipo'] == 'A'){
      $auxiliares++;
      $aux_pubs +=$req['pub'];
      $aux_videos += $req['video'];
      $aux_horas += $req['horas'];
      $aux_revisitas += $req['revisita'];
      $aux_estudios += $req['estudio'];
   }

	$tot_pub += $req['pub'];
	$tot_video += $req['video'];


	//PUBLICADORES BAJA ACTIVIDAD
   $j=$i-1;
	if($req['horas'] < $baja_actividad){
		$tabla_baja_actividad[$j]['nombre'] = $req['nombre'];
      $tabla_baja_actividad[$j]['horas'] = $req['horas'];
      $tabla_baja_actividad[$j]['grupo'] = $req['grupo'];
   }
	//PUBLICADORES SIN ESTUDIOS
	if($req['estudio'] < 1){
      $tabla_sin_estudios[$j]['nombre'] = $req['nombre'];
      $tabla_sin_estudios[$j]['grupo'] = $req['grupo'];
   }


}
//totales
$tot_horas = $pub_horas + $reg_horas + $aux_horas;
$tot_revisitas = $pub_revisitas + $reg_revisitas + $aux_revisitas;
$tot_estudios = $pub_estudios + $reg_estudios + $aux_estudios;
$total_baja_actividad = count($tabla_baja_actividad);
$total_sin_estudios = count($tabla_sin_estudios);
$tot_publicadores = $i;
//SI NO HAY REGISTROS EVITAR LA DIVISION POR CERO
if ($i==0)
	$i=1;

$prom_pub = round($tot_pub/$i, 1);
$prom_video = round($tot_video/$i, 1);
$prom_horas = round($tot_horas/$i, 1);
$prom_revisitas = round($tot_revisitas/$i, 1);
$prom_estudios = round($tot_estudios/$i, 1);

$db->close();
?>



   <div id="grupo">

	   <h5>VALORES DEL MES:</h5>
	   <table id ="tabla-estadisticas-mes">
		   <tr>
			   <th></th>
			   <th id="celda-centrada">PUB</th>
			   <th id="celda-centrada">VIDEO</th>
			   <th id="celda-centrada">HORAS</th>
			   <th id="celda-centrada">REVISITA</th>
			   <th id="celda-centrada">ESTUDIO</th>
		   </tr>
		<tr>
			<td>TOTAL</td>
			<td id="celda-centrada"><?php echo $tot_pub ?></td>
			<td id="celda-centrada"><?php echo $tot_video ?></td>
			<td id="celda-centrada"><?php echo $tot_horas ?></td>
			<td id="celda-centrada"><?php echo $tot_revisitas ?></td>
			<td id="celda-centrada"><?php echo $tot_estudios ?></td>
		</tr>
		<tr>
			<td>PROM.</td>
			<td id="celda-centrada"><?php echo $prom_pub ?></td>
			<td id="celda-centrada"><?php echo $prom_video ?></td>
			<td id="celda-centrada"><?php echo $prom_horas ?></td>
			<td id="celda-centrada"><?php echo $prom_revisitas ?></td>
			<td id="celda-centrada"><?php echo $prom_estudios ?></td>
		</tr>
	</table>

	<h5>PUBLICADORES CON BAJA ACTIVIDAD:
	<?php echo '  '. $total_baja_actividad. ' ['.round($total_baja_actividad*100/$i).'%]';  ?></h5>
   <?php 
      if (count($tabla_baja_actividad) < 10){
         echo '<table id="baja-actividad"> 
		         <tr>
			      <th>NOMBRE</th>
			      <th>HORAS</th>
               </tr>';
         foreach ($tabla_baja_actividad as $value) {
            echo '<tr>
            <td>'.$value['nombre'].'</td>
            <td>'.round($value['horas'], 1).'</td>
            </tr>';      
         }
         echo '</table>';      
      }else{
         echo '<p id="sin-estudios">';
		   foreach ($tabla_baja_actividad as $value) {
            echo $value['nombre']." (".$value['horas'].") -- ";
         }
         echo '</p>';
      }
   ?>
   
	<h5>PUBLICADORES SIN ESTUDIOS:
	<?php echo '  '. $total_sin_estudios.' ['.round($total_sin_estudios*100/$i).'%]';  ?></h5>
	<p id="sin-estudios">
		<?php
         sort($tabla_sin_estudios);
			foreach ($tabla_sin_estudios as $value) {
				echo $value['nombre'].' -- ';
			}

		?>
	</p>

</div>

   <div id = "publicador">
      <div id="iframe-estadisticas-publicador" frameborder="0">
          <h5>PUBLICADOR</h5>
          <select name="" id="pubselect"></select>
          <div id="resultado"></div>
      </div>
   </div>
   
   <div id = "precursores">
      <h5>PRECURSORES REGULARES: <?php echo mysqli_num_rows($consulta1) ?></h5>
      <table>
         <tr>
            <th>Nombre</th>
            <th>Total</th>
            <th>Prom</th>
            <th>Meses</th>
            <th>Faltantes</th>
         </tr>
         <tbody>
         <?php
               if(strcmp($_SESSION['tipousuario'],"secretario") == 0){
                  while($preReg=mysqli_fetch_array($consulta1)){
                     if($preReg['promedio']<70)
                        echo '<tr>';
                     else
                        echo '<tr style="color: darkblue; font-weight:bold">';
                        echo "<td>".$preReg['nombre']."</td>
                              <td>".$preReg['suma']."h</td>
                              <td>".round($preReg['promedio'],1)."</td>
                              <td>".$preReg['meses']."</td>
                              <td>".$preReg['faltantes']."</td>
                              </tr>";
                  }
               }
            ?>
         </tbody>
      </table>
      <h5>PRECURSORES AUXILIARES: <?php echo mysqli_num_rows($consulta2) ?> </h5>
      <table>
         <tr><thead>
            <th>Nombre</th>
            <th>Total</th>
            <th>Prom</th>
            <th>Meses</th>
            <th>Faltantes</th>
         </thead></tr>
         <tbody>
            <?php
               if(strcmp($_SESSION['tipousuario'],"secretario") == 0){
                  while($preReg=mysqli_fetch_array($consulta2)){
                     if($preReg['promedio']<50)
                        echo '<tr>';
                     else
                        echo '<tr style="color: darkblue; font-weight:bold">';
                        echo "<td>".$preReg['nombre']."</td>
                              <td>".$preReg['suma']."h</td>
                              <td>".round($preReg['promedio'],1)."</td>
                              <td>".$preReg['meses']."</td>
                              <td>".$preReg['faltantes']."</td>
                              </tr>";
                  }
               }
            ?>
         </tbody>
      </table>
   </div>

   <div id = "datosjw">

     <h5>PUBLICADORES</h5>
     <table>
         <tr>
            <th>Num Informantes</th>
            <th>Pub</th>
            <th>Videos</th>
            <th>Horas</th>
            <th>Revisitas</th>
            <th>Estudios</th>
         </tr>
         <tr>
            <td><?php echo $publicadores; ?></td>
            <td><?php echo $pub_pubs ?></td>
            <td><?php echo $pub_videos ?></td>
            <td><?php echo $pub_horas ?></td>
            <td><?php echo $pub_revisitas ?></td>
            <td><?php echo $pub_estudios ?></td>
         </tr>
      </table>
      <h5>PRECURSORES REGULARES</h5>
     <table>
         <tr>
            <th>Num Informantes</th>
            <th>Pub</th>
            <th>Videos</th>
            <th>Horas</th>
            <th>Revisitas</th>
            <th>Estudios</th>
         </tr>
         <tr>
            <td><?php echo $regulares ?></td>
            <td><?php echo $reg_pubs ?></td>
            <td><?php echo $reg_videos ?></td>
            <td><?php echo $reg_horas ?></td>
            <td><?php echo $reg_revisitas ?></td>
            <td><?php echo $reg_estudios ?></td>
         </tr>
      </table>
      <h5>PRECURSORES AUXILIARES</h5>
     <table>
         <tr>
            <th>Num Informantes</th>
            <th>Pub</th>
            <th>Videos</th>
            <th>Horas</th>
            <th>Revisitas</th>
            <th>Estudios</th>
         </tr>
         <tr>
            <td><?php echo $auxiliares; ?></td>
            <td><?php echo $aux_pubs ?></td>
            <td><?php echo $aux_videos ?></td>
            <td><?php echo $aux_horas; ?></td>
            <td><?php echo $aux_revisitas; ?></td>
            <td><?php echo $aux_estudios; ?></td>
         </tr>
      </table>
      <h5>TOTAL DE PUBLICADORES</h5>
      <table>
         <tr><td>Total Informantes en el Mes</td><td> <?php  echo $informantes ?></td>
         </tr>
         <tr><td>Total Publicadores Registrados</td><td> <?php echo $tot_publicadores ?></td>
         </tr>
      </table>

   </div>

  

 