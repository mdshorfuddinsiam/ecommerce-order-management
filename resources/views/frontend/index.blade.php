@extends('frontend.layouts.app')

@section('title', 'Products')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-5 fw-bold">Our Products</h1>
            <p class="lead">Browse our amazing collection</p>
        </div>
        {{-- <div class="col-auto">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search products...">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div> --}}
    </div>

    <div class="row g-4">
        
        <!-- Product Grid -->
        <div class="col-md-12">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($products as $product)
                <div class="col">
                    <div class="card h-100 shadow-sm product-card">
                        <!-- Product Image -->
                        <div class="position-relative">
                            <img src="{{ $product->image ? asset($product->image) : asset('uploads/download.jpg') }}" 
                                 class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                            @if($product->stock <= 5)
                            <span class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 small">Low Stock</span>
                            @endif
                        </div>
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $product->name }}</h5>
                                <span class="badge bg-success">{{ $product->stock }} in stock</span>
                            </div>
                            <p class="card-text text-muted small">{{ Str::limit($product->description, 100) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <h4 class="text-primary mb-0">${{ number_format($product->price, 2) }}</h4>
                                
                                <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="input-group input-group-sm me-2" style="width: 100px;">
                                        <button class="btn btn-outline-secondary minus-btn" type="button">-</button>
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                               class="form-control text-center quantity-input">
                                        <button class="btn btn-outline-secondary plus-btn" type="button">+</button>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                    </div>
                </div>
                @endforeach
            </div>
            
        </div>
    </div>
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