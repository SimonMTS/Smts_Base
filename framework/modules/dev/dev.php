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
            
            parent::init( $url );
            
            Smts::ErrorView(404);

        }
    }