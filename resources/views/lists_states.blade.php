<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lists States</title>
</head>

<body>
    <h1>Lists States</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Group</th>
                <th>LTYPE</th>
                <th>Disabled</th>
                <th>FreeField1</th>
                <th>FreeField2</th>
                <th>FreeField3</th>
                <th>TimestampINS</th>
                <th>TimestampEDT</th>
                <th>CodUserLastEdit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($listsStates as $state)
                <tr>
                    <td>{{ $state->IDListState }}</td>
                    <td>{{ $state->Description }}</td>
                    <td>{{ $state->Group }}</td>
                    <td>{{ $state->LTYPE }}</td>
                    <td>{{ $state->Disabled ? 'Yes' : 'No' }}</td>
                    <td>{{ $state->FreeField1 }}</td>
                    <td>{{ $state->FreeField2 }}</td>
                    <td>{{ $state->FreeField3 }}</td>
                    <td>{{ $state->TimestampINS }}</td>
                    <td>{{ $state->TimestampEDT }}</td>
                    <td>{{ $state->CodUserLastEdit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
