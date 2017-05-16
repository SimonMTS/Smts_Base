<?php

    class User {
        public $_id;
        public $name;
        public $password;
        public $role;
        public $class_code;
        public $child_id;
        public $firstname;
        public $lastname;
        public $age;
        public $gender;

        public function __construct($id, $name, $password, $role) {
            $this->id = $id;
            $this->name = $name;
            $this->password = $password;
            $this->role = $role;
        }

        public static function role($text) { //eval
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

        public static function all() {
            return Sql::Get('user');
        }

        public static function find($id) {
            $result = Sql::Get('user', 'id', $id);

            if (
                isset($result['id']) &&
                isset($result['name']) &&
                isset($result['password']) &&
                isset($result['role'])
            ) {
                return new User(
                    $result['id'],
                    $result['name'],
                    $result['password'],
                    $result['role']);
            } else {
                return false;
            }

        }

        public static function findByName($name) {
            $result = Sql::Get('user', 'name', $name);

            if (
                isset($result['id']) &&
                isset($result['name']) &&
                isset($result['password']) &&
                isset($result['role'])
            ) {
                return new User(
                    $result['id'],
                    $result['name'],
                    $result['password'],
                    $result['role']);
            } else {
                return false;
            }
        }

        public static function findByRole($number, $lt) {
            $db = db::init();

            $col = $db->user;

            if (!$lt) {
                $result = $col->find( ["role" => ['$lt' => $number]] );
            } else {
                $result = $col->find( ["role" => $number] );
            }

            return $result;
        }

        public function save() {
            if ( !self::find($this->id) ) {
                Sql::Save('user', [
                    'id' => $this->id,
                    'name' => $this->name,
                    'password' => $this->password,
                    'role' => $this->role,
                ]);

                $_SESSION['user'] = [
                    "id" => $this->id,
                    "name" => $this->name,
                    "password" => $this->password,
                    "role" => $this->role
                ];

                return true;
            } else {
                Sql::Update('user', 'id', $this->id, [
                    'id' => $this->id,
                    'name' => $this->name,
                    'password' => $this->password,
                    'role' => $this->role,
                ]);

                $_SESSION['user'] = [
                    "id" => $this->id,
                    "name" => $this->name,
                    "password" => $this->password,
                    "role" => $this->role
                ];

                return true;
            }
        }

        public function delete() {
            $db = db::init();

            $col = $db->user;

            $result = $col->remove(["_id" => $this->_id]);
        }
    }
?>
