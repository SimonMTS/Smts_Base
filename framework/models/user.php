<?php

    class User extends model {

        public $id;
        public $name;
        public $password;
        public $password_rep;
        public $salt;
        public $role;
        public $pic;

        public $firstname;
        public $lastname;
        public $gender;
        public $dateofbirth;
        public $address;

        public function rules() {

            return [
                [ ['name', 'password', 'password_rep', 'firstname', 'lastname', 'gender', 'dateofbirth', 'address'], 'required' ],

                [ ['name'], 'unique' ],
                
                [ ['password', 'password_rep'], 'password' ],

                [ ['gender'], 'in', ['m', 'f'] ],
                
                [ ['name', 'password', 'firstname', 'lastname', 'gender'], 'type_string' ],

                [ ['pic'], 'image', 400 ],

                [ ['dateofbirth'], 'date' ],

                [ ['address'], 'address' ]
            ];

        }

        public function attributes() {

            return [
                'name' => 'Username',
                'password' => 'Password',
                'password_rep' => 'Repeat Password',
                'pic' => 'Profile picture',
                'firstname' => 'Firstname',
                'lastname' => 'Lastname',
                'gender' => 'Gender',
                'dateofbirth' => 'Date of birth',
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
                    return Sql::Save('user')->Values([
                        'id' => $this->id,
                        'name' => $this->name,
                        'password' => $this->password,
                        'salt' => $this->salt,
                        'role' => $this->role,
                        'pic' => $this->pic,
                        'firstname' => $this->firstname,
                        'lastname' => $this->lastname,
                        'gender' => $this->gender,
                        'dateofbirth' => $this->dateofbirth,
                        'address' => $this->address
                    ]);
                } else {
                    return false;
                }
            } else {
                return Sql::Update('user')->Where(['id' => $this->id])->Values([
                    'id' => $this->id,
                    'name' => $this->name,
                    'password' => $this->password,
                    'salt' => $this->salt,
                    'role' => $this->role,
                    'pic' => $this->pic,
                    'firstname' => $this->firstname,
                    'lastname' => $this->lastname,
                    'gender' => $this->gender,
                    'dateofbirth' => $this->dateofbirth,
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

                return Sql::Delete('user')->Where(['id' => $this->id])->all();
            } else {
                return false;
            }
            
        }
    }