<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders States</title>
</head>
<body>
    <h1>Orders States</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>OTYPE</th>
                <th>Public Recall</th>
                <th>Min Auto Recall</th>
                <th>Disabled</th>
                <th>Last Edited</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ordersStates as $state)
                <tr>
                    <td>{{ $state->IDOrderState }}</td>
                    <td>{{ $state->Description }}</td>
                    <td>{{ $state->OTYPE }}</td>
                    <td>{{ $state->PublicRecall ? 'Yes' : 'No' }}</td>
                    <td>{{ $state->MinAutoRecall }}</td>
                    <td>{{ $state->Disabled ? 'Yes' : 'No' }}</td>
                    <td>{{ $state->TimestampEDT }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
