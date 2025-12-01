@extends('layouts.travelix')

@section('content')

<div style="display:flex; justify-content:center; align-items:center; height:70vh;">

    <div style="
        width:360px;
        padding:26px;
        border-radius:12px;
        box-shadow:0 6px 18px rgba(0, 0, 0, 0.06);
        background: rgba(141, 79, 255, 0.16);
        backdrop-filter: blur(3px);
        color:white;
    ">

        <h3 style="text-align:center; margin-bottom:18px;">Iniciar sesión</h3>

        @if($errors->any())
            <div style="background:rgba(255,90,90,0.6); padding:10px; margin-bottom:12px; border-radius:5px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <div class="form-group mb-2">
                <label for="email" style="display:block; text-align:left; width:100%; margin-bottom:5px;">Correo</label>
                <input id="email" name="email" type="email" class="form-control" required value="{{ old('email') }}">
            </div>

            <div class="form-group mb-3">
                <label for="password" style="display:block; text-align:left; width:100%; margin-bottom:5px;">Contraseña</label>
                <input id="password" name="password" type="password" class="form-control" required>
            </div>

            <div style="margin-bottom:10px; text-align:left;">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" style="margin-left:5px;">Recordarme</label>
            </div>

            <button class="btn btn-primary w-100">Ingresar</button>
        </form>

    </div>

</div>

@endsection