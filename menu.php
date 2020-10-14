<?php
   include 'seguridad.php';
   include 'db.php';
   

    //definir select para grupos
    if (strcmp($_SESSION['tipousuario'],"secretario") == 0){
        $sqlx = "SELECT nombregrupo from grupos where grupos.congregacion = ".$_SESSION['congregacion']." order by nombregrupo asc";    
        $query = $db->consulta($sqlx);
    }
    
    $db->close();

    //CALCULAR FECHA DEL MES ANTERIOR:
    setlocale(LC_TIME,"es_CO");
    $mes =array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO',
			'AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');

    $fecha = date('m', strtotime('-28 day'));
    $fecha=$mes[$fecha-1];
    $fecha=$fecha." ".date('Y', strtotime('-28 day'));
    $mailsecretario = $_SESSION['mailsecretario']

?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
	<title>LISTADO DE PUBLICADORES</title>
	<link rel="stylesheet" href="css/estilo.css">
	<link rel="stylesheet" href="css/fontello.css">
	<link rel="icon" type="image/png" href="images/favicon.png"/>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<!--	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
<!--<script>-->
<!--  (adsbygoogle = window.adsbygoogle || []).push({-->
<!--    google_ad_client: "ca-pub-7832000734227518",-->
<!--    enable_page_level_ads: true-->
<!--  });-->
<!--</script>-->
</head>
<body>

<div id="contenedor-principal">
<!--  contiene la barra lateral  -->
  <div id = "sidebar">
    	<nav> 
            
            <h3>INFORME GRUPO PUBLICADOR <label class ="icon-menu" for="menu-button"></label></h3>
            <div class="menu">
                <a id="menu_a" href="#">USUARIO</a>
                <?php

                // SESSION DE USUARIO UNICO
                if ($_SESSION['grupo']== -1)
                echo '<a id="menu_a" href="config_grupo.php">CONFIG. GRUPO PREDICACION</a>';
                //SESION ADMINISTRADOR
                if ($_SESSION['tipousuario'] == "secretario")
                echo '<a id="menu_a" href="#"> GRUPO-USUARIOS</a>';
            ?>
                <input id="cerrar-sesion-btn" type="button" name="btn4" value="CERRAR SESION" onclick="location='logout.php'">
            </div>
	    </nav>

    <div id="div-menu">
        <img id="imagen-menu" src="images/banner.jpg"  alt="">
        <div>
            <input type="button" name="btn1" value="VER LISTADO- INGRESO DE INFORME">

            <!--      HANILITAR SI ERES ADMINISTRADOR O CLIENTE INDEPENDIENTE  -->
                <?php if($_SESSION['grupo'] < 0 or strcmp($_SESSION['tipousuario'], "secretario") == 0)
                    echo '
                        <input type="button" name="btn2" value="AGREGAR/EDITAR NUEVO PUBLICADOR"
                            >
                    ';
                ?>

            <input type="button" name="btn3" value="ESTADISTICAS PUBLICADOR/GRUPO">
            <input type="button" name="btn4" value="EXPORTAR TABLA-ENVIAR EMAIL">

            <p id="mes-menu"><?php echo 'MES '.$fecha ?></p>

            <table class="inicial">
            <tr>
                <td id="jusTd">Entregados:</td>
                <td></td>
            </tr>
            <tr>
                <td id="jusTd">Faltantes:</td>
                <td></td>
            </tr>
            </table>
        </div>

    </div>
    <footer>
        <div id="session">
            <?php echo strtoupper($_SESSION['nombre'].'<br>'.$_SESSION['nombregrupo'].' - '.$_SESSION['nombrecongregacion']);
                ?>
        </div>
        <div id="copy">
            &copy;&nbsp;e1maxSystemas 2019
        </div>
    </footer>
 </div>
  
