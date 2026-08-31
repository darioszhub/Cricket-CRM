<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groups OStates</title>
</head>

<body>
    <h1>Groups OStates</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>CodUsersGroup</th>
                <th>CodOrderState</th>
                <th>Can Save</th>
                <th>Can Delete</th>
                <th>Edit State</th>
                <th>Edit Customer</th>
                <th>Edit Portfolio</th>
                <th>Edit Appto</th>
                <th>Edit TLK</th>
                <th>Edit EXT</th>
                <th>Edit Note INT</th>
                <th>Edit Note EXT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupsOStates as $groupOState)
                <tr>
                    <td>{{ $groupOState->CodUsersGroup }}</td>
                    <td>{{ $groupOState->CodOrderState }}</td>
                    <td>{{ $groupOState->CanSave ? 'Yes' : 'No' }}</td>
                    <td>{{ $groupOState->CanDelete ? 'Yes' : 'No' }}</td>
                    <td>{{ $groupOState->EditState ? 'Yes' : 'No' }}</td>
                    <td>{{ $groupOState->EditCustomer ? 'Yes' : 'No' }}</td>
                    <td>{{ $groupOState->EditPortfolio ? 'Yes' : 'No' }}</td>
                    <td>{{ $groupOState->EditAppto ? 'Yes' : 'No' }}</td>
                    <td>{{ $groupOState->EditTLK ? 'Yes' : 'No' }}</td>
                    <td>{{ $groupOState->EditEXT ? 'Yes' : 'No' }}</td>
                    <td>{{ $groupOState->EditNoteINT ? 'Yes' : 'No' }}</td>
                    <td>{{ $groupOState->EditNoteEXT ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
