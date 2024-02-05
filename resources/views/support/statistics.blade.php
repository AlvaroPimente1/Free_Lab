@extends('administrator.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow" style="border-radius: 15px; margin-top: 30px">
                <div class="card-body">
                    <div class="col-md-12">
                        <h3>Laudos gerados pelos laboratórios</h3>
                        <p></p>
                    </div>

                    @isset($reports)
                        <table id="" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col" width="30px">id</th>
                                    <th scope="col" width="100px">Tipo de Exame</th>
                                    <th scope="col">Laboratório</th>
                                    <th scope="col">Requisitante</th>
                                    <th scope="col">Executante</th>
                                    <th scope="col">Paciente</th>
                                    <th scope="col" width="30px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>{{ $report->procedure->mnemonic }}</td>
                                        <td>{{ $report->laboratory->name }}</td>
                                        <td>{{ $report->requester }}</td>
                                        <td>{{ $report->signer->name }}</td>
                                        <td>{{ $report->patient->name }}</td>
                                        <td>
                                            <a href="{{ route('reportShow', $report->id) }}" class="badge badge-warning">Visualizar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-primary" role="alert">
                            Não existem ainda laudos expedidos no sistema
                        </div>
                    @endisset
                    <button class="btn btn-outline-primary" id="returnButton" type="button">Voltar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
