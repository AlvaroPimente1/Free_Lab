<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Laboratory;
use App\Report;
use App\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Laboratory $laboratory)
    {
        $laboratories = $laboratory->all();

        return view('employee.home', compact('laboratories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Report $report)
    {
        //Se não encontrar o laboratório no banco de dados retornar erro
        if(!$laboratory = Laboratory::find($id)){
            return view('errors.404');
        };
        //Obtém os dados do usuário logado
        $user = Auth::user();
        //Se o usuário logado é administrador deste laboratório redireciona para a página do administrador
        foreach ($user->administrator as $lab) {
            if ($lab->id === $laboratory->id) {
                return redirect()->route('administrators.show', $laboratory->id);
            }
        }
        //Se o usuário não tem permissão para esta operação retornar erro
        $this->authorize('laboratory-users', [$laboratory, $user]);

        $users = User::all();
        $reports = $laboratory->reports->all();
        $patients = $laboratory->patients->all();

        //Se o usuário é apenas funcionário do lab, mas não administrador, é redirecionado para a página do lab sem permissões de adm
        foreach ($user->labs as $lab) {
            if ($lab->id === $laboratory->id) {
                return view('employee.show', compact(['laboratory', 'users', 'reports', 'patients']));
            }
        }

        return view('medic.show', compact('laboratory', 'patients'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
