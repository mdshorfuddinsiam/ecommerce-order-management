@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ol>
            </nav>
            <h1 class="display-5 fw-bold">Checkout</h1>
        </div>
    </div>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Delivery Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="firstName" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Street Address *</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State/Province *</label>
                                <input type="text" class="form-control" id="state" name="state" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="zip" class="form-label">ZIP/Postal Code *</label>
                                <input type="text" class="form-control" id="zip" name="zip" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="country" class="form-label">Country *</label>
                            <select class="form-select" id="country" name="country" required>
                                <option value="">Select Country</option>
                                <option value="US">United States</option>
                                <option value="CA">Canada</option>
                                <option value="UK">United Kingdom</option>
                                <option value="BD">Bangladesh</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="outlet" class="form-label">Select Outlet for Pickup *</label>
                            <select class="form-select" id="outlet" name="outlet_id" required>
                                <option value="">Select Outlet</option>
                                @foreach($outlets as $outlet)
                                <option value="{{ $outlet->id }}">{{ $outlet->name }} - {{ $outlet->location }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="saveInfo" name="save_info">
                            <label class="form-check-label" for="saveInfo">
                                Save this information for next time
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="creditCard" value="credit_card" checked>
                            <label class="form-check-label" for="creditCard">
                                <i class="far fa-credit-card me-2"></i> Credit/Debit Card
                            </label>
                        </div>
                        
                        <div class="row g-3 mb-3" id="creditCardForm">
                            <div class="col-12">
                                <label for="cardNumber" class="form-label">Card Number *</label>
                                <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cardName" class="form-label">Name on Card *</label>
                                <input type="text" class="form-control" id="cardName" placeholder="John Doe" required>
                            </div>
                            <div class="col-md-3">
                                <label for="cardExpiry" class="form-label">Expiry Date *</label>
                                <input type="text" class="form-control" id="cardExpiry" placeholder="MM/YY" required>
                            </div>
                            <div class="col-md-3">
                                <label for="cardCvv" class="form-label">CVV *</label>
                                <input type="text" class="form-control" id="cardCvv" placeholder="123" required>
                            </div>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                            <label class="form-check-label" for="paypal">
                                <i class="fab fa-paypal me-2"></i> PayPal
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cashOnDelivery" value="cod">
                            <label class="form-check-label" for="cashOnDelivery">
                                <i class="fas fa-money-bill-wave me-2"></i> Cash on Delivery
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Order Notes</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" id="orderNotes" name="notes" rows="3" placeholder="Any special instructions for your order..."></textarea>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal ({{ count($cartItems) }} items):</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>$5.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (10%):</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between fw-bold fs-5 mt-3 pt-2 border-top">
                            <span>Total:</span>
                            <span>${{ number_format($total + 5, 2) }}</span>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mt-4 py-2">
                            Place Order <i class="fas fa-lock ms-2"></i>
                        </button>
                        
                        <div class="mt-3 text-center">
                            <p class="small text-muted">
                                <i class="fas fa-shield-alt text-success me-2"></i> Secure checkout
                            </p>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white">
                        <h6 class="mb-3">Items in Your Order</h6>
                        <div class="list-group list-group-flush">
                            @foreach($cartItems as $item)
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <img src="{{ $item['product']->image ? asset('storage/'.$item['product']->image) : 'https://via.placeholder.com/60' }}" 
                                             alt="{{ $item['product']->name }}" class="rounded me-2" width="60" height="60">
                                        <div>
                                            <h6 class="mb-1">{{ $item['product']->name }}</h6>
                                            <p class="small text-muted mb-0">Qty: {{ $item['quantity'] }}</p>
                                        </div>
                                    </div>
                                    <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@section('scripts')
<script>
    // Payment method toggle
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.id === 'creditCard') {
                document.getElementById('creditCardForm').style.display = 'flex';
            } else {
                document.getElementById('creditCardForm').style.display = 'none';
            }
        });
    });

    // Initialize with credit card form visible
    document.getElementById('creditCardForm').style.display = 'flex';
</script>
@endsection
@endsection