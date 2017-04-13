<?php
    class PagesController {

        public function home() {

            if (isset($_SESSION['user']['name'])) {
                $name = $_SESSION['user']['name'];
            } else {
                $name = 'bezoeker';
            }

            require_once('views/pages/home.php');
        }

        public function error() {
            require_once('views/pages/error.php');
        }

        public function faq() {
            require_once('views/pages/faq.php');
        }
    }
?>
