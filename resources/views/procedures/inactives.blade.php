@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow" style="border-radius: 15px; margin-top: 30px">
                <div class="card-body">
                    <div class="col-md-12">
                        <h3>Modelos de Laudo Inativos do <b>{{ $laboratory->name }}</b></h3>
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
                                        <form action="{{ route('procedures.activate', ['procedure' => $procedure->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja ativar este procedimento?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success mx-2">Ativar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <button class="btn btn-outline-primary" id="returnButton" type="button" onclick="window.history.back()">Voltar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
