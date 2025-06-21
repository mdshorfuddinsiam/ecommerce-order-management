<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Outlet;

class CartController extends Controller
{
    
	public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $cartItems = [];
        $subtotal = 0;
        
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $itemTotal = $product->price * $quantity;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'total' => $itemTotal,
                ];
                $subtotal += $itemTotal;
            }
        }
        
        $tax = $subtotal * 0.1;
        $total = $subtotal + $tax;
        $outlets = Outlet::all();
        
        return view('frontend.pages.cart', compact('cartItems', 'subtotal', 'tax', 'total', 'outlets'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $cart = $request->session()->get('cart', []);
        $productId = $request->product_id;
        
        if (isset($cart[$productId])) {
            $cart[$productId] += $request->quantity;
        } else {
            $cart[$productId] = $request->quantity;
        }
        
        $request->session()->put('cart', $cart);
        
        return redirect()->route('cart.index')->with('success', 'Product added to cart');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $cart = $request->session()->get('cart', []);
        $productId = $request->product_id;
        
        if (isset($cart[$productId])) {
            $cart[$productId] = $request->quantity;
            $request->session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart updated');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        
        $cart = $request->session()->get('cart', []);
        $productId = $request->product_id;
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $request->session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Product removed from cart');
    }


}
