<?php

session_start();

// aqui llamar a los includes de funciones o configuracion necesarios

require_once (dirname(__FILE__).'/contador.php');

// ---- en el caso de Idiorm tomar este valor ----
// $orm = ORM::get_db();

// ---- en otro caso tomar este valor, poner el host y dbname a vuestros valores ----
$orm = new PDO('mysql:host=localhost;dbname=contador','root','');

$contador = new Contador($orm); 

if (isset($_COOKIE['jContador'])) // lo pongo 2 unidades atrás para que se vea la animación
    $contador->setTotalAnt($_COOKIE['jContador']-2);  // en un caso real dejar sin -2

setcookie('jContador',$contador->getTotal());

// más código vuestro necesario para inicializar

?>