<?php

    class baseController extends Controller 
    {

        public static function home() 
        {
            self::$title = 'Home';

            base_smts::Render('base/home');
        }
    }