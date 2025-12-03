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

        <h2 style="text-align:center; margin-bottom:20px;">Editar Tipo de Atracción</h2>

        <form id="frm_tipo_edit" action="{{ route('tipos.update', $tipo->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label><b>Nombre del tipo de atracción</b></label>
            <input class="form-control mb-3" type="text" name="Nombre" id="Nombre" value="{{ old('Nombre', $tipo->Nombre) }}">
            <div class="text-danger" id="error-Nombre"></div>
            
            <!-- Campo oculto con tipos existentes en JSON (excluyendo el actual) -->
            <input type="hidden" id="tipos_existentes" value="{{ $tiposExistentes->pluck('Nombre')->toJson() }}">

            <div style="display:flex; justify-content:space-between; margin-top:10px;">
                <button class="btn btn-success" type="submit">Actualizar</button>
                <a href="{{ route('tipos.index') }}" class="btn btn-danger">Cancelar</a>
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
    // Obtener tipos existentes del campo oculto
    const tiposExistentes = JSON.parse($('#tipos_existentes').val() || '[]');
    
    // Método para validar que no sea duplicado
    $.validator.addMethod("noDuplicado", function(value, element) {
        if (!value || value.trim().length < 4) return true;
        
        const valorNormalizado = value.trim().toLowerCase();
        
        // Verificar si existe en la lista de tipos
        for (const nombreTipo of tiposExistentes) {
            if (nombreTipo.trim().toLowerCase() === valorNormalizado) {
                return false; // Es duplicado
            }
        }
        return true; // No es duplicado
    }, "Este tipo de atracción ya existe.");

    // Método para validar solo letras y espacios
    $.validator.addMethod("soloLetrasEspacios", function(value, element) {
        return this.optional(element) || /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(value);
    }, "Solo se permiten letras y espacios.");

    // Configuración de validación
    $("#frm_tipo_edit").validate({
        rules: {
            Nombre: {
                required: true,
                minlength: 4,
                maxlength: 30,
                soloLetrasEspacios: true,
                noDuplicado: true
            }
        },
        messages: {
            Nombre: {
                required: "Este campo es obligatorio",
                minlength: "Debe tener mínimo 4 caracteres",
                maxlength: "No puede superar los 30 caracteres",
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