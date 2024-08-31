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
                    <td>{{$bp->category}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
