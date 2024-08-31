@extends('dashboard.layouts.app')

@section('content')
<div class="container">
    <h1>Submit Blood Sugar Level</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('diabetes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="blood_sugar_level">Blood Sugar Level</label>
            <input type="number" class="form-control" id="blood_sugar_level" name="blood_sugar_level" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>
@endsection
