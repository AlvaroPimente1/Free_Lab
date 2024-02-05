<?php

namespace App\Http\Controllers;

use App\Laboratory;
use App\Patient;
use App\Report;
use App\Rules\Cpf;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SupportController extends Controller
{

    public function home(Laboratory $laboratory, Patient $patient)   
    {
        //Retorna a página principal de administração do sistema (Apenas para a usuário root)
        $this->authorize('support');

        $laboratories = $laboratory->all();
        $patients = $patient->all();
        $excepts = User::where('role', 'root')->pluck('id')->toArray();
        $users = User::all()->except($excepts);

        $responsibles = [];

        foreach ($users as $user) {
            if(isset($user->administrator[0])){
                array_push($responsibles , $user);
            }
        }

        return view('support.home', compact('laboratories', 'patients', 'responsibles', 'users'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Laboratory $laboratory)
    {
        return view('errors.404');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Cadastro de novos usuários
        $this->authorize('support');

        $roles = [env('EMPLOYEE_NAME'), env('INTERN_NAME')];

        $title = "Adicionar usuário";

        return view('support.create-users', compact(['title', 'roles']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Persiste os dados do novo usuário cadastrado
        $this->authorize('support');

        //Validação dos dados
        $rules = [
            'name'      => 'required|string|min:3|max:50',
            'cpf'       => ['required', 'unique:users', new Cpf],
            'role'      => ['required', 'in:'.env('INTERN_NAME').','.env('EMPLOYEE_NAME')],
            'password'  => 'required|min:8',
        ];

        $request->validate($rules);

        $user = new User;

        $user->name = $request->name;
        $user->cpf = $request->cpf;
        switch ($request->role) {
            case env('INTERN_NAME'):
                $user->role = 'intern';
                break;
            
            case env('EMPLOYEE_NAME'):
                $user->role = 'employee';
                break;
        }
        $user->password = Hash::make($request->password); //Criptografia da senha

        $user->save();
        
        //Se o root deseja adicionar o novo usuário cadastrado como administrador em um laboratório
        //Redireciona para a página de gerenciamento de permissão de usuários
        if ($request->admin == 'on') {
            return redirect()->route('support.connect', $user->id);
        }

        return redirect()->route('home');
    }

    public function connect($id)
    {
        //Retorna a página de gerenciamento de permissão de usuários
        if(!$user = User::find($id)){
            return view('errors.404');
        };

        $title = "Tornar $user->name administrador(a)";
        $laboratories = Laboratory::all();
        return view('support.connect', compact(['user', 'laboratories', 'title']));
    }

    public function connecting($id, Request $request)
    {
        //Altera a permissão de usuários do laboratório
        $rules = [
            'lab_id'      => 'required'
        ];

        $request->validate($rules);

        if(!$user = User::find($id)){
            return view('errors.404');
        };

        if(!$laboratory = Laboratory::find($request->lab_id)){
            return view('errors.404');
        };

        $this->authorize('support');

        foreach ($laboratory->administrator as $administrator) {
            if ($administrator->id == $user->id) {
                return redirect()->route('support.home');
            }
        }
        $user->administrator()->attach($request->lab_id);
        $user->labs()->attach($request->lab_id);

        return redirect()->route('support.home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Shows a single resource

        if(!$patient = Patient::find($id)){
            return view('errors.404');
        };

        $this->authorize('support');
        $reports = $patient->reports()->get();

        return view('support.show', ['patient' => $patient, 'reports' => $reports]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Exibe a página de edição dos dados de um usuário
        if(!$user = User::find($id)){
            return view('errors.404');
        };

        $this->authorize('support');

        $roles = [env('EMPLOYEE_NAME'), env('INTERN_NAME')];
        $title = "Editando $user->name";

        return view('support.edit-users', compact(['user', 'roles', 'title']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Persiste os novos dados de um usuário
        if(!$user = User::find($id)){
            return view('errors.404');
        };

        $this->authorize('support');

        $rules = [
            'name'      => 'required|string|min:3|max:50',
            'cpf'       => ['required', new Cpf],
            'role'      => 'required', 'in:'.env('INTERN_NAME').','.env('EMPLOYEE_NAME'),
        ];

        $request->validate($rules);

        if (isset($request->name)) {
            $user->name = $request->name;
            $user->cpf = $request->cpf;
            if (isset($request->password)) {
                $user->password = Hash::make($request->password);
            }
            switch ($request->role) {
                case env('INTERN_NAME'):
                    $user->role = 'intern';
                    break;
                
                case env('EMPLOYEE_NAME'):
                    $user->role = 'employee';
                    break;
            }
        }

        if (isset($request->lab_id)) {
            $user->administrator()->attach($request->lab_id);
            $user->labs()->attach($request->lab_id);
        }

        $user->save();

        if ($request->admin == 'on') {
            $title = "Adicionar administrador ao laboratório";
            $laboratories_admin = $user->administrator;
            $excepts = $user->administrator->pluck('id')->toArray();
            $laboratories = Laboratory::all()->except($excepts);
            
            return view('support.connect', compact(['user', 'laboratories', 'title', 'laboratories_admin']));
            //return redirect()->route('users.edit', compact(['title', 'user', 'laboratories']));
        }

        return redirect()->route('support.home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!$user = User::find($id)){
            return view('errors.404');
        };

        $this->authorize('support');
        
        $user->delete();

        return redirect()->back();
    }

    public function statistics()
    {
        //Exibe a página de estatisticas do sistema
        $this->authorize('support');

        $title = "Estatísticas";
        $reports = Report::all();

        return view('support.statistics', compact(['title', 'reports']));
    }
}