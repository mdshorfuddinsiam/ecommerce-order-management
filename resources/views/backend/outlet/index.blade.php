@extends('backend.layouts.app')

@section('title', 'Outlets')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-box-seam"></i> Outlet Lists</h2>
    
</div>

<div class="row row-cols-12 row-cols-md-12 g-4">
    <!-- product Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Sl</th>
                    <th>name</th>
                    <th>location</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($outlets as $outlet)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $outlet->name }}</td>
                        <td>{{ $outlet->location }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No outlets found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</div>
@endsection