<?php
    require "base/core/module.php";

    class Dev extends Module {
        public static function Init( $var ) {
            
            if ( !Smts::$config['Debug'] ) {
                Smts::ErrorView('custom', [
                    'Denied',
                    'This page is disabled'
                ]);
            }

            self::$RewriteRules = [

                '[controller]' => '[controller]/overview',
                '[controller]/[action]' => '[controller]/[action]',
                'setup/init/[pw]' => 'setup/init/[pw]',
    
                '' => 'pages/home'
            ];
            
            self::UrlDecode($var);

        }
    }