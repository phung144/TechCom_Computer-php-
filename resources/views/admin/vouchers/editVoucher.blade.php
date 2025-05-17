@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card card-default">
            <div class="card-header">
                <h2>Edit Voucher</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" name="code" class="form-control" required value="{{ old('code', $voucher->code) }}">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control" value="{{ old('description', $voucher->description) }}">
                    </div>
                    <div class="form-group">
                        <label>Discount Type</label>
                        <select name="discount_type" class="form-control" required>
                            <option value="percent" {{ old('discount_type', $voucher->discount_type) == 'percent' ? 'selected' : '' }}>Percent</option>
                            <option value="fixed" {{ old('discount_type', $voucher->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Discount Value</label>
                        <input type="number" name="discount_value" class="form-control" required min="0" value="{{ old('discount_value', $voucher->discount_value) }}">
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" required value="{{ old('start_date', $voucher->start_date) }}">
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" required value="{{ old('end_date', $voucher->end_date) }}">
                    </div>
                    <div class="form-group">
                        <label>Active</label>
                        <select name="is_active" class="form-control" required>
                            <option value="1" {{ old('is_active', $voucher->is_active) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $voucher->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Min Order Value</label>
                        <input type="number" name="min_order_value" class="form-control" min="0" value="{{ old('min_order_value', $voucher->min_order_value) }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Voucher</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
