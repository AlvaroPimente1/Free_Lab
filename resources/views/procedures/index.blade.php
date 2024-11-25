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
                                    <td>{{ $procedure->id }}</td>
                                    <td>{{ $procedure->name }}</td>
                                    <td>{{ $procedure->mnemonic }}</td>
                                    <td class="d-flex justify-content-center">
                                        <!-- Botão VISUALIZAR -->
                                        <a href="{{ route('procedures.edit', ['lab_id' => $lab_id, 'procedure' => $procedure]) }}" class="btn btn-success mx-2"><b>EDITAR</b></a>
                                        
                                        <!-- Botão EXCLUIR com modal -->
                                        <button type="button" class="btn btn-danger mx-2" onclick="openModal({{ $procedure->id }})">EXCLUIR</button>
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

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteModal" class="modal" style="display:none;">
        <div class="modal-content">
            <h5>Tem certeza que deseja excluir este procedimento?</h5>
            <form id="deleteForm" method="POST" style="display:inline;">
                @csrf
                @method('PUT') <!-- Ou DELETE, dependendo de como configurou a rota -->
                <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script type="text/javascript">
    // Função para abrir o modal
    function openModal(procedureId) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');

        // Altera a ação do formulário para o procedimento correto
        form.action = `/procedures/${procedureId}/inactivate`;
        
        // Exibe o modal
        modal.style.display = 'block';
    }

    // Função para fechar o modal
    function closeModal() {
        const modal = document.getElementById('deleteModal');
        modal.style.display = 'none';
    }
</script>

<!-- CSS simples para estilizar o modal -->
<style>
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        text-align: center;
    }
</style>
@endsection
