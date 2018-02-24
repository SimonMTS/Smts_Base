<?php

class Controller {
    public static $layout = 'main';
    public static $title;

    public static function beforeAction() {
        self::$title = Smts::$config['DefaultTitle'];
    }

    public static function generate( $modelname ) {

        $UCmodelname = ucfirst($modelname);
        require_once "base/templates/controller.php";

        return $controller;
    }
}
