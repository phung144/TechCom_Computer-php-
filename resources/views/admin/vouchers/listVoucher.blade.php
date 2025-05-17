@extends('admin.layout')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Vouchers List</h2>
                    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">Add Voucher</a>
                </div>
                <div class="card-body">
                    <table id="productsTable" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Discount Type</th>
                                <th>Discount Value</th>
                                <th>Min Order Value</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vouchers as $voucher)
                                <tr>
                                    <td>{{ $voucher->code }}</td>
                                    <td>{{ $voucher->description }}</td>
                                    <td>{{ ucfirst($voucher->discount_type) }}</td>
                                    <td>
                                        {{ $voucher->discount_type === 'percent' ? $voucher->discount_value . '%' : number_format($voucher->discount_value) . ' VND' }}
                                    </td>
                                    <td>
                                        {{ $voucher->min_order_value !== null ? number_format($voucher->min_order_value) . ' VND' : '-' }}
                                    </td>
                                    <td>{{ $voucher->start_date }}</td>
                                    <td>{{ $voucher->end_date }}</td>
                                    <td>
                                        @if ($voucher->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="dropdown-toggle btn btn-sm btn-light" href="#" role="button"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                               <i class="mdi mdi-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('admin.vouchers.edit', $voucher->id) }}"
                                                   class="dropdown-item">
                                                   <i class="mdi mdi-pencil-outline mr-2"></i> Edit
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to delete this voucher?')">
                                                        <i class="mdi mdi-delete-outline mr-2"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
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
