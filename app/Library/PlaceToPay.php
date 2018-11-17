<?php

namespace App\Library;

use Log;
use App\Transactions as Transactions;
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

    public function __construct() {
        $this->client = new \SoapClient(config('placetopay.wdsl'), array('trace' => true, 'exceptions' => true));
        $this->client->__setLocation(config('placetopay.endpoint'));
    }

    private function getAuth() {
        // Get key from config
        $tranKey = config('placetopay.key');

        // Generate a new seed, be sure your server time is sincronized
        // ISO 8601 formated date
        $seed = date('c');

        // Generate new tranKey (seed + key)
        $hashString = sha1($seed . $tranKey, false);

        return array(
            'login' => config('placetopay.login'),
            'tranKey' => $hashString,
            'seed' => $seed,
        );
    }

    public function getBanksList() {
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

    public function processPayment(Request $request) {

        $response = [
            'returnCode' => 'FAIL_UNKNOWN',
            'bankURL' => null,
            'trazabilityCode' => null,
            'transactionCycle' => null,
            'transactionID' => null,
            'sessionID' => null,
            'bankCurrency' => null,
            'bankFactor' => null,
            'responseCode' => 0,
            'responseReasonCode' => null,
            'responseReasonText' => 'Ocurrió un error desconocido al hacer la transacción.'
        ];

        $args = array(
            'auth' => $this->getAuth(),
            'transaction' => $this->createTransaction($request),
        );
        
        try {
            $call = $this->client->__call('createTransaction', array($args));
            $response = $call->createTransactionResult;

            Transactions::create((array) $response);
        } catch (\SoapFault $e) {
            $response['error'] = $e->getMessage();
            Log::error($e);
            error_clear_last();
        } catch (Exception $e) {
            Log::error($e);
            $response['error'] = $e->getMessage();
        }

        return $response;
    }

    public function getTransaction() {
        
    }

    public function createTransaction(Request $request) {

        // Transaction
        $transaction = array_merge(self::transaction(), array_intersect_key($request->all(), self::transaction()));
        $person = array_merge(self::person(), array_intersect_key($request->all(), self::person()));
        
        $transaction['payer'] = $person;
        $transaction['shipping'] = $person;

        return $transaction;
    }

    static function transaction() {

        return array(
            'bankCode' => 0,
            'bankInterface' => 0,
            'returnURL' => url(self::$returnUrl),
            'reference' => '#P2P' . rand(100, 10000),
            'description' => 'Pago de prueba',
            'language' => self::$language,
            'currency' => self::$currency,
            'totalAmount' => 100000.0,
            'taxAmount' => 0,
            'devolutionBase' => 0,
            'tipAmount' => 0,
            'payer' => array(),
            'shipping' => array(),
            'ipAddress' => \Request::ip(),
            'userAgent' => \Request::header('User-Agent'),
        );
    }

    static function person() {
        return array(
            'document' => NULL,
            'documentType' => NULL,
            'firstName' => NULL,
            'lastName' => NULL,
            'company' => NULL,
            'emailAddress' => NULL,
            'address' => NULL,
            'city' => NULL,
            'province' => NULL,
            'country' => NULL,
            'phone' => NULL,
            'mobile' => NULL,
        );
    }

}
