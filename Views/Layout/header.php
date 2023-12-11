<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Senderismo</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css">

</head>
<body>

    <div class="container">
    <h1><a href="<?= BASE_URL ?>index.php">Inicio</a></h1>
    <a href="<?= BASE_URL ?>Rutas/mostrarTodasLasRutas/">Todas las Rutas</a>
    <?php
    if(!isset($_SESSION['login'])){
        ?><a href="<?=BASE_URL?>usuario/login/">Iniciar Sesión</a>
        <a href="<?=BASE_URL?>usuario/registro/">Registrarse</a>
        <?php
    }?>
   
    <?php 
       if (isset($_SESSION['login'])) {
                ?><a href="<?=BASE_URL?>usuario/logout/">Cerrar Sesion</a>
                <h3><?=$_SESSION['login']->nombre.' '?><?=$_SESSION['login']->apellidos.' ' ?><?=$_SESSION['login']->rol?></h3>
    <?php }?>

    <!-- buscarRutas.php -->

<form action="<?= BASE_URL ?>Rutas/buscarRutas/" method="post">
    <label for="search">Buscar Rutas:</label>
    <input type="text" name="search" id="search" required>

    <button type="submit">Buscar</button>
</form>






    <?php if(isset($_SESSION['login']) &&$_SESSION['login']->rol == 'admin'):?>
        <li><a href="<?=BASE_URL?>Rutas/crearRuta/">Crear Ruta</a></li>
        <?php endif; ?>


    <?php use Controllers\RutasController; 
    $rutas = RutasController::obtenerRutas()?>
    <nav id="menu_cat">
        <ul>
        <?php foreach($rutas as $ruta): ?>
    <li>
    <p><?=$ruta['titulo']?> </p>
    <a href="<?= BASE_URL ?>Rutas/verDetallesRuta/?ruta_id=<?= $ruta['id'] ?>">
    

    <a href="<?= BASE_URL ?>ComentariosRutas/crearComentario/?ruta_id=<?= $ruta['id'] ?>">Comentar Ruta</a>

    <?php if(isset($_SESSION['login']) && $_SESSION['login']->rol == 'admin'):?>
        <form method="post" action="<?=BASE_URL?>Rutas/borrarRuta/">
            <input type="hidden" name="ruta_id" value="<?=$ruta['id']?>">
            <button type="submit">Borrar Ruta</button>
        </form>
        <form method="post" action="<?= BASE_URL ?>Rutas/modificarRuta/?ruta_id=<?= $ruta['id'] ?>">
            <input type="hidden" name="ruta_id" value="<?=$ruta['id']?>">
            <button type="submit">Modificar Ruta</button>
        </form>
    <?php endif;?>
</a>

    </li>
<?php 
endforeach;?>
        </ul>
    </nav>

    <p>Numero total de rutas: <?=count($rutas);?></p>

    <?php 
    $rutaMasLarga = RutasController::obtenerRutaMasLarga();

if ($rutaMasLarga) {
    ?><p>La ruta más larga es: <?=$rutaMasLarga['titulo']?> con una distancia de <?=$rutaMasLarga['distancia']?> kilómetros.</p><?php
} else {
    ?><p>No hay rutas disponibles</p><?php
}?>
</div>