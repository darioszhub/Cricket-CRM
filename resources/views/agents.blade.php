@extends('layout')
@section('title', 'Tabella Primi 10 Agenti')
@section('content')
    <h1>Primi 10 Agenti</h1>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">IDAgent</th>
                <th scope="col">AgentCode</th>
                <th scope="col">ATYPE</th>
                <th scope="col">CodAgentParent</th>
                <th scope="col">Name</th>
                <th scope="col">Surname</th>
                <th scope="col">CodFisc</th>
                <th scope="col">PIVA</th>
                <th scope="col">Address</th>
                <th scope="col">CAP</th>
                <th scope="col">Prov</th>
                <th scope="col">City</th>
                <th scope="col">Town</th>
                <th scope="col">Phone</th>
                <th scope="col">Email</th>
                <th scope="col">Cell</th>
                <th scope="col">Fax</th>
                <th scope="col">BadgeNumber</th>
                <th scope="col">BornDate</th>
                <th scope="col">BornCity</th>
                <th scope="col">BornProv</th>
                <th scope="col">BornNation</th>
                <th scope="col">HiringDate</th>
                <th scope="col">DismissalDate</th>
                <th scope="col">Contract</th>
                <th scope="col">Gender</th>
                <th scope="col">DocType</th>
                <th scope="col">DocNumber</th>
                <th scope="col">DocRelease</th>
                <th scope="col">DocExpire</th>
                <th scope="col">DocProvider</th>
                <th scope="col">Paycheck</th>
                <th scope="col">IBAN</th>
                <th scope="col">Notes</th>
                <th scope="col">Disabled</th>
                <th scope="col">TimestampINS</th>
                <th scope="col">TimestampEDT</th>
                <th scope="col">CodUserLastEdit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agents as $agent)
                <tr>
                    <td>{{ $agent->IDAgent }}</td>
                    <td>{{ $agent->AgentCode }}</td>
                    <td>{{ $agent->ATYPE }}</td>
                    <td>{{ $agent->CodAgentParent }}</td>
                    <td>{{ $agent->Name }}</td>
                    <td>{{ $agent->Surname }}</td>
                    <td>{{ $agent->CodFisc }}</td>
                    <td>{{ $agent->PIVA }}</td>
                    <td>{{ $agent->Address }}</td>
                    <td>{{ $agent->CAP }}</td>
                    <td>{{ $agent->Prov }}</td>
                    <td>{{ $agent->City }}</td>
                    <td>{{ $agent->Town }}</td>
                    <td>{{ $agent->Phone }}</td>
                    <td>{{ $agent->Email }}</td>
                    <td>{{ $agent->Cell }}</td>
                    <td>{{ $agent->Fax }}</td>
                    <td>{{ $agent->BadgeNumber }}</td>
                    <td>{{ $agent->BornDate }}</td>
                    <td>{{ $agent->BornCity }}</td>
                    <td>{{ $agent->BornProv }}</td>
                    <td>{{ $agent->BornNation }}</td>
                    <td>{{ $agent->HiringDate }}</td>
                    <td>{{ $agent->DismissalDate }}</td>
                    <td>{{ $agent->Contract }}</td>
                    <td>{{ $agent->Gender }}</td>
                    <td>{{ $agent->DocType }}</td>
                    <td>{{ $agent->DocNumber }}</td>
                    <td>{{ $agent->DocRelease }}</td>
                    <td>{{ $agent->DocExpire }}</td>
                    <td>{{ $agent->DocProvider }}</td>
                    <td>{{ $agent->Paycheck }}</td>
                    <td>{{ $agent->IBAN }}</td>
                    <td>{{ $agent->Notes }}</td>
                    <td>{{ $agent->Disabled }}</td>
                    <td>{{ $agent->TimestampINS }}</td>
                    <td>{{ $agent->TimestampEDT }}</td>
                    <td>{{ $agent->CodUserLastEdit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- <div>{{ dd($agents) }}</div> --}}
@endsection