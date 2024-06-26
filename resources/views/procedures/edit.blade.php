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
            <div class="card-header"><h2>Editando um Procedimento</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('procedures.update', ['lab_id' => $lab_id, 'procedure' => $procedure, 'textmodel' => 0]) }}" id="procedureFormEdit">
                    @csrf
                    @method('PUT')

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
                            value="{{ old('name', $procedure->name) }}"
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
                            value="{{ old('mnemonic', $procedure->mnemonic) }}"
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
                            value="{{ old('method', $procedure->method) }}"
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

                    <div class="form-group">
                        <table class="table table-borderless">
                            <tbody id="fieldsGroup">
                            @for($i = 0; $i < $fieldCount; $i++)
                                <div class="row g-3 mb-2" id="{{$i+1}}">
                                    <div class="col-sm-5">
                                        <input type="text" id="iField{{$i+1}}" value="{{ explode('!-!', $fields[$i])[0] }}" class="form-control" required>
                                    </div>
                                    <div class="col-sm">
                                        <input type="text" placeholder="Preenchido depois" class="form-control" disabled>
                                    </div>
                                    <div class="col-sm">
                                        <input type="text" id="ref{{$i+1}}" value="{{ explode('!-!', $fields[$i])[1] }}" class="form-control" required>
                                    </div>
                                </div>
                            @endfor
                            </tbody>
                        </table>
                        @error('fields')
                            <p class='text-danger' id='fielderror'>{{ $errors -> first('fields') }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="button" id="addField" class="btn btn-primary">Novo Exame</button>
                        @if ($fieldCount > 0)
                            <button type="button" id="removeField" class="btn btn-danger">Remover Exame</button><br>
                        @else
                            <button type="button" id="removeField" class="btn btn-danger" disabled="disabled">Remover Exame</button><br>
                        @endif
                    </div>

                    <div class="mb-4 mt-4">
                        <h4>Soros Utilizados</h4>
                        <hr>

                        <div class="row gp-3 mb-2">
                            <div class="ml-3">
                                <input type="checkbox" id="soro_lipemico" name="soro_lipemico" value="1" {{ old('soro_lipemico', $procedure->soro_lipemico) ? 'checked' : '' }} />
                                <label for="soro_lipemico">Lipêmico</label>
                            </div>

                            <div class="ml-3">
                                <input type="checkbox" id="soro_icterico" name="soro_icterico" value="1" {{ old('soro_icterico', $procedure->soro_icterico) ? 'checked' : '' }} />
                                <label for="soro_icterico">Ictérico</label>
                            </div>

                            <div class="ml-3">
                                <input type="checkbox" id="soro_hemolisado" name="soro_hemolisado" value="1" {{ old('soro_hemolisado', $procedure->soro_hemolisado) ? 'checked' : '' }} />
                                <label for="soro_hemolisado">Hemolisado</label>
                            </div>

                            <div class="ml-3">
                                <div class="col-sm-10">
                                    <input type="text" name="soro_outro" class="form-control form-control-sm" id="soro_outro" placeholder="Outro" value="{{ old('soro_outro', $procedure->soro_outro) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group @if(!$procedure->conclusion) d-none @endif" id="conclusionDiv">
                        <label class="label" for="conclusion">Modelo de Conclusão/ões do Laudo</label>
                        <textarea
                            name="conclusion"
                            id="conclusion"
                            class="form-control mce"
                        >{{ old('conclusion', $procedure->conclusion) }}</textarea>
                        @error('conclusion')
                        <p class='text-danger'>{{ $errors -> first('conclusion') }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        @if ($procedure->conclusion)
                            <button type="button" id="addConclusion" class="btn btn-primary d-none">Adicionar Conclusão</button> 
                            <button type="button" id="removeConclusion" class="btn btn-danger">Remover Conclusão</button><br>
                        @else
                            <button type="button" id="addConclusion" class="btn btn-primary">Adicionar Conclusão</button> 
                            <button type="button" id="removeConclusion" class="btn btn-danger d-none">Remover Conclusão</button><br>
                        @endif                    
                    </div>

                    <textarea
                        name="fields"
                        id="body"
                        style="display:none"
                    ></textarea>

                    <button class="btn btn-outline-primary" id="returnButton" type="button">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar modelo de Laudo</button>
                </form>
            </div>
            <input id="fieldCount" class="d-none" value="{{$fieldCount}}">
            </div>
        </div>
    </div>
</div>
@endsection
