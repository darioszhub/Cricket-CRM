<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Calls</title>
</head>

<body>
    <h1>Calls</h1>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>IDCall</th>
                <th>CallStart</th>
                <th>Duration</th>
                <th>DurationType</th>
                <th>Notes</th>
                <th>WhyNot</th>
                <th>Agent</th>
                <th>List</th>
                <th>Order</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($calls as $call)
                <tr>
                    <td>{{ $call->IDCall }}</td>
                    <td>{{ $call->CallStart }}</td>
                    <td>{{ $call->Duration }}</td>
                    <td>{{ $call->DurationType }}</td>
                    <td>{{ $call->ConversationNotes }}</td>
                    <td>{{ $call->WhyNot }}</td>
                    <td>{{ $call->agent->Name ?? 'N/A' }}</td>
                    <td>{{ $call->list->IDList ?? 'N/A' }}</td>
                    <td>{{ $call->order->IDOrder ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
