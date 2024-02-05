<?php

namespace App\Http\Controllers;

use App\Laboratory;
use App\Patient;
use App\Report;
use App\User;
use App\Rules\Cpf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdministratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //Se não encontrar o laboratório no banco de dados retornar erro
        if (!($laboratory = Laboratory::find($request->lab_id))) {
            return view('errors.404');
        }

        //Se o usuário não tem permissão para esta operação retornar erro
        $this->authorize('edit-lab', [$laboratory]);

        $roles = [env('EMPLOYEE_NAME'), env('INTERN_NAME')];

        $title = "Adicionar usuário";

        $lab_id = $request->lab_id;

        return view('administrator.create-users', compact(['title', 'roles', 'lab_id']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Se não encontrar o laboratório no banco de dados retornar erro
        if (!($laboratory = Laboratory::find($request->lab_id))) {
            return view('errors.404');
        }

        //Se o usuário não tem permissão para esta operação retornar erro
        $this->authorize('edit-lab', [$laboratory]);

        //Validação dos dados que o usuário informou
        $rules = [
            'name'      => 'required|string|min:3|max:50',
            'cpf'       => ['required', 'unique:users', new Cpf],
            'role'      => ['required', 'in:'.env('INTERN_NAME').','.env('EMPLOYEE_NAME')],
            'password'  => 'required|min:8',
        ];
        $request->validate($rules);

        //Criando um novo usuário
        $user = new User;
        $user->name = $request->name;
        $user->cpf = $request->cpf;
        //Guarda informação de role como está no arquivo .env 
        switch ($request->role) {
            case env('INTERN_NAME'):
                $user->role = 'intern';
                break;

            case env('EMPLOYEE_NAME'):
                $user->role = 'employee';
                break;
        }
        $user->password = Hash::make($request->password);
        $user->save();

        //Se o usuário escolher a opação de tornar administrador deste labolatório
        if ($request->admin == 'on') {
            $user->administrator()->attach($request->lab_id);
        }
        //Adicionar usuário ao laboratório
        $user->labs()->attach($request->lab_id);
        //Usuário logado
        $auth_user = Auth::user();
        //Se o usuário logado é o root, redireciona novamente para a página de gerenciamento de permissão de usuários
        if ($auth_user->role == 'root') {
            return redirect()->route('laboratories.connect', [$request->lab_id]);
        }
        //Se o usuário logado não for o root redireciona para a página principal de administrador do lab
        return redirect()->route('administrators.show', [$request->lab_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, User $user, Report $report, Patient $patient)
    {
        //Se não encontrar o laboratório no banco de dados retornar erro
        if(!$laboratory = Laboratory::find($id)){
            return view('errors.404');
        };
        //Se o usuário não tem permissão para esta operação retornar erro
        $this->authorize('administrator', [$laboratory]);
        //Obtém os dados de todos os usuários
        $users = $user->all();
        //Obtém os dados de todos os laudos
        $reports = $laboratory->reports->all();
        //Obtém os dados de todos os pacientes
        $patients = $laboratory->patients->all();
        //Retorna a página principal de administrador do lab juntamente com os dados obtidos
        return view('administrator.show', compact(['laboratory', 'users', 'reports', 'patients']));
    }

    public function connect($id, User $user, Request $request)
    {
        //Se não encontrar o laboratório no banco de dados retornar erro
        if(!$laboratory = Laboratory::find($id)){
            return view('errors.404');
        };
        //Se o usuário não tem permissão para esta operação retornar erro
        $this->authorize('administrator', [$laboratory]);
        //Se existe um usuário informado na requisição, ele será adicionado ao laboratório 
        if(isset($request->user_id)){
            foreach ($laboratory->employees as $employee) {
                //Se o usuário por um acaso já esteja associado aquele laboratório, desfaz a associação
                if ($employee->id == $request->user_id) {
                    $laboratory->employees()->detach($request->user_id);
                    return redirect()->back();
                }
            }
            $laboratory->employees()->attach($request->user_id);
            return redirect()->back();
        }
        //Obtém os dados de todos os usuários do sistema, exceto o usuário root
        $excepts = $laboratory->employees->pluck('id')->toArray();
        array_push($excepts , 1);
        $users = $user->all()->except($excepts);
        //Retorna a página de gerenciamento de permissão de usuários
        return view('administrator.connect', compact(['laboratory', 'users']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('errors.404');
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
        return view('errors.404');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        //Se não encontrar o usuário no banco de dados retornar erro
        if(!$user = User::find($id)){
            return view('errors.404');
        };
        //Se não encontrar o laboratório no banco de dados retornar erro
        if(!$laboratory = Laboratory::find($request->lab_id)){
            return view('errors.404');
        };
        //Se o usuário não tem permissão para esta operação retornar erro
        $this->authorize('administrator', [$laboratory]);
        //Retira o acesso do usuário ao laboratório
        $laboratory->employees()->detach($user->id);
        //Caso o usuário seja um dos administradores do laboratório, também retira essa permissão
        foreach ($user->administrator as $lab) {
            if ($lab->id == $laboratory->id) {
                $laboratory->administrator()->detach($user->id);
                //Se o usuário logado foi o mesmo que perdeu acesso ao laboratório, redireciona para a página de seleção de laboratórios
                if(Auth::user()->id == $user->id){
                    return redirect()->route('home');
                }
            }
        }
        //Se o usuário logado foi o mesmo que perdeu acesso ao laboratório, redireciona para a página de seleção de laboratórios
        if(Auth::user()->id == $user->id){
            return redirect()->route('home');
        }
        //Se o usuário logado não é o mesmo que perdeu acesso ao laboratório, redireciona novamente para a página de gerenciamento de permissões de usuários
        return redirect()->back();
    }

    public function manualdownload()
    {
        //Se o arquivo de manual existe, redireciona para uma nova aba e baixa o arquivo
        if(Storage::disk('public')->exists("manual.pdf")){
            $path = Storage::disk('public')->path("manual.pdf");
            $content = file_get_contents($path);
            return response($content)->withHeaders([
                'Content-Type' => mime_content_type($path)
            ]);
            return redirect('/404');
        }
    }
}
