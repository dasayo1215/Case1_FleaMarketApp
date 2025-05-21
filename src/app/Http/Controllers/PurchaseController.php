<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function showPurchaseForm(){
        return view('purchases.create');
    }

    public function purchase(){
        //
    }

    public function showAddressForm(){
        return view('purchases.address');
    }

    public function updateAddress(){
        //
    }
}
