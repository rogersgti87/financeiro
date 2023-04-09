@extends('auth.base')

@section('content')
<div class="card">
    <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group row">
                <label for="email" class="col-md-12 col-form-label text-md-left">{{ __('E-Mail Admin') }}</label>

                <div class="col-md-12">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E-mail Admin">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Enviar E-mail de Recuperação') }}
                    </button>
                </div>
                <div class="col-md-12">
                    <a href="{{ url('/login') }}">Faça seu acesso, clicando aqui</a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection