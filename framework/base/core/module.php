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
    }