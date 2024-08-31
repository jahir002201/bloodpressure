@extends('dashboard.layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Blood Sugar Results</h1>

    <form method="GET" action="{{ route('diabetes.show') }}">
        <div class="form-group">
            <label for="time_frame">Filter by:</label>
            <select id="time_frame" name="time_frame" class="form-control" onchange="this.form.submit()">
                <option value="day" {{ $timeFrame == 'day' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ $timeFrame == 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ $timeFrame == 'month' ? 'selected' : '' }}>This Month</option>
            </select>
        </div>
    </form>

    <a href="{{ route('diabetes.downloadPDF', ['time_frame' => $timeFrame]) }}" class="btn btn-secondary">Download PDF</a>

    @if($diabetes->isEmpty())
        <p>No blood sugar records found for the selected time frame.</p>
    @else
        @foreach ($diabetes as $reading)
            <div>
                <p><strong>Date:</strong> {{ $reading->created_at->format('Y-m-d H:i:s') }}</p>
                <p><strong>Blood Sugar Level:</strong> {{ $reading->blood_sugar_level }} mg/dL</p>
                <p><strong>Category:</strong> {{ $reading->category }}</p>
            </div>
            <br/>
        @endforeach
    @endif

    <a href="{{ route('diabetes.index') }}" class="btn btn-primary">Back</a>
</div>
@endsection
