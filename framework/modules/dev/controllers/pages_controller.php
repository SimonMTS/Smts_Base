<?php
    use Base\Core\Smts;
    use Base\Core\Controller;

    class pagesController extends Controller {

        public static function home() {
            
            Dev::Render('pages/home');
            
        }

    }