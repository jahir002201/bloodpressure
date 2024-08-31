@extends('dashboard.layouts.app') 
@section('content')
<div class="container mt-5">
    <h1>Blood Pressure Input</h1>
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <form action="{{ url('/blood-store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="systolic">Systolic Pressure (mm Hg):</label>
            <input type="number" id="systolic" name="systolic" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="diastolic">Diastolic Pressure (mm Hg):</label>
            <input type="number" id="diastolic" name="diastolic" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection