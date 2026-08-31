<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zones Groups</title>
</head>

<body>
    <h1>Zones Groups</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>IDZoneGroup</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($zonesGroups as $zoneGroup)
                <tr>
                    <td>{{ $zoneGroup->IDZoneGroup }}</td>
                    <td>{{ $zoneGroup->Description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
