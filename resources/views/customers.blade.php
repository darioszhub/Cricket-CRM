<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customers</title>
</head>

<body>
    <h1>Customers</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>IDCustomer</th>
                <th>Name</th>
                <th>Surname</th>
                <th>Tel1</th>
                <th>Email1</th>
                <th>Portfolio</th>
                <th>Zone</th>
                <th>Locked</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->IDCustomer }}</td>
                    <td>{{ $customer->Name }}</td>
                    <td>{{ $customer->Surname }}</td>
                    <td>{{ $customer->Tel1 }}</td>
                    <td>{{ $customer->Email1 }}</td>
                    <td>{{ $customer->portfolio->Portfolio ?? 'N/A' }}</td>
                    <td>{{ $customer->zone->Description ?? 'N/A' }}</td>
                    <td>{{ $customer->Locked ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
