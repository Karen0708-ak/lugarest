@extends('layouts.travelix')

@section('content')
<div class="container my-5" style="background: rgba(141, 79, 255, 0.16); padding:30px; border-radius:12px; backdrop-filter: blur(3px);">
    <h1 class="text-center mb-4" style="color:white;">Editar Lugar Turístico</h1>

    <form action="{{ route('lugarest.update', $lugar->IdLugar) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3">

            <div class="col-md-6">
                <label for="NombreLugar" class="form-label text-white">Nombre del Lugar</label>
                <input type="text" class="form-control" id="NombreLugar" name="NombreLugar" value="{{ old('NombreLugar', $lugar->NombreLugar) }}" required>
            </div>

            <div class="col-md-6">
                <label for="IdProvi" class="form-label text-white">Provincia</label>
                <select class="form-control" id="IdProvi" name="IdProvi" required>
                    <option value="">Seleccione una provincia</option>
                    @foreach($provincias as $provincia)
                        <option value="{{ $provincia->IdProvi }}" {{ $lugar->IdProvi == $provincia->IdProvi ? 'selected' : '' }}>
                            {{ $provincia->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="IdTipoA" class="form-label text-white">Tipo de Atracción</label>
                <select class="form-control" id="IdTipoA" name="IdTipoA" required>
                    <option value="">Seleccione un tipo</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->IdTipoA }}" {{ $lugar->IdTipoA == $tipo->IdTipoA ? 'selected' : '' }}>
                            {{ $tipo->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="AnioCreacion" class="form-label text-white">Año de Creación</label>
                <input type="number" class="form-control" id="AnioCreacion" name="AnioCreacion" value="{{ old('AnioCreacion', $lugar->AnioCreacion) }}">
            </div>

            <div class="col-12">
                <label for="Descripcion" class="form-label text-white">Descripción</label>
                <textarea class="form-control" id="Descripcion" name="Descripcion" rows="3">{{ old('Descripcion', $lugar->Descripcion) }}</textarea>
            </div>

            <div class="col-md-6">
                <label for="Accesibilidad" class="form-label text-white">Accesibilidad</label>
                <input type="text" class="form-control" id="Accesibilidad" name="Accesibilidad" value="{{ old('Accesibilidad', $lugar->Accesibilidad) }}">
                <div class="mt-2">
                    <label for="Latitud" class="form-label text-white">Latitud</label>
                    <input type="text" class="form-control mb-2" id="Latitud" name="Latitud" value="{{ old('Latitud', $lugar->Latitud) }}" readonly>
                    <label for="Longitud" class="form-label text-white">Longitud</label>
                    <input type="text" class="form-control" id="Longitud" name="Longitud" value="{{ old('Longitud', $lugar->Longitud) }}" readonly>
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

<script>
function initMap() {
    const latLngInicial = new google.maps.LatLng({{ $lugar->Latitud ?? -0.9374805 }}, {{ $lugar->Longitud ?? -78.6161327 }});
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
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAr9nkZqo-8r4BIwIBe09aHs9oYSGqDJwY&callback=initMap">
    </script>
@endsection
