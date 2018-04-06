<?php

    class i18n {

        private static $langArrs = [];

        private static function getLangArr( $lang, $category ) {

            if ( !isset( self::$langArrs[ $lang.$category ] ) ) {
                $langs = array_diff(scandir("base/i18n"), ['..', '.']);

                if ( !in_array( $lang, $langs ) ) {
                    $lang = 'en';
                }
                self::$langArrs[ $lang.$category ] = require "base/i18n/".$lang."/".$category."_lang.php";
            }

            return self::$langArrs[ $lang.$category ];

        }

        public static function t( $category, $key ) {
            
            $lang = call_user_func( Smts::$config['DetermineLanguage'] );

            return (isset(self::getLangArr( $lang, $category )[$key])?self::getLangArr( $lang, $category )[$key]:'');

        }

    }