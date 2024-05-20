<?php

namespace App\Http\Controllers;

use App\Patient;
use App\Laboratory;
use Illuminate\Http\Request;

class PatientController extends Controller
{

    public function index()
    {
        //Lista de todos os pacientes

        $patients = Patient::orderBy('name', 'asc')->get();//->paginate(10); //Returns patients in descending order, 10 per page

        return view('patients.index', ['patients' => $patients]);
    }

    public function show($id, $lab_id)
    {
        //Retorna a página com todas as informações de um determinado paciente e seus laudos naquele laboratório

        if(!$patient = Patient::find($id)){
            return view('errors.404');
        };

        if(!$laboratory = Laboratory::find($lab_id)){
            return view('errors.404');
        };

        $this->authorize('patient_lab', [$patient, $laboratory]);
        $reports = $patient->reports()->get();

        return view('patients.show', ['laboratory' => $laboratory, 'patient' => $patient, 'reports' => $reports]);
    }

    public function create($lab_id)
    {
        // Shows a view to create a new resource

        $title = "Cadastrar Paciente";

        return view('patients.create', compact(['title','lab_id']));
    }

    public function store(Request $request, $lab_id)
    {
        //$request->validate()
        // Persists a created resource

        $patient = new Patient($this->validatePatient());
        $patient -> save(); // Persists patient
        if(isset($request->lab_id)){
            foreach ($patient->laboratories as $laboratory) {
                if ($lab_id == $laboratory->id) {
                    $patient->laboratories()->detach($request->lab_id);
                }
            }
            $patient->laboratories()->attach($request->lab_id);
        }

        return redirect()->route('administrators.show',  $lab_id);
    }

    public function connect($id, Request $request)
    {
        //Cadastra novo paciente ao laboratório
        //Se o paciente ja existe no banco de dados do sistema, por está cadastrado em outro lab
        //O paciente apenas tem as suas informações compartilhadas com o novo laboratório
        //Se o paciente ainda não existe no banco, o sistema retorna o formulário de criação de paciente  
        if(!$laboratory = Laboratory::find($id)){
            return view('errors.404');
        };

        $this->authorize('administrator', [$laboratory]);

        $title = "Cadastrar novo paciente";
        $lab_id = $id;
        //Procura o paciente na base de dados
        $patient_id = Patient::where('cpf', $request->cpf)->pluck('id');
        //Se o paciente já existe no banco, o sistema apenas adiciona o paciente ao laboratório
        if(isset($patient_id[0])){
            foreach ($laboratory->patients as $patient) {
                if ($patient->id == $patient_id[0]) {
                    $laboratory->patients()->detach($patient_id[0]);
                }
            }
            $laboratory->patients()->attach($patient_id[0]);
            return redirect()->route('administrators.show', [$lab_id]);
        }
        //Caso o paciente ainda não esteja cadastrado no sistema, redireciona para a página de cadastro de paciente
        $cpf = $request->cpf;
        if(isset($request->cpf)){
            return view('patients.create', compact(['title','lab_id', 'cpf']));
        }

        return view('patients.connect', compact(['lab_id', 'title']));
    }

    public function edit($id, $lab_id)
    {
        // Change stored patient

        if(!$patient = Patient::find($id)){
            return view('errors.404');
        };

        if(!$laboratory = Laboratory::find($lab_id)){
            return view('errors.404');
        };

        $this->authorize('patient_lab', [$patient, $laboratory]);

        $ufs = ['AC','AL','AM','AP','BA','CE','DF','ES','GO','MA','MG','MS','MT','PA',
        'PB','PE','PI','PR','RJ','RN','RO','RR','RS','SC','SE','SP','TO'];

        return view('patients.edit', ['laboratory' => $laboratory, 'patient' => $patient, 'ufs' => $ufs]);
    }

    public function update($id, $lab_id)
    {
        if(!$patient = Patient::find($id)){
            return view('errors.404');
        };

        if(!$laboratory = Laboratory::find($lab_id)){
            return view('errors.404');
        };

        $this->authorize('patient_lab', [$patient, $laboratory]);

        $patient->update($this->validatePatient());

        return redirect()->route('administrators.show', $laboratory->id);
    }

    public function validatePatient()
    {
        return request()->validate([
            'name'          => 'required',
            'birthday'      => 'nullable|date',
            'email'         => 'nullable|email',
            'phone'         => 'nullable|digits_between:9,11', // 9 para números sem DDD, 11 para números com DDD
            'profession'    => 'nullable',
            'birthday'      => 'nullable|date',
            'height'        => 'nullable|integer',
            'weight'        => 'nullable|integer',
            'address'       => 'nullable',
            'cpf'           => 'nullable',
            'cep'           => 'nullable',
            'neighborhood'  => 'nullable',
            'state'         => 'nullable|max:2',
            'extras'        => 'nullable|max:255',
        ]);
    }    
}
