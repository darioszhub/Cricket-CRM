<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risultati Filtrati</title>
</head>

<body>
    <h1>Risultati:</h1>
    @if ($orders->isEmpty())
        <p>Nessun ordine trovato per questa data.</p>
    @else
        <table border="1">
            <thead>
                <tr>
                    <th>ID Ordine</th>
                    <th>Data Inserimento</th>
                    <th>Data Appuntamento</th>
                    <th>Operatore</th>
                    <th>Telefono</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Città</th>
                    <th>Indirizzo</th>
                    <th>Codice Fiscale</th>
                    
                    <!-- Aggiungi altre colonne se necessario -->
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->IDOrder }}</td>
                        <td>{{ $order->TimestampINS }}</td>
                        <td>{{ $order->Date_Appnt }}</td>
                        <td>{{ $order->CodAgentT }}</td>
                        <td>{{ $order->Tel1 }}</td>
                        <td>{{ $order->Name }}</td>
                        <td>{{ $order->Surname }}</td>
                        <td>{{ $order->City }}</td>
                        <td>{{ $order->Address }}</td>
                        <td>{{ $order->CodFisc }}</td>
                        <td>
                            <a href="{{ route('orders.show', ['order' => $order->IDOrder]) }}">
                                <button>Visualizza</button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif



    {{-- <a href="{{ route('orders') }}">Torna al form</a> --}}
</body>

</html>
