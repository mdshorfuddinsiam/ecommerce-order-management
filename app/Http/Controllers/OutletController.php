<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Outlet;

class OutletController extends Controller
{
    
    public function outletLists(){
    	$outlets = Outlet::all();
        return view('backend.outlet.index', compact('outlets'));
    }

}
