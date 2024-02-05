<?php

namespace App\Http\Controllers;

use App\Laboratory;
use Illuminate\Http\Request;
use App\Procedure;
use App\Report;
use App\User;
use App\Patient;

class ReportController extends Controller
{
    public function index()
    {
        // Renders list

        $reports = Report::orderBy('id', 'desc')->paginate(10); //Returns reports in descending order, 10 per page

        return view('reports.index', ['reports' => $reports]);
    }

    public function show(Report $report)
    {
        // Shows a single resource

        return view('reports.show', [
            'report'    => $report,
            'body'    => $report->body
        ]);
    }

    public function create(Request $request, Procedure $procedure)
    {

        if (!($laboratory = Laboratory::find($request->lab_id))) {
            return view('errors.404');
        }
        // Shows a view to create a new resource
        $user = auth()->user()->id;
        $user = User::find($user);

        if (!$procedure->textmodel) {
            $fields = explode('!@#',$procedure->fields);
        } else {
            $fields = $procedure->fields;
        }


        if(isset($request->patient)){
            $patient = Patient::find($request->patient);
            return view('reports.create', [
                'fields' => $fields,
                //'labs' => $user->labs,
                'procedure' => $procedure,
                'patient' => $patient,
                'lab_id' => $request->lab_id
            ]);
        }

        return view('reports.index', [
            'patients' => $laboratory->patients->all(),
            'procedure' => $procedure,
            'lab_id' => $request->lab_id
        ]);
    }

    public function store(Request $request)
    {
        // Persists a created resource
        $creator = auth()->user()->id;
        if (!($laboratory = Laboratory::find($request->lab_id))) {
            return view('errors.404');
        }

        $report = new Report($this->validateReport());
        // LINHAS IMPORTANTES ABAIXO
        $report -> laboratory_id = $request->lab_id;
        $report -> patient_id = request('patient'); //Temporário, trocar ao completar sistema de pacientes
        $report -> signer_id = $creator; //Temporário, trocar ao completar sistema de verificação
        $report -> creator_id = $creator;
        $report -> procedure_id = request('procedure'); //Preenche o ID do procedimento automaticamente

        $report -> save(); // Persists resource

        return redirect()->route('administrators.show', $request->lab_id);
    }

    public function showProcedures(Request $request)
    {
        if (!($laboratory = Laboratory::find($request->lab_id))) {
            return view('errors.404');
        }

        if (!($patient = Patient::find($request->patient_id))) {
            return view('errors.404');
        }

        return view('reports.procedureChoice', [
            'patient_id' => $patient->id,
            'procedures' => $laboratory->procedures,
            'lab_id' => $laboratory->id,
            'laboratory' => $laboratory
        ]);
    }
    // public function destroy()
    // {
    // Não existe na versão final
    // }

    public function validateReport()
    {
        return request()->validate([
            // 'mnemonic'      => ['required', 'max:3'], <- Procedimento é preenchido automaticamente
            'body'          => ['present', 'required_without:conclusion'],
            'method'        => 'required',
            'conclusion'    => ['present', 'required_without:body'],
            'requester'     => 'required',
        ]);
    }
}
