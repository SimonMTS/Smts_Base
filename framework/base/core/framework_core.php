<?php

    namespace Base\Core;

    use Base\Core\Controller;

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

            spl_autoload_register(function ($class) {
                $class = str_replace('\\','/', strToLower($class)) . '.php';

                require_once $class;
            });
            
        }

        protected static function GetPath() {
            $path = str_replace(self::$config['BaseUrl'], '',Smts::Curl());
            $rules = self::$config['RewriteRules'];

            $routeComponents = explode( '/', $path );
    
            foreach ( $rules as $pattern => $destination ) {
    
                global $i;
                $i=-1;
                $PREregex = preg_replace_callback(
                    '/\[([a-zA-Z0-9_.-]*)\]/', 
                    function($regex_matches){
                        global $i;
                        $i++;
    
                        return '(?P<'.$regex_matches[1].'>\w+)';
                    }, 
                    $pattern
                );
    
                $regex = str_replace('/', '\\/', $PREregex);
    
                $matches = [];
                if ( preg_match( '/'.$regex.'/', $path, $matches ) ) {
    
                    foreach ($matches as $key => $value) {
                        if (is_int($key)) {
                            unset($matches[$key]);
                        }
                    }
    
                    $GLOBALS['matches'] = $matches;
    
                    $plk = preg_replace_callback(
                        '/\[([a-zA-Z0-9_.-]*)\]/', 
                        function($regex_matches){
                            global $i;
                            $i++;
        
                            return $GLOBALS['matches'][ $regex_matches[1] ];
                        }, 
                        $destination
                    );
    
                    $plk_explode = explode( '/', $plk );
    
                    $components = [
                        'module' => $plk_explode[0],
                        'controller' => $plk_explode[1],
                        'action' => $plk_explode[2]
                    ];
    
                    unset( $GLOBALS['matches'] );
                    unset( $matches['module'] );
                    unset( $matches['controller'] );
                    unset( $matches['action'] );

                    $components['params'] = $matches;
    
                    return $components;
    
                }
    
            }
        }

        protected static function RouteRequest( $url ) {
            
            if ( $url['module'] == 'base' ) {

                $controllers = array_diff(scandir("./controllers"), ['..', '.']);

                if ( in_array( ( $url['controller'].'_controller.php'), $controllers ) ) {

                    require 'controllers/' . $url['controller'] . '_controller.php';

                    $controller = $url['controller'].'Controller';
                    $actions = get_class_methods( $controller );

                    if ( in_array( $url['action'], $actions ) ) {
                        $controller::$title = self::$config['DefaultTitle'];
                        $controller::beforeAction();
                        $controller::{$url['action']}($url['params']);
                    }

                }
                
                self::ErrorView(404);

            } else {

                $modules = array_diff(scandir("./modules"), ['..', '.']);

                if ( in_array($url['module'], $modules) ) {
                    
                    require 'modules/'.$url['module'].'/'.$url['module'].'.php';
                    ucfirst($url['module'])::Init($url);exit;

                }
                
                self::ErrorView(404);

            }

        }

    }