<?php

    class pagesController extends Controller {

        public static function home() {

            self::$title = 'Home';

            Smts::Render('pages/home');

        }

    }