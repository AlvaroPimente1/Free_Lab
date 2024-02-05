@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h2>{{ $title }}</h2></div>

                <div class="card-body">

                    @if ( isset($laboratory) )
                        <form method="POST" action="{{ route('laboratories.update', $laboratory->id) }}">
                            @method('PUT')
                    @else
                        <form method="POST" action="{{ route('laboratories.store') }}">
                    @endif

                    @csrf

                        <div class="form-group">
                            <label for="name">Nome do Laboratório</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Nome:" required autocomplete="name" value="{{ $laboratory->name ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label for="mnemonic">Sigla</label>
                            <input type="text" name="mnemonic" class="form-control" id="mnemonic" placeholder="Silga:" required value="{{ $laboratory->mnemonic ?? '' }}">
                        </div>

                    @can('support')

                        {{-- <div class="form-group row">
                            <label for="responsible_id" class="col-md-4 col-form-label text-md-right">{{ __('Administrador') }}</label> --}}

                        @if (empty($responsible) && !($responsibles->all() === []))

                            @if (isset($laboratory->administrator[0]) && isset($laboratory)) {{--Editar--}}

                                <p>Administradores</p>

                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Função</th>
                                        </tr>
                                    </thead>
                                    @foreach ($responsibles as $responsible)
                                        @can('administrators-lab', [$laboratory, $responsible])
                                            <tbody>
                                                <tr>
                                                    <th>{{ $responsible->name }}</th>
                                                    <th>
                                                        @switch($responsible->role)
                                                            @case('intern')
                                                                {{env('INTERN_NAME')}}
                                                                @break
                                                            @case('employee')
                                                                {{env('EMPLOYEE_NAME')}}
                                                                @break
                                                            @default
                                                        @endswitch
                                                    </th>
                                                </tr>
                                            </tbody>
                                        @endcan
                                    @endforeach
                                </table>

                                <a href="{{ route('laboratories.connect', $laboratory->id) }}" class="btn btn-primary">Editar administradores</a>

                            @else
                                {{--Cadastrar--}}
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="admin" id="Check">
                                        <label class="form-check-label" for="Check">Adicionar administradores a este laboratório</label>
                                        <p></p>
                                    </div>
                                </div>
                            @endif

                                {{-- <div class="col-md-6">
                                    <select class="form-control @error('responsible_id') is-invalid @enderror" id="responsible_id" name="responsible_id">
                                        <option value="">Sem administrador</option>
                                        @foreach ($responsibles as $responsible)
                                            <option value="{{ $responsible->id }}"
                                                @if (isset($laboratory->administrator[0]) && isset($laboratory))
                                                    @if ($laboratory->administrator[0]->id == $responsible->id)
                                                        selected
                                                    @endif
                                                @endif
                                                >{{ $responsible->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('responsible_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="admin" id="Check">
                                        <label class="form-check-label" for="Check">Adicionar administradores a este laboratório</label>
                                        <p></p>
                                    </div>
                                </div> --}}

                        @elseif( empty($responsibles) && isset($responsible->id) )

                            {{--Quando vem da criação de um administrador--}}

                            <div class="form-group row">
                                <label for="responsible_id" class="col-md-4 col-form-label text-md-right">{{ __('Administrador') }}</label>
                                <div class="col-md-6">
                                    <select class="form-control @error('responsible_id') is-invalid @enderror" id="responsible_id" name="responsible_id" required>
                                        <option value="{{ $responsible->id }}" selected>{{ $responsible->name }}</option>
                                    </select>
                                    @error('responsible_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                        @else

                            <div class="col-md-6">
                                <div class="alert alert-primary" role="alert">
                                    Não foi encontrado nenhum funcionário na base de dados.
                                    Vá para a tela principal para criar um novo usuário e depois
                                    torná-lo administrador deste aloratório.
                                </div>
                            </div>


                        @endif

                    @endcan

                        {{-- <a href="{{ route('home') }}" class="btn btn-primary">Voltar</a> --}}
                        <button class="btn btn-outline-primary" id="returnButton" type="button">Voltar</button>
                        <button type="submit" class="btn btn-primary">OK</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
