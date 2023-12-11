<?php
    session_start();
    use Controllers\FrontController;
    require_once "vendor/autoload.php";
    require_once "config/config.php";
    


    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
    FrontController::main();
?>

