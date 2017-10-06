<?php

    class crudController extends Controller 
    {
        public static function overview() 
        {
            Base::Render('crud/overview');
        }
    }