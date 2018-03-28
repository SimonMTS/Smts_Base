<?php
    require "base/database_struct.php";

    class setupController extends Controller {

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

                self::setupdb();

            sleep(1);ob_flush();flush();

                if ( databaseStruct::getData() ) {

                    echo json_encode(['msg' => 'Done adding data <br><br>', 'isDone' => false]);

                } else {

                    echo json_encode(['msg' => 'Errors adding data <br><br>', 'isDone' => false]);

                }

            sleep(1);ob_flush();flush();

                Smts::$session = null;

                $end_time = new DateTime();
                echo json_encode(['msg' => 
                'Completed database reset. <br><br>Operation took ' . $start_time->diff($end_time)->i . 'min ' . $start_time->diff($end_time)->s . ' sec. <br><br>', 
                'isDone' => true]);

            sleep(1);ob_flush();flush(); 

            ob_end_flush();

        }

        private static function setupdb() {

            Sql::Extra()->RemoveDB(Smts::$config['DataBaseName']);

            Sql::Extra()->CreateDB(Smts::$config['DataBaseName']);

            $databaseStruct = databaseStruct::getStructure();

            $pkeys = array_pop( $databaseStruct );

            foreach ( $databaseStruct as $table => $columns ) {
                Sql::Extra()->CreateTable( $table, $columns );
            }

            foreach ( $pkeys as $table => $column ) {
                Sql::Extra()->AddPKey( $table, $column );
            }

            echo json_encode(['msg' => 'Done creating database <br><br>', 'isDone' => false]);

        }
        
    }