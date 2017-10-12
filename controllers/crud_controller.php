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
            if ( isset($_POST['generate']) ) {
                $model = fopen("models/" . $_SESSION['crud']['modelName'] . ".php", "w");
    
                fwrite($model, $_SESSION['crud']['val']);
                fclose($model);

                Base::Redirect($GLOBALS['config']['base_url'].'crud');
            
            } elseif ( isset($_POST['tableName']) ) {
                $_SESSION['crud']['modelName'] = $_POST['tableName'];

                if ( isset( $_POST['className'] ) && !empty( $_POST['className'] ) ) {
                    $_SESSION['crud']['modelName'] = $_POST['className'];
                }

                $classname = $_SESSION['crud']['modelName'];

                $_SESSION['crud']['val'] = "<?php
                
    class $classname extends model {
        public \$name;

        public function rules() {
            return [
                [ ['name'], 'required' ],

                [ ['name'], 'unique' ]
            ];
        }

        public function attributes() {
            return [
                'name' => 'Gebruikersnaam'
            ];
        }
    }";

                Base::Render('crud/preview', [
                    'val' => $_SESSION['crud']['val'],
                    'classname' => $classname
                ]);
            } else {
                $tables = Sql::GetTables( $GLOBALS['config']['DataBaseName'] );

                Base::Render('crud/model', [
                    'tables' => $tables
                ]);
            }
        }
    }