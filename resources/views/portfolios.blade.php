<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolios</title>
</head>

<body>
    <h1>Portfolios</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Portfolio</th>
                <th>Parent Portfolio</th>
                <th>Disabled</th>
                <th>Color</th>
                <th>Timestamp Created</th>
                <th>Timestamp Edited</th>
                <th>Last User Edit</th>
                <th>Group</th>
                <th>Group 2</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($portfolios as $portfolio)
                <tr>
                    <td>{{ $portfolio->IDPortfolio }}</td>
                    <td>{{ $portfolio->Portfolio }}</td>
                    <td>{{ $portfolio->parentPortfolio->Portfolio ?? 'None' }}</td>
                    <td>{{ $portfolio->Disabled ? 'Yes' : 'No' }}</td>
                    <td>{{ $portfolio->COLOR }}</td>
                    <td>{{ $portfolio->TimestampINS }}</td>
                    <td>{{ $portfolio->TimestampEDT }}</td>
                    <td>{{ $portfolio->CodLastUserEdit }}</td>
                    <td>{{ $portfolio->Group }}</td>
                    <td>{{ $portfolio->Group_2 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No Portfolios Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
