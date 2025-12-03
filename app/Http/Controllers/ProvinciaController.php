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
        $provincias = Provincia::all();
        return view('provincias.index', compact('provincias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinciasExistentes = Provincia::all(['Nombre']);
        return view('provincias.nuevo', compact('provinciasExistentes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => [
                'required',
                'min:4',
                'max:25',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/',
                'unique:provincias,Nombre'
            ]
        ], [
            'Nombre.required' => 'El nombre es obligatorio',
            'Nombre.min' => 'El nombre debe tener al menos 4 caracteres',
            'Nombre.max' => 'El nombre no puede superar los 25 caracteres',
            'Nombre.regex' => 'Solo se permiten letras y espacios',
            'Nombre.unique' => 'Esta provincia ya existe'
        ]);

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
        $provincia = Provincia::findOrFail($id);
        $provinciasExistentes = Provincia::where('id', '!=', $id)->get(['Nombre']);
        return view('provincias.editar', compact('provincia', 'provinciasExistentes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $provincia = Provincia::findOrFail($id);
        
        $request->validate([
            'Nombre' => [
                'required',
                'min:4',
                'max:25',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/',
                'unique:provincias,Nombre,' . $id
            ]
        ], [
            'Nombre.required' => 'El nombre es obligatorio',
            'Nombre.min' => 'El nombre debe tener al menos 4 caracteres',
            'Nombre.max' => 'El nombre no puede superar los 25 caracteres',
            'Nombre.regex' => 'Solo se permiten letras y espacios',
            'Nombre.unique' => 'Esta provincia ya existe'
        ]);

        $provincia->update([
            'Nombre' => $request->Nombre
        ]);

        return redirect()->route('provincias.index')
                         ->with('success', 'Provincia actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $provincia = Provincia::find($id);

        if (!$provincia) {
            return back()->with('error', 'La provincia no existe.');
        }


        if ($provincia->lugares()->exists()) {
            return back()->with('error', 'No se puede eliminar esta provincia porque tiene lugares turísticos asociados.');
        }

        $provincia->delete();
        return back()->with('success', 'Provincia eliminada correctamente.');
    }

    public function verificar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string'
        ]);
        
        $existe = Provincia::whereRaw('LOWER(Nombre) = ?', [strtolower($request->nombre)])->exists();
        
        return response()->json([
            'disponible' => !$existe
        ]);
    }

    public function verificarEdicion(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string'
        ]);
        
        $existe = Provincia::where('id', '!=', $id)
                          ->whereRaw('LOWER(Nombre) = ?', [strtolower($request->nombre)])
                          ->exists();
        
        return response()->json([
            'disponible' => !$existe
        ]);
    }
}