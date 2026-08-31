<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lists Headers</title>
</head>

<body>
    <h1>Lists Headers</h1>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>IDHeader</th>
                <th>Header</th>
                <th>Description</th>
                <th>Portfolio</th>
                <th>Start</th>
                <th>Stop</th>
                <th>Zone Group</th>
                <th>Last Edit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($headers as $header)
                <tr>
                    <td>{{ $header->IDHeader }}</td>
                    <td>{{ $header->Header }}</td>
                    <td>{{ $header->Description }}</td>
                    <td>{{ $header->portfolio->Portfolio ?? '—' }}</td>
                    <td>{{ $header->DateStart }}</td>
                    <td>{{ $header->DateStop }}</td>
                    <td>{{ $header->CodZoneGroup }}</td>
                    <td>{{ $header->CodLastUserEdit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
