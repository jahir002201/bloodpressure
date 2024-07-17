@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Blood Pressure Results</h1>

    <form method="GET" action="{{ url('/blood-pressure/results') }}">
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
                <p><strong>Systolic:</strong> {{ $bp->systolic }} mm Hg</p>
                <p><strong>Diastolic:</strong> {{ $bp->diastolic }} mm Hg</p>
                <p><strong>Category:</strong> 
                    @if ($bp->systolic >= 180 || $bp->diastolic >= 110)
                    <span class="text-danger">High blood pressure (Hypertensive crisis)</span>
                @elseif (($bp->systolic >= 160 && $bp->systolic < 180) || ($bp->diastolic >= 100 && $bp->diastolic < 110))
                    <span class="text-danger">High blood pressure (Stage 2)</span>
                @elseif (($bp->systolic >= 140 && $bp->systolic < 160) || ($bp->diastolic >= 90 && $bp->diastolic < 100))
                    <span class="text-danger">High blood pressure (Stage 1)</span>
                @elseif (($bp->systolic >= 130 && $bp->systolic < 140) || ($bp->diastolic >= 80 && $bp->diastolic < 90))
                    <span class="text-warning">Pre-high blood pressure</span>
                @elseif ($bp->systolic < 90 || $bp->diastolic < 60)
                    <span class="text-primary">Low blood pressure</span>
                @elseif ($bp->systolic < 120 && $bp->diastolic < 80)
                    <span class="text-success">Ideal blood pressure</span>
                @else
                    <span>Blood pressure classification not found.</span>
                @endif
                
                </p>
            </div>
            <br/>
        @endforeach
    @endif

    <a href="{{ url('/blood-pressure') }}" class="btn btn-primary">Back</a>
</div>
@endsection
