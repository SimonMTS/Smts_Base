<?php

    class Controller {
        
        public static $layout = 'main';
        public static $title;

        public static function beforeAction() {
            
        }

        public static function generate( $modelname ) {

            $UCmodelname = ucfirst($modelname);
            $controller = require "base/templates/controller.php";

            return $controller;

        }
    }