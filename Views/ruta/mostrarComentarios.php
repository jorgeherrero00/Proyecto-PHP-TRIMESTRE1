<div class="container">

<?php if (!empty($comentarios)) : ?>
    <h2>Comentarios</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Comentario</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comentarios as $c) : ?>
                <tr>
                    <td><?= $c['nombre'] ?></td>
                    <td><?= $c['fecha'] ?></td>
                    <td><?= $c['texto'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>No hay comentarios disponibles.</p>
<?php endif; ?>
</div>
