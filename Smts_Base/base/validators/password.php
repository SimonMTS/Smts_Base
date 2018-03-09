<?php

    class password {

        public static function validate( $rule, &$model ) {
            
            if ( $model->{$rule[0][0]} != $model->{$rule[0][1]} ) {
                return false;
            }

            return true;
        }

    }