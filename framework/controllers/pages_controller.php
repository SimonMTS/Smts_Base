<?php
    use Base\Core\Smts;
    use Base\Core\Controller;

    class pagesController extends Controller {

        public static function home() {

            self::$title = 'Home';

            Smts::Render('pages/home');

        }

    }