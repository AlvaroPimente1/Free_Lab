@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow" style="border-radius: 15px; margin-top: 30px">
                <div class="card-body">
                    <div class="col-md-12">
                        <h3>Modelos de Laudo do <b>{{ $laboratory->name }}</b></h3>
                        <p></p>
                    </div>

                    <div class="d-flex justify-content-center mb-4">
                        <a href="{{ route('procedures.create', ['lab_id' => $lab_id, 'text' => 0]) }}" class="btn btn-primary mr-2">Criar novo modelo</a>
                    </div>

                    <div class="table-responsive-md">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Mnemônico</th>
                                    <th scope="col" style="display:flex;justify-content: center">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($procedures as $procedure)
                                <tr>
                                    <td>{{ $procedure->id}}</td>
                                    <td>{{ $procedure->name}}</td>
                                    <td>{{ $procedure->mnemonic}}</td>
                                    <td class="d-flex justify-content-center">
                                        <a href="{{ route('procedures.edit', ['lab_id' => $lab_id, 'procedure' => $procedure]) }}" class="btn btn-warning mx-2"><b>EDITAR</b></a>
                                        {{-- <a href="{{ route('procedures.destroy', ['procedure' => $procedure]) }}" value="DELETE" class="btn btn-danger mx-2">DELETAR</a> --}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('administrators.show', $laboratory->id) }}" class="btn btn-primary mb-2">Voltar</a>
                        {{-- <button class="btn btn-outline-primary" id="returnButton" type="button">Voltar</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
