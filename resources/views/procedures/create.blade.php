@extends('administrator.layout')

@section('headJS')
    <script src="{{ asset('js/newfield.js') }}" defer></script>
    <script src="{{ asset('js/report.mask.js') }}" defer></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header"><h2>Criando um Novo Procedimento</h2></div>
            <div class="card-body">
                @if (!$text)
                <form method="POST" action="{{ route('procedures.store', ['lab_id' => $lab_id, 'textmodel' => 0]) }}" id="procedureForm">
                    @csrf

                    <h4>Dados Gerais</h4>
                    <hr>

                    <div class="form-group">
                        <label class="label" for="name">Nome do Procedimento</label>
                        <input
                            class="form-control @error('name') border-danger @enderror"
                            type="text"
                            name="name"
                            id="name"
                            placeholder="Ex: Hemograma"
                            value="{{ old('name') }}"
                            required>

                        @error('name')
                            <p class="text-danger">{{$errors->first('name')}}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="label" for="mnemonic">Mnemônico do Procedimento</label>
                        <input
                            class="form-control @error('mnemonic') border-danger @enderror"
                            type="text"
                            name="mnemonic"
                            id="mnemonic"
                            placeholder="Ex: HEM"
                            value="{{ old('mnemonic') }}"
                            required>

                        @error('mnemonic')
                            <p class="text-danger">{{$errors->first('mnemonic')}}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="label" for="method">Método do Procedimento</label>
                        <input
                            class="form-control @error('method') border-danger @enderror"
                            type="text"
                            name="method"
                            id="method"
                            placeholder="Ex: Automático"
                            value="{{ old('method') }}"
                            required>

                        @error('method')
                            <p class="text-danger">{{$errors->first('method')}}</p>
                        @enderror
                    </div>

                    <h4>Exames</h4>
                    <hr>

                    <div class="row g-3">
                        <div class="col-sm-5">
                          <h6>Nome do Exame</h6>
                        </div>
                        <div class="col-sm">
                            <h6>Valor</h6>
                        </div>
                        <div class="col-sm">
                            <h6>Valor de Referência</h6>
                        </div>
                    </div>

                    <p></p>

                    <div id="fieldsGroup">
                        {{-- Adicionar dinamicamente os campos de exames aqui --}}
                    </div>

                    <p></p>

                    <div class="form-group">
                        <button type="button" id="addField" class="btn btn-primary">Novo Exame</button>
                        <button type="button" id="removeField" class="btn btn-danger" disabled="disabled">Remover Exame</button><br>
                    </div>

                    <div class="mb-4 mt-4">
                        <h4>Soros Utilizados</h4>
                        <hr>

                        <div class="row gp-3 mb-2">
                            <div class="ml-3">
                                <input type="checkbox" id="soro_lipemico" name="soro_lipemico" />
                                <label for="soro_lipemico">Lipêmico</label>
                            </div>

                            <div class="ml-3">
                                <input type="checkbox" id="soro_icterico" name="soro_icterico" />
                                <label for="soro_icterico">Ictérico</label>
                            </div>

                            <div class="ml-3">
                                <input type="checkbox" id="soro_hemolisado" name="soro_hemolisado" />
                                <label for="soro_hemolisado">Hemolisado</label>
                            </div>

                            <div class="ml-3">
                                <div class="col-sm-10">
                                    <input type="text" name="soro_outro" class="form-control form-control-sm" id="soro_outro" placeholder="Outro">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group d-none" id="conclusionDiv">
                        <label class="label" for="body">Modelo de Conclusão do Laudo</label>
                        <textarea
                            name="conclusion"
                            id="conclusion"
                            class="form-control mce"
                            value="{{ old('conclusion') }}"
                        ></textarea>
                    </div>

                    <div class="form-group">
                        <button type="button" id="addConclusion" class="btn btn-primary">Adicionar Conclusão</button> 
                        <button type="button" id="removeConclusion" class="btn btn-danger d-none">Remover Conclusão</button><br>
                    </div>
                    @error('conclusion')
                        <p class='text-danger'>{{ $errors -> first('conclusion') }}</p>
                    @enderror

                    <textarea
                        name="fields"
                        id="body"
                        style='display:none'
                    ></textarea>
                    <button class="btn btn-outline-primary" id="returnButton" type="button">Voltar</button>
                    <button type="submit" class="btn btn-primary">Submeter novo modelo de Laudo</button>
                </form>
                @endif
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
