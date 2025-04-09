@extends('client.layout')

@section('head')
<link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endsection

@section('main')
<div class="container my-5">
    @if(session('error'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h2 class="mb-4 text-center">🛒 Your Orders</h2>

    @auth
        @if($orders->isEmpty())
            <div class="alert alert-info text-center">
                You have no orders yet.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Name</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Created At</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            @foreach($order->orderDetails as $detail)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>
                                        <img src="{{ Storage::url($detail->product->image) }}" alt="Product Image" class="img-thumbnail" style="width: 50px; height: 50px;">
                                    </td>
                                    <td>{{ $detail->product->name }}</td>
                                    <td class="text-success">${{ number_format($order->total, 2) }}</td>
                                    <td>
                                        @php
                                            $statusColor = match($order->status) {
                                                'pending' => 'warning',
                                                'completed' => 'success',
                                                'canceled' => 'secondary',
                                                default => 'light'
                                            };
                                        @endphp
                                        <span>
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->address }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td>{{ $order->created_at->format('d M, Y H:i') }}</td>
                                    <td class="text-center">
                                        @if($order->status !== 'canceled')
                                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure you want to cancel this order?')">
                                                    <i class="fas fa-times-circle me-1"></i> Cancel
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            You need to log in to create and view your orders.
        </div>
    @endauth
</div>
@endsection
