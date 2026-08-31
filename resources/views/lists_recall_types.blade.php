<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lists Recall Types</title>
</head>

<body>
    <h1>Lists Recall Types</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>IDRecallType</th>
                <th>Description</th>
                <th>Disabled</th>
                <th>TimestampINS</th>
                <th>TimestampEDT</th>
                <th>CodUserLastEdit</th>
                <th>isPublicRecall</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recallTypes as $recallType)
                <tr>
                    <td>{{ $recallType->IDRecallType }}</td>
                    <td>{{ $recallType->Description }}</td>
                    <td>{{ $recallType->Disabled ? 'Yes' : 'No' }}</td>
                    <td>{{ $recallType->TimestampINS }}</td>
                    <td>{{ $recallType->TimestampEDT }}</td>
                    <td>{{ $recallType->CodUserLastEdit }}</td>
                    <td>{{ $recallType->isPublicRecall ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
