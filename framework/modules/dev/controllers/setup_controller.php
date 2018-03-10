<?php
    require "models/user.php";

    class setupController extends Controller {

        public static function beforeAction()  {

            self::$layout = 'empty';

        }

        public static function overview( $var ) {

            Dev::Render('setup/overview');

        }

        public static function init( $var ) {   

            $pw = 'pw';
            $start_time = new DateTime();

            if ( !(isset($var['pw']) && $var['pw'] == $pw) && !(isset($var['pw']) && $var['pw'] == $pw.'confirmed') ) {
                exit;
            }

            if ( $var['pw'] == $pw ) {
                exit;
            }

            echo json_encode(['msg' => 'Started Database reset <br><br>', 'isDone' => false]);
            ob_flush();flush();

            self::setupdb();ob_flush();flush();
            self::addusers();ob_flush();flush();

            sleep(1);

            Smts::$session = null;

            $end_time = new DateTime();
            echo json_encode(['msg' => 
            'Completed database reset. <br><br>Operation took ' . $start_time->diff($end_time)->i . 'min ' . $start_time->diff($end_time)->s . ' sec. <br><br>', 
            'isDone' => true]);
            ob_flush();flush();
            ob_end_flush();

        }

        private static function setupdb() {

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

            echo json_encode(['msg' => 'done creating database <br><br>', 'isDone' => false]);

        }

        private static function addusers() {

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

            echo json_encode(['msg' => 'done adding users <br><br>', 'isDone' => false]);
            
        }
    }