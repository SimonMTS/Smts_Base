<?php

    class Base {

        public static function Redirect( $url ) {
            if (headers_sent()) {
                echo '<meta http-equiv="Location" content="' . $url . '">';
                echo '<script> location.replace("' . $url . '"); </script>';
                echo '<a href="' . $url . '">' . $url . '</a>';
                exit;
            } else {
                header('location: ' . $url);exit;
            }
        }

        public static function Render( $view, $Cvar = [] ) {
            foreach ($Cvar as $key => $value) {
                ${$key} = $value;
            }

            $view = $view . '.php';

            require_once(__dir__.'/../views/layout/' . Controller::$layout . '.php');
        }

        public static function Upload_file($file, $resolution = null) {
            $target_dir = "assets/img/";
            $target_file = $target_dir . self::Genetate_id().$file['name'] ;
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            
            $check = getimagesize($file["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
            
            if ( $imageFileType != "jpg" && $imageFileType != "jpeg" ) {
                $uploadOk = 0;
            }
            if ($uploadOk == 0) {
                return false;
            } else {
                if ( isset( $resolution ) ) {
                    self::square_thumbnail_with_proportion($file["tmp_name"], $target_file, $resolution, 100);

                    return $target_file;
                } else {
                    if (move_uploaded_file($file["tmp_name"], $target_file)) {
                        return $target_file;
                    } else {
                        return false;
                    }
                }
            }

        }

        public static function square_thumbnail_with_proportion($src_file,$destination_file,$square_dimensions,$jpeg_quality=90)
        {
            // Step one: Rezise with proportion the src_file *** I found this in many places.

            $src_img=imagecreatefromjpeg($src_file);

            $old_x=imageSX($src_img);
            $old_y=imageSY($src_img);

            $ratio1=$old_x/$square_dimensions;
            $ratio2=$old_y/$square_dimensions;

            if($ratio1>$ratio2)
            {
                $thumb_w=$square_dimensions;
                $thumb_h=$old_y/$ratio1;
            }
            else    
            {
                $thumb_h=$square_dimensions;
                $thumb_w=$old_x/$ratio2;
            }

            // we create a new image with the new dimmensions
            $smaller_image_with_proportions=ImageCreateTrueColor($thumb_w,$thumb_h);

            // resize the big image to the new created one
            imagecopyresampled($smaller_image_with_proportions,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 

            // *** End of Step one ***

            // Step Two (this is new): "Copy and Paste" the $smaller_image_with_proportions in the center of a white image of the desired square dimensions

            // Create image of $square_dimensions x $square_dimensions in white color (white background)
            $final_image = imagecreatetruecolor($square_dimensions, $square_dimensions);
            $bg = imagecolorallocate ( $final_image, 255, 255, 255 );
            imagefilledrectangle($final_image,0,0,$square_dimensions,$square_dimensions,$bg);

            // need to center the small image in the squared new white image
            if($thumb_w>$thumb_h)
            {
                // more width than height we have to center height
                $dst_x=0;
                $dst_y=($square_dimensions-$thumb_h)/2;
            }
            elseif($thumb_h>$thumb_w)
            {
                // more height than width we have to center width
                $dst_x=($square_dimensions-$thumb_w)/2;
                $dst_y=0;

            }
            else
            {
                $dst_x=0;
                $dst_y=0;
            }

            $src_x=0; // we copy the src image complete
            $src_y=0; // we copy the src image complete

            $src_w=$thumb_w; // we copy the src image complete
            $src_h=$thumb_h; // we copy the src image complete

            $pct=100; // 100% over the white color ... here you can use transparency. 100 is no transparency.

            imagecopymerge($final_image,$smaller_image_with_proportions,$dst_x,$dst_y,$src_x,$src_y,$src_w,$src_h,$pct);

            imagejpeg($final_image,$destination_file,$jpeg_quality);

            // destroy aux images (free memory)
            imagedestroy($src_img); 
            imagedestroy($smaller_image_with_proportions);
            imagedestroy($final_image);
        }

        public static function Error( $a = null, $b = null, $c = null, $d = null, $e = null, $f = null) { //todo
            $error = error_get_last();
            
            if ( $error["type"] == E_ERROR ) {
                // fatal error
                $data = str_replace( '\\', '|', implode('*', $error) );
                self::error_view('fatal', $data);exit;
            } elseif ( isset($a) && !isset($b) && !isset($c) && !isset($d) && !isset($e) && !isset($f) ) {
                // error
                self::error_view($a->getcode(), $a);exit;
            } elseif ( isset( $error ) ) { 
                // exeption
                self::error_view($a, [
                    $a,
                    $b,
                    $c,
                    $d,
                    $e,
                    $f
                ]);exit;
            }
        }

        public static function error_view($type = null, $data = null) {
            Base::Render('pages/error', [
                'type' => $type,
                'data' => $data
            ]);
        }

        public static function BreadCrumbs() {
            $base_url = $GLOBALS['config']['base_url'];
            $var = explode('/', str_replace($base_url, '',Base::Curl()) );

            if ( empty( $var[0] ) && empty( $var[1] ) ) {
                $string = '<li class="active">Home</li>';
            } elseif ( !empty( $var[0] ) && empty( $var[1] ) ) {
                $string = '<li><a href="' . $base_url . '">Home</a></li>' . '<li class="active">' . ucfirst($var[0]) . '</li>';
            } else {
                $string = '<li><a href="' . $base_url . '">Home</a></li>' . '<li><a href="' .$base_url . $var[0] . '">' . ucfirst($var[0]) . '</a></li>' 
                        . '<li class="active">' . ucfirst($var[1]) . '</li>';
            }
            
            return "<ol class=\"breadcrumb\"> $string </ol>";
        }

        public static function Sanitize($string) {
            return htmlentities($string);
        }
        
        public static function Curl() {
            return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }

        public static function Genetate_id() {
            return str_replace('.', '', uniqid('', true));;
        }

        public static function Hash_String($string, $salt) {
            return hash('sha512', $string . $salt);
        }
    }

    class Controller {
        public static $layout = 'main';
        public static $title;

        public static function beforeAction() {
            self::$title = $GLOBALS['config']['Default_Title'];
        }

        public static function generate( $modelname ) {

            $UCmodelname = ucfirst($modelname);
            $controller = "<?php
\trequire_once \"models/$modelname.php\";

\tclass ".$modelname."sController extends Controller 
\t{

\t\tpublic static function overview() 
\t\t{

\t\t\tBase::Render('".$modelname."s/overview');
\t\t}

\t\tpublic static function view( \$var ) 
\t\t{
\t\t\t\$id = Base::Sanitize( \$var[2] );
\t\t\t\$$modelname = $UCmodelname::Find(\$id);

\t\t\tif ( \$$modelname !== false ) {
\t\t\t\tBase::Render('".$modelname."s/view', [
\t\t\t\t\t'$modelname' => \$$modelname,
\t\t\t\t]);
\t\t\t} else {
\t\t\t\tBase::Render('pages/error', [
\t\t\t\t\t'type' => 'custom',
\t\t\t\t\t'data' => [
\t\t\t\t\t\t'Error',
\t\t\t\t\t\t'$UCmodelname not found'
\t\t\t\t\t]
\t\t\t\t]);
\t\t\t}
\t\t}

\t\tpublic static function create() 
\t\t{
    \$user = new User();
    
    if ( \$user->load('post') && \$user->validate() ) {
        \$user->id = Base::Genetate_id();

        if ( \$user->save() ) {
            Base::Redirect(\$GLOBALS['config']['base_url']);
        } else {
            Base::Render('pages/error', [
                'type' => 'custom',
                'data' => [
                    'Error',
                    'Could not save user'
                ]
            ]);
        }
    } else {
        Base::Render('users/create', [
            'var' => \$var,
            'user' => \$user
        ]);
    }
\t\t}

\t\tpublic static function edit() 
\t\t{

\t\t\tBase::Render('".$modelname."s/edit');
\t\t}

\t\tpublic static function delete() 
\t\t{
\t\t\t\$id = Base::Sanitize( \$var[2] );
\t\t\t\$$modelname = $UCmodelname::find(\$id);

\t\t\tif ( \$".$modelname."->delete() ) {
\t\t\t\tBase::Redirect(\$GLOBALS['config']['base_url'] . '".$modelname."s/overview');
\t\t\t} else {
\t\t\t\tBase::Render('pages/error', [
\t\t\t\t\t'type' => 'custom',
\t\t\t\t\t'data' => [
\t\t\t\t\t\t'Error',
\t\t\t\t\t\t'$UCmodelname not found'
\t\t\t\t\t]
\t\t\t\t]);
\t\t\t}
\t\t\tBase::Redirect(\$GLOBALS['config']['base_url'] . '".$modelname."s/overview');
\t\t}
\t}
";

            return $controller;
        }
    }

    class Model {
        public static function generate( $classname, $tablename, $properties ) {
            $props = '';
            $rules = '';
            $attributes = '';
            $lenghtRules = '';
            $sqldata = '';

            $ruletypes = [];
            $rulelenghts = [];

            $i = 1;

            // set $props, attributes, and types of rules //
            foreach ( $properties as $key => $property ) {
                $props .= "\t\tpublic $" . $property['COLUMN_NAME'] . ";\n";

                $attributes .= "\t\t\t\t'" . $property['COLUMN_NAME'] . "' => 'seo_" . $property['COLUMN_NAME'] . "'";
                if ( count($properties) != ($key + 1) ) {
                    $attributes .= ",\n";
                }

                $sqldata .= "\t\t\t\t\t'" . $property['COLUMN_NAME'] . "' => \$this->" . $property['COLUMN_NAME'];
                if ( count($properties) != ($key + 1) ) {
                    $sqldata .= ",\n";
                }

                $ruletypes[$property['DATA_TYPE']][] = $property;

                preg_match('/(?<=\()(.*?)(?=\))/', $property['COLUMN_TYPE'], $match);
                if ( isset( $match[0] ) ) {
                    $rulelenghts[ $match[0] ][] = $property;
                }
            }

            // link props with their rule //
            foreach ( $ruletypes as $key => $ruletype ) {
                $propnames = '';

                foreach ( $ruletype as $rtkey => $propname ) {
                    $propnames .= "'" . $propname['COLUMN_NAME'] . "'";
                    if ( count($ruletype) != ($rtkey + 1) ) {
                        $propnames .= ", ";
                    }
                }

                $rules .= "\t\t\t\t[ [" . $propnames . "], '" . $key . "' ]";

                if (count($ruletypes) != $i ) {
                    $rules .= ",\n\n";
                } else {
                    $rules .= ",\n";
                }

                $i++;
            }

            // set lenght rules //
            foreach ( $rulelenghts as $key => $rulelenght ) {
                $propnames = '';

                foreach ( $rulelenght as $rlkey => $propname ) {
                    $propnames .= "'" . $propname['COLUMN_NAME'] . "'";
                    if ( count($rulelenght) != ($rlkey + 1) ) {
                        $propnames .= ", ";
                    }
                }

                $lenghtRules .= "\t\t\t\t[ [" . $propnames . "], 'maxlen', " . $key . " ]";

                if ((count($rulelenghts) + count($ruletypes)) != $i ) {
                    $lenghtRules .= ",\n\n";
                }
                
                $i++;
            }

            $UCclassname = ucfirst($classname);
            $model = "<?php
            
\tclass $UCclassname extends model {
$props
\t\t// Only contains fields with a set lenght //
\t\tpublic function rules() 
\t\t{ 
\t\t\treturn [
$rules
$lenghtRules
\t\t\t];
\t\t}

\t\t// Remove fields that aren't visible to the user, used in \$model-load() to find form fields //
\t\tpublic function attributes() 
\t\t{
\t\t\treturn [
$attributes
\t\t\t];
\t\t}

\t\t// If your database doesn't use 'id', you'll have to change that here //
\t\tpublic static function find(\$id)
\t\t{
\t\t\t\$result = Sql::Get('$tablename', 'id', \$id);

\t\t\tif ( isset(\$result[0]) ) {
\t\t\t\t\$$classname = new $classname();
\t\t\t\t\$".$classname."->load( \$result[0] );
\t\t\t\treturn \$$classname;
\t\t\t} else {
\t\t\t\treturn false;
\t\t\t}
\t\t}

\t\t// If your database doesn't use 'id', you'll have to change that here //
\t\tpublic function save()
\t\t{
\t\t\tif ( !self::find(\$this->id) ) {
\t\t\t\treturn Sql::Save('$tablename', [
$sqldata
\t\t\t\t]);
\t\t\t} else {
\t\t\t\treturn Sql::Update('$tablename', 'id', \$this->id, [
$sqldata
\t\t\t\t]);
\t\t\t}
\t\t}

\t\t// If your database doesn't use 'id', you'll have to change that here //
\t\tpublic function delete()
\t\t{
\t\t\t\$user = self::find(\$this->id);
\t\t\tif (\$user) {
\t\t\t\treturn Sql::Delete('$tablename', 'id', \$this->id);
\t\t\t} else {
\t\t\t\treturn false;
\t\t\t}
\t\t}
\t}";

            return $model;
        }

        public function load($input) {
            if ($input == 'post' && isset( $_POST[get_class($this)] )) {
                $input = array_merge( $_POST[get_class($this)], $_FILES );

                foreach ( $this->attributes() as $attribute => $value ) {
                    if ( isset( $input[$attribute] ) && !empty( $input[$attribute] ) ) {
                        $this->{$attribute} = $input[$attribute];
                    } else {
                        if ( !isset( $this->{$attribute} ) || empty( $this->{$attribute} )) {
                            return false;
                        }
                    }
                }
                
                return true;
            } elseif ( is_array( $input ) ) {
                foreach ($input as $prop => $value) {
                    if ( is_string($prop) ) {
                        $this->{$prop} = $value;
                    }
                }

                return true;
            } else {
                return false;
            }
        }

        public function validate() {
            //todo
            foreach ( $this->rules() as $rule ) {
                switch ( $rule[1] ) {

                    case 'required':
                        foreach ($rule[0] as $prop) {
                            if ( !isset( $this->{$prop} ) || empty( $this->{$prop} ) ) {
                                return false;
                            }
                        }
                    break;

                    case 'unique':
                        foreach ($rule[0] as $prop) {
                            $item = Sql::Get(get_class($this), $prop, $this->{$prop});
                            if ( $item && $item[0][$prop] != $this->{$prop} ) {
                                return false;
                            }
                        }
                    break;

                    case 'password':
                        if ( $this->{$rule[0][0]} != $this->{$rule[0][1]} ) {
                            return false;
                        }
                    break;

                    case 'in': 
                        foreach ($rule[0] as $prop) {
                            if ( !in_array( $this->{$prop}, $rule[2] ) ) {
                                return false;
                            }
                        }
                    break;

                    case 'string': 
                        foreach ($rule[0] as $prop) {
                            if ( !is_string( $this->{$prop} ) ) {
                                return false;
                            } else {
                                $this->{$prop} = strval( $this->{$prop} );
                            }
                        }
                    break;

                    case 'integer': 
                        foreach ($rule[0] as $prop) {
                            if ( !is_int( $this->{$prop} ) ) {
                                $this->{$prop} = intval( $this->{$prop} );
                            }
                        }
                    break;

                    case 'double': 
                        foreach ($rule[0] as $prop) {
                            if ( !is_double( $this->{$prop} ) ) {
                                return false;
                            } else {
                                $this->{$prop} = floatval( $this->{$prop} );
                            }
                        }
                    break;

                    case 'image': 
                        foreach ($rule[0] as $prop) {
                            
                            if ( $this->{$prop}['size'] > 0 ) {
                                $this->{$prop} = Base::Upload_file( $_FILES[$prop], $rule[2] );
                                
                                if ( !$this->{$prop} ) {
                                    return false;
                                }
                            } else {
                                $this->{$prop} = $GLOBALS['config']['Default_Profile_Pic'];
                            }

                        }
                    break;

                    case 'adres': 
                        foreach ($rule[0] as $prop) {
                            
                            if ( sizeof( $this->{$prop} ) == 4 ) {
                                $exAdres = [
                                	'',
                                	'',
                                	'',
                                	''
                                ];
                    
                                $adres = $this->{$prop};
                                
                                for ($i=0; $i < 6; $i++) {
                                	if (isset($adres[$i])) {
                                		$exAdres[$i] = Base::Sanitize ($adres[$i]);
                                	}
                                }
                                
                                $curl = curl_init();
                                curl_setopt_array($curl, [
                                    CURLOPT_RETURNTRANSFER => 1,
                                    CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($exAdres[0])."+".urlencode($exAdres[1])."+".urlencode($exAdres[2])."+".urlencode($exAdres[3])."&key=AIzaSyB5osi-LV3EjHVqve1t7cna6R_9FCgxFys"
                                ]);
                                $jsonString = curl_exec($curl);
                                curl_close($curl);
                                
                                $parsedArray = json_decode($jsonString,true);
                                
                                if (
                                	!isset($parsedArray['results'][0]['address_components'][1]['long_name']) || 
                                	!isset($parsedArray['results'][0]['address_components'][0]['long_name']) || 
                                	!isset($parsedArray['results'][0]['address_components'][6]['long_name']) || 
                                	!isset($parsedArray['results'][0]['address_components'][2]['long_name']) || 
                                	!isset($parsedArray['results'][0]['address_components'][5]['long_name'])
                                ) {
                                	return false;
                                }

                                $this->{$prop} = $parsedArray['results'][0]['address_components'][1]['long_name'] . ', ' . $parsedArray['results'][0]['address_components'][0]['long_name'] . ', ' . $parsedArray['results'][0]['address_components'][6]['long_name'] . ', ' .  $parsedArray['results'][0]['address_components'][2]['long_name'] . ', ' . $parsedArray['results'][0]['address_components'][5]['long_name'];
                                
                            } else {
                                return false;
                            }

                        }
                    break;

                    case 'date': 
                        foreach ($rule[0] as $prop) {
                            
                            $date = date('d/m/Y:H:i:s', strtotime( implode( '-', $this->{$prop} ) ));
                            
                            if ( sizeof( $this->{$prop} ) == 3 ) {
                                $this->{$prop} = $date;
                            } else {
                                return false;
                            }

                        }
                    break;

                    default:
                        // error todo
                    break;

                }
            }

            return true;
        }
    }

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
        public static function Search($table, $row = '', $like = '', $limit = 11, $offset = 0) {
            $db = self::getInstance();

            try {
                $req = $db->prepare("SELECT * FROM $table WHERE $row LIKE :like LIMIT $limit OFFSET $offset");
                $req->execute([
                    ':like' => "%".$like."%"
                    ]);
                $res = $req->fetchall();
            } catch( PDOException $Exception ) {
                return $Exception->getMessage();
            }

            return $res;
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