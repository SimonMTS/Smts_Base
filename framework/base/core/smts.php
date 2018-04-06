<?php
    require "base/core/internationalization.php";
    require "base/core/framework_core.php";
    require "base/core/controller.php";
    require "base/core/model.php";
    require "base/core/sql.php";

    class Smts extends FrameworkCore {

        public static function Redirect( $url ) {

            if ( headers_sent() ) {
                echo '<meta http-equiv="Location" content="' . $url . '">';
                echo '<script> location.replace("' . $url . '"); </script>';
                echo '<a href="' . $url . '">' . $url . '</a>';
                exit;
            } else {
                header('location: ' . $url);
                exit;
            }

        }

        public static function Sanitize( $string ) {

            return htmlentities( $string, ENT_QUOTES );

        }
        
        public static function Curl() {

            return ( isset($_SERVER['HTTPS']) ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        }

        public static function GenerateId() {

            return bin2hex( random_bytes(32) );

        }

        public static function HashString( $string, $salt ) {

            return hash('sha512', $string . $salt);
            
        }

        public static function UploadFile( $file, $resolution = 400 ) {

            $target_dir = "assets/img/";
            $target_file = $target_dir . self::GenerateId().$file['name'] ;
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            
            $check = getimagesize($file["tmp_name"]);
            if ( $check !== false ) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
            
            if ( $imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" ) {
                $uploadOk = 0;
            }
            if ( $uploadOk == 0 ) {
                return false;
            } else {

                if ( $imageFileType != "png" ) {
                    $im = imagecreatefromjpeg($file['tmp_name']);
                } else {
                    $im = imagecreatefrompng($file['tmp_name']);
                }


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
                if ( $im2 !== FALSE ) {
                    
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

        public static function t( $category, $key, $replaceArr = [] ) {
            
            $text = i18n::t( $category, $key );

            foreach ( $replaceArr as $key => $value ) {
                $text = str_replace( $key, $value, $text );
            }

            return $text;

        }

        
        public static function Init( $config ) {
            
            parent::EnvSetup( $config );

            self::$session =& $_SESSION[ self::$config['BaseUrl'] ];
            
            $path = parent::GetPath();

            parent::RouteRequest( $path );

        }

    }