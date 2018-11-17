<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Transactions as Transactions;
use App\Library\PlaceToPay as PlaceToPay;

/**
 * Payments Controller
 *
 * @author  Juan Manuel Pinzon <softjuanm@gmail.com>
 * @version 0.1
 *
 */
class Payments extends Controller {

    /**
     * Shows transaction form
     *
     * @return \Illuminate\View\View
     */
    public function index(PlaceToPay $client) {
        // Set Page title
        $title = 'PlaceToPay - Formulario de Pago';

        // Retrieve bank list and default transaction from Library
        $banks = $client->getBanksList();
        $transaction = $client::transaction();

        // Retrieve allowed values form config
        $docsType = config('placetopay.documentTypes');
        $bankInterfaces = config('placetopay.bankInterfaces');

        return view('payments.form', compact('title', 'banks', 'docsType', 'bankInterfaces', 'transaction'));
    }

    /**
     * Given post form it generates a new transaction
     * If data is correct, user is redirected to PSE
     * 
     * @param Request $request
     * @param PlaceToPay $client
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function process(Request $request, PlaceToPay $client) {
       
        // TODO Request Validation
       
        // Process transaction using library
        $result = $client->processPayment($request);

        if ($result->returnCode == 'SUCCESS') {
            Session::put('transactionId', $result->transactionID);
            // Redirec to to bank cosumer page
            return redirect($result->bankURL);
        }

        // Redirect to form
        return redirect()->route('payment::index');
    }

    /**
     * Shows transaction status using transaction ID
     * 
     * @param PlaceToPay $client
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function processResult(PlaceToPay $client) {
        // Set page title
        $title = 'Resultado de la operacion';

        // Recover last transactionID from Session
        $transactionId = Session::pull('transactionId');

        if ($transactionId) {
            $transaction = $client->getTransaction($transactionId);
            return view('payments.result', compact('title', 'transaction'));
        } else {
            return redirect()->route('payment::resume');
        }
    }

    /**
     * Show a history of transactions done
     * @return \Illuminate\View\View
     */
    public function resume() {
        // Set page title
        $title = "Historial de Transacciones";

        // Retrieve records from database model
        $transactions = Transactions::orderBy('created_at', 'desc')->paginate(15);
        return view('payments.resume', compact('title', 'transactions'));
    }
    
    /**
     * Update transaction status and return to resume
     * @param PlaceToPay $client
     * @param int $transaction_id
     * @return \Illuminate\Routing\Redirectore
     */    
    public function update(PlaceToPay $client, $transaction_id = null)
    {
        if($transaction_id){
            $client->getTransaction($transaction_id);
        }
        return redirect()->route('payment::resume');
    }
}
