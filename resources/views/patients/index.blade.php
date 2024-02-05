@extends('administrator.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4>Administrador</h4>Root - Support</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="col-md-12">
                        <h2>Pacientes</h2>
                        {{-- <a href="{{ route('patients.create') }}" class="badge badge-success">Criar</a> --}}
                    </div>

                    <hr>

                    @if ($patients->all())

                        <table id="" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">CPF</th>
                                    <th scope="col" width="100px">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($patients as $patient)
                                    <tr>
                                        <td>{{ $patient->id }}</td>
                                        <td>{{ $patient->name }}</td>
                                        <td>{{ $patient->cpf }}</td>
                                        <th>
                                            <a href="{{ route('support.show', ['id' => $patient->id]) }}" class="badge badge-warning">Visualizar</a>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-primary" role="alert">
                            Não foi encontrado nenhum paciente cadastrado.
                        </div>
                    @endif
                    {{-- <a href="{{ route('home') }}" class="btn btn-primary">Voltar</a> --}}
                    <button class="btn btn-outline-primary" id="returnButton" type="button">Voltar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
