<?php

    class image {

        public static function validate( $rule, &$model ) {
            
            foreach ( $rule[0] as $prop ) {

                if ( $model->{$prop}['size'] > 0 ) {

                    $model->{$prop} = Smts::UploadFile( $_FILES[$prop], $rule[2] );
                    
                    if ( $model->{$prop} === false ) {
                        return false;
                    }

                    $item = Sql::find( strtolower(get_class($model)) )->where(['id' => $model->id])->one();
                    if ( $item != false && $item['pic'] != Smts::$config['DefaultProfilePic'] ) {
                        if ( unlink($item['pic']) == false ) {
                            return false;
                        }
                    }

                } else {
                    $model->{$prop} = Smts::$config['DefaultProfilePic'];
                }
                
            }

            return true;
            
        }

    }