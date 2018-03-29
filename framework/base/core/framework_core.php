<?php

    class FrameworkCore {

        public static $session = [];
        public static $config = [];

        public static function Flash( $msg = [] ) {
            
            if ( is_array($msg) && sizeof($msg) > 0 ) {
                $flip = array_flip($msg);
                $key = array_pop($flip);
                self::$session['flash'][ $key ] = $msg[ $key ];

                return true;
            } else {
                if ( isset( self::$session['flash'] ) ) {
                    $res = self::$session['flash'];
                    unset( self::$session['flash'] );
    
                    return $res;
                } else {
                    return false;
                }
            }

        }

        public static function Render( $view, $Cvar = [] ) {
            foreach ($Cvar as $key => $value) {
                ${$key} = $value;
            }

            $view = $view . '.php';

            require __dir__.'/../../views/layout/' . Controller::$layout . '.php';
            exit;
        }

        public static function Error( $a = null, $b = null, $c = null, $d = null, $e = null, $f = null ) {

            if ( count(array_unique([ $a, $b, $c, $d, $e, $f, null ])) !== 1  ) {
                self::ErrorView('custom', [
                    'Error: 500',
                    'Something went wrong'
                ]);
                exit;
            }

        }

        public static function ErrorView( $type = null, $data = null ) {
            self::Render(self::$config['ErrorViewLocation'], [
                'type' => $type,
                'data' => $data
            ]);
        }

        protected static function EnvSetup( $config ) {

            self::$config = $config;

            foreach ( self::$config[ self::$config['Env'] ] as $settingName => $settingValue ) {
                self::$config[ $settingName ] = $settingValue;
            }

            header("X-Frame-Options: DENY");
            header("Content-Security-Policy: frame-ancestors 'none'");
            session_start();

            if ( self::$config['CustomErrors'] ) {
                register_shutdown_function('FrameworkCore::Error');
                set_error_handler('FrameworkCore::Error');
                set_exception_handler('FrameworkCore::Error');
                ini_set( "display_errors", "off" );
                error_reporting( E_ALL );
            }
            
        }

        protected static function GetPath() {
            $path = str_replace(self::$config['BaseUrl'], '',Smts::Curl());
            $var = explode('/', $path );

            $modules = array_diff(scandir("./modules"), ['..', '.']);

            if ( isset($var[0]) && in_array($var[0], $modules) ) {
                $Module = array_shift( $var );
                
                require 'modules/'.$Module.'/'.$Module.'.php';
                ucfirst($Module)::Init($var);exit;
            }

            $defaultPath = explode( '/', array_pop( self::$config['RewriteRules'] ) );

            if ( sizeof( $var ) == 1 && $var[0] == '' ) {

                $url['controller'] = $defaultPath[0];
                $url['action'] = $defaultPath[1];
                $url['params'] = [];

            } else {
                
                $urls = self::$config['RewriteRules'];

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
                $url['params'] = [];
                
                $i = 0;
                foreach ( array_reverse($fillInStructure) as $fillInStructureValue ) {
                    if ( mb_substr( $fillInStructureValue, 0, 1 ) == '[' || mb_substr( $fillInStructureValue, -1 ) == ']' ) {
                        if ( $i == sizeof( $pathToBeFilledIn ) ) {
                            break;
                        }
                        $url['params'][ substr($fillInStructureValue, 1, -1) ] = $pathToBeFilledIn[ $i ];
                        $i++;
                    }
                }

            }

            return $url;
        }

        protected static function RouteRequest( $url ) {
            
            $controllers = array_diff(scandir("./controllers"), ['..', '.']);

            if ( in_array( ( $url['controller'].'_controller.php'), $controllers ) ) {

                require_once('controllers/' . $url['controller'] . '_controller.php');

                $controller = $url['controller'].'Controller';
                $actions = get_class_methods( $controller );

                if ( in_array( $url['action'], $actions ) ) {
                    $controller::$title = self::$config['DefaultTitle'];
                    $controller::beforeAction();
                    $controller::{$url['action']}($url['params']);
                } else {
                    self::ErrorView(404);
                }

            } else {
                self::ErrorView(404);
            }

        }

    }