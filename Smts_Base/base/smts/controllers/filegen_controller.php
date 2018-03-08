<?php

    class filegenController extends Controller 
    {
        public static function overview() 
        {
            base_smts::Render('filegen/overview');
        }

        public static function model() 
        {
            if ( !isset( $_POST['generate'] ) && !isset( $_POST['generateConfirm'] ) ) {
                // step 1: select table and class-name

                $tables = Sql::GetTables( $GLOBALS['config']['DataBaseName'] );
                
                base_smts::Render('filegen/model', [
                    'tables' => $tables
                ]);

            } elseif ( isset( $_POST['generate'] ) ) {
                // step 2: confirm input and generate output

                if ( !isset( $_POST['tableName'] ) ) {
                    Base::Redirect($GLOBALS['config']['base_url'].'smts/filegen/model');
                } elseif ( isset( $_POST['className'] ) && !empty( $_POST['className'] ) ) {
                    $classname = $_POST['className'];
                } else {
                    $classname = $_POST['tableName'];
                }

                $properties = Sql::GetColumns('smts_base',  $_POST['tableName']);

                $val = Model::generate( $classname, $_POST['tableName'], $properties );

                base_smts::Render('filegen/preview', [
                    'classname' => $classname,
                    'val' => $val
                ]);

            } elseif ( isset( $_POST['generateConfirm'] ) ) {
                // step 3: create files

                $model = fopen("models/" . $_POST['classname'] . ".php", "w");
                
                fwrite($model, $_POST['content']);
                fclose($model);

                Base::Redirect($GLOBALS['config']['base_url'].'smts/filegen');
            }
        }

        public static function crud()
        {
            if ( !isset( $_POST['generate'] ) && !isset( $_POST['generateConfirm'] ) ) {
                // step 1: select table and class-name

                $models = [];

                foreach ( array_diff(scandir("./models"), ['..', '.']) as $file ) {
                    $models[] = str_replace( '.php', '', $file );
                }
                
                base_smts::Render('filegen/crud', [
                    'models' => $models
                ]);

            } elseif ( isset( $_POST['generate'] ) ) {
                // step 2: confirm input and generate output

                if ( !isset( $_POST['modelName'] ) ) {
                    Base::Redirect($GLOBALS['config']['base_url'].'smts/filegen/crud');
                } else {
                    $modelname = $_POST['modelName'];
                }

                $files['controller'] = ['controllers\\'.$modelname.'s_controller.php', Controller::generate($modelname)];

                $files['overview'] = ['views\\'.$modelname.'s\\overview.php', 'a'];
                $files['create'] = ['views\\'.$modelname.'s\\create.php', 'a'];
                $files['read'] = ['views\\'.$modelname.'s\\read.php', 'a'];
                $files['update'] = ['views\\'.$modelname.'s\\update.php', 'a'];

                base_smts::Render('filegen/crud_preview', [
                    'modelname' => $modelname,
                    'files' => $files
                ]);

            } elseif ( isset( $_POST['generateConfirm'] ) ) {
                // step 3: create files

                $controller = fopen("controllers/" . $_POST['modelname'] . ".php", "w");
                fwrite($controller, $_POST['content']);
                fclose($controller);

                mkdir("views/" . $_POST['modelname'] . "s");

                $overview = fopen("views/" . $_POST['modelname'] . "s/overview.php", "w");
                fwrite($overview, $_POST['content']);
                fclose($overview);

                $create = fopen("views/" . $_POST['modelname'] . "s/create.php", "w");
                fwrite($create, $_POST['content']);
                fclose($create);

                $read = fopen("views/" . $_POST['modelname'] . "s/read.php", "w");
                fwrite($read, $_POST['content']);
                fclose($read);

                $update = fopen("views/" . $_POST['modelname'] . "s/update.php", "w");
                fwrite($update, $_POST['content']);
                fclose($update);

                $delete = fopen("views/" . $_POST['modelname'] . "s/delete.php", "w");
                fwrite($delete, $_POST['content']);
                fclose($delete);

                Base::Redirect($GLOBALS['config']['base_url'].'smts/filegen');
            }
        }
    }