<?php

namespace App\Library;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * PlaceToPay Library
 * Class has common methods and soap connection
 *
 * @author  Juan Manuel Pinzon <softjuanm@gmail.com>
 * @version 0.1
 *
 */
final class PlaceToPay {

    static $currency = 'COP';
    
    static $country = 'CO';
    
    static $language = 'ES';
    
    static $returnUrl = '/payment/result';
    
    private $transaction = [];

    public function __construct()
    {
        $this->client = new \SoapClient(config('placetopay.wdsl'), array('trace' => true));
        $this->client->__setLocation(config('placetopay.endpoint'));
        
        $this->transaction = array(
            'returnURL' => url(self::$returnUrl),
            'reference' => '#P2P'.rand(100,10000),
            'description' => 'Pago de prueba',
            'language' => self::$language,
            'currency' => self::$currency,
            'totalAmount' => 100000.0,
            'taxAmount' => 0,
            'devolutionBase' => 0,
            'tipAmount' => 0,
        );
    }

    private function getAuth()
    {
        // Get key from config
        $key = config('placetopay.key');

        // Generate a new seed, be sure your server time is sincronized
        // ISO 8601 formated date
        $seed = date('c');

        // Generate new tranKey (seed + key)
        $tranKey = sha1("{$seed}{$key}");

        return array(
            'login' => config('placetopay.login'),
            'tranKey' => $tranKey,
            'seed' => $seed,
        );
    }

    public function getBanksList()
    {
        $banks = Cache::get('BankList');
        if (!$banks) {
            try {

                $args = array(
                    'auth' => $this->getAuth(),
                );
                $response = $this->client->__call('getBankList', array($args));
                $banks = $response->getBankListResult->item;
                Cache::put('BankList', $banks, now()->addDay(1));
            } catch (Exception $e) {
                Cache::flush();
                // To do
            }
        }
        return $banks;
    }


    public function processPayment(Request $request)
    {

        $this->setData($request);
        
        $args = array(
            'auth' => $this->getAuth(),
            'transaction' => $this->getTransaction(),
        );
        
        $response = $this->client->__call('createTransaction', array($args));

        return $response;
    }
    
    
    public function getTransaction()
    {
        return $this->transaction;
    }
    
    
    public function setData(Request $request)
    {
        // Transaction
        $this->transaction['bankCode'] = $request['bankCode'];
        $this->transaction['bankInterface'] = $request['bankInterface'];
        $this->transaction['ipAddress'] = $request->ip();
        $this->transaction['userAgent'] = $request->header('User-Agent');
        
        //Payer
        $payer = array(
            'document' => $request['document'],
            'documentType' => $request['documentType'],
            'firstName' => $request['firstName'],
            'lastName' => $request['lastName'],
            'company' => $request['company'],
            'emailAddress' => $request['emailAddress'],
            'address' => $request['address'],
            'city' => $request['city'],
            'province' => $request['province'],
            'country' => self::$country,
            'phone' => $request['phone'],
            'mobile' => $request['mobile'],
        );
        
        $this->transaction['payer'] = $payer;
        $this->transaction['shipping'] = $payer;
    }

}
