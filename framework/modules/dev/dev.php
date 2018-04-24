<?php

    use Base\Core\Smts;
    use Base\Core\Module;

    class Dev extends Module {
        public static function Init( $url ) {
            
            if ( !Smts::$config['Debug'] ) {
                Smts::ErrorView('custom', [
                    'Denied',
                    'This page is disabled'
                ]);
            }
            

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
            
            Smts::ErrorView(404);

        }
    }