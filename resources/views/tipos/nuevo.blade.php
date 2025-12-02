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

        <h2 style="text-align:center; margin-bottom:20px;">Registrar Nuevo Tipo de Atracción</h2>

        <form id="frm_tipo" action="{{ route('tipos.store') }}" method="POST">

            @csrf

            <label><b>Nombre del tipo de atracción</b></label>
            <input class="form-control mb-3" type="text" name="Nombre" required>

            <div style="display:flex; justify-content:space-between; margin-top:10px;">
                <button class="btn btn-success" type="submit">Guardar</button>
                <a href="{{ route('tipos.index') }}" class="btn btn-danger">Cancelar</a>
            </div>
        </form>

    </div>

</div>
<script>
$(document).ready(function() {
    $("#frm_tipo").validate({
        rules: {
            Nombre: {
                required: true,
                minlength: 4,
                maxlength: 30,
                pattern: /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/
            }
        },
        messages: {
            Nombre: {
                required: "Este campo es obligatorio",
                minlength: "Debe tener mínimo 4 caracteres",
                maxlength: "No puede superar los 30 caracteres",
                pattern: "Solo se permiten letras y espacios"
            }
        }
    });
});
</script>

@endsection
