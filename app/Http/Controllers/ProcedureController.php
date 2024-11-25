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

    public function showInactives(Request $request)
    {
        if (!($laboratory = Laboratory::find($request->lab_id))) {
            return view('errors.404');
        }
    
        // Filtra os procedimentos com status 0 (inativos)
        $procedures = $laboratory->procedures()->where('status', 0)->get();
    
        return view('procedures.inactives', [
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
    
        // Redireciona de volta para a página anterior
        return redirect()->back()->with('success', 'Procedimento inativado com sucesso!');
    }    

    public function activate($id)
    {
        $procedure = Procedure::findOrFail($id);

        // Altera o status para ativo (1)
        $procedure->status = 1;
        $procedure->save();

        return redirect()->route('procedures.inactives', ['lab_id' => $procedure->laboratory_id])
            ->with('success', 'Procedimento ativado com sucesso!');
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
        $fields = [];
        $fieldCount = 0;
    
        if ($procedure->fields) {
            $decodedFields = json_decode($procedure->fields, true);
            if (isset($decodedFields['sessions'])) {
                foreach ($decodedFields['sessions'] as $session) {
                    $sessionData = [
                        'sessionName' => $session['sessionName'] ?? '',
                        'exams' => $session['exams'] ?? [],
                    ];
                    $fields[] = $sessionData;
                    $fieldCount += count($sessionData['exams']);
                }
            }
        }
    
        return view('procedures.edit', [
            'procedure' => $procedure,
            'fields' => $fields,
            'fieldCount' => $fieldCount,
        ]);
    }
    
    public function update(Request $request, Procedure $procedure)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mnemonic' => 'nullable|string|max:255',
            'sessions' => 'nullable|array',
            'sessions.*.sessionName' => 'nullable|string|max:255',
            'sessions.*.exams' => 'nullable|array',
            'sessions.*.exams.*.examName' => 'nullable|string|max:255',
            'sessions.*.exams.*.referenceValue' => 'nullable|string|max:255',
            'soro_lipemico' => 'nullable|boolean',
            'soro_icterico' => 'nullable|boolean',
            'soro_hemolisado' => 'nullable|boolean',
            'soro_outro' => 'nullable|string|max:50',
            'method' => 'required', 
            'material' => 'required', 
        ]);
        
        $fields = [
            'sessions' => $validated['sessions'] ?? [],
        ];
        
        $procedure->update([
            'name' => $validated['name'],
            'mnemonic' => $validated['mnemonic'],
            'fields' => json_encode($fields),
            'soro_lipemico' => $validated['soro_lipemico'] ?? 0,
            'soro_icterico' => $validated['soro_icterico'] ?? 0,
            'soro_hemolisado' => $validated['soro_hemolisado'] ?? 0,
            'soro_outro' => $validated['soro_outro'] ?? '',
            'method' => $validated['method'], 
            'material' => $validated['material'], 
        ]);
    
        return redirect()->route('procedures.index', ['lab_id' => $procedure->laboratory_id])
            ->with('success', 'Procedimento atualizado com sucesso!');

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
            'material' => ['required'],
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
