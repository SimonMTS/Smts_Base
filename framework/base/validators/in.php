<?php

    class in {

        public static function validate( $rule, &$model ) {
            
            foreach ( $rule[0] as $prop ) {

                if ( !in_array( $model->{$prop}, $rule[2] ) ) {
                    return false;
                }
                
            }

            return true;
            
        }

    }