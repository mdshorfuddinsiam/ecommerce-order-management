<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlet;

class CheckoutController extends Controller
{
    
	public function index()
    {
    	$outlets = Outlet::all();
        return view('frontend.pages.checkout', compact('outlets'));
    }

}
