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
                                        <a href="{{ route('createReport', ['lab_id' => $lab_id, 'procedure' => $procedure->id, 'patient' => $patient_id]) }}" class="btn btn-success mx-2"><b>CRIAR</b></a>
                                        {{-- <a href="{{ route('procedures.destroy', ['procedure' => $procedure]) }}" value="DELETE" class="btn btn-danger mx-2">DELETAR</a> --}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{-- <a href="{{ route('administrators.show', $laboratory->id) }}" class="btn btn-primary mb-2">Voltar</a> --}}
                        <button class="btn btn-outline-primary" id="returnButton" type="button">Voltar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
