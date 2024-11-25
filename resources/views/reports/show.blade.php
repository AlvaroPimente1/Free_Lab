@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <!-- LAUDO COMEÇA AQUI -->
        <div class="col-md-12 card position-relative" style="min-height: 1400px; padding: 10px;">

            <!-- Aplicando imagem de fundo com opacidade via CSS -->
            <div class="background-image"></div>
            
            <!-- Conteúdo principal do laudo -->
            <div class="card-body position-relative" style="z-index: 1;">
                <!-- Informação do Laboratório e Requisição -->
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
                        </td>
                    </tr>
                </table>

                <!-- Título do Exame -->
                <h2 class="text-center font-weight-bold">{{ $report->procedure->name }}</h2>
                <h4><p class="text-center">Método: {{ $report->method }}</p></h4>
                <h4><p class="text-center">Material: {{ $report->material }}</p></h4>

                <h5 class="text-center">Médico Requisitante: <b>{{ $report->requester ?? 'Não Definido' }}</b></h5>

                <!-- Informação do Paciente e Exame -->
                <div class="outer-container" style="padding: 30px;">
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 50%;">
                                <h5>Nome do Paciente: <b>{{ $report->patient->name ?? 'Não Definido' }}</b></h5>
                            </td>
                            <td style="width: 50%; text-align: right;">
                                <h5>Data da Realização do Exame: <b>{{ $report->created_at->format('d/m/Y') }}</b></h5>
                            </td>
                        </tr>
                    </table>

                    <!-- Exames -->
                    @if ($report->body)
                    @php
                        $reportData = json_decode($report->body, true);
                    @endphp

                    @if (isset($reportData['sessions']))
                        @foreach ($reportData['sessions'] as $session)
                            @php
                                $emptyValueCount = 0; 
                                $examsPerSession = 0; 
                            @endphp

                            {{-- Primeiro, contar os exames na sessão --}}
                            @foreach ($session['exams'] as $exam)
                                @php
                                    $examsPerSession++;
                                    if (empty($exam['value']) || $exam['value'] == null) {
                                        $emptyValueCount++; 
                                    }
                                @endphp
                            @endforeach

                            <div class="session-container" style="margin-bottom: 40px;">
                                {{-- Exibe o título da sessão apenas se houver exames com valores válidos --}}
                                @if ($examsPerSession > $emptyValueCount)
                                    <h3>{{ $session['sessionName'] }}</h3>
                                    <hr>
                                @endif

                                <table style="width:90%; margin: 0 auto;">
                                    {{-- Exibe o cabeçalho da tabela se houver exames válidos --}}
                                    @if ($examsPerSession > $emptyValueCount)
                                        <thead>
                                            <tr>
                                                <th scope="col" width="300px" height="50px">Exame</th>
                                                <th scope="col">Resultado</th>
                                                <th scope="col" width="200px">Valor de referência</th>
                                            </tr>
                                        </thead>
                                    @endif
                                    <tbody>
                                        {{-- Exibe os exames válidos --}}
                                        @foreach ($session['exams'] as $exam)
                                            @if (!empty($exam['examName']) && ($exam['value'] != '' && $exam['value'] != null))
                                                <tr>
                                                    <td class="paragrafo"><h5>{{ $exam['examName'] }}</h5><hr></td>
                                                    <td><h5><b>{{ $exam['value'] ?? 'Valor Não Definido' }}</b></h5></td>
                                                    <td><h5>{{ $exam['referenceValue'] ?? 'Valor de Referência Não Definido' }}</h5></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> 
                        @endforeach
                    @endif
                @endif


                    <!-- Observações -->
                    @if ($report->soro_lipemico || $report->soro_hemolisado || $report->soro_icterico || $report->soro_outro)
                        <hr style="border-top: 2px dashed #CCC; border-bottom: 2px dashed #CCC; height: 2px;">    
                        <div class="mt-4">
                            <h4>Observação:</h4>
                            <ul style="list-style-type: none; padding-left: 0;">
                                @if ($report->soro_lipemico)
                                    <li><h5>Soro Lipêmico</h5></li>
                                @endif
                                @if ($report->soro_hemolisado)
                                    <li><h5>Soro Hemolisado</h5></li>
                                @endif
                                @if ($report->soro_icterico)
                                    <li><h5>Soro Ictérico</h5></li>
                                @endif
                                @if ($report->soro_outro)
                                    <li><h5>Soro {{ $report->soro_outro }}</h5></li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    <!-- Conclusão -->
                    @if ($report->conclusion)
                        <p></p>
                        <hr style="border-top: 2px dashed #CCC; border-bottom: 2px dashed #CCC; height: 2px; overflow: visible;">
                        <h4>Observação:</h4>
                        <h5>{!! $report->conclusion !!}</h5>
                    @endif                        
                </div>
            </div>
            
            <!-- Footer sempre fixo ao final da div -->
            <div class="card-footer text-center mt-auto">
                <!--<h5 class="col-md-12">Responsável Técnico {{ $report->signer->name }}, CRM </h5>-->
                <h5 class="col-md-12">{{ $report->laboratory->name }} - NMT - UFPA</h5>
                <h6 class="col-md-12">Belém - PA - @php
                    date_default_timezone_set('America/Sao_Paulo');
                    $date = date('d/m/Y');
                    echo $date;
                @endphp</h6>
            </div>
        </div>

        <!-- Botões para voltar e imprimir -->
        <div class="d-print-none" style="margin-top: 20px;">
            <div class="row justify-content-center">
                <div>
                    <button class="btn btn-outline-primary" id="returnButton" type="button">Voltar</button>
                    <button type="button" class="btn btn-primary" onclick="window.print()">Imprimir</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    /* Definindo o background como pseudo-elemento com opacidade e ajustando tamanho */
    .background-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('{{ url('css/logo_ufpa.png') }}');
        background-size: 50%; /* Ajuste o tamanho aqui, por exemplo, 50% */
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.1;
        z-index: 1;
    }

    /* Forçar impressão da imagem de fundo */
    @media print {
        .background-image::before {
            opacity: 0.1;
            background-size: 50%; /* Ajuste o tamanho também na impressão */
        }

        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }


    .session-container {
        margin-bottom: 40px;
    }
    table {
    width: 90%;
    margin: 0 auto;
    border-collapse: collapse;
}

td, th {
    vertical-align: top; /* Alinha o conteúdo ao topo */
    padding: 10px;
    text-align: left;
}

td h5 {
    margin: 0;
    padding: 0;
    white-space: normal; /* Permite a quebra de linha */
}

td {
    word-wrap: break-word; /* Garante a quebra de linha para textos longos */
}

.paragrafo {
    display: flex;
    align-items: center;
}

.paragrafo h5 {
    margin-bottom: 5px; /* Ajusta o espaçamento abaixo do texto */
}

.paragrafo hr {
    flex: 1;
    margin: 0px 10px;
    border-top: 2px dashed #CCC;
    height: 1px;
    overflow: visible;
}


</style>