<?php

    class required {

        public static function validate( $rule, $props ) {
            foreach ($props as $prop) {
                if ( !isset( $prop ) || empty( $prop ) ) {
                    return false;
                }
            }

            return true;
        }

    }