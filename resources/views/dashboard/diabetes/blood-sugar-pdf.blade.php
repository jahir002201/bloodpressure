<!DOCTYPE html>
<html>
<head>
    <title>Blood Sugar Results</title>
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
        
    </style>
</head>
<body>
    <h1>Blood Sugar Results</h1>
    <p>Time Frame: {{ ucfirst($timeFrame) }}</p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Blood Sugar Level (mg/dL)</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($diabetes as $reading)
                <tr>
                    <td>{{ $reading->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $reading->blood_sugar_level }}</td>
                    <td class="{{ $reading->category_class }}">{{ $reading->category }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