<!--  contiene la seccion de contenido router-oulet-->
  <div id="contenido">
   
    <div id="listado-principal">
       <h3 >LISTADO
           <img class="busca" src="images/lupa.png" alt="BuscarPub">
           <label id="label-faltantes" for="faltantes"><input type="checkbox" name="faltantes" id="faltantes" checked> Faltantes</label>
           <label id="label-az" for="az"><input type="checkbox" name="az" id="az" checked> A-Z</label>
       </h3>
         
       <div id= "busqueda">
            <input type="text" id="buscar" placeholder="Buscar Publicador">
            <select name="grupos" id="select-grupos">
                <?php 
                        if (strcmp($_SESSION['tipousuario'],"secretario") == 0){
                            echo '<option value="FFFFF" checked>Todos Grupos</option>';        
                            while ($rex = mysqli_fetch_row($query)){
                                echo '<option value="'.$rex[0].'">'.$rex[0].'</option>';
                            }
                            
                        }else {
                            echo '<option value="'.$_SESSION['nombregrupo'].'" checked>'.$_SESSION['nombregrupo'].'</option>';
                            echo '<option value="FFFFF">Todos Grupos</option>';
                        }
                ?>   
            </select>    
        </div>


      <div id="div-listado-pub">
      </div>
          
        <section>
             <h5>LEYENDA</h5>
             <span>Publicador</span>
             <span style=" color:#8c3504">&thinsp;&thinsp;Precursor. Regular</span>
             <span style=" color:#d85206">&thinsp;&thinsp;Precursor. Auxiliar</span>
             <span style=" color:#b72a6c">&thinsp;&thinsp;Sin Grupo</span>
             <input class ="button_back" type="button" value="VOLVER A MENU">
        </section>

        <div id='resumen-publicador'>
            <h3>ERWIN CORRALES</h3>
            <div style='padding: 0 7px'>
                <table>
                        <tr>
                            <td style='text-align:left'>Dirección</td>
                            <td style='text-align: right'></td>
                        </tr>
                        <tr>
                            <td style='text-align:left'>Teléfono</td>
                            <td style='text-align: right'></td>
                        </tr>
                        <tr>
                            <td style='text-align:left'>Correo</td>
                            <td style='text-align: right'></td>
                        </tr>
                        <tr>
                            <td style='text-align:left'>Fecha Nacimiento</td>
                            <td style='text-align: right'>
                                <span></span>
                                <span style='color: darkgreen'></span>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align:left'>Fecha Bautismo</td>
                            <td style='text-align: right'>
                                <span></span>
                                <span style='color: darkgreen'></span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button style='padding: 5px 40px'>Editar&nbsp;Datos&nbsp;Publicador</button>
                            </td>
                        </tr>
                </table>
            </div>
            <br>
            <div id='resumen-anio'>
                
            </div>
            <button>CERRAR</button>
        </div>
    </div>
    
    <div id="editar-publicador">
       <form autocomplete="off">
        <h3><i class="icon-user-plus"></i>&nbsp;&nbsp;INGRESE NUEVO PUBLICADOR</h3>  
        <input name="nombre" type="text" required="" placeholder="NOMBRE" >
        <input name="tel" type="tel" placeholder="TELEFONO" >
        <input name="direccion" type="text" placeholder="DIRECCION">
        <input name="correo" type="email" placeholder="CORREO ELECTRONICO">
        <label for='fNacimiento' style='margin-top: 10px'>Fecha Nacimiento</label>
        <div style='display: flex; '>
            <input name='fNacimiento' type='date' placeholder="aaaa-mm-dd">
            <span style='padding-left: 10px; color: blue;'></span>
        </div> 
        <label for='fBautismo'>Fecha Bautismo</label> 
        <div style='display: flex'>
            <input name='fBautismo' type='date' placeholder="aaaa-mm-dd">
            <span style='padding-left: 10px; color: green;'></span>
        </div>

        <label id="label-grupo" for="select-grupo">GRUPO:</label>
        <select name="select-grupo">
        </select>		
        <div id="div-nuevopublicador">
       
            <input name="radio" id="radio1" type="radio" value="">
            <label for="radio1">Pub</label>
       
            <input name="radio" id="radio2" type="radio" value="A">
            <label for="radio2">PrecAuxiliar</label>
      
            <input name="radio" id="radio3" type="radio" value="R">
            <label for="radio3">PrecRegular</label>
   
            <input type="hidden" name="num">
        </div>

        <input name="guardar" type="submit" value="GUARDAR REGISTRO">
        <input class="button_back" type="button" value="VOLVER" >
        </form>
    </div>
    
    <div id="informe">
       
        <h3></h3>
        <form autocomplete="off">
            <label for="pub">Publicaciones:</label>
            <input name="publicaciones" type="number" placeholder="Publicaciones Impresas/Digitales">
            <label for="video">Videos:</label>
            <input name="video" type="number" placeholder="Videos">
            <div style="display: -webkit-flex">
              <div>
                <label for="Horas">Horas:</label>
                <input name="horas" type="number" step="1" min="0" placeholder="Horas">
              </div>
              <div>
                <label for="Horas">Minutos:</label>
                <input name="minutos" type="number" step="1" min="0" placeholder="minutos">
              </div>
            </div>
            <div id='creditoHoras'>
                <label for="creditHoras">Crédito Horas:</label>
                <input name="creditHoras" type="number" step="1" min="0" placeholder="Crédito Horas">
            </div>
            <label for="revisitas">Revisitas:</label>
            <input name="revisitas" type="number" placeholder="Revisitas">
            <label for="estudios">Estudios:</label>
            <input name="estudios" type="number" placeholder="Estudios">
            <label for="comentario">Comentarios:</label>
            <input name="comentario" type="text" placeholder="Novedad o Comentario">
            <input type="hidden" name="fecha">
            <input type="hidden" name="agregar_actualizar">
            <input type="hidden" name="tipo">
            <input type="hidden" name="num">
            <br>
            <p>Ingresar/editar informe publicador</p>
           
            <input name="submit" type="submit" value="GUARDAR INFORME">
            <input type="button" name="btn" value="VOLVER" onclick="mostrarprincipal()">
        </form>
  
    </div>
    
    <div id="exportar-datos">
        <h3><i class = "icon-share"></i> EXPORTAR TABLA DE PUBLICADORES</h3>
  
	   <div id="iframe-datos" frameborder="0"></div>

	<br>
	
	<div id="exportardatos-op">
	    <h5>Enviar Correo a Secretario u otro:</h5>
       <form id="correo" action="correo.php" method="POST">
        
        <input type="email" required name="correo" placeholder="direccion correo"  <?php echo 'value="'.$mailsecretario.'"' ?> >	
        <input type="submit" value = "Enviar" onclick="this.disabled = true; this.value='Enviando...';this.form.submit()" >
       
 	   </form>
 	   
 	    
        <?php
        if ($correo)
           echo "<div id = 'alert_ok'>El Correo ha Sido enviado!</div>";
        ?>
 	    
	    <h5>Exportar a Excel:</h5>
		<input style="font-family: fontello;" type="button" name="btn" value= "&#xe800 REGISTRO MES EXCEL" onclick="location='exportarexcel.php'">
		<input style="font-family: fontello;" type="button" name="btn" value= "&#xe800 Datos Contacto Publicadores" onclick="location='exportardatospubexcel.php'">
		<br>
		
		<input class="button_back" type="button" name="btn" value="VOLVER A MENU">
	</div>
    </div>
    
    <div id="usuario">
      <div>
           <h3><i class = "icon-user-plus"></i>&nbsp;&nbsp;CONFIGURACION DE ACCESO</h3>

           <form autocomplete="off">

               <h5>Cambia tus credenciales de acceso al programa</h5>
               <br>
                <label for="">USUARIO:</label>
                <input type="text" name="user" id="user">
                <label for="">Nuevo Password:</label>
                <input id="p1" type="password" name="pass">
                <label for="">Repita Password:</label>
                <input id="p2" type="password" name="pass2">
                <label for="correo">Correo:</label>
                <input type="email" id="mail" name="correo" required>
                <br><br>
                <label for="nombre">Nombre del usuario:</label>
                <input type="text" name="nombre" required>
                <input type="hidden" name="num">
                <input type="submit" id='submitbutton' value="CONFIGURAR">
                <input class="button_back" type="button" name="btn" value="VOLVER A MENU">

            </form>
     </div>
   </div>  
    
    <div class ="nuevogrupo">
        <h3>AGREGA/EDITAR GRUPO PREDICACION</h3>
        <form >
            <label for="nombreGrupo">Nombre Grupo:</label>
            <input type="text" placeholder="Ingresa Nombre de Grupo" name="nombreGrupo" value="" required>
            <input type="hidden" name="num" value="">
            <br>
            <br>
            <div style="display:flex">
                <input type="submit" value="CONFIGURAR" >
                <input type="button" class="borrar" value="Eliminar">
            </div>
            <input type="button" value="VOLVER A MENU">
        </form>
    </div>                    

    <div id="conf_user">
       
        <h3><i class="icon-user-plus">&nbsp;</i>CONFIG GRUPOS - USUARIOS <label class ="icon-menu"></label></h3>
        <span></span>
        
        <div id="contenedor">
            <!-- Agregamos informacion del servidor -->
        </div> 
        <input class = "button_back" type="button" value="VOLVER A MENU">
    </div> 
    
    <div id="editar_user">
       <h3>
           <i class = "icon-user-plus"></i>&nbsp;&nbsp;CONFIGURACION DE ACCESO
           <label class ="icon-menu" for="menu-button"></label>
       </h3>
       <form id="formNuevoUser" autocomplete="off">
            <h5>Cambia tus credenciales de acceso al programa</h5>
            <label for="">USUARIO:</label>
            <input type="text" name="user">
            <span></span>
            <label for="">Nuevo Password:</label>
            <input type="password" name="pass">
            <label for="">Repita Password:</label>
            <input type="password" name="pass2">
            <label for="correo">Correo:</label>
            <input type="email"  name="correo" required>
            <span></span>

            <label for="">Tipo de Usuario:</label>
            <select name="tipousuario">
                <option value="">--Selecciona tipo ---</option>
                <option value="grupo">Siervo De Grupo</option>
                <option value="anciano">Anciano</option>
                <option value="secretario">Secretario</option>
            </select>

            <label for="nombre">Nombre del usuario:</label>
            <input type="text" name="nombre" required>

            <input type="hidden" name="num">
            <div style="display:flex">
                <input type="submit" value="CONFIGURAR" >
                <input type="button" class="borrar" value="Eliminar">
            </div>
            
            <input type="button" name="btn" value="VOLVER A MENU">
       </form>
    </div> 
    
    <div id="estadisticas">
        <h3><i class="icon-chart-line"></i>	&nbsp;ESTADISTICAS GRUPO-PUBLICADOR<label class ="icon-menu" for="menu-button"></label></h3>
        
        <div id ="opciones_estadistica">
                <input type="button" name="" value="GRUPO" onclick="mostrargrupo()">
                <input type="button" name="" value="PUBLICADOR" onclick="mostrarpublicador()">

            <?php
                if ($_SESSION['tipousuario'] == "secretario"){
                    echo '<input type="button" name="" value="PRECURSORES" onclick="mostrarprecursores()">'; 
                    echo '<input type="button" name="" value="DATOS JW" onclick="mostrardatosjw()">';
                }
            ?>
        </div>
        <div id="contenedor">
            <div id="grupo">

                <h5>VALORES DEL MES:</h5>
                <table id="tabla-estadisticas-mes">
                    <tbody>
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
                            <td id="celda-centrada"></td>
                            <td id="celda-centrada"></td>
                            <td id="celda-centrada"></td>
                            <td id="celda-centrada"></td>
                            <td id="celda-centrada"></td>
                        </tr>
                        <tr>
                            <td>PROM.</td>
                            <td id="celda-centrada"></td>
                            <td id="celda-centrada"></td>
                            <td id="celda-centrada"></td>
                            <td id="celda-centrada"></td>
                            <td id="celda-centrada"></td>
                        </tr>
                    </tbody></table>

                <h5>PUBLICADORES CON BAJA ACTIVIDAD: 72 [50%]</h5>
                <p id="sin-estudios"></p>   
                <h5>PUBLICADORES SIN ESTUDIOS:92 [63%]</h5>
                <p id="sin-estudios"></p>
            </div>         
            <div id="publicador">
                Graphs soon
                <!-- <div id="iframe-estadisticas-publicador" frameborder="0">
                <h5>PUBLICADOR</h5>
                <select name="" id="pubselect"><option value="0">Escoge Publicador</option><option value="296">Adela PÃ©rez</option><option value="303">Adonei Castro Perez</option><option value="171">Alba Edith Garcia</option><option value="266">Alberto Angulo</option><option value="271">Alexandra GÃ³mez TÃ¡mara</option><option value="616">Alfonso Orlando Ortiz B</option><option value="208">Alfonso Suarez</option><option value="282">Alvaro MuÃ±oz</option><option value="155">Alvaro Salas</option><option value="203">Ana DÃ­az Moreno</option><option value="576">Ana EchavarrÃ­a Cassiani</option><option value="162">Ana Raquel Mercado</option><option value="478">Angel Echenique</option><option value="272">Angelica Martinez</option><option value="299">Anibal Simancas</option><option value="544">Aurori Castellano</option><option value="163">Beatriz Olivares</option><option value="288">Betty Barclay</option><option value="159">Betty Bustos Montalvo</option><option value="577">Brayan Iturriago</option><option value="276">Candelaria Carrasquiel Garcia</option><option value="565">Caridad Caballero</option><option value="141">Carlos Beltran</option><option value="273">Carlos Tafur Gomez</option><option value="146">Carmen Alicia Caballero</option><option value="260">Carmen Elena Orjuela Rojas</option><option value="172">Carmen Gomez</option><option value="176">Chavelis Marina Jimenez Hernandez</option><option value="235">Cristina Peinado Caballero</option><option value="304">Danis De Castro</option><option value="545">David Castellanos</option><option value="149">Delvis Casiani Cimarra</option><option value="261">Denis Frias</option><option value="297">Diana Gomez</option><option value="192">Dina Rojas Frias</option><option value="226">Domingo Banquez Lamadrid</option><option value="264">Domingo Jose Banquez</option><option value="289">Dormelina Martinez</option><option value="538">Edith Collazo Puello</option><option value="301">Eduardo Luis Peinado</option><option value="156">Elias BriceÃ±o</option><option value="215">Ella Cecilia Mendoza EscandÃ³n</option><option value="302">Emmanuel JosÃ© Castro Meza</option><option value="195">Emperatriz Barrios</option><option value="239">Erwin Corrales</option><option value="188">Esther PiÃ±erez</option><option value="165">Esther Ursela</option><option value="138">Frank Alcazar</option><option value="243">Gustavo Jimenez</option><option value="257">Heinar Elias Narvaez Gaviria</option><option value="169">Hortencia De La Rosa</option><option value="475">Irma Borelly</option><option value="575">Jaidiver EchavarrÃ­a</option><option value="147">Jakelin Caballero Suarez</option><option value="291">Jeissy Torres Garcia</option><option value="157">Jenny BriseÃ±o</option><option value="628">JesÃºs Angulo</option><option value="279">JosÃ© Galeano</option><option value="154">Jose D. Hernandez</option><option value="270">Jose Figueroa</option><option value="247">Josue BriseÃ±o</option><option value="238">Juan Corrales</option><option value="513">Juan Peinado</option><option value="209">Karolay Suarez</option><option value="558">Katia Ortiz HernÃ¡ndez</option><option value="232">Katrin Cassiani Barboza</option><option value="476">Kerit Echenique</option><option value="285">Krisly Morales</option><option value="177">Lidia Montes Ramos</option><option value="246">Ligia Urshela De Ortiz</option><option value="170">Lilia De La Rosa</option><option value="489">Luigui Arellano</option><option value="199">Luis Caballero Arellano</option><option value="204">Luis Diaz Torres</option><option value="265">Luis Martin Torres</option><option value="161">Luz Estela Jimenez</option><option value="198">Luz Marina Benitez Mejia</option><option value="579">Maira julio Guerra</option><option value="588">MarÃ­a Fernanda Figueroa</option><option value="259">Marelvi Angulo</option><option value="181">Margarita Contreras</option><option value="269">Margarita Corrales</option><option value="481">Maria Bustos lambis</option><option value="143">Maria Caballero</option><option value="251">Maria Elena MuÃ±oz</option><option value="244">Maria Perez</option><option value="180">Marlon Contreras</option><option value="580">Marta Meza</option><option value="144">Martha Caballero</option><option value="290">Martha Torres Barreiro</option><option value="221">Mary Luz Castilo Perez</option><option value="300">Maryuris Simancas Wilcox</option><option value="218">Maxima Casseres</option><option value="263">Melissa Banquez Martinez</option><option value="237">Merlys Castillo</option><option value="477">Milagros Echenique</option><option value="587">Milena Galeano</option><option value="223">Nancy Chico</option><option value="256">Nasli Atencia Lozano</option><option value="231">Nehemias Caballero Arellano</option><option value="483">Neila Florez</option><option value="190">Nelfa Polo</option><option value="213">Nelys Caraballo Valdez</option><option value="550">Nilson Arboleda</option><option value="574">Norver Galeano Narvaez</option><option value="284">Ofelia Castro Torres</option><option value="293">Pablo Rojas</option><option value="233">Petra Simarra CaÃ±ate</option><option value="210">Piedad Suarez AlcalÃ¡</option><option value="205">Rafael Morales Castro</option><option value="249">Rita Romero</option><option value="615">Robert Guanipa</option><option value="255">Roberto Caraballo Imitola</option><option value="222">Roberto Castillo</option><option value="571">RocÃ­o Fuentes Molina</option><option value="236">Rosa Salas Mercado</option><option value="173">Rosalba Gomez</option><option value="543">RubÃ©n Perdomo</option><option value="547">Ruth Castellanos</option><option value="546">Salvador Castellanos</option><option value="589">Samanta DÃ­az</option><option value="228">Samuel Banquez</option><option value="175">Samuel Gomez Tajan</option><option value="148">Santiago Caballero Suarez</option><option value="248">Sara Ortiz</option><option value="258">Sara Simancas</option><option value="294">Saray MuÃ±oz</option><option value="194">Sergio Rojas</option><option value="286">Shirley Barrios Gonzalez</option><option value="482">Siever Florez</option><option value="219">Sonia Esther Cassiani MuÃ±oz</option><option value="241">Susana Corrales</option><option value="151">Temilda Maria Gonzalez</option><option value="152">Teresa Gomez</option><option value="202">Valeria Diaz</option><option value="551">VÃ­ctor Ortiz</option><option value="225">Victor Santis</option><option value="494">Victoria Banquez</option><option value="160">Vivian De Jesus Castro Meza</option><option value="229">Wilberto Meza</option><option value="217">William Atencia</option><option value="480">Willian Bustos</option><option value="473">Willian David Atencia</option><option value="150">Wilson Diaz Rivero</option><option value="552">Yamili Desiree</option></select>
                <div id="resultado"></div>
                </div> -->
            </div>
            <div id="precursores">
                <h5>PRECURSORES REGULARES: 19</h5>
                <table>
                    <tbody>
                        <tr>
                            <th>Nombre</th>
                            <th>Total</th>
                            <th>Prom</th>
                            <th>Meses</th>
                            <th>Faltantes</th>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr style="color: darkblue; font-weight:bold">
                            <td>Betty Barclay</td>
                            <td>1039.00h</td>
                            <td>86.6</td>
                            <td>12</td>
                            <td>-199.00</td>
                        </tr>
                    </tbody>
                </table>
                <h5>PRECURSORES AUXILIARES: 2 </h5>
                <table>
                    <tbody>
                        <tr></tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Total</th>
                            <th>Prom</th>
                            <th>Meses</th>
                            <th>Faltantes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Danis De Castro</td>
                            <td>393.00h</td>
                            <td>32.8</td>
                            <td>12</td>
                            <td>207.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="datosjw">

                <h5>PUBLICADORES</h5>
                <table>
                    <tbody>
                        <tr>
                            <th>Num Informantes</th>
                            <th>Pub</th>
                            <th>Videos</th>
                            <th>Horas</th>
                            <th>Revisitas</th>
                            <th>Estudios</th>
                        </tr>
                    <tr>
                        <td>124</td>
                        <td>204</td>
                        <td>220</td>
                        <td>1226.75</td>
                        <td>330</td>
                        <td>51</td>
                    </tr>
                    </tbody>
                </table>
                <h5>PRECURSORES REGULARES</h5>
                <table>
                    <tbody>
                        <tr>
                            <th>Num Informantes</th>
                            <th>Pub</th>
                            <th>Videos</th>
                            <th>Horas</th>
                            <th>Revisitas</th>
                            <th>Estudios</th>
                        </tr>
                        <tr>
                            <td>19</td>
                            <td>48</td>
                            <td>117</td>
                            <td>1034</td>
                            <td>328</td>
                            <td>51</td>
                        </tr>
                    </tbody>
                </table>
                <h5>PRECURSORES AUXILIARES</h5>
                <table>
                    <tbody><tr>
                        <th>Num Informantes</th>
                        <th>Pub</th>
                        <th>Videos</th>
                        <th>Horas</th>
                        <th>Revisitas</th>
                        <th>Estudios</th>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>8</td>
                        <td>3</td>
                        <td>110</td>
                        <td>20</td>
                        <td>3</td>
                    </tr>
                    </tbody>
                </table>
                <h5>TOTAL DE PUBLICADORES</h5>
                <table>
                    <tbody>
                        <tr>
                            <td>Total Informantes en el Mes</td>
                            <td> 142</td>
                        </tr>
                        <tr>
                            <td>Total Publicadores Registrados</td>
                            <td> 145</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <input class = "button_back" type="button" value="VOLVER A MENU">
    </div>          
    
    <div id="comerciales">
       *********** ********************
    </div> 

  </div>   
  
  <div id="comerciales1">
      *****************************************************************
  </div>               
  <div id="ajaxCarga">
      <img id="ajaxGif" src="images/orbit-load.gif" alt="ConsultaAJAX espere...">
  </div>
  
  
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src = "js/index.js"></script>
</body>
</html>
