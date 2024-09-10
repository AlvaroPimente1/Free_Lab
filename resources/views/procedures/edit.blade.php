@extends('administrator.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h2>Visualizando Procedimento</h2></div>
                <div class="card-body">

                    <h4>{{ $procedure->name }}</h4>
                    <hr>

                    <div class="form-group">
                        <label class="label" for="mnemonic">Mnemônico do Procedimento</label>
                        <input
                            class="form-control"
                            type="text"
                            name="mnemonic"
                            id="mnemonic"
                            value="{{ $procedure->mnemonic }}"
                            readonly>
                    </div>

                    <div class="form-group">
                        <label class="label" for="method">Método</label>
                        <input
                            class="form-control"
                            type="text"
                            name="method"
                            id="method"
                            value="{{ $procedure->method }}"
                            readonly>
                    </div>

                    <div class="form-group">
                        <label class="label" for="material">Material</label>
                        <input
                            class="form-control"
                            type="text"
                            name="material"
                            id="material"
                            value="{{ $procedure->material }}"
                            readonly>
                    </div>

                    @if ($procedure->fields)
                    @php
                        // Decodifica o JSON armazenado no campo fields
                        $fieldsData = json_decode($procedure->fields, true);
                    @endphp

                    @if (isset($fieldsData['sessions']))
                        @foreach ($fieldsData['sessions'] as $session)
                            <h3>{{ $session['sessionName'] }}</h3>
                            <hr>

                            <div class="row g-3 mb-2">
                                <div class="col-sm-5">
                                    <h6>Nome do Exame</h6>
                                </div>
                                <div class="col-sm">
                                    <h6>Valor de Referência</h6>
                                </div>
                            </div>

                            @foreach ($session['exams'] as $exam)
                                <div class="row g-3 mb-2">
                                    <div class="col-sm-5">
                                        <input class="form-control" value="{{ $exam['examName'] }}" readonly>
                                    </div>
                                    <div class="col-sm">
                                        <input class="form-control" value="{{ $exam['referenceValue'] }}" readonly>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @endif
                    @endif

                    <hr class="border">

                    <h4>Soros Utilizados</h4>
                    <hr>
                    <div class="row gp-3 mb-2">
                        <div class="ml-3">
                            <input type="checkbox" id="soro_lipemico" name="soro_lipemico" value="1" {{ $procedure->soro_lipemico ? 'checked' : '' }} disabled>
                            <label for="soro_lipemico">Lipêmico</label>
                        </div>

                        <div class="ml-3">
                            <input type="checkbox" id="soro_icterico" name="soro_icterico" value="1" {{ $procedure->soro_icterico ? 'checked' : '' }} disabled>
                            <label for="soro_icterico">Ictérico</label>
                        </div>

                        <div class="ml-3">
                            <input type="checkbox" id="soro_hemolisado" name="soro_hemolisado" value="1" {{ $procedure->soro_hemolisado ? 'checked' : '' }} disabled>
                            <label for="soro_hemolisado">Hemolisado</label>
                        </div>

                        @if ($procedure->soro_outro)
                        <div class="ml-3">
                            <div class="col-sm-10">
                                <input type="text" name="soro_outro" class="form-control form-control-sm" id="soro_outro" value="{{ $procedure->soro_outro }}" readonly>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if ($procedure->conclusion)
                    <hr class="border">
                    <div class="form-group">
                        <label class="label" for="conclusion">Conclusão do Laudo</label>
                        <textarea
                            name="conclusion"
                            id="conclusion"
                            class="form-control"
                            readonly>{{ $procedure->conclusion }}</textarea>
                    </div>
                    @endif
                    <div class="form-group">
                        <button class="btn btn-outline-primary" id="returnButton" type="button" onclick="window.history.back()">Voltar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
