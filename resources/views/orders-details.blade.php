@extends('layout')
@section('title', 'Tabella Primi 10 Agenti')
@section('content')
    <h1>Dettagli Ordini</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID Dettaglio</th>
                <th>Codice Ordine</th>
                <th>Descrizione</th>
                <th>Prezzo Unitario</th>
                <th>Quantità</th>
                <th>Data Conferma</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ordersDetails as $detail)
                <tr>
                    <td>{{ $detail->IDDetail }}</td>
                    <td>{{ $detail->CodOrder }}</td>
                    <td>{{ $detail->Description }}</td>
                    <td>{{ $detail->UnitPrice }}</td>
                    <td>{{ $detail->Quantity }}</td>
                    <td>{{ $detail->Date_Confirm }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
