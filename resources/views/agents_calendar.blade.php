<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agents Calendar</title>
</head>

<body>
    <h1>Agents Calendar</h1>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>IDCalendar</th>
                <th>CodAgent</th>
                <th>Agent Name</th>
                <th>Day</th>
                <th>Max Appointments</th>
                <th>Time Start</th>
                <th>Time Stop</th>
                <th>Disabled</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($calendar as $entry)
                <tr>
                    <td>{{ $entry->IDCalendar }}</td>
                    <td>{{ $entry->CodAgent }}</td>
                    <td>{{ $entry->agent->Name ?? 'N/A' }} {{ $entry->agent->Surname ?? '' }}</td>
                    <td>{{ $entry->DayOfTheWeek }}</td>
                    <td>{{ $entry->MaxAppointments }}</td>
                    <td>{{ $entry->TimeStart }}</td>
                    <td>{{ $entry->TimeStop }}</td>
                    <td>{{ $entry->Disabled ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
