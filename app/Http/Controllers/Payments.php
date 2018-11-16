<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function index ()
    {
        $title = 'Bienvenido a PlaceToPay';
        return view('index', compact('title'));
    }
    
}
