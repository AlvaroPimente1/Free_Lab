@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h2>{{ $title }}</h2></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('support.connecting', $user->id) }}" autocomplete="off">

                        @csrf

                        @if ( !($laboratories->all() === []) )

                            <div class="form-group row">
                                <label for="lab_id" class="col-md-4 col-form-label text-md-right">{{ __('Laboratório') }}</label>
                                <div class="col-md-6">
                                    <select class="form-control @error('lab_id') is-invalid @enderror" id="lab_id" name="lab_id">
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

                            <a id="cancel" href="{{ route('home') }}" class="btn btn-outline-primary">Cancelar</a>
                            <a href="{{ route('laboratories.create', ['responsible_id' => $user->id]) }}" class="btn btn-outline-primary">Criar um laboratório</a>
                            <button type="submit" class="btn btn-primary">OK</button>

                        @else

                            <div class="alert alert-primary" role="alert">
                                O usuário já é administrador de todos os laboratórios
                                OU
                                Não existe nenhum laboratório na base de dados.
                            </div>
                            <a href="{{ route('laboratories.create', ['responsible_id' => $user->id]) }}" class="btn btn-primary">Criar um laboratório</a>
                        
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection