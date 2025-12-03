@extends('layouts.travelix')

@section('content')
<div class="container my-5" style="background: rgba(141, 79, 255, 0.16); padding:30px; border-radius:12px; backdrop-filter: blur(3px);">
    <h1 class="text-center mb-4" style="color:white;">Editar Lugar Turístico</h1>

    <form id="frm_lugar_edit" action="{{ route('lugarest.update', $lugar->IdLugar) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label text-white">Nombre del Lugar</label>
                <input type="text" class="form-control" id="NombreLugar" name="NombreLugar"
                       value="{{ old('NombreLugar', $lugar->NombreLugar) }}">
                <div class="text-danger" id="error-NombreLugar"></div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-white">Provincia</label>
                <select class="form-control" id="IdProvi" name="IdProvi">
                    <option value="">Seleccione una provincia</option>
                    @foreach($provincias as $provincia)
                        <option value="{{ $provincia->Id }}"
                            {{ $lugar->IdProvi == $provincia->Id ? 'selected' : '' }}>
                            {{ $provincia->Nombre }}
                        </option>
                    @endforeach
                </select>
                <div class="text-danger" id="error-IdProvi"></div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-white">Tipo de Atracción</label>
                <select class="form-control" id="IdTipoA" name="IdTipoA">
                    <option value="">Seleccione un tipo</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->Id }}"
                            {{ $lugar->IdTipoA == $tipo->Id ? 'selected' : '' }}>
                            {{ $tipo->Nombre }}
                        </option>
                    @endforeach
                </select>
                <div class="text-danger" id="error-IdTipoA"></div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-white">Año de Creación</label>
                <input type="number" class="form-control" id="AnioCreacion" name="AnioCreacion"
                       value="{{ old('AnioCreacion', $lugar->AnioCreacion) }}">
                <div class="text-danger" id="error-AnioCreacion"></div>
            </div>

            <div class="col-12">
                <label class="form-label text-white">Descripción</label>
                <textarea class="form-control" id="Descripcion" name="Descripcion" rows="3">{{ old('Descripcion', $lugar->Descripcion) }}</textarea>
                <div class="text-danger" id="error-Descripcion"></div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-white">Accesibilidad</label>
                <select class="form-control" id="Accesibilidad" name="Accesibilidad">
                    <option value="">Seleccione una opción</option>
                    <option value="Fácil" {{ $lugar->Accesibilidad == 'Fácil' ? 'selected' : '' }}>Fácil</option>
                    <option value="Difícil" {{ $lugar->Accesibilidad == 'Difícil' ? 'selected' : '' }}>Difícil</option>
                </select>
                <div class="text-danger" id="error-Accesibilidad"></div>

                <div class="mt-2">
                    <label class="form-label text-white">Latitud</label>
                    <input type="text" class="form-control mb-2" id="Latitud" name="Latitud"
                           value="{{ old('Latitud', $lugar->Latitud) }}" readonly>

                    <label class="form-label text-white">Longitud</label>
                    <input type="text" class="form-control" id="Longitud" name="Longitud"
                           value="{{ old('Longitud', $lugar->Longitud) }}" readonly>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-white">Ubicación en el mapa</label>
                <div id="mapa_lugar" style="border:1px solid black; height:250px; border-radius:8px;"></div>
            </div>

        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="{{ route('lugarest.index') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </form>
</div>

<!-- Primero cargar jQuery y jQuery Validation -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/additional-methods.min.js"></script>

<!-- Luego el script de Google Maps -->
<script>
function initMap() {
    const latLngInicial = new google.maps.LatLng(
        {{ $lugar->Latitud ?? -0.9374805 }},
        {{ $lugar->Longitud ?? -78.6161327 }}
    );

    const mapa = new google.maps.Map(document.getElementById('mapa_lugar'), {
        center: latLngInicial,
        zoom: 15
    });

    const marcador = new google.maps.Marker({
        position: latLngInicial,
        map: mapa,
        draggable: true,
        title: "Seleccione la ubicación del lugar"
    });

    google.maps.event.addListener(marcador, 'dragend', function() {
        document.getElementById('Latitud').value = this.getPosition().lat();
        document.getElementById('Longitud').value = this.getPosition().lng();
    });
}
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAr9nkZqo-8r4BIwIBe09aHs9oYSGqDJwY&callback=initMap"></script>

<!-- Finalmente la validación -->
<script>
$(document).ready(function() {
    // Método personalizado para letras y espacios
    $.validator.addMethod("soloLetras", function(value, element) {
        return this.optional(element) || /^[a-zA-ZÀ-ÿñÑ\s]+$/.test(value);
    }, "Solo se permiten letras y espacios.");
    
    // Validar que sea un año válido
    $.validator.addMethod("anioValido", function(value, element) {
        if (!value) return true;
        const anio = parseInt(value);
        return anio >= 1000 && anio <= new Date().getFullYear();
    }, "Ingrese un año válido.");

    // Configuración de validación
    $("#frm_lugar_edit").validate({
        ignore: [],
        rules: {
            NombreLugar: {
                required: true,
                minlength: 5,
                maxlength: 40,
                soloLetras: true
            },
            IdProvi: { 
                required: true 
            },
            IdTipoA: { 
                required: true 
            },
            Latitud: { 
                required: true, 
                number: true 
            },
            Longitud: { 
                required: true, 
                number: true 
            },
            Descripcion: {
                required: true,
                minlength: 10,
                maxlength: 300
            },
            AnioCreacion: {
                required: true,
                digits: true,
                minlength: 4,
                maxlength: 4,
                anioValido: true
            },
            Accesibilidad: { 
                required: true 
            }
        },
        messages: {
            NombreLugar: {
                required: "Este campo es obligatorio.",
                minlength: "Debe tener al menos 5 caracteres.",
                maxlength: "Máximo 40 caracteres.",
                soloLetras: "Solo se permiten letras y espacios."
            },
            IdProvi: { 
                required: "Seleccione una provincia." 
            },
            IdTipoA: { 
                required: "Seleccione un tipo de atracción." 
            },
            Latitud: { 
                required: "Ingrese la latitud.",
                number: "Debe ser un número válido."
            },
            Longitud: { 
                required: "Ingrese la longitud.",
                number: "Debe ser un número válido."
            },
            Descripcion: {
                required: "Ingrese una descripción.",
                minlength: "Debe tener al menos 10 caracteres.",
                maxlength: "Máximo 300 caracteres."
            },
            AnioCreacion: {
                required: "Ingrese el año de creación.",
                digits: "Solo se permiten números.",
                minlength: "Debe tener 4 dígitos.",
                maxlength: "Debe tener 4 dígitos."
            },
            Accesibilidad: { 
                required: "Seleccione una opción." 
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
        },
        invalidHandler: function(event, validator) {
            console.log("Formulario inválido");
            console.log(validator.errorList);
        }
    });

    // Para debug: mostrar en consola si jQuery Validation está funcionando
    console.log("jQuery Validation cargado");
    console.log("Formulario configurado:", $("#frm_lugar_edit").validate().settings);
});
</script>

@endsection