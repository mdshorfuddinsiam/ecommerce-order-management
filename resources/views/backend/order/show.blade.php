@extends('backend.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-receipt"></i> Order #{{ $order->id }}</h2>
    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Orders
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-cart-check"></i> Order Items</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="table-light">
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product->image ? asset('storage/'.$item->product->image) : asset('uploads/download.jpg') }}" 
                                             alt="{{ $item->product->name }}" width="60" class="me-3 rounded">
                                        <div>
                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                            <small class="text-muted">{{ Str::limit($item->product->description, 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        @if($order->notes)
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-chat-left-text"></i> Order Notes</h5>
                <p class="card-text">{{ $order->notes }}</p>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-info-circle"></i> Order Information</h5>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <div>
                        <span class="badge 
                            @if($order->status === 'pending') bg-warning text-dark
                            @elseif($order->status === 'accepted') bg-success
                            @elseif($order->status === 'cancelled') bg-danger
                            @elseif($order->status === 'transferred') bg-info text-dark
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Outlet</label>
                    <p>{{ $order->outlet->name }} - {{ $order->outlet->location }}</p>
                </div>
                @if($order->transferred_to_outlet_id)
                <div class="mb-3">
                    <label class="form-label">Transferred To</label>
                    <p>{{ $order->transferredToOutlet->name }} - {{ $order->transferredToOutlet->location }}</p>
                </div>
                @endif
                <div class="mb-3">
                    <label class="form-label">Order Date</label>
                    <p>{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Customer</label>
                    <p>{{ $order->user->name }} ({{ $order->user->email }})</p>
                </div>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-receipt"></i> Order Summary</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Tax (10%):</span>
                    <span>${{ number_format($tax, 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Total:</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    @role('super_admin', 'admin', 'outlet')
                    <form action="{{ route('orders.accept', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle"></i> Accept Order
                        </button>
                    </form>
                    @endrole
                    
                    @role('super_admin', 'admin')
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-x-circle"></i> Cancel Order
                        </button>
                    </form>
                    @endrole
                    
                    @role('super_admin', 'admin', 'outlet')
                    <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#transferModal">
                        <i class="bi bi-arrow-left-right"></i> Transfer Order
                    </button>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transfer Modal -->
@role('super_admin', 'admin', 'outlet')
<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transfer Order #{{ $order->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('orders.transfer', $order->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Transfer to Outlet</label>
                        <select name="outlet_id" class="form-select" required>
                            @foreach($availableOutlets as $outlet)
                            <option value="{{ $outlet->id }}">{{ $outlet->name }} - {{ $outlet->location }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Confirm Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endrole
@endsection