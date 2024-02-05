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

                    <a href="{{ route('support.statistics') }}" type="button" class="btn btn-success">
                        Estatísticas
                    </a>

                    <a href="{{ route('laboratories.index') }}" type="button" class="btn btn-primary">
                        Laboratórios <span class="badge badge-light">{{ count($laboratories->all()) }}</span>
                    </a>

                    <a href="{{ route('patients.index') }}" type="button" class="btn btn-primary">
                        Pacientes <span class="badge badge-light">{{ count($patients->all()) }}</span>
                    </a>

                    @if (!$laboratories->all())
                        <div class="alert alert-primary" role="alert">
                            Não existem laboratórios cadastrados.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card" style="margin-top: 30px">
                <div class="card-body">
                    <div class="col-md-12">
                        <h4>Administradores dos Laboratórios</h4>
                        <p></p>
                    </div>

                    @if ( isset($responsibles) && (!($responsibles === [])) )
                        
                        <table id="" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">CPF</th>
                                    <th scope="col" width="300px">Laboratórios que administra</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($responsibles as $responsible)
                                    <tr>
                                        <td scope="row">{{ $responsible->id }}</td>
                                        <td>{{ $responsible->name }}</td>
                                        <td>{{ $responsible->cpf }}</td>
                                        <td>
                                            @if ($responsible->administrator->all() == [])
                                                Não é responsável por nenhum laboratório atualmente
                                            @else
                                                @foreach ($responsible->administrator as $lab)
                                                    | {{ $lab->name }} |
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-primary" role="alert">
                            Não foi encontrado nenhum responsável.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card" style="margin-top: 30px">
                <div class="card-body">
                    <div class="col-md-12">
                        <h4>Todos os Usuários do Sistema</h4>
                        <a href="{{ route('users.create') }}" class="badge badge-success">Cadastrar novo usuário</a>
                        <p></p>
                    </div>

                    @if ($users->all())
                        
                        <table id="" class="display" style="width:100%">
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
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->cpf }}</td>
                                        <td>
                                            @switch($user->role)
                                                @case('intern')
                                                    {{env('INTERN_NAME')}}
                                                    @break
                                                @case('employee')
                                                    {{env('EMPLOYEE_NAME')}}
                                                    @break
                                                @default
                                                    
                                            @endswitch
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('users.destroy', $user->id)  }} ">
                                                @method("DELETE")
                                                @csrf
                                                <a href="{{ route('users.edit', $user->id) }}" class="badge badge-warning">Editar</a>
                                                <button type="submit" class="badge badge-danger">Apagar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-primary" role="alert">
                            Não foi encontrado nenhum usuário
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
