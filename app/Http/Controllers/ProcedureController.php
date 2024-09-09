<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Procedure;
use App\User;
use App\Laboratory;
use Illuminate\Http\Request;

class ProcedureController extends Controller
{
    use SoftDeletes;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!($laboratory = Laboratory::find($request->lab_id))) {
            return view('errors.404');
        }

        // Filtra os procedimentos com status 1 (ativos)
        $procedures = $laboratory->procedures()->where('status', 1)->get();

        return view('procedures.index', [
            'procedures' => $procedures,
            'lab_id' => $laboratory->id,
            'laboratory' => $laboratory
        ]);
    }

    public function inactivate($id)
    {
        $procedure = Procedure::findOrFail($id);

        // Altera o status para inativo
        $procedure->status = 0;
        $procedure->save();

        // Retorna uma resposta simples, sem redirecionar
        return response()->json(['success' => 'Procedimento inativado com sucesso!']);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Shows a view to create a new resource
        $user = auth()->user()->id;
        $user = User::find($user);

        return view('procedures.create', [
            'lab_id' => $request->lab_id,
            'text' => $request->text
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $procedure = new Procedure($this->validateProcedure());
        $laboratory = Laboratory::findOrFail($request->lab_id);
        $procedure->mnemonic = strtoupper($procedure->mnemonic);
        $procedure->laboratory_id = $laboratory->id;

        // Salva o JSON diretamente no campo fields
        $procedure->fields = $request->input('fields');

        $procedure->save();

        return view('procedures.index', [
            'procedures' => $laboratory->procedures,
            'lab_id' => $laboratory->id,
            'laboratory' => $laboratory
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
    public function show(Procedure $procedure)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
    public function edit(Procedure $procedure, Request $request)
    {
        $user = auth()->user()->id;
        $user = User::find($user);
    
        // Decodificar o JSON armazenado em fields
        if ($procedure->fields) {
            $decodedFields = json_decode($procedure->fields, true); // Decodifica o JSON em um array associativo
    
            // Inicializa as variáveis
            $fields = [];
            $fieldCount = 0;
    
            // Verifica se existem sessões e exames
            if (isset($decodedFields['sessions']) && is_array($decodedFields['sessions'])) {
                foreach ($decodedFields['sessions'] as $session) {
                    $sessionData = [
                        'sessionName' => $session['sessionName'] ?? 'Sessão Sem Nome',
                        'exams' => []
                    ];
    
                    if (isset($session['exams']) && is_array($session['exams'])) {
                        foreach ($session['exams'] as $exam) {
                            $sessionData['exams'][] = [
                                'examName' => $exam['examName'] ?? 'Nome do Exame Não Definido',
                                'referenceValue' => $exam['referenceValue'] ?? 'Valor de Referência Não Definido'
                            ];
                            $fieldCount++;
                        }
                    }
                    $fields[] = $sessionData;
                }
            }
        } else {
            $fields = [];
            $fieldCount = 0;
        }
    
        return view('procedures.edit', [
            'procedure' => $procedure,
            'fields' => $fields, // Array de campos com nomes de exames e valores de referência organizados por sessão
            'fieldCount' => $fieldCount,
            'lab_id' => $request->lab_id,
        ]);
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Procedure $procedure)
    {
        $procedure->update($this->validateProcedure());
        $laboratory = Laboratory::findOrFail($request->lab_id);

        // Atualiza o JSON no campo fields
        $procedure->fields = $request->input('fields');

        $procedure->save();

        return view('procedures.index', [
            'procedures' => $laboratory->procedures,
            'lab_id' => $laboratory->id,
            'laboratory' => $laboratory
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
    public function destroy(Procedure $procedure, Request $request)
    {
        $procedure->delete(); // Deleta o procedimento em questão
        $laboratory = Laboratory::findOrFail($request->lab_id);

        return view('reports.procedureChoice', [
            'procedures' => $laboratory->procedures,
            'lab_id' => $laboratory->id,
            'laboratory' => $laboratory
        ]);
    }

    /**
     * Validate the procedure inputs.
     *
     * @return array
     */
    public function validateProcedure()
    {
        return request()->validate([
            'name' => ['required', 'max:100'],
            'mnemonic' => ['required', 'max:5'],
            'method' => ['required'],
            'fields' => ['present', 'required_without:conclusion'],
            'textmodel' => ['required'],
            'conclusion' => ['present', 'required_without:fields'],
            'soro_lipemico' => ['boolean'],
            'soro_hemolisado' => ['boolean'],
            'soro_icterico' => ['boolean'],
            'soro_outro' => ['nullable', 'string', 'max:50'],
        ]);
    }
}
