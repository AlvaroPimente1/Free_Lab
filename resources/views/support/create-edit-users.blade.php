@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h2>{{ $title }}</h2></div>

                <div class="card-body">
                    @if ( isset($user) )
                        <form method="POST" action="{{ route('users.update', $user->id) }}" autocomplete="off">
                            @method('PUT')
                    @else
                        <form method="POST" action="{{ route('users.store') }}" autocomplete="off">
                    @endif
                        
                    @csrf
                    
                        @if (empty($laboratories))

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name ?? old('name') }}">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cpf" class="col-md-4 col-form-label text-md-right">{{ __('CPF') }}</label>

                                <div class="col-md-6">
                                    <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" value="{{ $user->cpf ?? old('cpf') }}">

                                    @error('cpf')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- @can('supervisor')
                                <div class="form-group">
                                    <label for="laboratory_id">Laborátório associado</label>
                                    <input type="text" name="laboratory_id" class="form-control" id="laboratory_id" placeholder="Laboratório:"  disabled="disabled" value="{{ Auth::user()->responsible->name }}">
                                </div>
                            @endcan --}}
                            <div class="form-group row">
                                <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Cargo') }}</label>
                                <div class="col-md-6">
                                    <select class="form-control @error('role') is-invalid @enderror" id="role" name="role">
                                        <option value="">Escolha a função</option>
                                        
                                        @if (isset($user))
                                            @switch($user->role)
                                                @case('intern')
                                                    {{$role_user = env('INTERN_NAME')}}
                                                    @break
                                                @case('employee')
                                                    {{$role_user = env('EMPLOYEE_NAME')}}
                                                    @break
                                                @default
                                            @endswitch
                                        @endif

                                        @foreach ($roles as $role)
                                                <option value="{{ $role }}"
                                                    @if ( isset($user) && $role_user == $role )
                                                        selected
                                                    @endif
                                                    >{{ $role }}</option>
                                        @endforeach

                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @if (empty($user))
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Senha') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
        
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            @if (isset($user))
                                <p><b>Laboratórios administrados por este  usuário</b></p>
                                <tr>
                                <table class="table table-hover">
                                    @if (isset($user->administrator) && !($user->administrator->all() === []))
                                        <thead>
                                            <tr>
                                                @foreach ($user->administrator as $lab)
                                                    <th scope="col">Id: {{ $lab->id }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach ($user->administrator as $lab)
                                                    <th scope="row">Nome: {{ $lab->name }}</th>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    @else
                                        <div class="alert alert-primary" role="alert">
                                            Não é responsável por nenhum laboratório atualmente
                                        </div>
                                    @endif
                                </table>
                            @endif
                            
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="admin" id="Check">
                                <label class="form-check-label" for="Check">Tornar este usuário um administrador de um laboratório</label>
                                <p></p>
                            </div>
                            <a href="{{ route('home') }}" class="btn btn-primary">Voltar</a>

                        @elseif ( !($laboratories->all() === []) )
                            <div class="form-group row">
                                <label for="lab_id" class="col-md-4 col-form-label text-md-right">{{ __('Laboratório') }}</label>
                                <div class="col-md-6">
                                    <select class="form-control @error('lab_id') is-invalid @enderror" id="lab_id" name="lab_id" required>
                                        <option value="">Escolha o laboratório</option>

                                    @if (isset($laboratories_admin))
                                        @if (!($laboratories_admin->all() === []))
                                            @foreach ($laboratories_admin as $lab)
                                                <option value="{{ $lab->id }}" disabled>{{ $lab->name }} - Já é administrador</option>
                                            @endforeach
                                            @foreach ($laboratories as $laboratory)
                                                <option value="{{ $laboratory->id }}">{{ $laboratory->name }}</option>
                                            @endforeach
                                        @else
                                            @foreach ($laboratories as $laboratory)
                                                <option value="{{ $laboratory->id }}">{{ $laboratory->name }}</option>
                                            @endforeach
                                        @endif
                                    @else
                                        @foreach ($laboratories as $laboratory)
                                            <option value="{{ $laboratory->id }}">{{ $laboratory->name }}</option>
                                        @endforeach
                                    @endif                                          
                                        
                                    </select>
                                    @error('lab_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <label for="cancel" class="col-md-4 col-form-label text-md-right">Não tornar mais um administrador</label>
                            <a id="cancel" href="{{ route('home') }}" class="btn btn-primary">Cancelar</a>
                        @else
                            <div class="alert alert-primary" role="alert">
                                Não foi encontrado nenhum laboratório na base de dados, 
                                ou este usuário é administrador já de todos os laboratórios existentes
                            </div>
                            <a href="{{ route('laboratories.create', ['responsible_id' => $user->id]) }}" class="btn btn-primary">Criar um laboratório</a>
                        @endif

                        <button type="submit" class="btn btn-primary">OK</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection