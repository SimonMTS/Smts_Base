<?php
    class BootstrapHelper {

        public static function Pagination($count) {
            $current_page = explode('/', str_replace(Smts::$config['BaseUrl'], '',Smts::Curl()) );
            $last_page = ceil($count);
            $contr = $current_page[0];
            if ( $last_page == 0 ) {
                $last_page = 1;
            }
            
            if ( isset($current_page[3]) ) {
                $searchpar = '/' . $current_page[3];
            } else {
                $searchpar = '';
            }
            if ( isset($current_page[2]) ) {
                $current_page = $current_page[2];
            } else {
                $current_page = 1;
            }
            
            $Nprev = $current_page - 1;
            $Nnext = $current_page + 1;
            if ( $last_page < 5 ) {
                $numbers = '';
                for ( $i=1; $i<=$last_page; $i++ ) {
                    $numbers = $numbers . '<li class="page-item ' . (($i==$current_page)?'active':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . $i . $searchpar . '">' . $i . '</a></li>';
                }
                $prev = '<li class="page-item ' . (($current_page==1)?'disabled':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . (($current_page==1)?'1':$Nprev) . $searchpar . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
                $next = '<li class="page-item ' . ((($last_page+1)==$Nnext)?'disabled':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . ((($last_page+1)==$Nnext)?$last_page:$Nnext) . $searchpar . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
            } else {
                if ( $current_page < 4 ) {
                    $Nnumbers = [ 1, 2, 3, 4, 5 ];
                } elseif ( ($current_page+2) > $last_page ) {
                    $Nnumbers = [ $last_page-4, $last_page-3, $last_page-2, $last_page-1, $last_page ];
                } else {
                    $Nnumbers = [ $current_page-2, $current_page-1, $current_page, $current_page+1, $current_page+2 ];
                }
    
                $prev = '<li class="page-item ' . (($current_page==1)?'disabled':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . (($current_page==1)?'1':$Nprev) . $searchpar . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
                $next = '<li class="page-item ' . ((($last_page+1)==$Nnext)?'disabled':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . ((($last_page+1)==$Nnext)?$last_page:$Nnext) . $searchpar . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
                $numbers = '<li class="' . (($Nnumbers[0]==$current_page)?'active':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . $Nnumbers[0] . $searchpar . '">' . $Nnumbers[0] . '</a></li>' .
                    '<li class="page-item ' . (($Nnumbers[1]==$current_page)?'active':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . $Nnumbers[1] . $searchpar . '">' . $Nnumbers[1] . '</a></li>' .
                    '<li class="page-item ' . (($Nnumbers[2]==$current_page)?'active':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . $Nnumbers[2] . $searchpar . '">' . $Nnumbers[2] . '</a></li>' .
                    '<li class="page-item ' . (($Nnumbers[3]==$current_page)?'active':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . $Nnumbers[3] . $searchpar . '">' . $Nnumbers[3] . '</a></li>' .
                    '<li class="page-item ' . (($Nnumbers[4]==$current_page)?'active':'') . '"><a class="page-link" href="' . Smts::$config['BaseUrl'] . $contr.'/' . $Nnumbers[4] . $searchpar . '">' . $Nnumbers[4] . '</a></li>';
            }
            return $prev . $numbers . $next;
        }

        public static function BreadCrumbs() {
            $base_url = Smts::$config['BaseUrl'];
            $var = explode('/', str_replace($base_url, '',Smts::Curl()) );

            if ( empty( $var[0] ) && empty( $var[1] ) ) {
                $string = '<li class="breadcrumb-item active">Home</li>';
            } elseif ( !empty( $var[0] ) && empty( $var[1] ) ) {
                $string = '<li class="breadcrumb-item"><a href="' . $base_url . '">Home</a></li>' . '<li class="breadcrumb-item active">' . ucfirst($var[0]) . '</li>';
            } else {
                $string = '<li class="breadcrumb-item"><a href="' . $base_url . '">Home</a></li>' . '<li class="breadcrumb-item"><a href="' .$base_url . $var[0] . '">' . ucfirst($var[0]) . '</a></li>' 
                        . '<li class="breadcrumb-item active">' . ucfirst($var[1]) . '</li>';
            }
            
            return "<nav class=\"mt-4\" aria-label=\"breadcrumb\"><ol class=\"breadcrumb\"> $string </ol></nav>";
        }
        
    }