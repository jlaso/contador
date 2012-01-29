<style type="text/css">
#contador{
    display:inline;
}
.digitJlaso {
    background-image:url('digitos.png');
    background-repeat:no-repeat;
    width:30px;
    height:45px;
    vert-align:middle;
    padding-top:30px;
}
</style>

<script type="text/javascript">

    /*
     * Objeto contador
     * 
     */
    function Contador(
            desde,      // desde que número vamos a contar
            hasta,      // hasta que número vamos a contar
            digitos,    // digitos de resolución del contador (pone ceros delante)
            milisegs,   // milisegs que transcurren entre cada iteración de animación
            miDiv,      // miDiv, ID del div donde hay que presentar el contador
            pathImg,    // ruta de las imágenes 
            altoImg,    // alto de cada dígito dentro de la tira de imágenes
            offsetImg   // offset del cero dentro de la tira de imágenes
        ){
        this.stop     = hasta;
        this.digitos  = digitos;
        this.milisegs = milisegs;
        this.miDiv    = miDiv;
        this.pathImg  = pathImg;
        this.altoImg  = altoImg;
        this.offsetImg= offsetImg;
        this.actualP  = new Array();
        this.stopP    = 0; 
        this.actual   = desde.toString();
        for (var i=this.actual.length;i<this.digitos;i++){
            this.actual = "0"+this.actual;
        }  
    }

    Contador.prototype = {
        show: function(){
            var a = this.actual.split("");
            var contenedor = document.getElementById(this.miDiv);
            //contenedor.innerHTML += this.actual + "<br/>";
            var e = this.digitos-1;
            var valor=0;
            for (i=0;i<a.length;i++){
                valor = parseInt(a[i]) * this.altoImg + this.offsetImg;
                contenedor.innerHTML += //a[i]+
                    "<span id='dig"+e+"' class='digitJlaso' style='background-position: 0px -"+valor+"px;'>"+
                    "    <img src='"+this.pathImg+"/transp30x45.png'/>"+
                    "</span>";
                e--;
            }                        
        },
        animarDigito:function(){
            this.actualP[0]++; 
            var dig = document.getElementById("dig0");
            dig.style.backgroundPosition="0 -"+this.actualP[0]+"px"; 
            // mirar si es un nueve para incrementar el de al lado tambien
            d=0;
            while(this.actualP[d]>9*this.altoImg+this.offsetImg){
                d++;
                this.actualP[d]++; 
                var dig = document.getElementById("dig"+d);
                dig.style.backgroundPosition="0 -"+this.actualP[d]+"px"; 
            }
            if (this.actualP[0]>this.stopP){
                clearInterval(this.interval);
                if (this.actual<this.stop-1){
                    this.actual++;
                    this.animar();
                }
            }
        },
        animar: function(){   
            if (this.actual<this.stop){ 
                var a = this.actual.toString().split("").reverse();
                for (i=0;i<a.length;i++){
                    this.actualP[i] = this.altoImg * a[i] + this.offsetImg;                        
                }
                this.stopP = this.actualP[0] + this.altoImg; 
                var that = this;
                this.interval = setInterval(function(){that.animarDigito();}, this.millisecs);
            }
        }
    }
    
    /* modelo de llamada
    window.onload = function(){
       var contador = new Contador(0,999999,6,100,"contenedor","inc/contador",42,17);
       contador.show();
       contador.animar();
    }
    */
</script>