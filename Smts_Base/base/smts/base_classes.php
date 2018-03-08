<?php

    class base_smts {

        public static function Render( $view, $Cvar = [] ) {
            foreach ($Cvar as $key => $value) {
                ${$key} = $value;
            }

            $view = $view . '.php';

            require_once(__dir__.'/views/layout/' . Controller::$layout . '.php');
        }


        public static function BreadCrumbs() {
            $base_url = $GLOBALS['config']['base_url'];
            $var = explode('/', str_replace($base_url, '',Base::Curl()) );

            if ( empty( $var[1] ) && empty( $var[2] ) ) {
                $string = '<li class="active">Smts</li>';
            } elseif ( !empty( $var[1] ) && empty( $var[2] ) ) {
                $string = '<li><a href="' . $base_url . 'smts">Smts</a></li>' . '<li class="active">' . ucfirst($var[1]) . '</li>';
            } else {
                $string = '<li><a href="' . $base_url . 'smts">Smts</a></li>' . '<li><a href="' .$base_url . 'smts/' . $var[1] . '">' . ucfirst($var[1]) . '</a></li>' 
                        . '<li class="active">' . ucfirst($var[2]) . '</li>';
            }
            
            return "<ol class=\"breadcrumb\"> $string </ol>";
        }
    }