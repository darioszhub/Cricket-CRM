<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lists</title>
</head>

<body>
    <h1>Lists</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>IDList</th>
                <th>CodCustomer</th>
                <th>CodHeader</th>
                <th>CodListState</th>
                <th>Recall Date/Time</th>
                <th>Note External</th>
                <th>Note Internal</th>
                <th>Last Contact</th>
                <th>Called Times</th>
                <th>Timestamp Insert</th>
                <th>Timestamp Edit</th>
                <th>Last User Edit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lists as $list)
                <tr>
                    <td>{{ $list->IDList }}</td>
                    <td>{{ $list->CodCustomer }}</td>
                    <td>{{ $list->CodHeader }}</td>
                    <td>{{ $list->CodListState }}</td>
                    <td>{{ $list->RecallDateTime }}</td>
                    <td>{{ $list->NoteEXT }}</td>
                    <td>{{ $list->NoteINT }}</td>
                    <td>{{ $list->LastContact }}</td>
                    <td>{{ $list->CalledTimes }}</td>
                    <td>{{ $list->TimestampIns }}</td>
                    <td>{{ $list->TimestampEdt }}</td>
                    <td>{{ $list->CodLastUserEdit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
