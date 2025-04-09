@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-12">
        <div class="card card-default shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Order Details</h2>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> {{ $order->id }}</p>
                        <p><strong>Full Name:</strong> {{ $order->full_name }}</p>
                        <p><strong>Phone:</strong> {{ $order->phone }}</p>
                        <p><strong>Address:</strong> {{ $order->address }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
                        <p><strong>Created At:</strong> {{ $order->created_at }}</p>
                        <p><strong>Updated At:</strong> {{ $order->updated_at }}</p>
                        <p><strong>Status:</strong>
                            <span class="badge badge-{{ $order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Thông tin user -->
                <h3 class="mb-3">User Information</h3>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>User ID:</strong> {{ $order->user->id }}</p>
                        <p><strong>Name:</strong> {{ $order->user->name }}</p>
                        <p><strong>Email:</strong> {{ $order->user->email }}</p>
                    </div>
                </div>
                <!-- Kết thúc thông tin user -->

                <h3 class="mb-3">Update Order Status</h3>
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mb-4">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select name="status" id="status" class="form-control">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>

                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>

                <h3 class="mb-3">Products</h3>
                <table class="table table-hover table-striped table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderDetails as $detail)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $detail->product->image) }}" alt="{{ $detail->product->name }}" class="img-thumbnail" style="width: 50px; height: 50px;">
                                </td>
                                <td>{{ $detail->product->name }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>${{ number_format($detail->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
