@extends('layouts.travelix')

@section('content')

<div style="display:flex; justify-content:center; align-items:center; height:70vh;">

    <div style="
        width:420px;
        padding:26px;
        border-radius:12px;
        box-shadow:0 6px 18px rgba(0, 0, 0, 0.06);
        background: rgba(141, 79, 255, 0.16);
        backdrop-filter: blur(3px);
        color:white;
    ">

        <h2 style="text-align:center; margin-bottom:20px;">Editar Provincia</h2>

        <form action="{{ route('provincias.update', $provincia) }}" method="POST">

            @csrf
            @method('PUT')

            <label><b>Nombre de la provincia</b></label>
            <input class="form-control mb-3" type="text" name="Nombre" value="{{ old('Nombre', $provincia->Nombre) }}" required>

            <div style="display:flex; justify-content:space-between; margin-top:10px;">
                <button class="btn btn-success" type="submit">Actualizar</button>
                <a href="{{ route('provincias.index') }}" class="btn btn-danger">Cancelar</a>
            </div>
        </form>

    </div>

</div>

@endsection
