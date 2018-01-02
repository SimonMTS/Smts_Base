<?php

    class Sql {
        private static $instance = NULL;

        private static function getInstance() {
            if (!isset(self::$instance)) {
                $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                self::$instance = new PDO('mysql:host=localhost;dbname='.$GLOBALS['config']['DataBaseName'], $GLOBALS['config']['DataBase_user'], $GLOBALS['config']['DataBase_password'], $pdo_options);
            }
            return self::$instance;
        }

        // Sql::Get('user', 'id', 'test_id');
        public static function Get($table, $row = '1', $where = '1') {
            $db = self::getInstance();

            try {
                $req = $db->prepare("SELECT * FROM $table WHERE $row = :where");
                $req->execute([':where' => $where]);
                $res = $req->fetchall();
            } catch( PDOException $Exception ) {
                return $Exception->getMessage();
            }

            return $res;
        }

        //Sql::GetSorted('game', 'views', 3)
        public static function GetSorted($table, $row, $limit = 4) {
            $db = self::getInstance();

            if ($limit) {
                try {
                    $req = $db->prepare("SELECT * FROM $table ORDER BY $row DESC LIMIT $limit");
                    $req->execute();
                    $res = $req->fetchall();
                } catch( PDOException $Exception ) {
                    return $Exception->getMessage();
                }
                
                return $res;
            } else {
                try {
                    $req = $db->prepare("SELECT * FROM $table ORDER BY $row DESC");
                    $req->execute();
                    $res = $req->fetchall();
                } catch( PDOException $Exception ) {
                    return $Exception->getMessage();
                }
                
                return $res;
            }
        }

        // Sql::Search('user', 'name', 'beheerder1');
        public static function Search($table, $row = '', $like = '', $limit = 11, $offset = 0, $Hwhere = 1, $HwhereIs = 1, $count = false) {
            $db = self::getInstance();

            if ( $Hwhere != 1 && $HwhereIs != 1 ) {
                $HwhereQ = " `$Hwhere` = '$HwhereIs' AND ";
            } else {
                $HwhereQ = '';
            }

            if ( $count ) {
                $countq = 'count(*)';
            } else {
                $countq = '*';
            }

            try {
                $req = $db->prepare("SELECT $countq FROM $table WHERE $HwhereQ $row LIKE :like LIMIT $limit OFFSET $offset");
                $req->execute([
                    ':like' => "%".$like."%"
                    ]);
                $res = $req->fetchall();
            } catch( PDOException $Exception ) {
                return $Exception->getMessage();
            }

            if ( $count ) {
                return intval($res[0][0]);
            } else {
                return $res;
            }
        }

        // Sql::Save('user', [
        //     'id' => 'test_id',
        //     'name' => 'test_name',
        //     'password' => 'test_password',
        //     'salt' => 'test_salt',
        //     'role' => 1,
        // ]);
        public static function Save($table, $values) {
            $db = self::getInstance();

            $vals = '';
            $names = '';
            $exec_arr = [];

            foreach ($values as $key => $value) {
                if ( array_search($key, array_keys($values)) !== count($values)-1 ) {
                    $names = $names . $key . ', ';
                    $vals = $vals . ':' . $key . ', ';
                } else {
                    $names = $names . $key;
                    $vals = $vals . ':' . $key;
                }
                $exec_arr[':'.$key] = $value;
            }

            try {
                $req = $db->prepare("INSERT INTO $table ($names) VALUES ($vals)");
                $req->execute($exec_arr);
            } catch( PDOException $Exception ) {
                return $Exception->getMessage();
            }

            return true;
        }


        // Sql::Update('user', 'id',  'test', [
        //     'name' => 'test_name',
        //     'password' => 'test_password',
        //     'salt' => 'test_slat',
        // ]);
        public static function Update($table, $row, $where, $values) {
            $db = self::getInstance();

            $changes = '';
            $exec_arr = [
                ':where' => $where
            ];

            foreach ($values as $key => $value) {
                if ( array_search($key, array_keys($values)) !== count($values)-1 ) {
                    $changes = $changes . $key . ' = :' . $key . ', ';
                } else {
                    $changes = $changes . $key . ' = :' . $key;
                }
                $exec_arr[':'.$key] = $value;
            }

            try {
                $req = $db->prepare("UPDATE $table SET $changes WHERE $row = :where");
                $req->execute($exec_arr);
            } catch( PDOException $Exception ) {
                return $Exception->getMessage();
            }

            return true;
        }

        
        public static function AddBlob($table, $id, $blob, $ext) {
            $db = self::getInstance();
            // $sth = $pdo->prepare("INSERT INTO blobtest ( type, picture ) VALUES ( ?, ? )");
            $req = $db->prepare("UPDATE $table SET pic = ?, pic_type = '$ext' WHERE id = '$id'");
		    $req->bindParam(1, $blob, PDO::PARAM_LOB);
		    $req->execute();
        }

        // Sql::Delete('user', 'id', 'test_id');
        public static function Delete($table, $row, $where) {
            if (isset($row) && isset($where)) {
                $db = self::getInstance();

                try {
                    $req = $db->prepare("DELETE FROM $table WHERE $row = :where");
                    $req->execute([':where' => $where]);
                } catch( PDOException $Exception ) {
                    return $Exception->getMessage();
                }

                return true;
            } else {
                return false;
            }
        }

        // Sql::RemoveDB('uxxx');
        public static function RemoveDB($name) {
            if (isset($name) && !empty($name)) {
                $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                $db = new PDO('mysql:host=localhost', $GLOBALS['config']['DataBase_user'], $GLOBALS['config']['DataBase_password'], $pdo_options);

                try {
                    $req = $db->prepare("DROP DATABASE `$name`");
                    $req->execute();
                } catch( PDOException $Exception ) {
                    return $Exception->getMessage();
                }

                return true;
            }
        }

        // Sql::CreateDB('uxxx');
        public static function CreateDB($name) {
            if (isset($name) && !empty($name)) {
                $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                $db = new PDO('mysql:host=localhost', $GLOBALS['config']['DataBase_user'], $GLOBALS['config']['DataBase_password'], $pdo_options);

                try {
                    $req = $db->prepare("CREATE DATABASE `$name`");
                    $req->execute();
                } catch( PDOException $Exception ) {
                    return $Exception->getMessage();
                }

                return true;
            }
        }

        // Sql::CreateTable('game', [
        //     'id' => 'varchar(256)',
        //     'name' => 'varchar(256)',
        //     'price' => 'int(10)',
        //     'descr' => 'longtext',
        //     'cover' => 'varchar(256)',
        //     'views' => 'int(20)'
        // ]);
        public static function CreateTable($dbn, $prop) {
            if (isset($dbn) && !empty($dbn) && isset($prop) && sizeof($prop) > 0) {
                $db = self::getInstance();
                $cols = '';

                foreach ($prop as $key => $value) {
                    $cols = $cols.'`'.$key.'` '.$value;
                    if (sizeof($prop) > sizeof(explode(',', $cols))) {
                        $cols = $cols.', ';
                    }
                }
                
                try {
                    $req = $db->prepare("CREATE TABLE $dbn ( $cols ) ");
                    $req->execute();
                } catch( PDOException $Exception ) {
                    return $Exception->getMessage();
                }

                return true;
            }
        }

        // Sql::AddPKey('game', 'id');
        public static function AddPKey($dbn, $prop) {
            if (isset($dbn) && !empty($dbn) && isset($prop) && !empty($prop)) {
                $db = self::getInstance();
                
                try {
                    $req = $db->prepare("ALTER TABLE `$dbn` ADD PRIMARY KEY(`$prop`)");
                    $req->execute();
                } catch( PDOException $Exception ) {
                    return $Exception->getMessage();
                }

                return true;
            }
        }

        // Sql::GetTables('smts_base');
        public static function GetTables($dbn) {
            if (isset($dbn) && !empty($dbn)) {
                $db = self::getInstance();
                
                try {
                    $req = $db->prepare("SHOW TABLES FROM $dbn");
                    $req->execute();
                    return $req->fetchAll();
                } catch( PDOException $Exception ) {
                    return $Exception->getMessage();
                }

                return true;
            }
        }
        
        // Sql::GetColumns('smts_base', 'user');
        public static function GetColumns($dbn, $col) {
            if (isset($dbn) && !empty($dbn)) {
                $db = self::getInstance();
                
                try {
                    $req = $db->prepare("SELECT COLUMN_NAME, DATA_TYPE, COLUMN_TYPE  FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$col."' AND TABLE_SCHEMA = '".$dbn."'");
                    $req->execute();
                    $cols = $req->fetchAll();

                    return $cols;
                } catch( PDOException $Exception ) {
                    return $Exception->getMessage();
                }

                return true;
            }
        }
    }