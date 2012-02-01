<?php

/*
 * estructura de la tabla que alberga el contador
  
  CREATE TABLE  `contador` (
     `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
     `ip` CHAR( 20 ) CHARACTER SET ASCII COLLATE ascii_general_ci NOT NULL ,
     `fecha` TIMESTAMP NOT NULL ,
     `cuenta` INT NOT NULL ,
      UNIQUE (`ip`)
  ) ENGINE = INNODB;
  
 */

@define ('ERR_ORM_NO_ACTIVO','ORM no está activo');

class Contador {
    
   private $orm = null;
   private $error = '';
   // segundos que han de transcurrir para que se considere una nueva visita
   private $lapso = 86400; // 24h * 60m * 60s;
   // path de las imagenes, relativas al index
   private $pathImg = 'inc/contador';
   // total acumulado, para no tener que hacer la consulta cada vez
   private $total = -1;
   // total anterior a la actualización para poder hacer animiación del cambio
   private $totalAnt = -1;
 
   public function __construct(PDO $orm, $lapso = 86400, $pathImg='inc/contador'){
       $this->lapso = $lapso;
       $this->pathImg = $pathImg;
       if ($orm){
           $this->orm = $orm;
           $this->totalAnt = $this->total($orm);
           $this->inc($orm);
           $this->total = $this->total($orm);
           return 1;
       }else{
           $this->error = ERR_ORM_NO_ACTIVO;
           return 0;
       }
   }
   
   /* si no se integra con Slim / IdiOrm, hacer esto antes de llamar a contador
    *       
    * $orm = new mysqli($dbhost,$dbuser,$dbpass,$dbdb);
    *
    */
   
   public function getError(){ return $this->error; }
   
   public function __destruct() {
       //if ($this->orm) $orm->close();
   }
   
   public function clientIp(){
        if (isset($_SERVER['HTTP_X_FORWARDER_FOR']) && $_SERVER['HTTP_X_FORWARDER_FOR'] != ''){
           $ip = (!empty($_SERVER['REMOTE_ADDR']))?$_SERVER['REMOTE_ADDR']:
            ((!empty($_ENV['REMOTE_ADDR']))?$_ENV['REMOTE_ADDR']:"unknow"); 
           $entries = split('[, ]', $_SERVER['HTTP_X_FORWARDER_FOR']);
           reset($entries);
           while (list(,$entry) = each($entries)){
               $entry = trim($entry);
               if (preg_match("/^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/",$entry,$ip_list)){
                  $private_ip = array(
                      '/^0\./',
                      '/^127\.0\.0\.1/',
                      '/^192\.168\..*/',
                      '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
                      '/^10\..*/');
                  $found_ip = preg_replace($private_ip, $ip, $ip_list[1]);

                  if ($ip != $found_ip){
                      $ip = $found_ip;
                      break;
                  }
               }
           }
        }else{
           $ip = (!empty($_SERVER['REMOTE_ADDR']))?$_SERVER['REMOTE_ADDR']:
            ((!empty($_ENV['REMOTE_ADDR']))?$_ENV['REMOTE_ADDR']:"unknow");         
        }    
        return $ip;
    }
    
    public function inc(PDO $conn){        
        $id = 0; $cant = 0;
        $ip = self::clientIp();
        $sql = "SELECT id, ip, cuenta,UNIX_TIMESTAMP(fecha) fecha FROM contador WHERE ip = '{$ip}'";
        if ($conn){
            try{
                $result = $conn->query($sql);
                if ($row = $result->fetchObject()){
                    $cant = $row->cuenta+1;
                    if ( date ("U") - $row->fecha > $this->lapso ){
                        $sql = "UPDATE contador SET cuenta = ".$cant." WHERE `id` = {$row->id}";
                        $conn->query($sql);
                    }
                }else{
                    $cant = 1;
                    $sql = "INSERT INTO contador (ip,cuenta) VALUES ('{$ip}',1)";    
                    $conn->query($sql);            
                }
            }catch(Exception $e){
                $this->error = $e->getMessage();
            }
        }
        
    }
    
    public function total(PDO $conn){
        $sql = "SELECT sum(cuenta) total FROM contador";
        if ($conn){
            $result = $conn->query($sql);
            $row = $result->fetchObject();
            return $this->total =  $row->total;
        }else{
            $this->error = ERR_ORM_NO_ACTIVO;
            return $this->total = -1;
        }
    }
    
    public function show(){
        if ($this->total == -1) $this->total();
        $cuenta = sprintf("%06s",$this->total);
        $contador = '';
        for($i=0;$i<strlen($cuenta);$i++) {
            $imagen = substr($cuenta,$i,1);
            $contador .= "&nbsp;<img alt='{$imagen}' src='{$this->pathImg}/{$imagen}.gif'>";
        }
        return $contador;
    }

    public function show2(){
        if ($this->total == -1) $this->total();
        $cuenta = sprintf("%06s",$this->total);
        $contador = '<!--div id="contadorJlaso" style="background-image:url(\''.$this->pathImg.'/fondo.png\');background-repeat:no-repeat;"-->
                        <style type="text/css">
                            .digitJlaso {
                                background-repeat:no-repeat;
                                width:30px;
                                height:45px;
                                _position:absolute;
                                vert-align:middle;
                                padding-top:30px;
                            }
                        </style>';
        for($i=strlen($cuenta)-1;$i>=0;$i--) {
            $imagen = substr($cuenta,-$i-1,1);
            $posic  = -42 *  (ord($imagen) - ord("0"));
            $contador .= "<span alt='{$imagen}' id='digitJlaso{$i}' class='digitJlaso' style=\"background-image:url('{$this->pathImg}/digitos.png');background-position:0 {$posic}px;\"><img src='{$this->pathImg}/transp30x45.png'/></span>";
            }
        $contador .= '<!--/div-->
            <!--script type="text/javascript">
                var dig = document.getElementById("digitJlaso0");
                //alert(dig.style.backgroundPosition);
                var actual = 42, stop = 84;
                function animarDigitJlaso(){
                    dig.style.backgroundPosition="0 -"+actual+"px";
                    //alert(dig.style.backgroundPosition);
                    actual++;       
                    if (actual==stop) clearInterval(intJlaso);
                }
                var intJlaso = setInterval("animarDigitJlaso()", 150);
            </script-->';
        return $contador;
    }
    public function showAnim($midiv){
        $html = file_get_contents($this->pathImg.'/contador.inc.php');
        $html = str_replace("'digitos.png'", "'".$this->pathImg."/digitos.png'", $html);
        $html .= '<script type="text/javascript">
                       window.onload = function(){
                           var contador = new Contador('.$this->totalAnt.','.
                                                         $this->total.',
                                                         6, 100, "'.$midiv.'", "'.
                                                         $this->pathImg.'",42,17);
                           contador.show();
                           contador.animar();
                       }            
                  </script>';
        return $html;
    }
    public function incAndGetTotAsImage(PDO $conn,$dir='inc/contador'){
        self::inc($conn);
        $total = self::total($conn);
        return self::show($total,$dir);
    }
    
}

?>
