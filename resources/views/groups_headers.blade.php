<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groups Headers</title>
</head>

<body>
    <h1>Lista dei Gruppi con Header</h1>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Codice Header</th>
                <th>CodUsersGroup</th>
                <th>Creato il</th>
                <th>Modificato da</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($groupsHeaders as $header)
                <tr>
                    <td>{{ $header->CodHeader }}</td>
                    <td>{{ $header->CodUsersGroup }}</td>
                    <td>{{ $header->TimestampINS }}</td>
                    <td>{{ $header->CodUserLastEdit }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nessun header trovato.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
