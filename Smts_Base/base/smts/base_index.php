<?php

    require_once('base/smts/base_classes.php');

    if ( isset($var[1]) && !empty($var[1]) && !isset($var[2]) ) {
        $var[2] = 'overview';
    }

    if (isset($var[1]) && isset($var[2])) {
        $controller = $var[1];
        $action     = $var[2];
    } else {
        $controller = 'base';
        $action     = 'home';
    }

    $controllers = array_diff(scandir("./base/smts/controllers"), ['..', '.']);
    
    if ( in_array( ($controller.'_controller.php'), $controllers) )
    {
        require_once('controllers/' . $controller . '_controller.php');

        $controller = $controller.'Controller';
        $actions = get_class_methods( $controller );

        if ( in_array( $action, $actions ) )
        {
            $controller::beforeAction();
            $controller::{$action}($var);
        }
        else
        {
            var_dump( $action );
            var_dump( $actions );
            Base::error_view(404);
        }
    }
    else
    {
        Base::error_view(404);
    }