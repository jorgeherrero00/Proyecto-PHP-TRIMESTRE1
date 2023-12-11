<div class="container">

<?php if(!isset($_SESSION['identity'])): ?>
    <h2>Login</h2>
    <form action="<?=BASE_URL?>usuario/login/" method="post">
        <label for="email">Email</label>
        <input type="email" name="data[email]" id="email">
        <label for="password">Contraseña</label>
        <input type="password" name="data[password]" id="password">
        <input type="submit" value="Enviar">
    </form>
<?php else: ?>
    <?php endif; ?>
</div>