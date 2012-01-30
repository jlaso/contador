<html> 
    <head>
        
    <?php require(dirname(__FILE__).'/contador.inc.php'); ?>
    
    </head>
    
    <body>
        
    <div>
        Contador:                        
        <div id ="contador"></div>
    </div>
  </body>
    <script type="text/javascript">
       window.onload = function(){
           var contador = new Contador(0,999999,6,100,"contador","/contador",42,17);
           contador.show();
           contador.animar();
       }
    </script>
</html>
