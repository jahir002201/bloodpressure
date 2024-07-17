<!DOCTYPE html>
<html>
<head>
    <title>Blood Pressure Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .text-orange {
            color: orange;
        }
        .text-danger {
            color: red;
        }
        .text-primary {
            color: blue;
        }
        .text-success {
            color: green;
        }
        .text-warning {
            color: yellow;
        }
    </style>
</head>
<body>
    <h1>Blood Pressure Results</h1>
    <p>Time Frame: {{ ucfirst($timeFrame) }}</p>
    <table>
        <thead>
            <tr>
                <th>Systolic (mm Hg)</th>
                <th>Diastolic (mm Hg)</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bloodpressure as $bp)
                <tr>
                    <td>{{ $bp->systolic }}</td>
                    <td>{{ $bp->diastolic }}</td>
                    <td>
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
                    
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
