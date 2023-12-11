<div class="container">

<?php if (!empty($rutasEncontradas)) : ?>
    <h2>Resultados de la Búsqueda</h2>
    <ul>
        <?php foreach ($rutasEncontradas as $ruta) : ?>
        
                
            <table border="1">
        <tr>
            <th>Titulo</th>
            <th>Descripción</th>
            <th>Desnivel</th>
            <th>Distancia</th>
            <th>Notas</th>
            <th>Dificultad</th>
        </tr>
            <tr>
                <td><?= $ruta['titulo'] ?></td>
                <td><?= $ruta['descripcion'] ?></td>
                <td><?= $ruta['desnivel'] ?></td>
                <td><?= $ruta['distancia'] ?></td>
                <td><?= $ruta['notas'] ?></td>
                <td><?= $ruta['dificultad'] ?></td>
            </tr>
    </table>

            
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>No se encontraron rutas.</p>
<?php endif; ?>
</div>