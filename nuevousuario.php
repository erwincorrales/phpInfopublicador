<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <title>REGSTRESE</title>
   <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
   <link rel="stylesheet" href="css/estilo.css">
   </head>
<body>
   <h3>REGISTRESE</h3>
   
   
   
   <form action="" method="post">
      <div>
         Este programa esta diseñado para llevar de <b>forma ordenada y con estadisticas</b>  los informes de los publicadores de su congregacion.<br>

         Puede utilizar el programa a nivel grupal como estrategia del <b> secretario de la congregacion</b> , o puede utilizarlo <b>individualmente</b>.
      </div>
      <br>
      <h5>Como lo utilizara?</h5>
      <div style="display:flex; justify-content:center;">
         <label for="op1">SecretarioCongregacion</label>
         <input type="radio" id="op1" name="opcion" value="Secretario" checked>
         <label for="op2">Personal</label>
         <input type="radio" id="op2" name="opcion" value="personal" >
      </div>
      <br>
      <h5>Datos:</h5>
      <input type="email" name="mail" placeholder='Correo Electronico' id="" required>
      <input type="text" name="nombre" placeholder='Nombre' id="" required>
      <input type="password" name="pass" placeholder='Contraseña' id="pass1" required>
      <input type="password" name="pass2" placeholder='Repita Contraseña' id="pass2" required>
      <input type="text" name="congregacion" placeholder='Nombre Congregacion' id="" required>
      <input type="text" name="ciudad" placeholder='Ciudad' id="" required>
      <input type="text" name="pais" placeholder='Pais' id="" required>
      <br>
      <input  type="button" value="REGISTRAR" onclick="" >
      
   </form>
 <br> 
<footer>
&copy;&nbsp;e1maxSystemas 2016   ---- infopub@e1max.co
</footer>   
   
</body>
</html>