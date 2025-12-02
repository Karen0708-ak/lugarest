@extends('layouts.travelix')

@section('content')
<div class="container my-5" style="background: rgba(141, 79, 255, 0.16); padding:30px; border-radius:12px; backdrop-filter: blur(3px);">
    <h1 class="text-center mb-4" style="color:white;">Registrar Nuevo Lugar Turístico</h1>

    <form id="frm_lugar" action="{{ route('lugarest.store') }}" method="POST">
        @csrf
        <div class="row g-3">

            <div class="col-md-6">
                <label for="NombreLugar" class="form-label text-white">Nombre del Lugar</label>
                <input type="text" class="form-control" id="NombreLugar" name="NombreLugar" >
            </div>

            <div class="col-md-6">
                <label for="IdProvi" class="form-label text-white">Provincia</label>
                <select class="form-control" id="IdProvi" name="IdProvi">
                    <option value="">Seleccione una provincia</option>
                    @foreach($provincias as $provincia)
                        <option value="{{ $provincia->Id }}">{{ $provincia->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="IdTipoA" class="form-label text-white">Tipo de Atracción</label>
                <select class="form-control" id="IdTipoA" name="IdTipoA">
                    <option value="">Seleccione un tipo</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->Id }}">{{ $tipo->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="AnioCreacion" class="form-label text-white">Año de Creación</label>
                <input type="number" class="form-control" id="AnioCreacion" name="AnioCreacion">
                <br>
            </div>

            <div class="col-12">
                <label for="Descripcion" class="form-label text-white">Descripción</label>
                <textarea class="form-control" id="Descripcion" name="Descripcion" rows="3"></textarea>
            </div>

            <div class="col-md-6">
            <label  class="form-label text-white">Accesibilidad</label>
            <br>
                <select class="form-control" id="Accesibilidad" name="Accesibilidad" >
                    <option value="">Seleccione una opción</option>
                    <option value="Fácil">Fácil</option>
                    <option value="Difícil">Difícil</option>
                </select>
            <br>
                <div class="mt-2">
                    <label for="Latitud" class="form-label text-white">Latitud</label>
                    <input type="text" class="form-control mb-2" id="Latitud" name="Latitud" readonly>
                    <label for="Longitud" class="form-label text-white">Longitud</label>
                    <input type="text" class="form-control" id="Longitud" name="Longitud" readonly>
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

    google.maps.event.addListener(marcador, 'dragend', function() {
        document.getElementById('Latitud').value = this.getPosition().lat();
        document.getElementById('Longitud').value = this.getPosition().lng();
    });
}
</script>

<script>


$("#frm_lugar").validate({
    ignore: [],
    rules: {
        NombreLugar: {
            required: true,
            minlength: 5,
            maxlength: 40,
            pattern: "^[a-zA-ZÀ-ÿ\u00f1\u00d1 ]+$"
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
            max: 2025,  
            min: 1000     
        },
        Accesibilidad: {
            required: true
        }
    },
    messages: {
        NombreLugar: {
            required: "Este campo es obligatorio.",
            minlength: "Debe tener al menos 5 caracteres.",
            pattern: "Solo se permiten letras y espacios."
        },
        IdProvi: {
            required: "Seleccione una provincia."
        },
        IdTipoA: {
            required: "Seleccione un tipo de atracción."
        },
        Latitud: {
            required: "Ingrese la latitud."
        },
        Longitud: {
            required: "Ingrese la longitud."
        },
        Descripcion: {
            required: "Ingrese una descripción."
        },
        AnioCreacion: {
            required: "Ingrese el año de creación.",
            max: "El año no puede ser mayor a 2025.",
            min: "Ingrese un año válido (mínimo 1000)."
        },
        Accesibilidad: {
            required: "Seleccione una opción."
        }
    }
});

</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAr9nkZqo-8r4BIwIBe09aHs9oYSGqDJwY&callback=initMap">
</script>

@endsection
