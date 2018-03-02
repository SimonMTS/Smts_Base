<?php

    class image {

        public static function validate( $rule, &$model ) {
            
            foreach ($rule[0] as $prop) {

                if ( $model->{$prop}['size'] > 0 ) {
                    $model->{$prop} = Smts::UploadFile( $_FILES[$prop], $rule[2] );
                    
                    if ( !$model->{$prop} ) {
                        return false;
                    }
                } else {
                    $model->{$prop} = Smts::$config['DefaultProfilePic'];
                }
                
            }

            return true;
        }

    }