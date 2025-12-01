@extends('layouts.travelix')

@section('content')

<div style="display:flex; justify-content:center; align-items:center; height:70vh;">
    <div style="width:360px; background:white; padding:26px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.08);">

        <h3 style="text-align:center; margin-bottom:18px;">Iniciar sesión</h3>

        @if($errors->any())
            <div style="background:#ffdddd; padding:10px; margin-bottom:12px; border-radius:5px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <div class="form-group mb-2">
                <label for="email">Correo</label>
                <input id="email" name="email" type="email" class="form-control" required value="{{ old('email') }}">
            </div>

            <div class="form-group mb-3">
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" class="form-control" required>
            </div>

            <div style="margin-bottom:10px;">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember"> Recordarme</label>
            </div>

            <button class="btn btn-primary w-100">Ingresar</button>
        </form>

    </div>
</div>

@endsection
