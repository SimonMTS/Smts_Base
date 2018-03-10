<?php

    class type_string {

        public static function validate( $rule, &$model ) {
            
            foreach ( $rule[0] as $prop ) {

                if ( !is_string( $model->{$prop} ) ) {
                    return false;
                } else {
                    $model->{$prop} = strval( $model->{$prop} );
                }
                
            }

            return true;
            
        }

    }