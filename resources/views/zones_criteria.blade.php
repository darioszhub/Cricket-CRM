<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zones Criteria</title>
</head>

<body>
    <h1>Zones Criteria</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>IDCriteria</th>
                <th>CodZone</th>
                <th>Disabled</th>
                <th>ZoneType</th>
                <th>Value</th>
                <th>Value2</th>
                <th>TimestampINS</th>
                <th>TimestampEDT</th>
                <th>CodUserLastEdit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($zonesCriteria as $criteria)
                <tr>
                    <td>{{ $criteria->IDCriteria }}</td>
                    <td>{{ $criteria->CodZone }}</td>
                    <td>{{ $criteria->Disabled ? 'Yes' : 'No' }}</td>
                    <td>{{ $criteria->ZoneType }}</td>
                    <td>{{ $criteria->Value }}</td>
                    <td>{{ $criteria->Value2 }}</td>
                    <td>{{ $criteria->TimestampINS }}</td>
                    <td>{{ $criteria->TimestampEDT }}</td>
                    <td>{{ $criteria->CodUserLastEdit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
