@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow" style="border: 0px; border-radius: 15px">
                <div class="card-header" style="border-bottom: 0px; border-radius: 15px; background-color: darkseagreen"><h2 style="margin-bottom: 0px"><b>{{Auth::user()->name}}</b></h2></div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
            </div>

            <div class="card shadow" style="border-radius: 15px; margin-top: 30px">
                <div class="card-body">
                    <div class="col-md-12">
                        <h3>Seus laboratórios</h3>
                        <p></p>
                    </div>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Sigla</th>
                                <th scope="col">Todos os administradores</th>
                                <th scope="col">Demais integrantes (Não Administrador)</th>
                                <th scope="col" width="100px">Ações</th>
                            </tr>
                        </thead>
                        @foreach ($laboratories as $laboratory)
                            @can('laboratory-users', [$laboratory, auth()->user()])
                                <tbody>
                                    <tr>
                                        <td>{{ $laboratory->id }}</td>
                                        <td>{{ $laboratory->name }}</td>
                                        <td>{{ $laboratory->mnemonic }}</td>
                                        <td>
                                            @if ($laboratory->administrator->all() == [])
                                                Não há responsável por este laboratório atualmente
                                            @else
                                                @foreach ($laboratory->administrator as $admin)
                                                    <p> {{ $admin->name }} <p>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if ($laboratory->employees->all() == [])
                                                Não há outros integrantes neste laboratório atualmente
                                            @else
                                                @php
                                                    $print = true;
                                                @endphp

                                                @foreach ($laboratory->employees as $employee)
                                                    @foreach ($laboratory->administrator as $admin)
                                                        @if ($employee->id == $admin->id)
                                                            @php
                                                                $print = false;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    
                                                    @if ($print == true)
                                                        <p> {{ $employee->name }} <p>
                                                    @endif

                                                    @php
                                                        $print = true;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        </td>
                                        <th>
                                            @foreach ($laboratory->administrator as $user)
                                                @if ($user->id === auth()->user()->id)
                                                    <a href="{{ route('laboratories.edit', $laboratory->id) }}" class="badge badge-warning">Editar</a>
                                                @endif
                                            @endforeach
                                            <a href="{{ route('employees.show', $laboratory->id) }}" class="badge badge-success">Entrar</a>
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
