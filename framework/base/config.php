<?php
    return [

        'RewriteRules' => [

            'login' => 'users/login',

            '[controller]' => '[controller]/overview',
            '[controller]/p/[page]' => '[controller]/overview/[page]',
            '[controller]/p/[page]/s/[search]' => '[controller]/overview/[page]/[search]',

            'users/view/[id]' => 'users/view/[id]',
            'users/edit/[id]' => 'users/edit/[id]',
            'users/delete/[id]' => 'users/delete/[id]',
            
            '[controller]/[action]' => '[controller]/[action]',

            '' => 'pages/home'

        ],


        'ErrorViewLocation' => 'pages/error',

        'DefaultTitle' => 'smts_base',
        'DefaultProfilePic' => 'assets/user.png',

        
        'Env' => 'Dev',

        'Live' => [
            'BaseUrl' => 'https://base.simonstriekwold.nl/',

            'DataBaseName' => "--",
            'DataBaseUser' => '--',
            'DataBasePassword' => '--',

            'Debug' => false,
            'CustomErrors' => true
        ],

        'Dev' => [
            'BaseUrl' => 'http://localhost/Smts_Base/framework/',

            'DataBaseName' => "smts_base",
            'DataBaseUser' => 'root',
            'DataBasePassword' => '',

            'Debug' => true,
            'CustomErrors' => false
        ],

        
        'DetermineLanguage' => function(){
            $lang = 'en';

            if ( isset( $_COOKIE['lang'] ) ) {
                $lang = $_COOKIE['lang'];
            }

            return $lang;
        }

    ];