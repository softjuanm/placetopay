<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Library\PlaceToPay as PlaceToPay;

/**
 * Payments Controller
 *
 * @author  Juan Manuel Pinzon <softjuanm@gmail.com>
 * @version 0.1
 *
 */
class Payments extends Controller
{

    /**
     * Shows index page
     *
     * @return \Illuminate\View\View
     */
    public function index (PlaceToPay $client)
    {
        $title = 'PlaceToPay - Formulario de Pago';
        
        $banks = $client->getBanksList();
        $docsType = config('placetopay.documentTypes');
        $bankInterfaces = config('placetopay.bankInterfaces');
        $transaction = $client::transaction();
                
        return view('payments.form', compact('title', 'banks' ,'docsType', 'bankInterfaces', 'transaction'));
    }
    
    public function process(Request $request, PlaceToPay $client)
    {
        
        // Validation
        
        // Process
        $result  = $client->processPayment($request);
        
        if($result->returnCode == 'SUCCESS'){
           Session::put('transactionId', $result->transactionID);
           return redirect($result->bankURL);
        }
        
        // Final redirect
        return redirect()->route('payment::index');
        
    }
    
    public function processResult(Request $request, PlaceToPay $client)
    {
        $title = 'Resultado de la operacion';

        $transactionId = Session::pull('transactionId');
        
        //$transaction = $client->getTransaction($transactionId);
        
        print "<pre>";
        print_r($request->all());
        print "</pre>";
        
        print "<pre>";
        print_r($transactionId);
        print "</pre>";
        
    }
    
    
}
