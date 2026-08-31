@extends('layout')
@section('title', 'Tabella Primi 10 Agenti')
@section('content')
    <h1>Lista degli Ordini</h1>
    <table class="table">
        <thead>
            <tr>
                <th>IDOrder</th>
                <th>Data Inserimento</th>
                <th>OrderState</th>
                <th>Nome e Cognome</th>
                <th>CodAgentE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->IDOrder }}</td>
                    <td>{{ $order->TimestampINS }}</td>
                    <td>{{ $order->OrderState }}</td>
                    <td>{{ $order->Name }} {{ $order->Surname }}</td>
                    <td>{{ $order->CodAgentE }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if (session('error'))
        <div style="color: red;">
            {{ session('error') }}
        </div>
    @endif
    <h1>Filtra Ordini</h1>
    <form action="{{ route('orders.submit') }}" method="POST">
        @csrf

        <label for="date">Data:</label>
        <input type="date" name="date" id="date" value="{{ request('date') }}">

        <label for="name">Nome:</label>
        <input type="text" name="name" id="name" placeholder="Inserisci il nome" value="{{ request('name') }}">

        <label for="surname">Cognome:</label>
        <input type="text" name="surname" id="surname" placeholder="Inserisci il cognome"
            value="{{ request('surname') }}">

        <label for="codfisc">Codice Fiscale:</label>
        <input type="text" name="codfisc" id="codfisc" placeholder="Inserisci il codice fiscale"
            value="{{ request('codfisc') }}">

        <button type="submit">Filtra</button>
    </form>

@endsection
