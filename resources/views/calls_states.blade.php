<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Calls States</title>
</head>

<body>
    <h1>Calls States</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>IDCallState</th>
                <th>Description</th>
                <th>Group</th>
                <th>CallType</th>
                <th>USEFUL</th>
                <th>Order State</th>
                <th>List State</th>
                <th>Recall Type</th>
                <th>Header</th>
                <th>Disabled</th>
                <th>TimestampINS</th>
                <th>TimestampEDT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($callsStates as $state)
                <tr>
                    <td>{{ $state->IDCallState }}</td>
                    <td>{{ $state->Description }}</td>
                    <td>{{ $state->Group }}</td>
                    <td>{{ $state->CallType }}</td>
                    <td>{{ $state->USEFUL ? 'Yes' : 'No' }}</td>
                    <td>{{ $state->orderState->Description ?? 'N/A' }}</td>
                    <td>{{ $state->listState->Description ?? 'N/A' }}</td>
                    <td>{{ $state->recallType->Description ?? 'N/A' }}</td>
                    <td>{{ $state->header->Header ?? 'N/A' }}</td>
                    <td>{{ $state->Disabled ? 'Yes' : 'No' }}</td>
                    <td>{{ $state->TimestampINS }}</td>
                    <td>{{ $state->TimestampEDT }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
