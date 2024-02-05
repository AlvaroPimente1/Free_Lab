<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //Se o usuário logado for o root, redireciona para a página de support
        //Senão redireciona para a escolha de laboratórios
        $user = auth()->user();
        $role = $user->role;
        
        switch ($role) {
            case "root":
                return redirect()->route('support.home');
            case ("employee" || "intern"):
                return redirect()->route('employees.index');
        }
    }
}
