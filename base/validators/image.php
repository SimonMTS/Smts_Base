<?php

    class image {

        public static function validate( $rule, $props ) {
            foreach ($props as $prop) {
                        
                if ( $prop['size'] > 0 ) {
                    $prop = Smts::UploadFile( $_FILES[$prop], $rule[2] );
                    
                    if ( !$prop ) {
                        return false;
                    }
                } else {
                    $prop = Smts::$config['Default_Profile_Pic'];
                }

            }
        }

    }