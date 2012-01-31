README

Este contador utiliza una tabla mysql para registrar las visitas de cada IP por fecha,
de tal manera que si la IP ya ha visitado la web y han transcurrido más de 24h entonces
se incrementa en uno el número de visitas. 

La cuenta total del número de visitas se produce al sumar todas las IP's de la tabla, 
procedimiento muy rápido porque se realiza con un SELECT count(cuenta).

En la próxima revisión quiero incorporar la gestión de almacenamiento de visitas por fechas
(y horas) de tal manera que el webmaster pueda tener una estimación de las visitas por
tramos. Quedará de parte de ese webmaster presentar la información según sus necesidades.

Se prevé guardar una cookie con el valor de las visitas a la web, de tal manera que en la 
próxima visita se vea la animación desde el número de visitas de la última vez hasta las que
hubiera ahora.

De momento no son parametrizables en la llamada algunos valores que luego si se pretender
configurar.

Joseluis Laso 

Probado con los siguientes navegadores

en W-XP: IE8, Safari 5.1.2, FF 9.0.1, Chrome 16.0

si encuentras algún navegador en el que test.php no funcione correctamente por favor, hazme
saber navegador, versión y S.O. para poder solventarlo, gracias.