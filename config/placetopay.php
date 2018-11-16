<?php
/**
 * Payments Controller
 *
 * @author  Juan Manuel Pinzon <softjuanm@gmail.com>
 * @version 0.1
 *
 */
return [
    /*
    |--------------------------------------------------------------------------
    | PlaceToPay config
    | Variables can be set by using .env file, otherwise 
    | default values will be taken
    |--------------------------------------------------------------------------
    |
    */
    'wdsl'      => env('PLACETOPAY_WSDL', 'https://test.placetopay.com/soap/pse/?wsdl'),
    'endpoint'  => env('PLACETOPAY_ENDPOINT', 'https://test.placetopay.com/soap/pse/'),
    'key'       => env('PLACETOPAY_KEY', NULL),
    'login'     => env('PLACETOPAY_ID', NULL),
    
    /*
    |--------------------------------------------------------------------------
    | PlaceToPay allowed bankInterface
    |--------------------------------------------------------------------------
    |
    */
    'bankInterfaces' => array(
        array('code'=> 0, 'name'=>'people'),
        array('code'=> 1, 'name'=>'business'),
    ),
    
    /*
    |--------------------------------------------------------------------------
    | PlaceToPay allowed documentType
    |--------------------------------------------------------------------------
    |
    */
    'documentTypes' => array('CC', 'CE', 'TI', 'PPN', 'NIT', 'SSN')
];