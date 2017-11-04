<?php

    class filegenController extends Controller 
    {
        public static function overview() 
        {
            base_smts::Render('filegen/overview');
        }

        public static function model() 
        { //todo use table name in generated sql queries//
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
            base_smts::Render('filegen/crud');

            // generate view files: create, edit, view, overview //
            // generate controller with actions: create, edit, delete, view, overview
        }
    }