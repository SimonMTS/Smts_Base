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
        require_once "base/templates/model.php";

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
        
        foreach ( $this->rules() as $rule ) {
            $validators = array_diff(scandir("./base/validators"), ['..', '.']);
            if ( in_array( ( $rule[1].'.php'), $validators ) ) {
                require './base/validators/' . $rule[1].'.php';

                $props = [];
                foreach ($rule[0] as $prop) {
                    $props[$prop] = $this->{$prop};
                }

                // echo'<pre>';var_dump( $props );exit;

                if ( $rule[1]::validate( $rule, $props ) === false ) {
                    return false;
                }

            }
            /*
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
                        $item = Sql::find(get_class($this))->where([$prop => $this->{$prop}])->all();
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
                            $this->{$prop} = Smts::UploadFile( $_FILES[$prop], $rule[2] );
                            
                            if ( !$this->{$prop} ) {
                                return false;
                            }
                        } else {
                            $this->{$prop} = Smts::$config['Default_Profile_Pic'];
                        }

                    }
                break;

                case 'address': 
                    foreach ($rule[0] as $prop) {
                        
                        if ( sizeof( $this->{$prop} ) == 4 ) {
                            $exaddress = [
                                '',
                                '',
                                '',
                                ''
                            ];
                
                            $address = $this->{$prop};
                            
                            for ($i=0; $i < 6; $i++) {
                                if (isset($address[$i])) {
                                    $exaddress[$i] = Smts::Sanitize ($address[$i]);
                                }
                            }
                            
                            $curl = curl_init();
                            curl_setopt_array($curl, [
                                CURLOPT_SSL_VERIFYPEER => FALSE,
                                CURLOPT_RETURNTRANSFER => 1,
                                CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($exaddress[0])."+".urlencode($exaddress[1])."+".urlencode($exaddress[2])."+".urlencode($exaddress[3])."&key=AIzaSyB5osi-LV3EjHVqve1t7cna6R_9FCgxFys"
                            ]);
                            $jsonString = curl_exec($curl);
                            // curl_close($curl);
                            
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
            */
        }
        // exit;

        return true;
    }
}