<div class="container">
<form action="<?= BASE_URL ?>ComentariosRutas/crearComentario/" method="post">
<input type="hidden" name="Ruta" value="<?= $Ruta['id'] ?>">
    <label for="nombre">Nombre</label>
    <input type="text" name="nombre">
    <label for="texto">Texto</label>
    <input type="text" name="texto">
    <button type="submit">Añadir comentario</button>
</form>
</div>