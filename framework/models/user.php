<?php

    class User extends model {

        public $id;
        public $name;
        public $password;
        public $password_rep;
        public $salt;
        public $role;
        public $pic;

        public $voornaam;
        public $achternaam;
        public $geslacht;
        public $geboorte_datum;
        public $address;

        public function rules() {

            return [
                [ ['name', 'password', 'password_rep', 'voornaam', 'achternaam', 'geslacht', 'geboorte_datum', 'address'], 'required' ],

                [ ['name'], 'unique' ],
                
                [ ['password', 'password_rep'], 'password' ],

                [ ['geslacht'], 'in', ['m', 'f'] ],
                
                [ ['name', 'password', 'voornaam', 'achternaam', 'geslacht'], 'type_string' ],

                [ ['pic'], 'image', 400 ],

                [ ['geboorte_datum'], 'date' ],

                [ ['address'], 'address' ]
            ];

        }

        public function attributes() {

            return [
                'name' => 'Username',
                'password' => 'Password',
                'password_rep' => 'Repeat Password',
                'pic' => 'Profile picture',
                'voornaam' => 'Firstname',
                'achternaam' => 'Lastname',
                'geslacht' => 'Gender',
                'geboorte_datum' => 'Date of birth',
                'address' => 'Address'
            ];

        }

        public function login() {

            Smts::$session = [
                "id" => $this->id,
                "name" => $this->name,
                "password" => $this->password,
                "salt" => $this->salt,
                "role" => $this->role,
                "pic" => $this->pic
            ];

        }

        public static function role($text) {

            switch ($text) {
                case 'User':
                    return 1;
                    break;
                case 'Admin':
                    return 777;
                    break;
                case 1:
                    return 'User';
                    break;
                case 777:
                    return 'Admin';
                    break;
                default:
                    return false;
                    break;
            }

        }
		
		public static function searchByName($text, $limit, $offset, $count = false) {

            if ( !$count ) {
                return Sql::find('user')
                    ->whereLike(['name' => $text])
                    ->orderBy('name', 'ASC')
                    ->limit($limit)
                    ->offset($offset)
                    ->all();
            } else {
                return Sql::find('user')
                    ->whereLike(['name' => $text])
                    ->count();
            }

		}

        public static function find( $id ) {

            $result = Sql::find('user')->where(['id' => $id])->one();

            if ( isset($result['id']) ) {
                $user = new User();
                $user->load( $result );
                return $user;
            } else {
                return false;
            }

        }

        public static function findByName( $name ) {

            $result = Sql::find('user')->where(['name' => $name])->one();

            if ( isset($result['id']) ) {
                $user = new User();
                $user->load( $result );
                return $user;
            } else {
                return false;
            }

        }

        public static function findByRole( $number ) {

            return Sql::find('user')->where(['role' => $number])->all();

        }

        public function save() {

            if ( !self::find($this->id) ) {
                if ( !self::findByName($this->name) ) {
                    return Sql::Save('user', [
                        'id' => $this->id,
                        'name' => $this->name,
                        'password' => $this->password,
                        'salt' => $this->salt,
                        'role' => $this->role,
                        'pic' => $this->pic,
                        'voornaam' => $this->voornaam,
                        'achternaam' => $this->achternaam,
                        'geslacht' => $this->geslacht,
                        'geboorte_datum' => $this->geboorte_datum,
                        'address' => $this->address
                    ]);
                } else {
                    return false;
                }
            } else {
                return Sql::Update('user', 'id', $this->id, [
                    'id' => $this->id,
                    'name' => $this->name,
                    'password' => $this->password,
                    'salt' => $this->salt,
                    'role' => $this->role,
                    'pic' => $this->pic,
                    'voornaam' => $this->voornaam,
                    'achternaam' => $this->achternaam,
                    'geslacht' => $this->geslacht,
                    'geboorte_datum' => $this->geboorte_datum,
                    'address' => $this->address
                ]);
            }

        }

        public function delete() {

            $user = self::find($this->id);
            
            if ($user) {
                if (explode('/', $user->pic)[1] == 'img') {
                    if ( !unlink($user->pic) ) {
                        return false;
                    }
                }

                return Sql::Delete('user', 'id', $this->id);
            } else {
                return false;
            }
            
        }
    }