<?php

    namespace Base\Core;

    class Module {

        public static $RewriteRules = [];
        public static $layout = 'main';

        public static function Render( $view, $Cvar = [] ) {

            foreach ( $Cvar as $key => $value ) {
                ${$key} = $value;
            }

            $view = $view . '.php';
            require __dir__.'/../../modules/'.lcfirst(get_called_class()).'/views/layout/' . self::$layout . '.php';
            exit;

        }

        public static function init( $url ) {

            $controllers = array_diff(scandir("./modules/".$url['module']."/controllers"), ['..', '.']);

            if ( in_array( ( $url['controller'].'_controller.php'), $controllers ) ) {
                require 'modules/' . $url['module'].'/controllers/' . $url['controller'] . '_controller.php';

                $controller = $url['controller'].'Controller';
                $actions = get_class_methods( $controller );

                if ( in_array( $url['action'], $actions ) ) {
                    $controller::$title = Smts::$config['DefaultTitle'];
                    $controller::beforeAction();
                    $controller::{$url['action']}($url['params']);
                }
            }

        }
    }