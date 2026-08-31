<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Detail States</title>
</head>
<body>
    <h1>Orders Detail States</h1>
    <table border="1">
        <thead>
            <tr>
                <th>State</th>
                <th>DTYPE</th>
                <th>Visible4Selling</th>
                <th>Group</th>
                <th>FreeDate1HEADER</th>
                <th>FreeDate2HEADER</th>
                <th>FreeDate3HEADER</th>
                <th>FreeDate4HEADER</th>
                <th>FreeDate5HEADER</th>
                <th>FreeField1HEADER</th>
                <th>FreeField2HEADER</th>
                <th>FreeField3HEADER</th>
                <th>FreeField4HEADER</th>
                <th>FreeField5HEADER</th>
                <th>Disabled</th>
                <th>TimestampINS</th>
                <th>TimestampEDT</th>
                <th>CodUserLastEdit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detailStates as $state)
                <tr>
                    <td>{{ $state->State }}</td>
                    <td>{{ $state->DTYPE }}</td>
                    <td>{{ $state->Visible4Selling ? 'Yes' : 'No' }}</td>
                    <td>{{ $state->Group }}</td>
                    <td>{{ $state->FreeDate1HEADER }}</td>
                    <td>{{ $state->FreeDate2HEADER }}</td>
                    <td>{{ $state->FreeDate3HEADER }}</td>
                    <td>{{ $state->FreeDate4HEADER }}</td>
                    <td>{{ $state->FreeDate5HEADER }}</td>
                    <td>{{ $state->FreeField1HEADER }}</td>
                    <td>{{ $state->FreeField2HEADER }}</td>
                    <td>{{ $state->FreeField3HEADER }}</td>
                    <td>{{ $state->FreeField4HEADER }}</td>
                    <td>{{ $state->FreeField5HEADER }}</td>
                    <td>{{ $state->Disabled ? 'Yes' : 'No' }}</td>
                    <td>{{ $state->TimestampINS }}</td>
                    <td>{{ $state->TimestampEDT }}</td>
                    <td>{{ $state->CodUserLastEdit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
