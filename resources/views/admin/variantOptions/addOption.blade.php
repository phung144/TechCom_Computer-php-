@extends('admin.layout')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Add Variant Option</h2>
                    <a href="{{ route('admin.variants.index') }}" class="btn btn-primary">Back to List</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.variant-options.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="variant">Variant</label>
                            <select name="variant_id" id="variant" class="form-control @error('variant_id') is-invalid @enderror" required>
                                <option value="">Select Variant</option>
                                @foreach ($variants as $variant)
                                    <option value="{{ $variant->id }}" {{ old('variant_id') == $variant->id ? 'selected' : '' }}>
                                        {{ $variant->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('variant_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="value">Option Value</label>
                            <input type="text" name="value" id="value" class="form-control @error('value') is-invalid @enderror"
                                   value="{{ old('value') }}" required placeholder="Enter option value...">
                            @error('value')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-success">Save</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
