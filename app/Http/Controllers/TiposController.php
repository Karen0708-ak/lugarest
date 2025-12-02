<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tipos;
use App\Models\LugaresT; 
class TiposController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tipos = Tipos::all();
        return view('tipos.index', compact('tipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('tipos.nuevo');
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

        Tipos::create($datos);

        return redirect()->route('tipos.index')
                         ->with('message', 'Tipo creado exitosamente');
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
        $tipo = Tipos::findOrFail($id);
        return view('tipos.editar', compact('tipo'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $tipo = Tipos::findOrFail($id);

        $tipo->update([
            'Nombre' => $request->Nombre
        ]);

        return redirect()->route('tipos.index')
                         ->with('success', 'Tipo actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tipo = Tipos::find($id);

        if (!$tipo) {
            return back()->with('error', 'El tipo no existe.');
        }

        // VALIDA QUE TENGA LUGARES
        if ($tipo->lugares()->exists()) {
            return back()->with('error', 'No se puede eliminar este tipo porque tiene lugares asociados.');
        }

        $tipo->delete();
        return back()->with('success', 'Tipo eliminado correctamente.');
    }

}
