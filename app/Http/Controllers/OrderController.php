<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Order::with(['user', 'outlet', 'transferredToOutlet', 'items.product']);
        
        // if ($user->isOutletInCharge()) {
        //     $query->where('outlet_id', $user->outlet_id);
        // }
        
        $orders = $query->latest()->get();
        $outlets = Outlet::all();
        
        return view('backend.order.index', compact('orders', 'outlets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
        ]);
        
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty');
        }
        
        $order = Order::create([
            'user_id' => Auth::id(),
            'outlet_id' => $request->outlet_id,
            'status' => 'pending',
        ]);
        
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
            }
        }
        
        $request->session()->forget('cart');


        return redirect('')->with('success', 'Order placed successfully');
        // return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully');
    }

    public function show(Order $order)
    {
        $user = Auth::user();
        
        // // Authorization check
        // if ($user->isOutletInCharge() && $order->outlet_id !== $user->outlet_id) {
        //     abort(403);
        // }
        
        $order->load(['user', 'outlet', 'transferredToOutlet', 'items.product']);
        
        $subtotal = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });
        $tax = $subtotal * 0.1;
        $total = $subtotal + $tax;
        
        $availableOutlets = Outlet::where('id', '!=', $order->outlet_id)->get();
        // dd($availableOutlets);
        
        return view('backend.order.show', compact('order', 'subtotal', 'tax', 'total', 'availableOutlets'));
    }

    public function accept(Order $order)
    {
        $user = Auth::user();
        
        // // Authorization check
        // if ($user->isOutletInCharge() && $order->outlet_id !== $user->outlet_id) {
        //     abort(403);
        // }
        
        $order->update(['status' => 'accepted']);
        return redirect()->back()->with('success', 'Order accepted successfully');
    }

    public function cancel(Order $order)
    {
        $user = Auth::user();
        
        // // Authorization check
        // if ($user->isOutletInCharge() && $order->outlet_id !== $user->outlet_id) {
        //     abort(403);
        // }
        
        $order->update(['status' => 'cancelled']);
        return redirect()->back()->with('success', 'Order cancelled successfully');
    }

    public function transfer(Request $request, Order $order)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'notes' => 'nullable|string',
        ]);
        
        $user = Auth::user();
        
        // // Authorization check
        // if ($user->isOutletInCharge() && $order->outlet_id !== $user->outlet_id) {
        //     abort(403);
        // }
        
        $order->update([
            'status' => 'transferred',
            'transferred_to_outlet_id' => $request->outlet_id,
            'notes' => $request->notes,
        ]);
        
        return redirect()->back()->with('success', 'Order transferred successfully');
    }
}