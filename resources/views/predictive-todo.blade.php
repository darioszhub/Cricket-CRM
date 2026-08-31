<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predictive TODO</title>
</head>

<body>
    <h1>Predictive TODO</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>IDTODO</th>
                <th>CodEvent</th>
                <th>Event Label</th>
                <th>Chain</th>
                <th>Chain Label</th>
                <th>ToReport</th>
                <th>Label</th>
                <th>Preset</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($predictiveTodo as $todo)
                <tr>
                    <td>{{ $todo->IDTODO }}</td>
                    <td>{{ $todo->CodEvent }}</td>
                    <td>{{ $todo->event->Spec ?? 'N/A' }}</td>
                    <td>{{ $todo->Chain }}</td>
                    <td>{{ $todo->chain->Spec ?? 'N/A' }}</td>
                    <td>{{ $todo->ToReport }}</td>
                    <td>{{ $todo->Label }}</td>
                    <td>{{ $todo->Preset }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
