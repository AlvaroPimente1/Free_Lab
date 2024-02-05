@extends('administrator.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <a href="{{ route('home') }}" class="btn btn-primary">Escolher outro laboratório</a>

            <div class="card shadow" style="border-radius: 15px; margin-top: 30px">
                <div class="card-body">
                    <div class="col-md-12">
                        <h3>Laudos gerados pelo <b>{{ $laboratory->name }}</b></h3>
                        <p></p>
                    </div>

                    @isset($reports)
                        <table id="" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">Tipo de Exame</th>
                                    <th scope="col">Requisitante</th>
                                    <th scope="col">Executante</th>
                                    <th scope="col" width="100px">Paciente</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    {{-- @can('patient_lab', [$patient, $laboratory->id]) --}}
                                        <tr>
                                            <td>{{ $report->id }}</td>
                                            <td>{{ $report->procedure->mnemonic }}</td>
                                            <td>{{ $report->requester }}</td>
                                            <td>{{ $report->signer->name }}</td> {{--Mostrar nome do executante--}}
                                            <td>{{ $report->patient->name }}</td> {{--Mostrar nome do paciente--}}
                                            <td>
                                                <a href="{{ route('reportShow', $report->id) }}" class="badge badge-warning">Visualizar</a>
                                            </td>
                                        </tr>
                                    {{-- @endcan --}}
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-primary" role="alert">
                            Não existem ainda laudos expedidos por este laboratório
                        </div>
                    @endisset
                </div>
            </div>

            <div class="card shadow" style="border-radius: 15px; margin-top: 30px">
                <div class="card-body">
                    <div class="col-md-12">
                        <h3>Pacientes de <b>{{ $laboratory->name }}</b></h3>                   
                        <p></p>
                    </div>

                    @isset($patients)
                        <table id="" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">CPF</th>
                                    <th scope="col" width="100px">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($patients as $patient)
                                    <tr>
                                        <td>{{ $patient->name }}</td>
                                        <td>{{ $patient->cpf }}</td>
                                        <td>
                                            <a href="{{ route('patients.show', ['id' => $patient->id, 'lab_id' => $laboratory->id]) }}" class="badge badge-warning">Visualizar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-primary" role="alert">
                            Não existem ainda laudos expedidos por este laboratório
                        </div>
                    @endisset
                </div>
            </div>

            <div class="card shadow" style="border-radius: 15px; margin-top: 30px">
                <div class="card-body">
                    <div class="col-md-12">
                        <h3>Integrantes do <b>{{ $laboratory->name }}</b></h3>
                        {{-- <a href="{{ route('administrators.connect', $laboratory->id) }}" class="badge badge-success">Adicionar um novo integrante ao laboratório</a> --}}
                        <p></p>
                    </div>
                        
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">Nome</th>
                                <th scope="col">CPF</th>
                                <th scope="col">Função</th>
                                {{-- <th scope="col" width="100px">Ações</th> --}}
                            </tr>
                        </thead>
                        @foreach ($users as $user)
                            @can('laboratory-users', [$laboratory, $user])
                                <tbody>
                                    <tr>
                                        <th>{{ $user->id }}</th>
                                        <th>{{ $user->name }}</th>
                                        <th>{{ $user->cpf }}</th>
                                        <th>
                                            @switch($user->role)
                                                @case('intern')
                                                    {{env('INTERN_NAME')}}
                                                    @break
                                                @case('employee')
                                                    {{env('EMPLOYEE_NAME')}}
                                                    @break
                                                @default
                                            @endswitch
                                            @foreach ($user->administrator as $lab)
                                                @if ($lab->id === $laboratory->id)
                                                    - <b class="badge badge-sucess">Administrador</b>
                                                @endif
                                            @endforeach
                                        </th>
                                        <th>
                                            {{-- <form method="POST" action="{{ route('administrators.destroy', ['administrator' => $user->id, 'lab_id' => $laboratory->id]) }} ">
                                                @method("DELETE")
                                                @csrf
                                                <button type="submit" class="badge badge-danger">
                                                    @if ($user->id === auth()->user()->id)
                                                        Sair do laboratório
                                                    @else
                                                        Remover integrante
                                                    @endif
                                                </button>
                                            </form> --}}
                                        </th>
                                    </tr>
                                </tbody>
                            @endcan
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection