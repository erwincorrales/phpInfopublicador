<?php
include 'seguridad.php';
include 'db.php';
require_once 'Classes/PHPExcel.php';

//CALCULAR FECHA DEL MES ANTERIOR:

setlocale(LC_TIME,"es_CO");
$mes =array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO',
			'AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');

$fecha = date('m', strtotime('-29 day'));

if ($fecha >= 2){ //marzo en adelante
    $fecha1=$mes[$fecha-1];
    $fecha2=$mes[$fecha-2];
}
else if ($fecha ==1){ //febrero
    $fecha1=$mes[$fecha-1];
    $fecha2=$mes[11];
}
else if($fecha == 0){ //enero
    $fecha1=$mes[11];
    $fecha2=$mes[10];
}

//echo $fecha1."  ".$fecha2." ".$fecha." ". $mes[1];


$fecha1=$fecha1." ".date('Y', strtotime('-29 day'));
$fecha2=$fecha2." ".date('Y', strtotime('-29 day'));


//CONSULTA DEL MES

if(date('d') <30){
   $fecha_inicial1=date('Y-m',strtotime('-1 month')).'-30';
   $fecha_inicial2=date('Y-m',strtotime('-2 month')).'-30';
   $fecha_final=date('Y-m').'-29';   
   
}
else{
   $fecha_inicial1 = date('Y-m',strtotime('-1 month')).'-30';
   $fecha_inicial2 = date('Y-m',strtotime('-2 month')).'-30';
   $fecha_final = date('Y-m',strtotime('+1 month')).'-01';
}


//HACER EL INFORME DEL GRUPO DEL MES O TOTAL SUPERUSUARIO
if($_SESSION['tipousuario']=="secretario" ){
    	$sql = "SELECT `nombre`, `pub`, `video`, `horas`, `revisita`, `estudio`, `comentario`, `tipo` , `nombregrupo` FROM pub INNER JOIN grupos on pub.grupo = grupos.id LEFT JOIN registro ON pub.num = registro.num and registro.fecha >= '$fecha_inicial1' AND registro.fecha <= '$fecha_final' where pub.congregacion = '".$_SESSION['congregacion']."' ORDER BY grupo, nombre ASC";
    	$sql1 = "SELECT `nombre`, `pub`, `video`, `horas`, `revisita`, `estudio`, `comentario`, `tipo` , `nombregrupo` FROM pub INNER JOIN grupos on pub.grupo = grupos.id LEFT JOIN registro ON pub.num = registro.num and registro.fecha >= '$fecha_inicial2' AND registro.fecha <= '$fecha_inicial1' where pub.congregacion = '".$_SESSION['congregacion']."' ORDER BY grupo, nombre ASC";
        
}
else{
	$sql = "SELECT `nombre`, `pub`, `video`, `horas`, `revisita`, `estudio`, `comentario`, `tipo` , `nombregrupo` FROM pub INNER JOIN grupos on pub.grupo = grupos.id LEFT JOIN registro ON pub.num = registro.num and registro.fecha >= '$fecha_inicial1' AND registro.fecha <= '$fecha_final' where  pub.congregacion = '".$_SESSION['congregacion']."' and pub.grupo ='".$_SESSION['grupo']."' ORDER BY nombre ASC";    
	$sql1 = "SELECT `nombre`,  `pub`, `video`, `horas`, `revisita`, `estudio`, `comentario`, `tipo`, `nombregrupo` FROM pub INNER JOIN grupos on pub.grupo = grupos.id LEFT JOIN registro ON pub.num = registro.num and registro.fecha >= '$fecha_inicial2' AND registro.fecha <= '$fecha_inicial1' where  pub.congregacion = '".$_SESSION['congregacion']."' and pub.grupo ='".$_SESSION['grupo']."' ORDER BY nombre ASC";    
}


$consulta=$db->consulta($sql);
$consulta1=$db->consulta($sql1);

//CONSULTA NOMBRE DEL GRUPO
//Agregar nombre de grupo a archivo
$nombre_grupo = strtoupper($_SESSION['nombregrupo']);

