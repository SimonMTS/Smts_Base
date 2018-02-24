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
            return str_replace('.', '', uniqid('', true));;
        }

        public static function HashString( $string, $salt ) {
            return hash('sha512', $string . $salt);
        }

        public static function UploadFile( $file, $resolution = null ) {
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
                if ( isset( $resolution ) ) {
                    self::square_thumbnail_with_proportion($file["tmp_name"], $target_file, $resolution, 100);

                    return $target_file;
                } else {
                    if (move_uploaded_file($file["tmp_name"], $target_file)) {
                        return $target_file;
                    } else {
                        return false;
                    }
                }
            }

        }


        public static function Render( $view, $Cvar = [] ) {
            foreach ($Cvar as $key => $value) {
                ${$key} = $value;
            }

            $view = $view . '.php';

            require_once(__dir__.'/../views/layout/' . Controller::$layout . '.php');
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

            if ( self::$config['CustomErrors'] ) {
                register_shutdown_function('Smts::Error');
                set_error_handler('Smts::Error');
                set_exception_handler('Smts::Error');
                ini_set( "display_errors", "off" );
                error_reporting( E_ALL );
            }

            $path = str_replace(self::$config['BaseUrl'], '',self::Curl());
            $var = explode('/', $path );

            if ( self::$config['Debug'] && $var[0] == 'smts' ) {
                require 'base/smts/base_index.php';exit;
            }

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
            $fillInStructure = explode( '/', array_shift(array_slice(array_flip($urls), 0, 1)) );
            $pathToBeFilledIn = explode( '/', array_shift(array_slice($urls, 0, 1)) );
            $url = [];


            foreach ( $fillInStructure as $fillInStructureKey => $fillInStructureValue ) {
                
                if ( mb_substr( $fillInStructureValue, 0, 1 ) == '[' || mb_substr( $fillInStructureValue, -1 ) == ']' ) {

                    $pathToBeFilledIn[ array_search( $fillInStructureValue, $pathToBeFilledIn ) ] = $dataToFillIn[ $fillInStructureKey ];

                }

            }

            $pathToBeFilledIn = array_reverse($pathToBeFilledIn);
            $url['controller'] = array_pop( $pathToBeFilledIn );
            $url['action'] = array_pop( $pathToBeFilledIn );

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
            
            require 'base/routes.php';
        }


        public static function Pagination($count) {
            $current_page = explode('/', str_replace(Smts::$config['BaseUrl'], '',Smts::Curl()) );
            $last_page = ceil($count);
            $contr = $current_page[0];
            if ( $last_page == 0 ) {
                $last_page = 1;
            }
            
            if ( isset($current_page[3]) ) {
                $searchpar = '/' . $current_page[3];
            } else {
                $searchpar = '';
            }
            if ( isset($current_page[2]) ) {
                $current_page = $current_page[2];
            } else {
                $current_page = 1;
            }
            
            $Nprev = $current_page - 1;
            $Nnext = $current_page + 1;
            if ( $last_page < 5 ) {
                $numbers = '';
                for ( $i=1; $i<=$last_page; $i++ ) {
                    $numbers = $numbers . '<li class="page-item ' . (($i==$current_page)?'active':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . $i . $searchpar . '">' . $i . '</a></li>';
                }
                $prev = '<li class="page-item ' . (($current_page==1)?'disabled':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . (($current_page==1)?'1':$Nprev) . $searchpar . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
                $next = '<li class="page-item ' . ((($last_page+1)==$Nnext)?'disabled':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . ((($last_page+1)==$Nnext)?$last_page:$Nnext) . $searchpar . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
            } else {
                if ( $current_page < 4 ) {
                    $Nnumbers = [ 1, 2, 3, 4, 5 ];
                } elseif ( ($current_page+2) > $last_page ) {
                    $Nnumbers = [ $last_page-4, $last_page-3, $last_page-2, $last_page-1, $last_page ];
                } else {
                    $Nnumbers = [ $current_page-2, $current_page-1, $current_page, $current_page+1, $current_page+2 ];
                }
    
                $prev = '<li class="page-item ' . (($current_page==1)?'disabled':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . (($current_page==1)?'1':$Nprev) . $searchpar . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
                $next = '<li class="page-item ' . ((($last_page+1)==$Nnext)?'disabled':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . ((($last_page+1)==$Nnext)?$last_page:$Nnext) . $searchpar . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
                $numbers = '<li class="' . (($Nnumbers[0]==$current_page)?'active':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . $Nnumbers[0] . $searchpar . '">' . $Nnumbers[0] . '</a></li>' .
                    '<li class="page-item ' . (($Nnumbers[1]==$current_page)?'active':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . $Nnumbers[1] . $searchpar . '">' . $Nnumbers[1] . '</a></li>' .
                    '<li class="page-item ' . (($Nnumbers[2]==$current_page)?'active':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . $Nnumbers[2] . $searchpar . '">' . $Nnumbers[2] . '</a></li>' .
                    '<li class="page-item ' . (($Nnumbers[3]==$current_page)?'active':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . $Nnumbers[3] . $searchpar . '">' . $Nnumbers[3] . '</a></li>' .
                    '<li class="page-item ' . (($Nnumbers[4]==$current_page)?'active':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . $Nnumbers[4] . $searchpar . '">' . $Nnumbers[4] . '</a></li>';
            }
            return $prev . $numbers . $next;
        }

        public static function BreadCrumbs() {
            $base_url = Smts::$config['BaseUrl'];
            $var = explode('/', str_replace($base_url, '',Smts::Curl()) );

            if ( empty( $var[0] ) && empty( $var[1] ) ) {
                $string = '<li class="breadcrumb-item active">Home</li>';
            } elseif ( !empty( $var[0] ) && empty( $var[1] ) ) {
                $string = '<li class="breadcrumb-item"><a href="' . $base_url . '">Home</a></li>' . '<li class="breadcrumb-item active">' . ucfirst($var[0]) . '</li>';
            } else {
                $string = '<li class="breadcrumb-item"><a href="' . $base_url . '">Home</a></li>' . '<li class="breadcrumb-item"><a href="' .$base_url . $var[0] . '">' . ucfirst($var[0]) . '</a></li>' 
                        . '<li class="breadcrumb-item active">' . ucfirst($var[1]) . '</li>';
            }
            
            return "<nav class=\"mt-4\" aria-label=\"breadcrumb\"><ol class=\"breadcrumb\"> $string </ol></nav>";
        }
    }