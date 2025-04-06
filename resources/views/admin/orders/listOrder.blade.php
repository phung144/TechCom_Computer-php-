@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-12">
        <div class="card card-default shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Orders List</h2>
            </div>
            <div class="card-body">
                <table id="productsTable" class="table table-hover table-striped table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Customer Info</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    <strong>{{ $item->full_name }}</strong><br>
                                    <span class="text-muted">{{ $item->phone }}</span><br>
                                    <span class="text-muted">{{ $item->address }}</span>
                                </td>
                                <td>${{ number_format($item->total, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $item->status == 'completed' ? 'success' : ($item->status == 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $item->payment_method == 'cash_on_delivery' ? 'COD' : 'Online' }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $item->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
