<?php
    require_once('vendor/config.php');
    require_once('vendor/classes.php');

    session_start();

    if (isset($_GET['controller']) && isset($_GET['action'])) {
        $controller = $_GET['controller'];
        $action     = $_GET['action'];
    } else {
        $controller = $config['landing']['controller'];
        $action     = $config['landing']['action'];
    }

    require_once('views/layout.php');
?>
