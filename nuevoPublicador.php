<?php
include 'seguridad.php';
include 'db.php';

$nombre = '';
$tel='';
$direccion='';
$correo ='';
$tipo = '';
$info = 0;
$congregacion = $_SESSION['congregacion'];

if($_SESSION['tipousuario'] == 'grupo')
    $admin = 0;
else
    $admin = 1;


//CARGAR PUBLICADOR PARA EDITAR	
if (isset($_REQUEST['num'])){
    $num = $_REQUEST['num'];

    //cargar el publicador a editar 
    $sql = 'SELECT * FROM `pub` WHERE `num` = '.$num;
    $query=$db->consulta($sql);

    while ($req = mysqli_fetch_array($query)){
        $nombre = $req['nombre'];
        $tel = $req['telefono'];
        $direccion = $req['direccion'];
        $correo = $req['email'];
        $fNacimiento = $req['fNacimiento'];
        $fBautismo = $req['fBautismo'];
        $tipo = $req['tipo'];
        $grupo = $req['grupo'];
        $info = $req['info'];
        $congrepublicador = $req['congregacion'];
    }
}else
    $num = '';

//SELECCIONAR GRUPOS PARA LISTBOX
$sql = "SELECT * FROM grupos WHERE grupos.congregacion = '$congregacion' ORDER BY nombregrupo ASC";
$grupos = $db->consulta($sql);
$db->close();

//insertar grupos y valores a respuesta
$i=0;
while ($req=mysqli_fetch_array($grupos)){
    $id[$i] = $req['id'];
    $nombregrupo[$i] = $req['nombregrupo'];
    $i++;
}

 //ENVIAR DATOS A LA VISTA
    $resp = [
        'nombre' => $nombre,
        'num' => $num,
        'tel' => $tel,
        'direccion' => $direccion,
        'correo' => $correo,
        'tipo' => $tipo,
        'grupo' => $grupo,
        'fNacimiento' => $fNacimiento,
        'fBautismo' => $fBautismo,
        'congrepublicador' => $congrepublicador,
        'nombregrupo' => $nombregrupo,
        'id' => $id,
        'admin' => $admin
    ];


echo json_encode($resp);
?>




