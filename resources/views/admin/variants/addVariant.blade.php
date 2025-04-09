@extends('admin.layout')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Add Variant</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.variants.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Variant Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter variant name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                        <a href="{{ route('admin.variants.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
