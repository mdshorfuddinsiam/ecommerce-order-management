@extends('backend.layouts.app')

@section('title', 'Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-list-check"></i> Order Management</h2>
    @role('super_admin', 'admin')
    <a href="{{ route('outlets.all') }}" class="btn btn-outline-primary">
        <i class="bi bi-shop"></i> Manage Outlets
    </a>
    @endrole
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th>Order ID</th>
                        <th>Outlet</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="text-decoration-none">
                                #{{ $order->id }}
                            </a>
                        </td>
                        <td>{{ $order->outlet->name }}</td>
                        <td>
                            <span class="badge 
                                @if($order->status === 'pending') bg-warning text-dark
                                @elseif($order->status === 'accepted') bg-success
                                @elseif($order->status === 'cancelled') bg-danger
                                @elseif($order->status === 'transferred') bg-info text-dark
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                            @if($order->transferred_to_outlet_id)
                            <div class="text-muted small">To: {{ $order->transferredToOutlet->name }}</div>
                            @endif
                        </td>
                        <td>${{ number_format($order->items->sum(function($item) { 
                            return $item->price * $item->quantity; 
                        }), 2) }}</td>
                        <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                {{-- Accept button for super_admin, admin, outlet --}}
                                @role('super_admin', 'admin', 'outlet')
                                <form action="{{ route('orders.accept', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Accept Order">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                @endrole
                                
                                {{-- Cancel button only for super_admin and admin --}}
                                @role('super_admin', 'admin')
                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" title="Cancel Order">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                @endrole
                                
                                {{-- <button type="button" class="btn btn-sm btn-info" 
                                        data-bs-toggle="modal" data-bs-target="#transferModal{{ $order->id }}" title="Transfer Order">
                                    <i class="bi bi-arrow-left-right"></i>
                                </button> --}}
                                
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Transfer Modal -->
                    {{-- <div class="modal fade" id="transferModal{{ $order->id }}" tabindex="-1">
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
                    </div> --}}
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
        @endif --}}
    </div>
</div>
@endsection