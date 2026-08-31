<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agents Assignments</title>
</head>

<body>
    <h1>Agents Assignments</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>CodZone</th>
                <th>Zone Description</th>
                <th>CodAgent</th>
                <th>Agent Name</th>
                <th>Disabled</th>
                <th>CodPortfolio</th>
                <th>Portfolio</th>
                <th>TimestampINS</th>
                <th>TimestampEDT</th>
                <th>CodUserLastEdit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->CodZone }}</td>
                    <td>{{ $assignment->zone->Description ?? 'N/A' }}</td>
                    <td>{{ $assignment->CodAgent }}</td>
                    <td>{{ $assignment->agent->Name ?? 'N/A' }}</td>
                    <td>{{ $assignment->Disabled ? 'Yes' : 'No' }}</td>
                    <td>{{ $assignment->CodPortfolio }}</td>
                    <td>{{ $assignment->portfolio->Portfolio ?? 'N/A' }}</td>
                    <td>{{ $assignment->TimestampINS }}</td>
                    <td>{{ $assignment->TimestampEDT }}</td>
                    <td>{{ $assignment->CodUserLastEdit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
