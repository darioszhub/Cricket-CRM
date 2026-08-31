<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordine #{{ $order->IDOrder }}</title>
</head>

<body>
    <h1>Dettaglio Ordine #{{ $order->IDOrder }}</h1>
    <h3>Cambia Stato Ordine</h3>
    <form method="POST" action="{{ route('orders.updateState', $order->IDOrder) }}">
        @csrf
        @method('PUT')
        <select name="OrderState" class="form-select">
            @foreach ($orderStates as $state)
                <option value="{{ $state->IDOrderState }}"
                    {{ $order->OrderState == $state->IDOrderState ? 'selected' : '' }}>
                    {{ $state->Description }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Aggiorna Stato</button>
    </form>


    <p><strong>Stato Ordine Corrente:</strong> {{ $order->orderState->Description ?? 'Nessuno' }}</p>
    <p><strong>Nome:</strong> {{ $order->Name }}</p>
    <p><strong>Cognome:</strong> {{ $order->Surname }}</p>
    <p><strong>Codice Fiscale:</strong> {{ $order->CodFisc }}</p>
    <p><strong>Data Inserimento:</strong> {{ $order->TimestampINS }}</p>
    <p><strong>Codice Operatore:</strong> {{ $order->CodAgentT }}</p>
    <h2>Dettagli Ordine</h2>
    <table>
        <thead>
            <tr>
                <th>Codice Prodotto</th>
                <th>Descrizione Prodotto</th>
                <th>Quantità</th>
                <th>Prezzo Unitario</th>
                <th>Totale</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orders_details as $detail)
                <tr>
                    <td>{{ $detail->ordersPs->IDPS ?? 'N/A' }}</td>
                    <td>{{ $detail->ordersPs->Description ?? 'N/A' }}</td>
                    <td>{{ $detail->Quantity }}</td>
                    <td>{{ $detail->UnitPrice }}</td>
                    <td>{{ $detail->Quantity * $detail->UnitPrice }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Dettagli dell'Ordine</h3>
    <table>
        <thead>
            <tr>
                <th>Codice Prodotto</th>
                <th>Descrizione Prodotto</th>
                <th>Stato</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orders_details as $detail)
                <tr>
                    <td>{{ $detail->ordersPs->IDPS }}</td>
                    <td>{{ $detail->ordersPs->Description }}</td>
                    <td>{{ $detail->State1 ?? 'N/A' }}</td>
                    <td>
                        <!-- Modifica Stato -->
                        <form method="POST" action="{{ route('order-details.update-state', $detail->IDDetail) }}">
                            @csrf
                            @method('PATCH')
                            <select class="form-select" name="state" required>
                                <option value="">Seleziona Stato</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->State }}"
                                        {{ $state->State == $detail->State1 ? 'selected' : '' }}>
                                        {{ $state->State }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit">Aggiorna</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
