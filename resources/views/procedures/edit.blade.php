@extends('administrator.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Editando Procedimento</h2>
                </div>
                <div class="card-body">

                    <form action="{{ route('procedures.update', $procedure->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nome do Procedimento -->
                        <h4>Nome do Procedimento</h4>
                        <div class="form-group">
                            <input
                                class="form-control"
                                type="text"
                                name="name"
                                value="{{ $procedure->name }}">
                        </div>

                        <!-- Mnemônico -->
                        <div class="form-group">
                            <label class="label" for="mnemonic">Mnemônico do Procedimento</label>
                            <input
                                class="form-control"
                                type="text"
                                name="mnemonic"
                                id="mnemonic"
                                value="{{ $procedure->mnemonic }}">
                        </div>

                        <!-- Método -->
                        <div class="form-group">
                            <label class="label" for="method">Método</label>
                            <input
                                class="form-control"
                                type="text"
                                name="method"
                                id="method"
                                value="{{ $procedure->method }}">
                        </div>

                        <!-- Material -->
                        <div class="form-group">
                            <label class="label" for="material">Material</label>
                            <input
                                class="form-control"
                                type="text"
                                name="material"
                                id="material"
                                value="{{ $procedure->material }}">
                        </div>

                        <!-- Sessões e Exames -->
                        @if ($procedure->fields)
                        @php
                            $fieldsData = json_decode($procedure->fields, true);
                        @endphp

                        @if (isset($fieldsData['sessions']))
                            @foreach ($fieldsData['sessions'] as $sessionIndex => $session)
                                <div class="form-group">
                                    <label for="sessions[{{ $sessionIndex }}][sessionName]">Nome da Sessão</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        name="sessions[{{ $sessionIndex }}][sessionName]"
                                        value="{{ $session['sessionName'] ?? 'Sessão Sem Nome' }}">
                                </div>
                                <hr>

                                <div class="row g-3 mb-2">
                                    <div class="col-sm-5">
                                        <h6>Nome do Exame</h6>
                                    </div>
                                    <div class="col-sm">
                                        <h6>Valor de Referência</h6>
                                    </div>
                                </div>

                                @foreach ($session['exams'] as $examIndex => $exam)
                                    <div class="row g-3 mb-2">
                                        <div class="col-sm-5">
                                            <input
                                                class="form-control"
                                                name="sessions[{{ $sessionIndex }}][exams][{{ $examIndex }}][examName]"
                                                value="{{ $exam['examName'] ?? 'Nome do Exame Não Definido' }}">
                                        </div>
                                        <div class="col-sm">
                                            <input
                                                class="form-control"
                                                name="sessions[{{ $sessionIndex }}][exams][{{ $examIndex }}][referenceValue]"
                                                value="{{ $exam['referenceValue'] ?? 'Valor de Referência Não Definido' }}">
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        @endif
                        @endif

                        <hr class="border">

                        <!-- Soros Utilizados -->
                        <h4>Soros Utilizados</h4>
                        <hr>
                        <div class="row g-3 mb-2">
                            <div class="ml-3">
                                <input type="checkbox" id="soro_lipemico" name="soro_lipemico" value="1" {{ $procedure->soro_lipemico ? 'checked' : '' }}>
                                <label for="soro_lipemico">Lipêmico</label>
                            </div>

                            <div class="ml-3">
                                <input type="checkbox" id="soro_icterico" name="soro_icterico" value="1" {{ $procedure->soro_icterico ? 'checked' : '' }}>
                                <label for="soro_icterico">Ictérico</label>
                            </div>

                            <div class="ml-3">
                                <input type="checkbox" id="soro_hemolisado" name="soro_hemolisado" value="1" {{ $procedure->soro_hemolisado ? 'checked' : '' }}>
                                <label for="soro_hemolisado">Hemolisado</label>
                            </div>

                            <div class="ml-3">
                                <input
                                    type="text"
                                    name="soro_outro"
                                    class="form-control form-control-sm"
                                    value="{{ $procedure->soro_outro }}">
                            </div>
                        </div>

                        <!-- Conclusão do Laudo -->
                        @if ($procedure->conclusion)
                        <hr class="border">
                        <div class="form-group">
                            <label class="label" for="conclusion">Conclusão do Laudo</label>
                            <textarea
                                name="conclusion"
                                id="conclusion"
                                class="form-control">{{ $procedure->conclusion }}</textarea>
                        </div>
                        @endif

                        <!-- Botões -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>

                    <div class="form-group">
                        <button class="btn btn-outline-primary" id="returnButton" type="button" onclick="window.history.back()">Voltar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
