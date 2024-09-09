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
                                        <button type="button" class="btn btn-danger mx-2" onclick="inactivateProcedure({{ $procedure->id }})">INATIVAR</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('administrators.show', $laboratory->id) }}" class="btn btn-primary mb-2">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Adiciona a função JavaScript no final --}}
@section('scripts')
<script type="text/javascript">
    function inactivateProcedure(procedureId) {
        if (confirm('Tem certeza que deseja inativar este procedimento?')) {
            // Se o usuário confirmar, envia a requisição AJAX
            fetch(`/procedures/${procedureId}/inactivate`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (response.ok) {
                    alert('Procedimento inativado com sucesso!');
                    location.reload(); // Recarrega a página
                } else {
                    alert('Ocorreu um erro ao tentar inativar o procedimento.');
                }
            })
            .catch(error => {
                alert('Ocorreu um erro: ' + error);
            });
        }
    }
</script>
@endsection
