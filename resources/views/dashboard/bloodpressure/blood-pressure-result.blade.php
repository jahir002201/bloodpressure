@extends('dashboard.layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Blood Pressure Results</h1>

    <form method="GET" action="{{ url('/blood/results') }}">
        <div class="form-group">
            <label for="time_frame">Filter by:</label>
            <select id="time_frame" name="time_frame" class="form-control" onchange="this.form.submit()">
                <option value="day" {{ $timeFrame == 'day' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ $timeFrame == 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ $timeFrame == 'month' ? 'selected' : '' }}>This Month</option>
            </select>
        </div>
    </form>

    <a href="{{ route('blood-pressure.downloadPDF', ['time_frame' => $timeFrame]) }}" class="btn btn-secondary">Download PDF</a>

    @if($bloodpressure->isEmpty())
        <p>No blood pressure records found for the selected time frame.</p>
    @else
        @foreach ($bloodpressure as $bp)
            <div>
                <p><strong>Date:</strong> {{ $bp->created_at->format('Y-m-d H:i:s') }}</p>
                <p><strong>Systolic:</strong> {{ $bp->systolic }} mm Hg</p>
                <p><strong>Diastolic:</strong> {{ $bp->diastolic }} mm Hg</p>
                <p><strong>Category:</strong> <p>{{$bp->category}}</p>
            </div>
            <br/>
        @endforeach
    @endif

    <a href="{{ url('/blood') }}" class="btn btn-primary">Back</a>
</div>
@endsection
