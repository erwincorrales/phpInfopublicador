<?php
   
   function locksql($dato){
      $dato = addslashes($dato);
      $dato = htmlspecialchars($dato);
      $dato = trim($dato);
      
      return $dato;
   }

?>