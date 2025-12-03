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

        <h2 style="text-align:center; margin-bottom:20px;">Registrar Nueva Provincia</h2>

        <form id="frm_provincia" action="{{ route('provincias.store') }}" method="POST">
            @csrf

            <label><b>Nombre de la provincia</b></label>
            <input class="form-control mb-3" type="text" name="Nombre" id="Nombre" value="{{ old('Nombre') }}">
            <div class="text-danger" id="error-Nombre"></div>
            
            <!-- Campo oculto con provincias existentes en JSON -->
            <input type="hidden" id="provincias_existentes" value="{{ $provinciasExistentes->pluck('Nombre')->toJson() }}">

            <div style="display:flex; justify-content:space-between; margin-top:10px;">
                <button class="btn btn-success" type="submit">Guardar</button>
                <a href="{{ route('provincias.index') }}" class="btn btn-danger">Cancelar</a>
            </div>
        </form>

    </div>

</div>

<!-- Cargar jQuery y jQuery Validation -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/additional-methods.min.js"></script>

<script>
$(document).ready(function() {
    // Obtener provincias existentes del campo oculto
    const provinciasExistentes = JSON.parse($('#provincias_existentes').val() || '[]');
    
    // Método para validar que no sea duplicado
    $.validator.addMethod("noDuplicado", function(value, element) {
        if (!value || value.trim().length < 4) return true;
        
        const valorNormalizado = value.trim().toLowerCase();
        
        // Verificar si existe en la lista de provincias
        for (const nombreProvincia of provinciasExistentes) {
            if (nombreProvincia.trim().toLowerCase() === valorNormalizado) {
                return false; // Es duplicado
            }
        }
        return true; // No es duplicado
    }, "Esta provincia ya existe.");

    // Método para validar solo letras y espacios
    $.validator.addMethod("soloLetrasEspacios", function(value, element) {
        return this.optional(element) || /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(value);
    }, "Solo se permiten letras y espacios.");

    // Configuración de validación
    $("#frm_provincia").validate({
        rules: {
            Nombre: {
                required: true,
                minlength: 4,
                maxlength: 25,
                soloLetrasEspacios: true,
                noDuplicado: true
            }
        },
        messages: {
            Nombre: {
                required: "Este campo es obligatorio",
                minlength: "Debe tener mínimo 4 caracteres",
                maxlength: "No puede superar los 25 caracteres",
                soloLetrasEspacios: "Solo se permiten letras y espacios"
            }
        },
        errorPlacement: function(error, element) {
            const errorElement = document.getElementById('error-' + element.attr('name'));
            if (errorElement) {
                errorElement.innerHTML = error.text();
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    });
});
</script>

@endsection