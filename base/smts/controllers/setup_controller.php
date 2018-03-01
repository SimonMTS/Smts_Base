<?php
    require_once "models/user.php";

    class setupController extends Controller 
    {

        public static function beforeAction() 
        {
            self::$layout = 'empty';
        }

        public static function init($var) 
        { 
            Smts::Render('layout/empty');
            
            $pw = 'pw';
            $start_time = new DateTime();

            if ( !(isset($var[3]) && $var[3] == $pw) && !(isset($var[3]) && $var[3] == $pw.'confirmed') ) {
                echo'error: wrong password<br><br>';exit;
            }

            if ($var[3] == $pw) {
                echo 'Are you sure you want to create/reset the database? <br><br> als dit niet werkt moet de max_execution_time omhoog in php.ini <br><br> <a href="'.Smts::$config['BaseUrl'].'smts/setup/init/'.$pw.'confirmed">Yes</a>';
                exit;
            }

            self::setupdb();
            self::addusers();

            Smts::$session = null;

            echo 'Admin login is:<br> -Name: beheerder<br> -Password: beheerder<br><br>';
            $end_time = new DateTime();
            echo 'operation took ' . $start_time->diff($end_time)->i . 'min ' . $start_time->diff($end_time)->s . ' sec. <br><br>';

            echo '<a href="' . Smts::$config['BaseUrl'] . '">home</a>';
        }

        private static function setupdb() 
        {
            Sql::RemoveDB(Smts::$config['DataBaseName']);

            Sql::CreateDB(Smts::$config['DataBaseName']);

            Sql::CreateTable('user', [
                'id' => 'varchar(256)',
                'name' => 'varchar(256)',
                'password' => 'varchar(256)',
                'salt' => 'varchar(256)',
                'role' => 'int(6)',
                'pic' => 'varchar(256)',
                'voornaam' => 'varchar(256)',
                'achternaam' => 'varchar(256)',
                'geslacht' => 'varchar(3)',
                'geboorte_datum' => 'varchar(256)',
                'address' => 'varchar(256)'
            ]);
            Sql::AddPKey('user', 'id');

            echo'done creating database<br><br>';
        }

        private static function addusers() 
        {
            
            $salt = Smts::GenetateId();
            $user_data = [
                'id' => Smts::GenetateId(),
                'name' => 'beheerder',
                'password' => Smts::HashString('beheerder', $salt),
                'salt' => $salt,
                'role' => 777,
                'pic' => 'assets/user.png',
                'voornaam' => 'Simon',
                'achternaam' => 'Striekwold',
                'geslacht' => 'm',
                'geboorte_datum' => date('d/m/Y:H:i:s', strtotime( '19-3-1999' )),
                'address' => 'Teugenaarsstraat, 86, 5348JE, Oss, Nederland'
            ];

            $user = new user();
            $user->load($user_data);

            $users[] = $user;
            
            for ($i=1; $i < 128; $i++) {
                $salt = Smts::GenetateId();

                $user_data = [
                    'id' => Smts::GenetateId(),
                    'name' => 'test'.$i,
                    'password' => Smts::HashString('test'.$i, $salt),
                    'salt' => $salt,
                    'role' => 1,
                    'pic' => 'assets/user.png',
                    'voornaam' => 'voornaam'.$i,
                    'achternaam' => 'achternaam'.$i,
                    'geslacht' => 'm',
                    'geboorte_datum' => date('d/m/Y:H:i:s', strtotime( '27-6-1993' )),
                    'address' => 'Teugenaarsstraat, 86, 5348JE, Oss, Nederland'                   
                ];

                $user = new user();
                $user->load($user_data);

                $users[] = $user;
            }
            
            foreach ($users as $user) {
                if ( !$user->save() ) {
                    echo'error<br><br>';
                }
            }

            echo'done adding users<br><br>';
        }
    }