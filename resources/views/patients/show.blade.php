@extends('administrator.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h4>Dados do(a) paciente</h4></div>

                <div class="card-body">

                    <div class="dadosPaciente">
                        <h5><b>Dados Pessoais</b></h5>
                        <h6><b>Nome: </b>{{ $patient->name }}</h6>
                        <h6><b>CPF: </b>{{ $patient->cpf }}</h6>
                        <h6><b>Profissão: </b>{{ $patient->profession }}</h6>
                        <h6><b>Data de Nascimento: </b>{{ $patient->birthday }}</h6>
                        <h6><b>Altura: </b>{{ $patient->height }}</h6>
                        <h6><b>Peso: </b>{{ $patient->weight }}</h6>
                        <br>
                        <h5><b>Dados de Contato</b></h5>
                        <h6><b>Email: </b>{{ $patient->email }}</h6>
                        <h6><b>Telefone: </b>{{ $patient->phone }}</h6>
                        <br>
                        <h5><b>Dados de Endereço</b></h5>
                        <h6><b>Endereço: </b>{{ $patient->address }}</h6>
                        <h6><b>CEP: </b>{{ $patient->cep }}</h6>
                        <h6><b>Bairro: </b>{{ $patient->neighborhood }}</h6>
                        <h6><b>Estado: </b>{{ $patient->state }}</h6>
                    </div>
                    @can('administrator', $laboratory)
                        <a href="{{ route('patients.edit', ['id' => $patient->id, 'lab_id' => $laboratory->id]) }}" class="btn btn-primary">Editar</a>
                    @endcan

                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Exames do(a) paciente</h3></div>

                <div class="card-body">

                    <table id="" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Tipo de Exame</th>
                                <th scope="col">Requisitante</th>
                                <th scope="col">Executante</th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                @can('report_lab', [$laboratory, $report])
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>{{ $report->procedure->mnemonic }}</td>
                                        <td>{{ $report->requester }}</td>
                                        <td>{{ $report->signer->name }}</td>
                                        <td>
                                            <a href="{{ route('reportShow', $report->id) }}" class="badge badge-warning">Visualizar</a>
                                        </td>
                                    </tr>
                                @endcan
                            @endforeach
                        </tbody>
                    </table>

                    <button class="btn btn-outline-primary" id="returnButton" type="button">Voltar</button>
                    @can('administrator', $laboratory)
                        <a href="{{ route('showProcedures', ['lab_id' => $laboratory, 'patient_id' => $patient->id]) }}" class="btn btn-success">Gerar novo laudo</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
