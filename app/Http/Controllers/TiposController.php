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
        $tipos = Tipos::all();
        return view('tipos.index', compact('tipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        $tiposExistentes = Tipos::all(['Nombre']);
        return view('tipos.nuevo', compact('tiposExistentes'));
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
                'max:30',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/',
                'unique:tipos,Nombre'
            ]
        ], [
            'Nombre.required' => 'El nombre es obligatorio',
            'Nombre.min' => 'El nombre debe tener al menos 4 caracteres',
            'Nombre.max' => 'El nombre no puede superar los 30 caracteres',
            'Nombre.regex' => 'Solo se permiten letras y espacios',
            'Nombre.unique' => 'Este tipo de atracción ya existe'
        ]);

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
        $tipo = Tipos::findOrFail($id);
        // Pasar también los tipos existentes para validación de duplicados (excluyendo el actual)
        $tiposExistentes = Tipos::where('id', '!=', $id)->get(['Nombre']);
        return view('tipos.editar', compact('tipo', 'tiposExistentes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tipo = Tipos::findOrFail($id);
        
        $request->validate([
            'Nombre' => [
                'required',
                'min:4',
                'max:30',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/',
                'unique:tipos,Nombre,' . $id
            ]
        ], [
            'Nombre.required' => 'El nombre es obligatorio',
            'Nombre.min' => 'El nombre debe tener al menos 4 caracteres',
            'Nombre.max' => 'El nombre no puede superar los 30 caracteres',
            'Nombre.regex' => 'Solo se permiten letras y espacios',
            'Nombre.unique' => 'Este tipo de atracción ya existe'
        ]);

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


        if ($tipo->lugares()->exists()) {
            return back()->with('error', 'No se puede eliminar este tipo porque tiene lugares asociados.');
        }

        $tipo->delete();
        return back()->with('success', 'Tipo eliminado correctamente.');
    }

    
    public function verificar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string'
        ]);
        
        $existe = Tipos::whereRaw('LOWER(Nombre) = ?', [strtolower($request->nombre)])->exists();
        
        return response()->json([
            'disponible' => !$existe
        ]);
    }


    public function verificarEdicion(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string'
        ]);
        
        $existe = Tipos::where('id', '!=', $id)
                      ->whereRaw('LOWER(Nombre) = ?', [strtolower($request->nombre)])
                      ->exists();
        
        return response()->json([
            'disponible' => !$existe
        ]);
    }
}