//CREAR EL ARCHIVO DE EXCEL
// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();
// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Erwin Corrales")
->setLastModifiedBy("Erwin Corrales")
->setTitle("informe De predicacion")
->setSubject("Documento Excel de Prueba")
->setDescription("Registro de predicacion")
->setKeywords("Excel Office 2007 openxml php")
->setCategory("Excel");


//-----------------     HOJA 1           ----------------------------

// Agregar Informacion
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A3', 'Nombre')
->setCellValue('B3', 'Pub')
->setCellValue('C3', 'Videos')
->setCellValue('D3', 'Horas')
->setCellValue('E3', 'Revisitas')
->setCellValue('F3', 'Estudios')
->setCellValue('G3', 'Observacion')
->setCellValue('H3', 'Tipo')
->setCellValue('I3', 'Grupo');



$row=4;

while($req=mysqli_fetch_array($consulta)){
	$objPHPExcel->getActiveSheet()
	->setCellValue('A'.$row, $req['nombre'])
	->setCellValue('B'.$row, $req['pub'])
	->setCellValue('C'.$row, $req['video'])
	->setCellValue('D'.$row, $req['horas'])
	->setCellValue('E'.$row, $req['revisita'])
	->setCellValue('F'.$row, $req['estudio'])
	->setCellValue('G'.$row, $req['comentario'])
	->setCellValue('H'.$row, $req['tipo'])
    ->setCellValue('I'.$row, $req['nombregrupo']);    
	$row++;
}

//Agregar totales
$objPHPExcel->getActiveSheet()
->setCellValue('A'.$row, 'TOTALES')
	->setCellValue('B'.$row, '=sum(B4:B'.($row-1).')')
	->setCellValue('C'.$row, '=sum(C4:C'.($row-1).')')
	->setCellValue('D'.$row, '=sum(D4:D'.($row-1).')')
	->setCellValue('E'.$row, '=sum(E4:E'.($row-1).')')
	->setCellValue('F'.$row, '=sum(F4:F'.($row-1).')');

//CELDAS CON NEGRITAS
$objPHPExcel->getActiveSheet()->getStyle("A1:I3")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':I'.$row)->getFont()->setBold(true);

//AGREGAR autoajuste
for ($col = 'A'; $col != 'J'; $col++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
}
//Colocar bordes
$objPHPExcel->getActiveSheet()->getStyle(
    'A3:' . 
    $objPHPExcel->getActiveSheet()->getHighestColumn() . 
    $objPHPExcel->getActiveSheet()->getHighestRow()
)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//CENTRAR VALORES DE PUBLICADORES
$objPHPExcel->getActiveSheet()->getStyle(
	'B3:'.
	$objPHPExcel->getActiveSheet()->getHighestColumn().
	$objPHPExcel->getActiveSheet()->getHighestRow()
	)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//Combinar Celdas y poner titulo
$objPHPExcel->getActiveSheet()->setCellValue('A1', "INFORME ".$nombre_grupo." MES ".$fecha1);
$objPHPExcel->getActiveSheet()->mergeCells('A1:I1');

//Agregar footprint
$objPHPExcel->getActiveSheet()
->setCellValue('A'.($row+2), "Dsgn by e1maxSystems 2016")
->setCellValue('A'.($row+3), "infopublicador v2.0")
->setCellValue('A'.($row+4), "infopub.e1max.co")
->getCell('A'.($row+4))->getHyperlink()->setUrl('http://infopub.e1max.co');

//configurar estilos hipervinculo
$hipervinculo_estilo =[
'font' =>[
	'color' => ['rgb' => '0000FF'],
	'underline' => 'single'
	]
];

$objPHPExcel->getActiveSheet()
->getStyle('A'.($row+2))->getFont()->setbold(true);
$objPHPExcel->getActiveSheet()
->getStyle('A'.($row+4))->applyFromArray($hipervinculo_estilo);


// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('MES '.$fecha1);


//-----------------     HOJA 2     MES ANTERIOR           ----------------------------
$objPHPExcel->createSheet(1);

