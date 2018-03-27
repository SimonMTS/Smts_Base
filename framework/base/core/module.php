<?php

    class Module {

        public static $RewriteRules = [];
        public static $layout = 'main';

        public static function UrlDecode( $var ) {

            Controller::$title = Smts::$config['DefaultTitle'];

            $defaultPath = explode( '/', array_pop( self::$RewriteRules ) );
            
            if ( sizeof( $var ) == 0 || (sizeof( $var ) == 1 && $var[0] == '') ) {

                $url['controller'] = $defaultPath[0];
                $url['action'] = $defaultPath[1];
                $urlParams = [];

            } else {
                
                $urls = self::$RewriteRules;
                
                foreach ( $var as $varKey => $varValue ) {
                    foreach ( $urls as $urlKey => $urlValue ) {
                        $urlSection = explode( '/', $urlKey )[ $varKey ];
                        if ( 
                            (
                                ( mb_substr( $urlSection, 0, 1 ) != '[' || mb_substr( $urlSection, -1 ) != ']' ) &&
                                ( $urlSection != $varValue ) 
                            ) || 
                            empty( $varValue ) ||
                            sizeof( explode( '/', $urlKey ) ) != sizeof( $var )   
                        ) {
                            unset( $urls[ $urlKey ] );
                        }
                    }
                }
                
                $dataToFillIn = $var;

                $fillInStructureSliced = array_slice(array_flip($urls), 0, 1);
                $fillInStructure = explode( '/', array_shift( $fillInStructureSliced ) );

                $pathToBeFilledInSliced = array_slice($urls, 0, 1);
                $pathToBeFilledIn = explode( '/', array_shift( $pathToBeFilledInSliced ) );

                $url = [];

                foreach ( $fillInStructure as $fillInStructureKey => $fillInStructureValue ) {
                    if ( mb_substr( $fillInStructureValue, 0, 1 ) == '[' || mb_substr( $fillInStructureValue, -1 ) == ']' ) {
                        $pathToBeFilledIn[ array_search( $fillInStructureValue, $pathToBeFilledIn ) ] = $dataToFillIn[ $fillInStructureKey ];
                    }
                }

                $pathToBeFilledIn = array_reverse($pathToBeFilledIn);
                $url['controller'] = array_pop( $pathToBeFilledIn );
                $url['action'] = array_pop( $pathToBeFilledIn );
                $urlParams = [];
                
                $i = 0;
                foreach ( array_reverse($fillInStructure) as $fillInStructureValue ) {
                    if ( mb_substr( $fillInStructureValue, 0, 1 ) == '[' || mb_substr( $fillInStructureValue, -1 ) == ']' ) {
                        if ( $i == sizeof( $pathToBeFilledIn ) ) {
                            break;
                        }
                        $urlParams[ substr($fillInStructureValue, 1, -1) ] = $pathToBeFilledIn[ $i ];
                        $i++;
                    }
                }

            }
            

            $controllers = array_diff(scandir('modules/'.strtolower(get_called_class()).'/controllers'), ['..', '.']);

            if ( in_array( ( $url['controller'].'_controller.php'), $controllers ) )
            {
                require_once('modules/'.strtolower(get_called_class()).'/controllers/' . $url['controller'] . '_controller.php');

                $controller = $url['controller'].'Controller';
                $actions = get_class_methods( $controller );

                if ( in_array( $url['action'], $actions ) )
                {
                    $controller::beforeAction();
                    $controller::{$url['action']}($urlParams);
                }
                else
                {
                    Smts::ErrorView(404);
                }
            }
            else
            {
                Smts::ErrorView(404);
            }

        }

        public static function Render( $view, $Cvar = [] ) {

            foreach ( $Cvar as $key => $value ) {
                ${$key} = $value;
            }

            $view = $view . '.php';

            require_once(__dir__.'/../../modules/'.lcfirst(get_called_class()).'/views/layout/' . self::$layout . '.php');

        }
    }