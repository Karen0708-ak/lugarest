<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LugaresT;
use App\Models\Provincia;
use App\Models\Tipos;
class LugaresTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $lugares = LugaresT::with(['provincia', 'tipo'])->get();
        return view('lugarest.index', compact('lugares'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $provincias = Provincia::all();
        $tipos = Tipos::all();
        return view('lugarest.nuevo', compact('provincias', 'tipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $datos = $request->only([
            'NombreLugar', 'IdProvi', 'IdTipoA',
            'Latitud', 'Longitud', 'Descripcion',
            'AnioCreacion', 'Accesibilidad'
        ]);

        LugaresT::create($datos);

        return redirect()->route('lugarest.index')
                         ->with('message', 'Lugar turístico creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $lugar = LugaresT::findOrFail($id);
        $provincias = Provincia::all();
        $tipos = Tipos::all();
        return view('lugarest.editar', compact('lugar', 'provincias', 'tipos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $lugar = LugaresT::findOrFail($id);

        $lugar->update($request->only([
            'NombreLugar', 'IdProvi', 'IdTipoA',
            'Latitud', 'Longitud', 'Descripcion',
            'AnioCreacion', 'Accesibilidad'
        ]));

        return redirect()->route('lugarest.index')
                         ->with('success', 'Lugar turístico actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $lugar = LugaresT::findOrFail($id);
        $lugar->delete();

        return redirect()->route('lugarest.index')
                         ->with('success', 'Lugar turístico eliminado correctamente');
    }
}
