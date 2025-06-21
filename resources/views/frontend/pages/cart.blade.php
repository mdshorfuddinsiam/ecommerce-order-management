@extends('frontend.layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cart</li>
                </ol>
            </nav>
            <h1 class="display-5 fw-bold">Your Shopping Cart</h1>
        </div>
    </div>

    @if(empty($cartItems))
    <div class="row mt-4">
        <div class="col">
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class="fas fa-shopping-cart fa-2x me-3"></i>
                    <div>
                        <h4 class="alert-heading">Your cart is empty</h4>
                        <p>Looks like you haven't added any items to your cart yet.</p>
                    </div>
                </div>
                <hr>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping</a>
            </div>
        </div>
    </div>
    @else
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Cart Items ({{ count($cartItems) }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60px"></th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <img src="{{ $item['product']->image ? asset('storage/'.$item['product']->image) : asset('uploads\download.jpg') }}" 
                                             alt="{{ $item['product']->name }}" class="img-thumbnail" width="60">
                                    </td>
                                    <td>
                                        <h6 class="mb-1">{{ $item['product']->name }}</h6>
                                    </td>
                                    <td>${{ number_format($item['price'], 2) }}</td>
                                    <td width="150px">
                                        <form action="{{ route('cart.update') }}" method="POST" class="d-flex">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                            <div class="input-group input-group-sm">
                                                <button class="btn btn-outline-secondary minus-btn" type="button">-</button>
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                                                       min="1" max="{{ $item['product']->stock }}" class="form-control text-center">
                                                <button class="btn btn-outline-secondary plus-btn" type="button">+</button>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-link" title="Update">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                        </a>
                        <form action="{{-- {{ route('cart.clear') }} --}}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-2"></i> Clear Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            
        </div>
        
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm">
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

                    <br>
                    <div class="col-md-12 text-end">
                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                {{-- <label for="outlet_id" class="form-label">Select Outlet</label> --}}
                                <select name="outlet_id" id="outlet_id" class="form-select" required>
                                    <option value="">-- Select Outlet --</option>
                                    @foreach($outlets as $outlet)
                                    <option value="{{ $outlet->id }}">{{ $outlet->name }} - {{ $outlet->location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
                        </form>
                    </div>
                    
                    {{-- <a href="{{ route('checkout') }}" class="btn btn-primary w-100 mt-4 py-2">
                        Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
                    </a> --}}
                    
                </div>
            </div>
            
            
        </div>

        {{-- <div class="row mt-4">
            <div class="col-md-6">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Continue Shopping</a>
            </div>
            <div class="col-md-6 text-end">
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="outlet_id" class="form-label">Select Outlet</label>
                        <select name="outlet_id" id="outlet_id" class="form-select" required>
                            <option value="">-- Select Outlet --</option>
                            @foreach($outlets as $outlet)
                            <option value="{{ $outlet->id }}">{{ $outlet->name }} - {{ $outlet->location }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
                </form>
            </div>
        </div> --}}


    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Quantity input controls
    document.querySelectorAll('.minus-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.nextElementSibling;
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        });
    });

    document.querySelectorAll('.plus-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const max = parseInt(input.max);
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
            }
        });
    });
</script>
@endsection