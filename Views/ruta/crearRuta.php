<div class="container">

<form action="<?=BASE_URL?>Rutas/crearRuta/" method="post">

    <label for="titulo">Titulo</label>
    <input type="text" name="titulo">
    
    <label for="descripcion">Descripcion</label>
    <input type="text" name="descripcion">

    <label for="desnivel">desnivel</label>
    <input type="text" name="desnivel">

    <label for="distancia">distancia</label>
    <input type="text" name="distancia">

    <label for="notas">notas</label>
    <input type="text" name="notas">

    <label for="dificultad">dificultad</label>
    <input type="text" name="dificultad">

    <button type="submit">Crear</button>
</form>
</div>