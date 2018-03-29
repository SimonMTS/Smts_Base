<?php
    require "models/user.php";

    class databaseStruct {

        public static function getStructure() {

            return [

                'user' => [
                    'id' => 'varchar(256)',
                    'name' => 'varchar(256)',
                    'password' => 'varchar(256)',
                    'salt' => 'varchar(256)',
                    'role' => 'int(6)',
                    'pic' => 'varchar(256)',
                    'firstname' => 'varchar(256)',
                    'lastname' => 'varchar(256)',
                    'gender' => 'varchar(3)',
                    'dateofbirth' => 'varchar(256)',
                    'address' => 'varchar(256)'
                ],

                'primaryKeys' => [
                    'user' => 'id'
                ]

            ];

        }

        public static function getData() {

            $salt = Smts::GenerateId();
            $user_data = [
                'id' => Smts::GenerateId(),
                'name' => 'beheerder',
                'password' => Smts::HashString('beheerder', $salt),
                'salt' => $salt,
                'role' => 777,
                'pic' => Smts::$config['DefaultProfilePic'],
                'firstname' => 'Simon',
                'lastname' => 'Striekwold',
                'gender' => 'm',
                'dateofbirth' => date('d/m/Y:H:i:s', strtotime( '19-3-1999' )),
                'address' => 'Teugenaarsstraat, 86, 5348JE, Oss, Nederland'
            ];

            $user = new user();
            $user->load($user_data);

            $users[] = $user;
            
            for ($i=1; $i < 32; $i++) {
                $salt = Smts::GenerateId();

                $user_data = [
                    'id' => Smts::GenerateId(),
                    'name' => 'test'.$i,
                    'password' => Smts::HashString('test'.$i, $salt),
                    'salt' => $salt,
                    'role' => 1,
                    'pic' => Smts::$config['DefaultProfilePic'],
                    'firstname' => 'voornaam'.$i,
                    'lastname' => 'achternaam'.$i,
                    'gender' => 'm',
                    'dateofbirth' => date('d/m/Y:H:i:s', strtotime( '27-6-1993' )),
                    'address' => 'Teugenaarsstraat, 86, 5348JE, Oss, Nederland'                   
                ];

                $user = new user();
                $user->load($user_data);

                $users[] = $user;
            }

            $success = true;
            foreach ($users as $user) {
                if ( !$user->save() ) {
                    $success = false;
                }
            }

            return $success;

        }

    }