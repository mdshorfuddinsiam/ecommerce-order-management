@extends('backend.layouts.app')

@section('title', 'Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-box-seam"></i> Product Lists</h2>
    
</div>

<div class="row row-cols-12 row-cols-md-12 g-4">
    <!-- product Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Sl</th>
                    <th>image</th>
                    <th>name</th>
                    <th>description</th>
                    <th>price</th>
                    <th>stock</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <img src="{{ $product->image ? asset($product->image) : asset('uploads/download.jpg') }}" alt="{{ $product->name }}" style="height: 80px; object-fit: cover;">
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</div>
@endsection