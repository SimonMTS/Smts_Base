<?php

    class pagesController extends Controller 
    {

        public static function home() 
        {
            self::$title = 'Home';

            Base::Render('pages/home');
        }

        public static function error($type = null, $data = null) 
        {
            Base::error_view($type, $data);
        }
    }