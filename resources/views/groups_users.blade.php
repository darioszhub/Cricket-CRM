<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groups Users</title>
</head>

<body>
    <h1>Groups Users</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>CodUsersGroup</th>
                <th>CodUser</th>
                <th>TimestampINS</th>
                <th>TimestampEDT</th>
                <th>CodUserLastEdit</th>
                <th>Group Description</th>
                <th>User Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupsUsers as $groupUser)
                <tr>
                    <td>{{ $groupUser->CodUsersGroup }}</td>
                    <td>{{ $groupUser->CodUser }}</td>
                    <td>{{ $groupUser->TimestampINS }}</td>
                    <td>{{ $groupUser->TimestampEDT }}</td>
                    <td>{{ $groupUser->CodUserLastEdit }}</td>
                    <td>{{ $groupUser->group->Description ?? 'N/A' }}</td>
                    <td>{{ $groupUser->user->Username ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
