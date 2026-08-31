<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predictive Events</title>
</head>

<body>
    <h1>Predictive Events</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>IDEvent</th>
                <th>ServerEvent</th>
                <th>Spec</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $event)
                <tr>
                    <td>{{ $event->IDEvent }}</td>
                    <td>{{ $event->ServerEvent }}</td>
                    <td>{{ $event->Spec }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
