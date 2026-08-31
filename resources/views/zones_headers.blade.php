<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zones Headers</title>
</head>

<body>
    <h1>Zones Headers</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>IDZone</th>
                <th>Description</th>
                <th>CodGroup</th>
                <th>Disabled</th>
                <th>Priority</th>
                <th>Group1</th>
                <th>Group2</th>
                <th>Group3</th>
                <th>Group4</th>
                <th>Group5</th>
                <th>TimestampINS</th>
                <th>TimestampEDT</th>
                <th>CodUserLastEdit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($zonesHeaders as $zoneHeader)
                <tr>
                    <td>{{ $zoneHeader->IDZone }}</td>
                    <td>{{ $zoneHeader->Description }}</td>
                    <td>{{ $zoneHeader->CodGroup }}</td>
                    <td>{{ $zoneHeader->Disabled ? 'Yes' : 'No' }}</td>
                    <td>{{ $zoneHeader->Priority }}</td>
                    <td>{{ $zoneHeader->Group1 }}</td>
                    <td>{{ $zoneHeader->Group2 }}</td>
                    <td>{{ $zoneHeader->Group3 }}</td>
                    <td>{{ $zoneHeader->Group4 }}</td>
                    <td>{{ $zoneHeader->Group5 }}</td>
                    <td>{{ $zoneHeader->TimestampINS }}</td>
                    <td>{{ $zoneHeader->TimestampEDT }}</td>
                    <td>{{ $zoneHeader->CodUserLastEdit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
