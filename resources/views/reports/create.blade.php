@extends('administrator.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header"><h2>Criando um Laudo</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('storeReport', ['lab_id' => $lab_id, 'procedure' => $procedure->id, 'patient' => $patient->id]) }}" onsubmit="makeBody()">
                    @csrf

                    <h4>Dados Gerais</h4>
                    <hr>
                    {{--<div class="form-group">
                        {{-- <input
                            class="form-control @error('mnemonic') border-danger @enderror"
                            type="text"
                            name="mnemonic"
                            id="mnemonic"
                            placeholder="Mnemônico"
                            value="{{ old('mnemonic') }}">

                        @error('mnemonic')
                            <p class='text-danger'>{{ $errors -> first('mnemonic') }}</p>
                        @enderror --}}
                        <label class="label" for="procedure">{{$procedure->name}}</label>
                        {{--<select class="form-control" id="dynamic_select" name="procedure">
                            @foreach ($procedures as $procedure)
                                <option value="{{$procedure->id}}">{{$procedure->name }}</option>
                            @endforeach
                        </select>
                    </div>--}}
                    <div class="form-group">
                        <label class="label" for="requester">Médico Requisitante</label>
                        <input
                            class="form-control @error('requester')border-danger @enderror"
                            type="text"
                            name="requester"
                            id="requester"
                            placeholder="Nome do médico requisitante"
                            value="{{old('requester')}}"
                            required>

                        @error('requester')
                        <p class="text-danger">{{$errors->first('requester')}}</p>
                        @enderror

                    </div>
                    <div class="form-group">
                        <label class="label" for="method">Método</label>
                        <input
                            class="form-control @error('method')border-danger @enderror"
                            type="text"
                            name="method"
                            id="method"
                            value="{{$procedure->method}}"
                            required>

                        @error('method')
                        <p class="text-danger">{{$errors->first('method')}}</p>
                        @enderror

                    </div>

                    <div class="form-group">
                        <label class="label" for="patient">Paciente: {{$patient->name}}</label>

                        @error('patient')
                        <p class="text-danger">{{$errors->first('patient')}}</p>
                        @enderror
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
                                    <h6>Valor</h6>
                                </div>
                                <div class="col-sm">
                                    <h6>Valor de Referência</h6>
                                </div>
                            </div>

                            @foreach ($session['exams'] as $exam)
                                <div class="row g-3 mb-2">
                                    <div class="col-sm-5">
                                        <input class="form-control" name="exam_{{ strtolower($exam['examName']) }}" value="{{ $exam['examName'] }}" disabled>
                                    </div>
                                    <div class="col-sm">
                                        <input class="form-control" id="exam_{{ strtolower($exam['examName']) }}" name="values[{{ strtolower($exam['examName']) }}]" type="text" value="{{ old('values.' . strtolower($exam['examName'])) }}" placeholder="Valor de {{ $exam['examName'] }}">
                                    </div>
                                    <div class="col-sm">
                                        <input class="form-control" value="{{ $exam['referenceValue'] }}" disabled>
                                    </div>
                                    @error('values.' . strtolower($exam['examName']))
                                        <p class='text-danger'>{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        @endforeach
                    @endif
                @endif

                    <hr class="border">
                   <div class="form-group">
                        <label class="label" for="body">Conclusão do Laudo</label>
                        <textarea
                            name="conclusion"
                            id="conclusion"
                            class="form-control mce"
                            {{-- @if (!$procedure->textmodel)
                                style='display:none'>
                            @else
                                >{{$fields}}
                            @endif --}}
                            >{{$procedure->conclusion}}
                            </textarea>

                        @error('conclusion')
                            <p class='text-danger'>{{ $errors -> first('conclusion') }}</p>
                        @enderror
                    </div>
                    <textarea
                        name="body"
                        id="body"
                        class="form-control"
                        style='display:none'>
                    </textarea>
                    {{-- <div class="form-group">
                        <label for="lab">Laboratório</label>
                        <select class=form-control>
                            @foreach ($labs as $lab)
                                <option value="{{$lab->id}}">{{$lab->name}}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="form-group">
                        <button class="btn btn-outline-primary" id="returnButton" type="button">Voltar</button>
                        <button type="submit" class="btn btn-primary d-inline">Submeter</button>
                        <p class="d-inline position-absolute text-right mt-0" style="right:20px">Arraste para aumentar o campo de edição de texto &uarr;</p>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
{{-- Tudo abaixo é para motivos de teste. Nada além da variável fieldNames deve ser mantido, e o resto deve ser exportado para um arquivo *.js --}}
<script>
    function makeBody(){
        let jsonStructure = {
            sessions: []
        };

        // Itera sobre cada sessão
        $('.card-body').find('h3').each(function(){
            let sessionName = $(this).text().trim();
            let exams = [];

            // Itera sobre os exames dentro da sessão
            $(this).next('hr').nextAll('.row.g-3.mb-2').each(function(){
                let examName = $(this).find('input.form-control[disabled]').val();
                let fieldValue = $(this).find('input[type="text"]').val();
                let referenceValue = $(this).find('input[type="text"].form-control[disabled]').val();

                exams.push({
                    examName: examName,
                    value: fieldValue,
                    referenceValue: referenceValue
                });
            });

            jsonStructure.sessions.push({
                sessionName: sessionName,
                exams: exams
            });
        });

        console.log(JSON.stringify(jsonStructure)); // Para verificar se o JSON está correto
        $('#body').val(JSON.stringify(jsonStructure));
    }
</script>
@endsection
