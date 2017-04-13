<?php

    class db {
        public static function init() {
            $mongo = new MongoClient($GLOBALS['config']['mongodb']);

            return $mongo->tovuti;
        }
    }

    class Redirect {
        public static function to($url) {
            if (headers_sent()) {
                echo '<meta http-equiv="Location" content="' . $url . '">';
                echo '<script> location.replace("' . $url . '"); </script>';
                echo '<a href="' . $url . '">' . $url . '</a>';
                exit;
            } else {
                header('location: ' . $url);exit;
            }
        }
    }
?>
