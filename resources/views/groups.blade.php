<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groups</title>
</head>

<body>
    <h1>Lista dei Gruppi</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID Gruppo</th>
                <th>Descrizione</th>
                <th>Creato il</th>
                <th>Ultima modifica</th>
                <th>Modificato da</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($groups as $group)
                <tr>
                    <td>{{ $group->IDUsersGroup }}</td>
                    <td>{{ $group->Description }}</td>
                    <td>{{ $group->TimestampINS }}</td>
                    <td>{{ $group->TimestampEDT }}</td>
                    <td>{{ $group->CodUserLastEdit }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nessun gruppo trovato.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
