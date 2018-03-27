<?php

    class unique {

        public static function validate( $rule, &$model ) {

            foreach ( $rule[0] as $prop ) {

                $item = Sql::find( strtolower(get_class($model)) )->where([$prop => $model->{$prop}])->all();
                if ( $item && $item[0][$prop] != $model->{$prop} ) {
                    return false;
                }
                
            }

            return true;
            
        }

    }