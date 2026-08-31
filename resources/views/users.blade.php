@extends('layout')
@section('title', 'Tabella Primi 10 Utenti')
@section('content')
    <h1>Primi 10 Utenti</h1>

    <table class="table" border="1">
        <thead>
            <tr>
                <th scope="col">IDUser</th>
                <th scope="col">Username</th>
                <th scope="col">Password (Keyword)</th>
                <th scope="col">CodAgent</th>
                <th scope="col">PowerUser</th>
                <th scope="col">Disabled</th>
                <th scope="col">DBSPID</th>
                <th scope="col">DBLOGINTIME</th>
                <th scope="col">IPADDRESS</th>
                <th scope="col">TimestampLastLogin</th>
                <th scope="col">LoginVisible</th>
                <th scope="col">ChangePwdFirstLogin</th>
                <th scope="col">TimestampINS</th>
                <th scope="col">TimestampEDT</th>
                <th scope="col">CodUserLastEdit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->IDUser }}</td>
                    <td>{{ $user->Username }}</td>
                    <td>{{ $user->Keyword }}</td>
                    <td>{{ $user->CodAgent }}</td>
                    <td>{{ $user->PowerUser }}</td>
                    <td>{{ $user->Disabled }}</td>
                    <td>{{ $user->DBSPID }}</td>
                    <td>{{ $user->DBLOGINTIME }}</td>
                    <td>{{ $user->IPADDRESS }}</td>
                    <td>{{ $user->TimestampLastLogin }}</td>
                    <td>{{ $user->LoginVisible }}</td>
                    <td>{{ $user->ChangePwdFirstLogin }}</td>
                    <td>{{ $user->TimestampINS }}</td>
                    <td>{{ $user->TimestampEDT }}</td>
                    <td>{{ $user->CodUserLastEdit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- <div>{{ dd($users) }}</div>  --}}
@endsection
