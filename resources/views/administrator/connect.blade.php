@extends('administrator.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow" style="border: 0px; border-radius: 15px">
                <div class="card-header" style="border-bottom: 0px; border-radius: 15px; background-color: darkseagreen"><h2 style="margin-bottom: 0px">Adicionar Integrantes ao <b>{{ $laboratory->name }}</b></h2></div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
            </div>

            <div class="card shadow" style="border-radius: 15px; margin-top: 30px">
                <div class="card-body">
                    <div class="col-md-12">
                        <h3>Usuários não integrantes de <b>{{ $laboratory->name }}</b></h3>
                        <a href="{{ route('administrators.create', ['lab_id' => $laboratory->id]) }}" class="badge badge-success">Criar novo Integrante</a>
                        <p></p>
                    </div>
                    <table id="" class="display">
                        <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">Nome</th>
                                <th scope="col">CPF</th>
                                <th scope="col">Função</th>
                                <th scope="col" width="100px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
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
                                    </th>
                                    <th>
                                        <a href="{{ route('administrators.connect', ['id' => $laboratory->id, 'user_id' => $user->id]) }}" class="badge badge-success">Incluir integrante ao laboratório</a>
                                    </th>
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('administrators.show', $laboratory->id) }}" class="btn btn-primary">Voltar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
