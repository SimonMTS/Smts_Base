<?php

    class crudController extends Controller 
    {
        public static function overview() 
        {
            echo'<pre>';var_dump( Sql::GetColumns('smts_base',  'user') );echo'</pre>';

            Base::Render('crud/overview');
        }

        public static function model() 
        {
            if ( !isset( $_POST['generate'] ) && !isset( $_POST['generateConfirm'] ) ) {
                // step 1: select table and class-name

                $tables = Sql::GetTables( $GLOBALS['config']['DataBaseName'] );
                
                Base::Render('crud/model', [
                    'tables' => $tables
                ]);

            } elseif ( isset( $_POST['generate'] ) ) {
                // step 2: confirm input and generate output

                if ( !isset( $_POST['tableName'] ) ) {
                    Base::Redirect($GLOBALS['config']['base_url'].'crud/model');
                } elseif ( isset( $_POST['className'] ) && !empty( $_POST['className'] ) ) {
                    $classname = $_POST['className'];
                } else {
                    $classname = $_POST['tableName'];
                }

                $properties = Sql::GetColumns('smts_base',  $_POST['tableName']);

                $val = Model::generate( $classname, $properties );

                Base::Render('crud/preview', [
                    'classname' => $classname,
                    'val' => $val
                ]);

            } elseif ( isset( $_POST['generateConfirm'] ) ) {
                // step 3: create files

                $model = fopen("models/" . $modelName . ".php", "w");
                
                fwrite($model, $modelValue);
                fclose($model);

                Base::Redirect($GLOBALS['config']['base_url'].'crud');
            }


//             if ( isset($_POST['generate']) ) {
//                 $model = fopen("models/" . $_SESSION['crud']['modelName'] . ".php", "w");
    
//                 fwrite($model, $_SESSION['crud']['val']);
//                 fclose($model);

//                 Base::Redirect($GLOBALS['config']['base_url'].'crud');
            
//             } elseif ( isset($_POST['tableName']) ) {
//                 $_SESSION['crud']['modelName'] = $_POST['tableName'];
//                 $cols = Sql::GetColumns('smts_base',  'user');

//                 if ( isset( $_POST['className'] ) && !empty( $_POST['className'] ) ) {
//                     $_SESSION['crud']['modelName'] = $_POST['className'];
//                 }

//                 $classname = $_SESSION['crud']['modelName'];

//                 $props = "";

//                 foreach ( $cols as $col ) {
//                     $porps = $props .= "        public \$" . $col[0] . "; \n";
//                 }

//                 $_SESSION['crud']['val'] = "<?php
                
//     class $classname extends model {
// $props

//         public function rules() {
//             return [
//                 [ ['name'], 'required' ],

//                 [ ['name'], 'unique' ]
//             ];
//         }

//         public function attributes() {
//             return [
//                 'name' => 'Gebruikersnaam'
//             ];
//         }
//     }";

//                 Base::Render('crud/preview', [
//                     'val' => $_SESSION['crud']['val'],
//                     'classname' => $classname
//                 ]);
//             } else {
//                 $tables = Sql::GetTables( $GLOBALS['config']['DataBaseName'] );

//                 Base::Render('crud/model', [
//                     'tables' => $tables
//                 ]);
//             }
        }
    }