<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco Orders_PS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div>
    <h1>Elenco Orders_PS</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>IDPS</th>
                <th>Description</th>
                <th>CodPortfolio</th>
                <th>Disabled</th>
                <th>Group1</th>
                <th>Group2</th>
                <th>Group3</th>
                <th>Group4</th>
                <th>Visible4Selling</th>
                <th>UM</th>
                <th>UnitCost</th>
                <th>UnitPrice</th>
                <th>VAT</th>
                <th>RevenueAGN</th>
                <th>RevenueTLK</th>
                <th>MinQuantity</th>
                <th>TimestampINS</th>
                <th>TimestampEDT</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ordersPS as $orderPS)
                <tr>
                    <td>{{ $orderPS->IDPS }}</td>
                    <td>{{ $orderPS->Description }}</td>
                    <td>{{ $orderPS->CodPortfolio ? 'Yes' : 'No' }}</td>
                    <td>{{ $orderPS->Disabled ? 'Yes' : 'No' }}</td>
                    <td>{{ $orderPS->Group1 }}</td>
                    <td>{{ $orderPS->Group2 }}</td>
                    <td>{{ $orderPS->Group3 }}</td>
                    <td>{{ $orderPS->Group4 }}</td>
                    <td>{{ $orderPS->Visible4Selling ? 'Yes' : 'No' }}</td>
                    <td>{{ $orderPS->UM }}</td>
                    <td>{{ number_format($orderPS->UnitCost, 2) }}</td>
                    <td>{{ number_format($orderPS->UnitPrice, 2) }}</td>
                    <td>{{ $orderPS->VAT }}</td>
                    <td>{{ number_format($orderPS->RevenueAGN, 2) }}</td>
                    <td>{{ number_format($orderPS->RevenueTLK, 2) }}</td>
                    <td>{{ number_format($orderPS->MinQuantity, 2) }}</td>
                    <td>{{ $orderPS->TimestampINS }}</td>
                    <td>{{ $orderPS->TimestampEDT }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="18">Nessun record trovato.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
