@extends('administrator.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow" style="border: 0px; border-radius: 15px">
                <div class="card-header" style="border-bottom: 0px; border-radius: 15px; background-color: darkseagreen"><h2 style="margin-bottom: 0px">Gerenciamento dos usuários do <b>{{ $laboratory->name }}</b></h2></div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
            </div>
            <p></p>
            <a href="{{ route('administrators.create', ['lab_id' => $laboratory->id]) }}" class="btn btn-primary">Cadastrar novo usuário</a>

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
                                <th scope="col" width="100px">Ações</th>
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
                                            {{-- @foreach ($user->administrator as $lab)
                                                @if ($lab->id === $laboratory->id)
                                                    - <b class="badge badge-sucess">Administrador</b>
                                                @endif
                                            @endforeach --}}
                                            @if ($user->administrator->contains($laboratory->id))
                                                - <b class="badge badge-sucess">Administrador</b>
                                            @endif
                                        </th>
                                        <th>
                                            @if ($user->id === auth()->user()->id)
                                                <button type="button" class="badge badge-danger" data-toggle="modal" data-target="#Modal1">Sair do laboratório</button>
                                                <div class="modal fade" id="Modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Sair do laboratório?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Você deixarar de ter acesso a este laboratório. Tem certeza?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                                <form method="POST" action="{{ route('administrators.destroy', ['administrator' => $user->id, 'lab_id' => $laboratory->id]) }} ">
                                                                    @method("DELETE")
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-primary">Sim</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <form method="POST" action="{{ route('administrators.destroy', ['administrator' => $user->id, 'lab_id' => $laboratory->id]) }} ">
                                                    @method("DELETE")
                                                    @csrf
                                                    <button type="submit" class="badge badge-danger">Remover integrante</button>
                                                </form>
                                            @endif

                                            {{-- @foreach ($user->administrator as $lab)
                                                @can('administrator', $lab)
                                                    @if ($user->id === auth()->user()->id)
                                                        <a href="{{ route('laboratories.disconnect', ['id' => $laboratory->id, 'user_id' => $user->id]) }}" class="badge badge-danger">Deixar de ser administrador</a>
                                                    @else
                                                        <a href="{{ route('laboratories.disconnect', ['id' => $laboratory->id, 'user_id' => $user->id]) }}" class="badge badge-danger">Remover como administrador</a>
                                                    @endif
                                                @endcan
                                            @endforeach --}}

                                            @if ($user->administrator->contains($laboratory->id))
                                                @if ($user->id === auth()->user()->id)
                                                    <a href="{{ route('laboratories.disconnect', ['id' => $laboratory->id, 'user_id' => $user->id]) }}" class="badge badge-danger" data-toggle="modal" data-target="#Modal2">Deixar de ser administrador</a>
  
                                                    <div class="modal fade" id="Modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Deixar de ser administrador?</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Você deixarar de ter privilégios de administrador deste laboratório. Tem certeza?
                                                                </div>
                                                                <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                                    <a href="{{ route('laboratories.disconnect', ['id' => $laboratory->id, 'user_id' => $user->id]) }}" class="btn btn-primary">Sim</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <a href="{{ route('laboratories.disconnect', ['id' => $laboratory->id, 'user_id' => $user->id]) }}" class="badge badge-danger">Remover como administrador</a>
                                                @endif
                                            @else
                                                <a href="{{ route('laboratories.connect', ['id' => $laboratory->id, 'user_id' => $user->id]) }}" class="badge badge-success">Tornar administrador</a>
                                            @endif

                                            {{-- @can('not_administrator', [$laboratory, $user])
                                                <a href="{{ route('laboratories.connect', ['id' => $laboratory->id, 'user_id' => $user->id]) }}" class="badge badge-success">Tornar administrador</a>
                                            @endcan --}}
                                        </th>
                                    </tr>
                                </tbody>
                            @endcan
                        @endforeach
                    </table>
                </div>
            </div>

            <div class="card shadow" style="border-radius: 15px; margin-top: 30px">
                <div class="card-body">
                    <div class="col-md-12">
                        <h3>Demais usuários</h3>
                        <p></p>
                    </div>
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
                                @cannot('laboratory-users', [$laboratory, $user])
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
                                            <a href="{{ route('laboratories.connect', ['id' => $laboratory->id, 'user_id' => $user->id]) }}" class="badge badge-success">Tornar administrador</a>
                                            <a href="{{ route('administrators.connect', ['id' => $laboratory->id, 'user_id' => $user->id]) }}" class="badge badge-success">Incluir integrante ao laboratório</a>
                                        </td>
                                    </tr>
                                @endcannot
                            @endforeach
                        </tbody>
                    </table>
                    <button class="btn btn-outline-primary" id="returnButton" type="button">Voltar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
