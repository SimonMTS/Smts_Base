<?php

    class in {

        public static function validate( $rule, $props ) {
            foreach ($props as $prop) {
                if ( !in_array( $prop, $rule[2] ) ) {
                    return false;
                }
            }
        }

    }