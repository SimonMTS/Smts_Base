<?php

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
            $model = require "base/templates/model.php";

            return $model;

        }

        public function load( $input ) {

            if ( $input == 'post' && isset( $_POST[get_class($this)] ) ) {
                $input = array_merge( $_POST[get_class($this)], $_FILES );

                foreach ( $this->attributes() as $attribute => $value ) {
                    if ( isset( $input[$attribute] ) && !empty( $input[$attribute] ) ) {
                        $this->{$attribute} = $input[$attribute];
                    } else {
                        if ( !isset( $this->{$attribute} ) || empty( $this->{$attribute} )) {
                            $this->{$attribute} = '';
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

            $errors = false;

            foreach ( $this->rules() as $rule ) {
                $validators = array_diff(scandir("./base/validators"), ['..', '.']);
                if ( in_array( ( $rule[1].'.php'), $validators ) ) {
                    require_once './base/validators/' . $rule[1].'.php';

                    if ( $rule[1]::validate( $rule, $this ) === false ) {
                        $errors = true;
                    }

                }
            }

            return ( !$errors );
            
        }
        
    }