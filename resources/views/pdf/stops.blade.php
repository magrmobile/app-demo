<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        .table {
            width: 100%;
            border: 1px solid #999999;
        }
    </style>
</head>
<body>
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Machine Id</th>
                <th>Operator Id</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stops as $stop)
            <tr>
                <td>{{ $stop->id }}</td>
                <td>{{ $stop->machine_id }}</td>
                <td>{{ $stop->operator_id }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>