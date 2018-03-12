<?php
    class BootstrapHelper {
        
        public static function Pagination( $totalPages, $currentPage ) {

            $totalPages = ceil($totalPages);
            $item = [];

            if ( strpos( Smts::Curl(), $currentPage.'' ) !== false ) {
                $url = preg_replace( '/'.$currentPage.'/', '[page]', Smts::Curl(), 1 );
            } else {
                $url = Smts::Curl() . '/p/[page]';
            }

            $open = '<nav aria-label="Page navigation example"><ul class="pagination">';
            $close = '</ul></nav>';

            if ( $currentPage-1 < 1 ) {
                $backval = 1;
                $backactive = ' disabled';
            } else {
                $backval = $currentPage-1;
                $backactive = '';
            }
            $back = '<li class="page-item'.$backactive.'"><a class="page-link" href="' . str_replace('[page]', $backval, $url) . '"><span>&laquo;</span></a></li>';
            
            if ( $currentPage+1 > $totalPages ) {
                $nextval = $totalPages;
                $nextactive = ' disabled';
            } else {
                $nextval = $currentPage+1;
                $nextactive = '';
            }
            $next = '<li class="page-item'.$nextactive.'"><a class="page-link" href="' . str_replace('[page]', $nextval, $url) . '"><span>&raquo;</span></a></li>';

            if ( $totalPages <= 5 ) {
                for ( $i=1; $i<$totalPages+1; $i++ ) {
                    $item[] = '<li class="page-item '.($i==$currentPage?'active':'').'"><a class="page-link" href="' . str_replace('[page]', $i, $url) . '">' . $i . '</a></li>';
                }

                if ( sizeof( $item ) == 0 ) {
                    $item[] = '<li class="page-item active"><a class="page-link" href="' . str_replace('[page]', $currentPage, $url) . '">' . $currentPage . '</a></li>';
                }

            } else {
                switch ( true ) {
                    case $currentPage-1 < 1:
                        $lstart = 0;
                        $lend = 4;
                        break;
                    case $currentPage-2 < 1:
                        $lstart = -1;
                        $lend = 3;
                        break;
    
                    case $currentPage+1 > $totalPages:
                        $lstart = -4;
                        $lend = 0;
                        break;
                    case $currentPage+2 > $totalPages:
                        $lstart = -3;
                        $lend = 1;
                        break;
                        
                    default:
                        $lstart = -2;
                        $lend = 2;
                        break;
                }

                for ( $i=$lstart; $i<=$lend; $i++ ) {
                    $item[] = '<li class="page-item '.($currentPage+$i==$currentPage?'active':'').'"><a class="page-link" href="' . str_replace('[page]', $currentPage+$i, $url) . '">' . ($currentPage+$i) . '</a></li>';
                }
            }

            return $open . $back . implode('', $item) . $next . $close;
            
        }

    }