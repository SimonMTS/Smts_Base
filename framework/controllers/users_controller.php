<?php
    require "models/user.php";

    class usersController extends Controller {
        
        public static function login( $var ) {

            if ( isset($_POST['User']) ) {

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

        public static function logout( $var ) {

            Smts::$session = null;
            Smts::Redirect( Smts::$config['BaseUrl'] );

        }

        public static function overview( $var ) {

            if ( isset(Smts::$session['role']) && Smts::$session['role'] == 777 ) {

                $search = '';
                $page = 1;

                if ( isset( $var['search'] ) ) {
                    $search = Smts::Sanitize( $var['search'] );
                }

                if ( isset( $var['page'] ) ) {
                    $page = (int) Smts::Sanitize( $var['page'] );
                }
                
                $ipp = 12; // items per page
                $users = User::searchByName( $search, $ipp, (( $page-1 ) * $ipp) );
                $pagination = User::searchByName( $search, 0, 0, true ) / $ipp;
                
                Smts::Render('users/overview', [
                    'users' => $users,
                    'search' => $search,
                    'page' => $page,
                    'pagination' => $pagination
                ]);
            } else {
                Smts::ErrorView('custom', [
                    'Denied',
                    'This page requires admin privileges'
                ]);
            }

        }

        public static function view( $var ) {

            if ( isset( $var['id'] ) ) {
                $id = Smts::Sanitize( $var['id'] );
                $user = User::find($id);
            } else {
                Smts::ErrorView('custom', [
                    'Error',
                    'Could not find user'
                ]);
            }
            
            if (
                $user !== false && isset(Smts::$session) && (
                    ($user->id == Smts::$session['id'] && $user->password == Smts::$session['password']) || 
                    (Smts::$session['role'] == 777)
                )
            ) {
                Smts::Render('users/view', [
                    'user' => $user,
                ]);
            } else {
                Smts::ErrorView('custom', [
                    'Error',
                    'User not found'
                ]);
            }

        }

        public static function create( $var ) {

            $user = new User();

            if ( $user->load('post') && $user->validate() ) {
                $user->id = Smts::GenerateId();
                $user->salt = Smts::GenerateId();
                $user->role = 1;
                $user->password = Smts::HashString( $user->password, $user->salt );

                if ( $user->save() ) {
                    if ( !isset(Smts::$session['id']) ) {
                        $user->login();
                    }

                    if ( Smts::$session['role'] == 777 ) {
                        Smts::Redirect(Smts::$config['BaseUrl'].'users');
                    } else {
                        Smts::Redirect(Smts::$config['BaseUrl']);
                    }
                } else {
                    Smts::ErrorView('custom', [
                        'Error',
                        'Could not save user'
                    ]);
                }
            } else {
                Smts::Render('users/create', [
					'var' => $var,
                    'user' => $user
				]);
            }

        }

        public static function edit( $var ) {

            if ( isset( $var['id'] ) ) {
                $id = Smts::Sanitize( $var['id'] );
                $user = User::find($id);
            } else {
                Smts::ErrorView('custom', [
                    'Error',
                    'Could not find user'
                ]);
            }

            if ( !$user || ( $user->role >= Smts::$session['role'] && $user->id != Smts::$session['id'] ) ) {
                Smts::ErrorView('custom', [
                    'Error',
                    'Could not find user'
                ]);
            }
            
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
                $user->address = $model->address;

                if ( $user->save() ) {
                    if ( $user->id == Smts::$session['id'] ) {
                        $user->login();
                    }
                    Smts::Redirect( Smts::$config['BaseUrl'].'users/view/'.$user->id );
                } else {
                    Smts::ErrorView('custom', [
                        'Error',
                        'Could not save user'
                    ]);
                }
            } else {
                Smts::Render('users/edit', [
                    'user' => $user
                ]);
            }

        }

        public static function delete( $var ) {

            if ( isset(Smts::$session['role']) && Smts::$session['role'] == 777 ) {
                $id = Smts::Sanitize( $var['id'] );
                $user = User::find($id);
                
                if ( Smts::$session['role'] > $user->role && $user->delete() ) {
                    Smts::Redirect(Smts::$config['BaseUrl'] . 'users');
                } else {
                    Smts::ErrorView('custom', [
                        'Denied',
                        'You can only delete user of a lower role than your own'
                    ]);
                }
            } else {
                Smts::ErrorView('custom', [
                    'Denied',
                    'This page requires admin privileges'
                ]);
            }

        }

    }