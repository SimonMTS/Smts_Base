<?php
    require_once "models/user.php";

    class usersController extends Controller 
    {
        
        public static function login() 
        {
            if (isset($_POST['User'])) {
                $user = User::findByName($_POST['User']['name']);
                if ( $user != false && $user->password === Base::Hash_String($_POST['User']['password'], $user->salt) ) {
                    $_SESSION['user'] = [
                        "id" => $user->id,
                        "name" => $user->name,
                        "password" => $user->password,
                        "salt" => $user->salt,
                        "role" => $user->role,
                        "pic" => $user->pic
                    ];

                    Base::Redirect($GLOBALS['config']['base_url']);
                } else {
                    Base::Render('users/login');
                }
            } else {
                Base::Render('users/login');
            }
        }

        public static function logout() 
        {
            $_SESSION['user'] = null;
            Base::Redirect($GLOBALS['config']['base_url']);
        }

        public static function overview($var) 
        {
            if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 777) {

				if (isset($_POST['var2']) && !empty($_POST['var2'])) {
                    Base::Redirect($GLOBALS['config']['base_url'].'users/overview/1/'.Base::Sanitize($_POST['var2']));
                } elseif (isset($_POST['var2']) && empty($_POST['var2'])) {
                    Base::Redirect($GLOBALS['config']['base_url'].'users/overview/1');
                }

                if (isset($var[2])) {
                    $page = (int) Base::Sanitize( $var[2] );
                    if ($page < 1) {
                        Base::Redirect( $GLOBALS['config']['base_url'].'users/overview/1' );
                    }
                } else {
                    $page = 1;
                }

                if (isset($var[3])) {
                    $search = base::Sanitize($var[3]);
                    $users = User::searchByName($search, 12, (($page - 1) * 12) );
                } else {
                    $users = User::searchByName('', 12, (($page - 1) * 12) );
                }

                if (isset($var[3])) {
                    $searchpar = '/'.$var[3];
                } else {
                    $searchpar = null;
                }

                Base::Render('users/overview', [
                    'users' => $users,
                    'page' => $page,
                    'searchpar' => $searchpar,
                    'var' => $var
                ]);
            } else {
                Base::Render('pages/error', [
                    'type' => 'custom',
                    'data' => [
                        0 => 'Denied',
                        1 => 'This page requires admin privileges'
                    ]
                ]);
            }
        }

        public static function view($var) 
        {
            $id = Base::Sanitize( $var[2] );
            $user = User::Find($id);
            
            if ($user !== false && isset($_SESSION['user']) && (($user->id == $_SESSION['user']['id'] && $user->password == $_SESSION['user']['password']) || ($_SESSION['user']['role'] == 777))) {
                // $orders = Order::FindByUser($id);
                
                Base::Render('users/view', [
                    'user' => $user,
                    'orders' => []
                ]);
            } else {
                Base::Render('pages/error', [
                    'type' => 'custom',
                    'data' => [
                        0 => 'Error',
                        1 => 'User not found'
                    ]
                ]);
            }
        }

        public static function create($var) 
        {
            $user = new User();

            if (
                // isset($_POST['User']) &&
                // isset($_POST['User']['name']) && !empty($_POST['User']['name']) &&
                // isset($_POST['User']['password']) && !empty($_POST['User']['password']) &&
                // $_POST['User']['password'] === $_POST['User']['password_rep'] &&
                // !user::findByName($_POST['User']['name']) &&
                
                // isset($_POST['User']['voornaam']) && !empty($_POST['User']['voornaam']) &&
                // isset($_POST['User']['achternaam']) && !empty($_POST['User']['achternaam']) &&
                // isset($_POST['User']['geslacht']) && !empty($_POST['User']['geslacht']) &&
                // isset($_POST['User']['geboorte_datum']) && sizeof($_POST['User']['geboorte_datum']) == 3 &&
				// isset($_POST['User']['adres']) && sizeof($_POST['User']['adres']) == 4

                $user->load('post') && $user->validate()
            ) {
                $user->id = Base::Genetate_id();
                $user->salt = Base::Genetate_id();
                $user->role = 1;

                echo'<pre>';

                // echo'<hr>post<hr>';
                // var_dump( $_POST );

                // echo'<hr>load & validate<hr>';
                // var_dump( ( $user->load() && $user->validate() ) );

                echo'<hr>user<hr>';
                var_dump( $user );

                echo'</pre>';
                exit;

				// $exAdres = [
				// 	'',
				// 	'',
				// 	'',
				// 	''
				// ];
	
				// $adres = $_POST['User']['adres'];
				
				// for ($i=0; $i < 6; $i++) {
				// 	if (isset($adres[$i])) {
				// 		$exAdres[$i] = Base::Sanitize ($adres[$i]);
				// 	}
				// }
				// $jsonString = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$exAdres[0]+$exAdres[1],+$exAdres[2]+$exAdres[3]&key=AIzaSyB5osi-LV3EjHVqve1t7cna6R_9FCgxFys");
				// $parsedArray = json_decode($jsonString,true);
				
				// if (
				// 	!isset($parsedArray['results'][0]['address_components'][1]['long_name']) || 
				// 	!isset($parsedArray['results'][0]['address_components'][0]['long_name']) || 
				// 	!isset($parsedArray['results'][0]['address_components'][6]['long_name']) || 
				// 	!isset($parsedArray['results'][0]['address_components'][2]['long_name']) || 
				// 	!isset($parsedArray['results'][0]['address_components'][5]['long_name'])
				// ) {
				// 	Base::Redirect($GLOBALS['config']['base_url'].'users/create/wrongadres');
				// }

				// $result = $parsedArray['results'][0]['address_components'][1]['long_name'] . ', ' . $parsedArray['results'][0]['address_components'][0]['long_name'] . ', ' . $parsedArray['results'][0]['address_components'][6]['long_name'] . ', ' .  $parsedArray['results'][0]['address_components'][2]['long_name'] . ', ' . $parsedArray['results'][0]['address_components'][5]['long_name'];
				
				// if ( $_FILES['pic']['size'] > 0 ) {
                //     $pic = Base::Upload_file( $_FILES['pic'] );
                //     if (!$pic) {
                //         Base::Render('pages/error', [
                //         'type' => 'custom',
                //         'data' => [
                //             0 => 'Error',
                //             1 => 'Could not save image'
                //         ]
                //     ]);
                //     }
                // } else {
                //     $pic = 'assets/img/user.png';
                // }
                
                // $salt = Base::Genetate_id();

                // $user = new User(
                //     Base::Genetate_id(),
                //     Base::Sanitize( $_POST['User']['name'] ),
                //     Base::Hash_String( $_POST['User']['password'], $salt ),
                //     $salt,
                //     1,
                //     $pic,
                //     Base::Sanitize( $_POST['User']['voornaam'] ),
                //     Base::Sanitize( $_POST['User']['achternaam'] ),
                //     Base::Sanitize( $_POST['User']['geslacht'] ),
                //     implode( '/', $_POST['User']['geboorte_datum'] ),
				// 	$result
                // );

                if ($user->save()) {
                    if ( !isset($_SESSION['user']) ) {
                        $_SESSION['user'] = [
                            "id" => $user->id,
                            "name" => $user->name,
                            "password" => $user->password,
                            "salt" => $user->salt,
                            "role" => $user->role,
                            "pic" => $user->pic
                        ];
                    }

                    if ($_SESSION['user']['role'] == 777) {
                        Base::Redirect($GLOBALS['config']['base_url'].'users/overview');
                    } else {
                        Base::Redirect($GLOBALS['config']['base_url']);
                    }
                } else {
                    Base::Render('pages/error', [
                        'type' => 'custom',
                        'data' => [
                            0 => 'Error',
                            1 => 'Could not save user'
                        ]
                    ]);
                }
            } else {
                Base::Render('users/create', [
					'var' => $var
				]);
            }
        }

        public static function edit($var) 
        {
            $id = Base::Sanitize( $var[2] );
            $user = User::find($id);

            if ($user !== false && isset($_SESSION['user']) && (($user->id == $_SESSION['user']['id'] && $user->password == $_SESSION['user']['password']) || ($_SESSION['user']['role'] == 777))) {
                if (
                    isset($_POST['User']) &&
                    isset($_POST['User']['name']) && !empty($_POST['User']['name']) &&
                    ((isset($_POST['User']['password']) && !empty($_POST['User']['password']) && $_POST['User']['password'] == $_POST['User']['passwordrep']) ||
                    (empty($_POST['User']['password']) && empty($_POST['User']['passwordrep'])))
                ) {
                    $user->name = Base::Sanitize( $_POST['User']['name'] );
                    $user->voornaam = Base::Sanitize( $_POST['User']['voornaam'] );
                    $user->achternaam = Base::Sanitize( $_POST['User']['achternaam'] );
                    $user->geslacht = Base::Sanitize( $_POST['User']['geslacht'] );
                    $user->geboorte_datum = implode( '/', $_POST['User']['geboorte_datum'] );
					
					$exAdres = [
						'',
						'',
						'',
						''
					];
		
					$adres = $_POST['User']['adres'];
					
					for ($i=0; $i < 6; $i++) {
						if (isset($adres[$i])) {
							$exAdres[$i] = str_replace(' ', '%20', Base::Sanitize($adres[$i]));
						}
					}

					$jsonString = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$exAdres[0]+$exAdres[1],+$exAdres[2]+$exAdres[3]&key=AIzaSyB5osi-LV3EjHVqve1t7cna6R_9FCgxFys");
					$parsedArray = json_decode($jsonString,true);
					
					if (
						!isset($parsedArray['results'][0]['address_components'][1]['long_name']) || 
						!isset($parsedArray['results'][0]['address_components'][0]['long_name']) || 
						!isset($parsedArray['results'][0]['address_components'][6]['long_name']) || 
						!isset($parsedArray['results'][0]['address_components'][2]['long_name']) || 
						!isset($parsedArray['results'][0]['address_components'][5]['long_name'])
					) {
						Base::Redirect($GLOBALS['config']['base_url'].'users/edit/'. $user->id .'/warn/U heeft uw adres niet correct ingevoerd');exit;
					}

                    $result = $parsedArray['results'][0]['address_components'][1]['long_name'] . ', ' . $parsedArray['results'][0]['address_components'][0]['long_name'] . ', ' . $parsedArray['results'][0]['address_components'][6]['long_name'] . ', ' .  $parsedArray['results'][0]['address_components'][2]['long_name'] . ', ' . $parsedArray['results'][0]['address_components'][5]['long_name'];
					
					$user->adres = $result;
					
                    if (!empty($_POST['User']['password'])) {
                        $user->password = Base::Hash_String($_POST['User']['password'], $user->salt);
                    }

                    if ($_FILES['pic']['size'] > 0) {
                        $user->pic = Base::Upload_file( $_FILES['pic'] );
                    }
                    
                    if (!$user->pic) {
                        Base::Redirect($GLOBALS['config']['base_url'].'users/edit/'. $user->id .'/warn/U heeft een verkeerde foto toegevoegd');exit;
                    }

                    if ($user->save()) {
                        if ( $_SESSION['user']['id'] == $user->id ) {
                            $_SESSION['user'] = [
                                "id" => $user->id,
                                "name" => $user->name,
                                "password" => $user->password,
                                "salt" => $user->salt,
                                "role" => $user->role,
                                "pic" => $user->pic
                            ];
                        }
                        Base::Redirect($GLOBALS['config']['base_url'] . "users/view/" . $user->id);
                    } else {
                        Base::Render('pages/error', [
                            'type' => 'custom',
                            'data' => [
                                0 => 'Error',
                                1 => 'Could not save user'
                            ]
                        ]);
                    }
                } else {
                    Base::Render('users/edit', [
                        'user' => $user,
						'var' => $var
                    ]);
                }
            } else {
                Base::Render('pages/error', [
                    'type' => 'custom',
                    'data' => [
                        0 => 'Denied',
                        1 => 'This page requires admin privileges'
                    ]
                ]);
            }
        }

        public static function delete($var) 
        {
            if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 777) {
                $id = Base::Sanitize( $var[2] );
                $user = User::find($id);

                if ($_SESSION['user']['role'] > $user->role) {
                    $user->delete();
                    Base::Redirect($GLOBALS['config']['base_url'] . 'users/overview');
                } else {
                    Base::Render('pages/error', [
                       'type' => 'custom',
                        'data' => [
                            0 => 'Denied',
                            1 => 'You can only delete user of a lower role than you own'
                        ]
                    ]);
                }
            } else {
                Base::Render('pages/error', [
                    'type' => 'custom',
                    'data' => [
                        0 => 'Denied',
                        1 => 'This page requires admin privileges'
                    ]
                ]);
            }
        }
    }