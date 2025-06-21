<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['items.product', 'outlet'])->latest()->get();
        return response()->json($orders);
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'outlet']);
        return response()->json($order);
    }

    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), [
	        'outlet_id' => 'required|exists:outlets,id',
	        'items' => 'required|array',
	        'items.*.product_id' => 'required|exists:products,id',
	        'items.*.quantity' => 'required|integer|min:1',
	    ]);

	    if ($validator->fails()) {
	        return response()->json([
	            'success' => false,
	            'message' => 'Validation failed',
	            'errors' => $validator->errors(),
	        ], 422);
	    }

        $order = Order::create($request->all());
        return response()->json($order, 201);
    }

    public function accept(Order $order)
    {
        $order->update(['status' => 'accepted']);
        return response()->json(['message' => 'Order accepted']);
    }

    public function cancel(Order $order)
    {
        $order->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Order cancelled']);
    }

    public function transfer(Request $request, Order $order)
    {
        $request->validate(['outlet_id' => 'required|exists:outlets,id']);

        $order->update([
            'status' => 'transferred',
            'transferred_to_outlet_id' => $request->outlet_id,
            'notes' => $request->notes,
        ]);

        return response()->json(['message' => 'Order transferred']);
    }
}