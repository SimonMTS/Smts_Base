<?php
    require "base/module.php";

    class Dev extends Module
    {
        public static function Init($var) {
            
            if ( !Smts::$config['Debug'] ) {
                Smts::Render('pages/error', [
                    'type' => 'custom',
                    'data' => [
                        'Denied',
                        'This page is disabled'
                    ]
                ]);
            }

            self::$RewriteRules = [

                '[controller]' => '[controller]/overview',
                '[controller]/[action]' => '[controller]/[action]',
                'setup/init/[pw]' => 'setup/init/[pw]',
    
                '' => 'pages/home'
            ];
            
            self::$layout = 'main';

            self::UrlDecode($var);

        }
    }