<div class="container">

<form action="<?= BASE_URL ?>Rutas/modificarRuta/" method="post">
        <!-- Mostrar los detalles de la ruta en los campos del formulario -->
        <input type="hidden" name="ruta_id" value="<?= $rutaDetalles['id'] ?>">
        <label for="titulo">Titulo</label>
        <input type="text" name="titulo" value="<?= $rutaDetalles['titulo'] ?>">

        <label for="descripcion">Descripcion</label>
        <input type="text" name="descripcion" value="<?= $rutaDetalles['descripcion'] ?>">

        <label for="desnivel">Desnivel</label>
        <input type="text" name="desnivel" value="<?= $rutaDetalles['desnivel'] ?>">

        <label for="distancia">Distancia</label>
        <input type="text" name="distancia" value="<?= $rutaDetalles['distancia'] ?>">

        <label for="notas">Notas</label>
        <input type="text" name="notas" value="<?= $rutaDetalles['notas'] ?>">

        <label for="dificultad">Dificultad</label>
        <input type="text" name="dificultad" value="<?= $rutaDetalles['dificultad'] ?>">

        <button type="submit">Modificar</button>
    </form>
</div>