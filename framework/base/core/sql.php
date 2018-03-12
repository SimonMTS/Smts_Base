<?php

    // ::find()     object / array / false
    // ::save()     true / false
    // ::delete()   true / false

    //->Where()
    //->andWhere()
    //->orWhere()
    //->orderBy()
    //->whereLike
    
    //->limit()
    //->offset()

    // ->one() 
    // ->count()
    // ->all()

    class Sql {
         
        public static function find( $table ){

            return new sql_find(" FROM `$table` ");

        }

        public static function Save( $table, $values ) {

            $db = sql_find::getInstance();

            $vals = '';
            $names = '';
            $exec_arr = [];

            foreach ( $values as $key => $value ) {
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
                return false;
            }

            return true;
        }

        public static function Update( $table, $row, $where, $values ) {

            $db = sql_find::getInstance();

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
                return false;
            }

            return true;

        }

        public static function Delete( $table, $row, $where ) {

            if ( isset($row) && isset($where) ) {
                $db = sql_find::getInstance();

                try {
                    $req = $db->prepare("DELETE FROM $table WHERE $row = :where");
                    $req->execute([':where' => $where]);
                } catch( PDOException $Exception ) {
                    return false;
                }

                return true;
            } else {
                return false;
            }

        }


        public static function AddBlob( $table, $id, $blob, $ext ) {

            $db = sql_find::getInstance();
            $req = $db->prepare("UPDATE $table SET pic = ?, pic_type = '$ext' WHERE id = '$id'");
		    $req->bindParam(1, $blob, PDO::PARAM_LOB);
            $req->execute();
            
            return true;

        }
        
        public static function RemoveDB( $name ) {

            if ( isset($name) && !empty($name) ) {
                $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                $db = new PDO('mysql:host=localhost', Smts::$config['DataBaseUser'], Smts::$config['DataBasePassword'], $pdo_options);

                try {
                    $req = $db->prepare("DROP DATABASE `$name`");
                    $req->execute();
                } catch( PDOException $Exception ) {
                    return $Exception->getMessage();
                }

                return true;
            }

        }

        public static function CreateDB( $name ) {

            if ( isset($name) && !empty($name) ) {
                $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                $db = new PDO('mysql:host=localhost', Smts::$config['DataBaseUser'], Smts::$config['DataBasePassword'], $pdo_options);

                try {
                    $req = $db->prepare("CREATE DATABASE `$name`");
                    $req->execute();
                } catch( PDOException $Exception ) {
                    return $Exception->getMessage();
                }

                return true;
            }

        }

        public static function CreateTable( $dbn, $prop ) {

            if ( isset($dbn) && !empty($dbn) && isset($prop) && sizeof($prop) > 0 ) {
                $db = sql_find::getInstance();
                $cols = '';

                foreach ( $prop as $key => $value ) {
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

        public static function AddPKey( $dbn, $prop ) {

            if ( isset($dbn) && !empty($dbn) && isset($prop) && !empty($prop) ) {
                $db = sql_find::getInstance();
                
                try {
                    $req = $db->prepare("ALTER TABLE `$dbn` ADD PRIMARY KEY(`$prop`)");
                    $req->execute();
                } catch( PDOException $Exception ) {
                    return $Exception->getMessage();
                }

                return true;
            }

        }

        public static function GetTables( $dbn ) {

            if ( isset($dbn) && !empty($dbn) ) {
                $db = sql_find::getInstance();
                
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
        
        public static function GetColumns( $dbn, $col ) {

            if ( isset($dbn) && !empty($dbn) ) {
                $db = sql_find::getInstance();
                
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

    class sql_find {

        private $sql;
        private $prop;

        private static $instance = NULL;
        
        public static function getInstance() {

            if (!isset(self::$instance)) {
                $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                self::$instance = new PDO('mysql:host=localhost;dbname='.Smts::$config['DataBaseName'], Smts::$config['DataBaseUser'], Smts::$config['DataBasePassword'], $pdo_options);
            }
            return self::$instance;

        }

        private static function exec( $sql, $params = [] ) {

            $db = self::getInstance();

            try {
                $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
                $req = $db->prepare( $sql );
                $req->execute( $params );
                $res = $req->fetchall();
            } catch( PDOException $Exception ) {
                return false;
            }

            return $res;

        }

        public function __construct( $sql ) {

            $this->sql = $sql;

        }


        public function where( $cond ) {
                
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "WHERE `".key($cond)."` = :".key($cond).$i." ";
            $this->prop[':'.key($cond).$i] = $cond[key($cond)];

            return $this;

        }

        public function whereLike( $cond ) {
            
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "WHERE `".key($cond)."` LIKE :".key($cond).$i." ";
            $this->prop[':'.key($cond).$i] = '%'.$cond[key($cond)].'%';

            return $this;

        }

        public function andWhere( $cond ) {
                
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "AND `".key($cond)."` = :".key($cond).$i." ";
            $this->prop[':'.key($cond).$i] = $cond[key($cond)];

            return $this;

        }
        
        public function orWhere( $cond ) {
            
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "OR `".key($cond)."` = :".key($cond).$i." ";
            $this->prop[':'.key($cond).$i] = $cond[key($cond)];

            return $this;

        }

        
        public function orderBy( $row, $direction = "DESC" ) {

            $this->sql .= "ORDER BY `$row` $direction ";

            return $this;

        }

        public function limit( $limit ) {
            
            $this->sql .= "LIMIT :limit ";

            $this->prop[':limit'] = $limit;

            return $this;

        }
        
        public function offset( $offset ) {
            
            $this->sql .= "OFFSET :offset ";
            
            $this->prop[':offset'] = $offset;

            return $this;
            
        }


        public function one() {
            
            $res = self::exec( "SELECT *".$this->sql, $this->prop );
            
            if ( isset( $res[0] ) ) {
                return $res[0];
            } else {
                return false;
            }

        }

        public function all() {
                
            return self::exec( "SELECT *".$this->sql, $this->prop );

        }
        
        public function count() {
            
            return self::exec( "SELECT count(*)".$this->sql, $this->prop )[0][0];

        }
        
    }