<?php

use Controllers\RutasController;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

$todasLasRutas = RutasController::obtenerRutas();

// Configurar Pagerfanta con el adaptador de matriz
$adapter = new ArrayAdapter($todasLasRutas);
$paginador = new Pagerfanta($adapter);

// Establecer el número máximo de elementos por página
$elementosPorPagina = 3;
$paginador->setMaxPerPage($elementosPorPagina);

$paginaActual = $_GET['page'] ?? 1;

// Validar y configurar el paginador para la página actual
try {
    $paginador->setCurrentPage($paginaActual);
} catch (\Pagerfanta\Exception\OutOfRangeCurrentPageException $e) {
    header('Location: ?page=' . $paginador->getNbPages());
    exit();
}

?>

<div class="container">

    <table border="1">
        <tr>
            <th>Titulo</th>
            <th>Descripción</th>
            <th>Desnivel</th>
            <th>Distancia</th>
            <th>Notas</th>
            <th>Dificultad</th>
        </tr>
        <?php foreach ($paginador->getCurrentPageResults() as $ruta): ?>
            <tr>
                <td><?= $ruta['titulo'] ?></td>
                <td><?= $ruta['descripcion'] ?></td>
                <td><?= $ruta['desnivel'] ?></td>
                <td><?= $ruta['distancia'] ?></td>
                <td><?= $ruta['notas'] ?></td>
                <td><?= $ruta['dificultad'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Mostrar la paginación -->
    <div class="pagination">
        <?php if ($paginador->hasPreviousPage()): ?>
            <a href="?page=<?= $paginador->getPreviousPage() ?>">Anterior</a>
        <?php endif; ?>

        <?php for ($page = 1; $page <= $paginador->getNbPages(); $page++): ?>
            <?php if ($page == $paginador->getCurrentPage()): ?>
                <span><?= $page ?></span>
            <?php else: ?>
                <a href="?page=<?= $page ?>"><?= $page ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($paginador->hasNextPage()): ?>
            <a href="?page=<?= $paginador->getNextPage() ?>">Siguiente</a>
        <?php endif; ?>
    </div>

</div>