// Agregar Informacion
$objPHPExcel->setActiveSheetIndex(1)
->setCellValue('A3', 'Nombre')
->setCellValue('B3', 'Pub')
->setCellValue('C3', 'Videos')
->setCellValue('D3', 'Horas')
->setCellValue('E3', 'Revisitas')
->setCellValue('F3', 'Estudios')
->setCellValue('G3', 'Observacion')
->setCellValue('H3', 'Tipo')
->setCellValue('I3', 'Grupo');



$row=4;

while($req=mysqli_fetch_array($consulta1)){
	$objPHPExcel->getActiveSheet()
	->setCellValue('A'.$row, $req['nombre'])
	->setCellValue('B'.$row, $req['pub'])
	->setCellValue('C'.$row, $req['video'])
	->setCellValue('D'.$row, $req['horas'])
	->setCellValue('E'.$row, $req['revisita'])
	->setCellValue('F'.$row, $req['estudio'])
	->setCellValue('G'.$row, $req['comentario'])
	->setCellValue('H'.$row, $req['tipo'])
	->setCellValue('I'.$row, $req['nombregrupo']);
	$row++;
}

//Agregar totales
$objPHPExcel->getActiveSheet()
->setCellValue('A'.$row, 'TOTALES')
	->setCellValue('B'.$row, '=sum(B4:B'.($row-1).')')
	->setCellValue('C'.$row, '=sum(C4:C'.($row-1).')')
	->setCellValue('D'.$row, '=sum(D4:D'.($row-1).')')
	->setCellValue('E'.$row, '=sum(E4:E'.($row-1).')')
	->setCellValue('F'.$row, '=sum(F4:F'.($row-1).')');

//CELDAS CON NEGRITAS
$objPHPExcel->getActiveSheet()->getStyle("A1:I3")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':I'.$row)->getFont()->setBold(true);

//AGREGAR autoajuste
for ($col = 'A'; $col != 'J'; $col++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
}
//Colocar bordes
$objPHPExcel->getActiveSheet()->getStyle(
    'A3:' . 
    $objPHPExcel->getActiveSheet()->getHighestColumn() . 
    $objPHPExcel->getActiveSheet()->getHighestRow()
)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//CENTRAR VALORES DE PUBLICADORES
$objPHPExcel->getActiveSheet()->getStyle(
	'B3:'.
	$objPHPExcel->getActiveSheet()->getHighestColumn().
	$objPHPExcel->getActiveSheet()->getHighestRow()
	)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//Combinar Celdas y poner titulo
$objPHPExcel->getActiveSheet()->setCellValue('A1', "INFORME ".$nombre_grupo." MES ".$fecha2);
$objPHPExcel->getActiveSheet()->mergeCells('A1:I1');

//Agregar footprint
$objPHPExcel->getActiveSheet()
->setCellValue('A'.($row+2), "Dsgn by e1maxSystems 2016")
->setCellValue('A'.($row+3), "infopublicador v2.0")
->setCellValue('A'.($row+4), "infopub.e1max.co")
->getCell('A'.($row+4))->getHyperlink()->setUrl('http://infopub.e1max.co');

//configurar estilos hipervinculo
$hipervinculo_estilo =[
'font' =>[
	'color' => ['rgb' => '0000FF'],
	'underline' => 'single'
	]
];

$objPHPExcel->getActiveSheet()
->getStyle('A'.($row+2))->getFont()->setbold(true);
$objPHPExcel->getActiveSheet()
->getStyle('A'.($row+4))->applyFromArray($hipervinculo_estilo);


// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('MES '.$fecha2);



$objPHPExcel->setActiveSheetIndex(0);

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

// ENCABEZADO SI SE VA DESCARGAR ARCHIVO CON NAVEGADOR
//header('Content-Disposition: attachment;filename="informeManzanillo.xlsx"');
//header('Cache-Control: max-age=0');
//$objWriter->save('php://output');

//GUARDAR ARCHIVO EN SERVIDOR 
//$objWriter->save(__DIR__."/informe".$nombre_grupo.".xlsx");
?>
