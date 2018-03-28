<?php

    class Sql {

        private static $instance = NULL;
        
        public static function getInstance( $DBinfo ) {

            if (!isset(self::$instance)) {
                $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                self::$instance = new PDO($DBinfo[0], $DBinfo[1], $DBinfo[2], $pdo_options);
            }
            return self::$instance;

        }

        public static function exec( $DBinfo, $sql, $params = [] ) {

            $db = self::getInstance( $DBinfo );

            try {

                $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
                $req = $db->prepare( $sql );
                $req->execute( $params );
                
                if ( strpos($req->queryString, 'SELECT') !== false ) {
                    $res = $req->fetchall();
                } else {
                    $res = true;
                }

            } catch( PDOException $Exception ) {
                return false;
            }

            return $res;

        }

         
        public static function Find( $table ){

            return new sql_find($table);

        }

        public static function Update( $table ) {

            return new sql_update($table);

        }

        public static function Save( $table ) {

            return new sql_insert($table);

        }

        public static function Delete( $table ) {

            return new sql_delete($table);

        }

        public static function Extra() {

            return new sql_extra();

        }

        public static function Raw() {

            return new sql_raw();

        }

    }

    class sql_find {

        private $sql;
        private $prop;

        private $DBinfo;

        public function __construct( $table ) {

            $this->sql = " FROM `$table` ";

            $this->DBinfo = [
                'mysql:host=localhost;dbname='.Smts::$config['DataBaseName'],
                Smts::$config['DataBaseUser'],
                Smts::$config['DataBasePassword']
            ];

        }

        public function ChangeDBinfo( $newDBinfo ) {
            $this->DBinfo = [
                $newDBinfo[0],
                $newDBinfo[1],
                $newDBinfo[2]
            ];

            return $this;
        }


        public function Where( $cond ) {
                
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "WHERE `".key($cond)."` = :".key($cond).$i." ";
            $this->prop[':'.key($cond).$i] = $cond[key($cond)];

            return $this;

        }

        public function WhereLike( $cond ) {
            
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "WHERE `".key($cond)."` LIKE :".key($cond).$i." ";
            $this->prop[':'.key($cond).$i] = '%'.$cond[key($cond)].'%';

            return $this;

        }

        public function AndWhere( $cond ) {
                
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "AND `".key($cond)."` = :".key($cond).$i." ";
            $this->prop[':'.key($cond).$i] = $cond[key($cond)];

            return $this;

        }
        
        public function OrWhere( $cond ) {
            
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "OR `".key($cond)."` = :".key($cond).$i." ";
            $this->prop[':'.key($cond).$i] = $cond[key($cond)];

            return $this;

        }

        
        public function OrderBy( $row, $direction = "DESC" ) {

            $this->sql .= "ORDER BY `$row` $direction ";

            return $this;

        }

        public function Limit( $limit ) {
            
            $this->sql .= "LIMIT :limit ";

            $this->prop[':limit'] = $limit;

            return $this;

        }
        
        public function Offset( $offset ) {
            
            $this->sql .= "OFFSET :offset ";
            
            $this->prop[':offset'] = $offset;

            return $this;
            
        }


        public function One() {
            
            $res = Sql::exec( $this->DBinfo, "SELECT *".$this->sql.';', $this->prop );
            
            if ( isset( $res[0] ) ) {
                return $res[0];
            } else {
                return false;
            }

        }

        public function All() {
                
            return Sql::exec( $this->DBinfo, "SELECT *".$this->sql.';', $this->prop );

        }
        
        public function Count() {
            
            return Sql::exec( $this->DBinfo, "SELECT count(*)".$this->sql.';', $this->prop )[0][0];

        }
        
    }

    class sql_update {
     
        private $sql;
        private $prop;

        public function __construct( $table ) {

            $this->sql = "UPDATE `$table` SET ||| ";

            $this->DBinfo = [
                'mysql:host=localhost;dbname='.Smts::$config['DataBaseName'],
                Smts::$config['DataBaseUser'],
                Smts::$config['DataBasePassword']
            ];

        }

        public function ChangeDBinfo( $newDBinfo ) {
            $this->DBinfo = [
                $newDBinfo[0],
                $newDBinfo[1],
                $newDBinfo[2]
            ];

            return $this;
        }


        public function Where( $cond ) {
                
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "WHERE `".key($cond)."` = :".key($cond).$i;
            $this->prop[':'.key($cond).$i] = $cond[key($cond)];

            return $this;

        }

        public function WhereLike( $cond ) {
            
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "WHERE `".key($cond)."` LIKE :".key($cond).$i." ";
            $this->prop[':'.key($cond).$i] = '%'.$cond[key($cond)].'%';

            return $this;

        }

        public function AndWhere( $cond ) {
                
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "AND `".key($cond)."` = :".key($cond).$i." ";
            $this->prop[':'.key($cond).$i] = $cond[key($cond)];

            return $this;

        }
        
        public function OrWhere( $cond ) {
            
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "OR `".key($cond)."` = :".key($cond).$i." ";
            $this->prop[':'.key($cond).$i] = $cond[key($cond)];

            return $this;

        }


        public function Values( $values ) {

            $vals = "";

            $n = 1;
            foreach ( $values as $key => $value ) {

                $i=0;
                while ( isset($this->prop[':'.$key.$i]) ) {
                    $i++;
                }

                if ( sizeof($values) == $n  ) {
                    $vals .= "`".$key."` = :".$key.$i;
                } else {
                    $vals .= "`".$key."` = :".$key.$i.", ";
                }

                $this->prop[':'.$key.$i] = "".$value;

                $n++;
            }

            $this->sql = str_replace( '|||', $vals, $this->sql );

            return Sql::exec( $this->DBinfo, $this->sql.';', $this->prop );

        }

    }

    class sql_insert {
     
        private $sql;
        private $prop;

        public function __construct( $table ) {

            $this->sql = "INSERT INTO `$table` (|||) VALUES (===)";

            $this->DBinfo = [
                'mysql:host=localhost;dbname='.Smts::$config['DataBaseName'],
                Smts::$config['DataBaseUser'],
                Smts::$config['DataBasePassword']
            ];

        }

        public function ChangeDBinfo( $newDBinfo ) {
            $this->DBinfo = [
                $newDBinfo[0],
                $newDBinfo[1],
                $newDBinfo[2]
            ];

            return $this;
        }

        public function Values( $values ) {

            $vals = "";
            $cols = "";

            $n = 1;
            foreach ( $values as $key => $value ) {

                $i=0;
                while ( isset($this->prop[':'.$key.$i]) ) {
                    $i++;
                }

                if ( sizeof($values) == $n  ) {
                    $vals .= ":".$key.$i;
                    $cols .= "".$key;
                } else {
                    $vals .= ":".$key.$i.", ";
                    $cols .= $key.", ";
                }

                $this->prop[':'.$key.$i] = "".$value;

                $n++;
            }

            $this->sql = str_replace( '|||', $cols, $this->sql );
            $this->sql = str_replace( '===', $vals, $this->sql );

            return Sql::exec( $this->DBinfo, $this->sql.';', $this->prop );

        }

    }

    class sql_delete {

        private $sql;
        private $prop;

        public function __construct( $table ) {

            $this->sql = "DELETE FROM `$table` ";

            $this->DBinfo = [
                'mysql:host=localhost;dbname='.Smts::$config['DataBaseName'],
                Smts::$config['DataBaseUser'],
                Smts::$config['DataBasePassword']
            ];

        }

        public function ChangeDBinfo( $newDBinfo ) {
            $this->DBinfo = [
                $newDBinfo[0],
                $newDBinfo[1],
                $newDBinfo[2]
            ];

            return $this;
        }


        public function Where( $cond ) {
                
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "WHERE `".key($cond)."` = :".key($cond).$i;
            $this->prop[':'.key($cond).$i] = $cond[key($cond)];

            return $this;

        }

        public function WhereLike( $cond ) {
            
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "WHERE `".key($cond)."` LIKE :".key($cond).$i." ";
            $this->prop[':'.key($cond).$i] = '%'.$cond[key($cond)].'%';

            return $this;

        }

        public function AndWhere( $cond ) {
                
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "AND `".key($cond)."` = :".key($cond).$i." ";
            $this->prop[':'.key($cond).$i] = $cond[key($cond)];

            return $this;

        }
        
        public function OrWhere( $cond ) {
            
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= "OR `".key($cond)."` = :".key($cond).$i." ";
            $this->prop[':'.key($cond).$i] = $cond[key($cond)];

            return $this;

        }


        public function All() {
            return Sql::exec( $this->DBinfo, $this->sql.';', $this->prop );
        }

    }

    class sql_extra {

        private $sql;
        private $prop;

        public function __construct() {

            $this->DBinfo = [
                'mysql:host=localhost;dbname='.Smts::$config['DataBaseName'],
                Smts::$config['DataBaseUser'],
                Smts::$config['DataBasePassword']
            ];

        }

        public function ChangeDBinfo( $newDBinfo ) {
            $this->DBinfo = [
                $newDBinfo[0],
                $newDBinfo[1],
                $newDBinfo[2]
            ];

            return $this;
        }

        public function AddBlob( $table, $values ) {

            $db = Sql::getInstance( $this->DBinfo );
            $req = $db->prepare("UPDATE $table SET ".key($blob)." = ?, ".key($ext)." = '$ext' WHERE ".key($id)." = '$id'");
		    $req->bindParam(1, $blob, PDO::PARAM_LOB);
            $req->execute();
            
            return true;

        }

        public function Where( $cond ) {
                
            $i=0;
            while ( isset($this->prop[':'.key($cond).$i]) ) {
                $i++;
            }

            $this->sql .= key($cond)."|||:".key($cond).$i;
            $this->prop[':'.key($cond).$i] = $cond[key($cond)];

            return $this;

        }


        public function RemoveDB( $dbname ) {
            if ( isset($dbname) && !empty($dbname) ) {
                $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                $db = new PDO('mysql:host=localhost', $this->DBinfo[1], $this->DBinfo[2], $pdo_options);
                try {
                    $req = $db->prepare("DROP DATABASE `$dbname`");
                    $req->execute();
                } catch( PDOException $Exception ) {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        }

        public function CreateDB( $name ) {
            if ( isset($name) && !empty($name) ) {
                $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                $db = new PDO('mysql:host=localhost', $this->DBinfo[1], $this->DBinfo[2], $pdo_options);
                try {
                    $req = $db->prepare("CREATE DATABASE `$name`");
                    $req->execute();
                } catch( PDOException $Exception ) {
                    return $Exception->getMessage();
                }
                return true;
            }
        }

        public function CreateTable( $dbn, $prop ) {
            if ( isset($dbn) && !empty($dbn) && isset($prop) && sizeof($prop) > 0 ) {
                $db = Sql::getInstance( $this->DBinfo );
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

        public function AddPKey( $dbn, $prop ) {
            if ( isset($dbn) && !empty($dbn) && isset($prop) && !empty($prop) ) {
                $db = Sql::getInstance( $this->DBinfo );
                
                try {
                    $req = $db->prepare("ALTER TABLE `$dbn` ADD PRIMARY KEY(`$prop`)");
                    $req->execute();
                } catch( PDOException $Exception ) {
                    return $Exception->getMessage();
                }
                return true;
            }
        }

        public function GetTables( $dbn ) {
            if ( isset($dbn) && !empty($dbn) ) {
                $db = Sql::getInstance( $this->DBinfo );
                
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
        
        public function GetColumns( $dbn, $col ) {
            if ( isset($dbn) && !empty($dbn) ) {
                $db = Sql::getInstance( $this->DBinfo );
                
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

    class sql_raw {

        private $sql;
        private $prop;

        public function __construct() {

            $this->DBinfo = [
                'mysql:host=localhost;dbname='.Smts::$config['DataBaseName'],
                Smts::$config['DataBaseUser'],
                Smts::$config['DataBasePassword']
            ];

        }

        public function ChangeDBinfo( $newDBinfo ) {
            $this->DBinfo = [
                $newDBinfo[0],
                $newDBinfo[1],
                $newDBinfo[2]
            ];

            return $this;
        }

        public function Query( $sql, $prop = [] ) {
            return Sql::exec( $this->DBinfo, $sql, $prop );
        }

    }