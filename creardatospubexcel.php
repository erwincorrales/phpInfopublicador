<?php
include 'seguridad.php';
include 'db.php';
require_once 'Classes/PHPExcel.php';

//CALCULAR FECHA DEL MES ANTERIOR:

setlocale(LC_TIME,"es_CO");
$mes =array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO',
			'AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');

$fecha = date('m', strtotime('-29 day'));
$fecha=$mes[$fecha-1];
$fecha=$fecha." ".date('Y', strtotime('-29 day'));


//CONSULTA DEL MES

if(date('d') <30){
   $fecha_inicial=date('Y-m',strtotime('-1 month')).'-30';
   $fecha_final=date('Y-m').'-29';   
   
}
else{
   $fecha_inicial = date('Y-m').'-30';
   $fecha_final = date('Y-m',strtotime('+1 month')).'-01';
}


//HACER EL INFORME DEL GRUPO DEL MES O TOTAL SUPERUSUARIO
if($_SESSION['tipousuario']=="secretario")
	$sql = "SELECT `nombre`, `telefono`, `direccion`, `fNacimiento`, `fBautismo`, `tipo`, `nombregrupo` FROM pub INNER JOIN grupos on pub.grupo = grupos.id WHERE pub.congregacion = '".$_SESSION['congregacion']."' ORDER BY grupo, nombre ASC";
else
	$sql = "SELECT `nombre`, `grupo`, `telefono`, `direccion`, `fNacimiento`, `fBautismo`, `tipo`, `nombregrupo` FROM pub INNER JOIN grupos on pub.grupo = grupos.id WHERE pub.congregacion = '".$_SESSION['congregacion']."' AND pub.grupo ='".$_SESSION['grupo']."' ORDER BY nombre ASC";


$consulta=$db->consulta($sql);

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

// Agregar Informacion
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A3', 'Nombre')
->setCellValue('B3', 'Direccion')
->setCellValue('C3', 'Telefono')
->setCellValue('D3', 'FNacimiento')
->setCellValue('E3', 'FBautismo')
->setCellValue('F3', 'Tipo')
->setCellValue('G3', 'Grupo');
    


$row=4;

while($req=mysqli_fetch_array($consulta)){
	$objPHPExcel->getActiveSheet()
	->setCellValue('A'.$row, $req['nombre'])
	->setCellValue('B'.$row, $req['direccion'])
	->setCellValue('C'.$row, $req['telefono'])
	->setCellValue('D'.$row, $req['fNacimiento'])
	->setCellValue('E'.$row, $req['fBautismo'])
	->setCellValue('F'.$row, $req['tipo'])
	->setCellValue('G'.$row, $req['nombregrupo']);
	$row++;
}


//CELDAS CON NEGRITAS
$objPHPExcel->getActiveSheet()->getStyle("A1:G3")->getFont()->setBold(true);


//AGREGAR autoajuste
for ($col = 'A'; $col != 'H'; $col++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
}
//Colocar bordes
$objPHPExcel->getActiveSheet()->getStyle(
    'A3:' . 
    $objPHPExcel->getActiveSheet()->getHighestColumn(). 
    $objPHPExcel->getActiveSheet()->getHighestRow()
)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//CENTRAR VALORES DE PUBLICADORES
$objPHPExcel->getActiveSheet()->getStyle(
	'B3:'.
	$objPHPExcel->getActiveSheet()->getHighestColumn().
	$objPHPExcel->getActiveSheet()->getHighestRow()
	)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//Combinar Celdas y poner titulo
$objPHPExcel->getActiveSheet()->setCellValue('A1', "DATOS PUBLICADORES DEL GRUPO ".$nombre_grupo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');

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
$objPHPExcel->getActiveSheet()->setTitle('DATOS PUBLICADORES');

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

// ENCABEZADO SI SE VA DESCARGAR ARCHIVO CON NAVEGADOR
//header('Content-Disposition: attachment;filename="informeManzanillo.xlsx"');
//header('Cache-Control: max-age=0');
//$objWriter->save('php://output');

//GUARDAR ARCHIVO EN SERVIDOR 
//$objWriter->save(__DIR__."/informe".$nombre_grupo.".xlsx");
?>
