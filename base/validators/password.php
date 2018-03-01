<?php

    class password {

        public static function validate( $rule, $props ) {
            if ( $this->{$rule[0][0]} != $this->{$rule[0][1]} ) {
                return false;
            }
        }

    }