<?php

    $controllers = array_diff(scandir("./controllers"), ['..', '.']);

    if ( in_array( ( $url['controller'].'_controller.php'), $controllers ) )
    {
        require_once('controllers/' . $url['controller'] . '_controller.php');

        $controller = $url['controller'].'Controller';
        $actions = get_class_methods( $controller );

        if ( in_array( $url['action'], $actions ) )
        {
            $controller::beforeAction();
            $controller::{$url['action']}($urlParams);
        }
        else
        {
            Smts::ErrorView(404);
        }
    }
    else
    {
        Smts::ErrorView(404);
    }