<?php

namespace App\Http\Controllers;

use App\Services\InternetServiceProvider\Mpt;
use App\Services\InternetServiceProvider\Ooredoo;
use Illuminate\Http\Request;

class InternetServiceProviderController extends Controller
{
    protected $operator;

    public function getInvoiceAmount(Request $request, $operatorName)
    {
        switch($operatorName) {
            case 'ooredoo': 
                $this->operator = new Ooredoo();
                break;
            case 'mpt': 
                $this->operator = new Mpt();
                break;
            default:
                throw new \Exception('Operator Name not supported');
        }
       
        $this->operator->setMonth($request->get('month') ?: 1);
        $amount = $this->operator->calculateTotalAmount();
        
        return response()->json([
            'data' => $amount
        ]);
    }
}
