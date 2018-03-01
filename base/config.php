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


        'DefaultTitle' => 'smts_base',
        'DefaultProfilePic' => 'assets/user.png',

        
        'Env' => 'Dev',

        'Live' => [
            'BaseUrl' => 'https://base.simonstriekwold.nl/',

            'DataBaseName' => "simonstriekwold_nl_smts",
            'DataBaseUser' => 'simonstriekwold_nl_smts',
            'DataBasePassword' => 'zLtxPufNw2PM',

            'Debug' => false,
            'CustomErrors' => true
        ],

        'Dev' => [
            'BaseUrl' => 'http://localhost/smts_base/',

            'DataBaseName' => "smts_base",
            'DataBaseUser' => 'root',
            'DataBasePassword' => '',

            'Debug' => true,
            'CustomErrors' => false
        ]

    ];