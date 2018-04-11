<?php
    return [

        'RewriteRules' => [

            'login'                             => 'base/users/login',

            '[controller]/p/[page]/s/[search]'  => 'base/[controller]/overview',
            '[controller]/p/[page]'             => 'base/[controller]/overview',
            
            'users/[action]/[id]'               => 'base/users/[action]',

            'dev/[controller]/[action]'         => 'dev/[controller]/[action]',
            'dev/[controller]'                  => 'dev/[controller]/overview',
            'dev'                               => 'dev/pages/home',

            '[module]/[controller]/[action]'    => '[module]/[controller]/[action]',
            '[controller]/[action]'             => 'base/[controller]/[action]',
            '[controller]'                      => 'base/[controller]/overview',

            '' => 'base/pages/home'

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