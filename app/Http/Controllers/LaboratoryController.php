<?php

namespace App\Http\Controllers;

use App\Laboratory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaboratoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Laboratory $laboratory)
    {
        //Lista de todos laboratórios
        //Ativa e desativa o lab (Somente para usuário root)
        $this->authorize('support');

        $laboratories = $laboratory->all();

        return view('laboratory.show', compact('laboratories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('support');

        $title = 'Criando um Laboratório';

        //Se ao criar um laboratório existe um administrador pré-definido
        //Senão retorna todos os usuários para a seleção de administradores do lab
        if ($request->has('responsible_id')) {
            $responsible = User::find($request->responsible_id);
            return view('laboratory.create-edit', compact(['title', 'responsible']));
        } else {
            $responsibles = User::all()->except(1);
            return view('laboratory.create-edit', compact(['title', 'responsibles']));
        };
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('support');

        $laboratory = new Laboratory;

        $laboratory->name = $request->name;
        $laboratory->mnemonic = $request->mnemonic;
        $laboratory->enabled = 1; //Laboratório habilitado: 0; Laboratório desabilitado: 1
        $laboratory->save();

        //Se ao criar um laboratório foi pré definido um administrador
        if (isset($request->responsible_id)) {
            $laboratory->administrator()->attach($request->responsible_id); //Torna administrador
            $laboratory->employees()->attach($request->responsible_id);     //Torna funcionário do lab
        }

        //Se ao criar um laboratório não foi pré definido um administrador mas o usuário deseja definir um administrador
        if ($request->admin === 'on') {
            return redirect()->route('laboratories.connect', $laboratory->id); //Redireciona para a página de gerenciamento de usuários administradores
        }

        return redirect()->route('home');
    }

    public function connect($id, User $user, Request $request)
    {
        //Se não encontrar o laboratório no banco de dados retornar erro
        if(!$laboratory = Laboratory::find($id)){
            return view('errors.404');
        };
        //Se o usuário não tem permissão para esta operação retornar erro
        $this->authorize('edit-lab', [$laboratory]);
        //Código para realizar a operação de conexão no banco de dados
        if(isset($request->user_id)){
            foreach ($laboratory->employees as $employee) {
                if ($employee->id == $request->user_id) {
                    //$laboratory->employees()->detach($request->user_id);
                    $laboratory->administrator()->attach($request->user_id);
                    return redirect()->back();
                }
            }
            $laboratory->employees()->attach($request->user_id);
            $laboratory->administrator()->attach($request->user_id);
            return redirect()->back();
        }

        //Código para apenas retornar a página de gerenciamento de permissões de usuários do lab
        $users = $user->all()->except(1);

        return view('laboratory.connect', compact(['laboratory', 'users']));
    }

    public function disconnect($id, Request $request)
    {   
        if(!$laboratory = Laboratory::find($id)){
            return view('errors.404');
        };

        if(!$user = User::find($request->user_id)){
            return view('errors.404');
        };

        $this->authorize('edit-lab', [$laboratory]);

        /* $laboratory->employees()->detach($request->user_id); */
        //Retirar permissão de administrador
        $laboratory->administrator()->detach($user->id);
        
        if($user->id === Auth::user()->id){
            return redirect()->route('home');
        };

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('errors.404');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Página de edição dos dados do laboratório

        if(!$laboratory = Laboratory::find($id)){
            return view('errors.404');
        };

        $this->authorize('edit-lab', $laboratory);

        $title = "Editando $laboratory->name";
        
        // if ($laboratory->administrator->all() == []) {
        //     $responsibles = User::all()->except(1);
        //     return view('laboratory.create-edit', compact(['laboratory', 'responsibles', 'title']));
        // } else {
        //     $responsibles = $laboratory->administrator->all();
        //     $responsible = $responsibles[0];
        //     return view('laboratory.create-edit', compact(['laboratory', 'responsible', 'title']));
        // }

        $responsibles = User::all()->except(1);
        return view('laboratory.create-edit', compact(['laboratory', 'responsibles', 'title']));
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
        if(!$laboratory = Laboratory::find($id)){
            return view('errors.404');
        };

        $this->authorize('edit-lab', $laboratory);

        //Atualizando dados do laboratório
        $laboratory->name = $request->name;
        $laboratory->mnemonic = $request->mnemonic;

        //Se o usuário for root ele pode alterar os administradores do lab
        if (Auth::user()->role === 'root') {
            foreach ($laboratory->administrator as $adm) {
                if ($adm->id === $request->responsible_id) {
                    $laboratory->administrator()->detach($request->responsible_id); //Se der erro deixar só essa linha sem foreach e if
                    $laboratory->employees()->detach($request->responsible_id);
                }
            }
            $laboratory->administrator()->attach($request->responsible_id);
            $laboratory->employees()->attach($request->responsible_id);
        }
        
        $laboratory->save();

        //Se ao editar os dados de um laboratório o usuário deseja definir um administrador
        //Redireciona para a página de gerenciamento de permissões de usuário
        if ($request->admin === 'on') {
            return redirect()->route('laboratories.connect', $laboratory->id);
        }

        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Código para ativar ou desativar um laboratório

        if(!$laboratory = Laboratory::find($id)){
            return view('errors.404');
        };

        $this->authorize('support');

        if ($laboratory->enabled == 0) {
            $laboratory->enabled = 1;
        } else {
            $laboratory->enabled = 0;
        }
        
        $laboratory->save();
        /* $laboratory->delete(); */

        return redirect()->route('laboratories.index');
    }
}
