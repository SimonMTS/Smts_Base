<?php

    class address {

        public static function validate( $rule, &$model ) {
            
            foreach ( $rule[0] as $prop ) {

                if ( sizeof( $model->{$prop} ) == 4 ) {
                    $exaddress = [
                        '',
                        '',
                        '',
                        ''
                    ];
        
                    $address = $model->{$prop};
                    
                    for ($i=0; $i < 6; $i++) {
                        if ( isset($address[$i]) ) {
                            $exaddress[$i] = Smts::Sanitize( $address[$i] );
                        }
                    }
                    
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_SSL_VERIFYPEER => FALSE,
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?address=" . 
                        urlencode($exaddress[0]) . "+" . 
                        urlencode($exaddress[1]) . "+" . 
                        urlencode($exaddress[2]) . "+" . 
                        urlencode($exaddress[3]) . "&key=AIzaSyB5osi-LV3EjHVqve1t7cna6R_9FCgxFys"
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

                    $model->{$prop} = $parsedArray['results'][0]['address_components'][1]['long_name'] . ', ' . 
                    $parsedArray['results'][0]['address_components'][0]['long_name'] . ', ' . 
                    $parsedArray['results'][0]['address_components'][6]['long_name'] . ', ' .  
                    $parsedArray['results'][0]['address_components'][2]['long_name'] . ', ' . 
                    $parsedArray['results'][0]['address_components'][5]['long_name'];
                    
                } else {
                    return false;
                }
                
            }

            return true;
            
        }

    }