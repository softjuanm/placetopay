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

    /**
     * Default currency code
     * @var string
     */
    static $currency = 'COP';

    /**
     * Default country code
     * @var string
     */
    static $country = 'CO';

    /**
     * Default language
     * @var string 
     */
    static $language = 'ES';

    /**
     * Default return url, it is used by PSE
     * @var string
     */
    static $returnUrl = '/payment/result';

    /**
     * Class constructor
     */
    public function __construct() {

        // Generate a new SOAP connection using config parameters
        $this->client = new \SoapClient(config('placetopay.wdsl'), array('trace' => true, 'exceptions' => true));
        $this->client->__setLocation(config('placetopay.endpoint'));
    }

    /**
     * Generates an authentication entity
     * @return array
     */
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

    /**
     * Retrieve available banks list from PlaceToPay
     * If it is already Cached, is returned with requested it again
     * 
     * @return object
     */
    public function getBanksList() {

        // Read form cache
        $banks = Cache::get('BankList');
        if (!$banks) {
            try {
                // Set reques parameters
                $args = array(
                    'auth' => $this->getAuth(),
                );
                // Perform Soap Call
                $response = $this->client->__call('getBankList', array($args));

                // Rerieve bank list and pull them to cache
                $banks = $response->getBankListResult->item;
                Cache::put('BankList', $banks, now()->addDay(1));
            } catch (Exception $e) {
                Cache::flush();
                Log::error($e);
                // Todo Errors treatment
            }
        }
        return $banks;
    }

    /**
     * Given request data, perform a payment transaction 
     * 
     * @param Request $request
     * @return array response
     */
    public function processPayment(Request $request) {

        // Set a default response
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

        // Set request parameters
        $args = array(
            'auth' => $this->getAuth(),
            'transaction' => $this->createTransaction($request),
        );

        try {
            // Perfomr Soap request
            $call = $this->client->__call('createTransaction', array($args));
            $response = $call->createTransactionResult;

            Transactions::create((array) $response);
        } catch (\SoapFault $e) {
            $response['error'] = $e->getMessage();
            Log::error($e);
            error_clear_last();
            // Todo Errors treatment
        } catch (Exception $e) {
            Log::error($e);
            // Todo Errors treatment
        }

        return $response;
    }

    /**
     * Returns a transaction dara given its ID
     * If it's status is pending, a Soap request is performed 
     * In order to update the status
     * 
     * @param int $transactionID
     * @return object
     */
    public function getTransaction($transactionID = NULL) {
        // Retrieve record from database model
        $transaction = Transactions::where('transactionID', $transactionID)->first();

        if ((int) $transaction->responseCode == 3) {
            $arguments = array(
                'auth' => $this->getAuth(),
                'transactionID' => $transactionID,
            );

            // Perform Soap request
            $resp = $this->client->__call('getTransactionInformation', array($arguments));

            if ($resp) {
                $updated = (array) $resp->getTransactionInformationResult;
                $transaction->fill($updated);
                $transaction->save();
            }
        }

        //TODO Validate if requested ID exists

        return $transaction;
    }

    /**
     * Given request data from transaction form
     * Transaction is generated from default values and post data
     * 
     * @param Request $request
     * @return array
     */
    public function createTransaction(Request $request) {
        // Put request data into transaction default values
        $transaction = array_merge(self::transaction(), array_intersect_key($request->all(), self::transaction()));
        $person = array_merge(self::person(), array_intersect_key($request->all(), self::person()));

        $transaction['payer'] = $person;
        $transaction['shipping'] = $person;

        return $transaction;
    }

    /**
     * Return default transaction entity
     * @return array
     */
    static function transaction() {
        return array(
            'bankCode' => 0,
            'bankInterface' => 0,
            'returnURL' => url(self::$returnUrl),
            'reference' => '#P2P' . rand(100, 10000), // Randon reference
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

    /**
     * Return default person entity
     * @return array
     */
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
