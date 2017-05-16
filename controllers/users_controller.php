<?php
    require_once('/models/user.php');

    class usersController {

        public function login() {
            if (isset($_POST['user'])) {
                $user = User::findByName($_POST['user']['name']);

                if ( $user !== false && $user->password === hash('sha512', $_POST['user']['password']) ) {
                    $_SESSION['user'] = [
                        "id" => $user->id,
                        "name" => $user->name,
                        "password" => $user->password,
                        "role" => $user->role
                    ];

                    Base::Redirect($GLOBALS['config']['base_url']);
                } else {
                    Base::Render('users/login.php');
                }
            } else {
                Base::Render('users/login.php');
            }
        }

        public function logout() {
            $_SESSION['user'] = null;
            Base::Redirect($GLOBALS['config']['base_url']);
        }

        public function overview() { //TODO
            if ($_SESSION['user']['role'] > 1) {
                $users = user::findByRole($_SESSION['user']['role'], false);

                Base::Render('users/overview.php');
            } else {
                return call('pages', 'error');
            }
        }

        public function view() {
            Base::Render('users/view.php');
        }

        public function create() {
            if (
                isset($_POST['user']) &&
                isset($_POST['user']['name']) && !empty($_POST['user']['name']) &&
                isset($_POST['user']['password']) && !empty($_POST['user']['password']) &&
                $_POST['user']['password'] === $_POST['user']['passwordrep'] &&
                !user::findByName($_POST['user']['name'])
            ) {   
                $user = new User(
                    Base::Genetate_id(),
                    $_POST['user']['name'],
                    hash('sha512', $_POST['user']['password']),
                    1
                );

                if ($user->save()) {
                    Base::Redirect($GLOBALS['config']['base_url']);
                } else {
                    Base::Render('pages/error.php');
                }
            } else {
                Base::Render('users/create.php');
            }
        }

        public function edit() { //TODO
            $id = $_GET['var1'];
            $user = User::find(new MongoId($id));
            $students = user::findByRole(2, true);

            if ($user !== false && (($user->_id == $_SESSION['user']['_id'] && $user->password == $_SESSION['user']['password']) || ($_SESSION['user']['role'] > $user->role))) {
                if (
                    isset($_POST['user']) &&
                    isset($_POST['user']['name']) && !empty($_POST['user']['name']) &&
                    isset($_POST['user']['firstname']) && !empty($_POST['user']['firstname']) &&
                    isset($_POST['user']['lastname']) && !empty($_POST['user']['lastname']) &&
                    isset($_POST['user']['age']) && !empty($_POST['user']['age']) &&
                    isset($_POST['user']['gender']) && !empty($_POST['user']['gender']) &&
                    ((isset($_POST['user']['password']) && !empty($_POST['user']['password']) && $_POST['user']['password'] == $_POST['user']['passwordrep']) ||
                    (empty($_POST['user']['password']) && empty($_POST['user']['passwordrep'])))
                ) {
                    $user->name = $_POST['user']['name'];
                    $user->firstname = $_POST['user']['firstname'];
                    $user->lastname = $_POST['user']['lastname'];
                    $user->age = $_POST['user']['age'];
                    $user->gender = user::gender($_POST['user']['gender']);

                    if (isset($_POST['user']['class_code']) && !empty($_POST['user']['class_code'])) {
                        $user->class_code = $_POST['user']['class_code'];
                    }
                    if (isset($_POST['user']['child_id']) && !empty($_POST['user']['child_id'])) {
                        $user->child_id = $_POST['user']['child_id'];
                    }

                    if (!empty($_POST['user']['password'])) {
                        $user->password = $_POST['user']['password'];
                    }

                    if ($user->save()) {
                        Base::Redirect($GLOBALS['config']['base_url'] . "users/edit/" . $user->_id);
                    } else {
                        return call('pages', 'error');
                    }
                } else {
                    require_once('views/users/edit.php');
                }
            } else {
                return call('pages', 'error');
            }
        }

        public function delete() { //TODO
            $id = $_GET['var1'];
            $user = User::find(new MongoId($id));

            if ($_SESSION['user']['role'] > $user->role) {
                $user->delete();
                Base::Redirect($GLOBALS['config']['base_url'] . 'users/overview');
            } else {
                return call('pages', 'error');
            }
        }
    }
?>
