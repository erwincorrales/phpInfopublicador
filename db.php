<?php

class db {

	private $server ='localhost';
	private $user = 'e1maxdb';
	private $pass = 'db$e1maX';
	private $nameDB = 'infopublicador';
	private $conex;

	public function __construct(){
		$this->server;
		$this->user;
		$this->pass;
		$this->nameDB;
		$this->conex;
	}

	public function conectarDB(){
		$this->conex = mysqli_connect($this->server, $this->user, $this->pass, $this->nameDB) or die("NO SE CONECTA A LA BASE DE DATOS");
	}

	public function consulta($sql){
		$var=mysqli_query($this->conex,$sql);
		return $var;
	}
	public function close(){
		mysqli_close($this->conex);
	}

   public function initDB(){

    //CREAR TABLA DE USUARIO PASSWORD
    $sql1 = "CREATE TABLE IF NOT EXISTS `infopublicador`.`usuario` ( `orden` INT NOT NULL AUTO_INCREMENT , `user` VARCHAR(50) NOT NULL , `pass` VARCHAR(50) NOT NULL , `nombre` VARCHAR(100) NOT NULL , `congregacion` INT NOT NULL , `grupo` INT NOT NULL, `tipo` VARCHAR(30) NOT NULL, `correo` VARCHAR(50) NOT NULL , PRIMARY KEY (`orden`)) ENGINE = InnoDB CHARACTER SET latin1 COLLATE latin1_spanish_ci";

      //CREAR LA TABLA PUBLICADORES
    $sql2 = "CREATE TABLE IF NOT EXISTS `infopublicador`.`pub` ( `num` INT NOT NULL AUTO_INCREMENT , `congregacion` INT NOT NULL , `grupo` INT NOT NULL , `nombre` VARCHAR(50) NOT NULL , `telefono` BIGINT NOT NULL , `direccion` VARCHAR(100) NOT NULL , `correo` VARCHAR(50) NOT NULL, `tipo` TEXT NOT NULL , `info` INT NOT NULL , `passcode` VARCHAR(50) NOT NULL,
		PRIMARY KEY (`num`)) ENGINE = InnoDB CHARACTER SET latin1 COLLATE latin1_spanish_ci COMMENT = 'DATOS DE PUBLICADORES'";

      //CREACION DE TABLA REGISTROS DE INFORMES--------------------------------------------------------------
    	$sql3 = "CREATE TABLE IF NOT EXISTS `infopublicador`.`registro` (`orden` INT NOT NULL AUTO_INCREMENT , `num` INT NOT NULL , `pub` INT NOT NULL , `video` INT NOT NULL , `horas` DECIMAL NOT NULL , `revisita` INT NOT NULL , `estudio` INT NOT NULL , `comentario` VARCHAR(100) NOT NULL ,`tipopub` VARCHAR(1) NOT NULL, `fecha` VARCHAR(10) NOT NULL , PRIMARY KEY (`orden`)) ENGINE = InnoDB CHARACTER SET latin1 COLLATE latin1_spanish_ci COMMENT = 'BITACORA DE REGISTROS'";

			//CREACION DE GRUPOS DE PREDICACION EN CONGREGACION------------------------------------------------------------------------
			$sql4 = "CREATE TABLE IF NOT EXISTS `infopublicador`.`grupos` ( `id` INT NOT NULL AUTO_INCREMENT , `nombregrupo` VARCHAR(50) NOT NULL ,`congregacion` INT NOT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB";

			//CREAR TABLA DE CONGREGACIONES -----------------------------------------------------
			$sql5 = "CREATE TABLE IF NOT EXISTS`infopublicador`.`congregacion` ( `id` INT NOT NULL , `nombre_congregacion` VARCHAR(30) NOT NULL , `ciudad` VARCHAR(30) NOT NULL , `pais` VARCHAR(30) NOT NULL , `mailsecretario` VARCHAR(50) NOT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB";

  		//CREAR USUARIO POR DEFECTO pass = md5(2020)
    	$sql6 = "SELECT * FROM `usuario`";
    	$sql7 = "INSERT INTO `usuario` (`orden`, `user`, `pass`, `nombre`, `congregacion`, `grupo`, `tipo`, `correo`) VALUES ('1', 'e1max', 'susanadb1', 'Erwin David Corrales De La Rosa', '1', '1', 'secretario', 'e1max@hotmail.com' )";

    	//CREAR CONGREGACION POR DEFECTO
    	$sql8 = "SELECT * FROM `congregacion`";
    	$sql9 = "INSERT INTO `congregacion` (`id`, `nombre_congregacion`, `ciudad`, `pais`) VALUES ('2', 'Torices', 'Cartagena', 'Colombia')";

    	// CREAR GRUPO POR DEFECTO
			$sql10 = "SELECT * FROM `grupos`";
    	$sql11 = "INSERT INTO `grupos` (`id`, `nombregrupo`, `congregacion`) VALUES ('1', 'Manzanillo', 1)";


    	//CREAR TABLAS NECESARIAS POR EL PROGRAMA
  		$this->consulta($sql1);
	  	$this->consulta($sql2);
	    $this->consulta($sql3);
	    $this->consulta($sql4);
	    $this->consulta($sql5);

	   	//VERIFICAR SI EXISTE USUARIO EN TABLA USUARIO ADMINISTRADOR POR DEFECTO
	  	$req=$this->consulta($sql6);
	  	$req=mysqli_num_rows($req);
	  	if ($req == 0){
	  		$this->consulta($sql7);
	   	}

	   	//VERIFICAR SI EXISTE CONGREGACION EN TABLA CONGREGACION, CONGREGACION POR DEFECTO
	  	$req=$this->consulta($sql8);
	  	$req=mysqli_num_rows($req);
	  	if ($req == 0){
	  		$this->consulta($sql9);
	   	}

	   	// VERIFICAR TABLA GRUPOS Y SI NO HAY GRUPO ALTOBOSQUE POR DEFECTO
	   	$req=$this->consulta($sql10);
	  	$req=mysqli_num_rows($req);
	  	if ($req == 0){
	  		$this->consulta($sql11);
	   	}
   }
}

error_reporting(0);
$db = new db();
$db->conectardb();

//CREA TABLAS NECESARIAS PARA OPERAR APP SI NO ESTAN CREADAS PRIMER ARRANQUE DE Programa
//comentar luego del funcionamiento correcto de las tablas.
// $db->initDB();