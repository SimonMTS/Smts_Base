<?php

    class required {

        public static function validate( $rule, &$model ) {
            
            foreach ($rule[0] as $prop) {

                if ( !isset( $model->{$prop} ) || empty( $model->{$prop} ) ) {
                    return false;
                }
                
            }

            return true;
        }

    }