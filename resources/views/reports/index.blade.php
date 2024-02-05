@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h2>Criando um Laudo</h2></div>
                <div class="card-body">
                    <form method="GET" action="{{ route('createReport', ['lab_id' => $lab_id, 'procedure' => $procedure->id]) }}" onsubmit="makeBody()">

                        @csrf

                        <div class="form-group">
                            <label class="label" for="patient">Paciente</label>
                            <select
                                class="form-control"
                                name="patient">
                            @foreach ($patients as $patient)
                                <option value="{{$patient->id}}">{{$patient->name}}</option>
                            @endforeach
                            </select>
                        </div>
                        <button class="btn btn-outline-primary" id="returnButton" type="button">Voltar</button>
                        <a href="{{ route('patients.connect', $lab_id) }}" class="btn btn-outline-primary">Adicionar Pacientes ao laboratório</a>
                        <button type="submit" class="btn btn-primary">OK</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
