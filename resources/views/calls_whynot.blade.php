<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Calls WhyNot</title>
</head>

<body>
    <h1>Calls WhyNot</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>WhyNot</th>
                <th>WNTYPE</th>
                <th>CodHeader</th>
                <th>Disabled</th>
                <th>TimestampIns</th>
                <th>TimestampEdt</th>
                <th>CodLastUserEdit</th>
                <th>Header</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($callsWhyNot as $item)
                <tr>
                    <td>{{ $item->WhyNot }}</td>
                    <td>{{ $item->WNTYPE }}</td>
                    <td>{{ $item->CodHeader }}</td>
                    <td>{{ $item->Disabled ? 'Sì' : 'No' }}</td>
                    <td>{{ $item->TimestampIns }}</td>
                    <td>{{ $item->TimestampEdt }}</td>
                    <td>{{ $item->CodLastUserEdit }}</td>
                    <td>{{ $item->header->Description ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
