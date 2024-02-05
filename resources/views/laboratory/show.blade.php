@extends('layouts.app')

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

                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="mt-2">Laboratórios</h4>
                        <a href="{{ route('laboratories.create') }}" class="btn btn-success">Cadastrar novo Laboratório</a>
                    </div>

                    <p></p>

                    @if ($laboratories->all())

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Sigla</th>
                                    <th scope="col">Administradores</th>
                                    <th scope="col">Demais integrantes (Não Administrador)</th>
                                    <th scope="col" width="100px">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laboratories as $laboratory)
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
                                        <td>

                                            <form method="POST" action="{{ route('laboratories.destroy', $laboratory->id)  }} ">
                                                @method("DELETE")
                                                @csrf
                                                <a href="{{ route('laboratories.edit', $laboratory->id) }}" class="badge badge-warning">Editar</a>
                                                @if ($laboratory->enabled == 0)
                                                    <button type="submit" class="badge badge-success">Ativar</button>
                                                @else
                                                    <button type="submit" class="badge badge-danger">Desativar</button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-primary" role="alert">
                            Não foi encontrado nenhum laboratório cadastrado.
                        </div>
                    @endif
                    <a href="{{ route('home') }}" class="btn btn-primary">Voltar</a>
                    {{-- <button class="btn btn-outline-primary" id="returnButton" type="button">Voltar</button> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
