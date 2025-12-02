<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provincia;
use App\Models\LugaresT; 
class ProvinciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $provincias = Provincia::all();
        return view('provincias.index', compact('provincias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('provincias.nuevo');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $datos = [
            'Nombre' => $request->Nombre
        ];

        Provincia::create($datos);

        return redirect()->route('provincias.index')
                         ->with('message', 'Provincia creada exitosamente');
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
        $provincia = Provincia::findOrFail($id);
        return view('provincias.editar', compact('provincia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $provincia = Provincia::findOrFail($id);

        $provincia->update([
            'Nombre' => $request->Nombre
        ]);

        return redirect()->route('provincias.index')
                         ->with('success', 'Provincia actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $provincia = Provincia::find($id);

        if ($provincia->lugares()->exists()) {
            return back()->with('error', 'No se puede eliminar esta provincia porque tiene lugares turísticos asociados.');
        }

        $provincia->delete();
        return back()->with('success', 'Provincia eliminada correctamente.');
    }
}
