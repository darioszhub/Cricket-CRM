<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predictive Events Base</title>
</head>

<body>
    <h1>Predictive Events Base</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ServerEvent</th>
                <th>Event</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($events as $event)
                <tr>
                    <td>{{ $event->ServerEvent }}</td>
                    <td>{{ $event->Event }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
