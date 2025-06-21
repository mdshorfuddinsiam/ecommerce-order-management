<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Outlet;

class OutletController extends Controller
{
    public function outletLists()
    {
        return response()->json(Outlet::all());
    }
}