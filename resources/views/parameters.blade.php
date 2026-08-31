<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parameters</title>
</head>
<body>
    <h1>Parameters</h1>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Parameter</th>
                <th>Description</th>
                <th>Choices</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($parameters as $parameter)
                <tr>
                    <td>{{ $parameter->Parameter }}</td>
                    <td>{{ $parameter->Description }}</td>
                    <td>{{ $parameter->Choices }}</td>
                    <td>{{ $parameter->Value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
