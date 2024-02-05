@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 card" style="height: 1400px" style="padding: 10px">
            <img class="card-img" style="margin: auto; width: 500px; opacity: 0.1" src="{{url('css/logo_ufpa.png')}}" alt="Card image">
            <div class="card-img-overlay">
                <table class="table">
                    <tr>
                        <td class="border text-center" style="width: 25%; vertical-align: middle">
                            <img src="{{ url('css/logo.png') }}" alt="" style="width: 100%">
                        </td>
                        <td class="border text-center" style="width: 50%; vertical-align: middle">
                            <div><h5 style="margin: 0px">Av. Generalíssimo Deodoro, 92 - Umarizal, Belém - PA, 66055-240</h5></div>
                            <p></p>
                            <div><h5 style="margin: 0px">Telefone: (91) 3201-0950</h5></div>
                        </td>
                        <td class="border text-center" style="width: 25%; vertical-align: middle">
                            <div><h4 style="margin: 0px">Requisição:</h4></div>
                            <p></p>
                            <div><h3 style="margin: 0px">{{$report->id}}</h3></div>
                        </td>
                    </tr>
                </table>

                <h2 class="text-center font-weight-bold">{{ $report->procedure->name}}</h2>
                <h4><p class="text-center">Método: {{$report->method}}</p></h4>

                <div class="outer-container border" style="padding: 20px">
                    <table style="width: 100%">
                        <tr>
                            <td class="text-center" style="width: 50%">
                                <div class="row" style="min-height: 20px; margin: 1%">
                                    <h5 style="margin-right: 1%"><b>Nome do Paciente:</b></h5><h5>{{ $report->patient->name }}</h5>
                                </div>
                                <div class="row" style="min-height: 20px; margin: 1%">
                                    <h5 style="margin-right: 1%"><b>Número de Cadastro:</b></h5><h5>{{ $report->patient->id }}</h5>
                                </div>
                            </td>
                            <td class="text-center" style="width: 50%">
                                <div class="row" style="min-height: 20px; margin: 1%">
                                    <h5 style="margin-right: 1%"><b>Data da Realização do Exame:</b></h5><h5>{{ $report->created_at->format('d/m/Y') }}</h5>
                                </div>
                                <div class="row" style="min-height: 20px; margin: 1%">
                                    <h5 style="margin-right: 1%"><b>Médico Requisitante:</b></h5><h5>{{ $report->requester }}</h5>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="outer-container" style="padding: 30px">
                    <p>
                        @if($report->body)
                            @php
                                $fields = explode('@-@', $body);
                            @endphp

                            <table style="width:90%; margin: 0 auto">
                                <thead>
                                    <tr>
                                        <th scope="col" width="300px" height="50px">Exame</th>
                                        <th scope="col">Resultado</th>
                                        <th scope="col" width="200px">Valor de referência:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fields as $field)
                                    @php
                                        $couple = explode('!-!', $field);
                                        $finalFields[$couple[0]] = $couple[2];
                                    @endphp
                                        <tr>
                                            <td class="paragrafo"><h5>{{$couple[0]}}:</h5><hr></td>
                                            <td><h5><b>{{$couple[2]}}</b></h5></td>
                                            <td><h5>{{$couple[1]}}</h5></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                        @if ($report->conclusion)
                            <p></p>
                            <hr style="
                                border-top: 2px dashed #CCC;
                                border-bottom: 2px dashed #CCC;
                                height: 2px;
                                overflow: visible;
                            ">
                            <h4>Conclusão:</h4>
                            <h5>{!!($report->conclusion)!!}</h5>
                        @endif
                    </p>
                </div>
            </div>
            <div class="card-footer text-center">
                {{-- Necessita de CRM no campo de usuários --}}
                <h5 class="col-md-12">Responsável Técnico {{ $report->signer->name}}, CRM </h5>
                <h5 class="col-md-12">{{ $report->laboratory->name }} - NMT - UFPA</h5>
                <h6 class="col-md-12">Belém - PA - @php
                    date_default_timezone_set('America/Sao_Paulo');
                    $date = date('d/m/Y');
                    echo $date
                @endphp</h6>
            </div>
        </div>
        <div class="d-print-none" style="margin-top: 20px">
            <div class="row justify-content-center">
                <div>
                    {{-- <a href="{{ url()->previous() }}" class="btn btn-outline-primary">Voltar</a> --}}
                    <button class="btn btn-outline-primary" id="returnButton" type="button">Voltar</button>
                    <button type="button" class="btn btn-primary" onclick="window.print()">Imprimir</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<style>
    .paragrafo{
        display: flex;
        align-items: center;
    }

    .paragrafo hr{
        flex: 1;
        margin: 0px 10px;
        border-top: 2px dashed #CCC;
        height: 1px;
        overflow: visible;
    }
</style>
