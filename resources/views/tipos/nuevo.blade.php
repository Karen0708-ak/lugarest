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
            <input class="form-control mb-3" type="text" name="Nombre" id="Nombre" value="{{ old('Nombre') }}">
            <div class="text-danger" id="error-Nombre"></div>
            
            <!-- Mensaje de error de duplicado del servidor -->
            @if($errors->has('Nombre'))
                <div class="text-danger">{{ $errors->first('Nombre') }}</div>
            @endif

            <div style="display:flex; justify-content:space-between; margin-top:10px;">
                <button class="btn btn-success" type="submit" id="btn-submit">Guardar</button>
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
    // Variable para almacenar si el nombre es único
    let nombreEsUnico = false;
    
    // Método para validar solo letras y espacios
    $.validator.addMethod("soloLetrasEspacios", function(value, element) {
        return this.optional(element) || /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(value);
    }, "Solo se permiten letras y espacios.");
    
    // Método para validar duplicados (usando AJAX)
    $.validator.addMethod("verificarDuplicado", function(value, element) {
        if (!value || value.length < 4) return true;
        
        // Hacer petición AJAX para verificar duplicados
        const resultado = verificarNombreTipo(value);
        return resultado;
    }, "Este tipo de atracción ya existe.");

    // Configuración de validación
    $("#frm_tipo").validate({
        rules: {
            Nombre: {
                required: true,
                minlength: 4,
                maxlength: 30,
                soloLetrasEspacios: true,
                verificarDuplicado: true
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
            // Colocar el mensaje de error en el div correspondiente
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
        },
        submitHandler: function(form) {
            // Solo se ejecuta si la validación pasa
            console.log("Formulario válido, enviando...");
            form.submit();
        }
    });

    // Función para verificar duplicados via AJAX
    function verificarNombreTipo(nombre) {
        let esValido = true;
        
        // Hacer la petición sincrónica (ajax async: false está deprecado, usamos promesas)
        $.ajax({
            url: '{{ route("tipos.verificar") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nombre: nombre
            },
            async: false, // Sincrónico para validación
            success: function(response) {
                esValido = response.disponible;
            },
            error: function() {
                esValido = true; // En caso de error, permitir enviar
            }
        });
        
        return esValido;
    }

    // Validación en tiempo real con debounce
    let timeoutId;
    $('#Nombre').on('input', function() {
        clearTimeout(timeoutId);
        const valor = $(this).val();
        
        if (valor.length >= 4) {
            timeoutId = setTimeout(() => {
                $(this).valid();
            }, 500);
        }
    });

    console.log("Validación configurada correctamente");
});
</script>

@endsection