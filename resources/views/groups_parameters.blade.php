<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groups Parameters</title>
</head>

<body>
    <h1>Groups Parameters</h1>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>CodUsersGroup</th>
                <th>CodParameter</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupsParameters as $groupParameter)
                <tr>
                    <td>{{ $groupParameter->CodUsersGroup }}</td>
                    <td>{{ $groupParameter->CodParameter }}</td>
                    <td>{{ $groupParameter->Value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
