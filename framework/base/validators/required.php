<?php

    class required {

        public static function validate( $rule, &$model ) {
            
            $errors = false;

            foreach ( $rule[0] as $prop ) {

                if ( !isset( $model->{$prop} ) || empty( $model->{$prop} ) ) {
                    $errors = true;
                }
                
            }

            return ( !$errors );

        }

    }