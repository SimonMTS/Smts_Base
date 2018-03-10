<?php

    class type_double {

        public static function validate( $rule, &$model ) {
            
            foreach ( $rule[0] as $prop ) {

                if ( !is_double( $model->{$prop} ) ) {
                    return false;
                } else {
                    $model->{$prop} = floatval( $model->{$prop} );
                }
                
            }

            return true;

        }

    }