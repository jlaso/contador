<?php

/*
 * Probado con IE8, Safari 5.1.2, FF 9.0.1, Chrome 16.0 en Win-XP
 */

// este archivo es un ejemplo de una situación real

include "h.php";    // esto establece las cokies para animar el contador
                    // el visitante ve que el contador pasa del último numero 
                    // de visitas cuando estuvo a las actuales

?>
<html> 
    <head>
     
    <title>Demo de contador | m&aacute;s info en http://jaitec.net/blog/contador-visitas</title>
    <!--script type="text/javascript" src="firebug-lite/build/firebug-lite.js"></script-->
    <!-- thanks to Dean Edwards -->
    <!--[if lt IE 7]>
        <script src="/ie7/ie7.js" type="text/javascript"></script>
    <![endif]-->
    <!--[if IE 8]>
        <script src="/ie7/ie8.js" type="text/javascript"></script>
    <![endif]-->
    <!--[if IE 9]>
        <script src="/ie7/ie9.js" type="text/javascript"></script>
    <![endif]-->    

    </head>
    
    <body>
        
    <div>
        Contador:                        
        <div id ="contador"></div>
    </div>
    <?php print $contador->showAnim('contador');  ?>    
  </body>
</html>
