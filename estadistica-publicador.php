<?php
include 'seguridad.php';
include 'db.php';

$grupo = $_SESSION['grupo'];
$congregacion = $_SESSION['congregacion'];


if(isset($_REQUEST['num']))
	$numpublicador =$_REQUEST['num'];
else
	$numpublicador=0;


//SACAR TOTAL Y PROMEDIO DE PUBLICADOR-------------------------------------------------------
$fecha_inicial = date('Y-m-d',strtotime('-12 month'));
$fecha_final = date('Y-m-d');

$sql="SELECT `pub`, `video`, `horas`, `revisita`, `estudio`, `fecha` from registro where num =".$numpublicador."  and registro.fecha >= '$fecha_inicial' and registro.fecha <= '$fecha_final' order by fecha asc";
$consulta1 = $db->consulta($sql);
$db->close();


$tot_pub =0; $tot_video =0; $tot_horas =0; $tot_revisitas=0; $tot_estudios =0;
$i=0;
while($req = mysqli_fetch_array($consulta1)){
	$i++;
	$tot_pub += $req['pub'];
	$tot_video += $req['video'];
	$tot_horas += $req['horas'];
	$tot_revisitas += $req ['revisita'];
	$tot_estudios += $req['estudio'];
}
//SI NO HAY REGISTROS EVITAR LA DIVISION POR CERO
if ($i==0)
	$i=1;

$prom_pub = round($tot_pub/$i, 1);
$prom_video = round($tot_video/$i, 1);
$prom_horas = round($tot_horas/$i, 1);
$prom_revisitas =round($tot_revisitas/$i, 1);
$prom_estudios = round($tot_estudios/$i, 1);
?>


<h5>INFORME PUBLICADOR POR MES:</h5>	
<table id ="tabla-estadisticas-mes">
	<tr>
		<th>FECHA</th><th>PUBS</th><th>VIDEO</th><th>HORAS</th><th>REVISITAS</th><th>ESTUDIOS</th>
	</tr>
		
	<?php
		mysqli_data_seek($consulta1,0);
		while($rq=mysqli_fetch_array($consulta1)){
			$date=$rq['fecha'];
			$date=strtotime('-1 month', strtotime($date));
			$date=date ('Y-M', $date);	

			echo   '<tr>
				  	<td>'.$date.'</td>
				  	<td>'.$rq['pub'].'</td>
					<td>'.$rq['video'].'</td>
					<td>'.round($rq['horas'],1).'</td>
					<td>'.$rq['revisita'].'</td>
					<td>'.$rq['estudio'].'</td>
					</tr>';	
		}
	?>
</table>
<h5>RESULTADO DE 12 MESES:</h5>
	<table id ="tabla-estadisticas-mes">
		<tr>
			<th></th>
			<th>PUB</th>
			<th>VIDEO</th>
			<th>HORAS</th>
			<th>REVISITAS</th>
			<th>ESTUDIOS</th>
		</tr>
		<tr>	
			<td>PROM.</td>
			<td><?php echo $prom_pub ?></td>
			<td><?php echo $prom_video ?></td>
			<td><?php echo $prom_horas ?></td>
			<td><?php echo $prom_revisitas ?></td>
			<td><?php echo $prom_estudios ?></td>
		</tr>
	</table>


