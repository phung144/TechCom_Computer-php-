@extends('client.auth-layout')

@section('main')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h2>Register</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register.post') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="font-weight-bold">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="font-weight-bold">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="font-weight-bold">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="font-weight-bold">Confirm Password:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your password" required>
                    </div>
                    <div class="form-group">
                        <label for="image" class="font-weight-bold">Profile Image:</label>
                        <input type="file" id="image" name="image" class="form-control-file">
                        <small class="text-muted">Upload your profile picture (optional)</small>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <small>Already have an account? <a href="{{ route('login') }}" class="text-primary">Login here</a></small>
            </div>
        </div>
    </div>
</div>
@endsection
