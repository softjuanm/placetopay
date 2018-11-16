<?php

namespace App\Http\Controllers;

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
        $transaction = $client->getTransaction();
                
        return view('payments.form', compact('title', 'banks' ,'docsType', 'bankInterfaces', 'transaction'));
    }
    
    public function process(Request $request, PlaceToPay $client)
    {
        // Process
        $result  = $client->processPayment($request);
    }
    
    
}
