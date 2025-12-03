@extends('layouts.travelix')

@section('content')
<div class="container my-5" style="background: rgba(141, 79, 255, 0.16); padding:30px; border-radius:12px; backdrop-filter: blur(3px);">
    <h1 class="text-center mb-4" style="color:white;">Registrar Nuevo Lugar Turístico</h1>

    <form id="frm_lugar" action="{{ route('lugarest.store') }}" method="POST">
        @csrf
        <div class="row g-3">

            <div class="col-md-6">
                <label for="NombreLugar" class="form-label text-white">Nombre del Lugar</label>
                <input type="text" class="form-control" id="NombreLugar" name="NombreLugar" value="{{ old('NombreLugar') }}">
                <div class="text-danger" id="error-NombreLugar"></div>
            </div>

            <div class="col-md-6">
                <label for="IdProvi" class="form-label text-white">Provincia</label>
                <select class="form-control" id="IdProvi" name="IdProvi">
                    <option value="">Seleccione una provincia</option>
                    @foreach($provincias as $provincia)
                        <option value="{{ $provincia->Id }}" {{ old('IdProvi') == $provincia->Id ? 'selected' : '' }}>
                            {{ $provincia->Nombre }}
                        </option>
                    @endforeach
                </select>
                <div class="text-danger" id="error-IdProvi"></div>
            </div>

            <div class="col-md-6">
                <label for="IdTipoA" class="form-label text-white">Tipo de Atracción</label>
                <select class="form-control" id="IdTipoA" name="IdTipoA">
                    <option value="">Seleccione un tipo</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->Id }}" {{ old('IdTipoA') == $tipo->Id ? 'selected' : '' }}>
                            {{ $tipo->Nombre }}
                        </option>
                    @endforeach
                </select>
                <div class="text-danger" id="error-IdTipoA"></div>
            </div>

            <div class="col-md-6">
                <label for="AnioCreacion" class="form-label text-white">Año de Creación</label>
                <input type="number" class="form-control" id="AnioCreacion" name="AnioCreacion" value="{{ old('AnioCreacion') }}">
                <div class="text-danger" id="error-AnioCreacion"></div>
            </div>

            <div class="col-12">
                <label for="Descripcion" class="form-label text-white">Descripción</label>
                <textarea class="form-control" id="Descripcion" name="Descripcion" rows="3">{{ old('Descripcion') }}</textarea>
                <div class="text-danger" id="error-Descripcion"></div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-white">Accesibilidad</label>
                <select class="form-control" id="Accesibilidad" name="Accesibilidad">
                    <option value="">Seleccione una opción</option>
                    <option value="Fácil" {{ old('Accesibilidad') == 'Fácil' ? 'selected' : '' }}>Fácil</option>
                    <option value="Difícil" {{ old('Accesibilidad') == 'Difícil' ? 'selected' : '' }}>Difícil</option>
                </select>
                <div class="text-danger" id="error-Accesibilidad"></div>
                
                <div class="mt-2">
                    <label for="Latitud" class="form-label text-white">Latitud</label>
                    <input type="text" class="form-control mb-2" id="Latitud" name="Latitud" value="{{ old('Latitud') }}" readonly>
                    <div class="text-danger" id="error-Latitud"></div>
                    
                    <label for="Longitud" class="form-label text-white">Longitud</label>
                    <input type="text" class="form-control" id="Longitud" name="Longitud" value="{{ old('Longitud') }}" readonly>
                    <div class="text-danger" id="error-Longitud"></div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-white">Ubicación en el mapa</label>
                <div id="mapa_lugar" style="border:1px solid black; height:250px; border-radius:8px;"></div>
            </div>

        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('lugarest.index') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </form>
</div>

<!-- Cargar jQuery y jQuery Validation primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/additional-methods.min.js"></script>

<script>
function initMap() {
    const latLngInicial = new google.maps.LatLng(-0.9374805, -78.6161327);
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

    // Establecer valores iniciales si hay valores antiguos
    @if(old('Latitud') && old('Longitud'))
        const lat = {{ old('Latitud', '-0.9374805') }};
        const lng = {{ old('Longitud', '-78.6161327') }};
        const nuevaPosicion = new google.maps.LatLng(lat, lng);
        marcador.setPosition(nuevaPosicion);
        mapa.setCenter(nuevaPosicion);
        document.getElementById('Latitud').value = lat;
        document.getElementById('Longitud').value = lng;
    @endif

    google.maps.event.addListener(marcador, 'dragend', function() {
        const lat = this.getPosition().lat();
        const lng = this.getPosition().lng();
        document.getElementById('Latitud').value = lat;
        document.getElementById('Longitud').value = lng;
        
        // Limpiar errores cuando se mueve el marcador
        $('#error-Latitud').html('');
        $('#error-Longitud').html('');
        $('#Latitud').removeClass('is-invalid');
        $('#Longitud').removeClass('is-invalid');
    });
}
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAr9nkZqo-8r4BIwIBe09aHs9oYSGqDJwY&callback=initMap">
</script>

<script>
$(document).ready(function() {
    // Método personalizado para letras y espacios
    $.validator.addMethod("soloLetras", function(value, element) {
        return this.optional(element) || /^[a-zA-ZÀ-ÿñÑ\s]+$/.test(value);
    }, "Solo se permiten letras y espacios.");
    
    // Validar que sea un año válido entre 1000 y 2025
    $.validator.addMethod("anioValido", function(value, element) {
        if (!value) return true;
        const anio = parseInt(value);
        return anio >= 1000 && anio <= 2025;
    }, "Ingrese un año válido (entre 1000 y 2025).");

    // Configuración de validación
    $("#frm_lugar").validate({
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
                anioValido: true,
                min: 1000,
                max: 2025
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
                required: "Seleccione una ubicación en el mapa arrastrando el marcador.",
                number: "Debe ser un número válido."
            },
            Longitud: {
                required: "Seleccione una ubicación en el mapa arrastrando el marcador.",
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
                maxlength: "Debe tener 4 dígitos.",
                min: "El año mínimo permitido es 1000.",
                max: "El año máximo permitido es 2025."
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

    // Validar inmediatamente cuando se arrastra el marcador
    $('#Latitud, #Longitud').on('change', function() {
        $(this).valid();
    });

    // Para debug: mostrar en consola si jQuery Validation está funcionando
    console.log("jQuery Validation cargado");
    console.log("Formulario configurado:", $("#frm_lugar").validate().settings);
});
</script>

@endsection