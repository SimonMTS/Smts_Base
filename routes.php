<?php
    function call($controller, $action) {
        require_once('controllers/' . $controller . '_controller.php');

        // Links to the controllers
        switch($controller) {

            case 'pages':
                $controller = new PagesController();
            break;

            case 'users':
                require_once('models/user.php');
                require_once('models/doc.php');
                $controller = new UsersController();
            break;

            case 'docs':
                require_once('models/doc.php');
                $controller = new DocsController();
            break;
        }

        $controller->{$action}();
    }

    // All valid conntrollers and actions
    $controllers = [

        'pages' => [
            'home',
            'faq',
            'error'
        ],

        'users' => [
            'login',
            'logout',
            'overview',
            'view',
            'create',
            'edit',
            'delete'
        ],

        'docs' => [
            'create',
            'delete'
        ]
    ];

    if (array_key_exists($controller, $controllers)) {
        if (in_array($action, $controllers[$controller])) {
            call($controller, $action);
        } else {
            call('pages', 'error');
        }
    } else {
        call('pages', 'error');
    }
?>
