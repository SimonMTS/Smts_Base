<?php
    require "base/sql.php";
    require "base/controller.php";
    require "base/model.php";

    class Smts {

        public static $session = [];
        public static $config = [];

        public static function Redirect( $url ) {
            if (headers_sent()) {
                echo '<meta http-equiv="Location" content="' . $url . '">';
                echo '<script> location.replace("' . $url . '"); </script>';
                echo '<a href="' . $url . '">' . $url . '</a>';
                exit;
            } else {
                header('location: ' . $url);exit;
            }
        }

        public static function Sanitize( $string ) {
            return htmlentities($string, ENT_QUOTES);
        }
        
        public static function Curl() {
            return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }

        public static function GenetateId() {
            return bin2hex(random_bytes(32));
        }

        public static function HashString( $string, $salt ) {
            return hash('sha512', $string . $salt);
        }

        public static function UploadFile( $file, $resolution = 400 ) {
            $target_dir = "assets/img/";
            $target_file = $target_dir . self::GenetateId().$file['name'] ;
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            
            $check = getimagesize($file["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
            
            if ( $imageFileType != "jpg" && $imageFileType != "jpeg" ) {
                $uploadOk = 0;
            }
            if ($uploadOk == 0) {
                return false;
            } else {

                $im = imagecreatefromjpeg($file['tmp_name']);
                $newX = ( imagesx($im) - imagesy($im) ) / 2;
                $newY = ( imagesy($im) - imagesx($im) ) / 2;

                if ( $newX > 0 ) {
                    $x = $newX;  
                    $y = 0;  
                } else {
                    $x = 0;  
                    $y = $newY;  
                }

                $size = min(imagesx($im), imagesy($im));
                $im2 = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $size, 'height' => $size]);
                if ($im2 !== FALSE) {
                    
                    ob_start();
                        imagepng($im2);
                        $contents = ob_get_contents();
                    ob_end_clean();

                    imagedestroy($im2);
                }
                imagedestroy($im);

                $src = imagecreatefromstring($contents);
                $dst = imagecreatetruecolor($resolution, $resolution);
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $resolution, $resolution, $size, $size);

                if ( imagejpeg( $dst, $target_file ) ) {
                    return $target_file;
                } else {
                    return false;
                }
            }

        }


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

            require_once(__dir__.'/../views/layout/' . Controller::$layout . '.php');exit;
        }

        public static function Error( $a = null, $b = null, $c = null, $d = null, $e = null, $f = null ) { //todo
            $error = error_get_last();
            
            if ( $error["type"] == E_ERROR ) {
                // fatal error
                $data = str_replace( '\\', '|', implode('*', $error) );
                self::ErrorView('fatal', $data);exit;
            } elseif ( isset($a) && !isset($b) && !isset($c) && !isset($d) && !isset($e) && !isset($f) ) {
                // error
                self::ErrorView($a->getcode(), $a);exit;
            } elseif ( isset( $error ) ) { 
                // exeption
                self::ErrorView($a, [
                    $a,
                    $b,
                    $c,
                    $d,
                    $e,
                    $f
                ]);exit;
            }
        }

        public static function ErrorView( $type = null, $data = null ) {
            Smts::Render('pages/error', [
                'type' => $type,
                'data' => $data
            ]);
        }

        public static function Init( $config ) {
            self::$config = $config;

            header("X-Frame-Options: DENY");
            header("Content-Security-Policy: frame-ancestors 'none'");
            session_start();

            foreach ( self::$config[ self::$config['Env'] ] as $settingName => $settingValue ) {
                self::$config[ $settingName ] = $settingValue;
            }

            self::$session =& $_SESSION[ self::$config['BaseUrl'] ];

            if ( self::$config['CustomErrors'] ) {
                register_shutdown_function('Smts::Error');
                set_error_handler('Smts::Error');
                set_exception_handler('Smts::Error');
                ini_set( "display_errors", "off" );
                error_reporting( E_ALL );
            }

            $path = str_replace(self::$config['BaseUrl'], '',self::Curl());
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
                $urlParams = [];

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
            
            require 'base/routes.php';
        }

    }