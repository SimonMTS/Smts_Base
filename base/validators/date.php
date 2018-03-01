<?php

    class date {

        public static function validate( $rule, $props ) {
            foreach ($props as $prop) {
                        
                $date = date('d/m/Y:H:i:s', strtotime( implode( '-', $prop ) ));
                
                if ( sizeof( $prop ) == 3 ) {
                    $prop = $date;
                } else {
                    return false;
                }

            }
        }

    }