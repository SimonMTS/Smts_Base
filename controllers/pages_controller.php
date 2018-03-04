<?php

    class pagesController extends Controller 
    {

        public static function home() 
        {
            self::$title = 'Home';

            Smts::Render('pages/home');
        }

        public static function error($type = null, $data = null) 
        {
            Smts::ErrorView($type, $data);
        }
    }