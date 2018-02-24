<?php
    require_once "models/user.php";

    class usersController extends Controller 
    {
        
        public static function login() 
        {
            if (isset($_POST['User'])) {
                $user = User::findByName($_POST['User']['name']);
                if ( $user != false && $user->password === Smts::HashString($_POST['User']['password'], $user->salt) ) {
                    $user->login();

                    Smts::Redirect(Smts::$config['BaseUrl']);
                } else {
                    Smts::Render('users/login');
                }
            } else {
                Smts::Render('users/login');
            }
        }

        public static function logout() 
        {
            $_SESSION['user'] = null;
            Smts::Redirect(Smts::$config['BaseUrl']);
        }

        public static function overview($var) 
        { //todo
            if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 777) {

				if (isset($_POST['var2']) && !empty($_POST['var2'])) {
                    Smts::Redirect(Smts::$config['BaseUrl'].'users/overview/1/'.Smts::Sanitize($_POST['var2']));
                } elseif (isset($_POST['var2']) && empty($_POST['var2'])) {
                    Smts::Redirect(Smts::$config['BaseUrl'].'users/overview/1');
                }

                if (isset($var['page'])) {
                    $page = (int) Smts::Sanitize( $var['page'] );
                    if ($page < 1) {
                        Smts::Redirect( Smts::$config['BaseUrl'].'users/overview/1' );
                    }
                } else {
                    $page = 1;
                }

                if (isset($var['search'])) {
                    $search = Smts::Sanitize($var['search']);
                    $users = User::searchByName($search, 12, (($page - 1) * 12) );
                    $pagination = User::searchByName($search, 9999, 0, true ) / 12;
                } else {
                    $users = User::searchByName('', 12, (($page - 1) * 12) );
                    $pagination = User::searchByName('', 9999, 0, true ) / 12;
                }

                if (isset($var['search'])) {
                    $searchpar = '/'.$var['search'];
                } else {
                    $searchpar = null;
                }

                Smts::Render('users/overview', [
                    'users' => $users,
                    'page' => $page,
                    'searchpar' => $searchpar,
                    'var' => $var,
                    'pagination' => $pagination
                ]);
            } else {
                Smts::Render('pages/error', [
                    'type' => 'custom',
                    'data' => [
                        'Denied',
                        'This page requires admin privileges'
                    ]
                ]);
            }
        }

        public static function view($var) 
        {
            $id = Smts::Sanitize( $var['id'] );
            $user = User::Find($id);
            
            if (
                $user !== false && isset($_SESSION['user']) && (
                    ($user->id == $_SESSION['user']['id'] && $user->password == $_SESSION['user']['password']) || 
                    ($_SESSION['user']['role'] == 777)
                )
            ) {
                Smts::Render('users/view', [
                    'user' => $user,
                ]);
            } else {
                Smts::Render('pages/error', [
                    'type' => 'custom',
                    'data' => [
                        'Error',
                        'User not found'
                    ]
                ]);
            }
        }

        public static function create($var) 
        {
            $user = new User();

            if ( $user->load('post') && $user->validate() ) {
                $user->id = Smts::Genetate_id();
                $user->salt = Smts::Genetate_id();
                $user->role = 1;
                $user->password = Smts::Hash_String( $user->password, $user->salt );

                if ( $user->save() ) {
                    if ( !isset($_SESSION['user']) ) {
                        $user->login();
                    }

                    if ( $user->isAdmin() ) {
                        Smts::Redirect(Smts::$config['BaseUrl'].'users/overview');
                    } else {
                        Smts::Redirect(Smts::$config['BaseUrl']);
                    }
                } else {
                    Smts::Render('pages/error', [
                        'type' => 'custom',
                        'data' => [
                            'Error',
                            'Could not save user'
                        ]
                    ]);
                }
            } else {
                Smts::Render('users/create', [
					'var' => $var,
                    'user' => $user
				]);
            }
        }

        public static function edit($var) 
        { 
            $id = Smts::Sanitize( $var['id'] );
            $user = User::find($id);
            $model = clone($user);
            $model->password_rep = $model->password;
            
            if ( $model->load('post') && $model->validate() ) {

                if ( $user->password != $model->password ) {
                    $user->password = Smts::HashString( $model->password, $user->salt );
                }

                if ( is_string($model->pic) && sizeof( explode('/', $model->pic) ) == 3 ) {
                    $user->pic = $model->pic;
                }

                $user->name = $model->name;
                $user->voornaam = $model->voornaam;
                $user->achternaam = $model->achternaam;
                $user->geslacht = $model->geslacht;
                $user->geboorte_datum = $model->geboorte_datum;
                $user->adres = $model->adres;

                if ( $user->save() ) {
                    $user->login();
                    Smts::Redirect(Smts::$config['BaseUrl'].'users/view/'.$user->id);
                } else {
                    Smts::Render('pages/error', [
                        'type' => 'custom',
                        'data' => [
                            'Error',
                            'Could not save user'
                        ]
                    ]);
                }
            } else {
                Smts::Render('users/edit', [
                    'user' => $user,
                    'var' => $var
                ]);
            }
        }

        public static function delete($var) 
        {
            if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 777) {
                $id = Smts::Sanitize( $var[2] );
                $user = User::find($id);
                
                if ( $_SESSION['user']['role'] > $user->role && $user->delete() ) {
                    Smts::Redirect(Smts::$config['BaseUrl'] . 'users/overview');
                } else {
                    Smts::Render('pages/error', [
                       'type' => 'custom',
                        'data' => [
                            'Denied',
                            'You can only delete user of a lower role than your own'
                        ]
                    ]);
                }
            } else {
                Smts::Render('pages/error', [
                    'type' => 'custom',
                    'data' => [
                        'Denied',
                        'This page requires admin privileges'
                    ]
                ]);
            }
        }
    }