<?php

    class date {

        public static function validate( $rule, &$model ) {
            
            foreach ($rule[0] as $prop) {

                $date = date('d/m/Y:H:i:s', strtotime( implode( '-', $model->{$prop} ) ));
                            
                if ( sizeof( $model->{$prop} ) == 3 ) {
                    $model->{$prop} = $date;
                } else {
                    return false;
                }
                
            }

            return true;
        }

    }