<?php

    class type_integer {

        public static function validate( $rule, &$model ) {
            
            foreach ( $rule[0] as $prop ) {

                if ( !is_int( $model->{$prop} ) ) {
                    $model->{$prop} = intval( $model->{$prop} );
                }
                
            }

            return true;
            
        }

    }