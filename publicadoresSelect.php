<?php
    include 'seguridad.php';
    include 'db.php';
    

    $congregacion = $_SESSION['congregacion'];
    $grupo = $_SESSION['grupo'];

    //LLENAR EL LISTBOX DE PUBLICADORES
    if (strcmp($_SESSION['tipousuario'],"grupo") == 0)
        $sql = "SELECT nombre, num FROM pub where pub.congregacion = $congregacion and pub.grupo = $grupo order by nombre asc";
    else
        $sql = "SELECT nombre, num FROM pub where pub.congregacion = $congregacion order by nombre asc";

    $consulta = $db->consulta($sql);
    $db->close();
    
    $resp;
    $i=0;
    
    while($req=mysqli_fetch_array($consulta)){
        $resp[$i] = array($req[0],$req[1]);
        $i++;
    }


    $resp = json_encode($resp); 
    echo $resp;

?>